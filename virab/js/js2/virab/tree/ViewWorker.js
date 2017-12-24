/**
 * Events:
 * 	nodeInserted 	el : HTMLElement 
 */

/**
 * @constructor
 * @param {Object} treeGrid
 */
function ViewWorker (treeSource) {
	this.table = treeSource;
	this.rows = this.table.rows;
	this.parseWalkerCallback = web2.event.callback(this, this.parseWalker);
}

ViewWorker.prototype.parseChildren = function (nodeId)
{
	return this.parse(nodeId, false);
};

ViewWorker.prototype.parseTree = function ()
{
	return this.parse(null, true);
};

/**
 * Overload in the prototype this function it is possible to change 
 * the algorithm of parsing HTML
 * @param {Number} nodeId
 * @param {Boolean} includeSelf
 * @return {Array}
 */
ViewWorker.prototype.parse =  function (nodeId, includeSelf)
{
	var nodes = {};
	this.walkTree(nodeId, includeSelf, this.parseWalkerCallback, nodes);
	return nodes;
};

ViewWorker.prototype.parseWalker = function (row, nodes)
{
	var node = this._parseRow(row);
	// Insert into nodes
	nodes[node.id] = node;
	
	// Append to parent node
	var parent = nodes[node.parentId];
	if (parent) {
		parent.hasChildren = true;
		parent.children.push(node.id);
		parent.childrenLoaded = true;
	}
};

ViewWorker.prototype._parseRow = function (row)
{
	var id 			= parseInt(this.getNodeAttribute(row, 'id'), 10);
	var parentId 	= parseInt(this.getNodeAttribute(row, 'parentId'), 10);
	var hasChildren = parseInt(this.getNodeAttribute(row, 'hasChildren'), 10);
	var expanded 	= parseInt(this.getNodeAttribute(row, 'expanded'), 10);
	var level 		= parseInt(this.getNodeAttribute(row, 'level'), 10);
	return new virab.tree.Node(id, parentId, level, hasChildren, null, null, expanded);
};

ViewWorker.prototype.walkTree = function (nodeId, includeSelf, callback, userObject)
{
	var elRow = this.getElForNode(nodeId);
	var rowsCollection = elRow.parentNode.rows;

	var validParents = {};
	validParents[this.getNodeAttribute(elRow, 'id')] = 1;

	if (includeSelf) {
		callback(elRow, userObject);
	}

	for (var i=elRow.rowIndex+1; i<rowsCollection.length; i++) {  
		var row = rowsCollection.item(i);
		var id = this.getNodeAttribute(row, 'id');
		var parentId = this.getNodeAttribute(row, 'parentId');

		// Check break condition
	 	if (validParents[parentId] === undefined) {
			break;
		}

		callback(row, userObject);
		
		validParents[id] = 1;		
	}
};

/**
 * Returns <code>HTMLElement<code>, presented node 
 * @param {Number} nodeId
 * @return {HTMLElement}
 */
ViewWorker.prototype.getElForNode = function (nodeId)
{
	if (nodeId === null) {
		return this.rows.item(1);
	}
	
	for (var i=1; i<this.rows.length; i++) {
		var row = this.rows.item(i);
		if (this.getNodeAttribute(row, 'id') == nodeId) {
			return row;
		}
	}
	
	return null;
};

ViewWorker.prototype.getElForNodeArray = function (nodeIdenties)
{
	/**
	 * Найденые эелементы
	 */
	var found = {};
	
	/**
	 * Поиск осуществляется функцией Array.prototype.indexOf()
	 * она чувствительна к типу данных
	 */
	var ids = [];
	for (var i=0; i<nodeIdenties.length; i++) {
		ids.push(parseInt(nodeIdenties[i], 10));
	}
	
	/**
	 * Цикл поиска
	 */
	var c = 0;
	for (var i=1; i<this.rows.length; i++) {
		var row = this.rows.item(i);
		var nodeId = parseInt(this.getNodeAttribute(row, 'id'), 10);
		if (ids.indexOf(nodeId) != -1) {
			found[nodeId] = row;
			c++;
			if (c == ids.length) break;
		}
	}
	
	/**
	 * Если хоть один элемент не был найден, бросается исключение
	 */
	if (c < ids.length) {
		throw new Error ('Не все элементы были найдены');
	}
	
	/**
	 * Сортировка
	 */
	var ret = [];
	for (var k=0; k<ids.length; k++) {
		ret.push(found[ids[k]]);
	}
	return ret;
}

/**
 * @return {HTMLElement}
 */
ViewWorker.prototype.getEventTarget = function (domEvent)
{
	var row = domEvent.getTarget();
	while (row && row.nodeName != 'TR') {
		row = row.parentNode;
	}
	
	return row;
};

/**
 * @return {HTMLElement}
 */
ViewWorker.prototype.getNodeIcon = function (node, el)
{
	el = el || this.getElForNode(node);
	return el.cells[1].childNodes[0];
}

ViewWorker.ATTR_REGEXP = /([^\s=]+)=[\"\']?([\d]*)[\"\']?/img;
ViewWorker.TR_REGEXP = /\<tr([^\>]*)\>([\w\W\s]*?)\<\/tr\>/img;
 
ViewWorker.prototype.appendHTML = function (refId, html)
{
	var rowEl = this.getElForNode(refId);
	var rowIndex = rowEl.rowIndex;

	var tdArray = [];
	var attrArray = [[]];
	var index = 0;
	do {
		var trMatches = ViewWorker.TR_REGEXP.exec(html);
		if (trMatches) {
			tdArray.push(RegExp.$2);
			attrArray.push([]);
			var reg = RegExp.$1;
			do {
				attrMatches = ViewWorker.ATTR_REGEXP.exec(reg);
				if (attrMatches) {
					attrArray[index].push(attrMatches[1]);
					attrArray[index].push(attrMatches[2]);
				}
			} while (attrMatches);
			index++;
		}
	} 
	while(trMatches);

	for (var i=0; i<tdArray.length; i++) {
		var row = this.table.insertRow(rowIndex+i+1);
		for (j=0; j<attrArray[i].length; j+=2) {
			row.setAttribute(attrArray[i][j], attrArray[i][j+1]);
			//web2.Log.write(attrArray[i][j]+'='+attrArray[i][j+1]);
		}
		web2.dom.setInnerHTML(row, tdArray[i]);
	}
};

ViewWorker.prototype.deleteChildren = function (nodeId, el)
{
	el = el || this.getElForNode(nodeId);
	var elements = []; 
	this.walkTree(nodeId, false, function (row) {
		elements.push(row);
	});
	
	for (var i=0; i<elements.length; i++) {
		web2.dom.remove(elements[i]);
	}
}

ViewWorker._nodeAttributeMap = {
	'id' 			: 'x-primary',
	'parentId' 		: 'x-parent',
	'hasChildren' 	: 'x-has-children',
	'expanded' 		: 'x-expanded',
	'level' 		: 'x-level'
}; 

ViewWorker.prototype.getNodeAttribute = function (el, attrName)
{
	var a = ViewWorker._nodeAttributeMap[attrName];
	if (a == undefined) {
		throw new Error ("try to get undefined node attribute '" + attrName + "'");
	}
	return el.getAttribute(a); 
};

ViewWorker.prototype.setNodeAttribute = function (el, attrName, attrValue)
{
	var a = ViewWorker._nodeAttributeMap[attrName];
	if (a == undefined) {
		throw new Error ("try to set undefined node attribute '" + attrName + "'");
	}
	if (typeof attrValue == 'boolean') {
		attrValue = Number(attrValue);
	}
	return el.setAttribute(a, attrValue); 
};

ViewWorker.prototype.refreshNodeAttributes = function (node, el)
{
	el = el || this.getElForNode(node);
	Object.forEach(ViewWorker._nodeAttributeMap, 
		web2.event.callback(this, function (attrName, propName) {
			this.setNodeAttribute(el, propName, node[propName]);
		})
	);
};

ViewWorker.prototype.replaceBefore = function (nodeId, beforeId, el, beforeEl)
{
	el = el || this.getElForNode(nodeId);
	beforeEl = beforeEl || this.getElForNode(beforeId);

	el.parentNode.removeChild(el);

	var level = this.getNodeAttribute(beforeEl,'level');
	this.setNodeAttribute(el,'level',level);
	var parent = this.getNodeAttribute(beforeEl,'parentId');
	this.setNodeAttribute(el,'parentId',parent);

	var tdPadding = el.getElementsByTagName('TD')[1];
	tdPadding.style.paddingLeft = (16*level)+'px';

	beforeEl.parentNode.insertBefore(el, beforeEl);
	
	return;
	
	/**
	 * @todo изменить левелы
	 */

};
