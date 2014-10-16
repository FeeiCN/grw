(function() {
    // break
    /**
     * @function jQuery.cookie
     * @parent jquerypp
     * @plugin jquery/dom/cookie
     * @author Klaus Hartl/klaus.hartl@stilbuero.de
     *
     * `jQuery.cookie(name, [value], [options])` lets you create, read and remove cookies. It is the
     * [jQuery cookie plugin](https://github.com/carhartl/jquery-cookie) written by [Klaus Hartl](stilbuero.de)
     * and dual licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php)
     * and [GPL](http://www.gnu.org/licenses/gpl.html) licenses.
     *
	 * ## Examples
	 * 
	 * Set the value of a cookie.
	 *  
	 *      $.cookie('the_cookie', 'the_value');
	 * 
	 * Create a cookie with all available options.
	 *
     *      $.cookie('the_cookie', 'the_value', {
     *          expires: 7,
     *          path: '/',
     *          domain: 'jquery.com',
     *          secure: true
     *      });
	 *
	 * Create a session cookie.
	 *
     *      $.cookie('the_cookie', 'the_value');
	 *
	 * Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
	 * used when the cookie was set.
	 *
     *      $.cookie('the_cookie', null);
	 *
	 * Get the value of a cookie.
     *
	 *      $.cookie('the_cookie');
     *
     * @param {String} [name] The name of the cookie.
     * @param {String} [value] The value of the cookie.
     * @param {Object} [options] An object literal containing key/value pairs to provide optional cookie attributes. Values can be:
     *
     * - `expires` - Either an integer specifying the expiration date from now on in days or a Date object. If a negative value is specified (e.g. a date in the past), the cookie will be deleted. If set to null or omitted, the cookie will be a session cookie and will not be retained when the the browser exits.
     * - `domain` - The domain name
     * - `path` - The value of the path atribute of the cookie (default: path of page that created the cookie).
     * - `secure` - If true, the secure attribute of the cookie will be set and the cookie transmission will require a secure protocol (like HTTPS).
     *
     * @return {String} the value of the cookie or {undefined} when setting the cookie.
     */
    jQuery.cookie = function(name, value, options) {
        if (typeof value != 'undefined') { // name and value given, set cookie
            options = options ||
            {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }
            if (typeof value == 'object' && jQuery.toJSON) {
                value = jQuery.toJSON(value);
            }
            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                }
                else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
            }
            // CAUTION: Needed to parenthesize options.path and options.domain
            // in the following expressions, otherwise they evaluate to undefined
            // in the packed version for some reason...
            var path = options.path ? '; path=' + (options.path) : '';
            var domain = options.domain ? '; domain=' + (options.domain) : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        }
        else { // only name given, get cookie
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            if (jQuery.evalJSON && cookieValue && cookieValue.match(/^\s*\{/)) {
                try {
                    cookieValue = jQuery.evalJSON(cookieValue);
                }
                catch (e) {
                }
            }
            return cookieValue;
        }
    };

})(jQuery);
// http://bitovi.com/blog/2012/04/faster-jquery-event-fix.html
// https://gist.github.com/2954434 (original: https://gist.github.com/2377196)

// IE 8 has Object.defineProperty but it only defines DOM Nodes. According to
// http://kangax.github.com/es5-compat-table/#define-property-ie-note
// All browser that have Object.defineProperties also support Object.defineProperty properly

Object.defineProperties && (function (document, $){
	var
		// Use defineProperty on an object to set the value and return it
		set = function (obj, prop, val) {
			if( val !== undefined ){
				Object.defineProperty(obj, prop, {
					value : val
				});
			}
			return val;
		},

		// special converters
		special = {
			pageX : function (evt) {
				var
					  eventDoc = this.target.ownerDocument || document
					, doc	= eventDoc.documentElement
					, body	= eventDoc.body
				;
				return evt.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0 ) - ( doc && doc.clientLeft || body && body.clientLeft || 0);
			},

			pageY : function (evt) {
				var
					  eventDoc = this.target.ownerDocument || document
					, doc	= eventDoc.documentElement
					, body	= eventDoc.body
				;
				return evt.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0 ) - ( doc && doc.clientTop || body && body.clientTop || 0);
			},

			relatedTarget : function (evt) {
				if(!evt) {
					return;
				}
				return evt.fromElement === this.target ? evt.toElement : evt.fromElement;
			},

			metaKey : function (evt) {
				return evt.ctrlKey;
			},

			which : function (evt) {
				return evt ? evt.charCode != null ? evt.charCode : evt.keyCode : undefined;
			}
		}
	;


	// support jQuery < 1.7
	if( !$.event.keyHooks )		$.event.keyHooks	= { props: [] };
	if( !$.event.mouseHooks )	$.event.mouseHooks	= { props: [] };


	// Get all properties that should be mapped
	$.each($.event.keyHooks.props.concat($.event.mouseHooks.props, $.event.props), function (i, prop) {
		if( prop !== "target" ){
			(function (){
				Object.defineProperty($.Event.prototype, prop, {
					get : function () {
						// get the original value, undefined when there is no original event
						var originalValue = this.originalEvent && this.originalEvent[prop];

						// overwrite getter lookup
						return this['_' + prop] !== undefined ? this['_' + prop] : set(this, prop,
							// if we have a special function and no value
							special[prop] && originalValue === undefined ?
								// call the special function
								special[prop].call(this, this.originalEvent) :
								// use the original value
								originalValue)
					},

					set : function (newValue) {
						// Set the property with underscore prefix
						this['_' + prop] = newValue;
					}
				});
			})();
		}
	});


	$.event.fix = function (evt) {
		if( evt[ $.expando ] ){
			return	evt;
		}

		// Create a jQuery event with at minimum a target and type set
		var original = evt, target = original.target;

		evt = $.Event(original);

		// Fix target property, if necessary (#1925, IE 6/7/8 & Safari2)
		if( !target ){
			target = original.srcElement || document;
		}

		// Target should not be a text node (#504, Safari)
		if( target.nodeType === 3 ){
			target = target.parentNode;
		}

		evt.target = target;

		return	evt;
	}
})(document, jQuery);