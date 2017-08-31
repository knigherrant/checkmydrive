<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);

$country = array(
	'name'	=> 'country',
	'id'	=> 'country',
	'value'	=> set_value('country'),
);

$subscriber = array(
	'name'	=> 'subscription',
	'id'	=> 'subscription',
	'value'	=> set_value('subscription'),
);

$fname = array(
	'name'	=> 'first_name',
	'id'	=> 'first_name',
	'value'	=> set_value('first_name'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$lname = array(
	'name'	=> 'last_name',
	'id'	=> 'last_name',
	'value'	=> set_value('last_name'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$company = array(
	'name'	=> 'company',
	'id'	=> 'company',
	'value' => set_value('company'),
	'size'	=> 30,
);
$address = array(
	'name'	=> 'address',
	'id'	=> 'address',
	'value' => set_value('address'),
	'size'	=> 30,
);
$type = (Checkmydrive::isBusiness())? 'Business' : 'Personal';
?>

<script>
    jQuery(function($){
        <?php if($errors) foreach ($errors as $m) { ?>
                new PNotify({text: '<?php echo $m; ?>', type: 'alert'});
        <?php } ?>
    })
</script>

<h3 class="page-title"><?php echo Checkmydrive::_('Sign Up For Checkmydrive ' . $type); ?></h3>
<div class="auth-form">
<?php echo form_open($this->uri->uri_string()); ?>

    <div class="col-left">
    	<ul class="nav nav-tabs">
		    <li class="<?php if(!Checkmydrive::isBusiness()) echo 'active'; ?>"><a href="<?php echo Checkmydrive::route('register'); ?>">Personal</a></li>
		    <li class="<?php if(Checkmydrive::isBusiness()) echo 'active'; ?>"><a href="<?php echo Checkmydrive::route('business'); ?>">Business</a></li>
		</ul>
		<div class="content-form">
			<div class="content-detail">
	        <div class="login-row">
		        <div class="login-label"><?php echo form_label('First Name', $fname['id']); ?>* </div>
		        <div class="login-control"><span class="appmsg"><?php echo form_input($fname); ?></div>
				<div class="error"><?php echo form_error($fname['name']); ?><?php echo isset($errors[$fname['name']])?$errors[$fname['name']]:''; ?></div>
			</div>
	        <div class="login-row">
		        <div class="login-label"><?php echo form_label('Last Name', $lname['id']); ?>* </div>
		        <div class="login-control"><span class="appmsg"><?php echo form_input($lname); ?></div>
				<div class="error"><?php echo form_error($lname['name']); ?><?php echo isset($errors[$lname['name']])?$errors[$lname['name']]:''; ?></div>
			</div>
	        
	        <div class="login-row email">
				<div class="login-label"><?php echo form_label('Email Address', $email['id']); ?>*</div>
				<div class="login-control"><?php echo form_input($email); ?></div>
				<div class="error"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></div>
			</div>
		<?php if(Checkmydrive::isBusiness()){ ?>
	            <div class="login-row">
	                <div class="login-label"><?php echo form_label('Company', $company['id']); ?> </div>
	                <div class="login-control"><span class="appmsg"><?php echo form_input($company); ?></div>
	                <div class="error"><?php echo form_error($company['name']); ?><?php echo isset($errors[$company['name']])?$errors[$company['name']]:''; ?></div>
	            </div>
	            <div class="login-row">
	                <div class="login-label"><?php echo form_label('Address', $address['id']); ?> </div>
	                <div class="login-control"><span class="appmsg"><?php echo form_input($address); ?></div>
	                <div class="error"><?php echo form_error($address['name']); ?><?php echo isset($errors[$address['name']])?$errors[$address['name']]:''; ?></div>
	            </div>
	        <?php } ?>
	        <div class="login-row sub">
				<div class="login-label"><?php echo form_label('Subscription', $subscriber['id']); ?>*</div>
		        <div class="login-control"><?php echo Checkmydrive::createSelect($subscriber['value']); ?></div>
				<div class="error"><?php echo form_error($subscriber['name']); ?></div>
			</div>
	        <!--
	        <div class="login-row">
	            <?php if(isset($captcha)){ ?>
	            <div class="error"><?php echo $captcha; ?></div>
	            <?php } ?>
	            <?php echo Captcha::showCaptcha(); ?>
	        </div>
	        -->
	        <div class="text-center row-submit"><?php echo form_submit('register', 'Register'); ?></div>
	        
	    </div>
	    </div>
 	</div>   
    <div class="col-right">
        <img src="<?php echo Checkmydrive::root(); ?>images/register.png" />
    </div>
      
    
<?php echo form_close(); ?>
</div>