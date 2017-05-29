var ProjectModel = (function($){
    return function(data){
        var self = this;
        self.loading = ko.observable(false);
        self.project = ko.observable(data);
        self.tasks = ko.observableArray(data.tasks);

        self.sortBy = ko.observable('start');
        self.sortDir = ko.observable(0);
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

        self.tasksToShow = ko.computed(function(){
            if(!self.sortBy()) return self.tasks();
            var array= _.sortBy(self.tasks(), function(item){
                if(self.sortBy() == 'id') return parseInt(item[self.sortBy()]);
                return item[self.sortBy()];
            });

            if(self.sortDir()) array.reverse();

            var tasktree = [];
            $.each(array, function(i, item){
                if(!parseInt(item.parent_id)){
                    tasktree.push(item);
                    $.each(array, function(j, subitem){
                        if(item.type=='task' && subitem.parent_id == item.id){
                            tasktree.push(subitem);
                        }
                    })
                }
            });

            return tasktree;
        });

        self.gridTasksModel = new ko.simpleGrid.viewModel({
            data: self.tasksToShow,
            columns: [
                {id: 'id', name: "name", priority_text: "priority_text", start: "start_format", end: "end_format", assigned_name: "assigned_name", parent_id: "parent_id", status: "status", type: "type", note: "note", billtime: "billtime", attachments: "attachments"}
            ],
            pageSize: 20
        });

        self.task = ko.observable();
        self.getTaskDetail = function(value){
            var item = value;
            return function(){
                self.task(item);
                $('#ct_taskDetail').modal('show');
            }
        }
    }
})($JVCT);