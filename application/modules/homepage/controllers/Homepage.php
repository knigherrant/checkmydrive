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
        $this->google =  $this->getGoogle();
        
        
        $view = strtolower(get_class($this));
        
        
        
        $this->template->build($view);
    }
    
    private function getGoogle(){
        
        if(!isset($this->user->params->google)) return;
        return DriveApi::getInfo($this->user->params->google);
    }
    
    private function getDropbox(){
        
    }
    
}
