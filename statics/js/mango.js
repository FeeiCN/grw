"use strict";

/////////////////////////////////
// New Javascript Features ftw!



if (!Function.prototype.bind) {
	Function.prototype.bind = function (oThis) {
		if (typeof this !== "function") {
			// closest thing possible to the ECMAScript 5 internal IsCallable function
			throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
		}

		var aArgs = Array.prototype.slice.call(arguments, 1),
			fToBind = this,
			fNOP = function () {},
			fBound = function () {
				return fToBind.apply(this instanceof fNOP ? this : oThis || window, aArgs.concat(Array.prototype.slice.call(arguments)));
			};

		fNOP.prototype = this.prototype;
		fBound.prototype = new fNOP();

		return fBound;
	};
}



if (!Array.prototype.filter) {
	Array.prototype.filter = function (fun /*, thisp */ ) {
		"use strict";

		if (this == null) throw new TypeError();

		var t = Object(this);
		var len = t.length >>> 0;
		if (typeof fun != "function") throw new TypeError();

		var res = [];
		var thisp = arguments[1];
		for (var i = 0; i < len; i++) {
			if (i in t) {
				var val = t[i]; // in case fun mutates this  
				if (fun.call(thisp, val, i, t)) res.push(val);
			}
		}

		return res;
	};
}


// Production steps of ECMA-262, Edition 5, 15.4.4.18  
// Reference: http://es5.github.com/#x15.4.4.18  
if (!Array.prototype.forEach) {

	Array.prototype.forEach = function (callback, thisArg) {

		var T, k;

		if (this == null) {
			throw new TypeError(" this is null or not defined");
		}

		// 1. Let O be the result of calling ToObject passing the |this| value as the argument.  
		var O = Object(this);

		// 2. Let lenValue be the result of calling the Get internal method of O with the argument "length".  
		// 3. Let len be ToUint32(lenValue).  
		var len = O.length >>> 0; // Hack to convert O.length to a UInt32  
		// 4. If IsCallable(callback) is false, throw a TypeError exception.  
		// See: http://es5.github.com/#x9.11  
		if ({}.toString.call(callback) != "[object Function]") {
			throw new TypeError(callback + " is not a function");
		}

		// 5. If thisArg was supplied, let T be thisArg; else let T be undefined.  
		if (thisArg) {
			T = thisArg;
		}

		// 6. Let k be 0  
		k = 0;

		// 7. Repeat, while k < len  
		while (k < len) {

			var kValue;

			// a. Let Pk be ToString(k).  
			//   This is implicit for LHS operands of the in operator  
			// b. Let kPresent be the result of calling the HasProperty internal method of O with argument Pk.  
			//   This step can be combined with c  
			// c. If kPresent is true, then  
			if (k in O) {

				// i. Let kValue be the result of calling the Get internal method of O with argument Pk.  
				kValue = O[k];

				// ii. Call the Call internal method of callback with T as the this value and  
				// argument list containing kValue, k, and O.  
				callback.call(T, kValue, k, O);
			}
			// d. Increase k by 1.  
			k++;
		}
		// 8. return undefined  
	};
}




if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
		"use strict";
		if (this == null) {
			throw new TypeError();
		}
		var t = Object(this);
		var len = t.length >>> 0;
		if (len === 0) {
			return -1;
		}
		var n = 0;
		if (arguments.length > 0) {
			n = Number(arguments[1]);
			if (n != n) { // shortcut for verifying if it's NaN  
				n = 0;
			} else if (n != 0 && n != Infinity && n != -Infinity) {
				n = (n > 0 || -1) * Math.floor(Math.abs(n));
			}
		}
		if (n >= len) {
			return -1;
		}
		var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
		for (; k < len; k++) {
			if (k in t && t[k] === searchElement) {
				return k;
			}
		}
		return -1;
	}
}

// Production steps of ECMA-262, Edition 5, 15.4.4.19  
// Reference: http://es5.github.com/#x15.4.4.19  
if (!Array.prototype.map) {
	Array.prototype.map = function (callback, thisArg) {

		var T, A, k;

		if (this == null) {
			throw new TypeError(" this is null or not defined");
		}

		// 1. Let O be the result of calling ToObject passing the |this| value as the argument.  
		var O = Object(this);

		// 2. Let lenValue be the result of calling the Get internal method of O with the argument "length".  
		// 3. Let len be ToUint32(lenValue).  
		var len = O.length >>> 0;

		// 4. If IsCallable(callback) is false, throw a TypeError exception.  
		// See: http://es5.github.com/#x9.11  
		if ({}.toString.call(callback) != "[object Function]") {
			throw new TypeError(callback + " is not a function");
		}

		// 5. If thisArg was supplied, let T be thisArg; else let T be undefined.  
		if (thisArg) {
			T = thisArg;
		}

		// 6. Let A be a new array created as if by the expression new Array(len) where Array is  
		// the standard built-in constructor with that name and len is the value of len.  
		A = new Array(len);

		// 7. Let k be 0  
		k = 0;

		// 8. Repeat, while k < len  
		while (k < len) {

			var kValue, mappedValue;

			// a. Let Pk be ToString(k).  
			//   This is implicit for LHS operands of the in operator  
			// b. Let kPresent be the result of calling the HasProperty internal method of O with argument Pk.  
			//   This step can be combined with c  
			// c. If kPresent is true, then  
			if (k in O) {

				// i. Let kValue be the result of calling the Get internal method of O with argument Pk.  
				kValue = O[k];

				// ii. Let mappedValue be the result of calling the Call internal method of callback  
				// with T as the this value and argument list containing kValue, k, and O.  
				mappedValue = callback.call(T, kValue, k, O);

				// iii. Call the DefineOwnProperty internal method of A with arguments  
				// Pk, Property Descriptor {Value: mappedValue, Writable: true, Enumerable: true, Configurable: true},  
				// and false.  

				// In browsers that support Object.defineProperty, use the following:  
				// Object.defineProperty(A, Pk, { value: mappedValue, writable: true, enumerable: true, configurable: true });  

				// For best browser support, use the following:  
				A[k] = mappedValue;
			}
			// d. Increase k by 1.  
			k++;
		}

		// 9. return A  
		return A;
	};
}

