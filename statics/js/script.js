"use strict";

// ### Here all page JS is initialized (plugins, popups, ...)
(function($, mango, undefined){

	// Cache jQuery selectors
	var $main = $('#main'),
		$toolbar = $('#toolbar'),
		$sidebar = $('aside');
		
	// ---------------------------------------------
	// ! Hide Address Bar on iOS & Android
	// - @see: http://24ways.org/2011/raising-the-bar-on-mobile
	/*
	 * Normalized hide address bar for iOS & Android
	 * (c) Scott Jehl, scottjehl.com
	 * MIT License
	 */
	 (function (win) {
		var doc = win.document;

		// If we don't have a touch device, there's a hash, or addEventListener is undefined, stop here
		if (Modernizr.touch && !location.hash && win.addEventListener) {

			//scroll to 1
			window.scrollTo(0, 1);
			var scrollTop = 1,
				getScrollTop = function () {
					return win.pageYOffset || doc.compatMode === "CSS1Compat" && doc.documentElement.scrollTop || doc.body.scrollTop || 0;
				},

				//reset to 0 on bodyready, if needed
				bodycheck = setInterval(function () {
					if (doc.body) {
						clearInterval(bodycheck);
						scrollTop = getScrollTop();
						win.scrollTo(0, scrollTop === 1 ? 0 : 1);
					}
				}, 15);

			win.addEventListener("load", function () {
				setTimeout(function () {
					//at load, if user hasn't scrolled more than 20 or so...
					if (getScrollTop() < 20) {
						//reset to hide addr bar at onload
						win.scrollTo(0, scrollTop === 1 ? 0 : 1);
					}
				}, 0);
			});
		}
	})(window);
	
	// ---------------------------------------------
	// ! Initialize App Cache
	// @see: http://www.html5rocks.com/en/tutorials/appcache/beginner/
	
	if ($('html').attr('manifest')) {
	
		// Check if a new cache is available on page load.
		window.addEventListener('load', function (e) {
		
			try {
				var appCache = window.applicationCache;

				appCache.update(); // Attempt to update the user's cache.

				if (appCache.status == appCache.UPDATEREADY) {
				  appCache.swapCache();  // The fetch was successful, swap in the new cache.
				}
				
				appCache.addEventListener('updateready', function (e) {
					if (window.applicationCache.status == window.applicationCache.UPDATEREADY) {
						// Browser downloaded a new app cache.
						// Swap it in and reload the page to get the new hotness.
						console.info('Updating Application Cache :)');
						window.applicationCache.swapCache();
						
						if ($.jGrowl) {
							$.jGrowl(mango.config.lang.appcache.PLEASE_RELOAD, {header:mango.config.lang.appcache.PLEASE_RELOAD_TITLE});
						} else if (confirm(mango.config.lang.appcache.PROMT_RELOAD)) {
							window.location.reload();
						}
					} else {
						// Manifest didn't changed. Nothing new to server.
					}
				}, false);
			} catch(e){
				console.error(e);
			}

		}, false);
	
	}
	
	// ---------------------------------------------
	// ! Do several things on $(document).ready(...)
	
	
	// ! Update cached elements
	mango.loaded(function(){
		$main = $('#main');
		$toolbar = $('#toolbar');
		$sidebar = $('aside');
	});
	
	
	// ! Browser fixes
	mango.loaded(function(){
		
	
		$toolbar.find('div.right').find('a').has('span:not(.icon)').addClass('with_red');
		
		// - Webkit/Mozilla ftw
		if ($.browser.mozilla) {
			$('html').addClass('moz');
		} else if ($.browser.webkit) {
			$('html').addClass('webkit');
		}
		
		// - Bad IE
		var ie = !!$.browser.msie,
			ieV = parseInt($.browser.version);
			
		if (ie) {
			$('html').addClass('ie');
			ieV == 9 && $('html').addClass('ie9');
		}
		
	});
	
	
	// ! Set up content
	mango.loaded(function(){
		
		var $content = $('#content');
	
		// - Wrap the 'h1's contents with 'span's
		$('h1').each(function(){
			var $this = $(this);
			$this.wrapInner('<span />');
		});

		// - Initialize Boxes Menus
		$content.find('.box:has(.header a.menu)').each(function(){
			var $box = $(this),
				$btn = $box.find('.header').find('a.menu'),
				$menu = $btn.next('menu');
				
			$btn.on({
				mousedown: function(){
					$(this).addClass('active');
				},
				mouseup: function(){
					$(this).removeClass('active');
				},
				click: function(){
					$menu.fadeToggle(mango.config.fxSpeed);
					$btn.toggleClass('open');
				}
			});
				
			$menu.find('a').on({
				mousedown: function(){
					$(this).addClass('active');
				},
				mouseup: function(){
					$(this).removeClass('active');
				},
				click: function(){
					window.location = this.href;
					return false;
				},
				dragstart: function(){
					return false;
				}
			}).filter(':has(.icon)').addClass('with-icon');
		});
	
		// - Initialize sortable boxes
		if ($content.data('sort') && !(Modernizr.touch && !mango.settings.contents.sortableOnTouchDevices)) {			
			$content.sortable({
				handle: '.header',
				items: $content.find('.box').parent(),
								
				distance: 5,
				tolerance: 'pointer',
				
				placeholder: 'placeholder',
				forcePlaceholderSize: true,
				forceHelperSize: true
			});
		}
		
		// - Create accordions
		$('#content .accordion').not('.toggle').each(function(){
			$(this).accordion();
		});
		
		$('#content .accordion.toggle').each(function(){
			$(this).multiAccordion();
		});
		
		// - Create tabbed boxes
		$('#content .tabbedBox').tabbedBox();
		
		// - Create vertical tabs
		$('#content .vertical-tabs').tabbedBox({header: $('.right-sidebar'), content: $('.vertical-tabs')});
		
		// - Create wizard boxes
		$('#content .wizard').not('.manual').wizard();
		
		// - Initialize alert boxes
		$('.alert').not('.sticky').find('.icon')
			.after($('<span>').addClass('close').text('x'));
			
		$(document).on('click', '.alert:not(.sticky) .close', function(){
				$(this).parent().slideUp(mango.config.fxSpeed);
		});

		// - Resize and scroll event handling
		$(window).on('resize scroll', function(){
			// Center dialogs
			$('.ui-dialog').position({my: 'center', at: 'center', of: window});
		});
		
	});
	
	// ! Phone Navigation
	mango.loaded(function(){
		var $navi = $('nav').clone();
		$navi
			.addClass('phone')
			.children('ul').removeClass('collapsible accordion').end()
			.find('.badge').remove().end()
			.find('.icon').remove().end()
			.find('img').remove().end()
			.insertAfter('header');
			
		// The navigation menu
		var $level1 = $navi.children('ul').children('li').has('ul').children('a');
		$level1.addClass('with-sub');
		$level1.click(function(){
			var $item = $(this),
				$sub = $item.next();
				
			if ($sub.is('ul')) {
				// Slide up
				if ($sub.is(':visible')) {
					$sub.slideUp(mango.config.fxSpeed, function(){
						$item.parent().toggleClass('open');
					});
				// Slide down
				} else {
					$level1.next().not($sub).slideUp(mango.config.fxSpeed);
					$sub.slideDown(mango.config.fxSpeed);
					$item.parent().toggleClass('open');
				}
				return false;
			}
		});
		
		// - Open/close the menu
		$('#toolbar').find('.phone').find('.navigation').click(function(){
			$navi.fadeToggle(mango.config.fxSpeed);
		});
	});
	
	// ! High Density Mobile Logo
	mango.loaded(function(){
		if (window.devicePixelRatio && window.devicePixelRatio > 1) {
			var $img = $('.phone-title');
			if (!$img.is('img')) {
				$img = $img.find('img');
			}
			
			var src = $img[0].src;
			$img.error(function(){
				$img.attr('src', src);
			});
			
			$img.attr('src', src.replace('.png', '@2x.png'));
		}
	});
	
	// ! Set up sidebar
	mango.loaded(function(){
	
		var $menu = $sidebar.find('nav').children('ul');
		
		// - Initialize the menu
		$menu.initMenu();
		$menu.find('li').find('ul').find('li').has('.icon').addClass('with-icon');
	
		// - Progress bars in the sidebar
		var $top = $sidebar.find('.top'),
			$bottom = $sidebar.find('.bottom'),
			
			$progress = $('aside').find('div.progress'),
			
			$footer = $('footer'),
			$window = $(window);
		
		$progress.children().infobar();
		// - Give the sidebar a min-height
        var sheight = $sidebar.find('.top').height() + $sidebar.find('.bottom').height();
        var main_height = document.documentElement.clientHeight - $(".toolbar").height();
		$sidebar.css('height',main_height);
//        console.log(screen.width + '*' + screen.height + '|' + screen.availWidth + '*' + screen.availHeight)
        $("#content").css({
//            width:$(".toolbar").width() - 299 - 199,
            height:main_height,
            background:'none',
            overflow:'auto',
            'padding':'0px'
        });

//		console.log($sidebar.find('.top').height() + '-' + $sidebar.find('.bottom').height())
		// - Sticky bottom area
		if ($bottom.hasClass('sticky')) {
			
			var reset = function(){
				$bottom.css({
					position: 'absolute',
					left: 0,
					top: 'auto'
				});
			};
			
			var update = function(){
				var windowOffsetBottom = $window.scrollTop() + $window.height();
				console.log(windowOffsetBottom)
				reset();
				
				if (windowOffsetBottom + 4 < $footer.offset().top) {
					$bottom.css({
						position: 'fixed',
						left: $bottom.offset().left
					});
				}
							
				var sidebarOffsetBottom = $top.offset().top + $top.outerHeight();
				if ($bottom.offset().top - 1 <= sidebarOffsetBottom) {
					$bottom.css({
						top: sidebarOffsetBottom - $main.offset().top,
						left: 0,
						position: 'absolute'
					});
				}
			};
			
			update();

			$window.bind('scroll resize', update);
		}
		
	}); // End of 'mango.loaded'
	
	
	// ! jQuery UI elements
	mango.loaded(function(){
		
		var revalidateInput = function(){
			if ($.validator) {
				var $el = $(this),
					$form = $el.parents('form'),
					validator = $form.data('validator');
					
				if (validator) {
					validator.element(this);
				}
			}
		};
	
		// - Dialog
		$.extend($.ui.dialog.prototype.options, {
			minWidth: 350,
			resizable: false,
			autoOpen:false,
			
			show: {effect: 'fade', duration: 800},
			hide: {effect: 'fade', duration: 800}
		});
		
		// - Progressbar
		$('#content').find('.ui-progressbar').each(function(){
			var $this = $(this);
			$this.progressbar($this.data());
		});
		
		// - Datepicker
		// FIX: Wrong positioning of datepicker
		//      See: http://bit.ly/mangoDPfix
		$.extend($.datepicker,{_checkOffset:function(inst,offset,isFixed){return offset}});
		
		$.extend($.datepicker._defaults, {
			showOtherMonths: true,
			firstDay: 1, 
			showButtonPanel:true,//是否显示按钮面板
			showMonthAfterYear:true,//是否把月放在年的后面
			isRTL: false,
			clearText:"清除",//清除日期的按钮名称
			prevText: '<上月',  
	        nextText: '下月>',
	        monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],  
	        monthNamesShort: ['一','二','三','四','五','六','七','八','九','十','十一','十二'],  
	        dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],  
	        dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],  
	        dayNamesMin: ['日','一','二','三','四','五','六'], 
			yearSuffix: '年', //年的后缀 
	        weekHeader: '周', 
	        changeMonth: false,
	        changeYear: true,
			dateFormat: 'yy-mm-dd',//日期格式,
            currentText: "现在",
            closeText: "确定",
            timeOnlyTitle: '选择日期',
            timeText: '时间',
            hourText: '时',
            minuteText: '分',
            secondText: '秒',
            millisecText: '毫秒',
            timezoneText: '时区',
            defaultDate: new Date()
		});
		
		var datepickerEvents = {
			onSelect: revalidateInput,
			onClose: revalidateInput
		};
		
		$.extend($.datepicker._defaults, datepickerEvents);
		$.extend($.timepicker._defaults, datepickerEvents);
		
		// Optional: Localization
		
		$('input[type=date]').each(function(){
			var $el = $(this);
			if ($.browser.webkit) {
				$el[0].type = 'text';
			}
			$el.datepicker();
		});
		$('input[type=datetime]').each(function(){
			$(this).datetimepicker($.datepicker._defaults).blur(revalidateInput);
		});
		$('input[type=time]').each(function(){
			$(this).timepicker({
				ampm: $(this).data('data-timeformat') == 12
			}).blur(revalidateInput);
		});
		
		// FIX: Bug with Datepicker header
		mango.ready(function(){
			setTimeout(function(){$('.hasDatepicker').datepicker('refresh')}, 3000);
		});
		
		// Create mirror input for inline datepicker
		var inline = {
		
			// Write date to mirror
			onselect: _.debounce(function(date, inst){
				(inst.input || inst.$input).data('mirror').val(date);
			}, 300),
			
			// Create mirror
			setup: function($el){
				var $mirror = $('<input>', {
					id: $el.data('id'),
					'class': 'mirror',
					name: $el.data('name'),
					required: $el.attr('required') || 'false'
				}).hide().insertAfter($el);
				$el.data('mirror', $mirror);
			}
		};
		
		$('div[data-type=date]').each(function(){
			var $this = $(this);
			inline.setup($this);
			$this.datepicker({
				onSelect: inline.onselect
			});
		});
		$('div[data-type=datetime]').each(function(){
			var $this = $(this);
			inline.setup($this);
			$this.datetimepicker({
				onSelect: inline.onselect
			});
		});
		$('div[data-type=time]').each(function(){
			var $this = $(this);
			inline.setup($this);
			$this.timepicker({
				onSelect: inline.onselect,
				ampm: $(this).data('data-timeformat') == 12
			});
		});
		
		// - Slider
		$('input[data-type=range]').mslider();
		
		(function(){
			var $slider = $('input.eq[data-type=range]').next();
			var zindex = $slider.length + 1;
			$slider.each(function(){
				$(this).css('z-index', zindex--);
			});
		})();
		
		// - Autocomplete
		$('[data-type=autocomplete]').each(function(){
		
			var $input = $(this);
			$input.attr('autocomplete', 'off');
			$input.autocomplete({
				source: $input.data('data') || $input.data('source'),
				disabled: !!$input.attr('disabled'),
				minLength: $input.data('minlength') || 1,
				position: {
					my: 'top',
					at: 'bottom',
					offset: '0 10',
					collision: 'none'
				},
				
				select: revalidateInput
			});
			
		});
		
		// Reposition autocomplete after window resize
		$(window).resize( _.debounce(function(){
			$('[data-type=autocomplete]').each(function(){
				var $this = $(this),
					$menu = $this.data('autocomplete').menu.element;
					
				$menu
					.width($this.outerWidth())
					.position({
						my: 'top',
						at: 'bottom',
						offset: '0 10',
						collision: 'none',
						of: $this
					});
			});
		}, 300));
		
	});
	
	
	// ! Calendar
	mango.loaded(function(){
		
		$.fullCalendar.setDefaults({
			header: {
					left: 'prev,next',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
			}
		});
	
	});
	
	
	// ! Charts
	mango.loaded(function(){
		$('.chart').not('.manual').chart();
	});
	

	// ! Fullstats
	mango.loaded(function(){
		
		// ! Set up fullstats
		$('.full-stats').fullstats();
		
		// - Optional: Set equal hight for all stats
		$('.full-stats.equalHeight').equalHeight();
	
	});
	
	// ! Table
	mango.loaded(function(){
		
		// Add with-table class to boxes with tables to remove paddings
		$('.box').each(function(){
			var $box = $(this);
			if ($box.children('.content').children('table').length) {
				$box.addClass('with-table');
			}
		});
		
		// Initialize DataTables for dynamic tables
		$('table.dynamic').table();
	
	});
	
	// ! Gallery
	// mango.loaded(function(){
		
	// 	// Custom transition: fade in/out
	// 	// Adapted from: http://jsfiddle.net/6AYkT/3/
	// 	(function (F) {
	// 		F.transitions.fadeIn = function() {
	// 			var wrap     = F.wrap,
	// 				current  = F.current,
	// 				effect   = current.nextEffect,
	// 				elastic  = effect === 'elastic',
	// 				startPos = F._getPosition( elastic ),
	// 				endPos   = { opacity : 1 };

	// 			startPos.opacity = 0;

	// 			wrap.css(startPos)
	// 				.show()
	// 				.animate(endPos, {
	// 					duration : effect === 'none' ? 0 : current.nextSpeed,
	// 					easing   : current.nextEasing,
	// 					complete : F._afterZoomIn
	// 				});
	// 		};
		 
	// 		F.transitions.fadeOut = function() {
	// 			var wrap     = F.wrap,
	// 				current  = F.current,
	// 				effect   = current.prevEffect,
	// 				endPos   = { opacity : 0 },
	// 				cleanUp  = function () {
	// 					$(this).trigger('onReset').remove();
	// 				};

	// 			wrap.removeClass('fancybox-opened');

	// 			endPos.opacity = 0;

	// 			wrap.animate(endPos, {
	// 				duration : effect === 'none' ? 0 : current.prevSpeed,
	// 				easing   : current.prevEasing,
	// 				complete : cleanUp
	// 			});
	// 		};

	// 	}(jQuery.fancybox));
	
	// 	// Set up the gallery
	// 	$('.gallery').each(function(){
	// 		var $gallery = $(this);
						
	// 		$gallery.find('a:has(img)').attr('rel', _.uniqueId('gallery'));
			
	// 		$gallery.find('.image:has(menu)').each(function(){
	// 			var $box = $(this),
	// 				$btn = $box.find('a.menu'),
	// 				$menu = $btn.next('menu');
								
	// 			// Set up the menu
	// 			$menu.show().position({
	// 				my: 'left top',
	// 				at: 'left bottom',
	// 				of: $btn,
	// 				offset: '-3 3'
	// 			}).hide();
				
	// 			// Menu button listeners
	// 			$btn.on({
	// 				mousedown: function(){
	// 					$(this).addClass('active');
	// 				},
	// 				mouseup: function(){
	// 					$(this).removeClass('active');
	// 				},
	// 				click: function(){
	// 					$menu.fadeToggle(mango.config.fxSpeed);
	// 					$btn.toggleClass('open');
	// 				}
	// 			});
					
	// 			// Menu items listeners
	// 			$menu.find('a').on({
	// 				mousedown: function(){
	// 					$(this).addClass('active');
	// 				},
	// 				mouseup: function(){
	// 					$(this).removeClass('active');
	// 				},
	// 				click: function(){
	// 					window.location = this.href;
	// 					return false;
	// 				},
	// 				dragstart: function(){
	// 					return false;
	// 				}
	// 			}).filter(':has(.icon)').addClass('with-icon');
	// 		});
		
	// 	});
		
	// 	// Fancybox for gallery
	// 	$('.gallery a:has(img)').fancybox({
	// 			padding: 0,
				
	// 			nextMethod : 'fadeIn',
	// 			nextSpeed : 250,

	// 			prevMethod : 'fadeOut',
	// 			prevSpeed : 250
	// 		});
	
	// });
	
	// ! Forms
	mango.loaded(function(){
	
		// ! Checkbox and radio
		$('input:checkbox').checkbox({
			cls : 'checkbox',
			empty : 'img/elements/checkbox/empty.png'
		});
		$('input:radio').checkbox({
			cls : 'radiobutton',
			empty : 'img/elements/checkbox/empty.png'
		});
		
		// ! Select boxes
		var $cznSelects = $('select').not('.dualselects');
		$cznSelects.each(function(){
			var $el = $(this);
			
			$el.chosen({
				disable_search_threshold: $el.hasClass('search') ? 0 :Number.MAX_VALUE,
				allow_single_deselect: true,
				width: $el.data('width') || '100%'
			});
		});
				
		// - Set up select boxes validation
		$('.chzn-done').on('change', function(){
			var validate = $(this).parents('form').validate();
			validate && validate.element($(this));
		}).each(function(){
		// - Set up form reset listener
			var $input = $(this),
				$form = $input.parents('form');
			
			$form.on('reset', function(){
				$input[0].selectedIndex = -1;
				$input.trigger('liszt:updated');
			});
			
			$form.data('chzn-reset', true);
		});
		
		// ! Double Select Box
		if (!Modernizr.touch) {
			$('select.dualselects').dualselect();
		}
		
		// ! File input
		$('input:file').fileInput();
		
		// ! Uploader
		$('.uploader').each(function(){
			var $uploader = $(this);
			$uploader.pluploadQueue($.extend({
				runtimes: 'html5,flash,html4',
				url : 'extras/upload.php',
				max_file_size : '10mb',
				chunk_size : '1mb',
				unique_names : true,
				
				filters : [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "Zip files", extensions : "zip"}
				],
				
				flash_swf_url : 'js/mylibs/forms/uploader/plupload.flash.swf'
			}, $uploader.data()));
			$uploader.find('.plupload_button').addClass('button grey btn');
			$uploader.find('.plupload_add').addClass('icon-plus');
			$uploader.find('.plupload_start').addClass('icon-ok');
		});
		
		// ! Spinner
		$('input[data-type=spinner]').each(function(){
			var $spinner = $(this),
			opts = $spinner.data();
			
			if (opts.format) {
				opts.numberformat = opts.format;
				opts.format = undefined;
			}
			$spinner.spinner(opts);
		});
		
		// ! Color input
		$('input[type=color]').not('.flat').each(function(){
			var $input = $(this).hide(),
				$picker = $('<div class="cpicker"><div class="color"></div></div>').insertAfter($input),
				$color = $picker.children();
				
			// Update input val
			$input.val() ? $color.css('background', $input.val()) : $input.val('#ff0000');
			var origVal = $input.val();
			
			// Update preview and input val
			$picker.ColorPicker({
				onChange: function (hsb, hex, rgb) {
					$input.val('#' + hex);
					$color.css('background', '#' + hex);
				}
			});
			$picker.ColorPickerSetColor(origVal);
			
			// Reset input on form reset
			$input.parents('form').on('reset', function(){
				$input.val(origVal);
				$picker.ColorPickerSetColor(origVal);
				$color.css('background', origVal);
			});
		});
		
		$('input[type=color].flat').each(function(){
			var $input = $(this).hide(),
			$picker = $('<div>').insertAfter($input);
			
			// Update input val
			!$input.val() && $input.val('#ff0000');
			var origVal = $input.val();
			
			// Update preview and input val
			$picker.ColorPicker({
				flat: true,
				onChange: function (hsb, hex, rgb) {
					$input.val('#' + hex);
				}
			});
			$picker.ColorPickerSetColor(origVal);
			
			// Reset input on form reset
			$input.parents('form').on('reset', function(){
				$input.val(origVal);
				$picker.ColorPickerSetColor(origVal);
			});
		});
		
		// ! Editor
		$('textarea.editor').each(function(){
			var $input = $(this),
				isFull = $input.hasClass('full');
			$input.cleditor({			
				width: isFull ? 'auto' : '100%',
				height: '250px',
				bodyStyle: 'margin: 10px; font: 12px Arial,Verdana; cursor:text',
				useCSS: true
			});
			isFull && $input.parents('.cleditorMain').addClass('full');
		});
		
		// ! Forms
		// - In rows view: resize the labels
		var formResize = function(){
			$('#content,#login,.ui-dialog:not(:has(#settings))').find('form').each(function(){
				var $form = $(this);

				// Set up rows view
				// Let labels have equal width and same height as the corresponding <div>
				
				// - Clean up old values
				var $rows = $form.find('.row'),
					$label = $rows.children('label'),
					$divs = $rows.children('div');
				
				$label.css('width', '');
				$divs.css('height', '');
				$divs.css('margin-left', '');
				
				$label.equalWidth();
				$divs.css('margin-left', $label.width() + parseInt($label.css('margin-right')) );
				
				$label.each(function(){
					var $lbl = $(this),
						$div = $lbl.next();
					var heightLbl = $lbl.outerHeight(),
						heightDiv = $div.height();
						
					if (heightLbl > heightDiv) {
						$div.height(heightLbl);
					}
				});
				
				// Not Boxed
				if (!$form.parents('.box').length && !$form.is('.box')) {
					$form.addClass('no-box');
				}
				
				// Update pw meter
				$form.find(':password.meter').each(function(){
					$(this).data('reposition') && $(this).data('reposition')();
				})
			});
		};
		
		formResize();
		
		// Expose to public
		mango.utils.forms = {
			resize: formResize
		};
		
		// - Resize labels after webfont was loaded
		//   (otherwise crazy stuff could happen)
		$(window).on('fontsloaded', function(){
			formResize();
		});
		
		// - Resize labels when changing from desktop to mobile layout
		var windowWidth = $(window).width();
		$(window).on('resize', _.debounce(function(){
			formResize();
		}, 200));
		
		// ! Inline Labels
		var inlineLabelResize = function($input, $label){
			$input.css('padding-left', $label.outerWidth(true));
		};
		
		$('form').each(function(){
			var $form = $(this)
			  , $inlineLabels = $form.find('label.inline');
						  
			$inlineLabels.each(function(){
				var $label = $(this),
					$input = $('#' + $label.attr('for'));
				
				inlineLabelResize($input, $label);
				$(window).on('fontsloaded', function(){
					inlineLabelResize($input, $label);
				});
				
				var ie8 = ($.browser.msie && parseInt($.browser.version) == 8);
				
				if (ie8) {
					$label.css('position', 'absolute');
				}
				
				$label.position({
					my: 'left center',
					at: 'left center',
					of: $input,
					using: function(pos){
						$label.css('top', pos.top);
						if (ie8) {
							$label.css('top', pos.top * 2);
						}
					}
				});
			});
		});
	
	});
	
	// ! Explorer
	// mango.loaded(function(){
	// 	elFinder.prototype._options.resizable = false;
	// 	$('.explorer').each(function(){
	// 		var $el = $(this);
	// 		$el.elfinder({
	// 			url: $el.data('backend') || 'extras/explorer/'
	// 		});
	// 	});
	// });
	
	
	// - Demos
	mango.loaded(function(){
	
		// - Animated Progress Bar Demo
		mango.ready(function(){
			$('#animprog').progressbar({ fx: {
				animate: true,
				duration: 5,
				start: new Date(new Date().getTime() + 5 * 1000) // Now + 5s
			} });
		});
	
		// - Calendar Demo
		if ($('.calendar.demo').length) {
		
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
		
			$('.calendar.demo').fullCalendar({
				editable: true,
				events: [
					{
						title: 'All Day Event',
						start: new Date(y, m, 1)
					},
					{
						title: 'Long Event',
						start: new Date(y, m, d-5),
						end: new Date(y, m, d-2)
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d-3, 16, 0),
						allDay: false
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d+4, 16, 0),
						allDay: false
					},
					{
						title: 'Meeting',
						start: new Date(y, m, d, 10, 30),
						allDay: false
					},
					{
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false
					},
					{
						title: 'Birthday Party',
						start: new Date(y, m, d+1, 19, 0),
						end: new Date(y, m, d+1, 22, 30),
						allDay: false
					},
					{
						title: 'Click for Google',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						url: 'http://google.com/'
					}
				]			
			});
		}
		
	});

	
	
	
	
	// -----------------------------------------
	// ! Things to set up on $(window).load(...)
	
	
	// ! Set up different elements
	mango.ready(function(){
	
		// - Disabled buttons
		$('a.button.disabled').click(function(){
			return false;
		});
		
	});
	
	
	// ! Set forms
	mango.ready(function(){
	
		// ! Textarea autogrow
		$('textarea').not('.nogrow').not('.editor').autosize();
		
		
		// ! Password strength meter
		$('input:password.meter').passwordMeter();
		
		
		// ! Masked input
		$('.maskDate').mask('99/99/9999');
		$('.maskTelefone').mask('(99) 9999-9999');
		$('.maskPhoneExt').mask('(999) 999-9999? x99999');
		$('.maskIntPhone').mask('+33 999 999 999');
		$('.maskTin').mask('99-9999999');
		$('.maskSsn').mask('999-99-9999');
		$('.maskProd').mask('a*-999-a999');
		$('.maskPo').mask('PO: aaa-999-***');
		$('.maskPct').mask('99%');
		$('.maskCustom').each(function(){
			$(this).mask($(this).data('mask') || '');
		});
		
		
		// ! Validation
		
		// - Add new method: password
		$.validator.addMethod('strongpw', function(pwd, el){
			return $.pwdStrength(pwd) > 80;
		}, 'Your password is insecure');
		
		// - Add new method: checked
		$.validator.addMethod('checked', function(val, el){
			return !!$(el)[0].checked;
		}, 'You have to select this option');
		
		// - Set defaults
		$.validator.setDefaults({
		
			// Do not ignore chosen-selects | datepicker mirrors | checkboxes | radio buttons
			ignore: ':hidden:not(select.chzn-done):not(input.mirror):not(:checkbox):not(:radio):not(.dualselects),.ignore',
			
			// If a field is switches from invalid to valid
			success: function(label){
				// Change icon from error to valid
				$(label).prev().filter('.error-icon').removeClass('error-icon').addClass('valid-icon');
				
				// If file input: remove 'error' from '.customfile'
				$(label).prev('.customfile').removeClass('error');
			},
			
			// Where to place the error labels
			errorPlacement: function($error, $element){
						
				if ($element.hasClass('customfile-input-hidden')) {
					
				} else if ($element.hasClass('customfile-input-hidden')) {
				
					$error.insertAfter($element.parent().addClass('error'));
				
				// Password meter || Textarea || Spinner || Inline Datepicker || Checkbox || Radiobutton: No icon
				} else if ($element.is(':password.meter') || $element.is('textarea') || $element.is('.ui-spinner-input') || $element.is('input.mirror')) {
				
					$error.insertAfter($element);

				// Checkbox: No icon, after replacement
				} else if ($element.is(':checkbox') || $element.is(':radio')) {
				
					if ($element.is(':checkbox')) {
						$error.insertAfter($element.next().next());
					} else {
						// Find last radion button
						$error.insertAfter($('[name=' + $element[0].name + ']').last().next().next());
					}
				
				// Select: No icon, insert after select box replacement
				} else if ($element.is('select.chzn-done') || $element.is('.dualselects')) {
					
					$error.insertAfter($element.next());

				// Default: Insert after element, show icon
				} else {
			
					$error.insertAfter($element);
					
					// Show icon
					var $icon = $('<div class="error-icon icon" />').insertAfter($element).position({
						my: 'right',
						at: 'right',
						of: $element,
						offset: '-5 0',
						overflow: 'none',
						using: function(pos) {
							// Figure out the right and bottom css properties 
							var offsetWidth = $(this).offsetParent().outerWidth();
							var right = offsetWidth - pos.left - $(this).outerWidth();
							
							// Position the element so that right and bottom are set.
							$(this).css({left: '', right: right, top: pos.top});  
						}
					});
				
				}
			},
			
			// Reposition error labels and hide unneeded labels
			showErrors: function(map, list){
				var self = this;
				
				this.defaultShowErrors();
				
				list.forEach(function(err){
					var $element = $(err.element),
						$error = self.errorsFor(err.element);
				
					// Select || Textarea || File Input || Inline Datepicker || Checkbox || Radio button: Inline Error Labels
					if ( $element.data('errorType') == 'inline' || $element.is('select') || $element.is('textarea') || $element.hasClass('customfile-input-hidden') || $element.is('input.mirror') || $element.is(':checkbox') || $element.is(':radio') || $element.is('.dualselect')) {
					
						// Get element to which the error label is aligned
						var $of;
						if ($element.is('select')) {
							$of = $element.next();
						} else if ($element.is(':checkbox') || $element.is(':radio')) {
							if ($element.is(':checkbox')) {
								$of = $element.next();
							} else {
								// Find last radio button
								$of = $('[name=' + $element[0].name + ']').last().next().next();
							}
							$error.css('display', 'block');
						} else if ($element.is('input.mirror')) {
							$of = $element.prev();
						} else {
							$of = $element;
						}
						
						$error.addClass('inline').position({
							my: 'left top',
							at: 'left bottom',
							of: $of,
							offset: '0 5',
							collision: 'none'
						});
						
						if (!($element.is(':checkbox') && $element.is(':radio'))) {
							$error.css('left', '');
						}
					
					// Default: Tooltip labels
					} else {
						
						$error.position({
							my: 'right top',
							at: 'right bottom',
							of: $element,
							offset: '1 8',
							using: function(pos) {
								// Figure out the right and bottom css properties 
								var offsetWidth = $(this).offsetParent().outerWidth();
								var right = offsetWidth - pos.left - $(this).outerWidth();
								
								// Position the element so that right and bottom are set.
								$(this).css({left: '', right: right, top: pos.top});  
							}
						});
					
					} // End if
					
					// Switch icon from valid to error
					$error.prev().filter('.valid-icon').removeClass('valid-icon').addClass('error-icon');
			
					// Hide error labe on .noerror
					if ($element.hasClass('noerror')) {
						$error.hide();
						$element.next('.icon').hide();
					}
				});
				
				// Hide success labels
				this.successList.forEach(function(el){
					self.errorsFor(el).hide();
				});
				
			}
		});
		
		// - Validate
		$('form.validate').each(function(){
			$(this).validate({
				submitHandler: function(form){
					$(this).data('submit') ? $(this).data('submit')() : form.submit();
				}
			});
		});
		
		// - Reset validation on form reset
		$('form.validate').on('reset', function(){
			var $form = $(this);
			$form.validate().resetForm();
			$form.find('label.error').remove().end()
			     .find('.error-icon').remove().end()
			     .find('.valid-icon').remove().end()
			     .find('.valid').removeClass('valid').end()
			     .find('.customfile.error').removeClass('error');
		});

		
		// ! Polyfill: 'form' tag on <input>s
		if (!('form' in document.createElement('input'))) {
			$('input:submit').each(function(){
				var $el = $(this);
				if ($el.attr('form')){
					$el.click(function(){
						$('#' + $el.attr('form')).submit();
					});
				}
			});
			$('input:reset').each(function(){
				var $el = $(this);
				if ($el.attr('form')){
					$el.click(function(){
						$('#' + $el.attr('form'))[0].reset();
					});
				}
			});
		}
		
	});

	
	// ! Browser fixes and polyfills
	mango.ready(function(){
	
		// - Bad IE
		var ie = !!$.browser.msie,
			ieV = parseInt($.browser.version);
		
		// - IE 6-7 fixes
		if (ie && ieV < 8) {
			$('input[type=search]').addClass('search');
			$('input[type="search"] + ul.searchResults').addClass('in_toolbar');
		}
		if (ie && ieV == 9) {
			$('button, input:submit, input:reset, input:button').addClass('gradient');
		}
		
		// - IE 6-8 fixes
		if (ie && ieV < 9) {
			$toolbar.find('div.right').find('a').has('span.icon').addClass('has_icon');
		}
		
		if (ie && ieV == 9) {
			$sidebar.find('.badge').addClass('gradient');
		}
		
		// - Other fixes
		$('input, textarea').placeholder(); // Placeholder-polyfill
		
	});
	
	
	// ! Set up click handler
	mango.ready(function(){
	
		var $user_box = $main.find('section.toolbar').find('div.user'),
			$shortcuts_menu = $main.find('ul.shortcuts').find('li').has('div'),
			$toolbar_menu = $toolbar.children().find('ul').find('li').has('div.popup'),
			$box_menu = $main.find('.box').find('.header').find('menu'),
			$gallery_menu = $main.find('.gallery').find('menu');
	
		// - Hide popups on click outside
		$('html').click(function(e){
		
			var $target = $(e.target);
			if ($target.hasClass('ui-widget-overlay') || $target.hasClass('ui-dialog ui-widget') || $target.parents('.ui-dialog').length) {
				return;
			}
		
			// User-Box
			if (e.target !== $user_box[0] && !$user_box.doesHave(e.target) && $user_box.hasClass('clicked')) {
				$user_box.find('ul').slideUp(mango.config.fxSpeed, function(){
					$user_box.removeClass('clicked');
				});
			}
			
			// Shortcut popups
			if (!$shortcuts_menu.doesHave(e.target)) {			
				$shortcuts_menu.removeClass('active').children('div:visible').fadeOut(mango.config.fxSpeed);
			}
			
			// Toolbar popups
			if (!$toolbar_menu.doesHave(e.target)) {
				$toolbar_menu.removeClass('active').children('div.popup:visible').fadeOut(mango.config.fxSpeed);
			}
			
			// Box Menu popups
			$box_menu.each(function(){
				var $menu = $(this);
				if ($menu.is(':visible') && e.target != $menu.prev()[0] && !$menu.doesHave(e.target)) {
					$menu.prev().removeClass('open');
					$menu.fadeOut(mango.config.fxSpeed);
				}
			});

			// Gallery popups
			$gallery_menu.each(function(){
				var $menu = $(this);
				if ($menu.is(':visible') && e.target != $menu.prev()[0] && !$menu.doesHave(e.target)) {
					$menu.prev().removeClass('open');
					$menu.fadeOut(mango.config.fxSpeed);
				}
			});
			
		});

	});
	
	
	// ! Set up tooltips
	mango.ready(function(){
		
		// If tooltips are not included
		if (!$.fn.tipsy) {
			return;
		}
		
		$.fn.tipsy.defaults.opacity = 1;
		
		$('.tooltip').each(function(){
			var $tooltip = $(this),
				grav = $tooltip.data('gravity') || $.fn.tipsy.autoNS,
				anim = $tooltip.data('anim') || true;
			
			$tooltip.tipsy({
				gravity: grav,
				fade: anim
			});
		});
	
	});
	
	
	// ! Set up the user menu (most left toolbar menu)
	mango.ready(function(){
	
		$main.find('section.toolbar').find('div.user')
			.click(function(){
			
				var $this = $(this);
				
				// If the menu is already shown
				if ($this.hasClass('clicked')) {
				
					// Slide up
					$this.find('ul').slideUp(mango.config.fxSpeed, function(){
						$this.removeClass('clicked');
					});
				
				// The menu is not shown
				} else {
				
					// Slide down
					$this.find('ul').slideDown(mango.config.fxSpeed);
					$this.addClass('clicked');
					
				}
				
			})
			.find('ul')
				.click(mango.utils.noBubbling);
		
	});
	
	
	// ! Shortcuts popups
	mango.ready(function(){
		
		// For every shortcut with popup item:
		var $shortcuts_menu = $main.find('ul.shortcuts').find('li').has('div').each(function(){
		
			var $item = $(this), $box = $item.children('div');
			
			// On shourtcut click
			$item.click(function(e){		
			
				// Hide other opened popups
				$shortcuts_menu.not($item).children('div').fadeOut(mango.config.fxSpeed, function(){
					$shortcuts_menu.not($item).removeClass('active');
				});
				
				// Show the requestedd one
				$box.fadeToggle(mango.config.fxSpeed);
				$item.toggleClass('active');
				
			});
			
			// Do not bubble up
			$box.click(mango.utils.noBubbling);
			
		});
	
	});
	
	
	// ! Toolbar popups
	mango.ready(function(){
		
		// For every shortcut with popup item:
		var $shortcuts_menu = $toolbar.children().find('ul').find('li').has('div.popup').each(function(){
		
			var $item = $(this), $box = $item.children('div');
			
			// On shortcut click
			$item.click(function(e){
			
				if ($item.hasClass('disabled')) {
					return false;
				}
			
				// Hide other open popups
				$shortcuts_menu.not($item).children('div').fadeOut(mango.config.fxSpeed, function(){
					$shortcuts_menu.not($item).removeClass('active');
				});
				
				// FIX: wrong position in IE8
				if ($('html').hasClass('lt-ie9') && $box.is(':hidden')) {
					$box.show().css({
						left: 0
					}).position({
						my: 'top',
						at: 'bottom',
						of: $item,
						offset: '0 15',
						using: function(pos){
							$box.css({
								'left': pos.left,
								'top': 37
							});
						}
					}).hide();
				}
				
				// Show the requested shortcut
				$box.fadeToggle(mango.config.fxSpeed);
				$item.toggleClass('active');
				
				return false;
			});
			
			// Do not bubble up
			$box.click(mango.utils.noBubbling);
			
		});
		
		// ! Mail List
		var $mail = $('.mail').has('.text');
		$mail
			.on('click', 'li', function(){
				$mail.find('.text:visible').slideUp(mango.config.fxSpeed / 2);
				$(this).find('.text:hidden').slideToggle(mango.config.fxSpeed / 2);
			})
			.on('hover', 'li', function(){
				$(this).toggleClass('normal');
			})
		.find('.text')
			.hover(function(){
				$(this).parent('li').toggleClass('normal');
			})
			.click(mango.utils.noBubbling);
			
		// ! Popup positioning
		$shortcuts_menu.each(function(){
			var $el = $(this);
			$el.find('.popup').show().position({
				my: 'top',
				at: 'bottom',
				of: $el,
				offset: '0 15'
			}).hide();
		});
			
	});
	
	
	// ! The search box
	mango.ready(function(){
		
		// - Initialize the search
		$main.find('.toolbar').find('input[type="search"]').search({
			// source is set via data-source attribute
			minLength: 2
		});
	
	});
	
	
	// ! Scroll to Top button
	mango.ready(function(){
		
		if (mango.config.scollToTop) {
			
			var $toTop = $('<a>', {
				href: '#top',
				id: 'gotoTop'
			}).appendTo('body'),
				$window = $(window);
			
			// On scroll: create debounced function (see http://documentcloud.github.com/underscore/#debounce)
			$window
				.scroll(_.debounce(function(){
						if(!jQuery.support.hrefNormalized) {
							$toTop.css({
								'position': 'absolute',
								'top': $window.scrollTop() + $window.height() - settings.ieOffset
							});
						}
						
						// If we are not at the top: fade out
						if ($window.scrollTop() >= 1) {
							$toTop.fadeIn();
						} else {
						// Else: fade in						
							$toTop.fadeOut();
						}
						
					}, 300))
				// Call scroll handler (if page loads scrolled from cache)
				.scroll();
			
			// Scroll up on click
			$toTop.click(function(){
			
				$("html, body").animate({scrollTop: 0});
				return false;
				
			});
		}
	
	});
	
	
	// ! Notifications
	mango.ready(function(){
		$.jGrowl.defaults.life = 8000;
		$.jGrowl.defaults.pool = 5;
	});
	
	// ! Syntax Highlighter	
	// Set up autoloader
	// @see: https://github.com/alexgorbatchev/SyntaxHighlighter/issues/133
	// mango.utils.tryF(function(){
	// 	SyntaxHighlighter.autoload = function (brushes) {function handler(e) {
	// 			e = e || window.event;
	// 			if (!e.target) {
	// 				e.target = e.srcElement;
	// 				e.preventDefault = function () {
	// 					this.returnValue = false;
	// 				}
	// 			}
	// 			SyntaxHighlighter.autoloader.apply(this, brushes);
	// 			SyntaxHighlighter.all();
	// 		}
	// 		if (window.attachEvent) window.attachEvent("on" + "load", handler);
	// 		else window.addEventListener("load", handler, false)
	// 	};

	// 	function addPath(path, array) {
	// 		var result = [];
	// 		for (var i = 0; i < array.length; ++i) {
	// 			var elem = array[i].slice();
	// 			elem[elem.length - 1] = path + elem[elem.length - 1];
	// 			result.push(elem);
	// 		}
	// 		return result;
	// 	}
	// 	SyntaxHighlighter.autoload(addPath("js/mylibs/syntaxhighlighter/", [
	// 		["applescript", "shBrushAppleScript.js"],
	// 		["actionscript3", "as3", "shBrushAS3.js"],
	// 		["bash", "shell", "shBrushBash.js"],
	// 		["coldfusion", "cf", "shBrushColdFusion.js"],
	// 		["cpp", "c", "shBrushCpp.js"],
	// 		["c#", "c-sharp", "csharp", "shBrushCSharp.js"],
	// 		["css", "shBrushCss.js"],
	// 		["delphi", "pascal", "shBrushDelphi.js"],
	// 		["diff", "patch", "pas", "shBrushDiff.js"],
	// 		["erl", "erlang", "shBrushErlang.js"],
	// 		["groovy", "shBrushGroovy.js"],
	// 		["java", "shBrushJava.js"],
	// 		["jfx", "javafx", "shBrushJavaFX.js"],
	// 		["js", "jscript", "javascript", "shBrushJScript.js"],
	// 		["perl", "pl", "shBrushPerl.js"],
	// 		["php", "shBrushPhp.js"],
	// 		["text", "plain", "shBrushPlain.js"],
	// 		["py", "python", "shBrushPython.js"],
	// 		["ruby", "rails", "ror", "rb", "shBrushRuby.js"],
	// 		["sass", "scss", "shBrushSass.js"],
	// 		["scala", "shBrushScala.js"],
	// 		["sql", "shBrushSql.js"],
	// 		["vb", "vbnet", "shBrushVb.js"],
	// 		["xml", "xhtml", "xslt", "html", "shBrushXml.js"]
	// 	]));
	// })();
	
	
	// ! Login
	mango.ready(function(){
	
		var $login = $('#login'),
			$msg = $login.find('.login-messages');
			
		// Positioning of the messages
		$msg.height($msg.height());
		$msg.children().css('position', 'absolute');
		
		// Set up validation
		var $form = $login.find('form');
		$form.validationOptions({
			invalidHandler: function(){
				$msg.find('.welcome').fadeOut();
				$msg.find('.failure').fadeIn();
			}
		});

	});
	
	
	// ! Centered Elements
	// TODO: remove?
	/*
	mango.ready(function(){
		
		$(window).resize(_.debounce(function(){
			$('.center-elements').each(function(){
				$(this).children().each(function(){
					var $this = $(this).css('width', '');
					$this.width($this.width());
				});
			});
		}, 100)).resize();
	
	});
	// */
	
	
	// ! Disable dragging on some elements
	mango.ready(function(){
			
		var disableDrag = [];
		disableDrag.push($toolbar.find('li'));
		disableDrag.push($('nav').find('li'));
		disableDrag.push($('section.toolbar').find('li').find('a'));
		disableDrag.push($('header').find('img'));
		disableDrag.push($('div.avatar').find('img'));
		disableDrag.push($('ul.shortcuts').find('li'));
		disableDrag.push($('a.button'));
		disableDrag.push($('.profile').find('.avatar').children());
		disableDrag.push($('.messages').find('.buttons').children());
		disableDrag.push($('.full-stats').find('.stat'));
		disableDrag.push($('.ui-slider'));
		disableDrag.push($('.checkbox'));
		disableDrag.push($('.radiobutton'));
		disableDrag.push($('#gotoTop'));
		disableDrag.push($('.uploader'));
		disableDrag.push($('.dataTables_paginate'));
		disableDrag.push($('.avatar'));
		disableDrag.push($('header a'));
		disableDrag.push($('.gallery'));
		disableDrag.push($('.tabletools').find('a'));
		
		$.each(disableDrag, function(){$(this).on('dragstart', function(event) { event.preventDefault(); });});
		
	});
		
	
	// ! Loading screen
	mango.ready(function(){
	
		// ! Hide loading screen
		$('#loading').fadeOut(mango.config.fxSpeed);
		$('#loading-overlay').delay(100 + mango.config.fxSpeed).fadeOut(mango.config.fxSpeed * 2);

		// - Start counting when #loading fadeout is finished
		setTimeout(function(){
			
			// ! The lock screen
			// - Start only, if required html is complete and we don't have a phone
			if ($('#lock-screen').length && $('#btn-lock').length && !mango.utils.isPhone) {
				mango.lock();
			}
			
		}, mango.config.fxSpeed);
	});
	
	
	// ! Preload images
	mango.ready(function(){
		
		if (!mango.config.preloadImages) {
			return;
		}
		
		mango.utils.preload([
			'img/layout/navigation/arrow-active.png',
			'img/layout/navigation/arrow-hover.png',
			'img/layout/navigation/arrow.png',
			'img/layout/navigation/bg-current.png',
			'img/layout/navigation/bg-active.png',
			'img/layout/navigation/bg-hover.png',
			'img/layout/navigation/bg-normal.png'
		]);
		
		mango.utils.preload([
			'img/layout/sidebar/bg-right.png',
			'img/layout/sidebar/bg.png',
			'img/layout/sidebar/divider.png',
			'img/layout/sidebar/shadow-right.png',
			'img/layout/sidebar/shadow.png',
			'img/layout/sidebar-right/header-bg.png',
			'img/layout/sidebar-right/nav-bg-hover.png',
			'img/layout/sidebar-right/nav-bg.png'
		]);
		
		mango.utils.preload([
			'img/layout/toolbar/bg.png',
			'img/layout/toolbar/buttons/bg-active.png',
			'img/layout/toolbar/buttons/bg-disabled.png',
			'img/layout/toolbar/buttons/bg-hover.png',
			'img/layout/toolbar/buttons/bg-red-active.png',
			'img/layout/toolbar/buttons/bg-red-disabled.png',
			'img/layout/toolbar/buttons/bg-red-hover.png',
			'img/layout/toolbar/buttons/bg-red.png',
			'img/layout/toolbar/buttons/bg.png',
			'img/layout/toolbar/buttons/divider.png'
		]);
		
		mango.utils.preload(['img/layout/footer/divider.png']);
		
				
		mango.utils.preload([
			'img/layout/bg.png',
			'img/layout/content/box/actions-bg.png',
			'img/layout/content/box/bg.png',
			'img/layout/content/box/header-bg.png',
			'img/layout/content/box/menu-active-bg.png',
			'img/layout/content/box/menu-arrow.png',
			'img/layout/content/box/menu-bg.png',
			'img/layout/content/box/menu-item-bg-hover.png',
			'img/layout/content/box/menu-item-bg.png',
			'img/layout/content/box/tab-hover.png',
			'img/layout/content/toolbar/bg-shortcuts.png',
			'img/layout/content/toolbar/bg.png',
			'img/layout/content/toolbar/divider.png',
			'img/layout/content/toolbar/popup-arrow.png',
			'img/layout/content/toolbar/popup-header.png',
			'img/layout/content/toolbar/user/arrow-normal.png',
			'img/layout/content/toolbar/user/avatar-bg.png',
			'img/layout/content/toolbar/user/avatar.png',
			'img/layout/content/toolbar/user/bg-hover.png',
			'img/layout/content/toolbar/user/bg-menu-hover.png',
			'img/layout/content/toolbar/user/counter.png'
		]);
		
		
		mango.utils.preload([
			'img/elements/alert-boxes/bg-error.png',
			'img/elements/alert-boxes/bg-information.png',
			'img/elements/alert-boxes/bg-note.png',
			'img/elements/alert-boxes/bg-success.png',
			'img/elements/alert-boxes/bg-warning.png',
			'img/elements/alert-boxes/error.png',
			'img/elements/alert-boxes/information.png',
			'img/elements/alert-boxes/note.png',
			'img/elements/alert-boxes/success.png',
			'img/elements/alert-boxes/warning.png'
		]);
		
		mango.utils.preload([
			'img/elements/breadcrumb/bg-active.png',
			'img/elements/breadcrumb/bg-hover.png',
			'img/elements/breadcrumb/divider-active.png',
			'img/elements/breadcrumb/divider-hover.png'
		]);
		
		mango.utils.preload([
			'img/elements/headerbuttons/bg-active.png',
			'img/elements/headerbuttons/bg-hover.png'
		]);
	
		mango.utils.preload([
			'img/elements/autocomplete/el-bg-hover.png'
		]);
		
		mango.utils.preload([
			'img/elements/calendar/arrow-hover-bg.png'
		]);
		
		mango.utils.preload(['img/elements/charts/hover-bg.png']);
		
		mango.utils.preload([
			'img/elements/messages/button-active-bg.png',
			'img/elements/messages/button-hover-bg.png'
		]);
		
		mango.utils.preload([
			'img/elements/messages/button-active-bg.png',
			'img/elements/messages/button-hover-bg.png'
		]);
		
		mango.utils.preload([
			'img/elements/mail/actions-bg.png',
			'img/elements/mail/button-bg-disabled.png',
			'img/elements/mail/button-bg-hover.png',
			'img/elements/mail/button-bg.png',
			'img/elements/mail/button-red-bg-hover.png',
			'img/elements/mail/button-red-bg.png',
			'img/elements/mail/button-red-disabled.png',
			'img/elements/mail/hover-bg.png',
			'img/elements/mail/mail.png',
			'img/elements/mail/text-arrow.png',
			'img/elements/mail/text-bg.png'
		]);
		
		mango.utils.preload([
			'img/elements/fullstats/list/hover-bg.png',
			'img/elements/fullstats/simple/a-active.png',
			'img/elements/fullstats/simple/a-hover.png'
		]);
		
		mango.utils.preload([
			'img/elements/checkbox/checked-active.png',
			'img/elements/checkbox/checked-disabled.png',
			'img/elements/checkbox/checked-hover.png',
			'img/elements/checkbox/checked-normal.png',
			'img/elements/checkbox/unchecked-active.png',
			'img/elements/checkbox/unchecked-disabled.png',
			'img/elements/checkbox/unchecked-hover.png',
			'img/elements/checkbox/unchecked-normal.png'
		]);
		
		mango.utils.preload([
			'img/elements/radiobutton/checked-active.png',
			'img/elements/radiobutton/checked-disabled.png',
			'img/elements/radiobutton/checked-hover.png',
			'img/elements/radiobutton/checked-normal.png',
			'img/elements/radiobutton/unchecked-active.png',
			'img/elements/radiobutton/unchecked-disabled.png',
			'img/elements/radiobutton/unchecked-hover.png',
			'img/elements/radiobutton/unchecked-normal.png'
		]);
		
		mango.utils.preload([
			'img/elements/colorpicker/arrow.png',
			'img/elements/colorpicker/bg.png'
		]);
		
		mango.utils.preload([
			'img/elements/forms/icon-error.png',
			'img/elements/forms/icon-success.png',
			'img/elements/forms/tooltip-error-arrow.png',
			'img/elements/forms/tooltip-error.png'
		]);
		
		mango.utils.preload([
			'img/elements/profile/change-active-bg.png',
			'img/elements/profile/change-hover-bg.png'
		]);
		
		mango.utils.preload([
			'img/elements/search/arrow.png',
			'img/elements/search/glass.png',
			'img/elements/search/list-hover.png',
			'img/elements/search/loading.gif'
		]);

		mango.utils.preload([
			'img/elements/select/bg-active.png',
			'img/elements/select/bg-hover.png',
			'img/elements/select/bg-right-hover.png',
			'img/elements/select/list-hover-bg.png'
		]);
		
		mango.utils.preload([
			'img/elements/settings/header-bg.png',
			'img/elements/settings/header-current-bg.png',
			'img/elements/settings/header-hover-bg.png',
			'img/elements/settings/seperator-current-left.png',
			'img/elements/settings/seperator-current-right.png',
			'img/elements/settings/seperator.png'
		]);

		mango.utils.preload([
			'img/elements/slide-unlock/lock-slider.png'
		]);
		
		mango.utils.preload([
			'img/elements/spinner/arrow-down-active.png',
			'img/elements/spinner/arrow-down-hover.png',
			'img/elements/spinner/arrow-up-active.png',
			'img/elements/spinner/arrow-up-hover.png',
			'img/elements/table/pagination/active.png',
			'img/elements/table/pagination/disabled.png',
			'img/elements/table/pagination/hover.png',
			'img/elements/table/toolbar/hover.png',
			'img/elements/table/sorting-asc.png',
			'img/elements/table/sorting-desc.png',
			'img/elements/table/sorting.png'
		]);
		
		mango.utils.preload([
			'img/elements/tags/bg.png',
			'img/elements/tags/left.png'
		]);
		
		mango.utils.preload([
			'img/elements/to-top/active.png',
			'img/elements/to-top/hover.png',
			'img/elements/to-top/normal.png'
		]);
		
		mango.utils.preload(['img/elements/tooltips/bg.png']);
		
		mango.utils.preload([
			'img/elements/upload/bg-hover.png',
			'img/elements/upload/bg-normal.png'
		]);
		
		mango.utils.preload([
			'img/elements/wizard/arrow-current.png',
			'img/elements/wizard/arrow-error.png',
			'img/elements/wizard/arrow-normal.png',
			'img/elements/wizard/arrow-success.png',
			'img/elements/wizard/bg-current.png',
			'img/elements/wizard/bg-error.png',
			'img/elements/wizard/bg-normal.png',
			'img/elements/wizard/bg-success.png',
			'img/elements/wizard/bg.png'
		]);
		
		mango.utils.preload([
			'img/external/chosen-sprite.png'
		]);
		
		mango.utils.preload([
			'img/external/colorpicker/blank.gif',
			'img/external/colorpicker/colorpicker_background.png',
			'img/external/colorpicker/colorpicker_hex.png',
			'img/external/colorpicker/colorpicker_hsb_b.png',
			'img/external/colorpicker/colorpicker_hsb_h.png',
			'img/external/colorpicker/colorpicker_hsb_s.png',
			'img/external/colorpicker/colorpicker_indic.gif',
			'img/external/colorpicker/colorpicker_overlay.png',
			'img/external/colorpicker/colorpicker_rgb_b.png',
			'img/external/colorpicker/colorpicker_rgb_g.png',
			'img/external/colorpicker/colorpicker_rgb_r.png',
			'img/external/colorpicker/colorpicker_select.gif',
			'img/external/colorpicker/colorpicker_submit.png',
			'img/external/colorpicker/custom_background.png',
			'img/external/colorpicker/custom_hex.png',
			'img/external/colorpicker/custom_hsb_b.png',
			'img/external/colorpicker/custom_hsb_h.png',
			'img/external/colorpicker/custom_hsb_s.png',
			'img/external/colorpicker/custom_indic.gif',
			'img/external/colorpicker/custom_rgb_b.png',
			'img/external/colorpicker/custom_rgb_g.png',
			'img/external/colorpicker/custom_rgb_r.png',
			'img/external/colorpicker/custom_submit.png',
			'img/external/colorpicker/select.png',
			'img/external/colorpicker/select2.png',
			'img/external/colorpicker/slider.png'
		]);
		
		mango.utils.preload([
			'img/external/editor/buttons.gif',
			'img/external/editor/toolbar.gif'
		]);
		
		mango.utils.preload([
			'img/external/explorer/arrows-active.png',
			'img/external/explorer/arrows-normal.png',
			'img/external/explorer/crop.gif',
			'img/external/explorer/dialogs.png',
			'img/external/explorer/icons-big.png',
			'img/external/explorer/icons-small.png',
			'img/external/explorer/logo.png',
			'img/external/explorer/progress.gif',
			'img/external/explorer/quicklook-bg.png',
			'img/external/explorer/quicklook-icons.png',
			'img/external/explorer/resize.png',
			'img/external/explorer/spinner-mini.gif',
			'img/external/explorer/toolbar.png'
		]);
		
		mango.utils.preload([
			'img/external/gallery/blank.gif',
			'img/external/gallery/fancybox_buttons.png',
			'img/external/gallery/fancybox_loading.gif',
			'img/external/gallery/fancybox_sprite.png'
		]);
		
		mango.utils.preload([
			'img/external/jquery-ui/ui-bg_flat_0_000000_40x100.png',
			'img/external/jquery-ui/ui-bg_flat_30_000000_40x100.png',
			'img/external/jquery-ui/ui-bg_flat_65_e3e3e3_40x100.png',
			'img/external/jquery-ui/ui-bg_flat_75_ffffff_40x100.png',
			'img/external/jquery-ui/ui-bg_glass_55_fbf9ee_1x400.png',
			'img/external/jquery-ui/ui-bg_highlight-hard_100_f0f0f0_1x100.png',
			'img/external/jquery-ui/ui-bg_highlight-soft_100_e8e8e8_1x100.png',
			'img/external/jquery-ui/ui-bg_highlight-soft_75_b3bfcb_1x100.png',
			'img/external/jquery-ui/ui-bg_inset-soft_95_fef1ec_1x100.png',
			'img/external/jquery-ui/ui-icons_222222_256x240.png',
			'img/external/jquery-ui/ui-icons_2e83ff_256x240.png',
			'img/external/jquery-ui/ui-icons_3a4450_256x240.png',
			'img/external/jquery-ui/ui-icons_454545_256x240.png',
			'img/external/jquery-ui/ui-icons_888888_256x240.png',
			'img/external/jquery-ui/ui-icons_cd0a0a_256x240.png'
		]);
		
		mango.utils.preload([
			'img/external/uploader/backgrounds.gif',
			'img/external/uploader/buttons-disabled.png',
			'img/external/uploader/buttons.png',
			'img/external/uploader/delete.gif',
			'img/external/uploader/done.gif',
			'img/external/uploader/error.gif',
			'img/external/uploader/throbber.gif',
			'img/external/uploader/transp50.png'
		]);
		
		mango.utils.preload([
			'img/jquery-ui/accordion-header-active.png',
			'img/jquery-ui/accordion-header-hover.png',
			'img/jquery-ui/accordion-header.png',
			'img/jquery-ui/datepicker/arrow-left.png',
			'img/jquery-ui/datepicker/arrow-right.png',
			'img/jquery-ui/datepicker/button-bg.png',
			'img/jquery-ui/datepicker/button-hover-bg.png',
			'img/jquery-ui/datepicker/button-seperator.png',
			'img/jquery-ui/datepicker/day-current.png',
			'img/jquery-ui/datepicker/day-hover.png',
			'img/jquery-ui/datepicker/days-of-week-bg.png',
			'img/jquery-ui/datepicker/header-bg.png',
			'img/jquery-ui/datepicker/time-bg.png',
			'img/jquery-ui/datepicker/top-arrow.png',
			'img/jquery-ui/dialog-titlebar-close-hover.png',
			'img/jquery-ui/dialog-titlebar.png',
			'img/jquery-ui/loading.gif',
			'img/jquery-ui/progressbar/bg.png',
			'img/jquery-ui/progressbar/fill-blue-small.png',
			'img/jquery-ui/progressbar/fill-blue.gif',
			'img/jquery-ui/progressbar/fill-blue.png',
			'img/jquery-ui/progressbar/fill-grey.gif',
			'img/jquery-ui/progressbar/fill-grey.png',
			'img/jquery-ui/progressbar/fill-orange-small.png',
			'img/jquery-ui/progressbar/fill-orange.gif',
			'img/jquery-ui/progressbar/fill-orange.png',
			'img/jquery-ui/progressbar/fill-red-small.png',
			'img/jquery-ui/progressbar/fill-red.gif',
			'img/jquery-ui/progressbar/fill-red.png',
			'img/jquery-ui/slider/bg-range.png',
			'img/jquery-ui/slider/bg.png',
			'img/jquery-ui/slider/disabled-bg-range.png',
			'img/jquery-ui/slider/disabled-bg.png',
			'img/jquery-ui/slider/disabled-picker.png',
			'img/jquery-ui/slider/disabled-vertical-bg-range.png',
			'img/jquery-ui/slider/disabled-vertical-bg.png',
			'img/jquery-ui/slider/disabled-vertical-picker.png',
			'img/jquery-ui/slider/picker.png',
			'img/jquery-ui/slider/vertical-bg-range.png',
			'img/jquery-ui/slider/vertical-bg.png',
			'img/jquery-ui/slider/vertical-picker.png'
		]);
		
	});
	
})(jQuery, $$);
