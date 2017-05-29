<?php
$config = CheckmydriveHelper::getConfigs();
?>
<header role="banner" class="header">
        <div class="header-inner clearfix">
            <a href="<?php echo Checkmydrive::root(); ?>" class="brand pull-left">
                    <img alt="<?php echo $config->get('sitename'); ?>" src="<?php echo Checkmydrive::root() . $config->get('logo'); ?> ">
                </a>
        </div>
</header>
<?php $message = Checkmydrive::getMessage(); ?>
<script>
    jQuery(function($){
        <?php if($message) foreach ($message as $msg) foreach ($msg as $t=>$m){ ?>
                new PNotify({text: '<?php echo $m; ?>', type: '<?php echo $t; ?>'});
        <?php } ?>
    })
</script>