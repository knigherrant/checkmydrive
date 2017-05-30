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
                <h1><?php echo Checkmydrive::_('CHECKMYDTIVE_TITLE_PROFILE')?></h1>
                <form action="">
                    <div class="span12">
                        <div class="ct_panel">
                            <h6><i class="fa fa-user"></i> <?php echo Checkmydrive::_('CHECKMYDTIVE_TITLE_PROFILE');?></h6>
                        </div>
                        <div class="ct_subcont">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th><?php echo Checkmydrive::_('CHECKMYDTIVE_NAME');?></th>
                                    <td><input value="<?php echo $user->name;?>" name="name"/></td>
                                </tr>
                                <tr>
                                    
                                    <th><?php echo Checkmydrive::_('CHECKMYDTIVE_EMAIL');?></th>
                                    <td><input value="<?php echo $user->email;?>" name="email"/></td>
                                </tr>
                                <?php if(Checkmydrive::isBusiness()){ ?>
                                <tr>
                                    <th><?php echo Checkmydrive::_('Address');?></th>
                                    <td><input value="<?php echo $user->address;?>" name="address"/></td>
                                </tr>
                                <tr>
                                    <th><?php echo Checkmydrive::_('Company');?></th>
                                    <td><input value="<?php echo $user->company;?>" name="company"/></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <th><?php echo Checkmydrive::_('Password');?></th>
                                    <td><input value="" name="password"/></td>
                                </tr>
                                <tr>
                                    <th><?php echo Checkmydrive::_('Re Password');?></th>
                                    <td><input value="" name="password2"/></td>
                                </tr>
                                </tbody>
                            </table>
                            <div>

                                <button class="btn btn-small"  type="button">
                                <span class="icon-save"></span> Save</button>
          
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div> 
        </div>
    </div>
</div>


