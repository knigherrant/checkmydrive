<?php
$config = Checkmydrive::getConfigs(true);
if(@$month == 12){
    $price = $config->subscription * 12 * 90 / 100;
    $msg = Checkmydrive::_('CHECKMYDTIVE_PURCHASE_12MONTH');
}
else{
    $price = $config->subscription;
    $msg = Checkmydrive::_('CHECKMYDTIVE_PURCHASE_1MONTH');
}

?>
<script>
    jQuery(function($){
        $('.ct_paypal_form').submit();
    })
</script>
<div class="paypal-loading" style="text-align: center">
    <img src="<?php echo Checkmydrive::urlTheme(true) . 'assets/images/loading-p.gif'; ?>" />
    <div class="jomkungfu jform" style="display: none">
        <?php echo CheckmydriveHelper::buildPaypalForm( 
            Checkmydrive::route('subscription?return=success'),
            Checkmydrive::route('subscription?return=cancel'),
            $msg,
            1,
            CheckmydriveHelper::formatMoney($price),
            'USD',
            'fa fa-check',
            Checkmydrive::_('CHECKMYDTIVE_PURCHASE'),
            @$user_id,
            true
        );?>
    </div>
</div>


