<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    public function index() {	
        $data = array();
        $this->template->build('dashboard',$data);       
    }
    
   
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */