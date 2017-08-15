<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google extends Public_Controller
{
    public $client;
    function __construct()
    {         
        parent::__construct(); 
        $this->load->library('DriveApi');        
        $this->model = Checkmydrive::getModel('Drive');
        $params = Checkmydrive::getUser()->params;
        $token = $params->google;
        $client = $this->client = DriveApi::getClient($token);
        if(!$this->client->isAccessTokenExpired()){
            if(isset($client->refresh_token)){
                $token = $client->getAccessToken();
                $token['refresh_token'] = $client->refresh_token;
                $this->model->saveToken($token);
            }
            
            if($this->router->method == 'auth'){
                return redirect('/google');
            }
        }else if($this->router->method != 'auth'){
            redirect('/google/auth');
        }
        
    }
    
    public function auth()
    {   
        $this->client->setRedirectUri(Checkmydrive::root().'google/auth');
        if($code = $this->input->get('code')){
            try{                
                $token = $client->fetchAccessTokenWithAuthCode($code);
            } catch (Exception $e){
                redirect($this->client->createAuthUrl());
            }
            
            if($this->model->saveToken($token) == -2){
                redirect($this->client->createAuthUrl());
            }else{
                return redirect('/google');
            }
        }else{
            redirect($this->client->createAuthUrl());
            //$this->template->build(__FUNCTION__,'google');
        }        
    }
    public function index()
    {
        //$this->user = CheckmydriveHelper::getUser();
        $view = strtolower(get_class($this));        
        //$info = DriveApi::getInfo();
        //DriveApi::testList(); die();
        $this->template->build($view);
    }
    
    public function get_files(){
        $files = DriveApi::listFiles($this->input->get('pagetoken'));
        $fs = $files->getFiles();
//        foreach($fs as $file){            
//            $this->model->updateFile($file);
//            $pemisions = $file->getPermissions();
//            if(count($pemisions) > 0){
//                print_r($pemisions);
//                //new Google_Service_Drive_DriveFile();
//            }
//        }
        die(json_encode(array(
            'files' => $fs,
            'nextPageToken' => $files->getNextPageToken(),
            'success' => true
        )));
        new Google_Service_Drive_FileList();
    }


    public function update_file(){
        $file = DriveApi::getFile($this->input->get('file'));
        die(json_encode(array(
            'success' => true,
            'file' => $file
        )));
    }

    public function user_access(){
        $this->index();
    }
    public function shared(){
        $this->index();
    }
    public function emptyFiles(){
        $this->index();
    }
    
   public function app(){
       $view = strtolower(get_class($this));
       $this->model = Checkmydrive::getModel('Drive');
        
        $data = array();
        $this->template->build(__FUNCTION__, $data);
    }
    
    public function folder(){
        $view = strtolower(get_class($this));
        $this->model = Checkmydrive::getModel('Drive');
        $data = array();
        $this->template->build(__FUNCTION__, $data);
    }
   
    
    public function files(){
        $view = strtolower(get_class($this));
        $this->model = Checkmydrive::getModel('Drive');
        
        
        $data = array();
        $this->template->build(__FUNCTION__, $data);
    }
}
