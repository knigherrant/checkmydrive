<?php
$user = Checkmydrive::getUser($this->session->userdata('userId'));
$config = Checkmydrive::getConfigs();
if(!$user){
    redirect(Checkmydrive::route('homepage'));
    Checkmydrive::setMessage('Can\'t access this page', 'error');
}
?>

<div class="subscription logintable">
    <p><?php echo Checkmydrive::_('Are you Subscription successfull') ;?></p>
    <p>Subscription Days Expiry is: <?php echo $user->subscriber_end; ?></p>
    <div><?php echo $this->lang->line('auth_message_registration_completed_1'); ?></div>
</div>


