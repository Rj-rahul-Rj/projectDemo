<?php

class Commande
{
    protected $_montant;
    protected $_transId;
    protected $_methode;
    protected $_payId;
    protected $_buyerName;
    protected $_transStatus;
    protected $_signature;
    protected $_phone;
    protected $_errorMessage;
    protected $_statut;
    protected $_dateCreation;
    protected $_dateModification;
    protected $_datePaiement;

    public function create($inData = [])
    {
    // pos payment for unregistered user
    $posData = str_replace("'", '"', $inData->_cpm_custom);
    $posJson = json_decode(strval($posData), true);
           
    if(isset($posJson['pos']) ==="1")
    {
        $this->update($inData);
        return false;
    }
        //payment_for = 1 (subscription),2(product),3(wallet)
        $CI = &get_instance();

        $CI->db->where('tx_id', $inData->_cpm_trans_id);
        $query = $CI->db->get('payment');
            // echo $query->num_rows(); die();

        if ($query->num_rows() == 0) {
            $finalData = str_replace("'", '"', $inData->_cpm_custom);
            $json = json_decode(strval($finalData), true);
           


            if($json['payment_for']==7)
            {
                $payment_by = $json['payment_to'];
                $payment_to = $json['payment_by'];
            }
            else
            {
                $payment_by = $json['payment_by'];
                $payment_to = $json['payment_to'];
            }
            $payment_arr = [
                'payment_by' => $payment_by,
                'payment_to' => $payment_to,
                'payer_name' => (isset($json['cpm_payer_name']))?$json['cpm_payer_name']:"",
                'refrence_id' => $json['reference_id'],
                'payment_method' => $inData->_payment_method,
                'currency' => $inData->_cpm_currency,
                'currency_sign' => $inData->_cpm_currency,
                'total_amount' => $inData->_cpm_amount,
                'commision' => $json['commission'],
                'tx_id' => $inData->_cpm_trans_id,
                'payment_for' => $json['payment_for'],
                'payment_status' => $inData->_cpm_trans_status,
                'response' => json_encode($inData),
                'added_on' => time(),
                'update_on' => time(),
            ];

            $query = $CI->db->insert('payment', $payment_arr); //for all type of payments
            $payment_id = $CI->db->insert_id();
            
             //for webapp subscription 
            if($json['payment_for']==1 && $inData->_cpm_trans_status =='ACCEPTED')
            {
                if(isset($json['is_web']) && $json['is_web']==1){

                   $this->checkPayment($inData->_cpm_trans_id, $payment_by);
                }
            }

            if ($json['payment_for'] == '3' || $json['payment_for'] == '7') {
             
                // 1=user,2=merchant,
                $CI->db->where(['id'=>$json['payment_by']]);
                $CI->db->select('user_type');
                $CI->db->from('users');
                $usersType = $CI->db->get();
             if($usersType->row()->user_type == 1) 
             {
                $insert['user_id'] = $json['payment_by'];
             }  
             else
             {
                $insert['merchant_id'] = $json['payment_by'];
             }

            $insert['payment_id'] = $payment_id;
            $insert['wallet_amount'] = $json['orginal_amount'];
            $insert['margin '] = $json['commission'];
            $insert['currency'] = $inData->_cpm_currency;
            $insert['payment_status'] = $inData->_cpm_trans_status;
            $insert['added_on'] = time();
            $insert['updated_on'] = time();
            $query = $CI->db->insert('cinetpay_wallet', $insert);

            // update wallet amount in users table
                   if($query)
                    {
                        if($usersType->row()->user_type == 1) 
                        {
                           $CI->db->where(['cinetpay_wallet.user_id'=>$json['payment_by'],'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                        }  
                        else
                        {
                            $CI->db->where(['cinetpay_wallet.merchant_id'=>$json['payment_by'],'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                        }

                        $CI->db->select_sum('wallet_amount');
                        $CI->db->select_sum('margin');
                        $CI->db->from('cinetpay_wallet');
                        $total_amount = $CI->db->get();

                        if($total_amount)
                        {
                            // update wallet amount in users table
                            if($json['payment_for']==7)
                            {

                            $CI->db->where(['id'=>$payment_to]);
                            $CI->db->join('payment', 'users.id = payment.payment_to'); 
                            $update_balance['wallet_balance'] = $total_amount->row()->wallet_amount;
                            $update_balance['update_on'] = time();
                            }
                            else
                            {
                            $CI->db->where(['id'=>$json['payment_by']]);
                            $CI->db->join('payment', 'users.id = payment.payment_by'); 
                            $update_balance['wallet_balance'] = $total_amount->row()->wallet_amount;
                            $update_balance['update_on'] = time();
                            }
                            $query = $CI->db->update('users', $update_balance);
                        }

                    }
            }
            elseif ($json['payment_for'] == '8') {
             
                // 1=user,2=merchant,
                $CI->db->where(['id'=>$json['payment_to']]);
                $CI->db->select('user_type');
                $CI->db->from('users');
                $usersType = $CI->db->get();
             if($usersType->row()->user_type == 1) 
             {
                $insert['user_id'] = $json['payment_to'];
             }  
             else
             {
                $insert['merchant_id'] = $json['payment_to'];
             }

            $insert['payment_id'] = $payment_id;
            $insert['wallet_amount'] = $json['orginal_amount'];
            $insert['margin '] = $json['commission'];
            $insert['currency'] = $inData->_cpm_currency;
            $insert['payment_status'] = $inData->_cpm_trans_status;
            $insert['added_on'] = time();
            $insert['updated_on'] = time();
            $query = $CI->db->insert('cinetpay_wallet', $insert);

            // update wallet amount in users table
                   if($query)
                    {
                        if($usersType->row()->user_type == 1) 
                        {
                           $CI->db->where(['cinetpay_wallet.user_id'=>$json['payment_to'],'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                        }  
                        else
                        {
                            $CI->db->where(['cinetpay_wallet.merchant_id'=>$json['payment_to'],'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                        }

                        $CI->db->select_sum('wallet_amount');
                        $CI->db->select_sum('margin');
                        $CI->db->from('cinetpay_wallet');
                        $total_amount = $CI->db->get();

                        if($total_amount)
                        {
                            // update wallet amount in users table
                            $CI->db->where(['id'=>$json['payment_to']]);
                            $CI->db->join('payment', 'users.id = payment.payment_to'); 
                            $update_balance['wallet_balance'] = $total_amount->row()->wallet_amount;
                            $update_balance['update_on'] = time();
                            $query = $CI->db->update('users', $update_balance);
                        }

                    }
            }
            else
            {

            }
    } 
        else {
        $this->update($inData);
    }

        // Save the line for the first time
    }





    public function update($inData = [])
    {
        $CI = &get_instance();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, 'https://api.cinetpay.com/v1/?method=checkPayStatus');
        $param = [
            "apikey" => cinetPayapikey,
            "cpm_site_id" => $inData->_cpm_site_id,
            "cpm_trans_id" => $inData->_cpm_trans_id,
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));

        // Send request
        $response = curl_exec($curl);
        $curlinfo = curl_getinfo($curl);
        $json = json_decode(strval($response), true);
        
        $payment_arr = [
            'payment_status' => $json['transaction']['cpm_trans_status'],
            'update_on' => time(),
        ];

        $posData = str_replace("'", '"', $json['transaction']['cpm_custom']);
        $posJson = json_decode(strval($posData), true);
        // echo '<pre>'; print_r($posJson); die();
            // if (strpos($posJson['payment_by'], '@') !== false) {
            //     echo 'true'; checking if it is a email
            // }
        // print_r($posJson['payment_by']); 
        // print_r(strpos($posJson['payment_by'], '@')); 
        // die();
        // pos payment 
        // normal payment
        $CI->db->where('tx_id', $json['transaction']['cpm_trans_id']);
        $query = $CI->db->update('payment', $payment_arr);
        if(isset($posJson['pos']) == '1' && strpos($posJson['payment_by'], '@') !== false)
        {
            $CI->db->where('tx_id', $json['transaction']['cpm_trans_id']);
            $query = $CI->db->update('pos_payment', $payment_arr);   
            
        // get updated ID
        $CI->db->where('tx_id', $json['transaction']['cpm_trans_id']);
        $updated_id = $CI->db->get('pos_payment');
        $payment_id = $updated_id->row()->id;
        if (isset($json['transaction']['cpm_trans_status']) && $json['transaction']['cpm_trans_status'] == 'ACCEPTED') 
            {
                // 3 for done payment and 4 = delivered
                $posUpdate_arr = [
                    'payment_status' => '3', 
                    'status' => '4',
                    'update_on' => now,
                ];

            }
            else
            {
                $posUpdate_arr = [
                    'payment_status' => '4', 
                    'update_on' => now,
                ];

            }

            $CI->db->where('id', $posJson['reference_id']);
            $query = $CI->db->update('pos_orders', $posUpdate_arr);
            return;
        }
        elseif(isset($posJson['pos']) == '1' && strpos($posJson['payment_by'], '@') == false)
        {
              if (isset($json['transaction']['cpm_trans_status']) && $json['transaction']['cpm_trans_status'] == 'ACCEPTED') 
            {
                // 3 for done payment and 4 = delivered
                $posUpdate_arr = [
                    'payment_status' => '3', 
                    'status' => '4',
                    'update_on' => now,
                ];

            }
            else
            {
                $posUpdate_arr = [
                    'payment_status' => '4', 
                    'update_on' => now,
                ];

            }

            $CI->db->where('id', $posJson['reference_id']);
            $query = $CI->db->update('orders', $posUpdate_arr);
            return;
        }
        
        
        // get updated ID
        $CI->db->where('tx_id', $json['transaction']['cpm_trans_id']);
        $updated_id = $CI->db->get('payment');
        $payment_id = $updated_id->row()->id;
        // update subscription data in merchant_subscription

        $finalData = str_replace("'", '"', $json['transaction']['cpm_custom']);
        $custom_json = json_decode($finalData);
        // echo "<pre>"; print_r($custom_json); die();
        // checking merchant subscription
        if ($custom_json->payment_for == '1') {
			
		$where = ['tx_id' => $json['transaction']['cpm_trans_id'], 'merchant_id' => $custom_json->payment_by];
        $checkifalreadySucced = $CI->db->get_where('merchant_subscription', $where);
		//echo $CI->db->last_query();die;
		if($checkifalreadySucced->num_rows()>0 && $checkifalreadySucced->row()->status!='2'){
			//echo 'no';die;
			$where = ['merchant_id' => $custom_json->payment_by, 'status' => '2'];
			$checkActivePlans = $CI->db->get_where('merchant_subscription', $where);

			
			
			if ($checkActivePlans->num_rows()>0 && isset($json['transaction']['cpm_trans_status']) && $json['transaction']['cpm_trans_status'] == 'ACCEPTED') {
			  $subs_plan_status = '5';
			 } elseif (isset($json['transaction']['cpm_trans_status']) && $json['transaction']['cpm_trans_status'] == 'ACCEPTED') {
				$subs_plan_status = '2';
			}else {
				$subs_plan_status = '1';
			}

			$sub_arr = ['status' => $subs_plan_status, 'updated_on' => strtotime(date('Y-m-d H:i:s'))];
			$CI->db->where('tx_id', $json['transaction']['cpm_trans_id']);
			$query = $CI->db->update('merchant_subscription', $sub_arr);
		}
		

        
    }

    elseif ($custom_json->payment_for == '3' || $custom_json->payment_for == '7') {
                if($inData->_cpm_trans_status == 'ACCEPTED')
                {
            // $userCheck = $CI->db->('cinetpay_wallet', '*', ['user_id' => $json['payment_by'], 'status' => 1]);

                        $CI->db->where(['payment_type'=>'1','payment_id'=>$payment_id]);
                        $CI->db->join('payment', 'cinetpay_wallet.payment_id = payment.id'); 
                        $userCheck = $CI->db->get('cinetpay_wallet');
                    
                        // 1=user,2=merchant,
                        $CI->db->where(['id'=>$custom_json->payment_by]);
                        $CI->db->select('user_type');
                        $CI->db->from('users');
                        $usersType = $CI->db->get();

                            if($usersType->row()->user_type == 1) 
                            {
                                $insert['user_id'] = $custom_json->payment_by;
                                $update['user_id'] = $custom_json->payment_by;
                            }  
                            else
                            {
                                $insert['merchant_id'] = $custom_json->payment_by;
                                $update['merchant_id'] = $custom_json->payment_by;
                            }
                    if($userCheck->num_rows()==0){


                        $insert['payment_id'] = $payment_id;
                        $insert['wallet_amount'] =  $custom_json->orginal_amount;
                        $insert['margin '] = $custom_json->commission;
                        $insert['currency'] = $inData->_cpm_currency;
                        $insert['payment_status'] = $inData->_cpm_trans_status;
                        $insert['added_on'] = time();
                        $insert['updated_on'] = time();
                        $query = $CI->db->insert('cinetpay_wallet', $insert);
                    }
                    else
                    {
                        $CI->db->where(['payment_type'=>'1','payment_id'=>$payment_id]);
                        $CI->db->join('payment', 'cinetpay_wallet.payment_id = payment.id'); 
                        $update['wallet_amount'] = $custom_json->orginal_amount;
                        $update['margin '] = $custom_json->commission;
                        $update['currency'] = $inData->_cpm_currency;
                        $update['payment_status'] = $inData->_cpm_trans_status;
                        $update['updated_on'] = time();
                        $query = $CI->db->update('cinetpay_wallet', $update);
                    }
                    if($query)
                    {
                        if($usersType->row()->user_type == 1) 
                        {
                           $CI->db->where(['cinetpay_wallet.user_id'=>$custom_json->payment_by,'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                        }  
                        else
                        {
                            $CI->db->where(['cinetpay_wallet.merchant_id'=>$custom_json->payment_by,'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                        }
                         
                        $CI->db->select_sum('wallet_amount');
                        $CI->db->select_sum('margin');
                        $CI->db->from('cinetpay_wallet');
                        $total_amount = $CI->db->get();
                        
                        if($total_amount)
                        {
                            // update wallet amount in users table
                            $CI->db->where(['id'=>$custom_json->payment_by]);
                            $CI->db->join('payment', 'users.id = payment.payment_by'); 
                            $update_balance['wallet_balance'] = $total_amount->row()->wallet_amount;
                            $update_balance['update_on'] = time();
                            $query = $CI->db->update('users', $update_balance);
                        }

                    }
                    
                }
            }
            elseif ($custom_json->payment_for == '8') {
                if($inData->_cpm_trans_status == 'ACCEPTED')
                {
            // $userCheck = $CI->db->('cinetpay_wallet', '*', ['user_id' => $json['payment_by'], 'status' => 1]);

                        $CI->db->where(['payment_type'=>'1','payment_id'=>$payment_id]);
                        $CI->db->join('payment', 'cinetpay_wallet.payment_id = payment.id'); 
                        $userCheck = $CI->db->get('cinetpay_wallet');
                    
                        // 1=user,2=merchant,
                        $CI->db->where(['id'=>$custom_json->payment_to]);
                        $CI->db->select('user_type');
                        $CI->db->from('users');
                        $usersType = $CI->db->get();

                            if($usersType->row()->user_type == 1) 
                            {
                                $insert['user_id'] = $custom_json->payment_to;
                                $update['user_id'] = $custom_json->payment_to;
                            }  
                            else
                            {
                                $insert['merchant_id'] = $custom_json->payment_to;
                                $update['merchant_id'] = $custom_json->payment_to;
                            }
                    if($userCheck->num_rows()==0){


                        $insert['payment_id'] = $payment_id;
                        $insert['wallet_amount'] =  $json['orginal_amount'];
                        $insert['margin '] = $custom_json->commission;
                        $insert['currency'] = $inData->_cpm_currency;
                        $insert['payment_status'] = $inData->_cpm_trans_status;
                        $insert['added_on'] = time();
                        $insert['updated_on'] = time();
                        $query = $CI->db->insert('cinetpay_wallet', $insert);
                    }
                    else
                    {
                        $CI->db->where(['payment_type'=>'1','payment_id'=>$payment_id]);
                        $CI->db->join('payment', 'cinetpay_wallet.payment_id = payment.id'); 
                        $update['wallet_amount'] = $custom_json->orginal_amount;
                        $update['margin '] = $custom_json->commission;
                        $update['currency'] = $inData->_cpm_currency;
                        $update['payment_status'] = $inData->_cpm_trans_status;
                        $update['updated_on'] = time();
                        $query = $CI->db->update('cinetpay_wallet', $update);
                    }
                    if($query)
                    {
                           // $where_in = array('1','4');

                        if($usersType->row()->user_type == 1) 
                        {
                           $CI->db->where(['cinetpay_wallet.user_id'=>$custom_json->payment_to,'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                           // $CI->db->where_in('cinetpay_wallet.payment_type', $where_in);
                        }  
                        else
                        {
                            $CI->db->where(['cinetpay_wallet.merchant_id'=>$custom_json->payment_to,'cinetpay_wallet.payment_status'=>"ACCEPTED"]);
                            // $CI->db->where_in('cinetpay_wallet.payment_type', $where_in);
                        }
                         
                        $CI->db->select_sum('wallet_amount');
                        $CI->db->select_sum('margin');
                        $CI->db->from('cinetpay_wallet');
                        $total_amount = $CI->db->get();
                        // print_r($total_amount->row()->wallet_amount); die();
                        if($total_amount)
                        {
                            // update wallet amount in users table
                            $CI->db->where(['id'=>$custom_json->payment_to]);
                            $CI->db->join('payment', 'users.id = payment.payment_by'); 
                            $update_balance['wallet_balance'] = $total_amount->row()->wallet_amount;
                            $update_balance['update_on'] = time();
                            $query = $CI->db->update('users', $update_balance);
                        }

                    }
                    
                }
            }
    
        curl_close($curl);
    }


    function checkPayment($cpm_trans_id='', $payment_by=''){
        $CI = &get_instance();
     // checking merchant subscription
        $where = ['merchant_id' => $payment_by, 'status' => '2'];
        $CI->db->select('*');
        $CI->db->from('merchant_subscription');
        $CI->db->where($where);
        $checkActivePlans = $CI->db->get();
        $checkActivePlans = $checkActivePlans->row();
       // echo $CI->db->last_query();die;
//echo '<pre>';print_R($checkActivePlans);die;
            $CI = &get_instance();
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, 'https://api.cinetpay.com/v1/?method=checkPayStatus');
            $cpm_site_id = cinetPaysiteId;
            $param = [
                "apikey" => cinetPayapikey,
                "cpm_site_id" => $cpm_site_id,
                "cpm_trans_id" => $cpm_trans_id,
            ];
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));

            // Send request
            $response = curl_exec($curl);
            $curlinfo = curl_getinfo($curl);
            $json = json_decode(strval($response), true);
           
            $payment_arr = [
                'payment_status' => (isset($json['transaction']['cpm_trans_status']))?$json['transaction']['cpm_trans_status']:'',
                'update_on' => now,
            ];
          
        //updating payment status
        $upwhere = array('tx_id'=>$cpm_trans_id);
        $CI->db->where($upwhere); 
        $query = $CI->db->update('payment', $payment_arr);            
       

        if ($checkActivePlans && isset($json['transaction']['cpm_trans_status']) && $json['transaction']['cpm_trans_status'] == 'ACCEPTED') {
            if($checkActivePlans->tx_id == $pera['cpm_trans_id']){ //if already activated 
                $subs_plan_status = '2';
            }else{
                $subs_plan_status = '5';
            }
        } elseif (isset($json['transaction']['cpm_trans_status']) && $json['transaction']['cpm_trans_status'] == 'ACCEPTED') {
            $subs_plan_status = '2';
        }else {
            $subs_plan_status = '1';
        }

        $finalData = str_replace("'", '"',$json['transaction']['cpm_custom']);
        $custom_json = json_decode($finalData);

        $where = array('merchant_id'=>$payment_by);
        //getting business type
        $CI->db->select('business_type');
        $CI->db->from('business_info');
        $CI->db->where($where);
        $business = $CI->db->get();
        $business =  $business->row();
        $business_type_id ='';
        if($business){
            $CI->db->select('*');
            $CI->db->from('subscription_business_type');
            $CI->db->where(['subscription_id'=>$custom_json->reference_id,'business_type'=>$business->business_type]);
            $business_type_id = $CI->db->get();
            $business_type_id =  $business_type_id->row();
            $business_type_id = $business_type_id->id;
        }
        
       //checking payment done or not
        $CI->db->select('*');
        $CI->db->from('merchant_subscription');
        $CI->db->where($upwhere);
        $alreadyExist = $CI->db->get();
        $alreadyExist =  $alreadyExist->row();
        
        if($alreadyExist)
        {

            //updating merchant subscription status
            $sub_arr =array('status'=>$subs_plan_status,'business_type_subscription_id'=>$business_type_id,'updated_on'=>now);
            $CI->db->where($upwhere); 
            $query = $CI->db->update('merchant_subscription', $sub_arr);
            if($subs_plan_status=='2' || $subs_plan_status=='5'){
                 return true;
            }else{
                return false;  //payment pending
            }
          
        }
        else
        {

             // echo "<pre>"; print_r($custom_json); die();
            $where = ['id' => $custom_json->reference_id];
                $CI->db->select('*');
                $CI->db->from('subscription');
                $CI->db->where($where);
                $check = $CI->db->get();
                $check =  $check->row();

                $subs_business_typeid = '';
                $login_id = $custom_json->payment_by;
                $duration = ($check)?$check->duration:0;

                if ($checkActivePlans) {
                    //because we are actvating plan immideatly
                    $start_date = strtotime(date('Y-m-d H:i:s'));
                    $now = strtotime(date('Y-m-d H:i:s')); // or your date as well
                    $datediff = intval($checkActivePlans->expire_date) - $now;
                    $days_remaining = round($datediff / (60 * 60 * 24));
                    //extending expiry date
                    $duration = $days_remaining + $duration;
                    $status = $subs_plan_status;
                    $expire_date = date('Y-m-d', strtotime("+" . $duration . " days"));
                } else {
                    $start_date = strtotime(date('Y-m-d H:i:s'));
                    $status = $subs_plan_status;
                    $expire_date = date('Y-m-d', strtotime("+" . $duration . " days"));
                }
                // upgrade new plan
                $insert['merchant_id'] = $payment_by;
                $insert['subscription_id'] = $custom_json->reference_id;
                $insert['business_type_subscription_id'] = $business_type_id;
                $insert['start_date'] = $start_date;
                $insert['subscription_date'] = strtotime(date('Y-m-d H:i:s'));
                $insert['expire_date'] = strtotime($expire_date);
                $insert['duration'] = $duration;
                $insert['tx_id'] = $cpm_trans_id;
                $insert['added_on'] = strtotime(date('Y-m-d H:i:s'));
                $insert['updated_on'] = strtotime(date('Y-m-d H:i:s'));
                $insert['status'] =  $subs_plan_status;

                $query = $CI->db->insert('merchant_subscription', $insert); 
                //echo $this->db->last_query();die;
            return true;
            
        }
        
   }

    public function getCommandeByTransId()
    {
        // Retrieve an order by its $ _transId
        //should do "$this->$_transId" to get the transId
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->_montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant)
    {
        $this->_montant = $montant;
    }

    /**
     * @return mixed
     */
    public function getTransId()
    {
        return $this->_transId;
    }

    /**
     * @param mixed $transId
     */
    public function setTransId($transId)
    {
        $this->_transId = $transId;
    }

    /**
     * @return mixed
     */
    public function getMethode()
    {
        return $this->_methode;
    }

    /**
     * @param mixed $methode
     */
    public function setMethode($methode)
    {
        $this->_methode = $methode;
    }

    /**
     * @return mixed
     */
    public function getPayId()
    {
        return $this->_payId;
    }

    /**
     * @param mixed $payId
     */
    public function setPayId($payId)
    {
        $this->_payId = $payId;
    }

    /**
     * @return mixed
     */
    public function getBuyerName()
    {
        return $this->_buyerName;
    }

    /**
     * @param mixed $buyerName
     */
    public function setBuyerName($buyerName)
    {
        $this->_buyerName = $buyerName;
    }

    /**
     * @return mixed
     */
    public function getTransStatus()
    {
        return $this->_transStatus;
    }

    /**
     * @param mixed $transStatus
     */
    public function setTransStatus($transStatus)
    {
        $this->_transStatus = $transStatus;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->_signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature)
    {
        $this->_signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->_errorMessage = $errorMessage;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->_statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->_statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->_dateCreation;
    }

    /**
     * @param mixed $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->_dateCreation = $dateCreation;
    }

    /**
     * @return mixed
     */
    public function getDateModification()
    {
        return $this->_dateModification;
    }

    /**
     * @param mixed $dateModification
     */
    public function setDateModification($dateModification)
    {
        $this->_dateModification = $dateModification;
    }

    /**
     * @return mixed
     */
    public function getDatePaiement()
    {
        return $this->_datePaiement;
    }

    /**
     * @param mixed $datePaiement
     */
    public function setDatePaiement($datePaiement)
    {
        $this->_datePaiement = $datePaiement;
    }

    //    public function mycart($type='',$login_user='')
    //  {
    //    $CI = & get_instance();
    //    // $CI->load->model('api/APIModel','Cart');

    //     $join = array(
    //                  array('table'=>'products',
    //                      'condition'=>"carts.product_id = products.id"),
    //                    array('table'=>'favourite_unfavourite as fav',
    //                            'condition'=>"fav.product_id=products.id AND fav.type='1' AND fav.status='1' AND fav.user_id='".$login_user."'",
    //                            'type'=>'left'));
    //     $coloum  = "carts.qty, carts.id 'cart_id', carts.product_id,
    //                 carts.user_id,carts.status 'cart_status',carts.applyed_offer,
    //                 products.qty 'product_qty', products.name, products.category,
    //                 products.price, products.track_inventory, products.threshold,
    //                 products.threshold_limit, products.merchant_id,products.store_id,
    //                 products.description,products.imgs, products.currency,products.currency_code,
    //                 products.rating,products.status 'product_status',
    //                 fav.id 'is_favourite'";

    //     $where  = array('carts.user_id'=>$login_user,'carts.status'=>'1');
    //     // $results= $CI->Cart->GetData('carts',$coloum,$where,'carts.id','DESC',$join);

    //        $CI->db->select($coloum);
    //        $CI->db->from('carts');
    //        $CI->db->where($where);
    //        $CI->db->join('products',"carts.product_id = products.id");
    //        $CI->db->join('favourite_unfavourite as fav',"fav.product_id=products.id AND fav.type='1' AND fav.status='1' AND fav.user_id='".$login_user."'",
    //                         'left');
    //        $CI->db->order_by('carts.id','DESC');
    //        $results = $CI->db->get();

    //        if($results->num_rows()>0)
    //        {
    //            $results = $results->result_array();
    //        }
    //        else
    //        {
    //            $request = array();
    //        }
    //        // echo $CI->db->last_query(); die();
    //     $responce = $items = array();
    //    if($results)
    //    {
    //      $store_id = $results[0]['store_id'];
    //      $cart_total = $total_items = 0;
    //      foreach($results as $result)
    //      {
    //        // set proper product imgs
    //        $images =   $images_thumb =array();
    //        if(!empty(json_decode($result['imgs'])))
    //        {
    //          foreach(json_decode($result['imgs']) as $img)
    //          {
    //             $images_thumb[] =($img)?base_url('uploads/products/thumb/'.$img):base_url('default/product_default.jpg');
    //             $images[] =($img)?base_url('uploads/products/'.$img):base_url('default/product_default.jpg');
    //          }

    //        }else{
    //          $images_thumb[] = base_url('default/product_default.jpg');
    //          $images[] = base_url('default/product_default.jpg');
    //        }
    //        $result['imgs']         = $images;
    //        $result['imgs_thumb']   = $images_thumb;
    //        $result['is_favourite'] = ($result['is_favourite'])?'1':"0";
    //        $items[] = $result;
    //      }

    //      // get store info
    //      // $store_info= $CI->Cart->RowData('stores','*',array('id'=>$store_id));

    //        $CI->db->select('*');
    //        $CI->db->from('stores');
    //        $CI->db->where(array('id'=>$store_id));
    //        $store_info = $CI->db->get();

    //        if($store_info->num_rows()>0)
    //        {
    //            $store_info = $store_info->row();
    //        }
    //        else
    //        {
    //            $store_info = array();
    //        }

    //    if($store_info)
    //    {
    //    $store_info->store_logo_thumb =($store_info->store_logo)?base_url().'uploads/stores/thumb/'.$store_info->store_logo:store_default_img;
    //    $store_info->store_logo =($store_info->store_logo)?base_url().'uploads/stores/'.$store_info->store_logo:store_default_img;

    //    }
    //    $responce['cart']       = $this->fetchOffers($items,$login_user);
    //    $responce['store_info'] = ($store_info)?$store_info:array();

    //    }
    //    else
    //    {
    //      $responce= array();
    //    }

    //    return $responce;

    //  }

    //  function fetchOffers($mycart=array(),$login_user='')
    //  {
    //    $CI = &get_instance();
    //     // $CI->load->model('api/APIModel','Cart');
    //    $products = $category = array();
    //    foreach($mycart as $cart)
    //    {
    //       $products[] = $cart['product_id'];
    //       $category[] = $cart['category'];
    //    }

    //    // get all campaigns which is notifyed to login user
    //    $where    = "bn.user_id='".$login_user."' AND campaign.status='1' AND campaign.end_time>='".strtotime(date('d-m-Y H:i:s'))."' AND campaign.type='1' ";
    //    $coloum   = "bn.campaign_id";
    //    // $join[]   = array('table'=>'campaign','condition'=>"bn.campaign_id =campaign.id");
    //    // $campaigns = $CI->Cart->GetData('beacon_notifications as bn',$coloum,$where,'bn.id','DESC',$join);

    //        $CI->db->select($coloum);
    //        $CI->db->from('beacon_notifications as bn');
    //        $CI->db->where($where);
    //        $CI->db->join('campaign',"bn.campaign_id =campaign.id");
    //        $CI->db->order_by('bn.id','DESC');
    //        $campaigns = $CI->db->get();
    //    $campaigns = ($campaigns)?$campaigns:array();

    //    if($campaigns->num_rows()>0)
    //    {
    //        $campaigns = $campaigns->result_array();
    //    }
    //    else
    //    {
    //        $campaigns = array();
    //    }

    //    $activeCampaign = array();
    //    foreach($campaigns as $campaign)
    //    {
    //       $activeCampaign[] = $campaign['campaign_id'];
    //    }

    //    $productIds  = ($products)?implode(',',$products):"'*'";
    //    $categoryIds  = ($category)?implode(',',$category):"'*'";
    //    $campaignIds  = ($activeCampaign)?implode(',',$activeCampaign):"'*'";

    //    $cart_total =$total_items= $totalDiscount = $totalDiscountedPrice = 0;

    //    /// fetch all offers for my cart products
    //    $where  = " (offer_for.product_id in (".$productIds.") OR offer_for.category_id in (".$categoryIds.") ) AND
    //                offers.end_time>='".strtotime(date('d-m-Y H:i:s'))."' AND
    //                offers.status='1' AND
    //                (offers.created_by='2' || offers.campaign_id in (".$campaignIds.") )
    //              ";
    //    // $join   = array(array('table'=>'offers','condition'=>"offer_for.offer_id =offers.id"));
    //    // $coloum = "*";
    //    // $offers = $CI->Cart->GetData('offer_for',$coloum,$where,'offer_for.id','DESC',$join);

    //     $CI->db->select('*');
    //        $CI->db->from('offer_for');
    //        $CI->db->where($where);
    //        $CI->db->join('offers',"offer_for.offer_id =offers.id");
    //        $CI->db->order_by('offer_for.id','DESC');
    //        $offers = $CI->db->get();

    //  if($offers->num_rows()>0)
    //    {
    //        $offers = $offers->result_array();
    //    }
    //    else
    //    {
    //        $offers = array();
    //    }

    //    $offers = ($offers)?$offers:array();
    //    // applying offers on my cart item
    //    $offerCart = array();
    //    foreach($mycart as $cartItem)
    //    {
    //      $itemOffers   = $applyedOffer = array();
    //      $applyed      = false;
    //      $i =0;
    //      foreach($offers as $offer)
    //      {
    //        if($offer['product_id']==$cartItem['product_id'] || $offer['category_id']==$cartItem['category'] )
    //        {
    //          $itemOffers[] = $offer;
    //          if($cartItem['applyed_offer'] == $offer['id'])
    //          {
    //            $applyedOffer = $offer;
    //            $applyed      = true;
    //          }
    //          if($cartItem['applyed_offer'] != $offer['id'] && !$applyed)
    //          {
    //            // mark this offer as a apply in cart for future
    //            $update['applyed_offer'] = $offer['id'];
    //            $where = array("id"=>$cartItem['cart_id']);
    //            // $CI->Cart->UpdateData('carts',$update,$where);
    //            $CI->db->where($where);
    //            $CI->db->update('carts', $update);

    //            $applyedOffer = $offer;
    //            $applyed      = true;
    //            $i++;
    //          }
    //        }
    //      }

    //      // apply offer if it's available
    //      $offerPrice = $cartItem['price'];
    //      if($applyedOffer)
    //      {
    //        if($applyedOffer['amount_unit']=='1')
    //        {
    //         $offerPrice = (($cartItem['price']/100)*$applyedOffer['offer_amount']);
    //         $totalDiscount += $offerPrice*$cartItem['qty'];
    //         $offerPrice = ($offerPrice>$cartItem['price'])?$cartItem['price']:$cartItem['price']-$offerPrice;

    //        }
    //        else
    //        {
    //         $offerPrice = $applyedOffer['offer_amount'];
    //         $totalDiscount += $offerPrice*$cartItem['qty'];
    //         $offerPrice = ($offerPrice>$cartItem['price'])?$cartItem['price']:$cartItem['price']-$offerPrice;
    //        }

    //      }

    //      $cartItem['offfer_price_without_format']  = $offerPrice;
    //      $cartItem['offfer_price']  = number_format($offerPrice,2);
    //      $cartItem['offers']        = $itemOffers;
    //      $cartItem['applyed_offer'] = $applyedOffer;
    //      $cart_total    += floatval($cartItem['price'])*intval($cartItem['qty']);
    //      $totalDiscountedPrice += floatval($cartItem['offfer_price_without_format'])*intval($cartItem['qty']);
    //      $total_items   += intval($cartItem['qty']);

    //      $offerCart[] = $cartItem;
    //    }
    //    $result['item']           = $offerCart;
    //    $result['cart_total_without_format']  = $cart_total;
    //    $result['cart_total']     = number_format($cart_total,2);

    //    $result['total_items']    = $total_items;

    //    $result['total_discounted_price_without_format'] = $totalDiscountedPrice;
    //    $result['total_discounted_price'] = number_format($totalDiscountedPrice,2);

    //    $result['total_discount_without_format']  = $totalDiscount;
    //    $result['total_discount']   = number_format($totalDiscount,2);

    //    $result['tax']              = array();
    //    $result['dellivery_charge'] = 0;
    //    $result['extra_charge']     = array();
    //    $result['offers']           = array();
    //  return $result;
    //  }

    // function addOrderDetail($product, $orderId)
    //   {
    //   // print_r($product);die;
    //     $CI = &get_instance();
    //       $insert['order_id']    =  $orderId;
    //       $insert['product_id']  =  $product['product_id'];
    //       $insert['item_price']  =  $product['price'];
    //       $insert['store_id']    =  $product['store_id'];
    //       $insert['quantity']    =  $product['qty'];
    //       $insert['sub_total']   =  floatval($product['price'])*intval($product['qty']);
    //       $insert['offer_price'] =  floatval($product['offfer_price_without_format'])*intval($product['qty']);

    //       $insert['applied_offer'] =  json_encode($product['applyed_offer']);

    //      // $orderId = $this->Order->AddData('order_detail',$insert);
    //       $CI->db->insert('order_detail',$insert);
    //       $orderId = $CI->db->insert_id();

    //       $update['update_on'] = now;
    //       $this->db->set('qty', 'qty-'.$product['qty'], FALSE);
    //       $where = array("id"=>$product['product_id']);
    //       //$CI->db->update('products',);
    //       $orderId = $this->Order->UpdateData('products',$update,$where);

    //       // update product qty
    //   }
}
