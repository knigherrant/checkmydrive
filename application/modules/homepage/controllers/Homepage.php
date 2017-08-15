<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends Public_Controller
{
    function __construct()
    {
        parent::__construct();

    }
    public function index()
    {
        
        $this->load->library('DriveApi'); 
        
        $this->user = CheckmydriveHelper::getUser();
        $this->google = DriveApi::getInfo($this->user->params->google);
        
        $view = strtolower(get_class($this));
        
        
        
        $this->template->build($view);
    }
    
    private function getGoogle(){
        return Checkmydrive::getUser()->params->google;
    }
    
    private function getDropbox(){
        
    }
    
}
