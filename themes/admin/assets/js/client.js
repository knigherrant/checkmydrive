var ClientModel = (function($){
    return function(data, client_id){
        var self = this;
        self.data = ko.observable();
        self.plans = ko.observableArray();
        self.currencies = ko.observableArray();
        self.account = ko.observable();
        self.client = client_id;
        self.selectedPlan = ko.observable();
        self.selectedCurrency = ko.observable();
        self.expiredDate = ko.observable('');
        self.price = ko.observable(0);
        self.hours = ko.observable('00:00');
        self.h = ko.observable('0');
        self.m = ko.observable('0');
        self.tax = ko.observable('0');
        self.rate = ko.observable('0');
        self.showExpiredDate = ko.observable(false);
        self.unlimited = ko.observable(false);

        self.data.subscribe(function(value){
            self.plans(value.plans);
            self.currencies(value.currencies);
            setTimeout(function(){ $('select').trigger("liszt:updated");}, 1);
            self.account(value.account);
        });

        self.account.subscribe(function(value){
            if(value){
                self.selectedPlan(value.plan_id);
                self.selectedCurrency(value.currency_id);
                setTimeout(function(){ $('select').trigger("liszt:updated");}, 1);
                self.expiredDate(value.expired_date);
                self.price(value.price);
                self.hours(value.hours);
                self.h(value.h);
                self.m(value.m);
                self.tax(value.tax);
                self.rate(value.rate);
            }
        });

        self.selectedPlan.subscribe(function(value){
            self.showExpiredDate(false);
            self.unlimited(false);
            if(value){
                $.each(self.plans(), function(i, plan){
                    if(plan.id == value){
                        if(parseInt(plan.expired_date)) self.showExpiredDate(true);
                        if(parseInt(plan.unlimited)) self.unlimited(true);
                    }
                });
            }
        });

        setInterval(function(){
            $.getJSON(root + 'clients/getAccount?client='+self.client,function(newdata){
                if(newdata.account){
                    if( newdata.account.plan_id != self.data().account.plan_id || newdata.account.hours != self.data().account.hours ||
                        newdata.account.expired_date != self.data().account.expired_date || newdata.account.price != self.data().account.price ||
                        newdata.account.currency_id != self.data().account.currency_id){
                        new PNotify({text: jSont._('CHECKMYDTIVE_MSG_PLAN_SETTINGS_UPDATED_BY_USER'), type:'info'});
                        self.data(newdata);
                    }
                }
            });
        }, 5000);

        if(data) self.data(data);
    }
})($JVCT);