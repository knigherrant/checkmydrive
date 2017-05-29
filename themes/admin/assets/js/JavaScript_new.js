$(function(){
jQuery('.tabmenu').before('<div class="mobile_menu"><div class="mobile_menungang"><div class="tieude fa"></div><div class="noidung"></div><div class="nentat"></div></div>');
jQuery('.mobile_menungang .noidung').html(jQuery('.tabmenu').html());
jQuery('.nentat').click(function(){
	jQuery('.inshow').removeClass('inshow');
	jQuery('.inactive').removeClass('inactive');
});
jQuery('.tieude').click(function(){
	jQuery(this).next().addClass('inshow');
	jQuery('.nentat').addClass('inactive');
});
});
