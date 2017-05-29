var TicketForm = (function($){
    function getSizeList(){
        return [
            {text: 'Normal', value: ''},
            {text: 'Mini', value: 'input-mini'},
            {text: 'Small', value: 'input-small'},
            {text: 'Medium', value: 'input-medium'},
            {text: 'Large', value: 'input-large'},
            {text: 'XLarge', value: 'input-xlarge'},
            {text: 'XXLarge', value: 'input-xxlarge'},
            {text: 'Block Level', value: 'input-block-level'}
        ];
    }
    function getValiadteList(){
        return [
            {text: 'None', value: ''},
            {text: 'Number', value: 'number'},
            {text: 'Email', value: 'email'},
            {text: 'Url', value: 'url'}
        ];
    }
    function fieldText(data){
        var self = this;
        self.name = ko.observable('Text');
        self.icon = ko.observable('fa fa-font');
        self.sizeList = ko.observableArray(getSizeList());
        self.validateList = ko.observableArray(getValiadteList());
        self.data = {};
        if(data){
            self.data.size = ko.observable(data.size);
            self.data.minLen = ko.observable(data.minLen);
            self.data.maxLen = ko.observable(data.maxLen);
            self.data.minVal = ko.observable(data.minVal);
            self.data.maxVal = ko.observable(data.maxVal);
            self.data.prepend = ko.observable(data.prepend);
            self.data.append = ko.observable(data.append);
            self.data.validate = ko.observable(data.validate);
        }else{
            self.data.size = ko.observable('');
            self.data.minLen = ko.observable('');
            self.data.maxLen = ko.observable('');
            self.data.minVal = ko.observable('');
            self.data.maxVal = ko.observable('');
            self.data.prepend = ko.observable('');
            self.data.append = ko.observable('');
            self.data.validate = ko.observable('');
        }

        self.html = ko.computed(function(){
            var str = '';
            if(self.data.prepend() || self.data.append()){
                if(self.data.prepend() && self.data.append()) str += '<div class="input-prepend input-append">';
                else if(self.data.prepend()) str += '<div class="input-prepend">';
                else str += '<div class="input-append">';
            }
            if(self.data.prepend()) str += '<span class="add-on">' + self.data.prepend() + '</span>';
            str += '<input type="text" class="'+self.data.size()+'"/>';
            if(self.data.append()) str += '<span class="add-on">' + self.data.append() + '</span>';
            if(self.data.prepend() || self.data.append()){
                str += '</div>';
            }
            return str;
        });
    }
    function fieldDropdown(data){
        var self = this;
        self.name = ko.observable('Dropdown');
        self.icon = ko.observable('fa fa-caret-down');
        self.data = {};
        if(data){
            self.data.caption = ko.observable(data.caption);
            self.data.options = ko.observableArray([]);
            $.each(data.options, function(i, it){
                self.data.options.push({text: ko.observable(it.text)});
            });
        }else{
            self.data.caption = ko.observable('- Select Option -');
            self.data.options = ko.observableArray([{text: ko.observable('')}, {text: ko.observable('')}]);
        }

        self.html = ko.computed(function(){
            var str = '<select>';
            if(self.data.caption()) str += '<option>'+self.data.caption()+'</option>';
            else if(self.data.options().length) str += '<option>'+self.data.options()[0].text()+'</option>';
            str += '</select>';
            return str;
        });
        self.addOption = function(item){
            var position = self.data.options().indexOf(item);
            if(position>=0) self.data.options.splice(position+1, 0, {text: ko.observable('')});
            else self.data.options.push({text: ko.observable('')});
        };
        self.removeOption = function(item){
            self.data.options.remove(item);
        };
    }
    function fieldCheckboxes(data){
        var self = this;
        self.icon = ko.observable('fa fa-square-o');
        self.name = ko.observable('Checkboxes');
        self.data = {};
        if(data){
            self.data.other = ko.observable(data.other);
            self.data.options = ko.observableArray([]);
            $.each(data.options, function(i, it){
                self.data.options.push({text: ko.observable(it.text)});
            });
        }else{
            self.data.other = ko.observable(false);
            self.data.options = ko.observableArray([{text: ko.observable('')}, {text: ko.observable('')}]);
        }

        self.html = ko.computed(function(){
            var str = '';
            $.each(self.data.options(), function(i, it){
                str += '<div><label><input type="checkbox" false="" onclick="javascript: return false;"> '+ it.text() +'</label></div>';
            });
            if(self.data.other()) str += '<div><label><input type="checkbox" false="" onclick="javascript: return false;"> Other <input type="text" class="input-medium"></label></div>';
            return str;
        });
        self.addOption = function(item){
            var position = self.data.options().indexOf(item);
            if(position>=0) self.data.options.splice(position+1, 0, {text: ko.observable('')});
            else self.data.options.push({text: ko.observable('')});
        };
        self.removeOption = function(item){
            self.data.options.remove(item);
        };
    }
    function fieldRadio(data){
        var self = this;
        self.icon = ko.observable('fa fa-circle-o');
        self.name = ko.observable('Radio');
        self.data = {};
        if(data){
            self.data.other = ko.observable(data.other);
            self.data.options = ko.observableArray([]);
            $.each(data.options, function(i, it){
                self.data.options.push({text: ko.observable(it.text)});
            });
        }else{
            self.data.other = ko.observable(false);
            self.data.options = ko.observableArray([{text: ko.observable('')}, {text: ko.observable('')}]);
        }

        self.html = ko.computed(function(){
            var str = '';
            $.each(self.data.options(), function(i, it){
                str += '<div><label><input type="radio" false="" onclick="javascript: return false;"> '+ it.text() +'</label></div>';
            });
            if(self.data.other()) str += '<div><label><input type="radio" false="" onclick="javascript: return false;"> Other <input type="text" class="input-medium"></label></div>';
            return str;
        });
        self.addOption = function(item){
            var position = self.data.options().indexOf(item);
            if(position>=0) self.data.options.splice(position+1, 0, {text: ko.observable('')});
            else self.data.options.push({text: ko.observable('')});
        };
        self.removeOption = function(item){
            self.data.options.remove(item);
        };
    }
    function fieldDate(data){
        var self = this;
        self.icon = ko.observable('fa fa-calendar');
        self.name = ko.observable('Date');
        self.data = {};
        if(data){
            self.data.format = ko.observable(data.format);
        }else{
            self.data.format = ko.observable('MM/dd/yyyy');
        }

        self.html = ko.computed(function(){
            return '<div class="input-append"><input type="text" class="input-medium" placeholder="'+self.data.format()+'"><span class="add-on"><i class="fa fa-calendar"></i></span></div>';
        });
    }
    function fieldTime(data){
        var self = this;
        self.icon = ko.observable('fa fa-clock-o');
        self.name = ko.observable('Time');
        self.data = {};
        if(data){
            self.data.format = ko.observable(data.format);
        }else{
            self.data.format = ko.observable('hh:mm:ss');
        }

        self.html = ko.computed(function(){
            return '<div class="input-append"><input type="text" class="input-medium" placeholder="'+self.data.format()+'"><span class="add-on"><i class="fa fa-clock-o"></i></span></div>';
        });
    }
    function fieldParagraph(data){
        var self = this;
        self.icon = ko.observable('fa fa-paragraph');
        self.name = ko.observable('Paragraph');
        self.data = {};
        if(data){
            self.data.width = ko.observable(data.width);
            self.data.height = ko.observable(data.height);
        }else{
            self.data.width = ko.observable('100%');
            self.data.height = ko.observable('100px');
        }

        self.html = ko.computed(function(){
            return '<textarea style="width: '+self.data.width()+'; height: '+self.data.height()+'"></textarea>';
        });
    }
    function fieldCustom(data){
        var self = this;
        self.icon = ko.observable('fa fa-edit');
        self.name = ko.observable('Custom');
        self.data = {};
        if(data){
            self.data.content = ko.observable(data.content);
        }else{
            self.data.content = ko.observable('Some html here.');
        }

        self.html = ko.computed(function(){
            return '<div>'+self.data.content()+'</div>';
        });
    }
    function fieldFile(data){
        this.icon = ko.observable('fa fa-file');
        this.name = ko.observable('File');
        this.html = '<input type="file"/>';
    }

    function getNewField(type, data){
        switch (type) {
            case 'text': return new fieldText(data); break;
            case 'dropdown': return new fieldDropdown(data); break;
            case 'checkboxes': return new fieldCheckboxes(data); break;
            case 'radio': return new fieldRadio(data); break;
            case 'date': return new fieldDate(data); break;
            case 'time': return new fieldTime(data); break;
            case 'paragraph': return new fieldParagraph(data); break;
            case 'custom': return new fieldCustom(data); break;
            case 'file': return new fieldFile(data); break;
        }
    }

    function fieldType(viewModel, type, locked, label, desc, required, fieldData){
        var self = this;
        self.type = ko.observable(type);

        if(locked) self.locked = ko.observable(locked);
        else self.locked = ko.observable(false);

        if(label) self.label = ko.observable(label);
        else self.label = ko.observable('');

        if(desc) self.desc = ko.observable(desc);
        else self.desc = ko.observable('');

        if(required) self.required = ko.observable(required);
        else self.required = ko.observable(false);

        self.field = getNewField(self.type(), fieldData);

        self.selected = ko.observable(false);

        self.fieldClass = ko.computed(function(){
            var str = 'response-field-'+self.type();
            if(self.selected()) str += ' editing';
            return str;
        });

        self.duplicate = function(){
            return new fieldType(viewModel, self.type(), self.locked(), self.label(), self.desc(), self.required(), ko.toJS(self.field.data));
        }

        self.getData = function(){
            var data = {};
            data.type = self.type();
            data.locked = self.locked();
            data.label = self.label();
            data.desc = self.desc();
            data.required = self.required();
            data.data = self.field.data;
            return data;
        }
    }

    ko.bindingHandlers.draggableList = {
        init: function(element, valueAccessor, allBindingsAccessor, context) {
            var bindings = allBindingsAccessor();
            $(element).children().draggable({
                connectToSortable: bindings.connectToSortable,
                helper: function(){
                    var $helper = $("<div class='response-field-draggable-helper' />");
                    $helper.css({
                        width: $(bindings.connectToSortable).width(),
                        height: '80px'
                    });
                    return $helper;
                },
                zIndex: 999,
                revertDuration: 0,
                revert: true
            });
        }
    };
    ko.bindingHandlers.sortableList = {
        init: function(element, valueAccessor, allBindingsAccessor, viewModel, context) {
            $(element).data("sortList", valueAccessor()); //attach meta-data
            $(element).sortable({
                forcePlaceholderSize: true,
                placeholder: 'sortable-placeholder',
                stop: function(event, ui) {
                    var newParent = ui.item.parent().data("sortList");
                    var item = ui.item.data("sortItem");
                    if (item) {
                        //identify parents
                        var originalParent = ui.item.data("parentList");
                        //figure out its new position
                        var position = ko.utils.arrayIndexOf(ui.item.parent().children(), ui.item[0]);
                        if (position >= 0) {
                            originalParent.remove(item);
                            newParent.splice(position, 0, item);
                        }
                    }else{
                        var type = $(ui.item).data('field-type');
                        var position = $(ui.item).index();
                        if(position >= 0){
                            var newItem = new fieldType(viewModel, type, false, 'Untitled');
                            newParent.splice(position, 0, newItem);
                            //Set selected field
                            $.each(newParent(), function(i, it){
                                it.selected(false);
                            });
                            newItem.selected(true);
                            viewModel.selectedField(newItem);
                        }
                    }
                    ui.item.remove();
                }
            });
        }
    };
    ko.bindingHandlers.sortableItem = {
        init: function(element, valueAccessor) {
            var options = valueAccessor();
            $(element).data("sortItem", options.item);
            $(element).data("parentList", options.parentList);
        }
    };

    return function(fields, el){
        var self = this;
        self.fieldTypes = ko.observableArray([
            new fieldType(self, 'text'),
            new fieldType(self, 'dropdown'),
            new fieldType(self, 'checkboxes'),
            new fieldType(self, 'radio'),
            new fieldType(self, 'date'),
            new fieldType(self, 'time'),
            new fieldType(self, 'paragraph'),
            new fieldType(self, 'custom')
        ]);

        self.responseFields = ko.observableArray();
        if(!fields.length){
            self.responseFields.push(new fieldType(self, 'text', true, jSont._('CHECKMYDTIVE_SUBJECT'),'', true));
            self.responseFields.push(new fieldType(self, 'paragraph', true, jSont._('CHECKMYDTIVE_MESSAGE'), jSont._('CHECKMYDTIVE_DESC_PLEASE_SUBMIT_YOUR_SUPPORT_REQUEST'), true));
            self.responseFields.push(new fieldType(self, 'file', true, jSont._('CHECKMYDTIVE_ATTACHMENTS'),''));
        }else{
            $.each(fields, function(i, item){
                self.responseFields.push(new fieldType(self, item.type, item.locked, item.label, item.desc, item.required, item.data));
            });
        }

        self.responseData = ko.computed(function(){
            var data = [];
            $.each(self.responseFields(), function(i, item){
                data.push(item.getData());
            });
            return ko.toJSON(data);
        });

        self.selectedField = ko.observable('');

        self.setSelectedField = function(data){
            var item = data;
            return function(){
                $.each(self.responseFields(), function(i, it){
                    it.selected(false);
                });
                item.selected(true);
                self.selectedField(item);
            }
        }

        self.copyField = function(data){
            var item = data;
            return function(){
                var position = self.responseFields().indexOf(item);
                if(position>=0){
                    var newItem = item.duplicate();
                    self.responseFields.splice(position+1, 0, newItem);
                }
            }
        }

        self.deleteField = function(data){
            var item = data
            return function(){
                if(item.selected()) self.selectedField('');
                self.responseFields.remove(item);
            }
        }

        $(el).attr('data-bind','value: responseData');
    }
})($JVCT);