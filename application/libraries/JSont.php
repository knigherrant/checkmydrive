<?php
class JSont{
    static $config = array(
        'Port' => 465,
        'SMTPSecure' => 'ssl',
        'Host' => 'turquoise.webhostingireland.ie',
        'Username' => 'email@clientrolapp.com',
        'Password' => 'dawson45',
        'Subject' => 'Subject',
        'From' => 'email@clientrolapp.com',
        'To' => 'joomlavi.son@gmail.com',
        'AltBody' =>'',
        'Body' =>'',
        'jksmtp' => 'http://www.celticbeds.co.uk/mails/jksmtp/smtp.php'
        //'jksmtp' => 'http://joomlao2.com/project/mail/jksmtp/smtp.php'
    );
    
    public static function init($config){
        if($config){
            foreach ($config as $k => $cfg) if($cfg) self::$config[$k] = $cfg;
        }
    }
    public static function sendMail($from, $fromName, $to, $subject, $body){
        $params = self::$config;
        $params['From'] = $from;
        $params['FromName'] = $fromName;
        $params['To'] = $to;
        $params['Subject'] = $subject;
        $params['Body'] = $body;
        $data = self::post(self::$config['jksmtp'], http_build_query($params));
        return $data;
    }
    
    public static function post($url,$datax = null) {
        $ch = curl_init();
        $timeout = 100;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datax); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data =  curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    
    
}