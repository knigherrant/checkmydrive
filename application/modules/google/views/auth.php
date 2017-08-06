<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */

$user = Checkmydrive::getUser();
$config  = Checkmydrive::getConfigs();
?>


<div id="jk-contents" class="ct_ctn">
    <div class="contents row-fluid">
        <div id="sidebar-left"><?php echo CheckmydriveHelper::buildSidebar();?></div>
        <div id="content-wrapper">
            <div class="row-fluid ct_subcont">
                <a href="<?php
                echo $this->client->createAuthUrl()
                        ?>">Access Google</a>
            </div> 
        </div>
    </div>
</div>


