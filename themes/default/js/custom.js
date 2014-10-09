// JavaScript Document for softone

//Cufon font replacement class/id
Cufon.replace('h1, h2, h3, h4, h5, h6, #pricing table h2');

$(document).ready(function() {


	
// Superfish menu (main navigation)
$("ul.sf-menu").supersubs({ 
        minWidth:    7,   // minimum width of sub-menus in em units 
        maxWidth:    25,   // maximum width of sub-menus in em units 
        extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                               // due to slight rounding differences and font-family 
        }).superfish();  // call supersubs first, then superfish, so that subs are 
                         // not display:none when measuring. Call before initialising 
                         // containing tabs for same reason. 
$('ul.sf-menu').superfish(); 
	

//Thumbnail hover effect for gallery
$('.thumb').hover(function(){  
		 $(this).find(".first_icon").animate({left: '80px'}, 300);
	     $(this).find(".second_icon").animate({left: '115px'}, 500);
		 }  
		 , function(){  
		 $(this).find(".first_icon").animate({left: '-50px'}, 300);
		 $(this).find(".second_icon").animate({left: '-50px'}, 500);
});
			   
//Signin / login form
$(".login").click(function(e) {
       e.preventDefault();
       $("#login_form").toggle();
       $(".login").toggleClass("menu-open");
       });
       $("#login_form, .login").mouseup(function() {
       return false
       });
       $(document).mouseup(function(e) {
       if($(e.target).parent("a.login").length==0) {
       $(".login").removeClass("menu-open");
       $("#login_form").hide();
       }
       });            

//On Hover animation for images
$('ul.social li img, ul.payment li img').hover(function(){
	   $(this).animate({opacity: 0.6}, 300);
	   }, function () {
	   $(this).animate({opacity: 1}, 300);
	   });

//Cycle plugin for testimonial
$('.testimonial_style1').cycle({
	   fx:'fade',
	   speed:  1500,
	   pause:  1,
	   next:   '.next2', 
       prev:   '.prev2',
	   timeout: 1,//auto advance disabled, slideup only on click
	   cleartypeNoBg:   true,// set to true to disable extra cleartype fixing (leave false to force background color setting on slides)
       fastOnEvent:500,
       sync:true,
       before:function(curr, next, opts) { opts.animOut.opacity=0; opts.animIn.opacity=1; opts.cssBefore.opacity=0; }
});

$(function () {
	
// Accordion
$(".accordion_wrapper").accordion({ header: "h3", autoHeight: false, collapsible: true, active: 1});

//Tabs
$('.tabs_wrapper').tabs();

});
	
}); //close document.ready


