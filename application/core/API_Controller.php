<?php
/**
*   
*/
class API_Controller  extends CI_Controller
{
  public $request;
  public $login_user;
  public $login_user_type;
  function __construct()
  {
    parent::__construct();
    $this->load->model("RestModal");
    $this->config->load('rest');
    $this->AllowHeader();
    // get incommeng perameter from API
    $this->request=json_decode(file_get_contents("php://input"),true);

    // check Authontication 
    $this->Authorization();
    
  }

  function AllowHeader() // Cross site scripting auth
  {
	
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods:PUT,GET,POST,UPDATE,DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,authtoken,app_key,app_secret");
    header("Access-Control-Expose-Headers: Origin, X-Requested-With, Content-Type, Accept,authtoken");
  }

  // Authonticate every request
  function Authorization()
  {
    $headers = getallheaders();
    //print_r( $headers);
    // print_r( $this->request);
     //die();
    if(array_key_exists('app_key', $headers) && array_key_exists('app_secret', $headers))
    {
		
      $this->LoginAuthorization($headers);
    }
    else
    { 
	
      if(array_key_exists('authtoken', $headers))
      {
            $token =$headers['authtoken'];
            $this->verifyToken($token);
      }
      else
      {
        $res = array('status'=>'402','message'=>'Header is missing');
        $this->response($res);
      }
    }
  }

  // verify token is valid or not
  function verifyToken($token)
  {
        if($token =='development')
        {
         $this->login_user      = '1';
         $this->login_user_type = '1';
          return true;
        }

        $payload    = explode('.', $token);
        $userDetail = json_decode(base64_decode(end($payload)),true);
        $comman_id    = $userDetail['user_id'];

         $this->login_user      = $userDetail['user_id'];
         $this->login_user_type = $userDetail['user_type'];

        $where = array('user_id'=>$comman_id,'token'=>$token);
        // $check = $this->RestModal->checkToken($where);
        if($comman_id)
        {
          // $expire = $check['expire_time'];
          // if(now>$expire)
          // {
              // $diffrece = (($expire - now) / 60);
              // if($diffrece<=autoRefresh)
              // {
              //   $this->AutorefreshToken($token);
              // }
              // else
              // {
                // $this->response($res);
                // // }
                $res=array('status'=>'408','message'=>'Session expired.');
          // }
          // else
          // {
          //      // $this->AutorefreshToken($token);        
          //   return true;
          // }
          
        }
        else
        {
         $res=array('status'=>'401','message'=>'Unauthorized Access');
          $this->response($res); 
        } 
  }

  // Authonication if user don't have token like login,signup...
  function LoginAuthorization($headers)
  {
     $app_key     = $this->config->item('app_key');
     $app_secret  = $this->config->item('app_secret');
     $allowMethod = $this->config->item('allowed_method');
     $checkURI  = explode('/', $_SERVER['REQUEST_URI']);  // get methods 
     if(!in_array(end($checkURI), $allowMethod))
     {
        $res = array('status'=>'405','message'=>'Method Not Allowed');
        $this->response($res);
     }

     if(array_key_exists('app_key', $headers) && array_key_exists('app_secret', $headers))
     {
         if($headers['app_key'] ==$app_key && $headers['app_secret'] ==$app_secret)
         {
            return true;
         }
         else
         {
          $res = array('status'=>'401','message'=>'Unauthorized Access');
          $this->response($res);      
         }
     }
     else
     {
       $res = array('status'=>'402','message'=>'Header is missing');
        $this->response($res);
     }

  }

   function checkToken($token)
    {
        $payload = explode('.',$token);
        $userDetail = json_decode(base64_decode(end($payload)),true);
        $user_id    = $userDetail['user_id']; 
        $where = array('token'=>$token,'user_id'=>$user_id);
        $check = $this->RestModal->checkToken($where);
        if($check)
        {
           $expire  = $check['expire_time'];
           if(now>$expire)
           {
                 $res = array('status'=>'408','message'=>'Token expire.');
                 $this->response($res);
           }
           else
           {
            return true;
           }
        }
        else
        {
          $res = array('status'=>'401','message'=>'Unauthorized');
          $this->response($res);
        }
       
    }
    function GetToken($payload)
    {
        $token = openssl_random_pseudo_bytes(35).'__'.uniqid().rand(0,50);
        $token = bin2hex($token);
        $token = str_replace('._', 'TO', $token);
        return  $token.'.'.$payload;
    }
    function addToken($token)
    {
      
        $payload = explode('.',$token);
        $userDetail = json_decode(base64_decode(end($payload)),true);
        // print_r($userDetail);die;
        $user_id    = $userDetail['user_id']; 
        // $check = $this->RestModal->checkToken(array('user_id'=>$user_id));
        // $check = $this->checkToken(array('user_id'=>$user_id));
         $now = date('d-m-Y H:i:s');
        $expiry  = strtotime($now.token_expire);
        if(!$user_id)
        {
          // insert New token
          $insert['user_id']      = $user_id;
          $insert['token']        = $token;
          $insert['created_at']  = time();
          $insert['updated_at']     = time();
          // $this->RestModal->AddToken($insert);
          $this->RestModal->AddData('auth',$insert);
        }
        else
        {
          $update['token']        = $token;
          // $update['expire_time']  = $expiry;
          $update['updated_at']    = time();
          $where['user_id']      = $user_id;
          // $this->RestModal->updateToken($update,$where);
          $this->RestModal->updateData('auth',$update,$where);

        }
    }
    function refreshToken($user_id)
    {
      $payload = base64_encode(json_encode(array('user_id'=>$user_id)));
      $token = $this->GetToken($payload);
      $this->addToken($token);
      header('authtoken:'.$token);
      // http_response_code(205);
    } 

  

  public function PerameterValidation($pera,$custom=array())
  {
	   //print_R($this->request);die('yes');
	   //$this->request =$_POST;
        if(count($custom)>0)
        {
          $this->request = $custom;
        }
        $res     = array();
        $success = array();
        foreach($pera as $perameter)
        {
           if(isset($this->request[$perameter]))
           { 
            $success[$perameter] = ($this->request[$perameter])?$this->request[$perameter]:'';
           }
           else
           {
            $res[] = $perameter.' perameter is missing.';
           }
        }
        if(count($res) == 0)
        {
          return $success;
        }
        else
        {
             $msg['status']  = "400";
             $msg['message'] = "Perameter missing";
             $msg['record'] = $res;
            $this->response($msg);
        }
  }

  // check if mandotary perameter is empty 
  function emptyValidation($pera,$custom=array())
  {
      if(count($custom)>0)
      {
        $this->request = $custom;
      }

      $res = array();
      $success = array();
      foreach($pera as $perameter)
      {
         if(!empty($this->request[$perameter]))
         { 
          $success[$perameter] = ($this->request[$perameter])?$this->request[$perameter]:'';
         }
         else
         {
          $res[] = $perameter.' can not be empty.';
         }
      }
      if(count($res) == 0)
      {
        return $success;
      }
      else
      {
           $msg['status']  = "400";
           $msg['message'] = "All important field are mandatory.";
           $msg['record'] = $res;
          $this->response($msg);
      }
  }

  // send responce to api request in whole API
  public function response($response)
  {
      $status =  200;

      if(!is_array($response)){
        $response  = array('message'=>'Responce is not proper','status'=>'501');
      }
      if(array_key_exists('status', $response))
      {
        $status =  intval($response['status']);
      }
      // http_response_code($status);
      print(json_encode($response));
      die();
  }

 

}

?>

