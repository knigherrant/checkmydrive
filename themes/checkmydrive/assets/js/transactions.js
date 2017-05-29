var TransactionsModel = (function($){
    return function(data){
        var self = this;
        self.loading = ko.observable(false);
        self.transactions = ko.observableArray(data);

        self.sortBy = ko.observable('due_date');
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

        self.transactionsToShow = ko.computed(function(){
            if(!self.sortBy()) return self.transactions();
            var array= _.sortBy(self.transactions(), function(item){
                if(self.sortBy() == 'id' || self.sortBy() == 'paid') return parseInt(item[self.sortBy()]);
                if(self.sortBy() == 'amount') return parseFloat(item[self.sortBy()]);
                return item[self.sortBy()];
            });

            if(self.sortDir()) array.reverse();

            return array;
        });


        self.gridTransactionsModel = new ko.simpleGrid.viewModel({
            data: self.transactionsToShow,
            columns: [
                {id: 'id', txn_id: "txn_id", amount_full: "amount_full", product_name: "product_name", due_date: "due_date_format", pdf: 'pdf', paid: 'paid'}
            ],
            pageSize: 20
        });

        self.transaction = ko.observable();
        self.getTransactionDetail = function(value){
            var item = value;
            return function(){
                self.transaction(item);
                $('#ct_transactionDetail').modal('show');
            }
        }
    }
})($JVCT);