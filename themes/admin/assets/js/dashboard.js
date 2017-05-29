var DashboardModel = (function($){
    var Thing = function(data){
        var that = this;
        that.data = data;
        that.remind = ko.observable(false);
    }

    ko.bindingHandlers.slideVisible = {
        update: function(element, valueAccessor) {
            // Whenever the value subsequently changes, slowly fade the element in or out
            var value = valueAccessor();
            ko.unwrap(value) ? $(element).slideDown(300) : $(element).slideUp(300);
        }
    };

    return function(data){
        var self = this;
        self.loading = ko.observable(false);
        self.thingsToDo = ko.observableArray();

        $.each(data, function(i, it){
           self.thingsToDo.push(new Thing(it));
        });

        self.callOverlay = function(state){
            if(state) $('#ct_calendar_ctn').loadingOverlay();
            else $('#ct_calendar_ctn').loadingOverlay('remove');
        }

        self.toggleRemind = function(item){
            var remind = !item.remind();
            $.each(self.thingsToDo(), function(i, it){
                it.remind(false);
            });
            item.remind(remind);
        }

        self.getThingsToDo = function(){
            self.callOverlay(true);
            $.getJSON(root + 'dashboard/getThingsToDo', function(data){
                self.callOverlay(false);
                self.thingsToDo([]);
                $.each(data, function(i, it){
                    self.thingsToDo.push(new Thing(it));
                });
            });
        }

        self.remind = function(item, time){
            self.callOverlay(true);
            $.getJSON(root + 'dashboard/remind?type='+item.data.type+'&time='+time+'&id='+item.data.id, function(data){
                self.callOverlay(false);
                if(data.rs){
                    $('#calendar').fullCalendar('refetchEvents');
                    self.getThingsToDo();
                }
                else alert(data.msg);
            });
        }

        self.remindDay = function(item){
            self.remind(item, '1d');
        }

        self.remindWeek = function(item){
            self.remind(item, '1w');
        }

        self.activeDragg = function(elements, thing){
            var eventObject = {
                title: thing.data.name,
                itemid: thing.data.id,
                type: thing.data.type
            };
            $(elements[1]).data('eventObject', eventObject);
            $(elements[1]).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  // original position after the drag
            });
        }

        $(document).click(function(event){
            if( !$(event.target).is('#external-events a.remind-btn') &&
                !$(event.target).is('#external-events .remind-btn i') &&
                !$(event.target).is('#external-events .remind-ctn') &&
                !$(event.target).is('#external-events .remind-ctn a')) {
                $.each(self.thingsToDo(), function(i, it){
                    it.remind(false);
                });
            }
        });
    }
})($JVCT);



$JVCT(function($){
    $( ".accordion" ).accordion();

    /* initialize the notification */
    $('.notification:first').addClass('active').show();
    $('.notification a.close').click(function(){
        $(this).parent().remove();
        $('.notification:first').addClass('active').show();
    });
    if($('.notification').length > 1){
        setInterval(function(){
            if($('.notification').length > 1){
                var $active = $('.notification').not('.active').eq(0);
                $('.notification').removeClass('active');
                $active.addClass('active');
                $('.notification').not('.active').hide();
                $active.fadeIn(500);
            }
        }, 4000);
    }

    /* initialize the calendar */
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        defaultView: 'basicWeek',
        columnFormat: {week: 'ddd d/M'},
        editable: true,
        droppable: true,
        eventSources: [
            // your event source
            {
                url: 'dashboard/getEvents',
                color: '#636363',
                textColor: '#ffffff'
            }
        ],
        eventClick: function(event) {
            if (event.url) {
                window.open(event.url);
                return false;
            }
        },
        eventDrop: function( event, delta, revertFunc ) {
            var start = '';
            var end = '';
            if(event.state == 'start') start = $.fullCalendar.formatDate(event.start, 'yyyy-MM-dd');
            if(event.state == 'end' || event.state == 'remind') end = $.fullCalendar.formatDate(event.start, 'yyyy-MM-dd');
            DashboardModel.callOverlay(true);
            $.getJSON(root + 'dashboard/updateCalendar',{
                id: event.itemid,
                type: event.type,
                start: start,
                end: end,
                state: event.state
            }, function(data){
                DashboardModel.callOverlay(false);
                if(data.rs){
                    new PNotify({text: data.msg, type: 'success'});
                    DashboardModel.getThingsToDo();
                }else alert(data.msg);
                $('#calendar').fullCalendar('refetchEvents');
            });
        },
        drop: function(date, allDay) { // this function is called when something is dropped
            // retrieve the dropped element's stored Event Object
            var eventObject = $(this).data('eventObject');
            DashboardModel.callOverlay(true);
            $.getJSON(root + 'dashboard/updateCalendar',{
                id: eventObject.itemid,
                type: eventObject.type,
                start: '',
                end: $.fullCalendar.formatDate(date, 'yyyy-MM-dd'),
                state: 'end'
            }, function(data){
                DashboardModel.callOverlay(false);
                if(data.rs){
                    $('#calendar').fullCalendar('refetchEvents');
                    new PNotify({text: data.msg, type: 'success'});
                    DashboardModel.getThingsToDo();
                }else alert(data.msg);
            });
        }
    });
});