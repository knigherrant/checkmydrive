var jvTimer = (function($){
    return {
        bind: function() {
            $('#ct_tracking_start').click(function (e) {
                e.preventDefault();
                new PNotify({text: 'Please check client account of task/ticket before tracking time!', type: 'info'});

                $.cookie('ct_tracking_start', 1,{'path' : '/'});
                if($.cookie('ct_tracking_time') == undefined){
                    $.cookie('ct_tracking_time', 0, {'path' : '/'});
                }
                $('#ct_tracking_timer').show();
                $(this).hide();
                $('#ct_tracking_stop').show();

                jvTimer.timerScheduler();
            });
            $('#ct_tracking_stop').click(function (e) {
                e.preventDefault();
                clearInterval(jvTimer.interval);
                $.cookie('ct_tracking_start', 0, {'path' : '/'});
                $(this).hide();
                $('#ct_tracking_start').show();
                jvTimer.getAddtoItems();
                $('#trackingTimeModal').modal('show');
            });
            $('#ct_tracking_clear').click(function (e) {
                e.preventDefault();
                jvTimer.clear();
            });
            $('#ct_tracking_to_client, #ct_tracking_to_type').change(function(){
                jvTimer.getAddtoItems();
            });
            $('#ct_tracking_save').click(function (e) {
                e.preventDefault();
                var client = $('#ct_tracking_to_client').val();
                var type = $('#ct_tracking_to_type').val();
                var item = $('#ct_tracking_to_item').val();
                var status = $('#ct_tracking_to_status').val();
                var time = $.cookie('ct_tracking_time');
                if(!item) new PNotify({text: 'Please select Task or Ticket item!', type: 'error'});
                if(time<60) new PNotify({text: 'Tracking time must be large than 1 minute!', type: 'error'});
                if(item && time>=60){
                    $('#ct_tracking_save_loading').show();
                    $.getJSON(root + 'dashboard/saveTrackingTime?client='+client+'&type='+type+'&item='+item+'&status='+status+'&time='+jvTimer.hms(time),function(data){
                        $('#ct_tracking_save_loading').hide();
                        if(data.rs){
                            jvTimer.clear();
                            new PNotify({text: data.msg, type: 'success'});
                        }else{
                            new PNotify({text: data.msg, type: 'error'});
                        }
                    })
                }
            });
        },
        init: function () {
            this.bind();
            if($.cookie('ct_tracking_start') != undefined){
                $('#ct_tracking_timer').show();
                $('#ct_tracking_clock').text(jvTimer.hms($.cookie('ct_tracking_time')));
                if(parseInt($.cookie('ct_tracking_start'))){
                    $('#ct_tracking_start').hide();
                    $('#ct_tracking_stop').show();
                    this.timerScheduler();
                }else{
                    $('#ct_tracking_stop').hide();
                    $('#ct_tracking_start').show();
                }
            }else{
                $('#ct_tracking_stop').hide();
                $('#ct_tracking_start').show();
            }
        },
        timerScheduler: function(){
            jvTimer.interval = setInterval(function () {
                var time = $.cookie('ct_tracking_time');
                if(parseInt($.cookie('ct_tracking_start'))) {
                    time++;
                    $.cookie('ct_tracking_time', time, {'path' : '/'});
                    $('#ct_tracking_clock').text(jvTimer.hms(time));
                }
            }, 1000);
        },
        clear: function(){
            $.removeCookie('ct_tracking_start',{'path' : '/'});
            $.removeCookie('ct_tracking_time',{'path' : '/'});
            $('#ct_tracking_stop, #ct_tracking_timer').hide();
            $('#ct_tracking_clock').text(jvTimer.hms(0));
            $('#ct_tracking_start').show();
            $('#trackingTimeModal').modal('hide');
        },
        hms: function (secs) {
            secs = secs % 86400;
            var time = [0, 0, secs], i;
            for (i = 2; i > 0; i--) {
                time[i - 1] = Math.floor(time[i] / 60);
                time[i] = time[i] % 60;
                if (time[i] < 10) time[i] = '0' + time[i];
            }
            return time.join(':');
        },
        getAddtoItems: function(){
            var client = $('#ct_tracking_to_client').val();
            var type = $('#ct_tracking_to_type').val();
            $('#ct_tracking_to_item_loading').show();
            $('#ct_tracking_to_item').empty().trigger("liszt:updated");
            $.getJSON(root + 'dashboard/getTrackingList?client='+client+'&type='+type,
            function(data){
                $('#ct_tracking_to_item_loading').hide();
                $.each(data, function(i, it){
                    $('#ct_tracking_to_item').append('<option data-accept="'+parseInt(it.accept)+'" value="'+it.value+'">'+it.text+'</option>');
                })
                $('#ct_tracking_to_item').trigger("liszt:updated");
                $('#ct_tracking_to_item').change(function(){
                    if($(this).find('option:selected').data('accept') == 1){
                        $('ct_tracking_to_status').html('<option value="0">Open</option>');
                    }else{
                        $('ct_tracking_to_status').html('<option value="0">Open</option><option value="1">Close</option>');
                    }
                })
            });
        }
    }
})($JVCT)

$JVCT(function($){
    jvTimer.init();
});