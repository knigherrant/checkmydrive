var MessagesModel = (function($){
    ko.bindingHandlers.file = {
        init: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
            var bindings = allBindingsAccessor();
            var $process = $(bindings.process);
            var $parent = $(element).parent();
            $(element).fileupload({
                url: root + 'message/uploadAttach',
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
        self.loading = ko.observable(false);
        self.newMessageLoading = ko.observable(false);
        self.type = ko.observable('all');
        self.messages = ko.observableArray(data.messages);
        self.admin = ko.observable(data.admin);
        self.attachments = ko.observableArray();

        self.sortBy = ko.observable('created');
        self.sortDir = ko.observable(1);
        self.sort = function(sortBy){
            var sortBy = sortBy;
            return function(){
                if(sortBy == self.sortBy()) self.sortDir(1 - self.sortDir());
                else{
                    self.sortBy(sortBy);
                    self.sortDir(0);
                }
            }
        }

        self.messageToShow = ko.computed(function(){
            if(self.sortBy()){
                var array= _.sortBy(self.messages(), function(item){
                    return item[self.sortBy()];
                });

                if(self.sortDir()) array.reverse();

                self.messages(array);
            }

            if(self.type() == 'all') return self.messages();

            return ko.utils.arrayFilter(self.messages(), function(msg) {
                return msg.type == self.type();
            });
        })
        self.gridMessagesModel = new ko.simpleGrid.viewModel({
            data: self.messageToShow,
            columns: [
                {subject: "subject", from_name: "from_name", to_name: "to_name", type: "type", read: "read", created: "created_format", url: "url", attached: 'attached'}
            ],
            pageSize: 20
        });

        self.toggleType = function(value){
            var type = value;
            return function(){
                self.type(type);
            }
        }

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
                    url: root + 'message/send',
                    type: 'post',
                    dataType: "json",
                    data: {
                        subject: subject,
                        message: message,
                        attachs: (self.attachments() || [])
                    },
                    success: function(data){
                        if(data.rs){
                            self.attachments([]);
                            $('#ct_msg_subject').val('');
                            tinymce.get('ct_editor_msgbox').setContent('');
                            $.getJSON(root + 'message/getMessages',function(newMessages){
                                self.newMessageLoading(false);
                                new PNotify({title: jSont._('CHECKMYDTIVE_NEW_MESSAGE'), text: data.msg, type: 'success'});
                                self.messages(newMessages.messages);
                            });
                        }else{
                            self.newMessageLoading(false);
                            new PNotify({title: jSont._('CHECKMYDTIVE_NEW_MESSAGE'), text: data.msg});
                        }
                    }
                });
            }
        }

        self.deleteAttach = function(item){
            $.ajax({
                type: 'post',
                url: root + 'message/deleteAttach',
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