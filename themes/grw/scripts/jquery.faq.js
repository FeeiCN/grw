(function($){$.fn.dltoggle=function(options){var opts=$.extend({},$.fn.dltoggle.defaults,options);var shut=(opts["closed-image"]||"closed.gif");var open=(opts["open-image"]||"open.gif");this.data("shut",shut);this.data("open",open);this.parent().find('dt').addClass('selected').css({"background-repeat":"no-repeat","background-position":"left center","padding-left":"0px","font-weight":"bold","cursor":"pointer",})
this.parent().find('dt').css({"background-image":"url("+open+")",})
if(!opts["leave-open"])
{this.find('dd:not(:first)').hide();this.parent().find('dt:not(:first)').removeClass('selected').css({"background-image":"url("+shut+")",})}
return this.find('dt').each(function(){$this=$(this);jQuery(this).click(function(){$(this).next().toggle();if($(this).next().is(":visible"))
{$(this).addClass('selected').css({"background-image":"url("+open+")"});}
else
{$(this).removeClass('selected').css({"background-image":"url("+shut+")"})}
return false;});});}
$.fn.dltoggle_show=function(options)
{var open=$(this).data("open");return $(this).find('dt').each(function(){$this=$(this);$this.next().show().end();$this.css({"background-image":"url("+open+")"});});}
$.fn.dltoggle_hide=function(options)
{var shut=$(this).data("shut");return $(this).find('dt').each(function(){$this=$(this);$this.next().hide().end();$this.css({"background-image":"url("+shut+")"});});}
$.fn.dltoggle.defaults={"open-image":"open.gif","closed-image":"closed.gif","leave-open":0};})(jQuery);

