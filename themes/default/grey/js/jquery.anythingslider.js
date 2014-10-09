/*
    anythingSlider v1.2
    
    By Chris Coyier: http://css-tricks.com
    with major improvements by Doug Neiner: http://pixelgraphics.us/
    based on work by Remy Sharp: http://jqueryfordesigners.com/


	To use the navigationFormatter function, you must have a function that
	accepts two paramaters, and returns a string of HTML text.
	
	index = integer index (1 based);
	panel = jQuery wrapped LI item this tab references
	@return = Must return a string of HTML/Text
	
	navigationFormatter: function(index, panel){
		return index + " Panel"; // This would have each tab with the text 'X Panel' where X = index
	}
*/

(function($){
	
    $.anythingSlider = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;
        
        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el; 

		// Set up a few defaults
        base.currentPage = 1;
		base.timer = null;
		base.playing = false;

        // Add a reverse reference to the DOM object
        base.$el.data("AnythingSlider", base);
        
        base.init = function(){
            base.options = $.extend({},$.anythingSlider.defaults, options);
			
			// Cache existing DOM elements for later 
			base.$wrapper = base.$el.find('> div').css('overflow', 'hidden');
            base.$slider  = base.$wrapper.find('> ul');
            base.$items   = base.$slider.find('> li');
            base.$single  = base.$items.filter(':first');

			// Build the navigation if needed
			if(base.options.buildNavigation) base.buildNavigation();
        
        	// Get the details
            base.singleWidth = base.$single.outerWidth();
            base.pages = base.$items.length;

            // Top and tail the list with 'visible' number of items, top has the last section, and tail has the first
			// This supports the "infinite" scrolling
			base.$items.filter(':first').before(base.$items.filter(':last').clone().addClass('cloned'));
            base.$items.filter(':last' ).after(base.$items.filter(':first').clone().addClass('cloned'));

			// We just added two items, time to re-cache the list
            base.$items = base.$slider.find('> li'); // reselect
            
			// Setup our forward/backward navigation
			base.buildNextBackButtons();
		
			// If autoPlay functionality is included, then initialize the settings
			if(base.options.autoPlay) {
				base.playing = !base.options.startStopped; // Sets the playing variable to false if startStopped is true
				base.buildAutoPlay();
			};
			
			// If pauseOnHover then add hover effects
			if(base.options.pauseOnHover) {
				base.$el.hover(function(){
					base.clearTimer();
				}, function(){
					base.startStop(base.playing);
				});
			}
			
			// If a hash can not be used to trigger the plugin, then go to page 1
			if((base.options.hashTags == true && !base.gotoHash()) || base.options.hashTags == false){
				base.setCurrentPage(1);
			};
        };

		base.gotoPage = function(page, autoplay){
			// When autoplay isn't passed, we stop the timer
			if(autoplay !== true) autoplay = false;
			if(!autoplay) base.startStop(false);
			
			if(typeof(page) == "undefined" || page == null) {
				page = 1;
				base.setCurrentPage(1);
			};
			
			// Just check for bounds
			if(page > base.pages + 1) page = base.pages;
			if(page < 0 ) page = 1;

			var dir = page < base.currentPage ? -1 : 1,
                n = Math.abs(base.currentPage - page),
                left = base.singleWidth * dir * n;
			
			base.$wrapper.filter(':not(:animated)').animate({
                scrollLeft : '+=' + left
            }, base.options.animationTime, base.options.easing, function () {
                if (page == 0) {
                    base.$wrapper.scrollLeft(base.singleWidth * base.pages);
					page = base.pages;
                } else if (page > base.pages) {
                    base.$wrapper.scrollLeft(base.singleWidth);
                    // reset back to start position
                    page = 1;
                };
				base.setCurrentPage(page);
				
            });
		};
		
		base.setCurrentPage = function(page, move){
			// Set visual
			if(base.options.buildNavigation){
				base.$nav.find('.cur').removeClass('cur');
				$(base.$navLinks[page - 1]).addClass('cur');	
			};
			
			// Only change left if move does not equal false
			if(move !== false) base.$wrapper.scrollLeft(base.singleWidth * page);

			// Update local variable
			base.currentPage = page;
		};
		
		base.goForward = function(autoplay){
			if(autoplay !== true) autoplay = false;
			base.gotoPage(base.currentPage + 1, autoplay);
		};
		
		base.goBack = function(){
			base.gotoPage(base.currentPage - 1);
		};
		
		// This method tries to find a hash that matches panel-X
		// If found, it tries to find a matching item
		// If that is found as well, then that item starts visible
		base.gotoHash = function(){
			if(/^#?panel-\d+$/.test(window.location.hash)){
				var index = parseInt(window.location.hash.substr(7));
				var $item = base.$items.filter(':eq(' + index + ')');
				if($item.length != 0){
					base.setCurrentPage(index);
					return true;
				};
			};
			return false; // A item wasn't found;
		};
        
		// Creates the numbered navigation links
		base.buildNavigation = function(){
			base.$nav = $("<div id='thumbNav'></div>").appendTo(base.$el);
			base.$items.each(function(i,el){
				var index = i + 1;
				var $a = $("<a href='#'></a>");
				
				// If a formatter function is present, use it
				if( typeof(base.options.navigationFormatter) == "function"){
					$a.html(base.options.navigationFormatter(index, $(this)));
				} else {
					$a.text(index);
				}
				$a.click(function(e){
                    base.gotoPage(index);
                    
                    if (base.options.hashTags)
						base.setHash('panel-' + index);
						
                    e.preventDefault();
				});
				base.$nav.append($a);
			});
			base.$navLinks = base.$nav.find('> a');
		};
		
		
		// Creates the Forward/Backward buttons
		base.buildNextBackButtons = function(){
			var $forward = $('<a class="arrow forward">&gt;</a>'),
				$back    = $('<a class="arrow back">&lt;</a>');
				
            // Bind to the forward and back buttons
            $back.click(function(e){
                base.goBack();
				e.preventDefault();
            });

            $forward.click(function(e){
                base.goForward();
				e.preventDefault();
            });

			// Append elements to page
			base.$wrapper.after($back).after($forward);
		};
		
		// Creates the Start/Stop button
		base.buildAutoPlay = function(){

			base.$startStop = $("<a href='#' id='start-stop'></a>").html(base.playing ? base.options.stopText :  base.options.startText);
			base.$el.append(base.$startStop);            
            base.$startStop.click(function(e){
				base.startStop(!base.playing);
				e.preventDefault();
            });

			// Use the same setting, but trigger the start;
			base.startStop(base.playing);
		};
		
		// Handles stopping and playing the slideshow
		// Pass startStop(false) to stop and startStop(true) to play
		base.startStop = function(playing){
			if(playing !== true) playing = false; // Default if not supplied is false
			
			// Update variable
			base.playing = playing;
			
			// Toggle playing and text
			if(base.options.autoPlay) base.$startStop.toggleClass("playing", playing).html( playing ? base.options.stopText : base.options.startText );
			
			if(playing){
				base.clearTimer(); // Just in case this was triggered twice in a row
				base.timer = window.setInterval(function(){
					base.goForward(true);
				}, base.options.delay);
			} else {
				base.clearTimer();
			};
		};
		
		base.clearTimer = function(){
			// Clear the timer only if it is set
			if(base.timer) window.clearInterval(base.timer);
		};
		
		// Taken from AJAXY jquery.history Plugin
		base.setHash = function ( hash ) {
			// Write hash
			if ( typeof window.location.hash !== 'undefined' ) {
				if ( window.location.hash !== hash ) {
					window.location.hash = hash;
				};
			} else if ( location.hash !== hash ) {
				location.hash = hash;
			};
			
			// Done
			return hash;
		};
		// <-- End AJAXY code


		// Trigger the initialization
        base.init();
    };

	
    $.anythingSlider.defaults = {
        easing: "swing",                // Anything other than "linear" or "swing" requires the easing plugin
        autoPlay: true,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not
        startStopped: false,            // If autoPlay is on, this can force it to start stopped
        delay: 3000,                    // How long between slide transitions in AutoPlay mode
        animationTime: 600,             // How long the slide transition takes
        hashTags: true,                 // Should links change the hashtag in the URL?
        buildNavigation: true,          // If true, builds and list of anchor links to link to each slide
        pauseOnHover: true,             // If true, and autoPlay is enabled, the show will pause on hover
		startText: "",             // Start text
		stopText: "",               // Stop text
		navigationFormatter: null       // Details at the top of the file on this use (advanced use)
    };
	

    $.fn.anythingSlider = function(options){
		if(typeof(options) == "object"){
		    return this.each(function(i){			
				(new $.anythingSlider(this, options));

	            // This plugin supports multiple instances, but only one can support hash-tag support
				// This disables hash-tags on all items but the first one
				options.hashTags = false;
	        });	
		} else if (typeof(options) == "number") {

			return this.each(function(i){
				var anySlide = $(this).data('AnythingSlider');
				if(anySlide){
					anySlide.gotoPage(options);
				}
			});
		}
    };

	
})(jQuery);





/*
 * jQuery EasIng v1.1.2 - http://gsgd.co.uk/sandbox/jquery.easIng.php
 *
 * Uses the built In easIng capabilities added In jQuery 1.1
 * to offer multiple easIng options
 *
 * Copyright (c) 2007 George Smith
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 */

// t: current time, b: begInnIng value, c: change In value, d: duration

jQuery.extend( jQuery.easing,
{
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});