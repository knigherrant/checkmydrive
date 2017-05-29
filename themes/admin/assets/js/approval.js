var ApprovalModel = (function($){
    var DirNode = function(children, name, path, dirs, files, isRoot, parent) {
        this.children = ko.observableArray(children);
        this.name = ko.observable(name);
        this.path = ko.observable(path);
        this.dirs = ko.observable(dirs);
        this.files = ko.observable(files);
        this.loading = ko.observable(false);
        this.isRoot = ko.observable(false);
        if(isRoot) this.isRoot(true);
        this.parent = ko.observable();
        if(parent) this.parent(parent);
    }

    return function(root_dir, approval_id){
        var self = this;
        self.selectedDir = ko.observable();
        self.dirs = new DirNode([], root_dir.name, root_dir.path, root_dir.dirs, root_dir.files, true);
        self.showBrowser = function(){
            self.selectedDir('');
            $.getJSON(root + 'approvals/getDirectories', function(data){
                self.dirs.children([]);
                self.dirs.dirs(data.dirs);
                self.dirs.files(data.files);
                $('#browser_modal').modal('show');
            });
        }

        $('#browser_modal').on('hidden', function () {
            self.selectedDir('');
        });

        self.showChild = function(item){
            self.selectedDir(item);
            self.selectedDir().children([]);
            item.loading(true);
            $.getJSON(root + 'approvals/getDirectories', {path: item.path}, function(data){
                item.loading(false);
                self.selectedDir().dirs(data.childs.length);
                self.selectedDir().files(data.files);
                $.each(data.childs, function(i, it){
                    self.selectedDir().children.push(new DirNode([], it.name, it.path, it.dirs, it.files, false, item));
                });
            });
        }

        self.newFolderName = ko.observable();
        self.showInputNew = ko.observable(false);
        self.toggleNewFolder = function(state){
            var state = state;
            return function(){
                self.showInputNew(state);
            }
        }

        self.addFolder = function(){
            if(!self.newFolderName()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_NEW_FOLDER_NAME_REQUIRED'), type: 'error'});
            }else if(!self.selectedDir()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_PARENT_DIRECTORY_REQUIRED'), type: 'error'});
            }else{
                $.getJSON(root + 'approvals/addFolder', {path: self.selectedDir().path(), new: self.newFolderName()}, function(data){
                    if(data.rs){
                        self.selectedDir().dirs(self.selectedDir().dirs()+1);
                        self.selectedDir().children.push(new DirNode([], data.name, data.path, data.dirs, data.files));
                        self.showInputNew(false);
                        self.newFolderName('');
                    }else{
                        new PNotify({text: data.msg, type: 'error'});
                    }
                });
            }
        }

        self.setPath = function(){
            if(self.selectedDir()){
                $('#jform_path').val(self.selectedDir().path());
                $('#browser_modal').modal('hide');
            }else{
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_FOLDER_REQUIRED'), type: 'warning'});
            }

        }

        self.confirmDelete = function(){
            if(!self.selectedDir()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_FOLDER_REQUIRED'), type: 'error'});
            }else if(self.selectedDir().isRoot()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_CANT_DELETE_ROOT'), type: 'warning'});
            }else{
                $('#delete_confirm').subModal('show');
            }
        }

        self.loadingDelete = ko.observable(false);
        self.deleteFolder = function(){
            if(!self.selectedDir()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_FOLDER_REQUIRED'), type: 'error'});
            }else if(self.selectedDir().isRoot()){
                new PNotify({text: jSont._('CHECKMYDTIVE_MSG_CANT_DELETE_ROOT'), type: 'warning'});
            }else{
                self.loadingDelete(true);
                $.getJSON(root + 'approvals/deleteFolder', {path: self.selectedDir().path()}, function(data){
                    self.loadingDelete(false);
                    if(data.rs){
                        $('#delete_confirm').subModal('hide');
                        if($('#jform_path').val() == self.selectedDir().path()){
                            $('#jform_path').val('');
                        }
                        var parent_dirs = self.selectedDir().parent().dirs();
                        self.selectedDir().parent().dirs(parent_dirs - 1);
                        self.selectedDir().parent().children.remove(self.selectedDir());
                        self.selectedDir('');
                    }else{
                        new PNotify({text: data.msg, type: 'error'});
                    }
                });
            }
        }

        self.comments = ko.observableArray();
        $.getJSON(root + 'approvals/getComments', {id: approval_id}, function(data){
            $.each(data.comments, function(i, cm){
                cm.deleteCommentLoading = ko.observable(false);
            });
            self.comments(data.comments);
        });
        self.deleteComment = function(item, comment){
            var item = item;
            var comment = comment;
            return function(){
                comment.deleteCommentLoading(true);
                $.getJSON(root + 'approvals/deleteComment', {id: comment.id}, function(data){
                    comment.deleteCommentLoading(false);
                    if(data.rs){
                        item.comments.remove(comment);
                    }else{
                        new PNotify({text: data.msg, type: 'error'});
                    }
                });
            }
        }

        self.newComment = ko.observable('');
        self.addCommentLoading = ko.observable(false);
        self.addComment = function(){
            if(self.newComment()){
                self.addCommentLoading(true);
                $.ajax({
                    url: root + 'approvals/addComment',
                    data: {
                        approval_id: approval_id,
                        text: self.newComment()
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data){
                        self.addCommentLoading(false);
                        if(data.rs){
                            self.newComment('');
                            data.comment.deleteCommentLoading = ko.observable(false);
                            self.comments.unshift(data.comment);
                            if(data.msg) new PNotify({title: jSont._('CHECKMYDTIVE_NEW_COMMENT'), text: data.msg, type: 'error'});
                        }else{
                            new PNotify({title: jSont._('CHECKMYDTIVE_NEW_COMMENT'), text: data.msg, type: 'error'});
                        }
                    }
                });
            }
        }
        self.clearComment = function(){
            self.newComment('');
        }
    }
})($JVCT);