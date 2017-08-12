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

<style>

</style>
<div id="jk-contents" class="ct_ctn">
    <div class="contents row-fluid">
        <div id="sidebar-left"><?php echo CheckmydriveHelper::buildSidebar();?></div>
        <div id="content-wrapper" style="position: relative;">
            <div class="row-fluid ct_subcont box-balance">
                <h2>Authorization required</h2>
                <p>Authorize this app in Google Drive</p>
                <a 
                    class ="btn btn-primary btn-google-auth"
                    href="<?php
                        echo $this->client->createAuthUrl()
                        ?>"> Authorize</a>
                <p>
                    <label><input type="checkbox" name="remember"/><span>Remember me</span></label>
                </p>
            </div> 
        </div>
    </div>
</div>


