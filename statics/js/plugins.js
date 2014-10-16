"use strict";

// make it safe to use console.log always
(!window.console||!console.log)&&function(){for(var c=function(){},a="assert clear count debug dir dirxml error exception group groupCollapsed groupEnd info log markTimeline profile profileEnd markTimeline table time timeEnd timeStamp trace warn".split(" "),b=a.length,d=window.console={};b--;)d[a[b]]=c}();


// ! Helper: Does an element contian another?
// - $.fn.has does not have the effect
//   I thought it would have. Unlike
//   $.fn.has my function returns true/false
// - Options:
//    - el: The element to be searched
(function($, undefined){

	$.fn.doesHave = function(el){
		return !!this.has(el).length;
	}

})(jQuery);



// ! Helper: Insert html quickly
// - Options:
//    - html: html string/jQuery object to insert
(function($, undefined){
	$.fn.insert = function(html){
		var el = this[0];
		if (typeof html == 'string') 	{ el.innerHTML += html; return $(el.children); }
		else if (html.jquery) 			{ el.innerHTML += html.html(); return $(el.children);	}
		else 							{ return this.append(html); }
	};
})(jQuery);









// ! Helper: Give elements equal height/width
(function($, undefined){
	$.fn.equalHeight = function() {
		var $this = $(this);
		$this.height(Math.max.apply(Math, $this.map(function(){ return $(this).height(); }).get()));
		return $this;
	}

	$.fn.equalWidth = function() {
		var $this = $(this);
		$this.width(Math.max.apply(Math, $this.map(function(){ return $(this).width(); }).get()));
		return $this;
	}
})(jQuery);








// ! Alert Messages
// - Generate alert messages
// - Options:
//    - type: string
//        One of: 'information' (default),
//        'warning', 'error', 'success', 'note'
//    - position: 'top' or 'bottom'
//        Special positioning of the alert message
//    - noMargin: boolean (false)
//        Give the message full width
//    - sticky: boolean (false)
//        Make messages sticky or not

(function($, window, undefined){

	$.fn.alert = function(message, options) {
	
		
		return $(this).each(function() {
		
			var $el = $(this),
				settings = $.extend({}, $.fn.alert.defaults, options, $el.data());

			var alertClass = 'alert ' + settings.type;
			if(settings.noMargin) {
				alertClass += ' no-margin';
			}
			if(settings.position) {
				alertClass += ' ' + settings.position;
			}
			
			var $alert = $('<div style="display:none" class="' + alertClass + ' generated">' + message + '</div>');
			
			$alert.prepend($('<span>').addClass('icon'));
			if (!settings.sticky) {
				$alert.find('.icon').after($('<span>').addClass('close').text('x'))
			}

			var alertElement =  $el.prepend($alert);

			$alert.fadeIn();

			
		}); // End of '$el.each(function ...)'
		
	}; // End of '$.fn.alert = ...'
	
	$.fn.alert.defaults = {
		type : 'information',
		position : '',
		noMargin : false,
		sticky: false
	};
	
})(jQuery, this);










// ! Validation Options
// - Set validation options
// - Options:
//    - options: object
//        The options to set
//
// - Note:
//    - If submitHandler is set and
//      returns false, the original
//      submitHandler won't be executed.

(function($, window, undefined){

	$.fn.validationOptions = function(options) {
	
		
		return $(this).each(function() {
		
			// Get validation engine
			var $form = $(this),
				validator = $form.validate();
				
			// Handle submitHandler
			if (options.submitHandler) {
				// Store original submitHandler and given submitHandler
				var _submitHandler = validator.settings.submitHandler,
				submitHandler = options.submitHandler;
				
				// Set submitHandler
				validator.settings.submitHandler = function(){
					!!submitHandler.apply(this, arguments) && _submitHandler.apply(this, arguments);
				}
				
				delete options.submitHandler;
			}
			
			// Handle invalidHandler
			if (options.invalidHandler) {
				$form.on('invalid-form.validate', options.invalidHandler);
			}
			
			// Expand settings
			$.extend(validator.settings, options);

			
		}); // End of '$el.each(function ...)'
		
	}; // End of '$.fn.setValidationOptions = ...'
	
})(jQuery, this);












// ! Sidebar Menu
// - This initializes the sidebar menu.
// - Options:
//    - accordion: true/false (true)
//        Hide open submenus when opening one (accordion effect).
//        Note: you can set this option also by giving the menu the class
//        'accordion' or setting the 'data-accordion'-attribute to true.
//        The priority is:
//          1. class='accordion'
//          2. data-accordion='true'
//          3. options = { accordion: true }

(function($, window, undefined){

	$.fn.initMenu = function(options) {
	
		var $el = $(this);
	
		// Set up defaults/options
		var opts = $.extend({}, {
			// Defaults:
			accordeon: true
		}, options, $el.data());

		
		return $el.each(function() {
		
			var $menu = $(this);
			
			// ! Set options
			if ($menu.hasClass('accordion')) {
				opts.accordion = true;
			}
			
			
			// ! Append arrow to submenu items
			$menu.find('li:has(ul)')
				.children('a').addClass('with_sub').end()
				.children('ul').addClass('sub');
			
			var $subs = $menu.find('.sub');
			
			
			// ! Set the container's height
			
			if (opts.accordion) {
				
				// ! Max height: heighest .sub opened
				// - Find highest .sub
				$subs.show();
				var height = 0, $sub = $();
				
				$subs.each(function(){
					var $this = $(this),
						subHeight = $this.height();
					
					// If .sub is higher than cached highest .sub
					if (subHeight > height) {
						height = subHeight;
						$sub = $this;
					}
				});
				
				// - Get height of menu with heighest sub opened
				$subs.hide();
				$sub.show();
//				$menu.height($menu.height());
				$sub.hide();
				
			} else {

				// ! Max height: all .subs opened
				$subs.show();
				$menu.height($menu.height());
				$subs.hide();

			}
			
			
			// ! Show submenus with .open			
			$subs.filter(function(){ return $(this).prev().is('.open'); }).show();
			
			
			// ! Handle menu item clicks
			$menu.find('li a').click($$.utils.noBubbling);
			$menu.find('li a').click(function(e) {
			
				var $this = $(this), $submenu = $this.next(),
				speed = $$.config.fxSpeed * 2 / 3;
				
				
				// Use accordeon
				if(opts.accordion) {

					// If there is a visible submenu
					if($submenu.is('.sub:visible')) {

						// Slide up
						$submenu.prev().removeClass('open');
						$subs.filter(':visible').slideUp(speed);
						return false;
					
					// If submenu is not shown
					} else if($submenu.is('.sub:hidden')) {
					
						// Slide up other submenus
						$subs.filter(':visible').slideUp(speed);
						$menu.find('.open').removeClass('open');

						// Slide up current submenu
						$submenu.slideDown(speed);
						$submenu.prev().addClass('open');
												
					}

					
				// Do not use accordeon
				} else {
				
					// If there is no submenu to show
					if(!$submenu.length) {
					
						// Go to link destination
						window.location.href = $this.attr('href');
						
					// If there is a submenu
					} else {
					
						// Show/hide it
						$submenu.slideToggle(speed);
						$submenu.prev().toggleClass('open');
						
					}
					
					return false;
					
				} // End of 'if (opts.accordeon)'
				
			}); // End of '$menu.find('li a').click(function ...)'
			
		}); // End of '$el.each(function ...)'
		
	}; // End of '$.fn.initMenu = ...'
	
})(jQuery, this);









// ! Dynamic Tables
// - Set up datatables
//
// - Options: see defaults

(function($, window, document){

	var PLUGIN_NAME = 'table';
	$.fn[PLUGIN_NAME] = function(options){
	
		var $el = $(this);
			
		return $(this).each(function(){
			
			var $table = $(this),
				opts = $.extend(true, {}, $.fn[PLUGIN_NAME].defaults, options, $table.data());
				
			// ! Get options
			if (_.isString($table.data('tableTools'))) {
				$table.data('tableTools', $.parseJSON($table.data('tableTools')));
			}
				
			// ! Set up table
			$table.dataTable($.extend(true, {
				sDom: (opts.filterBar != 'none' ? '<"filters"fl>' : '') + 't<"footer"ip>',
				sPaginationType: 'full_numbers',
				iDisplayLength: opts.maxItemsPerPage,
				oLanguage: {
					sLengthMenu: '<span class=text>' + opts.lang.SHOW_ENTRIES + '</span> _MENU_',
					sSearch: '<span class=text>' + opts.lang.SEARCH + '</span> _INPUT_'
				},
				aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']]
			}, opts.dataTable)).parent().find('.dataTables_length select').data('width', 'auto');
			
			var $dataTable = $table.parent(),
				$filterBar = $dataTable.find('.filters');
			
			['with-prev-next', 'full'].forEach(function(cls){
				$table.hasClass(cls) && $dataTable.addClass(cls);
			});
			$table.removeClass('full');
			
			
			// ! Toolbar
			$dataTable.prev('.tabletools').insertBefore($table);
			
			// - TableTools
			if (opts.tableTools.display || $dataTable.find('.tabletools').length) {
				if (opts.tableTools.display) {
					var tableTools = new TableTools($table.dataTable(), $.extend(true, {
						sSwfPath: opts.tableTools.swfUrl,
						aButtons: opts.tableTools.buttons
					}, opts.tableTools.extras));
				}
				
				var $container = $dataTable.find('.tabletools');
				
				// Create toolbar if it doesn't exist
				if (!$container.length) {
					$container = $('<div class=tabletools><div class=left></div><div class=right></div></div>').insertBefore($table);
				}
				if (opts.tableTools.display) {
					$container.find('.' + opts.tableTools.pos).append(tableTools.dom.container);
				}
			}
			
			// ! Set up filter bar button
			if (opts.filterBar != 'none') {
				// Add button
				if (opts.filterBar != 'always') {
					var $toggleBtn = $('<div class=toggleFilter></div>').insertBefore($dataTable).click(function(){
						$filterBar.slideToggle($$.config.fxSpeed * 2 / 3);
						$toggleBtn.toggleClass('active');
					});
					
					
					// Show filter bar initially
					if (opts.showFilterBar) {
						$toggleBtn.addClass('active');
					}
				}
				
				// Show filter bar initially
				if (opts.filterBar == 'always' || opts.showFilterBar) {
					$dataTable.find('.filters').show();
				}
			}
		
		}); // End of '$(this).each(...)'
		
	} // End of '$.fn[PLUGIN_NAME] = ...'
	
	// Defaults:
	$.fn[PLUGIN_NAME].defaults = {
		filterBar: 'toggle', // One of: 'always', 'toggle, 'none'
		showFilterBar: false, // Show or hide the filter bar initially
		maxItemsPerPage: 10,
		dataTable: {},	// dataTable options
		tableTools: {
			display: false,							// Show tableTools?
			buttons: [ 'csv', 'xls', 'pdf'],	// Which buttons to show
			pos: 'right', 							// Show on the left or on the right?
			swfUrl: 'extras/datatables/copy_csv_xls_pdf.swf',	// Where the swf file is located
			extras: {}								// Other tableTools options
		},
		
		lang: {
			SHOW_ENTRIES: 'Número de Registros:',
			SEARCH: 'Buscar:'
		}
	};
	
})(jQuery, this, document);









// ! Tabbed Box
// - Create the box with tabs
// - The content to be shown
//   is set in the html:
//   The content corresponding
//   to 'ul li.current' will
//   be shown initially.

(function($, window, document){

	$.fn.tabbedBox = function(options){
	
		options = $.extend({}, {
			// Defaults:
			fxSpeed: $$.config.fxSpeed / 1.2,
			header: '.header',
			content: '.content'
		}, options);
	
		var $return = $(this).each(function(){
		
			// ! Show a content box
			var showContent = function($content, anim) {
			
				if ($content.is(':visible')) {
					return;
				}

				var $actions = $('.actions[rel=' + $content[0].id + ']');
				
				if (!anim) {
					$container.children(':visible').hide();
					$content.show();
					$allActions.hide();
					$actions.show();
					return;
				}
			
				// - Hide old actions and show new
				$allActions.hide();
				$actions.show();
				
				// - Hide old content
				$container.children(':visible').fadeOut(options.fxSpeed, function(){
					
					// - Show current content
					$content.fadeIn(options.fxSpeed, function(){
					
						// - Fix for jQuery UI Accordion
						$content.find('.ui-accordion').accordion('resize');
					
					});
					
				});
				
			} // End of 'showContent = ...'
		
			// ! Prepare everything
			var $box = $(this),
				$container = _.isString(options.content) ? $box.find(options.content) : $(options.content),
				$allActions = $box.find('.actions[rel]'),
				$tabs = _.isString(options.header) ? $box.find(options.header).find('ul') : $(options.header),
				current = $tabs.find('li.current').find('a').attr('href'); // Parse li.current
				
			if (!$tabs.is('ul')) {
				$tabs = $tabs.find('ul');
			}
				
			// - If no li.current was found:
			if (!current) {
				
				// Open first tab list item
				current = $tabs.find('li').first().find('a').attr('href');
				
			}
				
			// - Hide tab contents
			$container.children().hide();
			
			// ! Click on tab
			$tabs.on('click', 'a', function(e){	
			
				var $li = $(this).parent(), href = $li.find('a').attr('href'), lhash = location.hash;	
				var old_href = $tabs.find('li.current').find('a').attr('href');
				
				// Hash is in URL
				if (lhash.split(',').indexOf(old_href) != -1) {
				
					// Replace hash
					location.hash = lhash.replace(old_href, href);
				
				// Hash is not in URL
				} else {
					
					// Add hash
					location.hash += ((lhash.length && ',') || '') + href;
				}
				
				return false;
			});
			
			// ! Hashchange
			$(window).on('hashchange', {anim: true}, function(e, anim){

				var anim = anim == undefined && true || anim;
				
				var hash = location.hash,
					$content;
				
				// ! Parse hashes: #id1(,#id2)*
				// - Find requested content
				hash.trim().split(',').forEach(function(h){
				
					var $tmp = $container.find(h);
					
					if ($tmp.length) {
						$content = $tmp;
						hash = h;
					}
					
				});
				
				// ! No hashes given, try open .current, otherwise open first
				if ($content == undefined) {
					// Open .current
					$content = $container.find($tabs.find('li.current').find('a').attr('href'));
				}
				
				if ($content == undefined || !$content.length) {
					// Open first tab
					$content = $container.find($tabs.find('a').first().attr('href'));
				}
				
				// If content was found
				if ($content.length) {
				
					// Show content and current tab
					showContent($content, anim);
					$tabs.find('.current').removeClass('current');
					$tabs.find('li:has(a[href=#' + $content.attr('id') + '])').addClass('current');

				}
				
			}); // End of '$(window).on('hashchange', ...)'
			
		}); // End of '$(this).each(...)'
	
		// Show current tab
		$(window).trigger('hashchange', [false]);
		return $return;
		
	} // End of '$.fn.tabbedBox = ...'
	
})(jQuery, this, document);










