	var Active, Names, Times, xy;
	Active = new Array();
	Names = new Array();
	Times = new Array();
	xy = new Array;

	var popUpWin = 0;

	nn4 = (document.layers) ? true: false;
	ie4 = (document.all) ? true: false;
	nn6 = (document.getElementById && !ie4) ? true: false;

	if (nn4 || ie4 || nn6) ver = "good";

	function FindObj(n, d) {
		var p, i, x;
		if (!d) d = document;
		if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
			d = parent.frames[n.substring(p + 1)].document;
			n = n.substring(0, p);
		}
		if (! (x = d[n]) && d.all) x = d.all[n];
		for (i = 0; ! x && i < d.forms.length; i++) x = d.forms[i][n];
		for (i = 0; ! x && d.layers && i < d.layers.length; i++)
		x = FindObj(n, d.layers[i].document);
		return x;
	}

	function MsOv(chn) {
		var y, imgOn, imgOff;
		if (nn4 || ie4 || nn6) {
			imgOn = eval('bt' + chn + "on.src");
			imgOff = eval('bt' + chn + "off.src");
			if (document[chn].src == imgOn) document[chn].src = imgOff;
			else document[chn].src = imgOn;
		}
	}

	function NW(Img, Wi, He, Re, Sc, Desc) {
		Wi += 25;
		He += 25;
		if (Desc == "") {
			Desc = Img;
		}
		if (Sc == 1) {
			if (Re == 1) {
				window.open(Img, "_blank", "toolbar=0, height=" + He + ", width=" + Wi + ", resizable, scrollbars=yes");
			} else {
				window.open(Img, "_blank", "toolbar=0, height=" + He + ", width=" + Wi + ", scrollbars=yes");
			}
		} else {
			if (Re == 1) {
				window.open(Img, "_blank", "toolbar=0, height=" + He + ", width=" + Wi + ", resizable");
			} else {
				window.open(Img, "_blank", "toolbar=0, height=" + He + ", width=" + Wi);
			}
		}
	}

	 $(document).on('click',function(){
$('.collapse').collapse('hide');
})
	
	
	function popUpWindow(URLStr, left, top, width, height) {
		if (popUpWin) {
			if (!popUpWin.closed) popUpWin.close();
		}
		popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=1,resizable=yes,copyhistory=yes,width=' + width + ',height=' + height + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
	}

	function ShowImage(ev, aId, typ, width, height) {
		var id = parseInt(aId, 10);
		var wdt = (width == 0) ? 500 : width + 80;
		var hgt = (height == 0) ? 400 : height + 100;
		popUpWindow("/htdocs/view_image.php?id=" + id, 50, 50, wdt, hgt);
		if (window.event) {
			window.event.cancelBubble = true;
			window.event.returnValue = false
		}
		else if (ev) {
			ev.cancelBubble = true;
			ev.preventDefault();
			ev.stopPropagation()
		}
	}
	
	

var miscObj = {};
miscObj.ua = navigator.userAgent.toLowerCase(),
check = function (r) {
	return r.test(miscObj.ua);
},
miscObj.isStrict = document.compatMode == "CSS1Compat",
miscObj.isOpera = check(/opera/),
miscObj.isChrome = check(/chrome/),
miscObj.isWebKit = check(/webkit/),
miscObj.isSafari = !miscObj.isChrome && check(/safari/),
miscObj.isSafari3 = miscObj.isSafari && check(/version\/3/),
miscObj.isSafari4 = miscObj.isSafari && check(/version\/4/),
miscObj.isIE = !miscObj.isOpera && check(/msie/),
miscObj.isIE7 = miscObj.isIE && check(/msie 7/),
miscObj.isIE8 = miscObj.isIE && check(/msie 8/),
miscObj.isGecko = !miscObj.isWebKit && check(/gecko/),
miscObj.isGecko3 = miscObj.isGecko && check(/rv:1\.9/),
miscObj.isBorderBox = miscObj.isIE && !miscObj.isStrict,
miscObj.isWindows = check(/windows|win32/),
miscObj.isMac = check(/macintosh|mac os x/),
miscObj.isAir = check(/adobeair/),
miscObj.isLinux = check(/linux/),
miscObj.isSecure = /^https/i.test(window.location.protocol);

