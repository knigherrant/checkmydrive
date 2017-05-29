<!DOCTYPE html>
<html>
<head>
    <?php 
    $them_path = $this->template->get_theme_path(); 
    $theme_url = base_url() . $them_path;
    $template = (object) $template;
    ?>
    <?php require($them_path.'include/head.php'); ?>
</head>
<body class="wrapper">
    <div class="container">
        <?php require($them_path. 'include/header.php'); ?>
        <?php echo $template->body; ?>
        <?php require($them_path . 'include/footer.php'); ?>
    </div>
</body>
</html>