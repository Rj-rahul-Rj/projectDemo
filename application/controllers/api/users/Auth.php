<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 require APPPATH . 'core/API_Controller.php';
//  require APPPATH.'librariesCiqrcode';
 
/**
    Created By Rahul joshi (28-07-2020)
 * Controller for all Driver relatd  Operation like signin , signUp in two steps , forgot password,Otp verify,changePassword, resendForgotOtp etc..
 */ 

class Auth extends API_Controller
{
  function __construct()
  {
    parent::__construct();
      $this->load->model('api/AuthModal', 'Auth');   
      $this->load->helper('email');
      $this->load->helper('fileuploading');
	  $this->load->library('encryption');
	  $this->load->library('ciqrcode');
  }

  function index()
  {
    echo "Not good";
  }

	
	 public function createUser(){
		 $pera=$this->PerameterValidation(array('name','mobile_no','email','password','blood_group','health_insurance','insurance_cmpy','insurance_agent','relative_no'));
		 $data  = $this->emptyValidation(array('name','mobile_no','email','password','blood_group','health_insurance','relative_no'));
		 $qr_img = 'qrcode-'.date('m-d-Y-H-i-s').'.png';
		 $file = FCPATH.'uploads/qrcodes/';
		$insert['name'] = $this->encryption->encrypt($pera['name']);
		$insert['mobile_no'] = $this->encryption->encrypt($pera['mobile_no']);
		$insert['email'] = $pera['email'];
		$insert['password'] = md5($pera['password']);
		$insert['blood_group'] = $this->encryption->encrypt($pera['blood_group']);
		$insert['health_insurance'] =$pera['health_insurance'];
		$insert['insurance_cmpy'] = $pera['health_insurance']==2?$this->encryption->encrypt($pera['insurance_cmpy']):'';
		$insert['insurance_agent'] = $pera['health_insurance']==2?$this->encryption->encrypt($pera['insurance_agent']):'';
		$insert['relative_no'] = $this->encryption->encrypt($pera['relative_no']);
		$query = $this->db->select('name')
				  ->from('user')
				  ->where('email',$pera['email'])
				  ->get();
			$check =$query->row();
		if($check){
			$res = array('status'=>'400','message'=>'email already exist, please try again with different eamil!');
			$this->response($res);
		}else{
			$result = $this->Auth->AddData('user',$insert);
			
			 $payload = base64_encode(json_encode(array('user_id'=>$this->db->insert_id(),'user_type'=>2)));
				$token   = $this->GetToken($payload);
				$this->addToken($token);
				header('authtoken:'.$token);
		}
		
		$param['image'] = base_url().'uploads/qrcodes/'.$result.'.png';
		$data['user_type'] = 2;
		if($result){
			$file_name1 = $result.'.png';
			$file_name = $file.$file_name1;
			$img=base_url().'uploads/qrcodes/'.$file_name1;
			\QRcode::png($param['image'], $file_name);
			
			$res = array('status'=>'200','message'=>'You are Successfully Register','qrcode'=>$param['image'],'record'=>$data);
		}else{
			$res = array('status'=>'400','message'=>'registation failed , please try again!');
		}
		$this->response($res);
	}
	public function loginUser(){
		{
			$pera=$this->PerameterValidation(array('email','password'));
		
			$data  = $this->emptyValidation(array('email','password'));  
		  	
			
			$query = $this->db->select('id,user_type,name,mobile_no,blood_group,relative_no')
				  ->from('user')
				  ->where('email',$pera['email'])
				  ->where('password', md5($pera['password']))
				  ->get();
			$check =$query->row();
			if($check)
			{
				if($check->user_type==2){
				$responce["id"]=$check->id;
				$responce["name"]= $this->encryption->decrypt($check->name);
				$responce["email"]=$pera['email'];
				$responce["mobile_no"]=$this->encryption->decrypt($check->mobile_no);
				$responce["blood_group"]=$this->encryption->decrypt($check->blood_group);
				$responce["relative_no"]=explode(',',$this->encryption->decrypt($check->relative_no));
				$responce["user_type"]=$check->user_type;
				$responce["qrcode"]= base_url().'uploads/qrcodes/'.$check->id.'.png';
				}else{
				$responce["id"]=$check->id;
				$responce["name"]= $check->name;
				$responce["email"]=$pera['email'];
				$responce["user_type"]=$check->user_type;
				}
			   $payload = base64_encode(json_encode(array('user_id'=>$check->id,'user_type'=>$check->user_type)));
				$token   = $this->GetToken($payload);
				$this->addToken($token);
				header('authtoken:'.$token);
		
			   $res=array('status'=>'200','message'=>'Success','record'=>$responce);
			
			}
			else
			{
			  $res = array('status'=>'400','message'=>'Incorrect email/phone OR password');
			}
			$this->response($res);
		
		  }
	}
	
	public function downloadCount(){
		$pera=$this->PerameterValidation(array('count'));
		
		$data  = $this->emptyValidation(array('count'));
		$user = $this->Auth->GetData('user','count','id="'.$this->login_user.'"');
		$update['count'] = $user->count + $pera['count'];
		$where = 'id="'.$this->login_user.'"';
		$result =$this->Auth->updateData('user',$update,$where); 
		if($result){
			$res=array('status'=>'200','message'=>'Download Succesfully');

		}
		else
		{
		  $res = array('status'=>'400','message'=>'IError in download');
		}
		$this->response($res);
	}

	public function userList(){
		$coloumb = 'name,mobile_no,email,blood_group,relative_no,health_insurance,insurance_cmpy,insurance_agent,count';
		$where = 'user_type=2';
		$userData = $this->Auth->GetGroupData('user',$coloumb,$where);
		$result =[];
		foreach($userData as $record){
			// print_r($this->encryption->decrypt('ed7ad28b5c4ebfec405dfe94c9d618'));die;
			$data['name'] =$this->encryption->decrypt($record['name']);
			$data['email'] =$record['email'];
			$data['mobile_no'] =$this->encryption->decrypt($record['mobile_no']);
			$data['blood_group'] =$this->encryption->decrypt($record['blood_group']);
			$data['relative_no'] =explode(',',$this->encryption->decrypt($record['relative_no']));
			$data['health_insurance'] =$record['health_insurance']==2?'YES':'NO';
			$data['insurance_cmpy'] =$record['health_insurance']==2?$this->encryption->decrypt($record['insurance_cmpy']):'N/A';
			$data['insurance_agent'] =$record['health_insurance']==2?$this->encryption->decrypt($record['insurance_agent']):'N/A';
			$data['download_count'] =$record['count'];
			$result [] =$data;
		}
		if($result){
			$res=array('status'=>'200','message'=>'user list', 'data'=>$result);

		}
		else
		{
		  $res = array('status'=>'400','message'=>'Error in List');
		}
		$this->response($res);
	}

}
?>