<?php
$meta = array(
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'description', 'content' => 'My Great Site'),
        array('name' => 'keywords', 'content' => 'love, passion, intrigue, deception'),
        array('name' => 'robots', 'content' => 'no-cache'),
        array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
    );
echo meta($meta); 
?>
<title><?php echo $template->title; ?></title>
<!-- Bootstrap core CSS -->
<?php CheckmydriveHelper::addAsset(); ?>
<script>
    jQuery(document).ready(function (){
        jQuery('select').chosen({"disable_search_threshold":10,"allow_single_deselect":true,"placeholder_text_multiple":"Select some options","placeholder_text_single":"Select an option","no_results_text":"No results match"});
    });
</script>

