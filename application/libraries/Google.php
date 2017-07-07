<?php
/*
 * clientrol_approvals
 * clientrol_files
 * clientrol_message_attachments
 * clientrol_task_attachments
 * clientrol_ticket_attachments
 */
class Google{

    public static function getClient($token = null){
        static $client;
        if(!$client){
            require_once APPPATH . '/libraries/Google/vendor/autoload.php';
            $client = new Google_Client();
            $client->setAuthConfig(APPPATH . '/libraries/Google/client_secret.json');
            $client->setIncludeGrantedScopes(true);
            $client->addScope(array(
                 Google_Service_Drive::DRIVE,
                 Google_Service_Plus::PLUS_LOGIN,
                 Google_Service_Plus::PLUS_ME,
                 Google_Service_Plus::USERINFO_EMAIL,
                 Google_Service_Plus::USERINFO_PROFILE,
             ));
            $client->setAccessType('offline');
            $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
            $client->setHttpClient($guzzleClient);
            $redirect_uri = Checkmydrive::root();
            $client->setRedirectUri($redirect_uri);
            if(!$token){
                $user = Checkmydrive::getUser();
                if(isset($user->params->google)){
                    $token = json_encode($user->params->google->token);
                }
            }
		
            if($token){
                $client->setAccessToken($token);
                if($client->isAccessTokenExpired() && $user->params->google->refresh_token){
                    $params = $user->params;
                    $newToken = $client->refreshToken( $params->google->refresh_token);
                    $params->google->token =  $newToken;
                    Checkmydrive::getDbo(true)->where('id', $user->id)->set('params',  json_encode($params))->update('users');
                }
            }
        }
        return $client;
    }
    

    public static function saveToken(){
        $user = Checkmydrive::getUser();
        if (isset($_GET['code']) && $user->id && empty($_GET['state'])) {
            $client = self::getClient();
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if($token){
                $client->setAccessToken($token);
                $db = Checkmydrive::getDbo(true);
                $folderID = $refreshToken = $newAuth = '';
                //get Google profile
                $profile = new Google_Service_Oauth2($client);
                $about =   $profile->userinfo->get();
                
                $params = ($user->params)? $user->params : new stdClass();
                if(isset($user->params->google)) $folderID = @$user->params->google->folder;
                if(isset($user->params->google->refresh_token)) $refreshToken = $user->params->google->refresh_token;
                if($folderID){
                    if($about->email == $params->google->info->email){ // re-auth same account
                        if(empty($token['refresh_token'])) $token['refresh_token'] = $refreshToken;
                        $params->google->token = $token;
                    }else{ // re-auth other account
                        $folderID = ''; $newAuth = true;
                    }
                }else $newAuth = true;
                if(!$folderID){
                    $drive_service = new Google_Service_Drive($client);
                    $fileMetadata = new Google_Service_Drive_DriveFile(array(
                            'name' => 'clientrol',
                            'mimeType' => 'application/vnd.google-apps.folder')
                        );
                    $folder = $drive_service->files->create($fileMetadata, array('fields' => 'id'));
                    $folderID = $folder->id;
                }
                if($newAuth){
                    $params->google = array(
                        'token' => $token,
                        'refresh_token' => $token['refresh_token'],
                        'folder' => $folderID,
                        'info' => array(
                            'name' => $about->name,
                            'email' => $about->email,
                            'avatar' => $about->picture,
                        )
                    );
                }
                $params->use = 'google';
                $db->where('id', $user->id)->set('params',  json_encode($params))->update('users');
                echo "<div style='text-align: center'><h2>Authenticated user's Google Drive successfull</h2><script>window.close()</script></div>";
                die();
            }
        }
    }

    
    public static function testList(){
        $user = Checkmydrive::getUser();
        if(isset($user->params->google->folder)){
            $client = self::getClient();
            $service = new Google_Service_Drive($client);

            $files = $service->files->listFiles();

            k($files);
            
            
        }
    }
    
    public static function upload($source, $name){
        $user = Checkmydrive::getUser();
        if(isset($user->params->google->folder)){
            $client = self::getClient();
            $service = new Google_Service_Drive($client);
            $file = new Google_Service_Drive_DriveFile(array(
                'name' => $name,
                'parents' => array($user->params->google->folder)
            ));
            $request = $service->files->create($file,
                    array(
                        'data' => file_get_contents($source),
                        'mimeType' => 'application/octet-stream',
                        'uploadType' => 'multipart'
                    )        
            );   

            if($request){
                return json_encode (array('fileID' => $request->id , 'url' => 'https://drive.google.com/file/d/' . $request->id)); 
            }
            return false;
        }else{
            Checkmydrive::setMessage ('Are you not  google drive');
        }
        return false;
    }
    
    public static function delete($fileID){
        if($fileID){
            $client = self::getClient();
            $service = new Google_Service_Drive($client);
            return $service->files->delete($fileId);
        }
        return false;
    }
    

    public static function getInfo(){
        static $info;
        if(!isset($info)){
            $user = Checkmydrive::getUser(0, true);
            if(isset($user->params->google->info)){
                $info = $user->params->google->info;
                $info->class = '';
            }else{
                $info = (object) array(
                    'class' => 'jkhide'
                );
            }
        }
        return $info;
    }
    
}
    
   
