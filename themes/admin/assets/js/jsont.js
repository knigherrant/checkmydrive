if(!jQuery.curCss){
    jQuery.curCSS = jQuery.css;
}
$JVCT(function($){
    PNotify.prototype.options.delay = 5000;
    PNotify.prototype.options.styling = 'fontawesome';

    $('#j-main-container').prepend($('#toolbar'));
    $('#j-main-container').prepend($('#system-message-container').show());
    $('#toolbar button').attr('type', 'button');
});

var jSont = (function(){
    var fn = {
        log: function(x){
            console.log(x);
        },
        checkAll : function (a,b){
             b || (b = 'cb');
            if (a.form) {
                for (var c = 0, d = 0, f = a.form.elements.length; d < f; d++) {
                    var e = a.form.elements[d];
                    if (e.type == a.type && (b && 0 == e.id.indexOf(b) || !b)) e.checked = a.checked,
                    c += !0 == e.checked ? 1 : 0
                }
                a.form.boxchecked && (a.form.boxchecked.value = c);
                return !0
            }
            return !1
        },
        isChecked : function (a, b){
            'undefined' === typeof b && (b = document.getElementById('adminForm'));
            !0 == a ? b.boxchecked.value++ : b.boxchecked.value--;
        },
        tableOrdering : function(a, b, c, d){
            'undefined' === typeof d && (d = document.getElementById('adminForm'));
            d.filter_order.value = a;
            d.filter_order_Dir.value = b;
            this.submitform(c, d)
        },
        submitform : function (a,b){
            'undefined' === typeof b && (b = document.getElementById('adminForm'));
            'undefined' !== typeof a && '' !== a && (b.task.value = a);
            
            if(a == 'save' || a == 'apply'){
                var error = [];
                jQuery(b).find('input[aria-required="true"]').each(function(){    
                    if(!jQuery(this).val()){
                        error.push(jQuery(this));
                    }
                });
                if(error.length > 0){
                       jQuery('input').removeClass('has-error');
                        jQuery('html, body').animate({
                            scrollTop: error[0].offset().top
                        }, 500);
                       jQuery(error).each(function(i, input){
                           input.addClass('has-error');
                       })
                       return false;
                }
            }
            
            if ('function' == typeof b.onsubmit) b.onsubmit();
            'function' == typeof b.fireEvent && b.fireEvent('submit');
            b.submit()
        },
        submitbutton : function (a){
            this.submitform(a);
        },
        _ : function(text){
            var x = text;
            jQuery.each(lang, function(t, value) {
                if(text == t){
                    x = value;
                }
            }); 
            return x;
        },
        listItemTask : function (a, b) {
            var c = document.adminForm,
            d = c[a];
            if (d) {
                for (var f = 0; ; f++) {
                    var e = c['cb' + f];
                    if (!e) break;
                    e.checked = !1
                }
                d.checked = !0;
                c.boxchecked.value = 1;
                this.submitbutton(b)
            }
            return !1
        }
    }
    return fn;
})();



/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */

(function($)
{
	$(document).ready(function()
	{
		//$('*[rel=tooltip]').tooltip()

		// Turn radios into btn-group
		$('.radio.btn-group label').addClass('btn');
		$('.btn-group label:not(.active)').click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.btn-group').find('label').removeClass('active btn-success btn-danger btn-primary');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
			}
		});
		$('.btn-group input[checked=checked]').each(function()
		{
			if ($(this).val() == '') {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-danger');
			} else {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-success');
			}
		});
		// add color classes to chosen field based on value
		$('select[class^="chzn-color"], select[class*=" chzn-color"]').on('liszt:ready', function(){
			var select = $(this);
			var cls = this.className.replace(/^.(chzn-color[a-z0-9-_]*)$.*/, '\1');
			var container = select.next('.chzn-container').find('.chzn-single');
			container.addClass(cls).attr('rel', 'value_' + select.val());
			select.on('change click', function()
			{
				container.attr('rel', 'value_' + select.val());
			});

		});
	})
})(jQuery);