// ! Wizard
// - Small wizard plugin
// - Options:
//   - onSubmit: Function called on form submit.
//               Put your submit AJAX there.

(function($, window, document){

	var PLUGIN_NAME = 'wizard',
		instances = [];

	$.fn[PLUGIN_NAME] = function(options){
	
		options = $.extend({}, {
			// Defaults:
			onSubmit: function() {
				// Alert! :)
				alert('Wizard completed successfully! :)');
				
				$(this).parent().fadeOut();
				return false;
			}
		}, options);
	
		return  $(this).each(function(){
			var $this = $(this);
			var isForm = $this.is('form');
			
			if (!_.contains(instances, $this[0])) {
				instances.push($this[0]);
			} else {
				return;
			}
			
			// ! Set up frequently used elements
			var $el = {
				box: $this,
				content: $this.find('.content'),
				list: $this.find('.steps'),
				a_list: $this.find('.steps').find('a'),
				
				prev: $this.find('.actions').find('.left').find('a'),
				next: $this.find('.actions').find('.right').find('a').not('.finish'),
				finish: $this.find('.actions').find('.right').find('a.finish')
			};
		
			// ! Change step
			var goToIndex = function(newIndex){	
			
				// Old step
				var $oldStepLink = $el.a_list.filter('.current'),
					$oldStep = $($oldStepLink.attr('href'));
			
				// Fetch new step
				var $newStepLink = $el.a_list.eq(newIndex),
					$newStep = $($newStepLink.attr('href'));
			
				// ! Validate arguments
				if (newIndex > length || newIndex < 0) {
					return false;
				}
				
				// ! Do validation
				if (isForm && $this.hasClass('validate')) {
					// Do validation before going to new page
					var valid = true,
						validator = $this.validate();
					
					$oldStep.find(':input:enabled').each(function(){
						var fieldIsValid = validator.element($(this));
					
						if (fieldIsValid === undefined) {
							fieldIsValid = true;
						}
						
						valid &= fieldIsValid;
					});
					
					if (!valid) {
						// Show error
						$oldStepLink.addClass('error');
						return false;
					}
				}
				
				// ! Check for finish
				if (newIndex == length) {
					// Do finish
					if (isForm) {
						$this.submit(options.onSubmit).submit();
					}
				}
				
				// ! Hide old step and mark is as successfull
				$oldStepLink.removeClass('current').addClass('success');
				$oldStep.hide();
				
				// ! Show current step
				$newStepLink.removeClass('error').removeClass('success').addClass('current');
				$newStep.show();
				
				// ! If we are at the first step, disable the 'back' button
				if (newIndex == 0) {
					$el.prev.addClass('disabled');
				} else {
					$el.prev.removeClass('disabled');
				}
				
				// ! If we are at the last step, show 'Finish' instead of 'Next'
				if (newIndex == length - 1) {
					$el.next.hide();
					$el.finish.show().css('display', 'inline-block');
				} else if (!$el.next.is(':visible')) {
					$el.next.show();
				}
				
				// ! Possibly resize form
				if (isForm && $$.utils.forms) {
					$$.utils.forms.resize();
				}
				
				index = newIndex;
			},
			index = 0,
			length = 0;
						
			// ! Get number of steps
			length = $el.list.children().length;
			
			// ! Set up steps links
			$el.a_list.click(function(e){
				e.preventDefault();
				
				goToIndex($(this).parent().index());
			});
			
			// ! Set up prev/next/finish-buttons
			$el.prev.addClass('disabled'); // Initially disabled
			$el.prev.click(function(e){
				e.preventDefault();				
				goToIndex(index - 1);
			});
			
			$el.next.click(function(e){
				e.preventDefault();
				goToIndex(index + 1);
			});
			
			$el.finish.click(function(e){
				e.preventDefault();
				goToIndex(length); // Go to finish
			});
		
		}); // End of '$(this).each(...)'
		
	} // End of '$.fn[PLUGIN_NAME] = ...'
	
})(jQuery, this, document);









// ! Password Meter
// - Show the strength of a password

