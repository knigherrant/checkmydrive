var ApprovalsModel = (function($){
    ko.bindingHandlers.foreachGrouped = {
        init: function(element, valueAccessor) {
            var groupedItems,
                options = valueAccessor();

            //create our own computed that transforms the flat array into rows/columns
            groupedItems = ko.computed({
                read: function() {
                    var index, length, group,
                        result = [],
                        count = +ko.utils.unwrapObservable(options.count) || 1,
                        items = ko.utils.unwrapObservable(options.data);

                    //create an array of arrays (rows/columns)
                    for (index = 0, length = items.length; index < length; index++) {
                        if (index % count === 0) {
                            group = [];
                            result.push(group);
                        }

                        group.push(items[index]);
                    }

                    return result;
                },
                disposeWhenNodeIsRemoved: element
            });

            //use the normal foreach binding with our new computed
            ko.applyBindingsToNode(element, { foreach: groupedItems });

            //make sure that the children of this element are not bound
            return { controlsDescendantBindings: true };
        }
    };


    return function(data){console.log(data);
        var self = this;
        self.loading = ko.observable(false);
        self.approvals = ko.observableArray([]);
        self.breadcrumbs = ko.observableArray(data.breadcrumbs);
        self.user = ko.observable(data.user);

        self.parseApproval = function(directory){
            self.approvals([]);
            $.each(directory, function(i, item){
                if(item.type == 'file'){
                    item.likes = ko.observableArray(item.likes);
                    item.dislikes = ko.observableArray(item.dislikes);
                    item.comments = ko.observableArray(item.comments);
                    $.each(item.comments(), function(i, cm){
                        cm.deleteCommentLoading = ko.observable(false);
                    });
                    item.newComment = ko.observable('');
                    item.likeLoading = ko.observable(false);
                    item.addCommentLoading = ko.observable(false);
                }
                self.approvals.push(item);
            });
        }

        self.parseApproval(data.directory);

        self.resize = function(){
            $('.ct_approval_item_top').each(function(i, item){
                var width = $(this).parent().width();
                $(this).width(width);
                $(this).height(width);
                $('.ct_approval_image img').width(width-10);
            });
        }
        self.afterRender = function(){
            self.resize();
            $("a[rel^='lightbox']").slimbox();
        }
        self.enterFolder = function(folder){
            self.loading(true);
            $.getJSON(root + 'approval/getDirectory', {path: folder.path, level: folder.level}, function(data){console.log(data.directory);
                self.loading(false);
                self.parseApproval(data.directory);
                self.breadcrumbs(data.breadcrumbs);
                self.user(data.user);
            });
        }
        self.like = function(item){
            item.likeLoading(true);
            $.getJSON(root + 'approval/like', {id: item.id, like: 1}, function(data){
                item.likeLoading(false);
                item.likes(data.likes);
                item.dislikes(data.dislikes);
            });
        }
        self.dislike = function(item){
            item.likeLoading(true);
            $.getJSON(root + 'approval/like', {id: item.id, like: 0}, function(data){
                item.likeLoading(false);
                item.likes(data.likes);
                item.dislikes(data.dislikes);
            });
        }
        self.clearComment = function(item){
            item.newComment('');
        }
        self.addComment = function(item){
            if(item.newComment()){
                item.addCommentLoading(true);
                $.ajax({
                    url: root + 'approval/addComment',
                    data: {
                        approval_id: item.id,
                        text: item.newComment()
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data){
                        item.addCommentLoading(false);
                        if(data.rs){
                            item.newComment('');
                            data.comment.deleteCommentLoading = ko.observable(false);
                            item.comments.unshift(data.comment);
                        }else{
                            new PNotify({title: jSont._('CHECKMYDTIVE_NEW_COMMENT'), text: data.msg, type: 'error'});
                        }
                    }
                });
            }
        }
        self.deleteComment = function(item, comment){
            var item = item;
            var comment = comment;
            return function(){
                comment.deleteCommentLoading(true);
                $.getJSON(root + 'approval/deleteComment', {id: comment.id}, function(data){
                    comment.deleteCommentLoading(false);
                    if(data.rs){
                        item.comments.remove(comment);
                    }else{
                        new PNotify({title: jSont._('CHECKMYDTIVE_DELETE_COMMENT'), text: data.msg, type: 'error'});
                    }
                });
            }
        }

        $(window).resize(function(){
            self.resize();
        });
    }
})($JVCT);