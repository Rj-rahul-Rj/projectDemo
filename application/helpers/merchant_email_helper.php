<?php
require APPPATH.'third_party/phpMailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function inventoryThreshold($data='')
{
    $mail = new PHPMailer(true);
     try {
            $mail->isSMTP();     
            $mail->Host       = 'smtp.gmail.com';  
            $mail->SMTPAuth   = true;      
            $mail->Debug      = 2;      
            $mail->Username   = email;
            $mail->Password   = gpassword;    
            $mail->SMTPSecure = 'tls';   
            $mail->Port       = 587;
            $mail->setFrom(mailfrom, mailfromname);
             $mail->addAddress($data['merchant_email'], $data['merchant_name']); 
            // Content
            $mail->isHTML(true);  
          
            $body="<p>Hello ".$data['merchant_name']."</p> Please update your product inventory it reach out the threshold quantity limit for <b>".$data['store_name']."</b> store.";
            $body .= "<p> Product Name: ".$data['name']."</p>";
            $body .= "<p> Product Id: ".$data['id']."</p>";
            $body .= "<p> Product Price: ".$data['price']."</p>";
            $body .= "<p> Product Qty: ".$data['qty']."</p>";
            
            $mail->Subject = "Inventory ThreShold Notification";
            $mail->Body    = $body;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return   1;
        } catch (Exception $e) {
             //return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return 0;
        }
}

?>