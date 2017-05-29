jQuery.noConflict();

jQuery(document).ready(function(){
		
	/*sidebar menu*/			
	jQuery('.sidebar-menu li>ul li[class=active]').parents('ul').show().prev('.fa-angle-left').addClass('current'); 			
	jQuery('.sidebar-menu li:has(ul) > ul').addClass('menu-open');
	jQuery('[data-toggle-menu]').click(function() {
	jQuery(this).toggleClass('current');
	jQuery(this).next('ul').slideToggle(500);
	jQuery(this).parent().siblings('li').children('ul:visible').slideUp(500).parent('li').find('.fa-angle-left').removeClass('current');
		return false;
	});
	/**
	 * This will remove username/password text in the login form fields
	**/
	jQuery('.username, .password').focusout(function(){
		if(jQuery(this).val() != '') {
			jQuery(this).css({backgroundPosition: "0 -32px"});	
		} else {
			jQuery(this).css({backgroundPosition: "0 0"});	
		}
	});
	
	jQuery('.username, .password').focusin(function(){
		if(jQuery(this).val() == '') {
			jQuery(this).css({backgroundPosition: "0 -32px"});	
		}
	});
	
	
	/**
	 * Message Notify Drop Down
	**/
	jQuery('[data-message]').click(function(){

		var t = jQuery(this).parent();
		var url = t.attr('href');
		if(t.hasClass('showmsg')) {
			t.removeClass('showmsg');
		//	t.find('.thicon').removeClass('thiconhover');
			t.parent().find('.dropbox').remove();
			
		} else {
			
			jQuery('#nav-note li').each(function(){
				jQuery(this).find('.showmsg').removeClass('showmsg');
				//jQuery(this).find('.thicon').removeClass('thiconhover');
				jQuery(this).find('.dropbox').remove();
			});
			
			t.addClass('showmsg');
		//	t.find('.thicon').addClass('thiconhover');
			t.parent().append('<div class="dropbox"></div>');
						
			jQuery.post(url,function(data){
				jQuery('.dropbox').append(data);
			});
		}
		return false;
		
	});
	
	jQuery(document).click(function(event) {
		var msglist = jQuery('.dropbox');
		if(!jQuery(event.target).is('.dropbox')) {
			if(msglist.is(":visible")) {
				msglist.prev().removeClass('showmsg');
				//msglist.prev().find('.thicon').removeClass('thiconhover');
				msglist.remove();
			}
		}        
	});

	
	/**
	 * Login form validation
	**/
	jQuery('#loginform').submit(function(){
		var username = jQuery('.username').val();
		var password = jQuery('.password').val();
		if(username == '' && password == '') {
			jQuery('.loginNotify').slideDown('fast');	
			return false;
		} else {
			return true;
		}
	});
	
	
	/**
	 * Sidebar accordion
	**/
	jQuery('#accordion h3').click(function() {
		if(jQuery(this).hasClass('open')) {
			jQuery(this).removeClass('open');
			jQuery(this).next().slideUp('fast');
		} else {
			jQuery(this).addClass('open');
			jQuery(this).next().slideDown('fast');
		} return false;
	});
		
	
	/**
	 * Widget Box Toggle
	**/
	jQuery('.widgetbox h3, .widgetbox2 h3').hover(function(){
		jQuery(this).addClass('arrow');
		return false;
	},function(){
		jQuery(this).removeClass('arrow');
		return false;
	});
	
	jQuery('.widgetbox h3, .widgetbox2 h3').click(function(){
		jQuery(this).next().toggle('slow');
		jQuery(this).css({MozBorderRadius: '3px', 
						  WebkitBorderRadius: '3px',
						  borderRadius: '3px'});
		return false;
	})
	
	
	/**
	 * Notification
	**/
	jQuery('.notification .close').click(function(){
		jQuery(this).parent().fadeOut();
	});	
	
	
	/** Make footer always at the bottom**/
	if(jQuery('body').height() > jQuery(window).height()) {
		jQuery('.footer').removeClass('footer_float');
	}
	
	
	
	/**DROP DOWN MENU**/
	jQuery(".subnav").css({display: "none"}); // Opera Fix
	jQuery(".tabmenu li").hover(function(){
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
	},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
	});


	jQuery("[data-toggle='offcanvas']").click(function($){
		jQuery("body").toggleClass("sidebar-collapse");
	});
	jQuery("[data-widget='collapse']").click(function(){
		var parent_collapse = jQuery(this).attr("data-box");
		if(parent_collapse.length){
			jQuery(parent_collapse).toggleClass("collapsed").find(".box-body").slideToggle("medium");			
		}
	});
	
});