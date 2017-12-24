function disableSubmitButton (form, buttonName)
{
	try {
		form.elements[buttonName].disabled = true;
	} catch (e) {
		alert(e);
	}
	return true;
}

function disableSubmitButtons (domEvent)
{
	var form = domEvent.getTarget();
	while (form && form.nodeName != 'FORM') {
		form = form.parentNode;
	}
	var elems = form.elements;
	for (var i=0; i<elems.length; i++) {
		var el = elems.item(i);
		if (el.type == "submit") {
			el.disabled = true;
		}
	}
	return true;
}


function toggleSelected (checker)
{
	var elems = checker.form.elements;
	for (var i=0; i<elems.length; i++) {
		var el = elems.item(i);
		if (el.type == "checkbox" && el.className == "checker") {
			el.checked = checker.checked;
		}
	}
}

function showActions (event)
{
	event = event || window.event;
	var target = event.target || event.srcElement;
	var div = findActionsDiv(target);	
	if (div) {
		div.style.visibility = 'visible';
	}
}

function hideActions (event)
{
	event = event || window.event;
	var target = event.target || event.srcElement;	
	var div = findActionsDiv(target);
	if (div) {
		div.style.visibility = 'hidden';
	}
}

function findActionsDiv (target)
{
	var tr = findAncestor(target, "tr");
	if (!tr) return;
	return tr.cells.item(tr.cells.length-1).getElementsByTagName("div")[0];
}

function deleteRow (event, titleCol, args)
{
	event = event || window.event;
	var target = event.target || event.srcElement;	

	var tr = findAncestor(target, "tr");
	if (!tr) return;
	var form = findAncestor(tr, "form");
	if (!form) return;
	
	var td = tr.cells.item(titleCol);
	var title = 'Удалить елемент ' + (td ? '`'+ trim(web2.Xml.value(td)) + '` ' : '') + '?';
	
	if (confirm(title)) {
		postRequest(form.action, args);
	}
}

function findAncestor (contextNode, nodeName)
{
	var ret = contextNode;
	while (ret && ret.nodeName != nodeName.toUpperCase()) 
		ret = ret.parentNode;
	return ret;	
}

function postRequest(url, args, target) {
	var form = document.createElement("form");
	form.method = "POST";
	form.action = url;
	if (target) {
		form.target = target;
	}
	
	if (args) {
		for (var n in args) {
			var v = args[n];
			var i = document.createElement("input");
			i.type = "hidden";
			i.name = n;
			i.value = v;
			form.appendChild(i);
		}
	}
	
	document.body.appendChild(form);
	form.submit();
}


function startApp ()
{
	return;
	var dataGrid = web2.dom.get("xv_datagrid");
	if (dataGrid) {
		for (var i=1; i<dataGrid.rows.length; i++) {
			
		}
	}
}

function onMouseOverDataRow (event) 
{
	if ($B.ie) {
		web2.css.addClass(row, "hover");
	}
}

function onMouseOutDataRow (event) 
{
	if ($B.ie) {
		web2.css.removeClass(row, "hover");
	}
}

function highlightRow (row)
{
	
}

function bleachRow (row)
{
	
}


// Removes leading whitespaces
function LTrim( value ) {
	
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
	
}

// Removes ending whitespaces
function RTrim( value ) {
	
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
	
}

// Removes leading and ending whitespaces
function trim( value ) {
	
	return LTrim(RTrim(value));
	
}
