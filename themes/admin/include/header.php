<?php $message = Checkmydrive::getMessage(); ?>
<script>
    jQuery(function($){
        <?php if($message) foreach ($message as $msg) foreach ($msg as $t=>$m){ ?>
                new PNotify({text: '<?php echo $m; ?>', type: '<?php echo $t; ?>'});
        <?php } ?>
    })
</script>