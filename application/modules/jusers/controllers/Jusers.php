<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jusers extends Admin_Controller
{
    function __construct() {
        parent::__construct();
        Checkmydrive::loadForm();
        $this->table = 'users';
        $this->form_validation->set_rules('jform[email]', 'Email', 'trim|required|valid_email');	
        $this->form_validation->set_rules('jform[name]', 'Name', 'trim|required');	
        //$this->form_validation->set_rules('jform[password]', 'Password', 'matches[jform[password2]]');
        //$this->form_validation->set_rules('jform[username]', 'Login Name', 'trim|required');	
    }
    public function index()
    {	
    
        $task = Checkmydrive::input()->post_get('task');
        $view = Checkmydrive::uri()->view;
        if($task == 'new' || $task =='edit'){
            $cid = Checkmydrive::input()->post('cid');
            $id = (isset($cid))?$cid[0]: '';
            redirect(Checkmydrive::route("/$view/edit/$id" . $ticket));
        }else if($task=='save' || $task=='apply'){
            $this->save();
        }else if($task =='delete'){
            $this->delete();
        }else if($task =='unpublish' || $task =='publish'){
            $this->publish($task);
        }
        $data = array();
        $model = Checkmydrive::getModel($view);
        $this->model		= $model;
        $this->items		= $model->getItems();
        $this->pagination	= Checkmydrive::pagination($view, $model->getTotal());
        $data['sortFields'] = $this->getSortFields();
        $data['status'] = $this->getStatusOptions();
        Checkmydrive::addFilter(Checkmydrive::select('filter_status', Checkmydrive::options($this->getStatusOptions(),$model->getState('filter.status')), false, false, true));
        Checkmydrive::addFilter(Checkmydrive::select('filter_users', Checkmydrive::options($this->getUserTypeOptions(),$model->getState('filter.users')), false, false, true));

        
        $this->sidebar = Checkmydrive::renderFilter();
        $this->template->build($view.'_default',$data);    
    }
    
    public function delete(){
         $view = Checkmydrive::uri()->view;
         $cid = Checkmydrive::input()->post_get('cid');
         $return = Checkmydrive::delete($this->table, $cid);
         $count = count($cid);
         Checkmydrive::setMessage("$count item successfully deleted");
         redirect(Checkmydrive::route("/$view"));
     }
    
     public function publish($task){
         if($task == 'publish') $stats = 1;
         else $stats = 0;
         $cid = Checkmydrive::input()->post_get('cid');
         $view = Checkmydrive::uri()->view;
         $db = Checkmydrive::getDbo(true);
         $db->where('id IN (' . implode(',', $cid) . ')' );
         $db->set('activated', $stats);
         $db->update($this->table);
         $count = count($cid);
         Checkmydrive::setMessage("$count item successfully {$task}ed");
         redirect(Checkmydrive::route("/$view"));
     }
    
    
    protected function getSortFields()
    {
        return array(
            'a.status'        => Checkmydrive::_('CHECKMYDTIVE_STATUS'),
            'a.name'        => Checkmydrive::_('CHECKMYDTIVE_NAME'),
            'project_name'     => Checkmydrive::_('CHECKMYDTIVE_PROJECT'),
            'a.priority' => Checkmydrive::_('CHECKMYDTIVE_PRIORITY'),
            'a.start'          => Checkmydrive::_('CHECKMYDTIVE_START'),
            'a.end'          => Checkmydrive::_('CHECKMYDTIVE_END'),
            'assigned_name' => Checkmydrive::_('CHECKMYDTIVE_ASSIGNED'),
            'a.billtime' => Checkmydrive::_('CHECKMYDTIVE_TIME'),
            'a.ticket_id'          => Checkmydrive::_('CHECKMYDTIVE_TICKET_REFERENCE'),
            'a.visible'     => Checkmydrive::_('CHECKMYDTIVE_VISIBLE'),
            'a.id'          => Checkmydrive::_('JGRID_HEADING_ID')
        );
    }

   
    public function getStatusOptions(){
        $options = array();
        $options[] = (object)array('value' => '', 'text' => Checkmydrive::_('- Select Status- '));
        $options[] = (object)array('value' => -1, 'text' => Checkmydrive::_('CHECKMYDTIVE_STATUS_UNOPEN'));
        $options[] = (object)array('value' => 0, 'text' => Checkmydrive::_('CHECKMYDTIVE_STATUS_OPEN'));
        $options[] = (object)array('value' => 1, 'text' => Checkmydrive::_('CHECKMYDTIVE_STATUS_DONE'));
        return $options;
    }
    

    public function getUserTypeOptions(){
        $options = array();
        $options[] = (object)array('value' => '', 'text' => Checkmydrive::_('- Select User Type- '));
        $options[] = (object)array('value' => 3, 'text' => Checkmydrive::_('Super User'));
        $options[] = (object)array('value' => 2, 'text' => Checkmydrive::_('Subscriber'));
        $options[] = (object)array('value' => 1, 'text' => Checkmydrive::_('Registered'));
        return $options;
    }
    
    
    public function save(){
        
        $post = Checkmydrive::input()->post();
        $view = Checkmydrive::uri()->view;
        $model = Checkmydrive::getModel($view);
        $data = $post['jform'];
        $CI = get_instance();
        //save user
        $db = Checkmydrive::getDbo(true);
        $id = $data['id'];
        
        if(!$this->form_validation->run()){
            $msg = $this->form_validation->error_array();
            foreach ($msg as $f=>$m)  Checkmydrive::setMessage ($m, 'error');
            redirect(Checkmydrive::route("/$view/edit/$id"));
            return;
        }
        $data['username'] = $data['email'];
        $sucess = false;
        if($data['id']){
            if($data['password']){
                if($data['password'] == $data['password2']){
                    $hasher = new PasswordHash(
                        $CI->config->item('phpass_hash_strength', 'tank_auth'),
                        $CI->config->item('phpass_hash_portable', 'tank_auth')
                    );
                    $data['password'] = $hasher->HashPassword($data['password']);
                    unset($data['password2']);
                    
                }else{
                    Checkmydrive::setMessage ('The passwords you entered do not match!');
                    redirect(Checkmydrive::route("/$view/edit/$id"));
                }
            }else{
                unset($data['password']);
                unset($data['password2']);
            }
            $db->where('id', $data['id']);
            $sucess = $db->update('users', $data);
        }else{
            if($data['password'] !== $data['password2']) {
                Checkmydrive::setMessage ('The passwords you entered do not match!');
                redirect(Checkmydrive::route("/$view/edit/$id"));
            }
            $save = $this->tank_auth->create_user($data['username'],$data['email'],$data['password'],false);
            if(($save) && $save['user_id']){
                unset($data['password']);
                unset($data['password2']);
                $data['id'] = $save['user_id'];
                $db->where('id', $save['user_id']);
                $sucess = $db->update('users', $data);
            }
        }
        if($sucess) Checkmydrive::setMessage ('User save successfull!');
        else{
            $error = $this->tank_auth->get_error_message();
            Checkmydrive::setMessage (implode("\n", $error));
        }
        if($post['task'] == 'apply'){
            $id = $data['id'];
            redirect(Checkmydrive::route("/$view/edit/$id"));
        }else{
            redirect(Checkmydrive::route("/$view"));
        }
    }
    
    
    
    public function edit(){
        $data = array();
        $uri = Checkmydrive::uri();
        $model = Checkmydrive::getModel($uri->view);
        $this->model		= $model;
        $this->item		= $model->getItem($uri->id);
        $this->form = Checkmydrive::getForm('user', $this->item , array('control' => 'jform', 'load_data' => true));
        $this->template->build($uri->view . '_' . $uri->layout,$data);   
    }
    
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */