function DraggableRows (table)
{
	this.backgroundColor = 'infobackground';
	this.table = table;
	with (web2.event) {
		bindDom(this.table, 'mousedown', this, this.onMouseDown);
		bindDom(document, 'mouseup', this, this.onMouseUp);
		//
		bindDom(this.table, 'mouseover', this, this.onMouseAct);
		bindDom(this.table, 'mouseout', this, this.onMouseAct);
	}	
	
	this.dragTable = document.createElement('table');
	this.dragTable.className = 'draggable';

	var tableId = this.table.getAttribute('id');
	this.dragTable.setAttribute('id',tableId);
	// Size	
	var tableWidth = web2.elem.getBox(this.table);
	this.dragTable.style.width = px(tableWidth["width"]);
	
	this.dragTable.style.display = 'none'; 
	this.dragTable.style.position = 'absolute';
	this.table.parentNode.insertBefore(this.dragTable,this.table);
	// 
	
};

DraggableRows.prototype.setBackgroundColor = function(color)
{
	this.backgroundColor = color;
};

DraggableRows.prototype.onMouseDown = function (e)
{
	this.act = true;
	document.body.style.cursor = "move";
	// block selection
	document.onselectstart = _stopFunction;
	if (window.sidebar){
		document.onmousedown = _stopFunction;
	}

	// mouse down table.
	var row = e.getTarget();
	while (row.nodeName != "TR" && row) {
		row = row.parentNode;
	}
	this.fromIndex = row.rowIndex;
	
	// remove child nodes:
	if (this.dragTable.hasChildNodes()) {
		for (var i=0; i<this.dragTable.childNodes.length; i++) {
			this.dragTable.removeChild(this.dragTable.childNodes[i]);
		}
	}

	var clonedNode = row.cloneNode(true);
	var tbodyNode = document.createElement('TBODY');
	tbodyNode.appendChild(clonedNode);
	clonedNode.style.backgroundColor = this.backgroundColor;
	this.dragTable.appendChild(tbodyNode);
	
};

DraggableRows.prototype.onMouseUp = function (e)
{
	this.act = false;
	document.body.style.cursor = "default";
	// reenable selection
	document.onselectstart=new Function ("return true");

	if (window.sidebar){
		document.onmousedown=new Function ("return true");
	}
	
	this.dragTable.style.display = 'none'; 
	var row = e.getTarget();
	try {
		while (row.nodeName != 'TABLE' && row) {
			row = row.parentNode;
		}
	} catch (e) { this.toIndex = -1; }
	
	this.onDragEnd(row);
};

DraggableRows.prototype.onMouseAct = function(e)
{
	var row = e.getTarget();

	while (row.nodeName != "TR" && row) {
		row = row.parentNode;
	}

	var currentTable = e.getTarget();
	while (currentTable.nodeName != "TABLE" && currentTable) {
		currentTable = currentTable.parentNode;
	}
	// show dragged table
	this.toIndex = -1;
	if (this.fromIndex != row.rowIndex && this.act && 
		(row.rowIndex != 0) && (this.table == currentTable) && currentTable) {
		this.dragTable.style.display = '';
	
		if (row.nodeName == "TR") { 
			this.toIndex = row.rowIndex;
		}		
	}
	
	if (this.act && (row.rowIndex != 0)) {
		// Correcting size	
		var tableWidth = web2.elem.getBox(this.table);
		this.dragTable.style.width = px(tableWidth["width"]);
	
		var bbox = web2.elem.getBox(row);
		this.dragTable.style.left = px(bbox['x']);
		this.dragTable.style.top = px(bbox['y']);
	}
};

DraggableRows.prototype.onDragEnd = function (row)
{
	// проверка срабатывания на "правильной" таблице
	try {
		var tableid = this.table.getAttribute('id');
		var checkid = row.getAttribute('id');
	
		if (this.toIndex != -1 && tableid == checkid) {
			web2.event.trigger(this, 'drop', this.fromIndex, this.toIndex);
		}
	} catch (e) {}
	
};
