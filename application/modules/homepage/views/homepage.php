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
            <div class="row-fluid">
                <div class="ct_subcont welcomadmin">
                    <h3><?php echo Checkmydrive::_('CHECKMYDTIVE_WELCOME'); ?></h3>
                    <?php if(!$user->subscription){ ?>
                        <table class="table welcome">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="wmsg"><?php echo Checkmydrive::_('CHECKMYDTIVE_WELCOME_MSG')?></span>
                                    </td>
                                    <td class="welcomewexpire">
                                        <span class="wexpire"><?php echo Checkmydrive::_('CHECKMYDTIVE_SUBSCRIPTION_EXPIRE')?></span> <span class="dayexpire"><?php echo Checkmydrive::getDate($user->subscriber_end, false, 'M d Y')->toFormat; ?></span>
                                    </td>                       
                                    <td>
                                        <span class="renew"><a href="<?php echo Checkmydrive::route('renew?month=' . md5(1))  ; ?>"><?php echo Checkmydrive::_('Renew')?></a></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                     
                    <?php } else { ?>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="welcome">
                                            <span class="wmsg"><?php echo Checkmydrive::_('CHECKMYDTIVE_WELCOME_MSG')?></span><br>
                                            <span class="wexpire"><?php echo Checkmydrive::_('CHECKMYDTIVE_SUBSCRIPTION_EXPIRE')?></span> 
                                            <span class="dayexpire"><?php echo Checkmydrive::getDate($user->subscriber_end, false, 'M d Y')->toFormat; ?></span> 
                                        </div>
                                    </td>
                                    <td colspan="2" class="wexpiretd">

                                        <div class="link-contact">
                                            <div class="position_absolute">                                        
                                                <a href="mailto:support@checkmydrive.com"><i class="fa fa-life-ring" aria-hidden="true"></i><?php echo Checkmydrive::_('Contact Support')?></a>
                                            </div>
                                        </div>
                                    </td>                            
                                </tr>
                                <tr class="annual">
                                    <td><?php echo Checkmydrive::_('CHECKMYDTIVE_ANNUAL_SUBSCRIPTION')?></td>
                                    <td><?php echo Checkmydrive::_('CHECKMYDTIVE_SAVE10')?></td>
                                    <td>$<?php echo $config->subscription * 12 * 90 / 100 ;?> per year</td>
                                    <td><span class="renew"><a href="<?php echo Checkmydrive::route('renew?month=' . md5(12)) ; ?>"><?php echo Checkmydrive::_('Renew')?></a></span></td>
                                </tr>
                                <tr class="monthly">
                                    <td><?php echo Checkmydrive::_('CHECKMYDTIVE_MONTHLY_SUBSCRIPTION')?></td>
                                    <td></td>
                                    <td class="money">$<?php echo $config->subscription;?> per month</td>
                                    <td><span class="renew"><a href="<?php echo Checkmydrive::route('renew?month=' . md5(1)); ?>"><?php echo Checkmydrive::_('Renew')?></a></span></td>
                                </tr>
                            </tbody>

                        </table>
                    <p class="closeacc"><a onclick="confirmCloseAccount();" href="javascript:void(0);"><?php echo Checkmydrive::_('CHECKMYDTIVE_CLOSE_ACCOUNT'); ?></a></p>
                    <?php } ?>

                    <div class="authorize">
                        <?php if($this->google && isset($this->google->email)):?>
                            <div class="google_infor">
                                <h2><?php echo Checkmydrive::_('CHECKMYDRIVE_GOOGLE_LOGGED')?></h2>
                                <div>Name: <?php echo $this->google->name?></div>
                                <div>Email: <?php echo $this->google->email?></div>
                            </div>
                        <?php else:?>
                            <div class="google">
                               <h2>Authorization required</h2>
                                <p>Authorize this app in Google Drive</p>
                                <a 
                                    class ="btn btn-primary btn-google-auth"
                                    href="<?php
                                        echo Checkmydrive::route('google/auth');
                                        ?>"> Authorize</a>
                                <p>
                                    <label><input type="checkbox" name="remember"/><span>Remember me</span></label>
                                </p>
                            </div>
                        <?php endif;?>
                        <!--
                        <div class="dropbox">
                            <img src="<?php echo Checkmydrive::root(); ?>images/authorize.png" />
                        </div>
                        -->
                    </div>
                    
                </div>
            </div> 
        </div>
    </div>
</div>


