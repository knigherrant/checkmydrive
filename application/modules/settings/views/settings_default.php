<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */
echo link_tag(Checkmydrive::urlTheme().'assets/themeforest-admin/css/style.css');
echo link_tag(Checkmydrive::urlTheme().'assets/css/fix-themeforest.css');
?>


<div class="ct_ctn bodygrey">
    <form action="<?php echo Checkmydrive::route('settings'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
        <?php echo CheckmydriveHelper::addAdminSideBar();?>
        <div class="maincontent">
            <?php echo CheckmydriveHelper::getBreadcrumbs();?>
            <?php echo Checkmydrive::getSiderBnt(array ('apply'));?>
            <div class="left">
                <div id="j-main-container">
                    <ul class="nav nav-tabs" id="ct_tab">
                        <?php foreach ($this->form->getFieldsets() as $group=>$field){ ?>
                        <li><a href="#<?php echo $group; ?>" data-toggle="tab"><?php echo Checkmydrive::_($field->label);?></a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content form-horizontal">
                        <?php foreach ($this->form->getFieldsets() as $group=>$ffs){  ?>
                            <div class="tab-pane <?php echo 'content-'.$group; ?>" id="<?php echo $group; ?>"> 
                                <div class="tab-description"><?php echo Checkmydrive::_($ffs->description);?></div>
                                <?php $col = ($group == 'admin_template')? 'span6' : ''; ?>
                                <div class="<?php echo $col; ?>">
                                <?php foreach ($this->form->getFieldset($group) as $i=>$field) :?>
                                    <div class="control-group <?php echo 'jk-field-'.$field->fieldname; ?>">
                                        <div class="control-label"><?php echo $field->label; ?></div>
                                        <div class="controls">
                                            <?php if($field->fieldname == 'contact_id' && $field->value){?>
                                                <input type="text" value="<?php echo $this->item->contact_name;?>" readonly="true"/>
                                                <input type="hidden" value="<?php echo $this->item->contact_id;?>" name="<?php echo $field->name;?>"/>
                                            <?php }else{ ?>
                                                <?php echo $field->input; ?>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <?php  if($i == 'jform_calendar_task_remind_line_through') echo '</div><div class="span6">'; ?>
                                <?php endforeach; ?>
                                </div>
                            </div>

                        <?php } ?>
                    </div>

                    <input type="hidden" name="task" value="" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $JVCT(function($){
        $('#ct_tab a:first').tab('show');
        
    })
</script>