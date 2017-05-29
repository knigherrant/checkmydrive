<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */

if(!Checkmydrive::isSuperUser()){
    Checkmydrive::setMessage('Are you have not permistion','error');
    return;
}

$listOrder	= strip_quotes($this->model->getState('list.ordering'));
$listDirn	= strtolower(strip_quotes($this->model->getState('list.direction')));
?>


<div class="ct_ctn bodygrey">
    <form action="<?php echo Checkmydrive::route('jusers'); ?>" method="post" name="adminForm" id="adminForm">
        <?php echo CheckmydriveHelper::addAdminSideBar($this->sidebar);?>
        <div class="maincontent">
            <?php echo CheckmydriveHelper::getBreadcrumbs();?>
            <?php if(Checkmydrive::isSuperUser()) echo Checkmydrive::getSiderBnt();?>
            <div class="left">
                <div id="j-main-container">
                   
                    <div id="filter-bar" class="btn-toolbar">
                        <div class="filter-search btn-group pull-left">
                            <label for="filter_search" class="element-invisible"><?php echo Checkmydrive::_('CHECKMYDTIVE_FILTER_SEARCH_DESC');?></label>
                            <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo Checkmydrive::_('JSEARCH_FILTER'); ?>" value="<?php echo strip_quotes($this->model->getState('filter.search')); ?>" class="hasTooltip" title="<?php echo Checkmydrive::_('CHECKMYDTIVE_SEARCH_IN_NAME'); ?>" />
                        </div>
                        <div class="btn-group pull-left">
                            <button type="submit" class="btn hasTooltip" title="<?php echo Checkmydrive::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn hasTooltip" title="<?php echo Checkmydrive::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="fa fa-times"></i></button>
                        </div>
                        <!--
                        <div class="btn-group pull-right hidden-phone">
                            <label for="limit" class="element-invisible"><?php echo Checkmydrive::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                            <?php ////echo $this->pagination->getLimitBox(); ?>
                        </div>
                        -->
                        <?php echo $this->sidebar; ?>
                    </div>
                    <?php if (empty($this->items)) : ?>
                        <div class="alert alert-no-items">
                            <?php echo Checkmydrive::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </div>
                    <?php else : ?>
                        <table class="table table-striped">
                                <thead>
                                        <tr>
                                                <th width="1%" class="nowrap center">
                                                        <?php echo Checkmydrive::checkall(); ?>
                                                </th>
                                                <th class="left">
                                                        <?php echo Checkmydrive::sort( 'USERS_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="10%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="10%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'Country', 'a.info', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="5%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'USERS_HEADING_ENABLED', 'a.block', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="5%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'USERS_HEADING_ACTIVATED', 'a.activation', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="10%" class="nowrap center">
                                                        <?php echo Checkmydrive::_('USERS_HEADING_GROUPS'); ?>
                                                </th>
                                                <th width="15%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'JGLOBAL_EMAIL', 'a.email', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="10%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'USERS_HEADING_LAST_VISIT_DATE', 'a.last_login', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="10%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'USERS_HEADING_REGISTRATION_DATE', 'a.created', $listDirn, $listOrder); ?>
                                                </th>
                                                <th width="1%" class="nowrap center">
                                                        <?php echo Checkmydrive::sort( 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                                </th>
                                        </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($this->items as $i => $item) :
                                       
                                                $canEdit   = true;
                                                $canChange = true;
                                        
                                ?>
                                        <tr class="row<?php echo $i % 2; ?>">
                                                <td class="center">
                                                        <?php if ($canEdit) : ?>
                                                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                                        <?php endif; ?>
                                                </td>
                                                <td>
                                                 <?php if(Checkmydrive::isSuperUser()){ ?>
                                                    <a href="<?php echo Checkmydrive::route('jusers/edit/'.(int) $item->id); ?>" title="<?php echo Checkmydrive::sprintf('USERS_EDIT_USER', strip_quotes($item->name)); ?>">
                                                                        <?php echo strip_quotes($item->name); ?></a>
                                                     
                                                 <?php }else { ?>
                                                    <?php echo strip_quotes($item->name); ?>
                                                 <?php } ?>
                                                        
                                                </td>
                                                <td class="center">
                                                        <?php echo strip_quotes($item->username); ?>
                                                </td>
                                                <td class="center">
                                                        <?php echo strip_quotes($item->info); ?>
                                                </td>
                                                <td class="center">
                                 
                                                        <?php echo Checkmydrive::_($item->banned ? 'Yes' : 'No'); ?>
                                                  
                                                </td>
                                                <td class="center">
                                                    <?php echo JHtml::_('jgrid.published', $item->activated, $i, '', true, 'cb'); ?>
                                                </td>
                                                <td class="center">
                                             
                                                        <?php echo Checkmydrive::userGroup($item->user_level); ?>
                                               
                                                </td>
                                                <td class="center">
                                                        <?php echo strip_quotes($item->email); ?>
                                                </td>
                                                <td class="center">
                                                        <?php if ($item->last_login != '0000-00-00 00:00:00'):?>
                                                                <?php echo JHtml::_('date', $item->last_login, 'Y-m-d H:i:s'); ?>
                                                        <?php else:?>
                                                                <?php echo Checkmydrive::_('JNEVER'); ?>
                                                        <?php endif;?>
                                                </td>
                                                <td class="center">
                                                        <?php echo JHtml::_('date', $item->created, 'Y-m-d H:i:s'); ?>
                                                </td>
                                                <td class="center">
                                                        <?php echo (int) $item->id; ?>
                                                </td>
                                        </tr>
                                        <?php endforeach; ?>
                                </tbody>
                        </table>
                        <div class="pagination pagination-toolbar">
                        <?php echo $this->pagination->create_links(); ?>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="boxchecked" value="0" />
                    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </div>
        </div>
    </form>
</div>
