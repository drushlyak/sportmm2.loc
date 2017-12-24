/**
 * Events
 * 	loading
 * 	loaded
 * 	nodeReplaced	id : Number, beforeId : Number
 */


/**
 * 
 * @param {Object} backend
 */
function Model (backend) 
{
	this.backend = backend;

	this.ajaxListener = {
		onOpen 		: web2.event.callback(this, this.onAjaxOpen),
		onLoaded	: web2.event.callback(this, this.onAjaxLoaded),
		on400		: web2.event.callback(this, this.onAjax400),
		on500		: web2.event.callback(this, this.onAjax500),
		onSuccess 	: web2.event.callback(this, this.onAjaxSuccess)
	};
};

Model.prototype.setUserData = function (userdata)
{
	/**
	 * Массив пользовательских данных для ajax запроса
	 */
	this.userdata = userdata;
}

Model.prototype.setTree = function (tree)
{
	this.tree = tree;
	this.nodes = this.tree.getViewWorker().parseTree();
};

Model.prototype.getData = function ()
{
	return this.nodes;
}

/**
 * @throws Node not found error
 */
Model.prototype.getNode = function (refId)
{
	var node = this.nodes[refId]; 
	if (node == undefined) {
		throw new Error("node specified by '" + refId + "' not found");
	}
	
	return node;
}

Model.prototype.getRoot = function ()
{
	if (this.root) {
		return this.root;
	}
	
	var root;
	Object.forEach(this.nodes, function (v, k) {
		if (v.parentId == 0) {
			root = v;
		}
	});
	if (! root) {
		throw new Error ('Корневой узел отсутствует');
	}
	this.root = root;
	
	return root;
};

/**
 * @throws Node not found error
 */
Model.prototype.loadChildren = function (refId) 
{
	var node = this.getNode(refId);
	
	if (node.hasChildren && !node.childrenLoaded) {
		var data = {
			'action' : 'select', 
			'id' : node.id, 
			'axis' : 'child'
		};

		this._request(data);
	}
};

Model.prototype.unloadChildren = function (refId)
{
	var node = this.getNode(refId);
	
	var ids = []
	var parents = [node.id];
	do {
		var cId = parents.shift();
		var current = this.getNode(cId);
		ids.push.apply(ids, current.children);
		parents.push.apply(parents, current.children);
	}
	while (parents.length > 0);	
	
	for (var k=0; k<ids.length; k++) {
		delete this.nodes[ids[k]];
	}
	
	node.children = [];
	node.childrenLoaded = false;
};

Model.prototype.replaceBefore = function (refId, beforeId) 
{
	var refNode = this.getNode(refId);
	var beforeNode = this.getNode(beforeId);
	
	if (refNode.isRoot() || beforeNode.isRoot()) {
		throw new Error ('Невозможно перемеcтить узел в указанное место');
	}

	var data = {
		'action' : 'replaceBefore', 
		'id' : refNode.id, 
		'beforeId' : beforeNode.id
	};
	return this._request(data); 
};

Model.prototype.deleteNodes = function (refIds) 
{
	
};


Model.prototype._request = function (data)
{
	if (this.userdata) {
		var arr = [];
		Object.forEach(this.userdata, function (value, key) {
			arr.push(escape(key) + "=" + escape(value));
		});
		data.userdata = arr.join('&');
	} 
		
	var req = web2.ajax.createRequest(this.backend, {'data' : data});
	web2.event.trigger(this.tree, 'request', req);
	req.addListener(this.ajaxListener);
	req.send();
}

Model.prototype.onAjaxOpen = function (request)
{
	web2.event.trigger(this.tree, 'loading');
};

Model.prototype.onAjaxLoaded = function (request)
{
	web2.event.trigger(this.tree, 'loaded');
};

Model.prototype.onAjax400 = function (request)
{
	var action = request.params.data.action;
	switch (action) {
		case 'select':
			break;
		
		case 'replaceBefore':
			alert('Узел невозможно переметить в указанное место');
			break;
			
		default:
			break;
	}
};

Model.prototype.onAjax500 = function (request)
{
	alert('Internal Server Error');
};

Model.prototype.onAjaxSuccess = function (request, responseText)
{
	var action = request.params.data.action;
	switch (action) {
		case 'select':
			var vw = this.tree.getViewWorker(); 
			var refId = request.params.data.id;
			var node = this.getNode(refId);
			
			// refresh view
			vw.appendHTML(refId, responseText);
			
			// sync model
			children = vw.parseChildren(refId);
			Object.merge(this.nodes, children);
			Object.forEach(children, function (v, k) {
				node.children.push(parseInt(k, 10));
			});
			node.childrenLoaded = true;
			
			var ids = [];
			copyArray(ids, node.children);
			web2.event.trigger(this, 'nodesInserted', ids);
						
			break;
			
		case 'replaceBefore':
			var node = this.getNode(request.params.data.id);
			var before = this.getNode(request.params.data.beforeId);

			// refresh model			
			var nodeParent = this.getNode(node.parentId);
			var beforeParent = this.getNode(before.parentId);

			nodeParent.children.splice(nodeParent.children.indexOf(node.id), 1);
			beforeParent.children.splice(beforeParent.children.indexOf(before.id), 0, node.id);
			
			node.parentId = before.parentId;
			/**
			 * пересчет "левелов" в дочерних элементах node
			 */
			var ids = []
			var deltaLevel = before.level - node.level;
			
			this.nodes[node.id].level = before.level;
			
			var parents = [node.id];
			do {
				var cId = parents.shift();
				var current = this.getNode(cId);
				ids.push.apply(ids, current.children);
				parents.push.apply(parents, current.children);
			}
			while (parents.length > 0);	
	
			for (var k=0; k<ids.length; k++) {
				this.nodes[ids[k]].level += deltaLevel;
			}			 
			
			// sync view
			web2.event.trigger(this, 'nodeReplaced', node.id, before.id);
			break;
			
		default:
			break;
	}
};