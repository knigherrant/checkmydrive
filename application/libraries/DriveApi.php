<?php
/*
 * clientrol_approvals
 * clientrol_files
 * clientrol_message_attachments
 * clientrol_task_attachments
 * clientrol_ticket_attachments
 */

require_once APPPATH . '/libraries/Google/vendor/autoload.php';

class DriveApi{
    public static
            $client = null,
            $scopes = array(
                Google_Service_Drive::DRIVE,
                Google_Service_Drive::DRIVE_APPDATA,
                Google_Service_Drive::DRIVE_FILE,
                Google_Service_Drive::DRIVE_METADATA,
                Google_Service_Drive::DRIVE_METADATA_READONLY,
                Google_Service_Drive::DRIVE_PHOTOS_READONLY,
                Google_Service_Drive::DRIVE_READONLY,
                Google_Service_Drive::DRIVE_SCRIPTS,
                Google_Service_Plus::PLUS_LOGIN,
                Google_Service_Plus::PLUS_ME,
                Google_Service_Plus::USERINFO_EMAIL,
                Google_Service_Plus::USERINFO_PROFILE,
             )
        ;

    public static function getClient($token = null){
        static $client;
        if(!$client){
            $client = new Google_Client();
            $client->setAuthConfig(APPPATH . '/libraries/Google/client_secret.json');
            $client->setIncludeGrantedScopes(true);
            $client->addScope(self::$scopes);
            $client->setAccessType('offline');
            $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
            $client->setHttpClient($guzzleClient);            
            //$redirect_uri = current_url();
            //$client->setRedirectUri($redirect_uri);
            
            if($token){
                $client->setAccessToken((array)$token);
                if($client->isAccessTokenExpired() && $token->refresh_token){
                    $client->refreshToken( $token->refresh_token);
                    $client->refresh_token = $token->refresh_token;
                }
            }else{
                
            }
        }
        return $client;
    }
    
    public static function buildQuery($object){
        $params = array();
        foreach($object as $key => $type){
            if(is_object($type)){
                $key .= '('.self::buildQuery($type).')';
            }
            
            $params[] = $key;
        }
        return implode(',', $params);
    }
    
    public static function listFiles($pageToken = null){        
        $service = new Google_Service_Drive(self::getClient());
        return $service->files->listFiles(array(
            'pageToken' => $pageToken,
            'fields' => "kind,nextPageToken,incompleteSearch,files",
            'spaces' => 'drive',
            'pageSize' => '200',
            //'supportsTeamDrives' => true,
            //'includeTeamDriveItems' => true,
            //'corpora' => 'default',
            //'orderBy' => 'folder,title asc'
        ));
    }
    public static function getPermissions($fileId){        
        $service = new Google_Service_Drive(DriveApi::getClient());
        try {
            $list = $service->permissions->listPermissions($fileId);
            return $list->getPermissions();
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
        return null;
    }

    public static function getFile($fileId){
        $client->setUseBatch(true);
        $batch = new Google_Http_Batch($client);

        $optParams = array('filter' => 'free-ebooks');
        $req1 = $service->volumes->listVolumes('Henry David Thoreau', $optParams);
        $batch->add($req1, "thoreau");
        $req2 = $service->volumes->listVolumes('George Bernard Shaw', $optParams);
        $batch->add($req2, "shaw");

        $results = $batch->execute();
        
        return array();
        
        $pemissions = self::getPermissions($id);
        
        $file = Checkmydrive::getDbo(true)
                ->select('*')
                ->from('gdrive_files')
                ->where(array(
                    'id' => $id,
                    'user' => Checkmydrive::getUser()->id
                ))
                ->get()->row();
        $file->permission = $pemissions;
        return $file;
    }

        public static function testList(){
        $user = Checkmydrive::getUser();
        if(isset($user->params->google->folder)){
            $client = self::getClient();
            

            //$files = $service->files->listFiles();

            
            $pageToken  = $savedStartPageToken = $service->changes->getStartPageToken()->startPageToken;
            while ($pageToken != null) {
                $response = $service->changes->listChanges($pageToken, array(
                  'spaces' => 'drive'
                ));
                foreach ($response->changes as $change) {
                    // Process change
                    printf("Change found for file: %s", $change->fileId);
                }
                if ($response->newStartPageToken != null) {
                    // Last page, save this token for the next polling interval
                    $savedStartPageToken = $response->newStartPageToken;
                }
                $pageToken = $response->nextPageToken;
            }
            
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
    

    public static function getInfo($token = null){
        if(!$token) return new stdClass ();
        static $info;
        if(!isset($info)){
            $idToken = $token->id_token;
            if (substr_count($idToken, '.') == 2) {
              $parts = explode('.', $idToken);
              $payload = json_decode(base64_decode($parts[1]), true);
              if ($payload) {
                  return $info = (object)$payload;
              }
            }
            return $payload;
        }
        return $info;
    }
    
}
    
   
