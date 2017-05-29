<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */

if(Checkmydrive::input()->get_post('ticket_id')) $ticket = '?ticket_id='.Checkmydrive::input()->get_post('ticket_id');
else $ticket='';

?>
<script type="text/javascript">
    jSont.submitbutton = function(task) {
        jSont.submitform(task, document.getElementById('task-form'));
    }
</script>
<?php echo Checkmydrive::getSiderBnt(array('apply', 'save', 'cancel')); ?>

<div class="ct_ctn">
    <form action="<?php echo Checkmydrive::route('tasks') . $ticket; ?>" method="post" enctype="multipart/form-data" name="adminForm" id="task-form" class="form-validate form-horizontal row-fluid">
        <div class="widget">
            <div class="widget-header"><i class="fa fa-list-alt"></i><h3><?php echo Checkmydrive::_('CHECKMYDTIVE_LEGEND_DETAILS');?></h3></div>
            <div class="widget-content">
                <div class="alert alert-info"><i class="fa fa-warning"></i> <?php echo Checkmydrive::_('CHECKMYDTIVE_DESC_EDIT_TASK')?></div>
                <div id="ct_client_not_billable" class="alert alert-warning" style="display: none"><i class="fa fa-warning"></i> <?php echo Checkmydrive::_('CHECKMYDTIVE_DESC_CLIENT_NOT_BILLABLE')?></div>
                <div class="span6">
                    <?php foreach ($this->form->getFieldset('basic') as $field) : ?>
                        <div class="control-group">
                            <div class="control-label"><?php echo $field->label; ?></div>
                            <div class="controls">
                                <?php echo $field->input; ?>
                                <?php if($field->fieldname == 'assigned' || $field->fieldname == 'parent_id'){?>
                                <img class="loading" src="<?php echo Checkmydrive::urlTheme().'assets/images/loading.gif';?>" alt="" style="display: none"/>
                                <?php }?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="span6">
                    <?php foreach ($this->form->getFieldset('basic1') as $field) : ?>
                        <div class="control-group">
                            <div class="control-label"><?php echo $field->label; ?></div>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="control-group">
                        <div class="control-label"><?php echo Checkmydrive::_('CHECKMYDTIVE_ATTACHMENTS'); ?></div>
                        <div class="controls">
                            <?php if($this->item->attachments){?>
                                <div class="ct_thread_comment_attach_files">
                                    <?php foreach($this->item->attachments as $att){
                                        $fileType = CheckmydriveHelper::getFileType($att->savename);
                                        ?>
                                        <div class="ct_thread_comment_attach_file">
                                            <?php if($fileType['type'] == 'image'){?>
                                                <a class="modal" href="<?php echo Checkmydrive::root().'images/clientrol/attach/'.$att->savename;?>" title="<?php echo $att->filename;?>">
                                                    <img src="<?php echo Checkmydrive::root().'images/clientrol/attach/'.$att->savename;?>"/><br/>
                                                    <span><?php echo $att->filename;?></span>
                                                </a>
                                            <?php }else{ ?>
                                                <a href="<?php echo Checkmydrive::route('files/download?file='.urlencode(Checkmydrive::root().'images/clientrol/attach/'.$att->savename).'&name='.urlencode($att->filename));?>" title="<?php echo $att->filename;?>">
                                                    <img src="<?php echo $fileType['icon'];?>"/><br/>
                                                    <span><?php echo $att->filename;?></span>
                                                </a>
                                            <?php }?>
                                            <a href="javascript:void(0)" onclick="deleteAttachment(<?php echo $att->id;?>, this)" class="ct_thread_comment_attach_file_delete_btn" title="<?php echo Checkmydrive::_('CHECKMYDTIVE_DELETE')?>"><i class="fa fa-times"></i></a>
                                        </div>
                                    <?php }?>
                                </div>
                            <?php }?>
                            <a id="ct_attachment_add" class="btn btn-small" href="javascript:void(0)"><i class="fa fa-plus"></i> Add File</a>
                            <div id="ct_attachment_inputs" style="overflow: hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($this->item->ticket) && $this->item->ticket){?>
        <div class="widget widget-table">
            <div class="widget-header"><i class="fa fa-ct-ticket"></i><h3><?php echo Checkmydrive::_('CHECKMYDTIVE_LEGEND_TICKET_DETAILS');?></h3></div>
            <div class="widget-content">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?php echo Checkmydrive::_('CHECKMYDTIVE_FORM_LBL_ID')?></th>
                            <th><?php echo Checkmydrive::_('CHECKMYDTIVE_FORM_LBL_SUBJECT')?></th>
                            <th><?php echo Checkmydrive::_('CHECKMYDTIVE_FORM_LBL_FROM')?></th>
                            <th><?php echo Checkmydrive::_('JSTATUS')?></th>
                            <th class="center"><?php echo Checkmydrive::_('CHECKMYDTIVE_ACTION')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $this->item->ticket->id;?></td>
                            <td><?php echo $this->item->ticket->subject;?></td>
                            <td><?php echo $this->item->ticket->contact_name." - ".$this->item->ticket->client_name;?></td>
                            <td>
                                <?php
                                switch($this->item->ticket->status){
                                    case 1: echo '<span class="label label-success">'.Checkmydrive::_('CHECKMYDTIVE_STATUS_DONE').'</span>'; break;
                                    default: echo '<span class="label label-info">'.Checkmydrive::_('CHECKMYDTIVE_STATUS_OPEN').'</span>'; break;
                                }
                                ?>
                            </td>
                            <td class="center"><a href="<?php echo Checkmydrive::route('tickets/edit/'.$this->item->ticket->id, false);?>" class="btn btn-mini" title="<?php echo Checkmydrive::_('JACTION_EDIT'); ?>"><i class="fa fa-edit"></i></a></td>
                        </tr>
                        <tr>
                            <th><?php echo Checkmydrive::_('CHECKMYDTIVE_FORM_LBL_MESSAGE')?></th>
                            <td colspan="5"><?php echo $this->item->ticket->message;?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php }?>

        <?php echo $this->form->getInput('billcomplete');?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>

