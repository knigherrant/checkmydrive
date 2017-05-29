var ProjectsModel = (function($){
    return function(data){
        var self = this;
        self.loading = ko.observable(false);
        self.projects = ko.observableArray(data);

        self.sortBy = ko.observable('id');
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

        self.projectsToShow = ko.computed(function(){
            if(!self.sortBy()) return self.projects();
            var array= _.sortBy(self.projects(), function(item){
                if(self.sortBy() == 'id' || self.sortBy() == 'progress') return parseInt(item[self.sortBy()]);
                return item[self.sortBy()];
            });

            if(self.sortDir()) array.reverse();

            return array;
        });

        self.gridProjectsModel = new ko.simpleGrid.viewModel({
            data: self.projectsToShow,
            columns: [
                {id: 'id', name: "name", url: "url", phases: "phases", client_name: "client_name", end: "end_format", progress: "progress"}
            ],
            pageSize: 20
        });
    }
})($JVCT);