if (!Array.prototype.reduce) {
	Array.prototype.reduce = function reduce(accumulator) {
		if (this === null || this === undefined) throw new TypeError("Object is null or undefined");
		var i = 0,
			l = this.length >> 0,
			curr;

		if (typeof accumulator !== "function") // ES5 : "If IsCallable(callbackfn) is false, throw a TypeError exception."  
		throw new TypeError("First argument is not callable");

		if (arguments.length < 2) {
			if (l === 0) throw new TypeError("Array length is 0 and no second argument");
			curr = this[0];
			i = 1; // start accumulating at the second element  
		} else curr = arguments[1];

		while (i < l) {
			if (i in this) curr = accumulator.call(undefined, curr, this[i], i, this);
			++i;
		}

		return curr;
	};
}











(function($$, $, undefined){

	// Store old $$ for noConflict mode
	var old$$ = window[$$];
	
	var mango = {
	
		// ! Config
		config: {
			// Internal:
			version: '1.0.2',
			
			// Effects and animations
			fxSpeed: 300,
			
			// User Interface
			// - Lock Screen
			lock: {
				timeout: 60 * 10, // sec: Seconds to wait after user has become inactive before show the lock screen
				idle: 15, // sec: Seconds to wait if user is idle on the password form before show the slider again
				
				lockWhenInactive: false // boolean: Lock the screen when the user switches to another tab or minimizes the window
			},
			
			// - Settings Dialog
			settings: {
				width: 450 // px: The width of the dialog
			},
			
			contents: {
				sortableOnTouchDevices: false
			},
			
			// Scroll to Top button
			scollToTop: true,
			
			// Preload important images?
			preloadImages: true,
			
			// Some l18n
			lang: {
				appcache: {
					PLEASE_RELOAD_TITLE: 'New version',
					PLEASE_RELOAD: 'A new version of this site is available. Please reload page.',
					PROMT_RELOAD: 'A new version of this site is available. Load it?'
				}
			}
			
		},
		
		// ! Several utilities
		utils: {
		
			// ! Stop bubbling up the event
			noBubbling: function(e){e.stopPropagation()},

			// ! Try calling a function and catch errors
			tryF: function(cb){return function(){try{cb()}catch(e){console.error(e + cb())}}},
			
			// ! Run functions when the page including all it's ressources is loaded.
			// - This is a shortcut for $(window).load(...)
			ready: function(cb) {
				$(window).load(mango.utils.tryF(cb));
			},
			
			// ! $(document).ready(...) is too slow for us
			// - Use our own implementation instead
			loaded: function(cb) {
				// Local copy
				var _cb = this.loaded.cb;
			
				// Initialize callbacks array if needed
				!_cb && (_cb = []);
				
				// Add callback to stack or execute all callbacks
				$.isFunction(cb) ? _cb.push(mango.utils.tryF(cb)) : _cb.forEach(function(f){f()});
				
				// Store changes
				this.loaded.cb = _cb;
				
			}, // End of 'loaded'
			
			// ! Preload images
			preload: function(images) {
				// Defer execution
				_.defer(function(){
					images.forEach(function(img){
						(new Image()).src = ("/statics/"+img);
					});
				});
			}
			
		}, // End of 'utils'
		
		isOldIE: $.browser.msie && parseInt($.browser.version) < 9,
		
		isPhone: window.matchMedia('screen and (max-width: 650px)').matches && Modernizr.touch,
		
		// ! No Conflict Mode:
		// - Return $$ to it's previous owner and return the mango object
		noConflict: function(){
			window[$$] = old$$;
			return this;
		}
		
	}; // End of 'mango'
	
	// - Publish functions
	mango.loaded = mango.utils.loaded;
	mango.ready = mango.utils.ready;
	
	
	
	// ! Make $$ public
	window[$$] = {};
	$.extend(window[$$], mango);
	
})('$$', jQuery); // To change the public name, change $$ in this line.