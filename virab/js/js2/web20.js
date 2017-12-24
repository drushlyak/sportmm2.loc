new function () {
$B = new Object();
$B.TYPE_IE 		= 1;
$B.TYPE_MOZ 	= 2;
$B.TYPE_SAFARI 	= 3;
$B.TYPE_OPERA 	= 4;


var ua = navigator.userAgent.toLowerCase();
	
if(ua.indexOf("opera") != -1) {
	$B.opera = true;	
	$B.type = $B.TYPE_OPERA;
	if(ua.indexOf("opera/7") != -1 || ua.indexOf("opera 7") != -1) {
		$B.version = 7;
	}
	else if (ua.indexOf("opera/8") != -1 || ua.indexOf("opera 8") != -1) {
		$B.version=8;
	}
}
else if (ua.indexOf("msie") !=-1 && document.all) {
	$B.ie = true;
	$B.type = $B.TYPE_IE;
	if (ua.indexOf("msie 5")) { 
		$B.version = 5;
	}
}
else if(ua.indexOf("safari") != -1) {
	$B.safari = true;
	$B.type = $B.TYPE_SAFARI;
}
else if(ua.indexOf("mozilla") != -1) {
	$B.mozilla = true;
	$B.type = $B.TYPE_MOZ;
}


if(ua.indexOf("x11;")!=-1){
	$B.os_linux = true;
}
else if (ua.indexOf("macintosh")!=-1){
	$B.os_mac = true;
}

$logging = true;

var web2 = new Object();


if (! Array.prototype.indexOf) {
	/**
	* @param searchElement
	* @param fromIndex?
	*/ 
	Array.prototype.indexOf = function (searchElement, fromIndex) {
		var n = fromIndex ? fromIndex : 0;
		for (var i=n; i<this.length; i++) {
			if (this[i] == searchElement) {
				return i;
			}
		}
		return -1;
	}
}

if (! Array.prototype.lastIndexOf) {
	/**
	* @param searchElement
	* @param fromIndex?
	*/ 
	Array.prototype.lastIndexOf = function (searchElement, fromIndex) {
		var n = fromIndex ? fromIndex : 0;
		var ret = -1;
		for (var i=n; i<this.length; i++) {
			if (this[i] == searchElement) {
				ret = i;
			}
		}
		return ret;
	}
}

if (! Array.prototype.contains) {
	/**
	* @return Boolean
	*/
	Array.prototype.contains = function (item) {
		return this.indexOf(item) >= 0;
	}
}

if (! Array.prototype.every) {
	/**
	* Tests whether all elements in the array pass the test 
	* implemented by the provided function
	*
	* @param {Function} callback Function to test for each element. 
	* @param {Object} thisObject? Object to use as this when executing callback.
	* @return Boolean
	*/
	Array.prototype.every = function (callback, thisObject) {
		var r;
		for (var i=0; i<this.length; i++) {
			r = callback.call(thisObject, this[i], i, this);
			if (! Boolean(r)) return false;
		}
		return true;
	}
}

if (! Array.prototype.filter) {
	/**
	* Creates a new array with all elements that pass the test implemented by 
	* the provided function
	* 
	* @param {Function} callback Function to test for each element. 
	* @param {Object} thisObject? Object to use as this when executing callback.
	* @return Array
	*/
	Array.prototype.filter = function (callback, thisObject) {
		var ret = [];
		for (var i=0; i<this.length; i++) {
			var r = callback.call(thisObject, this[i], i, this);
			if (Boolean(r)) {
				ret.push(this[i]);
			}
		}
		return ret;
	}
}

if (! Array.prototype.forEach) {
	/**
	* Executes a provided function once per array element
	*
	* @param {Function} callback Function to test for each element. 
	* @param {Object} thisObject? Object to use as this when executing callback.
	*/
	Array.prototype.forEach = function (callback, thisObject) {
		for (var i=0; i<this.length; i++) {
			callback.call(thisObject, this[i], i, this);
		}
	}
}

if (! Array.prototype.map) {
	/**
	* Creates a new array with the results of calling a provided function on every element 
	* in this array
	*
	* @param {Function} callback Function to test for each element. 
	* @param {Object} thisObject? Object to use as this when executing callback.
	* @return Array
	*/
	Array.prototype.map = function (callback, thisObject) {
		var ret = [];
		for (var i=0; i<this.length; i++) {
			var v = callback.call(thisObject, this[i], i, this);
			ret.push(v);
		}
		return ret;
	}
}

if (! Array.prototype.some) {
	/**
	* Tests whether some element in the array passes the test implemented by the 
	* provided function
	*
	* @param {Function} callback Function to test for each element. 
	* @param {Object} thisObject? Object to use as this when executing callback.
	* @return Boolean
	*/
	Array.prototype.some = function (callback, thisObject) {
		for (var i=0; i<this.length; i++) {
			var r = callback.call(thisObject, this[i], i, this);
			if (Boolean(r)) return true;
		}
		return false;
	}
}


Object.forEach = function (thisObject, callback) 
{
	for (var key in thisObject) {
		if (!(key in Object.prototype)) {
			callback(thisObject[key], key);			
		}
	}
};

Object.merge = function (object1, object2)
{
	Object.forEach(object2, function (value, key) {
		object1[key] = value;
	});
};



px = function (a)
{
	return a + "px";
}

_nullFunction = function ()
{
	return null;
}

// Shallow-copies an array.
copyArray = function (dst, src) 
{ 
	for (var i = 0; i < src.length; ++i) {
		dst.push(src[i]);
	}
}


web2.setCookie = function(name, value, days, path, domain, secure) 
{
	var expires = -1;
	if(typeof days == "number" && days >= 0) {
		var d = new Date();
		d.setTime(d.getTime()+(days*24*60*60*1000));
		expires = d.toGMTString();
	}
	value = escape(value);
	document.cookie = name + "=" + value + ";"
		+ (expires != -1 ? " expires=" + expires + ";" : "")
		+ (path ? "path=" + path : "")
		+ (domain ? "; domain=" + domain : "")
		+ (secure ? "; secure" : "");
}

web2.getCookie = function(name) {
	// FIXME: Which cookie should we return?
	//        If there are cookies set for different sub domains in the current
	//        scope there could be more than one cookie with the same name.
	//        I think taking the last one in the list takes the one from the
	//        deepest subdomain, which is what we're doing here.
	var idx = document.cookie.lastIndexOf(name+'=');
	if(idx == -1) { return null; }
	var value = document.cookie.substring(idx+name.length+1);
	var end = value.indexOf(';');
	if(end == -1) { end = value.length; }
	value = value.substring(0, end);
	value = unescape(value);
	return value;
}

web2.deleteCookie = function(name) {
	dojo.io.cookie.setCookie(name, "-", 0);
}


window.web2 = web2;
/**
 * @package web2
 */


/**
 * An implementation of the debug log. 
 * 
 * Originally developed by Google (c) 2005 as a part of
 * ajaxxslt library
 * 
 * @link http://ajaxxslt.sourceforge.net/
 */
 
var Log = {};

Log.contentDocument = document;

Log.lines = [];

Log.write = function(s) {
  if ($logging) {
    this.lines.push(Xml.escapeText(s));
    this.show();
  }
};

// Writes the given XML with every tag on a new line.
Log.writeXML = function(xml) {
  if ($logging) {
    var s0 = xml.replace(/</g, '\n<');
    var s1 = web2.Xml.escapeText(s0);
    var s2 = s1.replace(/\s*\n(\s|\n)*/g, '<br/>');
    this.lines.push(s2);
    this.show();
  }
}

// Writes without any escaping
Log.writeRaw = function(s) {
  if ($logging) {
    this.lines.push(s);
    this.show();
  }
}

Log.writeURL = function (url) {
	if ($logging) {
		this.lines.push("<a href="+url+">"+url+"</a>");
		this.show();
	}
}

Log.clear = function() {
  if ($logging) {
    var l = this.div();
    l.innerHTML = '';
    Log.lines = [];
  }
}

Log.dump = function (s) {
	if ($logging) {
		try {
			this.lines.push(s.toString());
		}
		catch (e) {
			Log.write(Error().stack);
		}
		this.show();
	}
}

Log.show = function() {
  var l = this.div();
  l.innerHTML += this.lines.join('<br/>') + '<br/>';
  Log.lines = [];
  l.scrollTop = l.scrollHeight;
}

Log.div = function() {
  var l = this.contentDocument.getElementById("log");
  if (!l) {
    l = this.contentDocument.createElement('div');
    l.id = 'log';
    l.style.position = 'absolute';
    l.style.right = '5px';
    l.style.top = '5px';
    l.style.width = '300px';
    l.style.height = '200px';
    l.style.overflow = 'auto';
    l.style.backgroundColor = '#f0f0f0';
    l.style.border = '1px solid gray';
    l.style.fontSize = '10px';
    l.style.padding = '5px';
    this.contentDocument.body.appendChild(l);
	//new web2.ui.DragObject(l);
  }
  return l;
}


window.web2.Log = Log;
/**
 * @package web2
 */

/**
 * Originally developed by Google (c) 2005 as a part of
 * ajaxxslt library
 * 
 * @link http://ajaxxslt.sourceforge.net/
 */

DOM_ELEMENT_NODE = 1;
DOM_ATTRIBUTE_NODE = 2;
DOM_TEXT_NODE = 3;
DOM_CDATA_SECTION_NODE = 4;
DOM_ENTITY_REFERENCE_NODE = 5;
DOM_ENTITY_NODE = 6;
DOM_PROCESSING_INSTRUCTION_NODE = 7;
DOM_COMMENT_NODE = 8;
DOM_DOCUMENT_NODE = 9;
DOM_DOCUMENT_TYPE_NODE = 10;
DOM_DOCUMENT_FRAGMENT_NODE = 11;
DOM_NOTATION_NODE = 12;

var Xml = {};

/**
 * Returns the text value if a node; for nodes without children this
 * is the nodeValue, for nodes with children this is the concatenation
 * of the value of all children.
 */
Xml.value = function (node) 
{
  if (!node) {
    return '';
  }

  var ret = '';
  if (node.nodeType == DOM_TEXT_NODE ||
      node.nodeType == DOM_CDATA_SECTION_NODE ||
      node.nodeType == DOM_ATTRIBUTE_NODE) {
    ret += node.nodeValue;

  } else if (node.nodeType == DOM_ELEMENT_NODE ||
             node.nodeType == DOM_DOCUMENT_NODE ||
             node.nodeType == DOM_DOCUMENT_FRAGMENT_NODE) {
    for (var i = 0; i < node.childNodes.length; ++i) {
      ret += arguments.callee(node.childNodes[i]);
    }
  }
  return ret;
}

// 
/**
 * Returns the representation of a node as XML text.
 * 
 * @param {Node}
 * @return {String}
 */
Xml.text = function (node) 
{
  var ret = '';
  if (node.nodeType == DOM_TEXT_NODE) {
    ret += Xml.escapeText(node.nodeValue);
    
  } else if (node.nodeType == DOM_ELEMENT_NODE) {
    ret += '<' + node.nodeName;
    for (var i = 0; i < node.attributes.length; ++i) {
      var a = node.attributes[i];
      if (a && a.nodeName && a.nodeValue) {
        ret += ' ' + a.nodeName;
        ret += '="' + Xml.escapeAttr(a.nodeValue) + '"';
      }
    }

    if (node.childNodes.length == 0) {
      ret += '/>';

    } else {
      ret += '>';
      for (var i = 0; i < node.childNodes.length; ++i) {
        ret += arguments.callee(node.childNodes[i]);
      }
      ret += '</' + node.nodeName + '>';
    }
    
  } else if (node.nodeType == DOM_DOCUMENT_NODE || 
             node.nodeType == DOM_DOCUMENT_FRAGMENT_NODE) {
    for (var i = 0; i < node.childNodes.length; ++i) {
      ret += arguments.callee(node.childNodes[i]);
    }
  }
  
  return ret;
}


/**
 * Escape XML special markup chracters: tag delimiter < > and entity
 * reference start delimiter &. The escaped string can be used in XML
 * text portions (i.e. between tags).
 * 
 * @param {String}
 * @return {String}
 */
Xml.escapeText = function (s) 
{
	return s.toString().replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}


/**
 * Escape XML special markup characters: tag delimiter < > entity
 * reference start delimiter & and quotes ". The escaped string can be
 * used in double quoted XML attribute value portions (i.e. in
 * attributes within start tags).
 * 
 * @param {String}
 * @return {String}
 */
Xml.escapeAttr = function (s) 
{
  return Xml.escapeText(s).replace(/\"/g, '&quot;');
}


/**
 * Escape markup in XML text, but don't touch entity references. The
 * escaped string can be used as XML text (i.e. between tags).
 * 
 * @param {String}
 * @return {String}
 */
Xml.escapeTags = function (s) 
{
	return s.toString().replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/**
 * Parse XML string and create XMLDocument
 * 
 * @param {String}
 * @return {XMLDocument}
 */
Xml.parse = function (a) 
{
    try {
        if (ActiveXObject && GetObject) {
            var b = new ActiveXObject("Microsoft.XMLDOM");
            b.loadXML(a);
            return b;
        }
        else if (DOMParser) {
            return (new DOMParser()).parseFromString(a, "text/xml");
        }
        else{
			// AJAXXSLT
        }
    } 
    catch(e) {
        Log.write(e.toString());
        Log.incompatible("xmlparse");
        return document.createElement("div");        
    }
}


window.web2.Xml = Xml;
new function () {
/**
 * @namespace web2.ajax
 * 
 * Based on Prototype ajax library. It's great one
 */

var ajax = 
{
	listeners : [],
	events : [
		'Uninitialized', 'Open', 'Sent', 'Receiving', 'Loaded', /** XMLHttp events */ 
		"Abort", "Success", "Failure", "Error", /** Additional useful events */
		"Done" /** Finalization event */
	],
	riseToDoneEvents : ["Abort", "Success", "Failure", "Error"],
	activeRequestCount : 0
};

/**
 * Add readystatechange listener 
 * 
 * Sample listener object
 * <sample>
 * {
 * 	onUninitialized : function (request) {},
 *  onOpen : function (request) {},
 * 	onSent : function (request) {},
 *  onReceiving : function (request) {},
 * 	onLoaded : function (request) {},
 * 	onSuccess : function (request, responseData) {},
 *  onFailure : function (request) {},
 * 	onError : function (request, error) {},
 * 	onAbort : function (request) {},
 * 	onDone : function (request) {},
 * 	onXXX : function (request) // http status handlers 
 * }
 * </sample>
 * 
 * @param {Object} listener 
 * @return {Boolean} true on success 
 */
ajax.addListener = function (listener) 
{
	if (this.listeners.indexOf(listener) < 0) {
		this.listeners.push(listener);
		return true;		
	}
}

/**
 * 
 * @alias web2.ajax.removeListener
 * @param {Object} listener
 * @return {Boolean} true on success
 */
ajax.removeListener = function (listener) 
{
	var c = this.listeners.indexOf(listener);
	if (c >= 0) {
		this.listeners.splice(c,1);
		return true;
	}
}

/**
 * Clears all listeners
 * @alias web2.ajax.clearListeners
 */
ajax.clearListeners = function ()
{
	this.listeners = [];
}

ajax.fireEvent = function (eventName)
{
	var funcName = "on" + eventName;
	var args = [];
	for (var i=1; i<arguments.length; i++) {
		args.push(arguments[i]);
	}
	this.listeners.forEach(function (listener) {
		if (listener[funcName]) {
			try {
				listener[funcName].apply(listener, args);
			} catch (e) { web2.Log.write(e); }
		}
	});
}

ajax.getTransport = function ()
{
    if (typeof ActiveXObject!="undefined") {
        try {
			return new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {}
    }
    if (typeof XMLHttpRequest!="undefined") {
		return new XMLHttpRequest();
    }
    return null;
};

/**
 * Process like this
 * 
 * <code>param</code> values:
 * 
 * <code>method</code> <type>String</type> get|post	HTTP request method;
 * <code>async</code> <type>Boolean</type> asynchronous request flag;
 * <code>json</code> <type>Boolean</type> JSON request flag;
 * <code>timeout</code> <type>Number</type> request timeout in millisecconds;
 * <code>data</code> <type>String</type> data, that will be sent;
 * @alias web2.ajax.request
 * @param {Object} url
 * @param {Object} params
 * @return {Request}
 *
 * Sample usage
 * 
 * var r = web2.ajax.createRequest("http://mydomain/path/to/file", {
 * 	method : "post",
 *  async : true,
 * 	json : true,
 * 	data : requestData.toJSONString()
 * });
 * r.addListener({
 * 	onSuccess : function (xmlhttp, responseData) {
 * 		alert(responseData);       
 * 	},
 * 	onError : function (xmlhttp, error) {
 * 		alert("Error: " + error);
 * 	}
 * });
 * r.send();
 */
ajax.createRequest = function (url, params)
{
	ajax.activeRequestCount++;
	return new Request(url, params);
};

ajax.get = function (url, params)
{
	params = params || {};
	Object.merge(params, {"method" : "get"});
	return ajax.createRequest(url, params);
};

ajax.post = function (url, params)
{
	params = params || {};
	Object.merge(params, {"method" : "post"});
	return ajax.createRequest(url, params);
};

ajax.createIFrame = function (frameId)
{
	var f = web2.Dom.get(frameId);
	if (f && f.nodeName.toLowerCase() == "iframe") {
		return f;
	}
	
	f = document.createElement("iframe");
    f.id = frameId;
    f.name = frameId;	

	with (f.style) {
		position = 'absolute';
		left = top = '0px';
		height = width = '1px';
		visibility = 'hidden';		
	}
	
	document.body.appendChild(f);
	
	return f;
};

var ajaxStdListener = {
	onDone : function () {
		ajax.activeRequestCount--;
	}
};
ajax.addListener(ajaxStdListener);

/**
 * 
 * @param {Object} url
 * @param {Object} params
 * @return {Request}
 * @constructor
 */
function Request (url, params)
{
	this.transport = ajax.getTransport();
	this.url = url;
	this.params = {
		"method" 	: "post",
		"async"  	: true,
		"data"   	: "",
		"timeout" 	: 30000
	};
	Object.merge(this.params, params || {});
	this.clearListeners();
};

/**
 * Register listener object, for <code>request</code> events set
 * @alias addListener
 * @param {String} eventName
 * @param {Object} listener 
 */
Request.prototype.addListener = ajax.addListener;

/**
 * Removes listener that was installed using <code>addListener()</code>
 * @alias removeListener
 * @param {Object}
 */
Request.prototype.removeListener = ajax.removeListener;

/**
 * Removes all event listeners that was installed using <code>addListener()</code>
 * @alias clearListeners
 */
Request.prototype.clearListeners = ajax.clearListeners;

Request.prototype.fireEvent = function (eventName)
{
	//web2.Log.write('event fired ' + eventName + ' for ' + this.params.data['action']);
	
	var funcName = "on" + eventName;
	var args = [];
	for (var i=1; i<arguments.length; i++) {
		args.push(arguments[i]);
	}
	this.listeners.forEach(function (listener) {
		if (listener[funcName]) {
			try {
				listener[funcName].apply(listener, args);
			} catch (e) { web2.Log.write(e); }
		}
	});
	ajax.fireEvent.apply(ajax, arguments);
	
	if (ajax.riseToDoneEvents.contains(eventName)) {
		args.unshift("Done");
		this.fireEvent.apply(this, args);
	}
	
};

Request.prototype.send = function ()
{
	var transport = this.transport;
	var params = this.params;
	try {
		if (params.method == 'get') {
			this.url += (this.url.match(/\?/) ? '&' : '?') + "__rand=" + (new Date()).getTime();
		}

		if (params.async) {
			transport.onreadystatechange = web2.event.callback(this, this.onStateChange); 
		}
		transport.open(params.method, this.url, params.async);
		this.setHeaders();
		this.timeoutId = web2.win.setTimeout(this, this.onAbort, params.timeout);
		return transport.send(params.method == 'post' ? this.encodeData() : null);
    } 
	catch (e) {
		ajax.fireEvent("Error", this, e);
    }
};

Request.prototype.encodeData = function ()
{
	if (typeof this.params.data != "string") {
		var ret = [];
		Object.forEach(this.params.data, function (value, key) {
			ret.push(escape(key) + "=" + escape(value));
		});
		return ret.join("&");
	}
	return this.params.data;
};

Request.prototype.setHeaders = function() 
{
	var transport = this.transport;
	var requestHeaders = {};
	Object.merge(requestHeaders, this.params.headers || {}); 

	if (this.params.json) {
		requestHeaders['Content-type'] = "application/x-json";
	}

    if (this.params.method == "post" && (!requestHeaders["Content-type"])) {
		requestHeaders["Content-type"] = "application/x-www-form-urlencoded";  

		/** 
		* Force "Connection: close" for Mozilla browsers to work around
		* a bug where XMLHttpReqeuest sends an incorrect Content-length
		* header. See Mozilla Bugzilla #246651. 
		*/
		if (transport.overrideMimeType) {
			requestHeaders["Connection"] = "close";
		}
    }

	Object.forEach(requestHeaders, function (value, key) {
		transport.setRequestHeader(key, value);		
	});
};

Request.prototype.onStateChange = function ()
{
	var transport = this.transport;
	var state = ajax.events[transport.readyState]; 
	if (state == "Loaded") {
		this.fireEvent(transport.status.toString(), this);		
	}
	
	this.fireEvent(state, this);
	if (state == "Loaded") {
		transport.onreadystatechange = _nullFunction;
		clearTimeout(this.timeoutId);
		var response = new Response(this);
		response.process();
	}
};

Request.prototype.onAbort = function ()
{
	clearTimeout(this.timeoutId);
	this.transport.onreadystatechange = _nullFunction;
	this.transport.abort();
	this.fireEvent("Abort", this);
}

function Response (request)
{
	this.request = request;
	this.transport = request.transport;
}

Response.prototype.fireEvent = function (eventName)
{
	this.request.fireEvent.apply(this.request, arguments);
}

Response.prototype.isSuccess = function ()
{
    var transport = this.transport;
	return 	transport.status == undefined || 
			transport.status == 0 || (transport.status >= 200 && transport.status < 300);
}

Response.prototype.process = function ()
{
	var transport = this.transport;
	if (!this.isSuccess()) {
		this.fireEvent("Failure", this.request);
		this.request.clearListeners();		
		return;
	}
	
	var responseData = transport.responseText;

	var contentType = transport.getResponseHeader("Content-type") || "";
	try {
		if (contentType.match(/^text\/javascript/i)) {
			eval(transport.responseText);
		}
		else if (contentType.match(/^application\/x-json/i)) {
			responseData = responseData.parseJSON();	
		}
		else if (contentType.match(/^text\/xml/i)) {
			responseData = web2.Xml.parse(transport.responseText); 
		}
		this.fireEvent("Success", this.request, responseData); 
	} catch (e) {
		this.fireEvent("Error", this.request, e);		
	} finally {
		this.request.clearListeners();		
	}
}

window.web2.ajax = ajax;
}

new function () {
/**
 * @package web2.CSS
 */

var css = {};

/**
* Normalizes currentStyle and ComputedStyle.
* @param {HTMLElement} Accepts a DOM reference.
* @param {String} property The style property whose value is returned.
* @return {String} The current value of the style property.
*/
css.getStyle = function (el, property) 
{
	var value = null;
	var dv = document.defaultView;

	if (property == 'opacity' && el.filters) { // IE opacity
		value = 1;
		try {
			value = el.filters.item('DXImageTransform.Microsoft.Alpha').opacity / 100;
		} catch(e) {
        	try {
           		value = el.filters.item('alpha').opacity / 100;
			} catch(e) {}
		}
	}
	else if (el.style[property]) {
		value = el.style[property];
	}
	else if (el.currentStyle && el.currentStyle[property]) {
		value = el.currentStyle[property];
	}
	else if ( dv && dv.getComputedStyle )  {  
		// convert camelCase to hyphen-case

		var converted = '';
		for(i = 0, len = property.length;i < len; ++i) {
			if (property.charAt(i) == property.charAt(i).toUpperCase()) {
				converted = converted + '-' + property.charAt(i).toLowerCase();
			} else {
				converted = converted + property.charAt(i);
			}
		}
		
		if (dv.getComputedStyle(el, '').getPropertyValue(converted)) {
			value = dv.getComputedStyle(el, '').getPropertyValue(converted);
		}
	}

	return value;
}


/**
* Wrapper for setting style properties of HTMLElements. Normalizes "opacity" across modern browsers.
* @param {HTMLElement} Accepts a DOM reference
* @param {String} property The style property to be set.
* @param {String} val The value to apply to the given property.
*/
css.setStyle = function (el, property, value) 
{
	if (typeof property == 'object') {
		for (var p in property) {
			css.setStyle(el, p, property[p]);
		}
		return;
	}
	
	switch(property) {
		case 'opacity' :
			if (el.filters) {
				el.style.filter = 'alpha(opacity=' + value * 100 + ')';

				if (! el.currentStyle.hasLayout) {
					el.style.zoom = 1;
				}
				
			} else {
				el.style.opacity = value;
				el.style['-moz-opacity'] = value;
				el.style['-khtml-opacity'] = value;
			}
        	break;
        	
		default :
			el.style[property] = value;
	}
}

css.copyStyle = function (source, dest, properties)
{
	properties.forEach(function (prop) {
		dest.style[prop] = css.getStyle(source, prop);
	});
}


css.getClasses = function (el)
{
	return el.classNames.split(" ");
}

css.setClass = function (el, className) 
{
	el.className = className;
}

css.addClass = function (el, className) 
{
	if (! css.hasClass(el, className)) {
		el.className += el.className ? " " + className : className;
	}	
}

css.removeClass = function (el, className) 
{
	el.className = el.className.replace(new RegExp("\\s?"+className), "");
}

css.hasClass = function (el, className) 
{
	return new RegExp("(^|\\s)" + className + "(\\s|$)").test(el.className);
}

css.replaceClass = function (el, search, replace) 
{
	return el.className.replace(search, replace);
}


window.web2.css = css;
}

new function () {
/**
 * @package web2.dom
 */

var dom = {}

dom.get = function (id) 
{
	var ret = [];
	for (var i=0; i<arguments.length; i++) {
		var a = arguments[i];
		if (typeof a == "string") {
			a = document.getElementById(a);
		}
		ret.push(a);
	}
	
	if (arguments.length == 1) {
		return ret[0];
	}
	return ret;
}

dom.getBySelector = function (selector, context)
{
  	// Split selector in to tokens
	var tokens = selector.split(' ');
	var currentContext = new Array(context || document);
	
	for (var i = 0; i < tokens.length; i++) {
    	token = tokens[i].replace(/^\s+/,'').replace(/\s+$/,'');;
    	if (token.indexOf('#') > -1) {
			// Token is an ID selector
			var bits = token.split('#');
			var tagName = bits[0];
			var id = bits[1];
			var element = document.getElementById(id);
			if (tagName && element.nodeName.toLowerCase() != tagName) {
				// tag with that ID not found, return false
				return new Array();
			}
		
			// Set currentContext to contain just this element
			currentContext = new Array(element);
			continue; // Skip to next token
    	}
    
	    if (token.indexOf('.') > -1) {
			// Token contains a class selector
			var bits = token.split('.');
			var tagName = bits[0];
			var className = bits[1];
			
			if (!tagName) {
	        	tagName = '*';
	      	}
	      	
			// Get elements matching tag, filter them for class selector
	      	var found = new Array;
	      	var foundCount = 0;
			for (var h = 0; h < currentContext.length; h++) {
				var elements;
				if (tagName == '*') {
					elements = dom.all(currentContext[h]);
				} 
				else {
					elements = currentContext[h].getElementsByTagName(tagName);
				}
				
				for (var j = 0; j < elements.length; j++) {
					found[foundCount++] = elements[j];
				}
			}
			
			currentContext = new Array;
			var currentContextIndex = 0;
			for (var k = 0; k < found.length; k++) {
				if (found[k].className && found[k].className.match(new RegExp('\\b'+className+'\\b'))) {
	          		currentContext[currentContextIndex++] = found[k];
				}
			}
			
			continue; // Skip to next token
		}
	
	    // Code to deal with attribute selectors
	    if (token.match(/^(\w*)\[(\w+)([=~\|\^\$\*]?)=?"?([^\]"]*)"?\]$/)) {
			var tagName = RegExp.$1;
			var attrName = RegExp.$2;
			var attrOperator = RegExp.$3;
			var attrValue = RegExp.$4;
			
			if (!tagName) {
				tagName = '*';
			}
			
			// Grab all of the tagName elements within current context
			var found = new Array;
			var foundCount = 0;
			for (var h = 0; h < currentContext.length; h++) {
	        	var elements;
	        	if (tagName == '*') {
	            	elements = dom.all(currentContext[h]);
	        	} else {
	            	elements = currentContext[h].getElementsByTagName(tagName);
	        	}
	        	
	        	for (var j = 0; j < elements.length; j++) {
	          		found[foundCount++] = elements[j];
	        	}
	      	}
	      	
	      	currentContext = new Array;
	      	var currentContextIndex = 0;
	      	var checkFunction; // This function will be used to filter the elements
			switch (attrOperator) {
	        	case '=': // Equality
	          		checkFunction = function(e) { return (e.getAttribute(attrName) == attrValue); };
	          		break;
	        	
	        	case '~': // Match one of space seperated words 
	          		checkFunction = function(e) { return (e.getAttribute(attrName).match(new RegExp('\\b'+attrValue+'\\b'))); };
	          		break;
	          	
	        	case '|': // Match start with value followed by optional hyphen
					checkFunction = function(e) { return (e.getAttribute(attrName).match(new RegExp('^'+attrValue+'-?'))); };
	          		break;
	          	
				case '^': // Match starts with value
	          		checkFunction = function(e) { return (e.getAttribute(attrName).indexOf(attrValue) == 0); };
	          		break;
	          		
				case '$': // Match ends with value - fails with "Warning" in Opera 7
	          		checkFunction = function(e) { return (e.getAttribute(attrName).lastIndexOf(attrValue) == e.getAttribute(attrName).length - attrValue.length); };
	          		break;
	          		
	        	case '*': // Match ends with value
	          		checkFunction = function(e) { return (e.getAttribute(attrName).indexOf(attrValue) > -1); };
	          	break;
	          	
	        	default :
	          		// Just test for existence of attribute
	          		checkFunction = function(e) { return e.getAttribute(attrName); };
	      	}
	      	
			currentContext = new Array;
			var currentContextIndex = 0;
	      	for (var k = 0; k < found.length; k++) {
	        	if (checkFunction(found[k])) {
	          		currentContext[currentContextIndex++] = found[k];
	        	}
	      	}
	      	
	      	// alert('Attribute Selector: '+tagName+' '+attrName+' '+attrOperator+' '+attrValue);
	      	continue; // Skip to next token
	    }
    
	    if (!currentContext[0]){
	    	return;
	    }
    
	    // If we get here, token is JUST an element (not a class or ID selector)
	    tagName = token;
	    var found = new Array;
	    var foundCount = 0;
	    for (var h = 0; h < currentContext.length; h++) {
			var elements = currentContext[h].getElementsByTagName(tagName);
			for (var j = 0; j < elements.length; j++) {
	        	found[foundCount++] = elements[j];
	      	}
	    }
	    
    	currentContext = found;
  	}
  	
  	return currentContext;
}

/**
 * Wrapper function for creating new nodes
 * 
 * @param {String} tagName Tag of new element
 * @param {Object} attributes	hashtable of object attributes. events not setuped in this case 
 * @param {Object} events	hashtable of event names and listener functions
 * @param {Object} styles	hashtable|array of styles. in hastable mode style property 
 * must have scripting name i.e. `borderWidth`, not border-width. And in array mode 
 * values must be like this `border-width:2px`  
 * @param {HTMLElement} parentNode 
 */
dom.createElement = function (tagName, attributes, styles, events, parentNode)
{
	var n = document.createElement(tagName);
	
	if (attributes) {
		for (var attrName in attributes) {
			var attrVal = attributes[attrName];
			if (attrName == "class") {
				n.className = typeof attrVal == "function" ? attrVal() : attrVal;
			} else {
				n.setAttribute(attrName, typeof attrVal == "function" ? attrVal() : attrVal);				
			}
		}
	}

	if (styles) {
		if (styles instanceof Array) {
			var cssText = styles.join(";");
			if ($B.ie) {
				n.style.cssText = cssText;
			} else {
				n.style = cssText;
			}
		} else {
			web2.css.setStyle(n, styles);			
		}
	}
	
	if (events) {
		for (var eventName in events) {
			var listenerFn = events[eventName];
			web2.event.addDomListener(n, eventName, listenerFn);
		}
	}
	
	if (parentNode) {
		parentNode.appendChild(n);
	}
	
	return n;
}

dom.hasParent = function (htmlNode) 
{
	if (! htmlNode.parentNode) return false;
	
	/**
	 * When you remove an element from the parent in IE it makes the parent
	 * of the element a document fragment.
	 */
	if(htmlNode.parentNode.nodeType == DOM_DOCUMENT_FRAGMENT_NODE) return false;
		
	return true;	
}

dom.ancestors = function (htmlNode)
{
	var ret = [];
	
	var node = htmlNode;
	while (dom.hasParent(node)) {
		node = node.parentNode;
		ret.push(node);
	}

	return ret;
}

dom.all = function (htmlNode) 
{
	return htmlNode.all ? htmlNode.all : htmlNode.getElementsByTagName("*");
}

dom.remove = function (htmlNode) 
{
	if (htmlNode.removeNode) {
		return htmlNode.removeNode(true);
	}
	
	return htmlNode.parentNode ? htmlNode.parentNode.removeChild(htmlNode) : htmlNode;
}

dom.removeChildren = function (htmlNode)
{
	while (htmlNode.hasChildren) {
		htmlNode.removeChild(htmlNode.firstChild);
	}		
}

/**
 * Устанавливает URL для загрузки
 * 
 * @param {HTMLIFrameElement} элемент <iframe>
 * @param {String} URL для загрузки
 */
dom.setIFrameSrc = function (iframeNode, src) 
{
	if ($B.opera && $B.version == 7) {
        iframeNode.src = src;
    }
    else {
        var w = dom.getIFrameWindow(iframeNode);
        w.location.replace(src);
    }	
}

/**
 * Возвращает объект window связанный с <iframe>
 * 
 * @param {HTMLIFrameElement} элемент <iframe>
 * @return {Window}
 */
dom.getIFrameWindow = function (iframeNode)
{
	if(iframeNode.contentWindow){
        return iframeNode.contentWindow;
    }
    
    return window[iframeNode.id];	
}


dom.wrap = function (htmlNode, wrapperNode)
{
	var parent = htmlNode.parentNode;
	dom.remove(htmlNode);
	dom.remove(wrapperNode);
	wrapperNode.appendChild(htmlNode);
	parent.appendChild(wrapperNode);
}

dom.unwrap = function (htlNode, wrapperNode)
{
	
}



dom.setInnerHTML = function (el, html, next)
{
 if (el && html && (html != '')) {
	try {
		if (next) { 
			// run exception for recurse calls
			next.setAttribute(""); 
		}
		el.innerHTML = html;
	}
	catch (e) {
	
		var reg_html_el = /\<([^ >]*)([^\>]*)\>([\w\W\s]*?([ ]?\<\/\1\>[ ]?){0,})\<\/\1\>/img;
		
		do {
			var match = reg_html_el.exec(html);
			if (match) {
				var tag_name = match[1];
				var tag_param = match[2];
				var childs = match[3];
				// make element
				var element = document.createElement(tag_name);
				web2.dom.setElementAttribute(element,tag_param);
				el.appendChild(element);
				
				posdouble = childs.search(/\<([^ >]*)([^\>]*)\>([\w\W\s]*?([ ]?\<\/\1\>[ ]?){0,})\<\/\1\>/img);
				while ( posdouble != -1 ) {
					
					if (posdouble != 0) {
						// first nodes (text, single)
						var fnodes = childs.substr(0,posdouble);
						childs = childs.replace(fnodes,"");
						// first text node
						fnodes = web2.dom.extractCreateTextElement(element,fnodes);
						// single HTML elements
						
						web2.dom.createSingleHtmlTag(element,fnodes);
					}
					var doubletag = childs.match(/\<([^ >]*)([^\>]*)\>([\w\W\s]*?([ ]?\<\/\1\>[ ]?){0,})\<\/\1\>/img)[0];
					// recurse call
					web2.dom.setInnerHTML(element,doubletag,true);
					
					childs = childs.substr(doubletag.length,childs.length-doubletag.length);
					posdouble = childs.search(/\<([^ >]*)([^\>]*)\>([\w\W\s]*?([ ]?\<\/\1\>[ ]?){0,})\<\/\1\>/img);
				}
				if (childs.length != 0) {
						// first text node
						childs = web2.dom.extractCreateTextElement(element,childs);
						// single HTML elements
						web2.dom.createSingleHtmlTag(element,childs);
				}
				
			}

		} while (match);
	}
  }	
};

/**
 * Extract and create (append) text element from string like "5<br /><hr />"
 * 
 * @param {Object} el Parent element
 * @param {String} str Input string
 * @return {String}
 */
dom.extractCreateTextElement = function (el, str)
{
  if (el && str && (str != '')) {
	var _string = str;
	var pos = _string.search('<');
	var res_txt = ((pos != -1) ? _string.substr(0,pos) : _string );
	_string = _string.replace(res_txt,"");
	if (res_txt != "") {
		res_txt = res_txt.replace(/^[ \s]{0,}/img," ");
		var txt = document.createTextNode(res_txt);
		el.appendChild(txt);
	}
	return _string;
  }
}

/**
 * Create single HTML Tags
 * Sample input string: "<br /><hr />55<br />" 
 * !important! tag must be in style: <nametag />
 * 
 * @param {Object} el Parent Node 
 * @param {String} str Input string
 */
dom.createSingleHtmlTag = function (el, str)
{
   	if (el && str && (str != '')) {
	var reg_hmtl_single_el = /\<([^ >]*) ([^\>]*)\/[ ]?\>/img
	var wstr = str;
	do {
		var matchsingle = reg_hmtl_single_el.exec(str);
		if (matchsingle) {
			var single_name = matchsingle[1];
			var single_param = matchsingle[2];
			// make single element
			var s_element = document.createElement(single_name);
			web2.dom.setElementAttribute(s_element,single_param);
			el.appendChild(s_element);
			
			// cut-create text node
			wstr = wstr.replace(matchsingle[0],"");
			wstr = web2.dom.extractCreateTextElement(el,wstr);
		}
	} while (matchsingle);
  }		
}

/**
 * Set attributes for element
 * !important! tags attributes must be in style: name="value" or name='value'
 * 
 * @param {Object} el Element
 * @param {string} attr String of attributes 
 */
dom.setElementAttribute = function (el, attr)
{
  	if (el && attr && (attr != '')) {
	
	var reg_attr_el = /([^\s=]+)=[\"\']?([^"]*)[\"\']?[^ >]/img;
		
	var dhtml_events = new Array (
          "onabort", "onafterprint", "onafterupdate", 
          "onbeforecopy", "onbeforecut", "onbeforeeditfocus",
          "onbeforepaste", "onbeforeprint", "onbeforeunload", 
          "onbeforeupdate", "onblur", "onbounce", 
          "oncellchange", "onchange", "onclick", 
          "oncontextmenu", "oncopy", "oncut", 
          "ondataavailable", "ondatasetchanged", "ondatasetcomplete", 
          "ondblclick", "ondrag", "ondragend", 
          "ondragenter", "ondragleave", "ondragover", 
          "ondragstart", "ondrop", "onerror", 
          "onerrorupdate", "onfilterchange", "onfinish", 
          "onfocus", "onhelp", "onkeydown", 
          "onkeypress", "onkeyup", "onload", 
          "onlosecapture", "onmousedown", "onmousemove", 
          "onmouseout", "onmouseover", "onmouseup", 
          "onpaste", "onpropertychange", "onreadystatechange", 
          "onreset", "onresize", "onrowenter", 
          "onrowexit", "onrowsdelete", "onrowsinserted", 
          "onscroll", "onselect", "onselectstart", 
          "onstart", "onstop", "onsubmit", "onunload"
    );
	
	do {
	 attr_match = reg_attr_el.exec(attr);
		if (attr_match) {
			var attrname = attr_match[1];
			var attrval = attr_match[2];
			
			//web2.Log.write('Tag: '+el.nodeName);
			//web2.Log.write('attrname:'+attrname);
			//web2.Log.write('attrval:'+attrval);
			
			if (dhtml_events.contains(attrname)) {
				web2.event.addDomListener(el, attrname.substring(2), new Function(attrval)); 
			}
			else if (attrname == "style") {
				el.style.cssText = attrval;
			}
			else if (attrname == "class" && $B.ie) {
				el.className = attrval;
			}
			else {
				el.setAttribute(attrname, attrval);
			}			
			
	  	}
	} while (attr_match);
   }
}


dom.setOuterHTML = function (el, html)
{
   	if (el && html && (html != '')) {
	
		if (typeof(el.outerHTML) != 'undefined') {
   			// IE:
			var reg_html_el = /\<([^ >]*)([^\>]*)\>([\w\W\s]*?([ ]?\<\/\1\>[ ]?){0,})\<\/\1\>/img;
			var match = reg_html_el.exec(html);
			if (match) {
				var tag_name = match[1];
				var tag_param = match[2];
				var childs = match[3];
				
				// make element
				var element = document.createElement(tag_name);
				web2.dom.setElementAttribute(element,tag_param);
				el.parentNode.replaceChild(element, el);
			
				//innerHTML
				web2.dom.setInnerHTML(element,childs);
				return element;
			}
		} else {
   			// Mozilla:
			var range = document.createRange();
   			range.setStartBefore(el);
			var fragment = range.createContextualFragment(html);
   			el.parentNode.replaceChild(fragment, el);
			return fragment;
   		}
	}
};




window.web2.dom = dom;
}

new function () {
var elem = {};

/**
 * Get some user data from <code>HTMLElement</code> previously set by <method>setUserData</method>
 * @param {HTMLElement} el
 * @param {String} key
 * @return {Object}
 * It's implementation for a part of DOM3 specification Element.getUserData
 * Mostly modern browsers doesn't support it.
 */
elem.getUserData = function (el, key) 
{
	return el["_ud__"+key];
};

/**
 * Set some user data to <code>HTMLElement</code> 
 * @param {HTMLElement} el
 * @param {String} key
 * @param {Object} value
 * It's implementation for a part of DOM3 specification Element.setUserData
 * Mostly modern browsers doesn't support it.
 */
elem.setUserData = function (el, key, value) 
{
	el["_ud__"+key] = value;
};

/**
 * Shows each passed <code>HTMLElement</code>
 * @param {HTMLElement} el 
 */
elem.show = function (el) 
{
	for (var i=0; i<arguments.length; i++) {
		var n = web2.dom.get(arguments[i]);
		n.style.display = '';
	}
};

/**
 * Hides each passed <code>HTMLElement</code>
 * @param {HTMLElement} el
 */
elem.hide = function (el) 
{
	for (var i=0; i<arguments.length; i++) {
		var n = web2.dom.get(arguments[i]);
		n.style.display = 'none';
	}
};

/**
 * Check <code>el</code> visible
 * @param {HTMLElement} el
 */
elem.visible = function (el) 
{
	var n = web2.dom.get(el);
	return web2.css.getStyle(n, "display") != 'none';
};

/**
 * Toggles the visibility of each passed <code>HTMLElement</code>
 * @param {HTMLElement} el
 */
elem.toggle = function (el) 
{
	for (var i=0; i<arguments.length; i++) {
		var n = web2.dom.get(arguments[i]);
		elem.visible(n) ? elem.hide(n) : elem.show(n);
	}
};

/**
 * Make <code>el</code> flow
 * @param {HTMLElement} el
 */
elem.makeFlow = function (el)
{
	var pos = web2.css.getStyle(el, 'position');
	if (pos == 'static' || !pos) {
		el.style.position = 'relative';
		if ($B.opera) {
			/**
			 * Opera returns the offset relative to the positioning context, when an
			 * element is position relative but top and left have not been defined
			 */
			web2.css.setStyle(el, {top : '0', left : '0'});
		}
    }	
};

/**
 * Returns box object for <code>HTMLElement</code> 
 * @param {HTMLElement} el
 * @return {web2.lang.Box}
 */
elem.getBox = function (el)
{
	// has to be part of document to have box
	if ((!web2.dom.hasParent(el)) || web2.css.getStyle(el, "display") == "none") {
		return false;
	}

	if (el.getBoundingClientRect) { // IE
		var r = el.getBoundingClientRect();
		var scroll = web2.win.getScrollSize(document.window);
		return new web2.lang.Box(r.left+scroll.width, r.top+scroll.height, r.right-r.left, r.bottom-r.top);
	}
	else if (document.getBoxObjectFor && el.contentDocument == document) { // Gecko
		var box = document.getBoxObjectFor(el);
		return new web2.lang.Box(box.x, box.y, box.width, box.height);
	}
	else { // safari/opera/gecko another content document
		var pos = [el.offsetLeft, el.offsetTop];
		var parent = el.offsetParent;
		if (parent != el) {
			while (parent) {
				pos[0] += parent.offsetLeft;
				pos[1] += parent.offsetTop;
				parent = parent.offsetParent;
            }
        }

		// opera & (safari absolute) incorrectly account for body offsetTop
		if (($B.safari || $B.opera) && web2.css.getStyle(el, 'position') == 'absolute') {
			pos[1] -= document.body.offsetTop;
		}
		
		parent = el.parentNode; 
		while (parent && parent.tagName != 'BODY' && parent.tagName != 'HTML') {
			pos[0] -= parent.scrollLeft;
			pos[1] -= parent.scrollTop;
	
			parent = parent.parentNode; 
		}
		
		return new web2.lang.Box(pos[0],pos[1],el.offsetWidth,el.offsetHeight);
	}
};

/**
 * Set box for <code>HTMLElement</code>. 
 * <code>width</code> and <code>height</code> acording to <code>border-box</code> box sizing.
 * <code>x</code> and <code>y</code> absolutely to document client area 
 * @param {HTMLElement} el
 * @param {web2.lang.Box} box
 */
elem.setBox = function (el, box)
{
	elem.setPoint(el, box.getPoint());
	elem.setSize(el, box.getSize());
};

/**
 * Get <code>el</code> position, absolutely to document client area
 * @param {HTMLElement} el
 * @return {web2.lang.Point}
 */
elem.getPoint = function (el) 
{
	var box = elem.getBox(el);
	return box ? box.getPoint() : false;
}

/**
 * Set <code>el</code> position absolutely to document client area
 * @param {Object} el
 * @param {web2.lang.Point} point 
 * @param {Object} noRetry
 */
elem.setPoint = function (el, point, noRetry) 
{
	var pagePos = elem.getPoint(el);
	
	if (pagePos === false) { return false; } // has to be part of doc to have pageXY
	
	if (web2.css.getStyle(el, 'position') == 'static') { // default to relative
		web2.css.setStyle(el, 'position', 'relative');
  	}

	var delta = new web2.lang.Size (
		parseInt( web2.css.getStyle(el, 'left'), 10 ),
		parseInt( web2.css.getStyle(el, 'top'), 10 )
	);

	if ( isNaN(delta.width) ) { delta.width = 0; } // defalts to 'auto'
	if ( isNaN(delta.height) ) { delta.height = 0; }

	if (point.x !== null) { el.style.left = px(point.x - pagePos.x + delta.width); }
	if (point.y !== null) { el.style.top = px(point.y - pagePos.y + delta.height); }

	var newPos = elem.getPoint(el);

	// if retry is true, try one more time if we miss
	if (!noRetry && !newPos.equals(point)) {
		elem.setPoint(el, point, true);
	}

	return true;
};

/**
 * Get <code>el</code> size acording to <code>border-box</code> box sizing
 * @param {HTMLElement} el
 * @return {web2.lang.Size}
 */
elem.getSize = function (el) 
{
	var box = elem.getBox(el);
	return box ? box.getSize() : false;
};

/**
 * Set <code>el</code> size acording to <code>border-box</code> box sizing
 * @param {HTMLElement} el
 * @param {web2.lang.Size} size
 */
elem.setSize = function (el, size)
{
	el.style.width = px(size.width);
	el.style.height = px(size.height);
	
	var offsetWidth = el.offsetWidth;
	var offsetHeight = el.offsetHeight;
	
	el.style.width = px(2*size.width - offsetWidth);
	el.style.height = px(2*size.height - offsetHeight);
};


window.web2.elem = elem;
}

new function () {
/**
 * @namespace web2.event
 */
var event = {};


/**
 * Registers an event handler for a custom event on the <code>source</code> object. 
 * Returns a handle that can be used to eventually deregister the handler
 * @alias web2.event.addListener
 * @param {Object} source
 * @param {String} eventName
 * @param {Function} listenerFn
 * @return {EventListener}
 */
event.addListener = function (source,eventName,listenerFn) 
{
    var propertyName = event.getPropertyName(eventName);
    if(source[propertyName]) {
        source[propertyName].push(listenerFn)
    }
    else{
        source[propertyName] = [listenerFn];
    }
    return new EventListener(source,propertyName,listenerFn)
};

function EventListener (instance,propertyName,listenerFn)
{
    this.instance = instance;
    this.propertyName = propertyName;
    this.listenerFn = listenerFn;
};

/**
 * Registers an invocation of the <code>method</code> on the given <code>object</code> 
 * as the event handler for a custom event on the <code>source</code> object. 
 * Returns a handle that can be used to eventually deregister the handler.
 * @alias web2.event.bind
 * @param {Object} source
 * @param {String} eventName
 * @param {Object} object
 * @param {Function} method
 * @return {EventListener}
 */
event.bind = function(source, eventName, object, method)
{
    var listenerFn = event.callback(object, method);
    return event.addListener(source, eventName, listenerFn);
};

/**
 * Removes a handler that was installed using 
 * <code>addListener()</code> or <code>bind()</code>
 * @alias web2.event.removeListener
 * @param {EventListener} listener
 */
event.removeListener = function (listener)
{
    var b=listener.instance[listener.propertyName];
    for(var c=0;c<b.length;c++) {
        if(b[c]==listener.listenerFn){
            b.splice(c,1);
            return
        }
    }
};

/**
 * Removes all listeners, previously installed on the <code>source</code> object using 
 * <code>addListener()</code> or <code>bind()</code>
 * @alias web2.event.clearListeners
 * @param {Object} source
 * @param {String} eventName
 */
event.clearListeners = function (source, eventName)
{
    var propertyName = event.getPropertyName(eventName);
    source[propertyName] = null;
};

/**
 * Fires a custom event on the <code>source</code> object. 
 * All arguments after <code>eventName</code> are passed as arguments to the 
 * event handler functions
 * @alias web2.event.trigger
 * @param {Object} source
 * @param {String} eventName 
 */
event.trigger = function(source, eventName)
{
    var propertyName=event.getPropertyName(eventName);
    var listenerFuncs = source[propertyName];
    if(listenerFuncs && listenerFuncs.length>0) {
        
        var args=[];
        for(var f=2; f<arguments.length; f++){
            args.push(arguments[f])
        }
        
        for(var f=0; f<listenerFuncs.length; f++){
            var listenerFn = listenerFuncs[f];
            if(listenerFn) {
                try {
                    listenerFn.apply(source,args)
                } catch(e) {
					web2.Log.write(e)
                }
            }
        }
    }
};

/**
 * Returns a closure that calls <code>method</code> on <code>object</code>.
 * @alias web2.event.callback
 * @param {Object} object
 * @param {Function} method
 * @return {Function}
 */
event.callback = function(object,method)
{
    var c = function(){return method.apply(object,arguments)}
    return c;
};

/**
 * Returns a closure that calls <code>method</code> on <code>object</code> with
 * arguments passed after <code>method</code>
 * @param {Object} object
 * @param {Function} method
 * @return {Function}
 */
event.callbackArgs = function (object, method)
{
	var args = [];
	for (var i=2; i<arguments.length; i++) {
		args.push(arguments[i]);
	}
	return function () {return method.apply(object, args)};
};

event.getPropertyName=function(eventName)
{
    return"_e__"+eventName;
};

/**
 * Registers an event handler for a DOM event on the <code>source</code> object. 
 * The <code>source</code> object must be a DOM Node. 
 * Returns a handle that can be used to eventually deregister the handler. 
 * This function uses the DOM methods for the current browser to register the event handler
 * @alias web2.event.addDomListener
 * @param {Object} source
 * @param {String} eventName
 * @param {Function} listenerFn
 * @return {Function}
 */
event.addDomListener = function (source, eventName, listenerFn)
{
	/**
	 * Synthesized event. fires when DOM is fully loaded, without waiting 
	 * off all multimedia content
	 */
    if (eventName == "contentloaded") {
    	if ($B.ie) {
    		/** MSIE */
			var dummyId = "__id_onload" + Math.round(Math.random()*100000);
			document.write("<script id="+dummyId+" defer src=javascript:void(0)><\/script>");
			var script = document.getElementById(dummyId);
			script.onreadystatechange = function() {
    			if (this.readyState == "complete") {
        			listenerFn(); // call the onload handler
		    	}
			};
    	}
    	else if ($B.safari) {
    		/** Safari */
			var _timer = setInterval(function() {
					if (/loaded|complete/.test(document.readyState)) {
						clearInterval(_timer);
						listenerFn();
					}
				}, 10);    		
    	}
    	else if ($B.opera && $B.version < 9) {
    		/** Opera < 9 */
			var _timer = setInterval(function() {
					if (document.body) {
	            		clearInterval(_timer);
	            		listenerFn(); 
					}
    			}, 10);    	
    	} 
    	else if (source.addEventListener) {
    		/** FireFox & Opera 9 */
    		source.addEventListener("DOMContentLoaded", listenerFn, false);
    	}
    	else {
    		event.addDomListener(source, "load", listenerFn);
    	}
    	
    	return;
    }
    
    if ($B.gecko && eventName == "mousewheel")  {
    	source.addEventListener("DOMMouseScroll", listenerFn, false);
    	return;
    }
    
    if ($B.safari && eventName=="dblclick") {
        source["on"+eventName] = listenerFn;
        return;
    }
    
	if ($B.ie && eventName == "click") {
 		event.addDomListener(source, "dblclick", listenerFn);		
	}
	
    if(source.addEventListener) {
        source.addEventListener(eventName, listenerFn, false)
    }
    else if(source.attachEvent) {
        source.attachEvent("on"+eventName, listenerFn)
    }
    else {
        source["on"+eventName]=listenerFn;
    }
};

/**
 * Registers an invocation of the <code>method</code> on the given <code>object</code> 
 * as the event handler for a DOM event on the <code>source</code> object.
 * Returns a handle that can be used to eventually deregister the handler
 * @alias web2.event.bindDom 
 * @param {Object} source
 * @param {String} eventName
 * @param {Function} listenerFn
 * @return {Function} 
 */
event.bindDom = function (source,eventName,object,method) 
{
    var listentFn = event.createAdapter(object,method);
    event.addDomListener(source,eventName,listentFn);
    return listentFn;
};

/**
 * Removes listener from the event target, that was installed using 
 * <code>addDomListener()</code> or <code>bindDom</code>1
 * @alias web2.event.removeDomListener
 * @param {Object} source
 * @param {Object} eventName
 * @param {Object} listenerFn
 */
event.removeDomListener = function (source, eventName, listenerFn)
{
    if(source.removeEventListener) {
        source.removeEventListener(eventName,listenerFn,false)
    }
    else if (source.detachEvent) {
        source.detachEvent("on"+eventName,listenerFn)
    }
    else{
        source["on"+eventName]=null
    }
};

/**
 * Returns adapter of browser specific DOMEvent object implementation.
 * Mostly called in DOM event handlers.
 * @alias web2.event.getEvent
 * @param {DOMEvent} e
 * @return {DomEventAdapter}
 * @see #createAdapter(), #bindDom()
 */
event.getEvent = function (e)
{
	if (e instanceof DomEventAdapter) return e;
	
	var ev = e || window.event;
	
	if (!ev) {
        var c = Event.get.caller;
        while (c) {
            ev = c.arguments[0];
            if (ev && Event == ev.constructor) {
                break;
            }
            c = c.caller;
        }
    }
    
    return new DomEventAdapter(ev);		
};

/**
 * Returns an adaptee DOM event handler closure,
 * that calls <code>method</code> on <code>object</code>.
 * @alias web2.event.createAdapter
 * @param {Object} object
 * @param {Function} method
 * @return {Function}
 */
event.createAdapter = function (object, method) 
{
    return function(e) {
        e = event.getEvent(e);
        method.call(object, e);
    }
};


function DomEventAdapter (ev)
{
	this._ev = ev;
	
	['type', 'altKey', 'ctrlKey', 'shiftKey', 'button', 'clientX', 'clientY'].forEach(function (a) {
		this[a] = ev[a];
	}, this);
	
	/**
	 * @memberOf DomEventAdapter
	 */
	this.type 		= ev.type;
	this.altKey 	= ev.altKey;
	this.ctrlKey 	= ev.ctrlKey;
	this.shiftKey 	= ev.shiftKey;
};

/**
 * @alias getTarget
 * @return {HTMLElement}
 */
DomEventAdapter.prototype.getTarget = function ()
{
	return this._ev.target || this._ev.srcElement;	
}

/**
 * Returns the event's related target 
 * @return {HTMLElement} the event's relatedTarget
 */
DomEventAdapter.prototype.getRelatedTarget = function() 
{
	var e = this._ev;
    var t = e.relatedTarget;
    if (!t) {
        if (e.type == "mouseout") {
            t = e.toElement;
        } else if (e.type == "mouseover") {
            t = e.fromElement;
        }
    }

    return t;
}

/**
 * Returns the charcode for an event
 * @return {Number} the event's charCode
 */
DomEventAdapter.prototype.getCharCode = function() 
{
    return this._ev.charCode || (this.type == "keydown") ? this._ev.keyCode : 0;
}


/**
 * Returns the event's x, y
 * @return {web2.lang.Point} the event's point
 */
DomEventAdapter.prototype.getPagePoint = function ()
{
	return new web2.lang.Point(this.getPageX(), this.getPageY());
}


/**
 * Returns the event's pageX
 * @return {Number} the event's pageX
 */
DomEventAdapter.prototype.getPageX = function ()
{
    var x = this._ev.pageX;
    if (!x && 0 !== x) {
        x = this._ev.clientX || 0;

		if ($B.ie) {
			x += web2.win.getScrollSize().width;
		}
    }

    return x;
}


/**
 * Returns the event's pageY
 * @return {Number} the event's pageY
 */
DomEventAdapter.prototype.getPageY = function ()
{
    var y = this._ev.pageY;
    if (!y && 0 !== y) {
        y = this._ev.clientY || 0;

		if ($B.ie == 1) {
			y += web2.win.getScrollSize().height;
		}
    }

    return y;
}

/**
 * @see http://adomas.org/javascript-mouse-wheel/
 * 
 * @return {Number}
 */
DomEventAdapter.prototype.getWheelDelta = function ()
{
	var delta = 0;
	if (this._ev.wheelDelta) {
		/** IE/Opera. */
		delta = this._ev.wheelDelta/120;
		
		/** In Opera 9, delta differs in sign as compared to IE. */
		if ($B.opera) {
			delta = -delta;
		}
		
	} else if (this._ev.detail) {
		/** 
		 * In Mozilla, sign of delta is different than in IE. 
		 * Also, delta is multiple of 3.
		 */
         delta = -this._ev.detail/3;
	}
	
	return delta;
}

/**
 * Convenience method for stopPropagation + preventDefault
 */
DomEventAdapter.prototype.stopEvent = function() 
{
    this.stopPropagation();
    this.preventDefault();
}

/**
 * Stops event propagation
 */
DomEventAdapter.prototype.stopPropagation = function() 
{
    if (this._ev.stopPropagation) {
        this._ev.stopPropagation();
    } else {
        this._ev.cancelBubble = true;
    }
}


/**
 * Prevents the default behavior of the event
 */
DomEventAdapter.prototype.preventDefault = function() 
{
    if (this._ev.preventDefault) {
        this._ev.preventDefault();
    } else {
        this._ev.returnValue = false;
    }
}

_stopFunction = function (ev)
{
	ev = web2.event.getEvent(ev);
	ev.stopEvent();
}


window.web2.event = event;
}

new function () {
var lang = {};

window.web2.lang = lang;
function Box (x, y, width, height) 
{
	this.x = x; 
	this.y = y;	
	this.width = width; 
	this.height = height;
}

Box.prototype.getPoint = function ()
{
	return new web2.lang.Point(this.x, this.y); 
}

Box.prototype.getSize = function ()
{
	return new web2.lang.Size(this.width, this.height);
}

Box.prototype.containsPoint = function (point)
{
	var x2 = this.x + this.width;
	var y2 = this.y + this.height;
	return  Math.max(this.x, point.x) == point.x &&
			Math.max(x2, point.x) == x2 &&
			Math.max(this.y, point.y) == point.y &&
			Math.max(y2, point.y) == y2;
}

Box.prototype.toString = function () 
{
	return 'Box(x:'+this.x+'; y:'+this.y+'; w:'+this.width+'; h:'+this.height+')';
}


window.web2.lang.Box = Box;
/**
 * @package web2.Lang
 */

function Point(a,b){
	this.x=a;
	this.y=b;
}

Point.prototype.toString=function(){
	return"("+this.x+", "+this.y+")"
}
Point.prototype.equals=function(a){
	if(!a)return false;
	return this.x==a.x&&this.y==a.y
}

Point.prototype.distanceFrom=function(a){var b=this.x-a.x;var c=this.y-a.y;return Math.sqrt(b*b+c*c)}

Point.prototype.approxEquals=function(a){
	if(!a)return false;
	return Pa(this.x,a.x)&& Pa(this.y,a.y)
}

window.web2.lang.Point = Point;
/**
 * @package web2.Lang
 */

var Size = function (a,b)
{
	this.width=a;
	this.height=b
}

Size.prototype.toString=function(){
	return "("+this.width+", "+this.height+")";
}

Size.prototype.equals=function(a){
	if(!a) return false;
	return this.width==a.width&&this.height==a.height
}

window.web2.lang.Size = Size;
}

new function () {
var ui = {};

/**
 * Normalize `pointer` and `hand` cursor type
 * @param {HTMLElement} Accepts either a string to use as an ID for getting a DOM reference, or an actual DOM reference.
 * @param {String} Cursor typer
 * @return {String}
 */
ui.cursor = function (el, cursorType) 
{
	try {
		el.style.cursor = cursorType;
	} 
	catch (e) {
		if (cursorType=="pointer") { 
			ui.cursor(el, "hand"); 
		}
	}
}

ui.createMicrolink = function (text, clickFn, title)
{
	var a = document.createElement("a");
	a.href = "javascript:void(0);";
	if (text) {
		a.appendChild(document.createTextNode(text));		
	}
	if (clickFn) {
		web2.event.addDomListener(a, "click", clickFn);		
	}
	
	return a;
}

ui.safeFocus = function (el)
{
	try { el.focus(); } 
	catch (e) { return false; } 
	return true;	
}

ui.setUserSelect = function (el, enabled) {
	enabled = Boolean(enabled);
	
	if ($B.ie) {
		el.unselectable = enabled ? "off" : "on";
		el.onselectstart = enabled ? null : _nullFunction;
	}
	else {
		el.style["-moz-user-select"] = enabled ? "normal" : "none";
	}
}

var ModalBox =
{
	initialized : false
};

/**
 * Initialize ModalBox. called once
 * @see #show
 */
ModalBox.init = function ()
{
	if (this.initialized) return;
	
	var docBody = document.body;
	
	var ov = document.createElement("div");
	ov.className = "web2ui-modalbox-overlay";
	ov.style.display = "none";
	docBody.appendChild(ov);
	this.overlay = ov;
	
	var c = document.createElement("div");
	c.className = "web2ui-modalbox-container";
	c.style.left = c.style.top = "-1000px";
	docBody.appendChild(c);
	this.container = c;
	
	var h = document.createElement("div");
	h.className = "web2ui-modalbox-header";
	this.container.appendChild(h);
	this.header = h;

	var d = document.createElement("div");
	with (d.style) {
		position = "relative";
		left = top = fontSize = px(0);
		width = "100%";
		height = px(this.header.offsetHeight);
	}
	this.header.appendChild(d);
	this.dragDiv = d;
	
	var t = document.createElement("span");
	t.className = "web2ui-modalbox-title";
	t.innerHTML = "Title Goes Here";
	this.header.appendChild(t);
	this.title = t;
	
	var c = web2.ui.createMicrolink();
	c.className = "web2ui-modalbox-close";
	c.style.zIndex = "500";
	this.header.appendChild(c);
	this.closeButton = c;
	
	var f = document.createElement("iframe");
	f.className = "web2ui-modalbox-frame";
	f.src = "about:blank";
	this.container.appendChild(f);
	this.frame = f;
	
	this.dragObject = new DragObject(this.dragDiv);
	web2.event.bind(this.dragObject, "drag", this, function () {
		var d = this.dragObject;
		with (this.container.style) {
			left = px(d.clickStartPos.x + d.left);
			top = px(d.clickStartPos.y + d.top);  
		}
		
//		web2.Log.write(this.left+", "+this.top);
		//web2.Log.write(this.src.style.left);
//		web2.Log.write(this.clickStartPos);
	});
	
	with (web2.event) {
		/** Create Event listeners */
		this.frameLoadHandler = createAdapter(this, this.onFrameLoad);
		this.windowResizeHandler = createAdapter(this, this.onWindowResize);
		this.closeHandler = callback(this, this.hide);

 		/** Out pseudo event for handling frame loading */
		bind(window, "ModalBoxContentResized", this, this.onFrameContentResized);
		bind(window, "ModalBoxOK", this, this.onOK);
		addListener(window, "ModalBoxCancel", this.closeHandler);
	}
	
	this.initialized = true;
};

/**
 * Show modal box with given <code>url</code>
 * @param {String} url
 * @param {web2.lang.Size} size
 * @param {Function} okCallback
 * @see #onFrameLoad
 */
ModalBox.show = function (url, okCallback)
{
	if (!this.initialized) {
		this.init();
	}
	
	this.okCallback = okCallback;
	web2.event.addDomListener(this.frame, "load", this.frameLoadHandler);
	web2.dom.setIFrameSrc(this.frame, url);
};

/**
 * Hide modal box
 */
ModalBox.hide = function ()
{
	this.container.style.left = this.container.style.top = "-1000px";
	web2.elem.hide(this.overlay);
	this.reset();	
};

/**
 * Resets modalbox to initially state.
 * @see #hide
 */
ModalBox.reset = function ()
{
	with (web2.event) {
		removeDomListener(window, "resize", this.windowResizeHandler);
		removeDomListener(this.closeButton, "click", this.closeHandler);
		removeDomListener(this.frame, "load", this.frameLoadHandler);
		removeDomListener(document, "contextmenu", _stopFunction);
	}
	web2.dom.setIFrameSrc(this.frame, "about:blank");
};

ModalBox.onFrameLoad = function ()
{
	//web2.Log.write("[ModalBox] frame loaded :)");
		
	this.frameWindow = web2.dom.getIFrameWindow(this.frame);
	with (web2.event) {
		/** Handle frame resize */
		addDomListener(window, "resize", this.windowResizeHandler);
		/** Handle close click */
		addDomListener(this.closeButton, "click", this.closeHandler);
		
		addDomListener(document, "contextmenu", _stopFunction);
	}
	
	var doc = this.frameWindow.document;
	var title = doc.getElementsByTagName('title')[0];
	if (title) {
		this.title.innerHTML = title.innerHTML;
	}
	
	/** Display It */

	web2.elem.show(this.overlay);
	this.onWindowResize();
	this.fitToContent(true);
	
};

ModalBox.onFrameContentResized = function ()
{
	web2.Log.write("onFrameContentResized");
	this.fitToContent(false);
};

/**
 * 
 * @param {Boolean} centreBox
 */
ModalBox.fitToContent = function (centreBox)
{
	var c = this.frameWindow.document.getElementById("container");	
	web2.css.setStyle(this.frame, {"height": px(c.offsetHeight+1)});
	web2.css.setStyle(this.container, "width", px(c.offsetWidth+1));
	
	if (centreBox) {
		this.centre();
	}
}

/**
 */
ModalBox.centre = function ()
{
	var box = web2.elem.getBox(this.container);
	var clientSize = web2.win.getClientSize();
	
	var x = Math.round(0.5*(clientSize.width - box.width));
	var y = Math.round(0.25*(clientSize.height - box.height));
	web2.css.setStyle(this.container, {"left" : px(x), "top" : px(y)});
};


ModalBox.onWindowResize = function ()
{
	var cs = web2.win.getClientSize();
	with (this.overlay.style) {
		width = px(cs.width);
		height = px(cs.height);
	} 
};

ModalBox.onOK = function ()
{
	this.hide();
	if (this.okCallback) {
		this.okCallback.apply(null, arguments);
	}
}

ui.showModalBox = function (url, okCallback)
{
	ModalBox.show(url, okCallback);
};


window.web2.ui = ui;
/**
 * @constructor web2.ui.DragObject
 */
function DragObject(a,b,c,d)
{
    this.src=a;
    this.container=d;
    this.disabled=false;
    this.dragPoint = new web2.lang.Point(0,0);
    this.dragging=false;
    this.clickStartPos = new web2.lang.Point(0,0);
    
    this.src.style.position="absolute";    
    this.moveTo(b!=null ? b:a.offsetLeft, c!=null ? c:a.offsetTop);
    
    this.mouseDownHandler = web2.event.createAdapter(this,this.onMouseDown);
    this.mouseMoveHandler = web2.event.createAdapter(this,this.onMouseMove);
    this.mouseUpHandler = web2.event.createAdapter(this,this.onMouseUp);
    
    if($B.gecko) {
        web2.event.bindDom(window,"mouseout",this,this.onWindowMouseOut);
    }
    
    this.eventSrc=this.src.setCapture ? this.src : window;
    
    web2.event.addDomListener(this.src,"mousedown",this.mouseDownHandler);
    web2.event.bindDom(this.src,"mouseup",this,this.onDisabledMouseUp);
    web2.event.bindDom(this.src,"click",this,this.onDisabledClick)
}
    
DragObject.prototype.moveTo=function(a,b){
    if(this.left!=a||this.top!=b){
        this.left=a;this.top=b;
        this.src.style.left=this.left+"px";
        this.src.style.top=this.top+"px";
        web2.event.trigger(this,"move")
    }
}

DragObject.prototype.onDisabledClick=function(a){
    if(this.disabled){
        web2.event.trigger(this,"click",a)
    }
}

DragObject.prototype.onDisabledMouseUp=function(a){
    if(this.disabled){
        web2.event.trigger(this,"mouseup",a)
    }
}
DragObject.prototype.onMouseDown=function(a){
    web2.event.trigger(this,"mousedown",a);
    if(a.cancelDrag){return}
    var b=a.button==0||a.button==1;
    if(this.disabled||!b){
        a.stopEvent();
        return false;
    }
    this.dragPoint.x=a.clientX;
    this.dragPoint.y=a.clientY;
    this.dragging=true;
    
    web2.event.addDomListener(this.eventSrc,"mousemove",this.mouseMoveHandler);
    web2.event.addDomListener(this.eventSrc,"mouseup",this.mouseUpHandler);
    
    if(this.src.setCapture){
        this.src.setCapture()
    }
    this.clickStartTime=(new Date()).getTime();
    this.clickStartPos.x=a.clientX;
    this.clickStartPos.y=a.clientY;
    
    this.bodyOverflow = web2.css.getStyle(document.body, 'overflow');
    document.body.style.overflow = 'hidden';
    web2.event.trigger(this,"dragstart");
    
    this.originalCursor= web2.css.getStyle(this.src, 'cursor');
    web2.ui.cursor(this.src,"move");
    a.stopEvent();
}

DragObject.prototype.onMouseMove=function(a)
{
    if($B.os==1){
        if(a==null){return}
        if(this.dragDisabled){
            this.savedMove=new Object();
            this.savedMove.clientX=a.clientX;
            this.savedMove.clientY=a.clientY;
            return
        }
        web2.window.setTimeout(this,function(){
        	this.dragDisabled=false;
        	this.onMouseMove(this.savedMove)},30);
        
        this.dragDisabled=true;
        this.savedMove=null
    }
    var b=this.left+(a.clientX-this.dragPoint.x);
    var c=this.top+(a.clientY-this.dragPoint.y);
    var d=0;
    var e=0;
    if(this.container){
        var f=b;
        if(b<this.container.minX){
            f=this.container.minX
        }
        else{
            var g=this.container.maxX-this.src.offsetWidth;
            if(b>g){
                f=g
            }
        }
        d=f-b;
        b=f;
        var h=c;
        if(c<this.container.minY){
            h=this.container.minY
        }
        else{
            var i=this.container.maxY-this.src.offsetHeight;
            if(c>i) h=i
        }
        
        e=h-c;
        c=h
    }
    
    this.moveTo(b,c);
    this.dragPoint.x=a.clientX+d;
    this.dragPoint.y=a.clientY+e;
    
    web2.event.trigger(this,"drag");
}

DragObject.prototype.onMouseUp=function(a)
{
    web2.event.trigger(this,"mouseup",a);
    web2.event.removeDomListener(this.eventSrc,"mousemove",this.mouseMoveHandler);
    web2.event.removeDomListener(this.eventSrc,"mouseup",this.mouseUpHandler);
    this.dragging=false;
    web2.ui.cursor(this.src,this.originalCursor);
    if(document.releaseCapture){
        document.releaseCapture()
    }
    web2.event.trigger(this,"dragend");
    document.body.style.overflow = this.bodyOverflow;    
    var b=(new Date()).getTime();
    if (b-this.clickStartTime<=500&&(Math.abs(this.clickStartPos.x-a.clientX)<=2 && 
    	Math.abs(this.clickStartPos.y-a.clientY)<=2)){
        web2.event.trigger(this,"click",a)
    }
}

DragObject.prototype.onWindowMouseOut=function(a)
{
    if(!a.getRelatedTarget() && this.dragging) {
        this.onMouseUp(a)
    }
}

DragObject.prototype.disable=function() {this.disabled=true}
DragObject.prototype.enable=function() {this.disabled=false}


window.web2.ui.DragObject = DragObject;
}

new function () {
/**
 * @package web2.Window
 * @namespace web2.Window
 * @author Marat
 */

/**
 * Нормализация некоторых свойств window
 */
var win = {};

/**
 * Возвращает величину скроллинга
 * @param {Window object} Объект window броузера
 * @return {web2.lang.Size}
 */
win.getScrollSize = function (w) 
{
	w = w || window;
	
	var width = w.pageXOffset ||
			w.document.body.scrollLeft ||	
			w.document.documentElement.scrollLeft;

			
	var height = win.pageXOffset ||
			w.document.body.scrollTop ||	
			w.document.documentElement.scrollTop;
			
	return new web2.lang.Size(width, height);
}

/**
 * Возвращает величину клиентской области
 * @param {Window object}? Объект window броузера
 * @return {web2.Lang.Size}
 */
win.getClientSize = function (w) 
{
	w = w || window;
	
	var width = w.innerWidth ||
			w.document.body.clientWidth ||	
			w.document.documentElement.clientWidth;
			
	var height = w.innerHeight || 
			w.document.body.clientHeight ||
			w.document.documentElement.clientHeight;
			
	return new web2.lang.Size(width, height);
}

/**
* Set a interval for a specific function.
* @param {Object}
* @param {Function}
* @param {Int} is the number of milliseconds (thousandths of a second) that the function should be delayed
* @return {Int} the interval ID
*/
win.setInterval = function (thisObject, method, milliSeconds) 
{
	var d=window.setInterval(function(){method.apply(thisObject)}, milliSeconds);
	return d;
}

/**
* Sets a delay for executing a function
* @param {Object}
* @param {Function}
* @param {Int} is the number of milliseconds (thousandths of a second) that the function should be delayed
* @return {Int} the timeout ID
*/
win.setTimeout = function (thisObject, method, milliSeconds)
{
	var d = window.setTimeout(function(){method.apply(thisObject)}, milliSeconds);
	return d;
}


/**
 * Returns a selection object representing the selected item(s).
 * @return {web2.Dom.Selection}
 */
win.getSelection = function ()
{
	/**
	 * @todo
	 */
}



window.web2.win = win;
}

}

