<?php

function send_notifications($registrationIds, $data)
{  
	if(count($registrationIds) ==0)
	{
		return false;
	}
    
  $API_ACCESS_KEY = "AAAAMkosLpc:APA91bGLiLyzvEMHIUG3flEA3-hlJKwzYjMjPsysaWWozP0vTHi7Qs_OWFjeKRr-r8T6AFMs3Knal-UX-_lTe312RSdVF5vsymOIx9PYugwapXStUF8iR2EgZRDhWJfx9G9xUIjA8MCb";

    
       $msg = array(
            'body'  => $data['body'],
	          'id' => $data['id'],   // id of action 
	          'notification_type' => intval($data['type']),
              'title' => 'Vocust App',
              'largeIcon' => 'large_icon',
              'sound' => "default",
              'content_available'=>true,
              'badge' =>1);

       $fields = array(
           'registration_ids' => $registrationIds,  //fcm_token array
           'data' => $msg ,  //payload for andriod
           'notification'      => $msg   //payload for ios
       );

       $headers = array(
           'Authorization: key='.$API_ACCESS_KEY, //firebase API key
           'Content-Type: application/json'
       );
       
       //curl request
       $ch = curl_init();
       curl_setopt( $ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );  
       curl_setopt( $ch,CURLOPT_POST, true );
       curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
       curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
       curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
       curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
       $result = curl_exec($ch );
       curl_close( $ch );
       return $result;
   }


?>