/*

Quicksand 1.2.2

Reorder and filter items with a nice shuffling animation.

Copyright (c) 2010 Jacek Galanciak (razorjack.net) and agilope.com
Big thanks for Piotr Petrus (riddle.pl) for deep code review and wonderful docs & demos.

Dual licensed under the MIT and GPL version 2 licenses.
http://github.com/jquery/jquery/blob/master/MIT-LICENSE.txt
http://github.com/jquery/jquery/blob/master/GPL-LICENSE.txt

Project site: http://razorjack.net/quicksand
Github site: http://github.com/razorjack/quicksand

*/

(function(a){a.fn.quicksand=function(y,r,t){var b={duration:750,easing:"swing",attribute:"data-id",adjustHeight:"auto",useScaling:true,enhancement:function(){},selector:"> *"};a.extend(b,r);if(a.browser.msie||typeof a.fn.scale=="undefined")b.useScaling=false;var o;if(typeof r=="function")o=r;else if(typeof(t=="function"))o=t;return this.each(function(k){var m,i=[],l=a(y).clone(),g=a(this);k=a(this).css("height");var p,u=false,v=a(g).offset(),s=[],n=a(this).find(b.selector);if(a.browser.msie&&a.browser.version.substr(0,
1)<7)g.html("").append(l);else{var w=0,z=function(){if(!w){g.html(j.html());typeof o=="function"&&o.call(this);u&&g.css("height",p);b.enhancement(g);w=1}},c=g.offsetParent(),e=c.offset();if(c.css("position")=="relative"){if(c.get(0).nodeName.toLowerCase()!="body"){e.top+=parseFloat(c.css("border-top-width"));e.left+=parseFloat(c.css("border-left-width"))}}else{e.top-=parseFloat(c.css("border-top-width"));e.left-=parseFloat(c.css("border-left-width"));e.top-=parseFloat(c.css("margin-top"));e.left-=
parseFloat(c.css("margin-left"))}g.css("height",a(this).height());n.each(function(f){s[f]=a(this).offset()});a(this).stop();n.each(function(f){a(this).stop();var h=a(this).get(0);h.style.position="absolute";h.style.margin="0";h.style.top=s[f].top-parseFloat(h.style.marginTop)-e.top+"px";h.style.left=s[f].left-parseFloat(h.style.marginLeft)-e.left+"px"});var j=a(g).clone();c=j.get(0);c.innerHTML="";c.setAttribute("id","");c.style.height="auto";c.style.width=g.width()+"px";j.append(l);j.insertBefore(g);
j.css("opacity",0);c.style.zIndex=-1;c.style.margin="0";c.style.position="absolute";c.style.top=v.top-e.top+"px";c.style.left=v.left-e.left+"px";if(b.adjustHeight==="dynamic")g.animate({height:j.height()},b.duration,b.easing);else if(b.adjustHeight==="auto"){p=j.height();if(parseFloat(k)<parseFloat(p))g.css("height",p);else u=true}n.each(function(){var f=[];if(typeof b.attribute=="function"){m=b.attribute(a(this));l.each(function(){if(b.attribute(this)==m){f=a(this);return false}})}else f=l.filter("["+
b.attribute+"="+a(this).attr(b.attribute)+"]");if(f.length)b.useScaling?i.push({element:a(this),animation:{top:f.offset().top-e.top,left:f.offset().left-e.left,opacity:1,scale:"1.0"}}):i.push({element:a(this),animation:{top:f.offset().top-e.top,left:f.offset().left-e.left,opacity:1}});else b.useScaling?i.push({element:a(this),animation:{opacity:"0.0",scale:"0.0"}}):i.push({element:a(this),animation:{opacity:"0.0"}})});l.each(function(){var f=[],h=[];if(typeof b.attribute=="function"){m=b.attribute(a(this));
n.each(function(){if(b.attribute(this)==m){f=a(this);return false}});l.each(function(){if(b.attribute(this)==m){h=a(this);return false}})}else{f=n.filter("["+b.attribute+"="+a(this).attr(b.attribute)+"]");h=l.filter("["+b.attribute+"="+a(this).attr(b.attribute)+"]")}var x;if(f.length===0){x=b.useScaling?{opacity:"1.0",scale:"1.0"}:{opacity:"1.0"};d=h.clone();var q=d.get(0);q.style.position="absolute";q.style.margin="0";q.style.top=h.offset().top-e.top+"px";q.style.left=h.offset().left-e.left+"px";
d.css("opacity",0);b.useScaling&&d.css("transform","scale(0.0)");d.appendTo(g);i.push({element:a(d),animation:x})}});j.remove();b.enhancement(g);for(k=0;k<i.length;k++)i[k].element.animate(i[k].animation,b.duration,b.easing,z)}})}})(jQuery);

