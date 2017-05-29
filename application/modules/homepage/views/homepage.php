<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */

$user = Checkmydrive::getUser();
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
                                        <span class="renew"><a href="<?php echo Checkmydrive::root() . 'renew?month=' . md5(1) ; ?>"><?php echo Checkmydrive::_('Renew')?></a></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="link-dashboard f5dashboard">
                            <a href="<?php echo Checkmydrive::route('admin'); ?>"><?php echo Checkmydrive::_('CHECKMYDTIVE_TITLE_DASHBOARD')?></a>
                        </div>
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

                                        <div class="gotoadmin link-dashboard">
                                            <div class="position_absolute">                                        
                                                <a href="<?php echo Checkmydrive::route('admin'); ?>"><i class="fa fa-tachometer" aria-hidden="true"></i><?php echo Checkmydrive::_('CHECKMYDTIVE_TITLE_DASHBOARD')?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="link-contact">
                                            <div class="position_absolute">                                        
                                                <a href="mailto:support@clientrol.com"><i class="fa fa-life-ring" aria-hidden="true"></i><?php echo Checkmydrive::_('Contact Support')?></a>
                                            </div>
                                        </div>
                                    </td>                            
                                </tr>
                                <tr class="annual">
                                    <td><?php echo Checkmydrive::_('CHECKMYDTIVE_ANNUAL_SUBSCRIPTION')?></td>
                                    <td><?php echo Checkmydrive::_('CHECKMYDTIVE_SAVE10')?></td>
                                    <td>$<?php echo $master->subscription * 12 * 90 / 100 ;?> per year</td>
                                    <td><span class="renew"><a href="<?php echo Checkmydrive::root() . 'renew?month=' . md5(12); ?>"><?php echo Checkmydrive::_('Renew')?></a></span></td>
                                </tr>
                                <tr class="monthly">
                                    <td><?php echo Checkmydrive::_('CHECKMYDTIVE_MONTHLY_SUBSCRIPTION')?></td>
                                    <td></td>
                                    <td class="money">$<?php echo $master->subscription;?> per month</td>
                                    <td><span class="renew"><a href="<?php echo Checkmydrive::root() . 'renew?month=' . md5(1); ?>"><?php echo Checkmydrive::_('Renew')?></a></span></td>
                                </tr>
                            </tbody>

                        </table>
                    <p class="closeacc"><a onclick="confirmCloseAccount();" href="javascript:void(0);"><?php echo Checkmydrive::_('CHECKMYDTIVE_CLOSE_ACCOUNT'); ?></a></p>
                    <?php } ?>

                </div>
            </div> 
        </div>
    </div>
</div>


