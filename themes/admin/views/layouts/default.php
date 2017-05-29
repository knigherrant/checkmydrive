<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
    $them_path = $this->template->get_theme_path(); 
    $theme_url = base_url() . $them_path;
    $template = (object) $template;

    $uri = Checkmydrive::uri(); 
    ?>
    <?php require($them_path.'include/head.php'); ?>
</head>
<body class="sidebar-mini skin-blue">
        <div class="wrapper">
        <?php require($them_path. 'include/header.php'); ?>
        <?php echo $template->body; ?>
        <?php require($them_path . 'include/footer.php'); ?>
        </div>
</body>
</html>