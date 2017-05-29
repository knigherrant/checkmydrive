var MessagesModel = (function($){
    ko.bindingHandlers.file = {
        init: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
            var bindings = allBindingsAccessor();
            var $process = $(bindings.process);
            var $parent = $(element).parent();
            $(element).fileupload({
                url: root + 'messages/uploadAttach',
                dataType: 'json',
                send: function(e, data){
                    $process.show();
                },
                done: function (e, data) {
                    $process.hide();
                    if(data.result.rs){
                        viewModel.attachments.push(data.result.file);
                    }else{
                        new PNotify({title: jSont._('CHECKMYDTIVE_UPLOAD_ATTACH_FILE'), text: data.result.msg, type:'error'});
                    }
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $process.children('.bar').css('width',progress + '%');
                    $process.children('.percent').text(progress + '%');
                }
            });
        }
    };

    return function(data){
        var self = this;
        self.newMessageLoading = ko.observable(false);
        self.admin = ko.observable(data.admin);
        self.contacts = ko.observableArray(data.contacts);
        self.contactSelected = ko.observable(0);
        self.attachments = ko.observableArray();
        $('#toolbar-new button').click(function(e){
            if(self.admin()){
                $('#ct_messageNew').modal('show');
                $('#ct_contact').trigger("liszt:updated");
            }else{
                new PNotify({title: jSont._('CHECKMYDTIVE_NEW_MESSAGE'), text: 'Please config Admin Email!'});
            }
        });

        self.sendMessage = function(){
            var errorMsg = [];
            var message = tinymce.get('ct_editor_msgbox').getContent();
            var subject = $('#ct_msg_subject').val();
            if(!subject) errorMsg.push(jSont._('CHECKMYDTIVE_MSG_SUBJECT_REQUIRED'));
            if(!message) errorMsg.push(jSont._('CHECKMYDTIVE_MSG_MESSAGE_REQUIRED'));
            if(errorMsg.length) new PNotify({title: jSont._('CHECKMYDTIVE_NEW_MESSAGE'), text: errorMsg.join('<br/>')});
            else{
                $('#ct_messageNew').modal('hide');
                self.newMessageLoading(true);
                $.ajax({
                    url: 'messages/send',
                    type: 'post',
                    dataType: "json",
                    data: {
                        to: self.contactSelected,
                        subject: subject,
                        message: message,
                        attachs: (self.attachments() || [])
                    },
                    success: function(data){
                        self.newMessageLoading(false);
                        if(data.rs){
                            location.reload();
                        }else{
                            new PNotify({title: jSont._('CHECKMYDTIVE_NEW_MESSAGE'), text: data.msg});
                        }
                    }
                });
            }
        }

        self.deleteAttach = function(item){
            $.ajax({
                type: 'post',
                url: root + 'messages/deleteAttach',
                data:{ file: item },
                dataType: 'json',
                success: function(data){
                    if(data.rs) self.attachments.remove(item);
                }
            });
        }

        //Hide tinymce toolbar
        setTimeout(function(){
            $('#ct_editor .mce-tinymce .mce-container-body.mce-flow-layout').prepend($('#ct_editor .mce-tinymce .mce-i-link').parent().parent().parent().parent());
        }, 500);

        tinymce.init({
            selector: "textarea#ct_editor_msgbox",
            menubar : false,
            plugins: [
                "autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            toolbar: "link unlink image code"
        });

    }
})($JVCT);