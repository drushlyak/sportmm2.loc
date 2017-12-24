	/**
	 * Namespace
	 * @type object
	 */
	var Cruiser = {};

	/**
	 * Пустая функция
	 */
	var EmptyFn = function() {};

	/**
	 * Функция Apply
	 * @from ExtJS
	 **/
	var ExApply = function (o, c, defaults) {
		if (defaults) {
			ExApply(o, defaults);
		}
		if (o && c && typeof c == 'object') {
			for (var p in c) {
				o[p] = c[p];
			}
		}
		return o;
	};

	/**
	 * Создание метода делегирования cDelegate
	 * @from ExtJS
	 */
	ExApply(Function.prototype, {
		cDelegate: function (obj, args, appendArgs) {
			var method = this;
			return function () {
				var callArgs = args || arguments;
				if (appendArgs === true) {
					callArgs = Array.prototype.slice.call(arguments, 0);
					callArgs = callArgs.concat(args);
				} else if (typeof appendArgs == "number") {
					callArgs = Array.prototype.slice.call(arguments, 0);
					var applyArgs = [appendArgs, 0].concat(args);
					Array.prototype.splice.apply(callArgs, applyArgs);
				}
				return method.apply(obj || window, callArgs);
			};
		}
	});

	if (typeof console == 'undefined') {
		var console = {};

		ExApply(console, {
			log : EmptyFn,
			trace : EmptyFn,
			info: EmptyFn,
			error: function(o) {
				alert(o);
			}
		});
	}

	fb = console.log; __ = console.log;

	/**
	 * Установка cookie
	 *
	 * @param {string} name
	 * @param {string} value
	 * @param {int} days
	 * @param {string} path
	 * @param {string} domain
	 * @param {string} secure
	 * @return {void}
	 */
	Cruiser.setCookie = function(name, value, days, path, domain, secure) {
		var expires = -1;
		if (typeof days == "number" && days >= 0) {
			var d = new Date();
			d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = d.toGMTString();
		}

		var value = escape(value);
		document.cookie = name + "=" + value + ";"
						+ (expires != -1 ? " expires=" + expires + ";" : "")
						+ (path ? "path=" + path : "")
						+ (domain ? "; domain=" + domain : "")
						+ (secure ? "; secure" : "");
	}

	/**
	 * Получение значения cookie
	 *
	 * @param {string} name
	 * @return {string}
	 */
	Cruiser.getCookie = function(name) {
		var idx = document.cookie.lastIndexOf(name + '=');

		if (idx == -1) {
			return null;
		}

		var value = document.cookie.substring(idx + name.length + 1),
			end = value.indexOf(';');

		if (end == -1) {
			end = value.length;
		}

		value = value.substring(0, end);
		value = unescape(value);
		return value;
	}

	/**
	 * Удаление значения cookie
	 *
	 * @param {string} name
	 */
	Cruiser.deleteCookie = function(name) {
		Cruiser.setCookie(name, "-", 0);
	}


	/**
	 * ======================================================================================================
	 * Расширение функциональности используемых библиотек
	 * ======================================================================================================
	 */

	/**
	 * Расширение jQuery (создание DOM элементов)
	 **/
	jQuery.create = function() {
		if (arguments.length == 0) return [];
		var args = arguments[0] || {},
		elem = null,
		elements = null;
		var siblings = null;

		if (args == null) args = "";
		if (args.constructor == String) {
			if (arguments.length > 1) {
				var attributes = arguments[1];
				if (attributes.constructor == String) {
					elem = document.createTextNode(args);
					elements = [];
					elements.push(elem);
					siblings = jQuery.create.apply(null, Array.prototype.slice.call(arguments, 1));
					elements = elements.concat(siblings);
					return elements;

				} else {
					elem = document.createElement(args);

					var attributes = arguments[1];
					for (var attr in attributes)
					jQuery(elem).attr(attr, attributes[attr]);

					var children = arguments[2];
					children = jQuery.create.apply(null, children);
					jQuery(elem).append(children);

					if (arguments.length > 3) {
						siblings = jQuery.create.apply(null, Array.prototype.slice.call(arguments, 3));
						return [elem].concat(siblings);
					}
					return elem;
				}
			} else return document.createTextNode(args);
		} else {
			elements = [];
			elements.push(args);
			siblings = jQuery.create.apply(null, (Array.prototype.slice.call(arguments, 1)));
			elements = elements.concat(siblings);
			return elements;
		}
	};

	/**
	 * Объединение массивов
	 *     example 1: arr1 = {"color": "red", 0: 2, 1: 4}
	 *     example 1: arr2 = {0: "a", 1: "b", "color": "green", "shape": "trapezoid", 2: 4}
	 *     example 1: array_merge(arr1, arr2)
	 *     returns 1: {"color": "green", 0: 2, 1: 4, 2: "a", 3: "b", "shape": "trapezoid", 4: 4}
	 *     example 2: arr1 = []
	 *     example 2: arr2 = {1: "data"}
	 *     example 2: array_merge(arr1, arr2)
	 *     returns 2: {0: "data"}
	 *
	 * @return {array}
	 */
	function array_merge() {
		var args = Array.prototype.slice.call(arguments),
			retObj = {},
			k,
			j = 0,
			i = 0,
			retArr = true;

		for (i = 0; i < args.length; i++) {
			if (! (args[i] instanceof Array)) {
				retArr = false;
				break;
			}
		}

		if (retArr) {
			retArr = [];
			for (i = 0; i < args.length; i++) {
				retArr = retArr.concat(args[i]);
			}
			return retArr;
		}
		var ct = 0;

		for (i = 0, ct = 0; i < args.length; i++) {
			if (args[i] instanceof Array) {
				for (j = 0; j < args[i].length; j++) {
					retObj[ct++] = args[i][j];
				}
			} else {
				for (k in args[i]) {
					if (args[i].hasOwnProperty(k)) {
						if (parseInt(k, 10) + '' === k) {
							retObj[ct++] = args[i][k];
						} else {
							retObj[k] = args[i][k];
						}
					}
				}
			}
		}
		return retObj;
	}

	/**
	 * Removes duplicate values from array
	 * @param {array} inputArr
	 * @return {array}
	 */
	function array_unique(inputArr) {
		// *     example 1: array_unique(['Kevin','Kevin','van','Zonneveld','Kevin']);
		// *     returns 1: {0: 'Kevin', 2: 'van', 3: 'Zonneveld'}
		// *     example 2: array_unique({'a': 'green', 0: 'red', 'b': 'green', 1: 'blue', 2: 'red'});
		// *     returns 2: {a: 'green', 0: 'red', 1: 'blue'}

		var key = '',
		tmp_arr2 = {},
		val = '';

		var __array_search = function (needle, haystack) {
			var fkey = '';
			for (fkey in haystack) {
				if (haystack.hasOwnProperty(fkey)) {
					if ((haystack[fkey] + '') === (needle + '')) {
						return fkey;
					}
				}
			}
			return false;
		};

		for (key in inputArr) {
			if (inputArr.hasOwnProperty(key)) {
				val = inputArr[key];
				if (false === __array_search(val, tmp_arr2)) {
					tmp_arr2[key] = val;
				}
			}
		}

		return tmp_arr2;
	}

	/**
	 *
	 * example 1: array_values( {firstname: 'Kevin', surname: 'van Zonneveld'} );
	 * returns 1: ['Kevin', 'van Zonneveld']
	 * @param {object} input
	 * @return {array}
	 */
	function array_values(input) {
		var tmp_arr = [];

		for (key in input) {
			tmp_arr.push(input[key]);
		}

		return tmp_arr;
	}

	/**
	 * Return input as a new array with the order of the entries reversed
	 * @param {array} array
	 * @param {boolean} preserve_keys If set to TRUE keys are preserved.
	 * @return {array}
	 */
	function array_reverse(array, preserve_keys) {
		// http://kevin.vanzonneveld.net
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: Karol Kowalski
		// *     example 1: array_reverse( [ 'php', '4.0', ['green', 'red'] ], true);
		// *     returns 1: { 2: ['green', 'red'], 1: 4, 0: 'php'}

		var arr_len = array.length,
			newkey = 0,
			tmp_arr = {},
			key = '';
			preserve_keys = !!preserve_keys;

		for (key in array) {
			newkey = arr_len - key - 1;
			tmp_arr[preserve_keys ? key: newkey] = array[key];
		}

		return tmp_arr;
	}

	function trim(str, charlist) {
		// http://kevin.vanzonneveld.net
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: mdsjack (http://www.mdsjack.bo.it)
		// +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
		// +      input by: Erkekjetter
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +      input by: DxGx
		// +   improved by: Steven Levithan (http://blog.stevenlevithan.com)
		// +    tweaked by: Jack
		// +   bugfixed by: Onno Marsman
		// *     example 1: trim('    Kevin van Zonneveld    ');
		// *     returns 1: 'Kevin van Zonneveld'
		// *     example 2: trim('Hello World', 'Hdle');
		// *     returns 2: 'o Wor'
		// *     example 3: trim(16, 1);
		// *     returns 3: 6
		var whitespace, l = 0,
				i = 0;
		str += '';

		if (!charlist) {
			// default list
			whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
		} else {
			// preg_quote custom list
			charlist += '';
			whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
		}

		l = str.length;
		for (i = 0; i < l; i++) {
			if (whitespace.indexOf(str.charAt(i)) === -1) {
				str = str.substring(i);
				break;
			}
		}

		l = str.length;
		for (i = l - 1; i >= 0; i--) {
			if (whitespace.indexOf(str.charAt(i)) === -1) {
				str = str.substring(0, i + 1);
				break;
			}
		}

		return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
	}

	/**
	 * ======================================================================================================
	 * Управление главным окном Virab
	 * ======================================================================================================
	 */
	Cruiser.mainPageResize = function(resize, new_menu_width) {
		var cookie_val = parseInt(Cruiser.getCookie('menu_width'), 10),
			deflt_width = cookie_val ? cookie_val : 250,
			docwidth = $(".TVirabBody").width(),
			menuwidth = new_menu_width ? new_menu_width : deflt_width,
			indent = (jQuery.browser.msie ? 0 : 12);
			pagewidth = (jQuery.browser.msie ? (docwidth - menuwidth - 17) : (docwidth - menuwidth)) - indent;

		$('.TVirabLeftBox').width(menuwidth);
		$('.TVirabRightBox').width(pagewidth);
		$('.TVirabRightBox').css('left', (menuwidth + (jQuery.browser.msie ? 0 : 12)) + 'px');
	}


	$(function() {
		window.onresize = Cruiser.mainPageResize;
		// первоначальный ресайз
		Cruiser.mainPageResize();

		$(".TVirabLeftBox").resizable({
			handles: 'e',
			resize: function(event, ui) {
				var new_width = ui.size.width;
				Cruiser.setCookie('menu_width', new_width);
				Cruiser.mainPageResize(null, new_width);
			}
		});

	});

	/**
	 * ======================================================================================================
	 * Переключение режима отображения для окна узлов
	 * ======================================================================================================
	 */
	function setDspMode(mode) {
		var m = (mode === 'list') ? 'tree' : 'list';
		Cruiser.setCookie('type_visual_site_nodes', m, 999);
		window.location.reload();
	}

	
	/* 
	 * ======================================================================================================= 
	 * OLD part
	 * =======================================================================================================
	 */
	var is_load = (document.getElementById) ? true: false;
	var is_over = false;
	var currModel = 0;
	var currDealer = 0;
	
	nn4 = (document.layers) ? true: false;
	ie4 = (document.all) ? true: false;
	nn6 = (document.getElementById && !ie4) ? true: false;
	
	/**
	 * Установка названия панели
	 * @param {string} strGr - дополнительная строка
	 * @param {string} strChng - основная строка
	 * @return void
	 */
	function setPagerTitle(strGr, strChng) {
		var pgr = $("#CurrentNavPosition");
	
		if (pgr) {
			if (typeof strChng !== 'undefined') {
				pgr.html(strChng + strGr);
			} else {
				pgr.html(pgr.html() + strGr);
			}
		}
	}
	// Обратная совместимость
	setGradusnik = setPagerTitle;
	
	/**
	 * Переключение выбора всех элементов таблицы
	 *
	 * @param nameForm
	 * @return void
	 */
	function CheckAll(nameForm) {
		var checkboxes = $("table.t_virab_tree :checkbox"),
		checker = $("table.t_virab_tree input[name='allbox']"),
		flag = checker[0].checked;
	
		for (var i = 0, len = checkboxes.length; i < len; i++) {
			var chbxel = checkboxes[i];
			(chbxel.name != 'allbox' && !chbxel.disabled) ? chbxel.checked = flag: null;
		}
	}
	
	/**
	 * Подсветка строчки при наведении
	 * @param cell
	 * @return void
	 */
	function hilite(cell) {
		$(cell).addClass('trhover');
	}
	
	/**
	 * Удаление подсветки
	 * @param cell
	 * @return void
	 */
	function delite(cell) {
		$(cell).removeClass('trhover');
	}