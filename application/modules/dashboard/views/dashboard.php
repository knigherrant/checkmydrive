<?php
echo link_tag(Checkmydrive::urlTheme().'assets/themeforest-admin/css/style.css');
echo link_tag(Checkmydrive::urlTheme().'assets/css/fix-themeforest.css');
?>
<div class="ct_ctn bodygrey">
    <?php echo CheckmydriveHelper::addAdminSideBar();?>
    <div class="maincontent">
        <?php echo CheckmydriveHelper::getBreadcrumbs();?>
        <div class="left">
                <div id="j-main-container">
            
                </div>
        </div><!--one_third last-->
        <br clear="all" />
    </div><!--maincontent-->
</div>