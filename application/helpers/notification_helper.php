<?php
 /* Firebase notification for Andriod and ios */
    function send_notification($fcmtoken, $payload)
    {   
        $payload['click_action'] ='FLUTTER_NOTIFICATION_CLICK';
        $ci =& get_instance();    
        //log_event(json_encode($msg), $this->notify_log_file);  //create log of notifcation
        $fields = array(
            'registration_ids'  => $fcmtoken,  //firebase token array
            'data'      => $payload ,  //msg for andriod
            'notification' => $payload   //msg for ios
        );

        $headers = array(
            'Authorization: key=' .FCM_KEY, //firebase API key
            'Content-Type: application/json'
        );

		//print_R(json_encode( $fields ));die;
        
        //curl request
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );  //firebase end url
        //curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        // log_event($result, $this->notify_log_file);  //create log of notifcation
        return $result;
    }

?>