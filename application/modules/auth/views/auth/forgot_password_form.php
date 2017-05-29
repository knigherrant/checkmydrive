<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<h3 class="page-title">Forgot password</h3>
<div class="auth-form">
<?php echo form_open($this->uri->uri_string()); ?>
<div class="login-row">
	<div class="login-label"><?php echo form_label($login_label, $login['id']); ?></div>
	<div class="login-control"><?php echo form_input($login); ?></div>
	<div class="error"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>
</div>
<div class="text-center row-submit"><?php echo form_submit('reset', 'Get a new password'); ?></div>
<?php echo form_close(); ?>
</div>