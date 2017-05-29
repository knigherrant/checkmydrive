var MessageModel = (function($){
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
                    viewModel.canSubmit(false);
                },
                done: function (e, data) {
                    $process.hide();
                    if(data.result.rs){
                        viewModel.attachments.push(data.result.file);
                    }else{
                        new PNotify({title: jSont._('CHECKMYDTIVE_UPLOAD_ATTACH_FILE'), text: data.result.msg, type:'error'});
                    }
                    viewModel.canSubmit(true);
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
        self.message = ko.observable(data.message);
        self.attachments = ko.observableArray();
        self.comments = ko.observableArray(data.comments);
        self.canSubmit = ko.observable(true);

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

        self.addCommentLoading = ko.observable(false);
        self.addComment = function(){
            var message = tinymce.get('ct_editor_msgbox').getContent();
            if(!message) new PNotify({title: jSont._('CHECKMYDTIVE_REPLY'), text: jSont._('CHECKMYDTIVE_MSG_MESSAGE_REQUIRED')});
            else{
                self.addCommentLoading(true);
                $.ajax({
                    type: 'post',
                    url: root + 'message/addComment',
                    data:{
                        id: self.message().id,
                        message: message,
                        attachs: (self.attachments() || [])
                    },
                    dataType: 'json',
                    success: function(data){
                        self.addCommentLoading(false);
                        if(data.rs){
                            self.attachments([]);
                            tinymce.get('ct_editor_msgbox').setContent('');
                            self.comments.push(data.comment);
                            setTimeout(function(){
                                $('.ct_thread_comment_main .ct_thread_comment_text, .ct_thread_comment_sub .ct_thread_comment_text').last().show();
                            }, 100);
                        }else{
                            new PNotify({title: jSont._('CHECKMYDTIVE_REPLY'), text: data.msg});
                        }
                    }
                });
            }
        }

        self.deleteComment = function(item){
            $.getJSON(root + 'message/deleteComment', {id: item.id}, function(data){
                if(data.rs){
                    self.comments.remove(item);
                }else{
                    new PNotify({title: jSont._('CHECKMYDTIVE_DELETE_MESSAGE'), text: data.msg, type: 'error'});
                }
            });
        }

        self.toggleComment = function(item, event){
            $(event.target).next('.ct_thread_comment_text').slideToggle(200);
        }

        //Show last comment
        /*$('.ct_thread_comment_main .ct_thread_comment_text, .ct_thread_comment_sub .ct_thread_comment_text').hide();
        setTimeout(function(){
            $('.ct_thread_comment_main .ct_thread_comment_text, .ct_thread_comment_sub .ct_thread_comment_text').last().show();
        }, 100);*/

        //Hide tinymce toolbar
        setTimeout(function(){
            $('#ct_editor .mce-tinymce .mce-container-body.mce-flow-layout').prepend($('#ct_editor .mce-tinymce .mce-i-link').parent().parent().parent().parent());
            $('#ct_editor .mce-panel .mce-container-body .mce-toolbar.mce-last').after($('<div/>',{class: 'ct_mce_title', text: jSont._('CHECKMYDTIVE_MESSAGE')}));
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