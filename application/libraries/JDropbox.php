<?php
/*
 * clientrol_approvals
 * clientrol_files
 * clientrol_message_attachments
 * clientrol_task_attachments
 * clientrol_ticket_attachments
 */
class JDropbox{

    public static function getDropbox($token = null){
        static $dropbox;
        if(!$dropbox){
            require_once APPPATH . '/libraries/Dropbox/vendor/autoload.php';
            //Configure Dropbox Application
            $app = new Kunnu\Dropbox\DropboxApp("n2qgxsua9cv5kld", "r3vtjexs92yhf7e");
            //Configure Dropbox service
            $dropbox = new Kunnu\Dropbox\Dropbox($app);
            if(!$token){
                $user = Checkmydrive::getUser();
                if(isset($user->params->dropbox)){
                    $token = $user->params->dropbox;
                }
            }
            if($token) $token = $dropbox->setAccessToken($token->token);
        }
        return $dropbox;
    }
    
    public static function saveToken(){
        $user = Checkmydrive::getUser();
        if (isset($_GET['code']) && $user->id && isset($_GET['state'])) {
            $code = $_GET['code'];
            $state = $_GET['state'];
            $dropbox = self::getDropbox();
            $accessToken = $dropbox->getAuthHelper()->getAccessToken($code, $state, Checkmydrive::root());
            $token = $accessToken->getToken();
            if($token){
                $db = Checkmydrive::getDbo(true);
                $params = @$user->params;
                if(!$params) $params = new stdClass();
                $dropbox->setAccessToken($token);
                $info = $dropbox->getCurrentAccount();
                $params->dropbox = array(
                    'token' => $token,
                    'info' => array(
                        'name' => $info->getDisplayName(),
                        'email' => $info->getEmail(),
                    )
                );
                $params->use = 'dropbox';
                $db->where('id', $user->id)->set('params',  json_encode($params))->update('users');
                echo "<div style='text-align: center'><h2>Authenticated user's Dropbox successfull</h2><script>window.close()</script></div>";
                die();
            }
        }
    }
    
    
    public static function upload($source, $name){
        $user = Checkmydrive::getUser();
        if(isset($user->params->dropbox)){
            $dropbox = self::getDropbox();
            $dropboxFile = new Kunnu\Dropbox\DropboxFile($source);
            $file = $dropbox->upload($dropboxFile, "/$name", array('autorename' => true));
            if($file){
                $data = $dropbox->getTemporaryLink("/$name");
                return json_encode (array('fileID' => $file->id , 'url' => $data->getLink())); 
            }
        }else{
            Checkmydrive::setMessage ('Are you not  dropbox drive');
        }
        return false;
    }
    
    public static function delete($filename){
        if($filename){
            $dropbox = self::getDropbox();
            return $dropbox->delete("/$filename");
        }
        return false;
    }
    
    public static function getInfo(){
        static $info;
        if(!isset($info)){
            $user = Checkmydrive::getUser(0, true);
            if(isset($user->params->dropbox->info)){
                $info = $user->params->dropbox->info;
                @$info->class = '';
            }else{
                $info = (object) array(
                    'class' => 'jkhide'
                );
            }
        }
        return $info;
    }
 
    
    
    
}
    
   
