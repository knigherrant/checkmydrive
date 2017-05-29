<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->table_name			= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('id', $user_id);
		$db->where('activated', $activated ? 1 : 0);

		$query = $db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('LOWER(username)=', strtolower($login));
		$db->or_where('LOWER(email)=', strtolower($login));

		$query = $db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('LOWER(username)=', strtolower($username));

		$query = $db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('LOWER(email)=', strtolower($email));

		$query = $db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
            $db = Checkmydrive::getDbo(true);
		$db->select('1', FALSE);
		$db->where('LOWER(username)=', strtolower($username));

		$query = $db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
            $db = Checkmydrive::getDbo(true);
		$db->select('1', FALSE);
		$db->where('LOWER(email)=', strtolower($email));
		$db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data, $activated = TRUE)
	{
            $db = Checkmydrive::getDbo(true);
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;
                if(isset($_POST['first_name'])) $data['name'] = $_POST['first_name'] . ' ' . $_POST['last_name'];
                if(isset($_POST['jform']['first_name'])) $data['name'] = $_POST['jform']['first_name'] . ' ' . $_POST['jform']['last_name'];
                
                if(Checkmydrive::isBusiness()) $data['user_level'] = 2;
                else{
                    if(isset($_POST['jform']['user_level'])) $data['user_level'] = $_POST['jform']['user_level'];
                    else $data['user_level'] = 1;
                }
                //User Subscription
                if(isset($_POST['subscription'])){
                    $data['subscriber_start'] = Checkmydrive::getDate()->toSql;
                    $data['subscriber_end'] = Checkmydrive::getDate('now', '+1 month')->toSql;
                    //$data['subscriber_end'] = Checkmydrive::getDate('now', '+20 year')->toSql;
                }
                if( (isset($data['user_level'])) &&  $data['user_level']== 3){
                    if(!Checkmydrive::isSuperUser()) return false;
                }
                //if(isset($_POST['country'])) $data['info'] = $_POST['country'];
		if ($db->insert($this->table_name, $data)) {
			$user_id = $db->insert_id();
			if ($activated)	$this->create_profile($user_id);
			return array('user_id' => $user_id);
		}
		return NULL;
	}

	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email)
	{
                $db = Checkmydrive::getDbo(true);
		$db->select('1', FALSE);
		$db->where('id', $user_id);
		if ($activate_by_email) {
			$db->where('new_email_key', $activation_key);
		} else {
			$db->where('new_password_key', $activation_key);
		}
		$db->where('activated', 0);
		$query = $db->get($this->table_name);
		if ($query->num_rows() == 1) {
			$db->set('activated', 1);
			//$db->set('new_email_key', NULL);
			$db->where('id', $user_id);
			$db->update($this->table_name);
			$this->create_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('activated', 0);
		$db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$db->delete($this->table_name);
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('id', $user_id);
		$db->delete($this->table_name);
		if ($db->affected_rows() > 0) {
			$this->delete_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key)
	{
            $db = Checkmydrive::getDbo(true);
		$db->set('new_password_key', $new_pass_key);
		$db->set('new_password_requested', date('Y-m-d H:i:s'));
		$db->where('id', $user_id);

		$db->update($this->table_name);
		return $db->affected_rows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
	{
            $db = Checkmydrive::getDbo(true);
		$db->select('1', FALSE);
		$db->where('id', $user_id);
		$db->where('new_password_key', $new_pass_key);
		//$db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

		$query = $db->get($this->table_name);
		return $query->num_rows() == 1;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
	{
            $db = Checkmydrive::getDbo(true);
		$db->set('password', $new_pass);
		$db->set('new_password_key', NULL);
		$db->set('new_password_requested', NULL);
		$db->where('id', $user_id);
		$db->where('new_password_key', $new_pass_key);
		//$db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

		$db->update($this->table_name);
		return $db->affected_rows() > 0;
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass)
	{
            $db = Checkmydrive::getDbo(true);
		$db->set('password', $new_pass);
		$db->where('id', $user_id);

		$db->update($this->table_name);
		return $db->affected_rows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key, $activated)
	{
            $db = Checkmydrive::getDbo(true);
		$db->set($activated ? 'new_email' : 'email', $new_email);
		$db->set('new_email_key', $new_email_key);
		$db->where('id', $user_id);
		$db->where('activated', $activated ? 1 : 0);

		$db->update($this->table_name);
		return $db->affected_rows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
            $db = Checkmydrive::getDbo(true);
		$db->set('email', 'new_email', FALSE);
		$db->set('new_email', NULL);
		$db->set('new_email_key', NULL);
		$db->where('id', $user_id);
		$db->where('new_email_key', $new_email_key);

		$db->update($this->table_name);
		return $db->affected_rows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
            $db = Checkmydrive::getDbo(true);
		$db->set('new_password_key', NULL);
		$db->set('new_password_requested', NULL);

		if ($record_ip)		$db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$db->set('last_login', date('Y-m-d H:i:s'));

		$db->where('id', $user_id);
		$db->update($this->table_name);
	}

	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('id', $user_id);
		$db->update($this->table_name, array(
			'banned'		=> 1,
			'ban_reason'	=> $reason,
		));
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('id', $user_id);
		$db->update($this->table_name, array(
			'banned'		=> 0,
			'ban_reason'	=> NULL,
		));
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id)
	{
            $db = Checkmydrive::getDbo(true);
		$db->set('user_id', $user_id);
		return $db->insert($this->profile_table_name);
	}

	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	private function delete_profile($user_id)
	{
            $db = Checkmydrive::getDbo(true);
		$db->where('user_id', $user_id);
		$db->delete($this->profile_table_name);
	}
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */