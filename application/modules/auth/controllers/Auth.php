<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
		$this->load->helper(array('form', 'url'));
                $this->load->library('JFolder');
                $this->load->library('JFile');
		$this->load->library('form_validation');
		$this->load->library('Template');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
                $this->load->library('Checkmydrive');
                $this->load->library('Captcha');
                $this->load->helper('language');
                $this->lang->load('checkmydrive');
                $currentTheme=Checkmydrive::getDbo(true)->get('template')->result();
                foreach($currentTheme as $cur)
                {
                    $this->theme = $cur->current_tem;
                }
                $this->template->set_theme($this->theme);
                    
	}

	function index()
	{

		if ($message = $this->session->flashdata('message')) {
			$this->load->view('auth/auth/general_message', array('message' => $message));
		} else {
			redirect(Checkmydrive::route('/login/', false));
		}
	}

      
	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{
                       
                $rsegments = Checkmydrive::rsegments();
              
		if ($this->tank_auth->is_logged_in()) {		
                    if(Checkmydrive::isRedirectAdmin()){
                        redirect(Checkmydrive::route('dashboard'));
                    } else redirect(Checkmydrive::route('homepage'));
		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect(Checkmydrive::route('/send_again/', false));

		} else {
			$data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
                        $this->config->item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

                        
			$this->form_validation->set_rules('login', 'Login', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');

                        
			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}
			$data['errors'] = array();
    
                        
                        
			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'),
						$data['login_by_username'],
						$data['login_by_email'])) {
                                        $return = $this->input->get_post('return');
                                        if(Checkmydrive::isRedirectAdmin()) redirect(Checkmydrive::route('dashboard')); 
                                        else if($return) redirect(base64_decode($return)); 
                                        else{
                                             //if($this->session->userdata('userlevel') == 2) redirect(Checkmydrive::route('summary'));
                                             redirect(Checkmydrive::route('homepage'));
                                        }
                                        // success
				} else {
					$errors = $this->tank_auth->get_error_message();
					if (isset($errors['banned'])) {								// banned user
						$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);

					} elseif (isset($errors['not_activated'])) {				// not activated user
						redirect(Checkmydrive::route('/send_again/',false));

					} else {													// fail
						foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					}
				}
			}
			
			$this->template->set_layout('login')
			->build('auth/auth/login_form',$data);
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->tank_auth->logout();
		redirect(Checkmydrive::route('homepage'));

	//	$this->_show_message($this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register()
	{
                $config = Checkmydrive::getConfigs();
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect(Checkmydrive::root());

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect(Checkmydrive::route('/send_again/',false));

		} elseif (!$config->allow_registration) {	// registration is off
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));

		} else {
			$use_username = !$config->login_by_email;
			if ($use_username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_numeric');
			}
                        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
                        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			//$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			//$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');

			$data['errors'] = array();
                        $password = Checkmydrive::generateStrongPassword(8);
			$email_activation = $config->activation;
                        $captcha = true;// Captcha::checkCaptcha();
			if ($this->form_validation->run() && $captcha) {								// validation ok
				if (!is_null($data = $this->tank_auth->create_user(
						$this->input->post('email'),
						$this->input->post('email'),
						$password,
						$email_activation))) {									// success
					$data['site_name'] = $config->sitename;
                               
					if ($email_activation) {									// send "activate" email
						$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;
						$this->_send_email('activate', $data['email'], $data);
						unset($data['password']); // Clear password (just for any case)

					} else {
						if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email

							$this->_send_email('welcome', $data['email'], $data);
						}
						unset($data['password']); // Clear password (just for any case)
					}
                                        if(@$_POST['subscription']> 0){
                                            $this->paypal($data);
                                            return;
                                        }else $this->_show_message($this->lang->line('auth_message_registration_completed_1'), 'register');
				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
                        if(!$captcha && Checkmydrive::input()->get_post('register')) $data['captcha'] = Checkmydrive::_ ('Captcha invalid');
			$data['use_username'] = $use_username;
			$this->template
			->set_layout('login')
			->build('auth/auth/register_form',$data);
			//$this->load->view('auth/auth/register_form', $data);
		}
	}

        
        function renew($data = array()){
            $user = Checkmydrive::getUser();
            if($user->user_level == 3){
               Checkmydrive::setMessage('Your account is SuperUser can not renew');
               redirect(Checkmydrive::route ());
            }else{
                $data = array('user_id' => $user->id);
                $this->session->set_userdata('renew', true);
                $this->paypal($data);
            }
        }
        
        function paypal($data = array()){
            if(!$data['user_id']) {
                Checkmydrive::setMessage ('Can\'t access this page', 'error');
                redirect(Checkmydrive::route('/homepage/'));
            }
            $month = Checkmydrive::input()->get('month');
            if($month == md5(12)) $month = 12;
            else if( (isset($_POST['subscription'])) &&  $_POST['subscription'] == 12) $month = 12;
            else $month = 1;
            $data['month'] = $month;
            $this->session->set_userdata('userId', $data['user_id']);
            $this->session->set_userdata('month', $month);
            $this->template
			->set_layout('login')
			->build('auth/auth/paypal',$data);
        }
        
        public function subscription(){
            $input = Checkmydrive::input();
            $return  = $input->get('return');
            if($return =='success'){
                $configs = Checkmydrive::getConfigs(true);
                $userid = $this->session->userdata('userId');
                $reNew = $this->session->userdata('renew');
                $month = $this->session->userdata('month');
                if($input->post('mc_gross') && $input->post('payer_id') && $userid){
                    $value = (int) $input->post('mc_gross');
                    if($month == 1) $dValue = (int) $configs->subscription;
                    else $dValue = (int) ($configs->subscription * $month *90 / 100);
                    if($value == $dValue){
                        $db = Checkmydrive::getDbo(true);
                        $user = Checkmydrive::getUser($userid);
                        $update = array();
                        if($reNew){
                            $update['subscriber_end'] = Checkmydrive::getDate($user->subscriber_end, '+'.$month.' month')->toSql;
                        }
                        $update['subscription'] = 1;
                        $update['params'] = json_encode($input->post());
                        $db->where('id', $userid);
                        $db->update('users', $update);
                        Checkmydrive::setMessage('Subscription Successfull');
                        //subscription success
                        if($reNew) redirect(Checkmydrive::route ( 'summary'));
                        else redirect(Checkmydrive::route ('/subscriber/'));
                    }else Checkmydrive::setMessage('Subscription fail value', 'error');
                }else Checkmydrive::setMessage('Subscription fail', 'error');
            }else if($return =='cancel')  Checkmydrive::setMessage('Subscription cancel', 'error');
            else Checkmydrive::setMessage('Subscription fail', 'error');

            redirect(Checkmydrive::route ('/login/'));
        }
    
        function subscriber(){
            $data['userid'] = $this->session->userdata('userId');
            $this->template
			->set_layout('login')
			->build('auth/auth/subscription',$data);
        }
        
	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function send_again()
	{
		if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
			redirect(Checkmydrive::route('/login/'));

		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();
                        $config = Checkmydrive::getConfigs();
			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->change_email(
						$this->form_validation->set_value('email')))) {			// success

					$data['site_name']	= $config->sitename;
					$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

					$this->_send_email('activate', $data['email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			//$this->load->view('auth/auth/send_again_form', $data);
						$this->template
			->set_layout('login')
			->build('auth/auth/send_again_form',$data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate()
	{
            
                $rsegments = Checkmydrive::rsegments();
		$user_id		= $rsegments[3];
		$new_email_key	= $rsegments[4];
		// Activate user
		if ($this->tank_auth->activate_user($user_id, $new_email_key)) {		// success
				Checkmydrive::createDefaultData(Checkmydrive::getUser($user_id)->username);
				//$this->tank_auth->logout();
				//Checkmydrive::setMessage("Thank you, you're account is activated");
			$this->_show_message("Thank you, your account is now activated".' <p>Please Login to your '.anchor('/login/', 'Dashboard') . '</p>');

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgot_password()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect(Checkmydrive::route());

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect(Checkmydrive::route('/send_again/'));

		} else {
			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			$data['errors'] = array();
                        $config = Checkmydrive::getConfigs();
			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('login')))) {

					$data['site_name'] = $config->sitename;

					// Send email with password activation link
					$this->_send_email('forgot_password', $data['email'], $data);

					$this->_show_message($this->lang->line('auth_message_new_password_sent'));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			//$this->load->view('auth/auth/forgot_password_form', $data);
			$this->template
			->set_layout('login')
			->build('auth/auth/forgot_password_form',$data);
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password()
	{
                $config = Checkmydrive::getConfigs();
                $rsegments = Checkmydrive::rsegments();
		$user_id		= $rsegments[3];
		$new_pass_key	= $rsegments[4];

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

		$data['errors'] = array();
                $config = Checkmydrive::getConfigs();
		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($data = $this->tank_auth->reset_password(
					$user_id, $new_pass_key,
					$this->form_validation->set_value('new_password')))) {	// success

				$data['site_name'] = $config->sitename;

				// Send email with new password
				$this->_send_email('reset_password', $data['email'], $data);

				$this->_show_message($this->lang->line('auth_message_new_password_activated').' '.anchor('/login/', 'Login'));

			} else {														// fail
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		} else {
			// Try to activate user by password key (if not activated yet)
			if ($config->activation) {
				$this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
			$this->template
			->set_layout('login')
			->build('auth/auth/reset_password_form',$data);
	}

	/**
	 * Change user password
	 *
	 * @return void
	 */
	function change_password()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect(Checkmydrive::route('/login/'));

		} else {
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success
					$this->_show_message($this->lang->line('auth_message_password_changed'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->template
			->set_layout('login')
			->build('auth/auth/change_password_form',$data);
			//$this->load->view('auth/auth/change_password_form', $data);
		}
	}

	/**
	 * Change user email
	 *
	 * @return void
	 */
	function change_email()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect(Checkmydrive::route('/login/'));

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();
                        $config = Checkmydrive::getConfigs();
			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password')))) {			// success

					$data['site_name'] = $config->sitename;

					// Send email with new email address and its activation link
					$this->_send_email('change_email', $data['new_email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}

			$this->template
			->set_layout('login')
			->build('auth/auth/change_email_form',$data);
			//$this->load->view('auth/auth/change_email_form', $data);
		}
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_email()
	{
                $rsegments = Checkmydrive::rsegments();
		$user_id		= $rsegments[3];
		$new_email_key	= $rsegments[4];

		// Reset email
		if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_new_email_activated').' '.anchor('/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_new_email_failed'));
		}
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect(Checkmydrive::route('/login/'));

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('password'))) {		// success
					$this->_show_message($this->lang->line('auth_message_unregistered'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			//$this->load->view('auth/auth/unregister_form', $data);
			$this->template
			->set_layout('login')
			->build('auth/auth/change_password_form',$data);
		}
	}

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message, $type = null)
	{
		$this->session->set_userdata('message', $message);
                $this->session->set_userdata('type', $type);
		redirect(Checkmydrive::route('/auth/message'));
	}

        function message()
	{
            $data = array(
                'message' =>  $this->session->userdata('message'), 
                'type' => $this->session->userdata('type')
            );
            $this->template
			->set_layout('login')
			->build('auth/auth/message', $data);
	}
        
        
	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
            $config = Checkmydrive::getConfigs();
            $msg = Checkmydrive::sendMail(
                    $config->mailfrom, 
                    $config->sitename, 
                    $email, 
                    sprintf($this->lang->line('auth_subject_'.$type), $config->sitename), 
                    $this->load->view('email/'.$type.'-html', $data, TRUE)
            );
            return true;
           
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
			'img_url'		=> Checkmydrive::route($this->config->item('captcha_path', 'tank_auth')),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
			'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
			'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
			'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
			'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
			'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;

		} elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