<script type="text/javascript">
    function deleteAttachment(id, el){
        $JVCT.getJSON('<?php echo Checkmydrive::route('tasks/deleteAttachment?atid='); ?>'+id, function(data){
            if(data.rs){
                $JVCT(el).parent().remove();
            }else{
                alert(data.msg);
            }
        });
    }

    $JVCT(function($){
        <?php if($this->item->status){?>
            $('#jform_status1, label[for=jform_status1]').remove();
            <?php if($this->item->billable){?>
                $('#jform_billable1, label[for=jform_billable1]').remove();
            <?php }else{ ?>
                $('#jform_billable0, label[for=jform_billable0]').remove();
            <?php }?>
            $('#jform_billtime').attr('readonly', true);
        <?php }?>

        $('#jform_project_id').change(function(){
            var value = $(this).val();
            $.ajax({
                url: '<?php echo Checkmydrive::route('tasks/getProjectData'); ?>',
                data: {
                    id: <?php echo (int)$this->item->id;?>,
                    project: value,
                    assigned: <?php echo (int)$this->item->assigned;?>,
                    parent: <?php echo (int)$this->item->parent_id;?>
                },
                dataType: 'json',
                beforeSend: function(){
                    $('.loading').show();
                },
                success: function(data){
                    $('#jform_assigned').html(data.assigneds);
                    $('#jform_parent_id').html(data.parents);
                    $('select').trigger("liszt:updated");
                    $('.loading').hide();
                    if(!data.billable){
                        $('#ct_client_not_billable').slideDown(300);
                        $('label[for=jform_billable0]').hide();
                    }else{
                        $('#ct_client_not_billable').slideUp(300);
                        $('label[for=jform_billable0]').show();
                    }
                }
            });
        });

        $('#jform_project_id').trigger('change');

        $('#ct_attachment_add').click(function(){
            $('#ct_attachment_inputs').append('<div class="ct_attachment_input" style="margin-top: 10px; float: left"><a class="btn btn-mini remove" href="javascript:void(0)"><i class="fa fa-times"></i></a> <input type="file" name="attachments[]"/></div>');
            $('.ct_attachment_input .remove:last').click(function(){ $(this).parent('.ct_attachment_input').remove()});
        });
    });
</script>
