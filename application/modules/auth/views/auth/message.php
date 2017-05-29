<h3 class="page-title"><?php echo Checkmydrive::_('Message'); ?></h3>
<div class="auth-form message-page">
    <?php if(isset($type) && $type =='register'){ ?>
        <p><img src="<?php echo Checkmydrive::root(); ?>images/register-sucess.png" /></p>
    <?php } ?>
    <?php echo $message; ?>
</div>