(function($, window, document){

	var PLUGIN_NAME = 'passwordMeter';
	$.fn[PLUGIN_NAME] = function(options){
	
		return  $(this).each(function(){
			var $input = $(this),
				keysBlacklist = _.difference(_.values($.ui.keyCode), /* Whitelist: */ [$.ui.keyCode.DELETE, $.ui.keyCode.COMMA, $.ui.keyCode.PERIOD, $.ui.keyCode.SPACE]),
				$indicator = $('<div class=passwordmeter></div>').insertAfter($input),
				$wrapper = $('<div class="passwordmeter-wrapper"></div>').insertAfter($indicator);
			
			// TODO?: Better coding
			$wrapper.append($indicator).append($input);
			
			var needsReposition = true,
				// Reposition the indicator
				reposition = function(){
					$indicator.position({
						my: 'right',
						at: 'right',
						of: $input,
						offset: '-10 0'
					});				
				},
				reset = function(){
					$input.val('');
					update();
				};
				
			$input.data('reposition', reposition);
			$input.data('reset', reset);
			
			$indicator.css('opacity', 0);
		
			// Calculate gradient width
			var bg = new Image();
			bg.src = $indicator.css('background-image').replace(/"/g,"").replace(/url\(|\)$/ig, "");
			bg.onload = function(){ maxBgPositionX = bg.width - $indicator.width() }; // FIX for webkit
			var maxBgPositionX = bg.width - $indicator.width();
			
			// Update gradient on user input
			var update = function(){
				// Calculate strength
				var strength = $.pwdStrength($input.val());
				// Update backgound image
				$indicator.css('background-position', '-' + maxBgPositionX * (strength / 100) + 'px 0');
				// Optional validation: check $(...).data('pwStrength')
				$input.data('pwStrength', strength);
			}
			
			// Set up events
			$input.keypress(function(e){
				var $input = $(this);
				
				update();
			}).keyup(function(e){
				// Handle deletion of characters
				if (e.which == $.ui.keyCode.DELETE || e.which == 8) {
					update();
				}
			}).focus(function(){
				if (needsReposition) {
					needsReposition = false;
					reposition();
				}
				
				$indicator.animate({
					opacity: 1
				});
			});
		
		}); // End of '$(this).each(...)'
		
	} // End of '$.fn[PLUGIN_NAME] = ...'
	
})(jQuery, this, document);










// ! Search
// - A live search with results box
//
// - Options: see defaults in code below
//     - Note: all options (except lang)
//             can be set via data-attributes
//             (see data-search in html)
//     - Note: The priority of the options is:
//               1. data-tags (data-source='...' etc.)
//               2. $(...).search(options)
//
// - Methods:
//     Methods can be called via $(...).search('method name', arguments...);
//     - abort: Abort the current ajax search request
//     - destroy: Revert the input into the original state
//
// - Note:
//     Arrow key navigation is supported :)

(function($, _, window, document, undefined) {
	
	var PLUGIN_NAME = 'search', ns = '.mango_' + PLUGIN_NAME;
	
	// Instances will be stored here
	var instances = [];
	
	// The publically callable function
	$.fn[PLUGIN_NAME] = $.extend(function (method, options) {
		
		var ret = this,
			args = arguments;
		
		$(this).each(function(){
			
			var inst,
				$el = $(this);
						
			// ! Create instance		
			if (inst = instances.filter(function(o){return o.el[0] == $el[0];})[0]) {inst = inst.inst}
			else { instances.push({ el: $el, inst: inst = self() }); }
			
			// ! Parse arguments
			if (typeof method === "object") {
				options = method;
				method = undefined;
			}
			
			// - Default method is 'init'
			method = method || 'init';
			
			// Warning if plugin was not initialized	
			method != 'init' && !inst.initialized
				&& $.error('$.fn.' + PLUGIN_NAME + ' was not initialzed. Please call $.fn.' + PLUGIN_NAME + '(options) first.');
				
			// ! Call the requested method
			if ($.isFunction(inst.get(method))) {
			
				// - Get options
				var opts;
				
				// 'method' was not given, arguments contains all options
				if (args[0] == options) {
					opts = $.makeArray(args);
					
				// arguments[1,2,3,...] contains the optinos
				} else if (args.length > 1) {
					opts = Array.prototype.slice.call(args, 1);
				
				// Only options is given
				} else {
					opts = [options];
				}
			
				var fret = inst.get(method).apply($el, opts);
				if (!_.isUndefined(fret)) {
					ret = fret;
				}
				
			// ! Return property value
			} else if (inst.get(method)){
			
				var prop = arguments[0], val = arguments[1];
			
				// Dynamical getter & setter
				if (!val) {
					ret = inst.get(prop);
				} else {
					inst.set(prop, val);
				}
				
			} else {
			
				// Method or property not found
				$.error('Method or property ' +  method + ' does not exist on jQuery.fn.' + PLUGIN_NAME);
				
			}
		
		});
		
		return ret;
		
	}, {
		// Accessable via $.fn.PLUGIN_NAME[name]
		
		// ! Default settings
		defaults: {
			interval: 700, // ms: Time to wait after user has stopped typing
			minLength: 3, // int: The number of chars to enter before start searching
			
			source: 'search.php', // url: Where to get the search results from
			maxResults: 5, // int: Number of results to show at max
			
			resultsClass: 'searchResults', // css-class: The class name for the results container
			fxSpeed: $$.config.fxSpeed, // ms: The duration of the animations
			
			imgWidth: 50, // px: The width of the images in the results list
			
			lang: { // Langugage. Customize if you need.
				NO_RESULTS: 'Nothing found.',
				SHOW_MORE: 'Show more results (%num%)&hellip;' // %num% -> number of results not displayed
			}
		} // End of 'defaults'
		
	});
	
	// Constructor
	function self() {
		
		// Define public members (accessable via $.fn.PLUGIN_NAME[name])
		var me = {},
			publics = ['init', 'abort', 'destroy'];
		$.extend(me, {
			
			// ### Functions
		
			// ! Initialize the plugin
			init: function(opts){
			
				var $this = me.$element = $(this);
				me.options = $.extend(true, {}, me.defaults, opts, $this.data()); // Combine defaults and options
								
				// ! Prepare events
				me.timer = -1; // Used for keyup event
				me.hover = -1; // Used for arrow key navigation
				
				me.events = {
					'keyup': function(e){
						
						// ! Arrow keys navigation
						if (e.keyCode == $.ui.keyCode.UP || e.keyCode == $.ui.keyCode.DOWN) {
						
							// If result
							if (me.$results.is(':visible')) {
							
								var resultsCount = me.$results.find('li').length;
								
								// Remove old hover
								me.$results.find('li').eq(me.hover).removeClass('hover');
							
								switch (e.keyCode) {
									case $.ui.keyCode.UP:
										// Move up
										me.hover = (me.hover - 1) % resultsCount;
										if (me.hover < 0) {
											me.hover = resultsCount - 1;
										}
										break;
									case $.ui.keyCode.DOWN:
										// Move down
										me.hover = (me.hover + 1) % resultsCount;
										break;
								}
								
								// Add hover to selected result
								me.$results.find('li').eq(me.hover).addClass('hover');
								
							} // End of 'if ($results.is(:visible)) {...}'
							
							return false;
							
						// Enter pressed --> go to result
						} else if (e.keyCode == $.ui.keyCode.ENTER) {
							
							var $hovered = me.$results.find('li.hover');
							
							if ($hovered.length) {
								window.location.href = $hovered.find('a').attr('href');
							}
							
							return false;
							
						// Escape pressed --> abort & clear
						} else if (e.keyCode == $.ui.keyCode.ESCAPE) {
							
							me.abort();
							me.$element.blur();
							
						
						}// End of 'if ... else ...'
					
						// ! User input
						
						// Stop already running search request
						me.abort();
					
						// User is still typing, stop timer (see below)
						clearTimeout(me.timer);
						
						// User has typed enough chars to start the search
						if ($this.val().length > me.options.minLength) {
						
							// Do the search when user stops typing
							me.timer = setTimeout(me.search.bind(this), me.options.interval);
							
						// If the search input is empty and the results are show
						} else if ($this.val().trim().length == 0 && me.$results.is(':visible')) {
						
							me.$results.fadeOut(me.options.fxSpeed); // Hide results
							
						}
					} // End of 'keyup'
					
				}; // End of 'me.events'

				
				// ! Set up the event handlers
				for (var key in me.events) {
					$this.on(key + ns /* -> namespacing ftw */, me.events[key]);
				}
				
				
				// ! Fade out if user clicks somewhere else
				$(this).on('blur', function() {
								
						me.abort(); // Abort the search
						$this.val(''); // Empty the search box
						me.$results.fadeOut(me.options.fxSpeed); // Hide the results container
						me.hover = -1; // Reset hover index
					
				});
				
				
				// ! Prepare results container
				
				// If the results container doesn't already exist...
				if (!$this.next().is('ul.' + me.options.resultsClass)) {
				
					// ... create the container and insert it into the right place
					$('<ul>', {'class': me.options.resultsClass}).insertAfter($this);
					
				}
				me.$results = $this.next();
				
				// When hovering a result, update hover index
				me.$results.on('mousemove' + ns /* -> namespacing ftw */, function(e){
				
					var $target = $(e.target),
						$el = $target.is('li') ? $target : $(e.target).parents('li');
						
					me.hover = $el.index();
					me.$results.find('li').removeClass('hover');
					$el.addClass('hover');
					
				});
			
				// Ready
				me.initialized = true;

			}, // End of 'init'

			// ! Do the search itself
			search: function(){
			
				var $this = $(this), term = $this.val();
				
				$this.addClass('loading'); // Show the loading spinner
				
				me.xhr /* Store the ajax request */ = $.post(me.options.source, {term: term, max: me.options.maxResults}, function(results){
					var htmlArray = [];
					
					// If we have results
					if (results.length) {
					
						// Build the html
						results.forEach(function(entry){
							var html = '<li><a href="' + entry.href + '">';
							if (entry.img) {
								html += '<img src="' + entry.img + '" width=' + me.options.imgWidth + '>';
							}
							html += '<strong>' + entry.title + '</strong>';
							if (entry.descr) {
								html += '<small>' + entry.descr + '</small>';
							}
							html += '</a></li>';
							
							htmlArray.push(html);
						});
						
						// To many results...
						if (htmlArray.length > me.options.maxResults) {
						
							htmlArray = htmlArray.slice(0, me.options.maxResults - 1);
							htmlArray.push('<li><a href="#" class="showMore">' + me.options.lang.SHOW_MORE.replace('%num%', results.length - me.options.maxResults) + '</a></li>');
						
						}
						
					// No results
					} else {
					
						// Show 'no results' entry
						htmlArray.push('<li><a href="#" class="noResults">' + me.options.lang.NO_RESULTS + '</a></li>');
						
					} // End of 'if (results.length) {} else {}'
					
					// Show the results container
					me.$results.html(htmlArray.join('')).fadeIn();
					
				}, 'json'); // End of 'me.xhr = $.post ...'
				
				// When the search ajax request is ready, remove the loading spinner.
				$.when(me.xhr).always(function(){$this.removeClass('loading');});
				
			}, // End of 'search'
			
			
			// ! Abort a running search
			abort: function(){
			
				me.xhr && me.xhr.abort();
				
			},
			
			
			// ! Revert the search input into the original state
			destroy: function(){
			
				me.$results.remove(); // Remove the results container
				me.$input.off(ns); // Unbind all our events
				
				// Remove the instance from the instances list
				instances = _.filter(instances, function(inst){
					return inst.el[0] != me.$element[0];
				});
				
			}, // End of 'destroy'
			
			
			// ### Variables
			
			// ! Has the plugin already been initialized?
			initialized: false
			
		}, $.fn[PLUGIN_NAME]);
		
		// Publish members		

		var pub = {};
		
		$.extend(pub, {
			get: function(prop){
				if (_.contains(publics, prop)) {
					return me[prop];
				}
			},
			
			set: function(prop, val) {
				if (_.contains(publics, prop)) {
					me[prop] = val;
				}
			}
		});
		
		return pub;
		
	}; // End of self() {}

})(jQuery, _, this, document);










// ! Sidebar progress
// - Show the little progress bars
//   in the sidebar.
//
// - Options:
//     - title: string
//         The title
//     - value: float
//         The current value
//     - max: float
//         The maximal value
//     - format: string
//         How to format the values.
//         You can format the values
//
//     - maxNormal: float
//         All values above this will be marked as 'warn level'
//     - maxWarn: float
//         All values above this will be marked as 'critical level'
//
// - Note:
//     - Change progressbar value: .progressbar('value', 14);
//     - All options can be set via data-attributes

(function($, window, undefined){

	$.fn.infobar = function(opts) {
	
		return $(this).each(function(){
			
			// ! Collect settings
			var $bar = $(this),
				data = $.extend({}, $.fn.infobar.defaults, opts, $bar.data(), {colorize: false});
		
			// ! Build HTML
			var format = function(){
				return new Number(data.value).numberFormat(data.format) + ' / ' + new Number(data.max).numberFormat(data.format);
			},
			$bar = $(this)
			
			
			// - Show the title
				.append($('<strong>', {html: data.title}))
			
			// - Show value
				.append($('<small>')) // The value will be set later on
			
				.append($('<div class="clearfix">')); // Clear floats
			
			// - Show the progress bar
			var $progress = $bar.append($('<div>', {'class': 'small'}).progressbar(data)).children('div.ui-progressbar');
			
			// ! Colorize the progress bars
			$progress
				.bind('progressbarchange', function(e) {
				
					if (data.color) {
						$pbar.addClass(data.color);
						return;
					}
				
					// Get new data
					data = $bar.data($progress.data('progressbar').options).data();
					
					// Show new 
					$bar.find('small').html(format());
					
					// Calculate percent values
					var $pbar = $progress.children();
					var percent = parseInt(data.value / data.max * 100);
					
					$pbar.removeClass('blue green orange red');
					
					// Colorize bars
					if (percent < data.maxNormal){
						$pbar.addClass('blue');
					} else if (percent < data.maxWarn){
						$pbar.addClass('orange');
					} else {
						$pbar.addClass('red');
					}
					
				})
				.trigger('progressbarchange');

		}); // End of 'return $(this).each(...)'
		
	} // End of '$.fn.infobar = ...'
	
	// Defaults:
	$.fn.infobar.defaults = {
		title: '',
		value: 0,
		max: 0,
		format: '',
		
		color: false,
		maxNormal: 60,
		maxWarn: 90
	}
	
})(jQuery, this);









// ! Slider
// - Extend the jQuery UI Slider
//
// - Options:
//     - min: string
//         The minimal value
//     - max: float
//         The maximal value
//     - step: float
//         The step size
//     - range: array
//         If a range slider is needed,
//         set an array here with the min/max
//         values: e.g. [5, 55]
//     - orientation: string
//         Set orientation to vertical or horizontal
//
//     - format: string
//         How to format the values.
//     - pattern: string
//         The tooltip pattern. %n is the current value
//     - tooltip: boolean
//         Enable or disable tooltips
//
// - Note:
//     - 'value' and 'disabled' are set via attributes:
//         <input value=22 disabled=true />
//     - All options can be set via data-attributes

(function($, window, undefined){

	$.fn.mslider = function(opts) {
	
		return $(this).each(function(){
	
			// ! Helper: format
			var format = function(value, format){
				return new Number(value).numberFormat(format + '');
			},
		
			// ! Collect settings
				$input = $(this).hide(),
				data = $.extend({}, {
					// Defaults:
					value: 50,
					min: 0,
					max: 100,
					step: 1,
					
					range: false,
					values: undefined,
					orientation: 'horizontal',
					disabled: false,
					
					onslide: null,
					
					tooltip: true,
					format: '0',
					pattern: '%n'
				}, opts, (function(){
					var o = {};
					if ($input.val()) {
						o.value = $input.val();
					}
					if ($input.attr('disabled')) {
						o.disabled = true;
					}
					return o;
				})(), $input.data());
				
			// Parse input val if stored after reload
			if (_.isString(data.value) && data.value != '' && data.value.match(/^\d+:\d+$/)) {
				var reg = data.value.match(/^(\d+):(\d+)$/);
				data.range = [reg[1], reg[2]];
			}
			
			// Parse values
			data.values = (data.range == false || data.range == 'max' || data.range == 'min' ) ? undefined : (_.isArray(data.range) ? data.range : $.parseJSON(data.range));
			
			// Parse range
			data.range = data.range == false ? 'min' : (data.range == 'max' || data.range == 'min' ? data.range : true);

			
			// ! Write value to original input
			var saveInput = function(value, values){
				values ? $input.val(values[0] + ':' + values[1]) : $input.val(value);
			}
			
			saveInput(data.value, data.values);
			
			
			// ! Set up slider
			var $slider = $('<div>').insertAfter($input).slider({
				value: data.value,
				max: data.max,
				min: data.min,
				step: data.step,
				
				orientation: data.orientation,
				disabled: data.disabled,
				
				slide: function(e, ui){
					// Write value to original input
					saveInput(ui.value, ui.values);
					data.onslide && data.onslide.call(e, ui);
					
					// Update tooltip
					var $tooltip = $(ui.handle).find('.slider-tooltip');
					$tooltip.text(data.pattern.replace('%n', format(ui.value, data.format)));
				},
				start: function(e, ui) {
					$(ui.handle).find('.slider-tooltip').show().text(data.pattern.replace('%n', format(ui.value, data.format)));
				},
				stop: function(e, ui) {
					$(ui.handle).find('.slider-tooltip').hide();
				},
				
				range: data.range,
				values: data.values
			});
			
			// ! Set up tooltips
			$slider.find('.ui-slider-handle').each(function(){
				if (!data.tooltip) {
					return;
				}
			
				$('<div class="slider-tooltip">').hide().appendTo($(this));
			});
					
		});
	} // End of '$.fn.infobar = ...'
	
})(jQuery, this);









// ! Settings Dialog
// - A cool settings dialog

(function($, mango, window, undefined){

	mango.settings = function(){
			mango.settings.el.dialog({
				modal: true,
				draggable: false,
				width: mango.settings.el.data('width') || mango.config.settings.width,
				
				open: function(){
					var $dialog = $(this).parent(),
						$header = $dialog.find('.ui-dialog-titlebar').addClass('settings-header'),
						$content = $(this);
					$dialog.find('.ui-dialog-titlebar-close').remove(); // Remove the close button
					$dialog.find('.ui-dialog-title').remove(); // Remove title
					
					// FIX: overflow:hidden bug
					$dialog.css('overflow', 'visible');
					
					// Move tabs to header
					$dialog.find('.tabs').appendTo($header);
					$dialog.tabbedBox({
						header: '.settings-header'
					});
					
					// Set up forms
					$dialog.find('input').each(function(){
						var $this = $(this);
						$this.attr('name', $this.attr('id'));
					});
					
					// Set up action bar
					if ($content.find('.actions').length) {
						$content.addClass('with-actions');
					}
					
					// Set up save button
					var $save = $content.find('.save').show(),
						$saving = $content.find('.saving').hide();
						
					$save.click(function(){
						$save.hide();
						$saving.show();
						
						// Collect data of forms
						var formData = [];
						$content.find('form').each(function(){
							formData.push($(this).serializeArray());
						});
						
						mango.settings.save(function(){
							$content.dialog('close');
						}, formData);
					});
					
					// Set up cancel button
					var $cancel = $content.find('.cancel');
					
					$cancel.click(function(){
						$content.dialog('close');
						$content.find('.content').find('form')[0].reset();
					});
					
					// Set up 'change password' button
					var $pwButton = $content.find('.change_password_button'),
						$pwPopup = $dialog.find('.change_password');
					
					// Show password popup
					$pwButton.click(function(){
						$pwPopup.slideDown();
						$dialog.find('.tabs').addClass('disabled');
						$content.addClass('disabled');
					});
					
					// Set up 'change password' form
					var $pwCancel = $pwPopup.find('input[type=reset]'),
						$pwOk = $pwPopup.find('input[type=submit]'),
						$pwInput = $pwPopup.find('input[type=password]');
					
					var hidePwPopup = function(){
						$pwPopup.fadeOut();
						$dialog.find('.tabs').removeClass('disabled');
						$content.removeClass('disabled');
					};
					
					// Cancel: Clean up form and close popup
					$pwCancel.click(function(){
						$pwInput.val('');
						$pwInput.data('reset') && $pwInput.data('reset')();
						hidePwPopup();
						
						return false;
					});
					
					// Submit: Run changePw callback
					$pwPopup.find('form').validationOptions({
						submitHandler: function(){
							mango.settings.changePw(function(){
								$pwCancel.click();
								$pwButton.val($pwButton.data('langChanged'));
							}, $pwInput.val());
							return false;
						}
					});
				}
			});
		} // End of mango.settings = ...
		
		$.extend(mango.settings, {
		
			el: $('#settings'),

			// Save the form contents
			save: function(ready, formData){
				// Call ready() on success:
				// $.get(url, formData, function(){ $content.dialog('close'); });
				// - formData is the form data
				setTimeout(function(){
					ready();
				}, 500);
			},
			
			// Change password
			changePw: function(ready, pwd) {
				// Password is available in 'pwd'
				// Call ready() on success:
				// $.post(url, parameters, ready);
				ready();
			}
			
		});
		
		// FIX
		mango.loaded(function(){
			mango.settings.el = $('#settings');
		});
	
})(jQuery, $$, this);








// ! Lock screen
// - Show the lock screen if
//   the user was idle for
//   a certain time.
//
// - Methods:
//     Call methods with: $$.lock('method')
//     - init	: Initialize the lock screen
//         - Options:
//            - passwordIsValid: function
//                 Validate the user password
//            - start: boolean (default: true)
//                 Start counting immediately
//                 If false, use $$.lock('start') to start.
//     - start	: Start counting
//     - stop	: Stop counting
// 
// - Note:
//   If you want to disable the lock screen,
//   simply remove '#lock-screen' and the
//   corresponding toolbar button from the html.

(function($, mango, _, window, document, undefined) {
	
	var PLUGIN_NAME = 'lock', ns = '.mango_' + PLUGIN_NAME;
	
	// Instances will be stored here
	var instance;
	
	// The publically callable function
	$$[PLUGIN_NAME] = $.extend(function (method, options) {
		
		var inst;
		
		// ! Create instance	
		if (!instance) {instance = inst = self(); }
		else { inst = instance; }
		
		// ! Parse arguments
		if (typeof method === "object") {
			options = method;
			method = undefined;
		}
		
		// - Default method is 'init'
		method = method || 'init';

		// ! Call the requested method
		if ($.isFunction(inst.get(method))) {
		
			var opts;
			
			// 'method' was not given, arguments contains all options
			if (arguments[0] == options) {
				opts = $.makeArray(arguments);
				
			// arguments[1,2,3,...] contains the optinos
			} else if (arguments.length > 1) {
				opts = Array.prototype.slice.call(arguments, 1);
			
			// Only options is given
			} else {
				opts = [options];
			}
					
			return inst.get(method).apply(null, opts);
			
		// ! Return property value
		} else if (inst.get(method)){
		
			var prop = arguments[0], val = arguments[1];
		
			// Dynamical getter & setter
			return (!val && inst.get(prop)) || (inst.set(prop, val) && undefined);
			
		} else {
		
			// Method or property not found
			return $.error('Method or property ' +  method + ' does not exist on jQuery.fn.' + PLUGIN_NAME);
			
		}
		
	}, {
		// ! Default settings
		defaults: {
		
			// Default password validator.
			
			// In this place, do some ajax to verify the user's password.
			// For security reasons you should use an encrypted connection
			// (or everyone could read the password in plain text).
			
			// Please also make sure, the user can't use the page in case he
			// simply removes the lock screen using Firebug!
			passwordIsValid: function(pw) {
				return pw.length > 2;
			}
			
		} // End of 'defaults'
		
	});
	
	function self() {
		
		// Define public members (accessable via $.fn.PLUGIN_NAME[name])
		var publics = ['init', 'start', 'stop'];
		
		$.extend(self, {
			
			// ### Functions
		
			// ! Open the dialog
			open: function(){
				// Open the dialog
				$('#lock-screen').dialog({
					modal: true,
					draggable: false,
					closeOnEscape: false,
					
					open: function(){
						$(this).parent().find('.ui-dialog-titlebar-close').remove(); // Remove the close button
					}
				});
			},
			
			// ! Initialize the lock screen for the first use
			// - TL;DR
			//    - Set up Page Visibility API
			//    - Cache used elements
			//    - Show slider/password animations
			//    - Set up the slider timer
			//    - Set up the slider
			//    - Set up the password form
			//    - Set up password input
			//    - Detect user activity
			//    - Set up toolbar button
			init: function(opts){
			
				self = this;
				
				self.options = $.extend({}, self.defaults, opts);
				
				// ! Set up Page Visibility API
				// - Set name of hidden property and visibility change event
				var hidden, visibilityChange; 
				self.pageVisibility = {};
				
				if (typeof document.hidden !== "undefined") {
					self.pageVisibility.hidden = "hidden";
					self.pageVisibility.visibilityChange = "visibilitychange";
				} else if (typeof document.mozHidden !== "undefined") {
					self.pageVisibility.hidden = "mozHidden";
					self.pageVisibility.visibilityChange = "mozvisibilitychange";
				} else if (typeof document.msHidden !== "undefined") {
					self.pageVisibility.hidden = "msHidden";
					self.pageVisibility.visibilityChange = "msvisibilitychange";
				} else if (typeof document.webkitHidden !== "undefined") {
					self.pageVisibility.hidden = "webkitHidden";
					self.pageVisibility.visibilityChange = "webkitvisibilitychange";
				} else {
					self.pageVisibility = undefined;
				}
				 
				
				// ! Cache the used elements
				self.el = {
					$lock: $('#lock-screen'),
					$slider_wrapper: $("#slide_to_unlock"),
					$display: $('#btn-lock').find('span')
				}
				$.extend(self.el, {
					$form: self.el.$lock.find('form'),
					$slider: self.el.$slider_wrapper.find('img')
				});
				$.extend(self.el, {
					$pwd: self.el.$form.find('input[type=password]'),
					$submit: self.el.$form.find('input[type=submit]'),
					
					$sliderText: self.el.$slider.next()
				});
				
				
				// ! Show slider/password animations
				self.show = {
				
					slider: function(){
					
						// Revert password form
						self.el.$pwd.val('').removeClass('error').next('.icon').remove();
						self.el.$submit.attr('disabled', 'true');
						
						// Revert slider
						self.el.$slider.css('left', 0);
						self.el.$sliderText.css('opacity', 1);
						
						// Hide form and show slider
						self.el.$form.stop().hide('fade', 500);
						self.el.$slider_wrapper.stop().scale(1).show().animate({
							opacity: 1,
							bottom: 5
						}, 300);
						
					}.bind(self),
					
					
					password: function(){
											
						// Move down slider
						self.el.$slider_wrapper.animate({
							bottom: -50,
							opacity: 0.2,
							scale: 1.2
						}, 500, function(){
							// Hide slider, when moved down
							self.el.$slider_wrapper.hide();
						});
						
						// Show password form
						self.el.$form.show('fade', 1000, function(){
							self.el.$form.css('opacity', 1)
						});
						self.el.$pwd.focus();
						
						// Start idle timer (show slider if user gets idle)
						self.sliderTimer();
						
					}.bind(self)
					
				} // End of 'self.show'
				
				
				// ! Set up the slider timer
				// - Show the slider again,
				//   if the user is idle
				self.sliderTimer = function(){
									
					self.sliderTimer.id = setTimeout(function(){
					
						self.show.slider(); //  Show slider
						clearTimeout(self.sliderTimer.id); // Clear timeout
						
					}, mango.config.lock.idle * 1000); // * 1000 -> sec to ms
					
				} // End of 'self.sliderTimer = ...'
				
				self.sliderTimer.id = -1;
				
				// If there is user input, restart counting
				self.el.$pwd.keydown(function(){
					clearTimeout(self.sliderTimer.id); // Stop counting
					self.sliderTimer(); // Restart counting
				});
				
				
				// ! Set up the slider itself
				self.el.$slider.draggable({
					axis: 'x',
					containment: 'parent',
					
					// During user is dragging
					drag: function(event, ui) {
					
						// Fade out slider text while dragging proceedes
						self.el.$sliderText.css("opacity", 1 - (ui.position.left / 120))
						
					},
					
					// When user releases slider
					stop: function(event, ui) {
					
						// If the user has not dragged the slider to the end
						if (ui.position.left + self.el.$slider.outerWidth() < self.el.$slider_wrapper.innerWidth() - /* be tolerant*/ 5) {
						
							// Move slider back
							$(this).animate({
								left: 0
							});
							
							// Show slider text again
							self.el.$sliderText.animate({opacity: 1});
							
						// Slider was dragged to the end
						} else {
						
							// Show password form
							self.show.password();
							
						}
						
					} // End of 'stop'
					
				}); // End of 'draggable'
				
				// Make the slider touch compatible
				// see: http://www.evanblack.com/blog/touch-slide-to-unlock/
				if (self.el.$slider[0].addEventListener) {
					self.el.$slider[0].addEventListener('touchmove', function(event) {
						event.preventDefault();
						var el = event.target;
						var touch = event.touches[0];
						curX = touch.pageX - self.offsetLeft - 73; // TODO: check -73
						if(curX <= 0) return;
						if(curX > 160){ // TODO: read from css
							$slider_wrapper.fadeOut();
							$form.fadeIn();
						}
						el.style.webkitTransform = 'translateX(' + curX + 'px)'; 
					}, false);
					self.el.$slider[0].addEventListener('touchend', function(event) {	
						self.style.webkitTransition = '-webkit-transform 0.3s ease-in';
						self.addEventListener( 'webkitTransitionEnd', function( event ) { self.style.webkitTransition = 'none'; }, false );
						self.style.webkitTransform = 'translateX(0px)';
					}, false);
				}
				
				
				// ! Set up the password form
				// The button handlers
				
				// The reset button
				// - Show the slider again
				self.el.$form.find('input[type=reset]').click(function(){
					self.show.slider();
					return false;
				});
				
				// The submit button
				// - Check the password and
				//   close the lock screen
				self.el.$form.submit(function(){
					
					if (self.options.passwordIsValid(self.el.$pwd.val())) {
						// Success
						self.el.$lock.dialog('close');
						self.el.$pwd.removeClass('error').next('.icon').remove();
						setTimeout(self.show.slider, 500); // Show the slider again but only after the dialog has closed
						self.start(); // Restart timer
					} else {
						$('<div class="icon error-icon">').insertAfter(self.el.$pwd).position({
							my: 'right',
							at: 'right',
							of: self.el.$pwd,
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
						self.el.$pwd.addClass('error');
						return false;
					}
					
					return false;
					
				}); // End of '$form.submit'
				
				
				// ! Set up the password input
				// - Enable the 'OK' button if
				//   the field is not empty.
				self.el.$pwd.keyup(function(){
				
					if (self.el.$pwd.val() != '') {
						self.el.$submit.removeAttr('disabled'); // Enable 'OK' button
					} else {
						self.el.$submit.attr('disabled', 'disabled'); // Disable 'OK' button
					}
					return true;
					
				});
				
				
				// ! Detect the user's activity
				// - If the user is still active,
				//   do not count.
				
				// After 1 second: start counting down
				$(document).idleTimer(1000);
				
				// User gets idle
				$(document).on('idle.idleTimer', function(){
					// If lock screen is not shown
					if (!self.el.$lock.is(':visible')) {
						self.start();
					}
				});
				
				// User gets active again
				$(document).on('active.idleTimer', function(){
					// If lock screen is not shown
					if (!self.el.$lock.is(':visible')) {
						self.stop();
					}
				});
				
				// If user switches to another browser tab, lock screen
				mango.config.lock.lockWhenInactive && document.addEventListener(self.pageVisibility.visibilityChange, function(){
				
					// If the browser tab has gone inactive
					if (document[self.pageVisibility.hidden]) {
					
						self.stop(); // Stop counting
						self.el.$display.text('--:--'); // Clear counter display
						self.open(); // Show lock screen
						
					}
					
				}, false);
				
				// ! Set up the toolbar button
				$('#btn-lock').click(function(){
				
					self.stop(); // Stop counting
					self.el.$display.text('--:--'); // Clear counter display
					self.open(); // Show lock screen
					
					return false;
				});
			
				// Start couting
				self.start();
				
				// Ready
				self.initialized = true;
				
			}, // End of 'init'
			
			// ! Start counting down
			// - This will reset all timers
			//   we have set and start counting
			start: function(){
			
				self = this;
				
				if (!self.el) {
					self.init();
				}
			
				// Set up shorthands
				var timeout = mango.config.lock.timeout; // Shorthand
				var self = self; // Shorthand used in callbacks							
				
				// Set up the display
				self.el.$display.data('t', timeout);
				self.el.$display.text(self.utils.formatSeconds(timeout));
				
				
				// Clear the old timer to set up a new one afterwards
				clearInterval(self.timerId);
				
				// Set up the new timer
				self.timerId = setInterval(function(){
				
					var t = self.el.$display.data('t'); // Get the seconds left
					t--; // t-- : We're counting down
					
					// If we're done with counting
					if (t == 0) {
					
						// Show the lock screen!
						self.open();
						
						// ... turn off the timer ...
						self.stop();
						
						// ... and clear the counting display
						self.el.$display.text('--:--');
						
					// Continue counting
					} else {
					
						// Display the current timer value...
						self.el.$display.text(self.utils.formatSeconds(t));
						
						// ... and store the decreased value
						self.el.$display.data('t', t);
						
					} // End of 't = 0'
					
				}, 1000); // End of 'self.timerId = setInterval ...'
				
			}, // End of 'start'
			
			
			// ! Turn off the timer
			stop: function() {
			
				self = this;
			
				// Stop counting
				clearInterval(self.timerId);
				
				// Show the total waiting time again
				self.el.$display.text(
				
					// Format seconds left
					self.utils.formatSeconds( 
						mango.config.lock.timeout
					)
					
				); // End of '$display.text(...)'
				
			}, // End of 'stop'
			
			// Utilities
			utils: {
			
				// ! Format seconds into mm:ss
				formatSeconds: function(seconds) {
					var minutes = 0;
					while(seconds >= 60) {
						minutes++;
						seconds -= 60;
					}
					return new Number(minutes).numberFormat('00') + ':' + new Number(seconds).numberFormat('00');
				}
				
			}, // End of 'util'
		
			
			// ### Variables
			
			// ! Store the timer id
			timerId: -1
			
		}, $$[PLUGIN_NAME]); // End of '$.extend(self, { ... })'
		
		// Publish members		
		function pub() {
		}
		
		$.extend(pub, {
			publics: _.chain(self).keys().filter(_.bind(_.contains, _, publics)),
		
			get: function(prop){
				if (_.contains(publics, prop)) {
					if (_.isFunction(self[prop])) {
						return self[prop].bind(self);
					} else {
						return self[prop];
					}
				}
			},
			
			set: function(prop, val) {
				if (_.contains(publics, prop)) {
					self[prop] = val;
				}
			}
		});
		
		return pub;
	}; // End of 'function self() { ... }'

})(jQuery, $$, _, this, document);










// ! Colorizable Progress Bars
// - New options:
//     - color: string (blue)
//         One of: blue, red, orange, grey, auto (based on percentage)
//         If auto:
//          - maxNormal: float
//              All values above this will be marked as 'warn level'
//          - maxWarn: float
//              All values above this will be marked as 'critical level'
//     - colorize: boolean (true)
//         Wether to add the color class or not
//     - animated: boolean (false)
//         Show an animated progress bar

(function($, window, undefined){
	
	// Proxy Pattern
	var ui_progress = $.ui.progressbar.prototype._init;
	
	$.ui.progressbar.prototype.options.color = 'blue';
	$.ui.progressbar.prototype.options.colorize = true;
	$.ui.progressbar.prototype.options.animate = false;
	
	$.ui.progressbar.prototype.options.maxNormal = 60;
	$.ui.progressbar.prototype.options.maxWarn = 90;

	// Overwrite _init
	$.ui.progressbar.prototype._init = function(){
	
		var self = this;
		var options = this.options, $el = this.element;
		var colors = ['blue', 'orange', 'red', 'grey', 'auto'];
		
		if (options['color'] && options.colorize) {
			if (colors.indexOf(options['color']) == -1) {
				options['color'] = $.ui.progressbar.prototype.options.color;
			}
		}
		
		if (options.color == 'auto') {
			this.element
				.bind('progressbarchange', function(e) {
					
					// Calculate percent values
					var percent = parseInt(self.options.value / self.options.max * 100);
					
					$el.removeClass('blue green orange red');
					
					// Colorize bars
					if (percent < self.options.maxNormal){
						$el.addClass('blue');
					} else if (percent < self.options.maxWarn){
						$el.addClass('orange');
					} else {
						$el.addClass('red');
					}
					
				})
				.trigger('progressbarchange');
		}
		
		if (options.colorize && options.color != 'auto') {
			$el.addClass(options.color);
		}
		
		if (options.animate) {
			$el.addClass('animated');
		}
		
		ui_progress.apply(this, arguments);
		
	} // End of '_init = function(){...}'

})(jQuery, this);










// ! Animatable Progress Bars
// - New options:
//     - fx:
//         - animate: boolean (false)
//             Animate progress bar or not?
//         - start: string/Date ('now')
//             When to start the animation.
//             Possible values:
//               - 'now': Start immediatly
//               - Date: Use a Date object to set the start time
//         - duration: int
//             How long (seconds) to run
//         - finish: Date
//             When to stop/finish the animation
//         - refresh: int (16)
//             Refresh intervall in ms
// - Note:
//   Finish or duration has to be set!
// - TODO: User-defined start and finish (not only 0 and 100)

(function($, window, undefined){
	
	// Proxy Pattern
	var ui_progress = $.ui.progressbar.prototype._init;
	
	$.ui.progressbar.prototype.options.fx = {
		animate: false,
		start: 'now',
		duration: undefined,
		finish: undefined,
		refresh: 16 // ~1/60s
	};

	$.ui.progressbar.prototype._init = function(){

		var opts = this.options.fx,
			$el = this.element;	
			
		if (!opts.animate) {
			// Nothing special
			return ui_progress.apply(this, arguments);
		}
		
		var error = function(msg){
			$.error('Progressbar: ' + msg);
			return ui_progress.apply(this, arguments);
		}
		
		// ! Parse options
		// - Check option 'start'
		
		// start = 'now'
		if (opts.start == 'now' || !(opts.start instanceof Date)) {
		
			opts.start = new Date();
		
		// start = new Date ...
		} else if (opts.start instanceof Date) {
					
			// If requested to start in the past...
			if (opts.start < new Date()) {
				return error('Cannot start in the past');
			}
		
		}
		
		// Check option 'duration' or 'finish'
		if (!isNaN(Number(opts.duration))) {
		
			opts.finish = new Date(
				opts.start.getTime() + Number(opts.duration) * 1000
			);
			
		} else if (opts.finish instanceof Date) {
			
			// Anything to check?
		
		} else {
			return error('No valid duration or finish time given!');
		}
		
		if (opts.finish <= opts.start) {
			return error('End time before start time? O.o');
		}
		
		// ! The interesting part ;)
		this.options.max = 100;
		this.options.value = 0;
		
		var duration = opts.finish - opts.start,
			now = new Date(),
			$elVal = this.element.find('.ui-progressbar-value'),
			
			init = false, elapsed = 0, progress = 0, timerId = -1; // For update()
		
		var update = function(){		
			if (!init && progress > 0) {
				init = true;
				$elVal.show();
			}
		
			elapsed = new Date() - opts.start;			
			progress = elapsed / duration * 100;
			progress = progress >= 0 ? progress : 0;
			
			$elVal.css('width', progress + '%'); // Short circuit: this.value(...) is too slow!
				
			// If ready
			if (progress >= 100) {
			
				clearInterval(timerId);
				this.value(100);
				
			}
			
		}.bind(this) // End of 'update = function(){...}'
		
		if (now < opts.start) {
			setTimeout(function(){
				timerId = setInterval(update.bind(this), opts.refresh);
			}, opts.start - now - 10); // - 10 in case of lagging or inaccuracy of setTimeout
		} else {
			timerId = setInterval(update.bind(this), opts.refresh);
		}
		
		ui_progress.apply(this, arguments);		
		
	} // End of '_init = function(){...}'

})(jQuery, this);










// ! Titeled Progress Bars
// - New options:
//     - color: string (blue)
//         May be: 'auto' --> computed color based on percentage
//     - showTitle: string (none)
//         What to show as the progressbar title:
//               - title: The title defined by the title option
//               - percentage: The current percentage value
//     - showValue: boolean (false)
// - Note:
//   If showPercentage and showTitle both are set to true,
//   the custom title will be shown

(function($, window, undefined){
	
	// Proxy Pattern
	var ui_progress = $.ui.progressbar.prototype._init;
	
	$.ui.progressbar.prototype.options.showtitle = 'none'
	$.ui.progressbar.prototype.options.showvalue = false

	$.ui.progressbar.prototype._init = function(){

		var opts = this.options,
			$el = this.element;
		
		var $title = $('<div>', {
			'class': 'progress-title'
		});
		
		// Show title
		if (opts.showtitle == 'title') {
			$title.text($el.data('title')).insertBefore($el);
		} else if (opts.showtitle == 'percentage') {
			$title.text(parseInt(this.options.value / this.options.max * 100) + '%').insertBefore($el);
		}
		
		// Show value
		if (opts.showvalue) {
			opts.format = opts.format || '';
			var format = function(){
				return new Number(opts.value).numberFormat(opts.format) + ' / ' + new Number(opts.max).numberFormat(opts.format);
			}
			$('<div>', {
				'class': 'progress-value',
				text: format()
			}).insertAfter($el);
		}
		
		ui_progress.apply(this, arguments);		
		
	} // End of '_init = function(){...}'

})(jQuery, this);










// ! Charts
// - An extension to flot, make it easier to use.
//
// - Options: see defaults in code below
//     - Note: all options can be set via
//             data-attributes
//     - Note: The priority of the options is:
//               1. data-tags (data-source='...' etc.)
//               2. $(...).chart(options)
//
// - Methods:
//     Methods can be called via $(...).chart('method name');
//     - func: A common function
//     - another_func: Another function
//
// - TODO:
//     - Test Bars (normal/stacked)

(function($, _, window, document, undefined) {
	
	var PLUGIN_NAME = 'chart', ns = '.mango_' + PLUGIN_NAME;
	
	// Instances will be stored here
	var instances = [];
	
	// The publically callable function
	$.fn[PLUGIN_NAME] = $.extend(function (method, options) {
		
		var ret = this,
			args = arguments;
		
		$(this).each(function(){
			
			var inst,
				$el = $(this);
						
			// ! Create instance		
			if (inst = instances.filter(function(o){return o.el[0] == $el[0];})[0]) {inst = inst.inst}
			else { instances.push({ el: $el, inst: inst = self() }); }
			
			// ! Parse arguments
			if (typeof method === "object") {
				options = method;
				method = undefined;
			}
			
			// - Default method is 'init'
			method = method || 'init';
			
			// Warning if plugin was not initialized	
			method != 'init' && !inst.initialized
				&& $.error('$.fn.' + PLUGIN_NAME + ' was not initialzed. Please call $.fn.' + PLUGIN_NAME + '(options) first.');
				
			// ! Call the requested method
			if ($.isFunction(inst.get(method))) {
			
				// - Get options
				var opts;
				
				// 'method' was not given, arguments contains all options
				if (args[0] == options) {
					opts = $.makeArray(args);
					
				// arguments[1,2,3,...] contains the optinos
				} else if (args.length > 1) {
					opts = Array.prototype.slice.call(args, 1);
				
				// Only options is given
				} else {
					opts = [options];
				}
			
				var fret = inst.get(method).apply($el, opts);
				if (!_.isUndefined(fret)) {
					ret = fret;
				}
				
			// ! Return property value
			} else if (inst.get(method)){
			
				var prop = arguments[0], val = arguments[1];
			
				// Dynamical getter & setter
				if (!val) {
					ret = inst.get(prop);
				} else {
					inst.set(prop, val);
				}
				
			} else {
			
				// Method or property not found
				$.error('Method or property ' +  method + ' does not exist on jQuery.fn.' + PLUGIN_NAME);
				
			}
		
		});
		
		return ret;
		
	}, {
		// Accessable via $.fn.PLUGIN_NAME[name]
		
		// ! Default settings
		defaults: {
			data: [],		// The data
		
			height: null,	// The height of the chart
		
			type: 'lines',	// One of 'bars', 'lines', 'pie'
			points: true, 	// Show data points?
			stacked: false,	// Stack lines/bars?
			legend: true,	// Show legend?
			
			fill: null,		// Wether to fill the charts // TODO: what's that?
			donut: false,	// Pie: inner radius (=donut)
			horizontal: false,	// Bars: horizontal?
			
			colors: ['#88bbc8', '#ee7951', '#bc71f', '#e5c700'], // Colors of the lines
			
			tooltip: function(label, xlabel, xval, yval){
				return (label ? (label + " at ") : '') + (this.options.flot.xaxis.ticks || (this.options.horizontal && this.options.flot.yaxis.ticks) ? (xlabel != '' ? xlabel : xval.toFixed(2)) + " = " : '') + yval.toFixed(2);
			}, // Tooltip label: function or pattern string (%label%, %xval%, %yval%) or false
			
			// Event handlers
			events: {
				click: function(id, value, obj){},
				hover: function(id, value, obj){}
			},
			
			// Source: Table
			series: 'rows',		// Direction of the data flow: 'rows' or 'columns'?
			showTable: false,	// Wether to show the source table or not
			
			// Flot defaults:
			flot: {}
		} // End of 'defaults'
		
	});
	
	function self() {
		
		// Define public members (accessable via $.fn.PLUGIN_NAME[name])
		var me = {},
			publics = ['init', 'draw'];
		$.extend(me, {
			
			// ### Functions
		
			// ! Initialize the plugin
			init: function(opts){
			
			
				var $this = me.$input = $(this);
				me.options = $.extend(true, {}, me.defaults, opts, $this.data()); // Combine defaults and options
				
				var xlabels = [];
				
				// ! Check options
				if (!$.isArray(me.options.colors)) {
					me.options.colors = $.parseJSON(me.options.colors, true);
				}
				
				// ! Set up container
				var $container = $('<div>', {'class': 'chart', height: me.options.height || $this.parent().height()}).insertBefore($this);
				$this.appendTo($container);
				
				// ! Set up source table
				if ($this.is('table')) {
					var $table = $this;
				
					// Parse table
					var parsed = me.parseTable($this);
					me.options.data = parsed.data;
					me.xlabels = parsed.xlabels;
					
					// Insert new container
					$this = me.$input = $('<div>', {
						'class': 'graph'
					});
					$this.insertAfter($table);

				}
				
				// ! Set up legend
				me.options.legend && (me.$legend_box = $('<div>', {'class': 'legend'}).insertBefore($this));

				// ! Set height of the graph
				var paddingTopBottom = parseInt($this.parent().css('padding-top')) + parseInt($this.parent().css('padding-bottom'));

				if (me.options.height) {
					$this.height(me.options.height);
				} else {
					$this.height(
						$this.parent().height()
						- paddingTopBottom
						- (me.options.legend ? me.$legend_box.height() : 0)
						- (me.options.showTable ? $table.height() : 0)
					);
				}
				
				// Remove table
				$table && !me.options.showTable && $table.remove();
				
				
				// ! Set up events
				me.lastHoverItem = null;
				
				me.events = {				
					'plotclick': function(event, pos, item){
						if (item) {
							switch (me.options.type) {
								case 'bars':
									me.options.events.click.call($this[0], item.seriesIndex, item.datapoint[1], item)
									break;
								case 'pie':
									me.options.events.click.call($this[0], item.seriesIndex, item.datapoint[1][0][1], item)
									break;
								default:
									me.options.events.click.call($this[0], item.seriesIndex, item.datapoint[1], item)
							}
						}
					}, // End of 'plotclick'
					
					'plothover': function(event, pos, item){
						if (item) {
							// ! Fire hover event
							switch (me.options.type) {
								case 'bars':
									me.options.events.hover.call($this[0], item.seriesIndex, item.datapoint[1], item)
									break;
								case 'pie':
									me.options.events.hover.call($this[0], item.seriesIndex, item.datapoint[1][0][1], item)
									break;
								default:
									me.options.events.hover.call($this[0], item.seriesIndex, item.datapoint[1], item)
							}
							
							if (me.options.tooltip !== false) {
								// ! Set up tooltip
								if (me.lastHoverItem == item.datapoint.toString()) {
									return;
								}
								me.lastHoverItem = item.datapoint.toString();
								
								$('.chart-tooltip').remove();
								
								// ! Get required information
								var xval = item.datapoint[0];
								
								// Correct x value for bars
								if (item.series.bars.order && !me.options.horizontal){
									for (var i = 0; i < item.series.data.length; i++){
										if (item.series.data[i][3] == item.datapoint[0]) {
											xval = item.series.data[i][0];
										}
									}
								} else if (me.options.horizontal){
									for (var i = 0; i < item.series.data.length; i++){
										if (item.series.data[i][3] == item.datapoint[1]) {
											xval = item.series.data[i][1];
										}
									}
								}
								
								// Get y value
								var yval = parseInt(item.datapoint[1]);
								if (me.options.horizontal) {
									yval = parseInt(item.datapoint[0]);
								}
								
								// Get label
								var xlabel = '';
								if (me.options.type == 'bars') {
									xlabel = item.series.xaxis.ticks && item.series.xaxis.ticks[item.dataIndex].label;
									if (me.options.horizontal) {
										xlabel = item.series.xaxis.ticks && item.series.yaxis.ticks[item.dataIndex].label;
									}
								} else if (me.options.type == 'pie') {
									xlabel = item.series.xaxis.options.ticks && item.series.xaxis.options.ticks[0][1];
								} else {
									xlabel = item.series.xaxis.ticks && item.series.xaxis.ticks[item.datapoint[0]].label;
								}
								
								if (!xlabel) {
									xlabel = ''
								}
								
								// ! Setup tooltip text
								var text = '';
								if ($.isFunction(me.options.tooltip)) {
									text = me.options.tooltip.call(me, item.series.label, xlabel, xval, yval);
								} else {
									text = me.options.tooltip.replace('%label%', item.series.label).replace('%xlabel%', xlabel).replace('%xval%', xval).replace('%yval%', yval);
								}
								
								// ! Setup tooltip
								var $tooltip = $('<div>', {
									'class': 'chart-tooltip',
									text: text
								});
								
								var posX = item.pageX;
								var posY = item.pageY;
								
								if (me.options.type == 'pie') {
									$(document).mousemove(function(e){
										$tooltip.css({
											top: e.pageY + 5,
											left: e.pageX + 5
										});
									});
								} else if (me.options.type == 'bars') {
									$tooltip.css({
										top: posY - 23,
										left: posX - 10
									});
								} else {
									$tooltip.css({
										top: posY + 5,
										left: posX + 5
									});
								}
								
								$tooltip.appendTo('body').fadeIn(100);
							}
						} else {
							$('.chart-tooltip').remove();
							me.lastHoverItem = null;
						}
					} // End of 'plothover'
					
				}; // End of 'me.events'

				
				// ! Set up the event handlers
				for (var key in me.events) {
					$this.on(key + ns /* -> namespacing ftw */, me.events[key]);
				}
				
				me.setupChart();
				me.draw();
			
				// Ready
				me.initialized = true;
				
			}, // End of 'init'

			// ! Parse the source table
			parseTable: function($this){
				var ret = {
					data: [],
					xlabels: []
				}
			
				// Parse table
				if (me.options.series == 'columns') {
					var $labels = $this.find('tbody th'),	// Labels for x-values
						$legend = $this.find('thead th'),	// Title of the line
						$rows = $this.find('tbody tr');		// The data
					
					// Ignore very first cell:
					// (     ) | Title 1 | Title 2
					// Label 1 |   1     |    2
					// Label 2 |   1     |    2
					if ($legend.length) {
						$legend = $legend.slice(1);
					}
					
					var rowData = [];
					
					// Fetch data
					$rows.each(function(_, row){
						$(row).find('td').each(function(i, td) {
							var data = td.innerHTML;
							rowData[i] = rowData[i] || [];
							
							// Collect data
							if (!isNaN(data)) {
								rowData[i].push([_, parseInt(data) || 0]);
							}
						});						
					});
					
					// Collect label information
					$rows.eq(0).find('td').each(function(i, r){
						ret.xlabels.push([i, $labels.eq(i).text()]);
					});
					
					// Store data
					for (var i = 0; i < $legend.length; i++) {
						ret.data.push({
							'label': $legend.eq(i).text(),
							'data': rowData[i]
						});
					}
					
				} else { // 'rows'
					var $labels = $this.find('thead th'),	// Labels for x-values
						$legend = $this.find('tbody th'),	// Title of the line
						$rows = $this.find('tbody tr');		// The data
					
					// Ignore very first cell:
					// (     ) | Label 1 | Label 2
					// Title 1 |   1     |    2
					if ($legend.length) {
						$labels = $labels.slice(1);
					}
					
					// Fetch data
					$rows.each(function(_, row){
						var rowData = [];
					
						$(row).find('td').each(function(i, td) {
							var data = td.innerHTML;
							
							// Collect data
							if (!isNaN(data)) {
								rowData.push([i, parseInt(data) || 0]);
							}
						});
						
						// Store data
						ret.data.push({
							'label': $legend.eq(_).text(),
							'data': (me.options.type != 'pie') ? rowData : rowData[0][1]
						});
					});
					
					// Collect label information
					$rows.eq(0).find('td').each(function(i, r){
						ret.xlabels.push([i, $labels.eq(i).text()]);
					});
				} // End if type
				
				return ret;
			}, // End of 'parseTable'
			
			// ! Set up chart types
			setupChart: function(){
				if (me.options.type == 'bars') {
					var opts = me.options;
					
					// Set order
					if (!me.options.stacked) {
						var series, i = 1;
						for (series in opts.data) {
							var s = opts.data[series];
							$.extend(true, s, {
								bars: {
									order: i++
								}
							});
						}
					}
					
					// Swap x/y for horizontal charts
					// @see: http://stackoverflow.com/questions/9944336/flot-stacked-horizontal-bar-not-formatting-correctly
					if (opts.horizontal) {
						var series, i;
						for (series in opts.data) {
							var s = opts.data[series];
							if (s.data) {
								for (i = 0; i < s.data.length; i++) {
									var tmp = s.data[i][0];
									s.data[i][0] = s.data[i][1];
									s.data[i][1] = tmp;
								}
							}
						}
					}
						
					$.extend(true, opts.flot, {
						series: {
							points: {
								show: false
							},
							bars: {
								align: 'center',
								order: (opts.stacked) ? null : true, // TODO: why?
								show: true,
								border: false,
								fill: true,
								fillColor: null,
								horizontal: opts.horizontal,
								barWidth: (opts.stacked) ? 0.6 : 0.6 / opts.data.length,
								lineWidth: 0
							},
							lines: {
								show: false
							},
							pie: {
								show: false
							}
						}
					});
				} else if (me.options.type == 'pie') {
					var opts = me.options;
					$.extend(true, opts.flot, {
						series: {
							points: {
								show: true
							},
							bars: {
								show: false
							},
							lines: {
								show: false
							},
							pie: {
								show: true,
								label: true,
								tilt: 1,
								innerRadius: opts.donut ? opts.donut : 0 ,
								radius: 1
							}
						}
					});
				} else {
					var opts = me.options;
					$.extend(true, opts.flot, {
						series: {
							points: {
								show: opts.points
							},
							bars: {
								show: false
							},
							lines: {
								show: true,
								lineWidth: 2,
								fill: (opts.fill == false) ? false : true,
								fillColor: (opts.fill == false) ? null : { colors: [ { opacity: 0 }, { opacity: 0.15 } ] }
							},
							pie: {
								show: false
							}
						}
					});
				} // End of if type
				
				// General settings
				me.options.flot = $.extend(true, {}, {
					series: {
						stack: (me.options.stacked) ? true : null
					},
					shadowSize: 0,
					grid: {
						hoverable: me.options.tooltip !== false,
						clickable: true,
						color: '#ededed',
						markingsColor: '#939393',
						borderWidth: null
					},

					legend: {
						show: opts.legend,
						position: 'ne',
						container: me.options.legend ? me.$legend_box : null
					},
					colors: me.options.colors,
					xaxis: {
						color: '#939393',
						labelWidth: 30
					},
					yaxis: {
						color: '#939393'
					}
				}, me.options.flot);
				
				if (me.xlabels && !me.options.horizontal) {
					me.options.flot.xaxis.ticks = me.xlabels;
				} else if (me.options.horizontal) {
					// Swap x/y labels for horizontal charts
					me.options.flot.yaxis.ticks = me.xlabels;
				}
				
			}, // End of 'setupCharts'
			
			// ! Draw the chart/graph
			draw: function(){
				$.plot(me.$input, me.options.data, me.options.flot);
			}, // End of 'func'
		
			
			// ### Variables
			
			// ! Has the plugin already been initialized?
			initialized: false
			
		}, $.fn[PLUGIN_NAME]); // End of '$.extend(me, { ... })'
		
		// Publish members		
		function pub() {
		}
		
		$.extend(pub, {
			publics: _.chain(me).keys().filter(_.bind(_.contains, _, publics)),
		
			get: function(prop){
				if (_.contains(publics, prop)) {
					return me[prop];
				}
			},
			
			set: function(prop, val) {
				if (_.contains(publics, prop)) {
					me[prop] = val;
				}
			}
		});
		
		return pub;
	}; // End of 'function self() { ... }'

})(jQuery, _, this, document);











// ! Fullstats
// - Gives a set of different statistic elements
//
// - Options: see defaults in code below
//     - Note: $([...]).fullstats(); is called on the
//             container of the stat element
//     - Note: all options can only be set via
//             data-attributes
//     - Example:
//             call $('#fullstats').fullstats(); where
//             <div id=fullstats>
//                 <h2>[Title]</h2>
//                 <div class="stat [type]" data-[option]=[value]></div>
//             </div>

(function($, _, window, document, undefined) {
	
	var PLUGIN_NAME = 'fullstats', ns = '.mango_' + PLUGIN_NAME;
	
	// Instances will be stored here
	var instances = [];
	
	// The publically callable function
	$.fn[PLUGIN_NAME] = $.extend(function (method, options) {
		
		var ret = this,
			args = arguments;
			
		var inst,
			$el = $(this);
					
		// ! Create instance		
		if (inst = instances.filter(function(o){return o.el[0] == $el[0];})[0]) {inst = inst.inst}
		else { instances.push({ el: $el, inst: inst = self() }); }
		
		// ! Parse arguments
		if (typeof method === "object") {
			options = method;
			method = undefined;
		}
		
		// - Default method is 'init'
		method = method || 'init';
		
		// Warning if plugin was not initialized	
		method != 'init' && !inst.initialized
			&& $.error('$.fn.' + PLUGIN_NAME + ' was not initialzed. Please call $.fn.' + PLUGIN_NAME + '(options) first.');
			
		// ! Call the requested method
		if ($.isFunction(inst.get(method))) {
		
			// - Get options
			var opts;
			
			// 'method' was not given, arguments contains all options
			if (args[0] == options) {
				opts = $.makeArray(args);
				
			// arguments[1,2,3,...] contains the optinos
			} else if (args.length > 1) {
				opts = Array.prototype.slice.call(args, 1);
			
			// Only options is given
			} else {
				opts = [options];
			}
		
			var fret = inst.get(method).apply($el, opts);
			if (!_.isUndefined(fret)) {
				ret = fret;
			}
			
		// ! Return property value
		} else if (inst.get(method)){
		
			var prop = arguments[0], val = arguments[1];
		
			// Dynamical getter & setter
			if (!val) {
				ret = inst.get(prop);
			} else {
				inst.set(prop, val);
			}
			
		} else {
		
			// Method or property not found
			$.error('Method or property ' +  method + ' does not exist on jQuery.fn.' + PLUGIN_NAME);
			
		}
		
		return ret;
		
	}, {
		// Accessable via $.fn.PLUGIN_NAME[name]
		
		// ! Default settings
		defaults: {
		
			selector: '.stat',
			
			simple: {
				value: 0,
				format: '0',
				title: ''
			},
			
			minichart: {
				values: [],
				
				total: 0,
				format: '0',
				title: '',
				
				change: null,
				
				color: ''
			},
			
			load: {
				value: 0,
				min: 0,
				max: 100,
				format: '%'
			},
			
			level: {
				value: 50,
				max: 100,
				format: '',
				description: ''
			},
			
			pillar: {
				data: [],
				format: ''
			},
			
			circular: {
				value: 0,
				max: 100,
				format: '%',
				valueformat: '',
				
				width: 5,
				color: '',
				
				list: null,
				displaymax: true
			},
			
			uptime: {
				servers: [],
				left: 'lastDowntime',
				right: 'response',
				
				lang: {
					online: 'Online',
					offline: 'Offline',
					
					lastDowntime: 'Last downtime',
					response: 'Response time'
				}
			},
			
			list: {
				list: [],
				color: ''
			},
			
			hlist: {
				list: [],
				flexiwidth: false
			}
		
		} // End of 'defaults'
		
	});
	
	function self() {
		
		// Define private members (NOT accessable via $.fn.PLUGIN_NAME[name])
		var me = {},
			privates = ['format', 'formatPosNeg'];
		
		$.extend(me, {
			
			// ### Functions
		
			// ! Helpers:
			
			// - Format a number according to a format string
			format: function(val, format){
				if (format == '%') {
					return val + '%';
				} else if (format == '+-' || format == '-+') {
					return me.formatPosNeg(val);
				} else if (format == '+-%' || format == '-+%') {
					return me.formatPosNeg(val) + '%';
				}
			
				return new Number(val).numberFormat(format);
			},
			
			// - Format a positive/negative number
			formatPosNeg: function(val){
				return (val != 0 ? me.format(val, '+0;-0'): val);
			},
			
			// ! Initialize the plugin
			init: function(opts){
				
				var $this = me.$element = $(this);
				me.options = $.extend(true, {}, me.defaults, opts, $this.data()); // Combine defaults and options
				
				// Find stat elements
				$this = _.map($this, function(el){
					var $el = $(el);
					return $el.is(me.options.selector) ? $el : $el.find(me.options.selector);
				});
				
				// Initialize all stat elements
				_.each($this, function(el){
					_.each(el[0].classList, function(type){
						if (_.isFunction(me[type])) {
							me[type]($(el));
						}
					});
				});
					
				
				// Ready
				me.initialized = true;
				
			}, // End of 'init'

			// ! Stats: Simple
			simple: function($stat){
				
				var opts = $.extend({}, me.options.simple, $stat.data());
				
				// Fix js-evaluation of data-format
				if (opts.format === 0) {
					opts.format = $stat.attr('data-format');
				}
				
				// Collect data
				var value = me.format(opts.value, opts.format);
				var title = opts.title;
				
				// Positive/negative value
				if (/^\+/.test(value)) {
					$stat.addClass('positive')
				} else if (/^\-/.test(value)) {
					$stat.addClass('negative')
				}
				
				// Build html				
				var html = '<div class=title>' + title + '</div>';
				html += '<div class=value>' + value + '</div>';
				
				// Insert html
				$stat.insert(html);
				
				// Is link?
				if ($stat.is('a')) {
					$stat.addClass('link');
				}
				
			}, // End of 'func'
			
			
			// ! Stats: Minichart
			minichart: function($stat){
			
				var opts = $.extend({}, me.options.minichart, $stat.data());
			
				// Fix js-evaluation of data-format
				if (opts.format === 0) {
					opts.format = $stat.attr('data-format');
				}
				
				// Parse values option
				if (!$.isArray(opts.values)) {
					opts.data = $.parseJSON(opts.values);
				}
				
				// Positive/negative value
				if (/^\+/.test(opts.total)) {
					$stat.addClass('positive')
				} else if (/^\-/.test(opts.total)) {
					$stat.addClass('negative')
				}
				
				// Build html				
				var html = '<div class=left><div class=title>' + opts.title + '</div>';
				html += '<div class=total>' + me.format(opts.total, opts.format) + '</div></div>';
				html += '<div class=right><div class=minichart></div>';
				html += _.isNumber(opts.change) ? '<div class=change>' + me.format(opts.change, '+-%') +'</div>' : '';
				
				// Insert html
				$stat.insert(html);
				
				// Create Chart
				// - Get color
				if (opts.change == 0 && !opts.color) {
					opts.color = 'grey';
				} else if (opts.change > 0 && !opts.color) {
					opts.color = 'green';
				} else if (opts.change < 0 && !opts.color) {
					opts.color = 'red';
				}
				
				var color = '';
				
				(function(){
					var $el = $('<div class="circular-fg ' + (opts.color || '') + '">').appendTo('body');
					color = $el.css('color');
					$el.remove();
				})();
				
				$stat.find('.minichart').sparkline(opts.values, {type: 'bar', barColor: color, negBarColor: color, disableTooltips: true})
				
				$stat.find('.change').addClass(opts.color);
				$stat.find('.total').addClass(opts.color);
				
				// Equal height of .left and .right
				$stat.children().equalHeight();

				
			}, // End of 'another_func'
		
			// Type: Load
			load: function($stat){
				var opts = $.extend({}, me.options.load, $stat.data());
				
				// Fix js-evaluation of data-format
				if (opts.format === 0) {
					opts.format = $stat.attr('data-format');
				}
				
				// Recalc values if needed				
				if (opts.value > opts.max) {
					opts.value = opts.max;
				}
				
				// Build html
				var value = me.format(opts.value, opts.format),
					max = me.format(opts.max, opts.format),
					min = me.format(opts.min, opts.format);
				
				var html = '<div class=value>' + value + '</div>';
				html += '<div class=bg></div>';
				html += '<div class=gauge></div>';
				html += '<div class=min>' + min + '</div>';
				html += '<div class=max>' + max + '</div>';
				
				// Insert html			
				$stat.insert(html);
				
				$stat.find('.gauge').rotate(180 * (opts.value / opts.max));

			},
			
			// Type: Level
			level: function($stat){
				var opts = $.extend({}, me.options.level, $stat.data());
				
				// Build html
				// - Build gauge			
				var html = '<div class="gauge-container">'
					+ '<div class="gauge' + (opts.value == opts.max ? ' full' : '') + '" style="height:' + (opts.value / opts.max * 100) + '%"></div>'
				+ '</div>';
				$stat.insert(html);
				
				// - Build info			
				var htmlInner = '<div class=max>' + me.format(opts.max, opts.format) + '</div>';
				htmlInner += '<div class=info>'
					+ '<div class=value>' + me.format(opts.value, opts.format) + '</div>'
					+ '<div class=description>' + opts.description + '</div>'
				+ '</div>';
				
				$stat.insert(htmlInner);
				
				// Set top of info box
				// TODO: without JS, CSS only (see pillar)
				var gaugeContainerHeight = $stat.find('.gauge-container').height();
				$stat.find('.info').css('top', Math.min(gaugeContainerHeight + 10, 35 + gaugeContainerHeight - $stat.find('.gauge').height()));

			},
			
			// Type: Pillar
			pillar: function($stat){
				var opts = $.extend({}, me.options.pillar, $stat.data());
				
				if (!$.isArray(opts.data)) {
					opts.data = $.parseJSON(opts.data);
				}
				
				// Prepare html building
				var total = opts.data.map(function(a){
					return a.val
				}).reduce(function(a, b){
					return a+b;
				});
				
				// Build html
				var $pillar = $stat.insert('<div class="pillar-container"></div>');
				var pillarHeight = $pillar.height();
				
				var htmlInner = '';
				$.each(opts.data, function(){
					if (this.val <= 0) {
						return;
					}
				
					var height = pillarHeight * (this.val / total);
					
					// FIX: Webkit seems to have problems with comma values
					if ($.browser.webkit) {
						height = Math.round(height);
					}
				
					htmlInner += '<div class="inner ' + (this.color || 'green') + '" style="height:' + height + 'px">'
						+ '<div class=level></div>'
						+ '<div class=value>' + me.format(this.val, opts.format) + '</div>'
						+ '<div class=title>' + this.title + '</div>'
					+ '</div>';
				});
				
				// Insert html
				$pillar.insert(htmlInner);
				
				// Set width
				var $values = $pillar.find('.value');
				var valuesRightMax = Math.max.apply(Math, $values.map(function () { var $el = $(this); return $el.position().left + $el.width() }).get()) + parseInt($values.css('padding-right'));
				var $titles = $pillar.find('.title').css('left', valuesRightMax + 'px')
				
				var titlesMaxWidth = Math.max.apply(Math, $titles.map(function(){ return $(this).width(); }).get());
				$pillar.css('margin-right', valuesRightMax + titlesMaxWidth - $pillar.find('.inner').width() + 'px');
				
			},
			
			// Type: Circular
			circular: function($stat){
				var opts = $.extend({}, me.options.circular, $stat.data());
				
				// Fix js-evaluation of data-format
				if (opts.format === 0) {
					opts.format = $stat.attr('data-format');
				}
				
				if (opts.valueformat === 0) {
					opts.valueformat = $stat.attr('data-valueformat');
				}
				
				// Build values html
				
				// - Calculate value out of list
				if (opts.list) {
					if (!$.isArray(opts.list)) {
						opts.list = $.parseJSON(opts.list);
					}
					
					opts.value = opts.list.map(function(o){ return _.isNumber(o.percent) ? o.percent : o.val }).reduce(function(a,b){return a+b});
				}
				
				// - Build html
				var valueHtml = '<div class=value>';
					if (opts.list && opts.format == '%' && opts.value > 0) {
						valueHtml += '+';
					}
					
					valueHtml += me.format(opts.value, opts.format);
				
					if (opts.format != '%' && opts.displaymax) {
						valueHtml += '<small>/' + me.format(opts.max, opts.format) + '</small>';
					}
				valueHtml += '</div>';
				
				// Colors
				if (opts.value == 0 && !opts.color) {
					opts.color = 'grey';
				} else if (opts.list && opts.value > 0 && !opts.color) {
					opts.color = 'green';
				} else if (opts.list && opts.value < 0 && !opts.color) {
					opts.color = 'red';
				}
				
				opts.color && $stat.addClass(opts.color);
				
				var colorFg = '';
				var colorBg = '';
				
				(function(){
					var $fgEl = $('<div class="circular-fg ' + (opts.color || '') + '">').appendTo('body');
					var $bgEl = $('<div class="circular-bg ' + (opts.color || '') + '">').appendTo('body');
					
					colorFg = $fgEl.css('color');
					colorBg = $bgEl.css('color');
					
					$fgEl.remove();
					$bgEl.remove();
				})();
				
				// Draw canvas
				// - Prepare everything
				var $canvas = $stat.insert('<canvas></canvas>'),
					canvas = $canvas[0];
				
				// FIX: Canvas on IE8
				if (!canvas.getContext) // excanvas hack
					canvas = window.G_vmlCanvasManager.initElement(canvas);
					
				var ctx = $canvas[0].getContext('2d'),
					width = $canvas.width(),
					height = $canvas.height(),
					radius = height/2;
					
				canvas.height = height;
				canvas.width = width;
			
				var centerX = width/2,
					centerY = height / 2;
				
				var degToRad = function(deg){
					return deg * (Math.PI / 180);
				},
				drawCircle = function (deg, radius) {
					ctx.beginPath();
					ctx.moveTo(centerX, centerY);
					ctx.lineTo(centerX, 0);
					
					// FIX: 360� with Excanvas results in a thin line
					if (window.G_vmlCanvasManager && deg == 360) {
						ctx.arc(centerX, centerY, radius, degToRad(-90), degToRad(-90), false);
					} else {
						ctx.arc(centerX, centerY, radius, degToRad(-90), degToRad(-90 + deg), false);
					}
					
					ctx.closePath();
					ctx.fill();
				};

				ctx.clearRect(0, 0, width, height);
				
				// - Draw background circle
				ctx.fillStyle = colorBg;
				drawCircle(360, radius);
				
				// - Draw foreground circle
				if (opts.value != 0) {
					ctx.fillStyle = colorFg;
					drawCircle(360 * (Math.abs(opts.value) / opts.max), radius);
				}
				
				// - Draw inner circle
				ctx.fillStyle = '#fff';
				drawCircle(360, radius - opts.width);
				
				// Build html
				$stat.append(valueHtml);
				
				// - Build list (if given)
				if (opts.list) {
				
					var listHtml = '<ul>';
					
						$.each(opts.list, function(){
							var tClass = '';
							
							if (!_.isNumber(this.val) && _.isNumber(this.percent)) {
								this.val = this.percent;
								this.percent = undefined;
								opts.valueformat = '%';
							}
							
							if (this.val > 0) {
								tClass = 'pos';
							} else if (this.val < 0){
								tClass = 'neg'
							} else {
								tClass = 'neutr';
							}
							listHtml += '<li' + (tClass ? ' class=' + tClass : '') + '>';
								listHtml += '<span class=title>' + this.title + '</span>';
								listHtml += '<span class=value>' + (this.val > 0 ? '+' : '') + me.format(this.val, this.format || opts.valueformat) + '</span>';
								if (this.percent) {
									listHtml += '<span class=percent>' + me.formatPosNeg(this.percent) + '%</span>';
								}
							listHtml += '</li>';
						});
					
					listHtml += '</ul>';
					
					$stat.append(listHtml);
				}

			},
			
			// Type: Uptime
			uptime: function($stat){
				var opts = $.extend({}, me.options.uptime, $stat.data());
				
				// - Calculate value out of list
				if (opts.servers && !$.isArray(opts.servers)) {
					opts.servers = $.parseJSON(opts.servers);
				}
				
				// Build overview
				var stateHtml = '<div class=overview>';
					
					$.each(opts.servers, function(){
						stateHtml += '<div class=server>';
					
							stateHtml += '<div class="status ' + (this.online ? 'online' : 'offline') + '">'
								+ (this.online ? opts.lang.online : opts.lang.offline)
							+ '</div>';
							stateHtml += '<div class=name>' + this.name + '</div>';
							
						stateHtml += '</div>';
					});
				
				stateHtml += '</div>';
				
				$stat.insert(stateHtml);
				
				// Build info
				// - Build left
				var leftHtml = '<div class=left>';
					leftHtml += '<div class=title>' + opts.lang[opts.left] + '</div>';
					$.each(opts.servers, function(){
						leftHtml += '<div class=entry>' + (this[opts.left] || '&nbsp;') + '</div>';
					})
				leftHtml += '</div>';
				
				// - Build right
				var rightHtml = '<div class=right>';
					rightHtml += '<div class=title>' + opts.lang[opts.right] + '</div>';
					$.each(opts.servers, function(){
						(rightHtml += '<div class=entry>' + (this[opts.right] || '&nbsp;') + '</div>');
					})
				rightHtml += '</div>';
				
				// - Build info
				$stat.insert('<div class=info>' + leftHtml + rightHtml + '</div>');

			},
			
			// Type: List
			list: function($stat){
				var opts = $.extend({}, me.options.list, $stat.data());
				
				// Parse list option
				if (!$.isArray(opts.list)) {
					opts.list = $.parseJSON(opts.list);
				}
				
				var colors = {
					up: 'green',
					down: 'red'
				};
				
				// Build html
				var html = '<ul>';
					
					$.each(opts.list, function(){
						var classes = '';
						if (this.type) {
							classes += this.type;
							classes += ' ' + (this.color || colors[this.type]);
						}
						
						if (this.link) {
							classes += ' link'
						}
						
						var entry = '<li class="' + classes + '">';
							this.link && (entry += '<a href="' + this.link + '">');
							entry += '<div class=value>' + me.format(this.val, this.format || '') + '</div>';
							entry += '<div class=title>' + this.title + '</div>';
							this.link && (entry += '</a>');
						entry += '</li>';
						
						html += entry;
					});
				
				html += '</ul>';
				
				$stat.insert(html);
				
				// Set .value width
				// - Get max width				
				$stat.find('.value').equalWidth();

			},
			
			// Type: Horizontal List
			hlist: function($stat){
				
				var opts = $.extend({}, me.options.hlist, $stat.data());
				
				// Parse list option
				if (!$.isArray(opts.list)) {
					opts.list = $.parseJSON(opts.list);
				}
				
				// Build html
				var html = '<ul>';
					
					$.each(opts.list, function(){
						var classes = '';
						if (this.color) {
							classes += ' class="' + this.color + '"';
						}
						
						var entry = '<li' + (classes || '') + '>';
							entry += '<div class=value>' + me.format(this.val, this.format || '') + '</div>';
							entry += '<div class=title>' + this.title + '</div>';
						entry += '</li>';
						
						html += entry;
					});
				
				html += '</ul>';
				
				$stat.insert(html);
				
				// Set list elements width
				// - Get max width
				var $items = $stat.find('li');
				if (opts.flexiwidth) {
					$stat.parent().addClass('flexiwidth');
					$items.css('width', 1 / $items.length * 100 + '%');
				} else {
					$items.equalWidth(); 
				}

			},
			
			// ### Variables
			
			// ! Has the plugin already been initialized?
			initialized: false
			
		}, $.fn[PLUGIN_NAME]); // End of '$.extend(me, { ... })'
		
		// Publish members		
		function pub() {
		}
		
		$.extend(pub, {
			privates: _.chain(me).keys().reject(_.bind(_.contains, _, privates)),
		
			get: function(prop){
				if (!_.contains(privates, prop)) {
					return me[prop];
				}
			},
			
			set: function(prop, val) {
				if (!_.contains(privates, prop)) {
					me[prop] = val;
				}
			}
		});
		
		return pub;
	}; // End of 'function self() { ... }'

})(jQuery, _, this, document);








// ! Dual Select List
// - A linked dual select list
//
// - Options: see defaults in code below
//     - size: string ('medium')
//           One of: 'small', 'medium', 'large'
//           Sets the list's size
//     - sortBy: string ('name')
//           One of: 'name', 'text'
//           Wether to sort the list by the option's name attribute or the option's text
//     - buttonClass: string ('flat')
//           Which classes to set to the buttons
//     - lang: object
//           Set localizations here
//
//     - Note: all options can be set via
//             data-attributes
//     - Note: The priority of the options is:
//               1. data-tags (data-size='...' etc.)
//               2. $(...).dualselect(options)

(function($, _, window, document, undefined) {
	
	var PLUGIN_NAME = 'dualselect', ns = '.mango_' + PLUGIN_NAME;
	
	// Instances will be stored here
	var instances = [];
	
	// The publically callable function
	$.fn[PLUGIN_NAME] = $.extend(function (method, options) {
		
		var ret = this,
			args = arguments;
		
		$(this).each(function(){
			
			var inst,
				$el = $(this);
						
			// ! Create instance		
			if (inst = instances.filter(function(o){return o.el[0] == $el[0];})[0]) {inst = inst.inst}
			else { instances.push({ el: $el, inst: inst = self() }); }
			
			// ! Parse arguments
			if (typeof method === "object") {
				options = method;
				method = undefined;
			}
			
			// - Default method is 'init'
			method = method || 'init';
			
			// Warning if plugin was not initialized	
			method != 'init' && !inst.initialized
				&& $.error('$.fn.' + PLUGIN_NAME + ' was not initialzed. Please call $.fn.' + PLUGIN_NAME + '(options) first.');
				
			// ! Call the requested method
			if ($.isFunction(inst.get(method))) {
			
				// - Get options
				var opts;
				
				// 'method' was not given, arguments contains all options
				if (args[0] == options) {
					opts = $.makeArray(args);
					
				// arguments[1,2,3,...] contains the optinos
				} else if (args.length > 1) {
					opts = Array.prototype.slice.call(args, 1);
				
				// Only options is given
				} else {
					opts = [options];
				}
			
				var fret = inst.get(method).apply($el, opts);
				if (!_.isUndefined(fret)) {
					ret = fret;
				}
				
			// ! Return property value
			} else if (inst.get(method)){
			
				var prop = arguments[0], val = arguments[1];
			
				// Dynamical getter & setter
				if (!val) {
					ret = inst.get(prop);
				} else {
					inst.set(prop, val);
				}
				
			} else {
			
				// Method or property not found
				$.error('Method or property ' +  method + ' does not exist on jQuery.fn.' + PLUGIN_NAME);
				
			}
		
		});
		
		return ret;
		
	}, {
		// Accessable via $.fn.PLUGIN_NAME[name]
		
		// ! Default settings
		defaults: {
			buttonClass: 'flat',
			size : 'medium', // One of: 'small', 'medium', 'large'
			sortBy: 'name',  // One of: 'name', 'text'
			lang: {
				filterPlaceholder: 'Filter entries...'
			}
		} // End of 'defaults'
		
	});
	
	function self() {
		
		// Define public members (accessable via $.fn.PLUGIN_NAME[name])
		var me = {},
			publics = ['init', 'initialized', 'destroy'];
		$.extend(me, {
			
			// ### Functions
		
			// ! Initialize the plugin
			init: function(opts){
				
				var $this = me.$element = $(this);
				me.options = $.extend(true, {}, me.defaults, opts, $this.data()); // Combine defaults and options
				
				// ! Set up events
				me.events = {
					'change': me.reflect
				}; // End of 'me.events'

				
				// ! Set up the event handlers
				for (var key in me.events) {
					$this.on(key + ns /* -> namespacing ftw */, me.events[key]);
				}
				
				// ! Prepare HTML
				me.buildHtml();
				me.prepareHtml();
				
				// Ready
				me.initialized = true;
				
			}, // End of 'init'

			// ! Build the html and store the jQuery objects
			buildHtml: function(){
				
				// ! Build html
				var $wrapper = $('<div class=dualselect>').insertAfter(me.$element);
				$wrapper.addClass(me.options.size);
								
				// Build innerHMTL
				var innerHTML = '<div class="left"><input type="text" class="ignore" placeholder="' + me.options.lang.filterPlaceholder + '" /><select multiple class="ignore"></select><div class="counter"></div></div>';
				innerHTML += '<div class="buttons"><a class="button add">&nbsp;&gt;&nbsp;</a><a class="button addall">&nbsp;&gt;&gt;&nbsp;</a><a class="button delall">&nbsp;&lt;&lt;&nbsp;</a><a class="button del">&nbsp;&lt;&nbsp;</a></div>';
				innerHTML += '<div class="right"><input type="text" class="ignore" placeholder="' + me.options.lang.filterPlaceholder + '" /><select multiple class="ignore"></select><div class="counter"></div></div>';
				
				$wrapper.insert(innerHTML);
				me.$wrapper = $wrapper;
				
				// Store left selection
				var $left = $wrapper.find('.left');
				me.$leftFilter = $left.find('input');
				me.$left = $left.find('select');
				
				// Store right selection
				var $right = $wrapper.find('.right');
				me.$rightFilter = $right.find('input');
				me.$right = $right.find('select');
				
				// Store buttons
				var $buttons = $wrapper.find('.buttons');
				me.buttons = {
					'$add': $buttons.find('.add'),
					'$addall': $buttons.find('.addall'),
					'$del': $buttons.find('.del'),
					'$delall': $buttons.find('.delall')
				}
				
				_.each(me.buttons, function($el){
					$el.addClass(me.options.buttonClass);
				});
				
			}, // End of 'buildHtml'
			
			// ! Set all handlers etc. on the created html
			prepareHtml: function(){
			
				// ! Hide original element and store a clone of it for reset
				me.$originalState = me.$element.hide().clone();
				me.reflect();
				
				// ! Set up reset handler
				me.$element.parents('form').on('reset', function(){
					me.reset();
				});
				
				// ! Set up button handlers
				_.each(me.buttons, function($el, type){
					$el.click(function(){
						me[type.replace('$', '')].call(this);
						me.sort();
						me.reflectBack();
					});
				});
				
				// ! Set up doubleclick handlers
				me.$left.dblclick(me.add);
				me.$right.dblclick(me.del);
				
				// ! Set up filtering
				var filters = {
					$left: me.$leftFilter,
					$right: me.$rightFilter
				};
				
				_.each(filters, function($input, selectSide){
					$input.on('keyup' + ns, function(){
						me.filter(this.value.toLowerCase(), me[selectSide]);
					});
				});
				
				// ! Prepare views
				me.sort();
								
			}, // End of 'prepareHtml'
			
			// ! Make the dual list reflect the current state of the original list
			reflect: function(){
				
				var $selecteds = me.$element.children(':selected').clone().map(function(){
							this.selected = false;
							return this;
						}),
					$unselecteds = me.$element.children(':not(:selected)').clone().map(function(){
							this.selected = false;
							return this;
						});
					
				me.$left.empty().append($unselecteds);
				me.$right.empty().append($selecteds);
				
			}, // End of 'reflect'
			
			// ! Reflect the changes from the dual list back to the original list
			reflectBack: function(){
				
				var selected = _.map(me.$right.children(), function(el){
					return el.value;
				})
			
				me.$element.children().each(function(){
					
					this.selected = _.contains(selected, this.value);
				
				});
			
			}, // End of 'reflectBack'
			
			// ! Filter a list
			filter: function(search, $select){
				$select.children().show();
				$select.children().filter(function(){
					return !(this.text.toLowerCase().search(search) >= 0);
				}).hide();
			}, // End of 'filter'
			
			// ! Move selected options from unselected to selected
			add: function(){
				
				var $selected = me.$left.children(':selected').appendTo(me.$right);
				
			}, // End of 'add'
			
			// ! Move all options from unselected to selected
			addall: function(){
				
				me.$left.children().appendTo(me.$right);
				me.sort();
			
			}, // End of 'addall'
			
			// ! Move selected options to unselected
			del: function(){
			
				var $selected = me.$right.children(':selected').appendTo(me.$left);
				me.sort();
			
			}, // End of 'del'
			
			// ! Move all options from selected to unselected
			delall: function(){
			
				me.$right.children().appendTo(me.$left);
				me.sort();
			
			}, // End of 'delall'
			
			// ! Sort both lists
			sort: function(){
				
				[me.$left, me.$right].forEach(function($el){
					// Sort left
					var $sorted = $el.children().sort(function(a,b) {
						var _a = me.options.sortBy == 'text' ? a.text : $(a).attr('name'),
							_b = me.options.sortBy == 'text' ? b.text : $(b).attr('name');
							
						return _a > _b ? 1 : (_a < _b ? -1 : 0);
					});
					
					$el.empty().append($sorted);
				});
							
			}, // End of 'sort'
			
			// ! Reset the selection
			reset: function(){
			
				// Restore from original state
				me.$element.children().replaceWith(me.$originalState.children());
				me.reflect();
				
				// Deselect selected options
				me.$right.children().map(function(){
					this.selected = false;
					return this;
				});
			},
			
			// ! Destroy the dual list and revert everything to it's original state
			destroy: function(){
				me.$wrapper.remove();
				me.$element.off(ns).show();
				
				// Remove the instance from the list
				instances = _.filter(instances, function(inst){
					return inst.el[0] != me.$element[0];
				});
			}, // End of 'destroy'
			
			// ### Variables
			
			// ! Has the plugin already been initialized?
			initialized: false
			
		}, $.fn[PLUGIN_NAME]); // End of '$.extend(me, { ... })'
		
		// Publish members		
		function pub() {
		}
		
		$.extend(pub, {
			publics: _.chain(me).keys().filter(_.bind(_.contains, _, publics)),
		
			get: function(prop){
				if (_.contains(publics, prop)) {
					return me[prop];
				}
			},
			
			set: function(prop, val) {
				if (_.contains(publics, prop)) {
					me[prop] = val;
				}
			}
		});
		
		return pub;
	}; // End of 'function self() { ... }'

})(jQuery, _, this, document);
