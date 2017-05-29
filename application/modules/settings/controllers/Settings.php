<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller
{
    function __construct() {
        parent::__construct();
        Checkmydrive::loadForm();
        
    }
    public function index()
    {	
        $task = Checkmydrive::input()->post_get('task');
        $view = Checkmydrive::uri()->view;
        
        if($task=='save' || $task=='apply'){
            $this->save();
        }
        $data = array();
        $model = Checkmydrive::getModel($view);
        $this->model		= $model;
        $this->item		= $model->getItem(1);
        $this->form = Checkmydrive::getForm('config', $this->item , array('control' => 'jform', 'load_data' => true));
        $this->template->build($view.'_default',$data);    
    }
    
  

    public function save(){
        
        $post = Checkmydrive::input()->post();
        $view = Checkmydrive::uri()->view;
        $model = Checkmydrive::getModel($view);
        $data = $post['jform'];
        $sucess = $model->save($data);
        if($sucess) Checkmydrive::setMessage(Checkmydrive::_("CHECKMYDTIVE_SAVE_SUCCESS"));
        else Checkmydrive::setMessage(Checkmydrive::_("CHECKMYDTIVE_SAVE_ERROR"));
        redirect(Checkmydrive::route("/$view"));
    }
    
    public function getGoogleInfo(){
        die(json_encode(Google::getInfo()));
    }
    
    public function getDropboxInfo(){
        die(json_encode(JDropbox::getInfo()));
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */