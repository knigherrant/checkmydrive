<?php
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
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
<?php echo form_submit('cancel', 'Delete account'); ?>
<?php echo form_close(); ?>
</div>