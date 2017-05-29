<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <?php 
    $them_path = $this->template->get_theme_path(); 
    $theme_url = base_url() . $them_path;
    $template = (object) $template;
	$class = isset($this->uri->rsegments[2])? $this->uri->rsegments[2] . '-bg' : 'homepage';
        
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $template->title; ?></title>
    <link href="<?php echo Checkmydrive::urlTheme().'assets/css/template.css' ;?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo Checkmydrive::urlTheme() . 'assets/css/login.css' ;?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo Checkmydrive::urlTheme(true).'assets/pnotify/pnotify.custom.min.css' ;?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo Checkmydrive::urlTheme(true).'assets/css/chosen.min.css' ;?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo Checkmydrive::urlTheme(true).'assets/bootstrap/css/bootstrap.min.css' ;?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript" charset="utf-8" src="<?php echo Checkmydrive::urlTheme(true).'assets/js/jquery.js'; ?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo Checkmydrive::urlTheme(true).'assets/pnotify/pnotify.custom.min.js'; ?>"></script>
    
    <?php $message = Checkmydrive::getMessage(); ?>
    <script>
        jQuery(function($){
            <?php if($message) foreach ($message as $msg) foreach ($msg as $t=>$m){ ?>
                    new PNotify({text: '<?php echo $m; ?>', type: '<?php echo $t; ?>'});
            <?php } ?>
        })
    </script>

</head>
<body class="wrapper login-page <?php echo $class; ?>">
    <div class="container">
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
        <?php echo $template->body; ?>
        <?php require($them_path . 'include/footer.php'); ?>
    </div>
</body>
</html>