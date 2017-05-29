<?php
$config = Checkmydrive::getConfigs();
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Username';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);

?>
<div class="center-content">
<?php echo form_open($this->uri->uri_string()); ?>

<h3 class="page-title">Welcome</h3>
<h5 class="page-title">Login to your account</h5>

<div class='logintable'>
    
    <div class="login-row">
		<div class="login-label"><?php echo form_label($login_label, $login['id']); ?></div>		
		<div class="login-control"><?php echo form_input($login); ?></div>
		<div class="error"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>
    </div>
	<div class="login-row">
		<div class="login-label"><?php echo form_label('Password', $password['id']); ?></div>
		<div class="login-control"><?php echo form_password($password); ?></div>
		<div class="error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>
	</div>

	<div class="login-row">
		<div class="login-footer">
                    <input type="hidden" value="<?php echo isset($_GET['return'])?$_GET['return'] : '' ; ?>" name="return" />
                    <div style="text-align: center">
			<?php echo form_checkbox($remember); ?>
			<?php echo form_label('Remember me', $remember['id']); ?>   &nbsp;&nbsp;&nbsp;   
                        <a href="<?php echo Checkmydrive::route('forgot_password'); ?>">Forgot password</a> &nbsp;&nbsp;&nbsp; 
                        <a href="<?php echo Checkmydrive::route('register'); ?>">Register</a>
                    </div>
		</div>
	</div>
</div>

<div class="text-center row-submit"><?php echo form_submit('submit', 'ENTER'); ?>
<?php echo form_close(); ?>
</div>
