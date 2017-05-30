<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Public_Controller
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

    public function closeacc(){
        $user = Checkmydrive::getUser();
        $data = array(
            'ok' => true
        );
        if(!$user->id || !Checkmydrive::hasSubscriber()){
            $data['ok'] = false;
            $data['msg'] = 'Are you not permisstion';
        }else{
            $this->tank_auth->logout();
            $db = Checkmydrive::getDbo(true);
            $db->set('banned',1)->set('ban_reason','The Account of Admin have been closed.')->where('created_by', $user->id)->update('users');
            $db->set('banned',1)->set('ban_reason','The Account have been closed')->where('id', $user->id)->update('users');
            $data['msg'] = 'The Account have been closed';
        }
        die(json_encode($data));
    }
    
    public function saveUser(){
        $CI = get_instance();
        $data = $this->input->get_post('user');
        if(!isset($data['id'])) $data['id'] = Clientrol::getUser()->id;
        $db = Clientrol::getDbo(true);
        if(isset($data['password'])){
            if($data['password'] == $data['password2']){
                $hasher = new PasswordHash(
                    $CI->config->item('phpass_hash_strength', 'tank_auth'),
                    $CI->config->item('phpass_hash_portable', 'tank_auth')
                );
                $data['password'] = $hasher->HashPassword($data['password']);
                unset($data['password2']);

            }else{
                exit(json_encode(array('rs'=>true, 'msg'=>Clientrol::_('The passwords you entered do not match!'))));
            }
        }else{
            unset($data['password']);
            unset($data['password2']);
        }
        $db->where('id', $data['id']);
        $sucess = $db->update('users', $data);
        //Set new session
        //$session = Clientrol::getSession();
        //$session->set('user', new JUser($user->id));
        exit(json_encode(array('rs'=>true, 'msg'=>Clientrol::_('CLIENTROL_MSG_SUCCESSFULLY'))));
    }
    
    
}
