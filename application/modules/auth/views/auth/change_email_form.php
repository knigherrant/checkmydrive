<?php
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
?>
<h3 class="page-title">Change Email</h3>
<div class="auth-form">
<?php echo form_open($this->uri->uri_string()); ?>
	<div class="login-row">
		<div class="login-label"><?php echo form_label('Password', $password['id']); ?></div>
		<div class="login-control"><?php echo form_password($password); ?></div>
		<div class="error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>
	</div>
	<div class="login-row">
		<div class="login-label"><?php echo form_label('New email address', $email['id']); ?></div>
		<div class="login-control"><?php echo form_input($email); ?></div>
		<div class="error"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></div>
	</div>

<div class="text-center row-submit"><?php echo form_submit('change', 'Send confirmation email'); ?></div>
<?php echo form_close(); ?>
</div>