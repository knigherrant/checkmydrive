<?php (defined('BASEPATH')) or exit('No direct script access allowed');
class  Admin_Controller extends  CI_Controller {
    function __construct() {
        parent::__construct();
        date_default_timezone_set('America/New_York');
        $this->load->library('JFolder');
        $this->load->library('JFile');
        $this->load->library('Checkmydrive');
        $this->load->library('Tank_auth');
        //$this->load->helper('interval');
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->library('pagination');
        $this->load->library('form_validation');	
        //$this->load->library('image_lib');
        $this->load->library('Template');
        $this->load->helper('html');
        $this->load->library('Javascript');
        //$this->load->library('Google');
        //$this->load->library('JDropbox');
        //$this->load->helper('date');
        $this->load->helper('language');
        $this->lang->load('checkmydrive');
        $this->template->set_theme('admin');
        //Checkmydrive::createDefaultData();
        //Checkmydrive::redirectToSubsite();
        if(!$this->tank_auth->is_logged_in()) {
            redirect(Checkmydrive::route('admin'));
        }
        //$this->created_by = Checkmydrive::isSubscriber() ? implode(',', Checkmydrive::getUsersClient()) : null;
        //CheckmydriveHelper::createFolder2RemoveTemp();
        //CheckmydriveHelper::checkRequire();
        
        //$session_id = $this->session->__get('session_id');                                      
        //Checkmydrive::setSessions($session_id);
        
    }
}
class Public_Controller extends CI_Controller {
 
    function __construct()
    {
        parent::__construct(); 
        $this->load->database();
        date_default_timezone_set('America/New_York');
        $this->load->library('JFolder');
        $this->load->library('JFile');
        $this->load->helper('url');
        $this->load->library('Template');
        //$this->load->helper('interval');
        //$this->load->helper('text');
        $this->load->library('Checkmydrive');
        $this->load->library('Tank_auth');
        //$this->load->helper('string');
        $this->load->library('Javascript');
        $this->load->helper('language');
        $this->lang->load('checkmydrive');
        $this->load->helper('html');
        //$this->load->library('Google');
        //$this->load->library('JDropbox');
        //Google::saveToken();
        //JDropbox::saveToken();
        //Checkmydrive::createDefaultData();
        //Checkmydrive::redirectToSubsite();
        $currentTheme=  Checkmydrive::getDbo(true)->get('template')->result();
        
        foreach($currentTheme as $cur)
        {
            $this->theme = $cur->current_tem;
        }
        $this->template->set_theme($this->theme);	 
        if(!$this->tank_auth->is_logged_in()) {
            redirect(Checkmydrive::route('login'));
        }
        //$session_id = $this->session->__get('session_id');                                      
        //Checkmydrive::setSessions($session_id);
        //if(Checkmydrive::hasSubscriber() && !Checkmydrive::isSubscriber()) Checkmydrive::setMessage(Checkmydrive::_('The Subscription have been expired, Please re-new the Subscription'), 'warning');
          
    }
}