(function($) {
  $.fn.sorted = function(customOptions) {
    var options = {
      reversed: false,
      by: function(a) { return a.text(); }
    };
    $.extend(options, customOptions);
    $data = $(this);
    arr = $data.get();
    arr.sort(function(a, b) {
      var valA = options.by($(a));
      var valB = options.by($(b));
      if (options.reversed) {
        return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;				
      } else {		
        return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;	
      }
    });
    return $(arr);
  };
})(jQuery);

// DOMContentLoaded
$(function() {
  
  var read_button = function(class_names) {
    var r = {
      selected: false,
      type: 0
    };
    for (var i=0; i < class_names.length; i++) {
      if (class_names[i].indexOf('selected-') == 0) {
        r.selected = true;
      }
      if (class_names[i].indexOf('segment-') == 0) {
        r.segment = class_names[i].split('-')[1];
      }
    };
    return r;
  };
  
  var determine_sort = function($buttons) {
    var $selected = $buttons.parent().filter('[class*="selected-"]');
    return $selected.find('a').attr('data-value');
  };
  
  var determine_kind = function($buttons) {
    var $selected = $buttons.parent().filter('[class*="selected-"]');
    return $selected.find('a').attr('data-value');
  };
  
  var $preferences = {
    duration: 800,
    easing: 'easeInOutQuad',
    adjustHeight: 'auto'
  };
  
  var $list = $('#list');
  var $data = $list.clone();
  
  var $controls = $('ul.filter_nav');
  
  $controls.each(function(i) {
    
    var $control = $(this);
    var $buttons = $control.find('a');
    
    $buttons.bind('click', function(e) {
      
      var $button = $(this);
      var $button_container = $button.parent();
      var button_properties = read_button($button_container.attr('class').split(' '));      
      var selected = button_properties.selected;
      var button_segment = button_properties.segment;

      if (!selected) {

        $buttons.parent().removeClass('selected-0').removeClass('selected-1').removeClass('selected-2').removeClass('selected-3').removeClass('selected-4').removeClass('selected-5');
        $button_container.addClass('selected-' + button_segment);
        
        var sorting_type = determine_sort($controls.eq(1).find('a'));
        var sorting_kind = determine_kind($controls.eq(0).find('a'));
        
        if (sorting_kind == 'all') {
          var $filtered_data = $data.find('li');
        } else {
          var $filtered_data = $data.find('li.' + sorting_kind);
        }
		
        if (sorting_type == 'size') {
          var $sorted_data = $filtered_data.sorted({
            by: function(v) {
              return parseFloat($(v).find('p').text());
            }
          });
        } else {
          var $sorted_data = $filtered_data.sorted({
            by: function(v) {
              return $(v).find('h3').text().toLowerCase();
            }
          });
        }
        
        $list.quicksand($sorted_data, $preferences, function() {
					
			

//prettyphoto
$('a[data-rel]').each(function() {
		$(this).attr('rel', $(this).data('rel'));
});
$("a[rel^='prettyPhoto[mixed]']").prettyPhoto({
		animation_speed: 'fast',
		slideshow: 5000,
		autoplay_slideshow: false,
		opacity: 0.80,
		show_title: false,
		theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
		overlay_gallery: false,
		social_tools: false
});

//Thumbnail hover effect for gallery
$('.thumb').hover(function(){  
		 $(this).find(".first_icon").animate({left: '80px'}, 300);
	     $(this).find(".second_icon").animate({left: '115px'}, 500);
		 }  
		 , function(){  
		 $(this).find(".first_icon").animate({left: '-50px'}, 300);
		 $(this).find(".second_icon").animate({left: '-50px'}, 500);
});
		

});
		}
		e.preventDefault();
		});    
	});
});
		
