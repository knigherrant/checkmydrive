<?php
class jSontSMTP{
	public static function sendMail($post = array()){
        if(!$post) $post = $_REQUEST;
		$post = (object) $post;
		require 'PHPMailerAutoload.php';
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
		$mail->Host = $post->Host;
		/*
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		*/
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = $post->Port;
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = $post->SMTPSecure;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = $post->Username;
		//Password to use for SMTP authentication
		$mail->Password = $post->Password;
		//Set who the message is to be sent from
		$mail->setFrom($post->From, $post->FromName);
		//Set an alternative reply-to address
		$mail->addReplyTo($post->From, $post->FromName);
		//Set who the message is to be sent to
		$mail->addAddress($post->To, '');
		//Set the subject line
		$mail->Subject = $post->Subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($post->Body);
		//Replace the plain text body with one created manually
		//$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');
		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "OK";
		}
    }
}
jSontSMTP::sendMail();
die();