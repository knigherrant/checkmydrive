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
        $lastInfor = DriveApi::getInfo($params->google);
        if($lastInfor){
            $infor = DriveApi::getInfo($token);
            if($lastInfor->email != $infor->email){
                return -2;
            }            
        }
        
        $params->google = $token;
        if(!Checkmydrive::getDbo(true)->where('id', $user->id)->set('params',  json_encode($params))->update('users')) $rs = -1;
        return $rs;
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