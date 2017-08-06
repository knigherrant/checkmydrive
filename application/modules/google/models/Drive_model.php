<?php
class Drive_model extends CI_Model {
    
    //$redirect_uri = Checkmydrive::root(). 'google';
    public function saveToken($token){
        $rs = 0;
        $user = Checkmydrive::getUser();
        $params = ($user->params)? $user->params : new stdClass();
        if(isset($params->google)){
            $rs = 1;
        }else{
            $rs = 2;
        }
        $params->google = $token;
        if(!Checkmydrive::getDbo(true)->where('id', $user->id)->set('params',  json_encode($params))->update('users')) $rs = -1;
        return $rs;
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
                //echo "<div style='text-align: center'><h2>Authenticated user's Google Drive successfull</h2><script>window.close()</script></div>";
            }
            return $token;
        }
    }
    
    public function updateFile($file){
        $user = Checkmydrive::getUser();
        $obj = array(
            'name' => $file->name,
            'kind' => $file->kind,
            'mimeType' => $file->mimeType
        );
        $keys = array(                
            'id' => $file->id,
            'user' => $user->id,
        );
        if(Checkmydrive::getDbo(true)
                ->select('*')
                ->from('gdrive_files')
                ->where("id = '{$file->id}' AND user = {$user->id}")
                ->get()->row()){
            Checkmydrive::getDbo(true)
                    ->where($keys)
                    ->set($obj)
                    ->update('gdrive_files');
        }else{
            Checkmydrive::getDbo(true)
                    ->set($keys)->set($obj)
                    ->insert('gdrive_files');
        }
    }
}