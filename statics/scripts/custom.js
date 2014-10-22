$(document).ready(function(){
						   
						   


/*************SUB MENU SCRIPT****************/
/*************SUB MENU SCRIPT****************/

		
		$("#main-nav li ul").hide(); // Hide all sub menus
		$("#main-nav li a.current").parent().find("ul").show(); // Slide down the current menu item's sub menu
		
		$("#main-nav li a.nav-top-item").click( 
			function () {
				//$(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				$(this).toggleClass('arrow');
				return false;
			}
		);
		
		$("#main-nav li a.no-submenu").click( 
			function () {
				window.location.href=(this.href); // Just open the link instead of a sub menu
				return false;
			}
		); 
		



/*************submenu-footer-tooltip****************/
/*************submenu-footer-tooltip****************/

     
	  $(".submenu-footer .icons li a#search").click(function(){	  
		  $(".search_bar").toggle();
	  });	  
	  
	  $(".submenu-footer .icons li a#search").blur(function(){	  
		  $(".search_bar").hide();
	  });
	  
	  
	  $(".submenu-footer .icons li a#messages").click(function(){ 
		  $(".messages_list").toggle();  
	  });
	  

      $(".submenu-footer .icons li a#messages").blur(function(){ 
		  $(".messages_list").hide();  
	  });
	  
	  

	
/*************SLIDE CONTENT BOX SCRIPT****************/
/*************SLIDE CONTENT BOX SCRIPT****************/	
	
	
		
		$(".closed-box .box-content").hide(); // Hide the content of the header if it has the class "closed"
		$(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"
		
		$(".box-header .minimize").click( 
			function () {
			  $(this).toggleClass("icon-plus-sign");
			  $(this).parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
			  $(this).parent().parent().find(".box-content").slideToggle("fast"); // Toggle the tabs
			}
		);
		
		




/*************CLOSE BOX SCRIPT****************/
/*************CLOSE BOX SCRIPT****************/	

	
	   $(".close").click(
			function () {
				$(this).parent().parent().fadeTo(400, 0, function () {
					$(this).hide(400);
				});
				return false;
			}
		);
	
	
	
       
	   $(".close_notification").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () {
					$(this).hide(400);
				});
				return false;
			}
		);




/*************DRAG DROP BOX SCRIPT****************/
/*************DRAG DROP BOX SCRIPT****************/	

		
        $('.shortable-content').sortable({
		connectWith: '.shortable-content',
		handle: '.box-header',
		cursor: 'move',
		revert: '400',    //Revert speed
		/*axis: "y",*/    //Select axis "X" or "Y"
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,
		stop: function(event, ui){
			$(ui.item).find('.box-header').click();
			var sortorder='';
			$('.shortable-content').each(function(){
				var itemorder=$(this).sortable('toArray');
				var columnId=$(this).attr('id');
				sortorder+=columnId+'='+itemorder.toString()+'&';
			});
			/*alert('SortOrder: '+sortorder);*/
			/*Pass sortorder variable to server using ajax to save state*/
		}
	})
		
		
		
		
		
/*************FULL CALENDAR****************/
/*************FULL CALENDAR****************/		
	
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		events: [
			
			{
				title: 'Long Event',
				start: new Date(y, m, d-5),
				end: new Date(y, m, d-2)
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d+4, 16, 0),
				allDay: false
			},
			{
				title: '生日快乐',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false
			},
			{
				title: '点击进入谷歌',
				start: new Date(y, m, 28),
				end: new Date(y, m, 29),
				url: 'http://google.com/'
			}
		]
	});	
	
	
	
	
	
	
	
//===== Sortable columns =====//
	
	$("table").tablesorter();
	
	
	//===== Resizable columns =====//
	
	$("#resize, #resize2").colResizable({
		liveDrag:true,
		draggingClass:"dragging" 
	});
	
	
//===== Dynamic data table =====//
	
	oTable = $('.dTable').dataTable({
		"bJQueryUI": false,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		"sDom": '<"H"fl>t<"F"ip>'
	});
	
	
  
	
	
	
	
