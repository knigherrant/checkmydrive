<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends Public_Controller
{
    function __construct()
    {
        parent::__construct();

    }
    public function index()
    {
        $this->user = CheckmydriveHelper::getUser();
        $view = strtolower(get_class($this));
        $this->template->build($view);
    }

    
    
   
}
