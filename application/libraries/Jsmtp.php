<?php
include_once (dirname(__FILE__) . '/SMTP/PHPMailerAutoload.php');
class Jsmtp{
    
    public static function getSMTP($config = ''){
        static $mail;
        if(!$mail){
            if(!$config){
                $config = (object) array(
                    'smtp_host' => 'mail.jomkungfu.com',
                    'smtp_user' => 'info@jomkungfu.com',
                    'smtp_pass' => 'jomkungfu8Xmail',
                    'smtp_port' => '25',
                );
            }
            $mail = new PHPMailer;
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $config->smtp_host;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // use
            // $mail->Host = gethostbyname('smtp.gmail.com');
            // if your network does not support SMTP over IPv6
            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $mail->Port = $config->smtp_port;
            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = $config->smtp_secure;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = $config->smtp_user;
            //Password to use for SMTP authentication
            $mail->Password = $config->smtp_pass;
            //Set who the message is to be sent from
            $mail->setFrom($config->smtp_user, $config->sitename);
            //Set an alternative reply-to address
            $mail->addReplyTo($config->smtp_user, $config->sitename);
            //Set who the message is to be sent to
            //$mail->addAddress('joomlavi.son@gmail.com', 'John Doe');
            //Set the subject line
            $mail->Subject = $config->sitename;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML('<p>xxxxxxxxxxxxxxxxxxx</p>');
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
          
        }
         return $mail;
    }
            
}
