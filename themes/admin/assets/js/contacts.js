var ContactsModel = (function($){
    var NewUser = function(name, username, password, password2, email){
        this.name = ko.observable(name);
        this.username = ko.observable(username);
        this.password = ko.observable(password);
        this.password2 = ko.observable(password2);
        this.email = ko.observable(email);
    }

    return function(){
        var self = this;
        self.loading = ko.observable(false);
        self.newUserLoading = ko.observable(false);
        self.user = new NewUser();
        self.addNewUser = function(){
            if(!self.user.name() || !self.user.username() || !self.user.password() || !self.user.password2() || !self.user.email()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_PLEASE_COMPLETE_REQUIRED_FIELDS'), type:'error'});
            }else if(self.user.password() != self.user.password2()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_CONFIRM_PASSWORD_ERROR'), type:'error'});
            }else{
                $.ajax({
                    url: root + 'contacts/addNewUser',
                    type: 'post',
                    dataType: 'json',
                    data: {user: self.user},
                    beforeSend: function(){
                        self.newUserLoading(true);
                    },
                    success: function(data){
                        self.newUserLoading(false);
                        if(data.rs){
                            window.location.href = root + 'contacts/edit/'+data.user;
                        }else{
                            new PNotify({text: data.msg, type:'error'});
                        }
                    }
                });
            }
        }
    }
})($JVCT);