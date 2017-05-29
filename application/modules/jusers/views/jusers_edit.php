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

?>
<script type="text/javascript">
    jSont.submitbutton = function(task) {
        jSont.submitform(task, document.getElementById('task-form'));
    }
</script>
<div class="ct_ctn bodygrey">
<?php echo CheckmydriveHelper::addAdminSideBar();?>
<div class="maincontent">
    <?php echo CheckmydriveHelper::getBreadcrumbs();?>
<div class="left">
    <?php echo Checkmydrive::getSiderBnt(array('apply', 'save', 'cancel')); ?>
    <form action="<?php echo Checkmydrive::route('jusers'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="task-form" class="form-validate form-horizontal row-fluid">
        <div class="widget">
            <div class="widget-header"><i class="fa fa-list-alt"></i><h3><?php echo Checkmydrive::_('CHECKMYDTIVE_LEGEND_DETAILS');?></h3></div>
            <div class="widget-content">
                <div id="ct_client_not_billable" class="alert alert-warning" style="display: none"><i class="fa fa-warning"></i> <?php echo Checkmydrive::_('CHECKMYDTIVE_DESC_CLIENT_NOT_BILLABLE')?></div>
                <div class="span6">
                    <?php foreach ($this->form->getFieldset('user_details') as $field) : ?>
                            <div class="control-group">
                                    <div class="control-label">
                                            <?php echo $field->label; ?>
                                    </div>
                                    <div class="controls">
                                            <?php echo $field->input; ?>
                                    </div>
                            </div>
                    <?php endforeach; ?>
                </div>
                <div class="span6">
                    <div class="control-group">
                            <div class="control-label">
                                    <?php echo $this->form->getLabel('user_level'); ?>
                            </div>
                            <div class="controls">
                                    <?php echo $this->form->getInput('user_level'); ?>
                            </div>
                    </div>
                    
                    <div class="control-group">
                            <div class="control-label">
                                    <?php echo $this->form->getLabel('subscriber_start'); ?>
                            </div>
                            <div class="controls">
                                    <?php echo $this->form->getInput('subscriber_start'); ?>
                            </div>
                    </div>
                    
                    <div class="control-group">
                            <div class="control-label">
                                    <?php echo $this->form->getLabel('subscriber_end'); ?>
                            </div>
                            <div class="controls">
                                    <?php echo $this->form->getInput('subscriber_end'); ?>
                            </div>
                    </div>
                    
                    <div class="control-group">
                            <div class="control-label">
                                    <?php echo $this->form->getLabel('subscription'); ?>
                            </div>
                            <div class="controls">
                                    <?php echo $this->form->getInput('subscription'); ?>
                            </div>
                    </div>
                    
                    <div class="control-group">
                            <div class="control-label">
                                    <?php echo $this->form->getLabel('created_by'); ?>
                            </div>
                            <div class="controls">
                                    <?php echo $this->form->getInput('created_by'); ?>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
</div>
</div>