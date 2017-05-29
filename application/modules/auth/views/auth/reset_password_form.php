<?php
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
<h3 class="page-title">Reset password</h3>
<div class="auth-form">
<?php echo form_open($this->uri->uri_string()); ?>

	<div class="login-row">
		<div class="login-label"><?php echo form_label('New Password', $new_password['id']); ?></div>
		<div class="login-control"><?php echo form_password($new_password); ?></div>
		<div class="error"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></div>
	</div>
	<div class="login-row">
		<div class="login-label"><?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?></div>
		<div class="login-control"><?php echo form_password($confirm_new_password); ?></div>
		<div class="error"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></div>
	</div>

<div class="text-center row-submit"><?php echo form_submit('change', 'Change Password'); ?></div>
<?php echo form_close(); ?>
</div>