//===== INPUT MASK =====//  
//===== INPUT MASK =====//


    $.mask.definitions['~'] = "[+-]";
	$("#maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$("#maskPhone").mask("(999) 999-9999");
	$("#maskPhoneExt").mask("(999) 999-9999? x99999");
	$("#maskIntPhone").mask("+33 999 999 999");
	$("#maskTin").mask("99-9999999");
	$("#maskSsn").mask("999-99-9999");
	$("#maskProd").mask("a*-999-a999", { placeholder: " " });
	$("#maskEye").mask("~9.99 ~9.99 999");
	$("#maskPo").mask("PO: aaa-999-***");
	$("#maskPct").mask("99%");
	
	
	
	
	

//===== Placeholder =====//
	
	$('input[placeholder], textarea[placeholder]').placeholder();
	
	
	
	
	
	
//===== Autogrowing textarea =====//
	
	$('.auto').elastic();
	$('.auto').trigger('update');
	
	
	
	
	
//===== Autotabs. Inline data rows =====//

	$('.onlyNums input').autotab_magic().autotab_filter('numeric');
	$('.onlyText input').autotab_magic().autotab_filter('text');
	$('.onlyAlpha input').autotab_magic().autotab_filter('alpha');
	$('.onlyRegex input').autotab_magic().autotab_filter({ format: 'custom', pattern: '[^0-9\.]' });
	$('.allUpper input').autotab_magic().autotab_filter({ format: 'alphanumeric', uppercase: true });
	
	
	
	
	


	

	

//===== ANIMATED POPUP ALERTS =====//  
//===== ANIMATED POPUP ALERTS =====//




$('#simple-alert').click(function(ev) {

    $.msgbox("jQuery is a fast and concise JavaScript Library that simplifies HTML document traversing, event handling, animating, and Ajax interactions for rapid web development.", {type: "info"});

});




$('#attention-alert').click(function(ev) {

   $.msgbox("The selection includes process white objects. Overprinting such objects is only useful in combination with transparency effects.");

});




$('#error').click(function(ev) {

    $.msgbox("An error 1053 ocurred while perfoming this service operation on the MySql Server service.", {type: "error"});

});


$('#confirm').click(function(ev) {

   $.msgbox("Are you sure that you want to permanently delete the selected element?", {
	type: "confirm",
    
	buttons : [
        {type: "submit", value: "Yes"},{type: "submit", value: "No"},
		{type: "cancel", value: "Cancel"}]},
	
	function(result) { $("#result2").text(result); });

});




$('#simple_forms').click(function(ev) {

  $.msgbox("<p>In order to process your request you must provide the following:</p>", {
    type    : "prompt",
    name    : "lock",
    inputs  : [
      {type: "text",     name: "username", value: "", label: "Username:", required: true},
      {type: "password", name: "password", value: "", label: "Password:", required: true}
    ],
    buttons : [
      {type: "submit", name: "submit", value: "Sign In"},
      {type: "cancel", value: "Cancel"}
    ],
    form : {
      active: true,
      method: 'post',
      action: 'index.html'
    }
  });
  
  ev.preventDefault();

});



$("#form_with_confirm").click(function() {
									   
  $.msgbox("<p>In order to process your request you must provide the following:</p>", {
    type    : "prompt",
    inputs  : [
      {type: "text",     label: "Insert your Name:", value: "", required: true},
      {type: "password", label: "Insert your Password:", value: "", required: true}
    ],
    buttons : [
      {type: "submit", value: "OK"},
      {type: "cancel", value: "Exit"}
    ]
  }, function(name, password) {
    if (name) {
      $.msgbox("Hello <strong>"+name+"</strong>, your password is <strong>"+password+"</strong>.", {type: "info"});
    } else {
      $.msgbox("Bye!", {type: "info"});
    }
  });
  
});









//===== Sticky NOTES PURRRR =====//  
//===== STIKY NOTES PURRRR =====//

$( '.stiky-auto-hide' ).click( function () 
	{
	 var notice = '<div class="notice">'
	  + '<div class="notice-body">' 
	  + '<img src="images/purrr/info2.png" alt="" />'
	  + '<h3>Auto Hide Stiky Note</h3>'
	  + '<p>This Message will disappear after few seconds</p>'
	  + '</div>'
	  + '<div class="notice-bottom">'
	  + '</div>'
	  + '</div>';
							  
	  $( notice ).purr(
	   {
		 usingTransparentPNG: true
	   }
	   );
						
		return false;
		}
		);
 
 
 
				
$( '.stiky-fixed' ).click( function () 
	{
	  var notice = '<div class="notice">'
	  + '<div class="notice-body">' 
	  + '<img src="images/purrr/info2.png" alt="" />'
	  + '<h3>"Sticky" Purr Example</h3>'
	  + '<p>This Message is fixed, press x to close.</p>'
	  + '</div>'
	  + '<div class="notice-bottom">'
	  + '</div>'
	  + '</div>';
							  
	  $( notice ).purr(
	      {
		    usingTransparentPNG: true,
		    isSticky: true
		  }
		);
						
		return false;
	}
	);




//===== TOP FIXED NOTIFICATION =====//  
//===== TOP FIXED NOTIFICATION =====//


var myMessages = ['info-top','warning-top','error-top','success-top']; // define the messages types		 
	function hideAllMessages()
	{
			 var messagesHeights = new Array(); // this array will store height for each
		 
			 for (i=0; i<myMessages.length; i++)
			 {
					  messagesHeights[i] = $('.' + myMessages[i]).outerHeight();
					  $('.' + myMessages[i]).css('top', -messagesHeights[i]); //move element outside viewport	  
			 }
	}
	
	function showMessage(type)
	{
		$('.'+ type +'-trigger').click(function(){
			  hideAllMessages();				  
			  $('.'+type).animate({top:"0"}, 500);
		});
	}


		 
	 // Initially, hide them all
	 hideAllMessages();
	 
	 // Show message
	 for(var i=0;i<myMessages.length;i++)
	 {
		showMessage(myMessages[i]);
	 }
	 
	 // When message is clicked, hide it
	 $('.message-top').click(function(){			  
			  $(this).animate({top: -$(this).outerHeight()}, 500);
	  });








//===== MODAL BOXES =====//  
//===== MODAL BOXES =====//


$('.basic-modal').click(function (e) {
		$('#basic-modal-content').modal();
		return false;
	});


$('.scrolling-modal').click(function (e) {
		$('#basic-modal-content2').modal();
		return false;
	});






/*************Cotact List****************/
/*************Contact List****************/

$('#contact_list').sliderNav();
$('#transformers').sliderNav({items:['autobots','decepticons'], debug: true, height: '300', arrows: false});








/*************plupload_1_5_4****************/
/*************plupload_1_5_4****************/	



			
			var f = $('#finder').elfinder({
				url : 'connectors/php/connector.php',
				lang : 'en',
				docked : true

				// dialog : {
				// 	title : 'File manager',
				// 	height : 500
				// }

				// Callback example
				//editorCallback : function(url) {
				//	if (window.console && window.console.log) {
				//		window.console.log(url);
				//	} else {
				//		alert(url);
				//	}
				//},
				//closeOnEditorCallback : true
			})
			// window.console.log(f)
			$('#close,#open,#dock,#undock').click(function() {
				$('#finder').elfinder($(this).attr('id'));
			});
			
		
	
	
	
	
	



/*************Step by Step Wizard****************/
/*************Step by Step Wizard****************/


      $(".steps ul li:first").addClass("current").show(); //Activate first tab
	  $(".wiz_page").hide(); //Hide all content
	  $(".box-content .wiz_page:first").show(); //Show first tab content
	  //On Click Event
	  $(".steps ul li").click(function() {
		  $(".steps ul li").removeClass("current"); //Remove any "current" class
		  $(this).addClass("current"); //Add "current" class to selected tab
		  $(".wiz_page").hide(); //Hide all tab content
		  var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		  $(activeTab).fadeIn(); //Fade in the active content
	  	  return false;
	  });
	  
	  
	  




/*************Wysiwyg Editor****************/
/*************Wysiwyg Editor****************/

	  
		$(".wysiwyg").wysiwyg();
		
		
		
		
		
		
		
/*************Date and Time Picker****************/
/*************Date and Time Picker****************/		
		
		
		$( "#datepicker" ).datepicker({
			showButtonPanel: true
		});
		
		
		



/*************Step by Step Wizard****************/
/*************Step by Step Wizard****************/


    $('a.modal').hover(
		function(){
			$(this).fancybox({
		                      'overlayOpacity':	0.7,
		                      'overlayColor'	:	'#000',
		                      'padding'		:	0
	                        });
        }
	);


	  // Image hover actions menu
	$('ul.imglist li').hover(
		function() { $(this).find('ul').css('display', 'none').stop(true, true).fadeIn('fast').css('display', 'block'); },
		function() { $(this).find('ul').stop(true, true).fadeOut(100); }
	);
	
		
	// Image delete confirmation
	$('ul.imglist .delete a').click(function() {
		if (confirm('Are you sure you want to delete this image?')) {
		
			// Make AJAX call to delete
						
			$(this).parents('li').fadeOut('slow', function() {
				$(this).remove();
			});
		}
		return false;
	});
	
	
	
	
	
	
	
/*************Color Picker****************/
/*************Color Picker****************/

	$(".color-picker").miniColors({
					letterCase: 'uppercase',
					change: function(hex, rgb) {
						logData(hex, rgb);
					}
				});
	
	


/*************Accordion****************/
/*************Accordion****************/
	
	$("#accordion").accordion();
	




/*************Tabs****************/
/*************Tabs****************/

    $( "#tabs" ).tabs();
	
	$( "#tabs_hover" ).tabs({event: "mouseover"});
	






/*************Forms Validation****************/
/*************Forms Validation****************/


   jQuery("#formID").validationEngine();

	
	
   			
	  
	  
	  
	  
/*************Full Keyboard****************/
/*************Full Keyboard****************/	  
	  
	  
	$('#keyboard').keyboard({
				alwaysOpen: true
	    })  
	
	
	
	


/*************Login Page****************/
/*************Login Page****************/
			 
		 
     $("#login_slider").draggable({
		axis: 'x',
		containment: 'parent',
		drag: function(event, ui) {alert('123123123');
			//if (ui.position.left > 380) {
				$(".login_warper").fadeOut();
                                var formdata = {
                                    'username' : $('#username').val(),
                                    'password' : $('#password').val()
                                };
                                $.get('http://www.feitm.com/admin.php?c=FeiTm&a=ajax_login',formdata,function(result){
                                    alert('123');
                                });
				//window.location = "http://www.infynitix.com/esthetics_admin/dashboard.html"
			//} else {
			    // Apparently Safari isn't allowing partial opacity on text with background clip? Not sure.
				// $("h2 span").css("opacity", 100 - (ui.position.left / 5))
			//}
		},
		stop: function(event, ui) {
			if (ui.position.left < 380) {
				$(this).animate({
					left: 0
				})
			}
		}
	});
	
	// The following credit: http://www.evanblack.com/blog/touch-slide-to-unlock/
	
	


	  
		


});/*END*/