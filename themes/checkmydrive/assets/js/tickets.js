var TicketsModel = (function($){
    var instances_by_id = {}; // needed for referencing instances during updates.
    var init_queue = $.Deferred(); // jQuery deferred object used for creating TinyMCE instances synchronously
    init_queue.resolve();

    ko.bindingHandlers.tinymce = {
        init: function (element, valueAccessor, allBindingsAccessor, context) {
            var options = allBindingsAccessor().tinymceOptions || {
                menubar : false,
                plugins: [
                    "autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                toolbar: "link unlink image code"
            };
            var modelValue = valueAccessor();
            var value = ko.utils.unwrapObservable(valueAccessor());
            var $element = $(element);

            options.setup = function (ed) {
                ed.on('init', function (e) {
                    var $editor = $element.parent();
                    $editor.find('.mce-tinymce .mce-container-body.mce-flow-layout').prepend($editor.find('.mce-tinymce .mce-i-link').parent().parent().parent().parent());
                    $editor.find('.mce-panel .mce-container-body .mce-toolbar.mce-last').after($('<div/>',{class: 'ct_mce_title', text: allBindingsAccessor().parentField.desc}));
                });
                ed.on('change', function (e) {
                    if (ko.isWriteableObservable(modelValue)) {
                        var current = modelValue();
                        if(current !== this.getContent()) {
                            modelValue(this.getContent());
                        }
                    }
                });
                ed.on('keyup', function (e) {
                    if (ko.isWriteableObservable(modelValue)) {
                        var current = modelValue();
                        var editorValue = this.getContent({ format: 'raw' });
                        if(current !== editorValue) {
                            modelValue(editorValue);
                        }
                    }
                });
                ed.on('beforeSetContent', function (e, l) {
                    if (ko.isWriteableObservable(modelValue)) {
                        if (typeof (e.content) != 'undefined') {
                            var current = modelValue();
                            if(current !== e.content) {
                                modelValue(e.content);
                            }
                        }
                    }
                });
            };

            //handle destroying an editor
            ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
                $(element).parent().find("span.mceEditor,div.mceEditor").each(function (i, node) {
                    var tid = node.id.replace(/_parent$/, ''),
                        ed = tinymce.get(tid);
                    if (ed) {
                        ed.remove();
                        // remove referenced instance if possible.
                        if (instances_by_id[tid]) {
                            delete instances_by_id[tid];
                        }
                    }
                });
            });

            setTimeout(function () {
                if (!element.id) {
                    element.id = tinymce.DOM.uniqueId();
                }
                tinyMCE.init(options);
                tinymce.execCommand("mceAddEditor", true, element.id);
            }, 0);
            $element.html(value);

        },
        update: function (element, valueAccessor, allBindingsAccessor, context) {
            var $element = $(element),
                value = ko.utils.unwrapObservable(valueAccessor()),
                id = $element.attr('id');

            //handle programmatic updates to the observable
            // also makes sure it doesn't update it if it's the same.
            // otherwise, it will reload the instance, causing the cursor to jump.
            if (id !== undefined) {
                var tinymceInstance = tinyMCE.get(id);
                if (!tinymceInstance)
                    return;
                var content = tinymceInstance.getContent({ format: 'raw' });
                if (content !== value) {
                    $element.html(value);
                    //this should be more proper but ctr+c, ctr+v is broken, above need fixing
                    //tinymceInstance.setContent(value,{ format: 'raw' })
                }
            }
        }
    };

    ko.bindingHandlers.file = {
        init: function(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
            var bindings = allBindingsAccessor();
            var $process = $(bindings.process);
            var $parent = $(element).parent();
            $(element).fileupload({
                url: root + 'ticket/uploadAttach',
                dataType: 'json',
                send: function(e, data){
                    $process.show();
                },
                done: function (e, data) {
                    $process.hide();
                    if(data.result.rs){
                        TicketsModel.attachments.push(data.result.file);
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
        self.alertShow = ko.observable(false);
        self.newTaskLoading = ko.observable(false);
        self.tickets = ko.observableArray(data);
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

        self.ticketsToShow = ko.computed(function(){
            if(!self.sortBy()) return self.tickets();
            var array= _.sortBy(self.tickets(), function(item){
                if(self.sortBy() == 'id') return parseInt(item[self.sortBy()]);
                return item[self.sortBy()];
            });

            if(self.sortDir()) array.reverse();

            return array;
        });

        self.gridTicketsModel = new ko.simpleGrid.viewModel({
            data: self.ticketsToShow,
            columns: [
                {id: 'id', subject: "subject", status: "status", created: "created_format", accept: "accept",billtime: "billtime", billcomplete: "billcomplete", attached: "attached", url: "url", read: 'read'}
            ],
            pageSize: 20
        });

        self.form = ko.observableArray([]);
        self.addTicket = function(){
            self.newTaskLoading(true);
            $.getJSON(root + 'ticket/canTicket',function(data){
                self.newTaskLoading(false);
                if(data.rs){
                    self.form([]);

                    $.each(data.form, function(i, item){
                        item.values = {};
                        item.colspan = 1;
                        switch(item.type){
                            case 'text':
                            case 'paragraph':
                            case 'dropdown':
                            case 'date':
                            case 'time':
                                item.values.value = ko.observable('');
                                break;
                            case 'checkboxes':
                                item.values.value = ko.observableArray([]);
                                item.values.other = ko.observable('');
                                break;
                            case 'radio':
                                item.values.value = ko.observable('');
                                item.values.other = ko.observable('');
                                break;
                            case 'custom':
                                item.colspan = 2;
                                break;
                        }
                        self.form.push(item);
                    });

                    $('#ct_editor').modal('show');
                    self.alertShow(false);
                }else{
                    self.alertShow(true);
                }
            });
        }

        self.formValue = ko.computed(function(){
            var formValue = [];
            $.each(self.form(), function(i, item){
                if(item.type != 'file' && item.type != 'custom'){
                    var temp = {};
                    temp.type = item.type;
                    temp.locked = item.locked;
                    temp.required = item.required;
                    temp.label = item.label;
                    temp.values = ko.toJS(item.values);
                    formValue.push(temp);
                }
            });
            return formValue;
        });

        self.submitTask = function(){
            var errorMsg = [];
            $('.jvfbdate input').each(function(i, item){
                if($(item).val()) $(item).trigger('change');
            });

            $.each(self.form(), function(i, item){
                if(item.type != 'file' && item.type != 'custom'){
                    if(item.required && !item.values.value()) errorMsg.push(item.label + ' ' + jSont._('CHECKMYDTIVE_MSG_FIELD_REQUIRED'));
                    if(item.type == 'checkboxes' && item.values.value().length && item.values.value().indexOf('other')>=0 && !item.values.other()){
                        errorMsg.push('Please enter other option of '+ item.label);
                    }
                    if(item.type == 'radio' && item.values.value() == 'other' && !item.values.other()){
                        errorMsg.push('Please enter other option of '+ item.label);
                    }
                }
            });

            if(errorMsg.length) new PNotify({title: jSont._('CHECKMYDTIVE_NEW_TICKET'), text: errorMsg.join('<br/>')});
            else{
                $('#ct_editor').modal('hide');
                self.newTaskLoading(true);
                $.ajax({
                    url: root + 'ticket/submit',
                    type: 'post',
                    dataType: "json",
                    data: {
                        form: ko.toJSON(self.formValue()),
                        attachs: (self.attachments() || [])
                    },
                    success: function(data){
                        if(data.rs){
                            self.attachments([]);
                            $.getJSON(root + 'ticket/getTickets',function(newTitkets){
                                self.newTaskLoading(false);
                                new PNotify({title: jSont._('CHECKMYDTIVE_NEW_TICKET'), text: data.msg, type: 'success'});
                                self.tickets(newTitkets);
                            });
                        }else{
                            self.newTaskLoading(false);
                            new PNotify({title: jSont._('CHECKMYDTIVE_NEW_TICKET'), text: data.msg});
                        }
                    }
                });
            }
        }

        self.deleteAttach = function(item){
            $.ajax({
                type: 'post',
                url: root + 'ticket/deleteAttach',
                data:{ file: item },
                dataType: 'json',
                success: function(data){
                    if(data.rs) self.attachments.remove(item);
                }
            });
        }

        self.renderDate = function(el, item){
            $(el).datetimepicker({
                pickTime: false
            }).on('changeDate', function(e){
                item.values.value($(el).find('input').val());
            });
        }

        self.renderTime = function(el, item){
            $(el).datetimepicker({
                pickDate: false
            }).on('changeDate', function(e){
                item.values.value($(el).find('input').val());
            });
        }

        //Modal Add ticket
        $('#ct_editor').on('hidden', function () {
            if(self.attachments().length){
                $.ajax({
                    type: 'post',
                    url: root + 'ticket/deleteAttach',
                    data:{ files: self.attachments() },
                    dataType: 'json',
                    success: function(data){
                        if(data.rs) self.attachments([]);
                    }
                });
            }
        });
        self.accept = function(item){
            
            var tr = $('table.table-bordered').find('tr.item' + item.id);
            $.ajax({
                    url: root + 'ticket/accept?id=' + item.id,
                    dataType: 'json',
                    success: function(data){
                        if(data.ok == true){
                            tr.find('td.tlink a').addClass('ct_done');
                            tr.find('td.tstatus span').hide();
                            tr.find('td.tstatus span.label-success').html('Close').show();
                            new PNotify({text: data.msg, type: 'info'});
                        }
                    }
                });
        }
    }
})($JVCT);