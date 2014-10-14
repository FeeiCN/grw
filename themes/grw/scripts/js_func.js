$(function() {
	var myName = $('#name');
	myName.focus(function() { if ($(this).val() == 'NAME ...') {$(this).val('');} });
	myName.blur(function() { if ($(this).val() == '') {$(this).val('NAME ...');} });		
	var myEmail = $('#email');
	myEmail.focus(function() { if ($(this).val() == 'EMAIL ...') {$(this).val('');} });
	myEmail.blur(function() { if ($(this).val() == '') {$(this).val('EMAIL ...');} });			
	$('.top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});
	$('.header nav ul ul li a').append('<span>&nbsp;</span>');
	$('.featured_list li a').append('<span class="zoom">&nbsp;</span>');
	
	$('#tabs div').hide();
	$('#tabs div:first').show();
	$('#tabs ul li:first').addClass('active');
	$('#tabs ul li a').click(function(){
		$('#tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
		var currentTab = $(this).attr('href');
		$('#tabs div').hide();
		$(currentTab).show();
		return false;
	});
	$('#h_tabs div').hide();
	$('#h_tabs div:first').show();
	$('#h_tabs ul li:first').addClass('active');
	$('#h_tabs ul li a').click(function(){
		$('#h_tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
		var currentTab2 = $(this).attr('href');
		$('#h_tabs div').hide();
		$(currentTab2).show();
		return false;
	});
	$('#s_tabs div').hide();
	$('#s_tabs div:first').show();
	$('#s_tabs ul li:first').addClass('active');
	$('#s_tabs ul li a').click(function(){
		$('#s_tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
		var currentTab3 = $(this).attr('href');
		$('#s_tabs div').hide();
		$(currentTab3).show();
		return false;
	});
	$('.top_title, .text_bar2').prepend('<div class="text_bar_shadow"></div>').append('<div class="text_bar_shadow2"></div>');
	$('.features_block ul li, .features2_block ul li, .bc_list ul li, .features5_block ul li, .services_option4 ul li, .small_icons ul li, .medium_icons ul li, .large_icons ul li').prepend('<span class="circle"></span>');
	$('.faq_list .filter li').on('click', function(){
		$('.faq_list .filter li').removeClass('active');
		$(this).addClass('active');
	});
	$('.faq_list .filter .all').on('click', function(){
		$('#faq .all').show();
	});
	$('.faq_list .filter .business').on('click', function(){
		$('#faq .technical').hide();
		$('#faq .miscellaneous').hide();
		$('#faq .business').show();
	});
	$('.faq_list .filter .technical').on('click', function(){
		$('#faq .business').hide();
		$('#faq .miscellaneous').hide();
		$('#faq .technical').show();
	});
	$('.faq_list .filter .miscellaneous').on('click', function(){
		$('#faq .business').hide();
		$('#faq .technical').hide();
		$('#faq .miscellaneous').show();
	});
}); 