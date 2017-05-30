<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google extends Public_Controller
{
    function __construct()
    {
        parent::__construct();

    }
    public function index()
    {
        $this->user = CheckmydriveHelper::getUser();
        $view = strtolower(get_class($this));
        $this->model = Checkmydrive::getModel($view);
        $this->template->build($view);
    }

    
    public function user(){
        $view = strtolower(get_class($this));
        $this->model = Checkmydrive::getModel($view);
        $data = array();
        $this->template->build(__FUNCTION__, $data);
    }
    
   public function app(){
       $view = strtolower(get_class($this));
       $this->model = Checkmydrive::getModel($view);
        
        $data = array();
        $this->template->build(__FUNCTION__, $data);
    }
    
    public function folder(){
        $view = strtolower(get_class($this));
        $this->model = Checkmydrive::getModel($view);
        $data = array();
        $this->template->build(__FUNCTION__, $data);
    }
   
    
    public function files(){
        $view = strtolower(get_class($this));
        $this->model = Checkmydrive::getModel($view);
        
        
        $data = array();
        $this->template->build(__FUNCTION__, $data);
    }
}
