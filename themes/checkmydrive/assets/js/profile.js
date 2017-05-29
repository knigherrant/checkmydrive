var ProfileModel = (function($){
    ko.bindingHandlers.file = {
        init: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
            var bindings = allBindingsAccessor();
            var $process = $(bindings.process);
            var $parent = $(element).parent();
            $(element).fileupload({
                url: root + 'profile/uploadAvatar',
                dataType: 'json',
                send: function(e, data){
                    $process.show();
                    $parent.hide();
                },
                done: function (e, data) {
                    $process.hide();
                    $parent.show();
                    if(data.result.rs){
                        viewModel.profile().avatar(data.result.file);
                        new PNotify({title: jSont._('CHECKMYDTIVE_CHANGE_AVATAR'), text: data.result.msg, type:'success'});
                    }else{
                        new PNotify({title: jSont._('CHECKMYDTIVE_CHANGE_AVATAR'), text: data.result.msg, type:'error'});
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

    var User = function(name, email){
        this.name = ko.observable(name);
        this.password = ko.observable('');
        this.password2 = ko.observable('');
        this.email = ko.observable(email);
    }

    return function(data){
        var self = this;
        self.loading = ko.observable(false);
        self.saveInfoLoading = ko.observable(false);
        self.profile = ko.observable(data);
        self.name = ko.observable(data.name);
        self.email = ko.observable(data.email);
        self.notify = parseInt(data.notify);

        self.editInfo = ko.observable(false);
        $.each(self.profile(), function(i, item){
           if(typeof item === 'array'){
               self.profile()[i] = ko.observableArray(item);
           }else{
               self.profile()[i] = ko.observable(item);
           }
        });

        self.showEditInfo = function(){
            self.editInfo(true);
        }

        self.hideEditInfo = function(){
            self.editInfo(false);
        }

        self.saveInfo = function(){
            self.saveInfoLoading(true);
            $.ajax({
                url: root + 'profile/saveInfo',
                type: 'post',
                dataType: "json",
                data: {
                    phone: self.profile().phone(),
                    mobile: self.profile().mobile(),
                    address: self.profile().address(),
                    city: self.profile().city(),
                    state: self.profile().state(),
                    zipcode: self.profile().zipcode(),
                    country: self.profile().country()
                },
                success: function(data){
                    self.saveInfoLoading(false);
                    if(data.rs){
                        new PNotify({title: jSont._('CHECKMYDTIVE_EDIT_PRIVATE_INFO'), text: data.msg, type:'success'});
                        self.editInfo(false);
                    }else{
                        new PNotify({title: jSont._('CHECKMYDTIVE_EDIT_PRIVATE_INFO'), text: data.msg, type:'error'});
                    }
                }
            });
            self.editInfo(false);
        }

        self.userLoading = ko.observable(false);
        self.user = new User(self.name(), self.email());
        self.saveUser = function(){
            if(!self.user.name() || !self.user.email()){
                new PNotify({title: jSont._('CHECKMYDTIVE_EDIT_PROFILE'), text: jSont._('CHECKMYDTIVE_MSG_PLEASE_COMPLETE_REQUIRED_FIELDS'), type:'error'});
            }else if(self.user.password() != self.user.password2()){
                new PNotify({title: jSont._('CHECKMYDTIVE_EDIT_PROFILE'), text: jSont._('CHECKMYDTIVE_MSG_CONFIRM_PASSWORD_ERROR'), type:'error'});
            }else{
                $.ajax({
                    url: root + 'profile/saveUser',
                    type: 'post',
                    dataType: 'json',
                    data: {user: self.user},
                    beforeSend: function(){
                        self.userLoading(true);
                    },
                    success: function(data){
                        self.userLoading(false);
                        if(data.rs){
                            new PNotify({title: jSont._('CHECKMYDTIVE_EDIT_PROFILE'), text: data.msg, type:'success'});
                            $('#userModal').modal('hide');
                            self.name(self.user.name());
                            self.email(self.user.email());
                        }else{
                            new PNotify({title: jSont._('CHECKMYDTIVE_EDIT_PROFILE'), text: data.msg, type:'error'});
                        }
                    }
                });
            }
        }


        $('#ct_email_notify').bootstrapSwitch();
        $('#ct_email_notify').on('switch-change', function (e, switchData) {
            var value = 0;
            if(switchData.value) value = 1;
            if(self.notify == value) return;
            $.getJSON(root + 'profile/notify?notify='+value, function(data){
                if(!data.rs){
                    new PNotify({title: jSont._('CHECKMYDTIVE_CHANGE_NOTIFY'), text: data.msg, type:'error'});
                    $('#ct_email_notify').bootstrapSwitch('setState', !switchData.value);
                }else{
                    self.notify = switchData.value;
                }
            });
        });
    }
})($JVCT);