/**
 * indexOf for Ie6
 **/
if (!Array.indexOf) {
	Array.prototype.indexOf = function (obj) {
		for (var i = 0; i < this.length; i++) {
			if (this[i] == obj) {
				return i;
			}
		}
		return - 1;
	}
}


/**
 * http://phpjs.org/functions/number_format:481
 *
 * @param number
 * @param decimals
 * @param dec_point
 * @param thousands_sep
 */
function number_format(number, decimals, dec_point, thousands_sep) {
	// *     example 1: number_format(1234.56);
	// *     returns 1: '1,235'
	// *     example 2: number_format(1234.56, 2, ',', ' ');
	// *     returns 2: '1 234,56'
	// *     example 3: number_format(1234.5678, 2, '.', '');
	// *     returns 3: '1234.57'
	// *     example 4: number_format(67, 2, ',', '.');
	// *     returns 4: '67,00'
	// *     example 5: number_format(1000);
	// *     returns 5: '1,000'
	// *     example 6: number_format(67.311, 2);
	// *     returns 6: '67.31'
	// *     example 7: number_format(1000.55, 1);
	// *     returns 7: '1,000.6'
	// *     example 8: number_format(67000, 5, ',', '.');
	// *     returns 8: '67.000,00000'
	// *     example 9: number_format(0.9, 0);
	// *     returns 9: '1'
	// *    example 10: number_format('1.20', 2);
	// *    returns 10: '1.20'
	// *    example 11: number_format('1.20', 4);
	// *    returns 11: '1.2000'
	// *    example 12: number_format('1.2000', 3);
	// *    returns 12: '1.200'
	// *    example 13: number_format('1 000,50', 2, '.', ' ');
	// *    returns 13: '100 050.00'
	// Strip all characters but numerical ones.
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function (n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

/**
 * JSON
 */
if (!this.JSON) {
	this.JSON = {};
}

(function () {

	function f(n) {
		// Format integers to have at least two digits.
		return n < 10 ? '0' + n: n;
	}

	if (typeof Date.prototype.toJSON !== 'function') {

		Date.prototype.toJSON = function (key) {

			return isFinite(this.valueOf()) ? this.getUTCFullYear() + '-' + f(this.getUTCMonth() + 1) + '-' + f(this.getUTCDate()) + 'T' + f(this.getUTCHours()) + ':' + f(this.getUTCMinutes()) + ':' + f(this.getUTCSeconds()) + 'Z': null;
		};

		String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function (key) {
			return this.valueOf();
		};
	}

	var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
	escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
	gap, indent, meta = { // table of character substitutions
		'\b': '\\b',
		'\t': '\\t',
		'\n': '\\n',
		'\f': '\\f',
		'\r': '\\r',
		'"': '\\"',
		'\\': '\\\\'
	},
	rep;

	function quote(string) {

		// If the string contains no control characters, no quote characters, and no
		// backslash characters, then we can safely slap some quotes around it.
		// Otherwise we must also replace the offending characters with safe escape
		// sequences.

		escapable.lastIndex = 0;
		return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
			var c = meta[a];
			return typeof c === 'string' ? c: '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice( - 4);
		}) + '"': '"' + string + '"';
	}

	function str(key, holder) {

		// Produce a string from holder[key].

		var i, // The loop counter.
		k, // The member key.
		v, // The member value.
		length, mind = gap,
		partial, value = holder[key];

		// If the value has a toJSON method, call it to obtain a replacement value.

		if (value && typeof value === 'object' && typeof value.toJSON === 'function') {
			value = value.toJSON(key);
		}

		// If we were called with a replacer function, then call the replacer to
		// obtain a replacement value.

		if (typeof rep === 'function') {
			value = rep.call(holder, key, value);
		}

		// What happens next depends on the value's type.

		switch (typeof value) {
		case 'string':
			return quote(value);

		case 'number':

			// JSON numbers must be finite. Encode non-finite numbers as null.

			return isFinite(value) ? String(value) : 'null';

		case 'boolean':
		case 'null':

			// If the value is a boolean or null, convert it to a string. Note:
			// typeof null does not produce 'null'. The case is included here in
			// the remote chance that this gets fixed someday.

			return String(value);

			// If the type is 'object', we might be dealing with an object or an array or
			// null.

		case 'object':

			// Due to a specification blunder in ECMAScript, typeof null is 'object',
			// so watch out for that case.

			if (!value) {
				return 'null';
			}

			// Make an array to hold the partial results of stringifying this object value.

			gap += indent;
			partial = [];

			// Is the value an array?

			if (Object.prototype.toString.apply(value) === '[object Array]') {

				// The value is an array. Stringify every element. Use null as a placeholder
				// for non-JSON values.

				length = value.length;
				for (i = 0; i < length; i += 1) {
					partial[i] = str(i, value) || 'null';
				}

				// Join all of the elements together, separated with commas, and wrap them in
				// brackets.

				v = partial.length === 0 ? '[]': gap ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']': '[' + partial.join(',') + ']';
				gap = mind;
				return v;
			}

			// If the replacer is an array, use it to select the members to be stringified.

			if (rep && typeof rep === 'object') {
				length = rep.length;
				for (i = 0; i < length; i += 1) {
					k = rep[i];
					if (typeof k === 'string') {
						v = str(k, value);
						if (v) {
							partial.push(quote(k) + (gap ? ': ': ':') + v);
						}
					}
				}
			} else {

				// Otherwise, iterate through all of the keys in the object.

				for (k in value) {
					if (Object.hasOwnProperty.call(value, k)) {
						v = str(k, value);
						if (v) {
							partial.push(quote(k) + (gap ? ': ': ':') + v);
						}
					}
				}
			}

			// Join all of the member texts together, separated with commas,
			// and wrap them in braces.

			v = partial.length === 0 ? '{}': gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}': '{' + partial.join(',') + '}';
			gap = mind;
			return v;
		}
	}

	// If the JSON object does not yet have a stringify method, give it one.

	if (typeof JSON.stringify !== 'function') {
		JSON.stringify = function (value, replacer, space) {

			// The stringify method takes a value and an optional replacer, and an optional
			// space parameter, and returns a JSON text. The replacer can be a function
			// that can replace values, or an array of strings that will select the keys.
			// A default replacer method can be provided. Use of the space parameter can
			// produce text that is more easily readable.

			var i;
			gap = '';
			indent = '';

			// If the space parameter is a number, make an indent string containing that
			// many spaces.

			if (typeof space === 'number') {
				for (i = 0; i < space; i += 1) {
					indent += ' ';
				}

				// If the space parameter is a string, it will be used as the indent string.

			} else if (typeof space === 'string') {
				indent = space;
			}

			// If there is a replacer, it must be a function or an array.
			// Otherwise, throw an error.

			rep = replacer;
			if (replacer && typeof replacer !== 'function' && (typeof replacer !== 'object' || typeof replacer.length !== 'number')) {
				throw new Error('JSON.stringify');
			}

			// Make a fake root object containing our value under the key of ''.
			// Return the result of stringifying the value.

			return str('', {
				'': value
			});
		};
	}

	// If the JSON object does not yet have a parse method, give it one.

	if (typeof JSON.parse !== 'function') {
		JSON.parse = function (text, reviver) {

			// The parse method takes a text and an optional reviver function, and returns
			// a JavaScript value if the text is a valid JSON text.

			var j;

			function walk(holder, key) {

				var k, v, value = holder[key];
				if (value && typeof value === 'object') {
					for (k in value) {
						if (Object.hasOwnProperty.call(value, k)) {
							v = walk(value, k);
							if (v !== undefined) {
								value[k] = v;
							} else {
								delete value[k];
							}
						}
					}
				}
				return reviver.call(holder, key, value);
			}

			text = String(text);
			cx.lastIndex = 0;
			if (cx.test(text)) {
				text = text.replace(cx, function (a) {
					return '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice( - 4);
				});
			}

			if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

				j = eval('(' + text + ')');

				return typeof reviver === 'function' ? walk({
					'': j
				},
				'') : j;
			}

			throw new SyntaxError('JSON.parse');
		};
	}
} ());