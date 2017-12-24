function Tree (source, model, viewWorker, cookieName)
{
	if (arguments.length == 0) return;
	
	/**
	 * Элемент - HTML представление дерева
	 * @type {HTMLElement}
	 */
	this.source = source;
	
	/**
	 * Объект, обспечивающий мост между моделью и представлением.
	 * @type {virab.tree.ViewWorker}
	 */
	this.viewWorker = viewWorker;
	
	/**
	 * Установленная модель тут же синхронизируется с 
	 * представлением дерева
	 * @type {virab.tree.Model}
	 */
	this.model = model;
	
	/**
	 * Дерево сохраняет в переменной идентификаторы открытых веток,
	 * чтобы сохранить состояние при перезагрузке страницы
	 */
	this.cookieName = cookieName;
	
	// Инициализация
	this._setup();
};

Tree.prototype._setup = function ()
{
	if (! this.source) {
		throw new Error ('Не определен HTMLElement дерева');
	}
	
	if (! this.viewWorker) {
		this.viewWorker = new virab.tree.ViewWorker(this.source);
	}
	
	if (! this.model) {
		throw new Error ('Не определена модель дерева');
	}
	this.model.setTree(this);

	// подключеие обработчиков событий
	this._setupEvents();
};

Tree.prototype._setupEvents = function ()
{
	with (web2.event) {
		/**
		 * Слушатели событий модели
		 */
		bind(this.model, 'nodesInserted', this, this.onNodesInserted);
		bind(this.model, 'nodeReplaced', this, this.onNodeReplaced);
		
		/**
		 * Раскрытие/закрытие веток
		 */
		bind(this, 'expanded', this, this.writeCookie);
		bind(this, 'collapsed', this, this.writeCookie);		
		
		/**
		 * callback функции для реагирования на DOM события
		 */
		this.onToggleCallback = createAdapter(this, this.onToggle);
		this.onRolloverCallback = createAdapter(this, this.onRollover);
	}
	
	/**
	 * все доступные модели узлы
	 */
	var nodes = this.model.getData();
	
	/**
	 * массив идентификаторов узлов
	 */
	var identies = [];
	Object.forEach(nodes, function (v, k) {
		identies.push(k);
	});
	
	/**
	 * получение HTML элементов для узлов
	 */
	var elements = this.viewWorker.getElForNodeArray(identies);

	for (var i=0; i<identies.length; i++) {
		// подключение обработчиков событий к узлу
		this._setupNodeEvents(elements[i], nodes[identies[i]]);
	}
};

Tree.prototype._setupNodeEvents = function (el, node)
{
	with (web2.event) {
		/**
		 * События мыши
		 */
		addDomListener(el, 'mouseover', this.onRolloverCallback);
		addDomListener(el, 'mouseout', this.onRolloverCallback);
		
		/**
		 * Обработчик клика по иконке
		 */
		var icon = this.viewWorker.getNodeIcon(node, el);
		addDomListener(icon, 'click', this.onToggleCallback);
	}
};

/**
 * @return {virab.tree.ViewWorker}
 */
Tree.prototype.getViewWorker = function ()
{
	return this.viewWorker;
};

Tree.prototype.getModel = function ()
{
	return this.model;
};

Tree.prototype.hideRoot = function ()
{
	var root = this.model.getRoot();
	var el = this.viewWorker.getElForNode(root.id);
	if (! root.expanded) {
		this.expand(root, el);
	}
	web2.elem.hide(el);
};

/**
 * @param el 	необязательный параметр HTMLElement, 
 * 				в котором отрисовывается узел. 
 * 				Исключительно для скорости работы
 */
Tree.prototype.expand = function (node, el)
{
	var vw = this.viewWorker;
	el = el || vw.getElForNode(node);
	
	var icon = vw.getNodeIcon(node, el);
	icon.src = icon.src.replace('closed','open');
	
	if (node.children.length > 0) {
		var ids = [];
		var parents = [];
		copyArray(parents, node.children);
		do {
			var cId = parents.shift();
			var current = this.model.getNode(cId);
			ids.push(cId);
			if (current.expanded) {
				parents.push.apply(parents, current.children);				
			}
		}
		while (parents.length > 0);		
		
		var elements = vw.getElForNodeArray(ids);
		web2.elem.show.apply(null, elements);
	}
	else {
		/**
		 * Сообщаем модели о необходимости 
		 * загрузить дочерние элементы
		 */
		this.model.loadChildren(node.id);
	}
	
	node.expanded = true;
	//vw.refreshNodeAttributes(node, el);
	
	web2.event.trigger(this, 'expanded', node);
};

/**
 * @param el 	необязательный параметр HTMLElement, 
 * 				в котором отрисовывается узел. 
 * 				Исключительно для скорости работы
 */
Tree.prototype.collapse = function (node, el)
{
	var vw = this.viewWorker;
	el = el || vw.getElForNode(node);	
	
	var icon = vw.getNodeIcon(node, el);
	icon.src = icon.src.replace('open','closed');
	
	var ids = []
	var parents = [node.id];
	do {
		var cId = parents.shift();
		var current = this.model.getNode(cId);
		ids.push.apply(ids, current.children);
		parents.push.apply(parents, current.children);
	}
	while (parents.length > 0);
	
	var elements = vw.getElForNodeArray(ids);
	web2.elem.hide.apply(null, elements);
	
	node.expanded = false;
	//vw.refreshNodeAttributes(node, el);
	
	web2.event.trigger(this, 'collapsed', node);
};

/**
 * Слушатель клика по иконке разворачивания/сворачивания ветки дерева
 * @param {web2.event.DomEventAdapter} domEvent
 */
Tree.prototype.onToggle = function (domEvent)
{
	var vw = this.viewWorker;
	
	try {
		var el = vw.getEventTarget(domEvent);
		var nodeId = vw.getNodeAttribute(el, 'id');
		var node = this.model.getNode(nodeId);
		if (node.hasChildren) {
			if (node.expanded) {
				this.collapse(node, el);
			}
			else {
				this.expand(node, el);
			}
		}
	}
	catch (e) {
		web2.Log.write(e);
	}
};

/**
 * Слушатель mouse{over|out} событий мыши
 * @param {web2.event.DomEventAdapter} domEvent
 */
Tree.prototype.onRollover = function (domEvent)
{
	var el = this.viewWorker.getEventTarget(domEvent);
	if (domEvent.type == 'mouseover') {
		web2.css.addClass(el, 'hover');
	} else if (domEvent.type == 'mouseout') {
		web2.css.removeClass(el, 'hover');
	}
};

/**
 * Слушатель события 'nodesInserted' модели 
 * @see virab.tree.Model
 */
Tree.prototype.onNodesInserted = function (ids)
{
	var elements = this.viewWorker.getElForNodeArray(ids);
	
	for (var k=0; k<ids.length; k++) {
		this._setupNodeEvents(elements[k], this.model.getNode(ids[k]));
	}
};

/**
 * Слушатель события 'nodeReplaced' модели 
 * @see virab.tree.Model
 */
Tree.prototype.onNodeReplaced = function (nodeId, beforeId)
{
	var vw = this.viewWorker;
	var node = this.model.getNode(nodeId);
	var el = vw.getElForNode(nodeId);
	
	this.collapse(node, el);
	
	vw.deleteChildren(nodeId, el);
	vw.replaceBefore(nodeId, beforeId, el);
	this._setupNodeEvents(el, node);	
	
	this.model.unloadChildren(nodeId);
};

/**
 * По событиям 'expanded' и 'collapsed' изменяет куки, 
 * хранящие информацию об открытых видимых ветках дерева
 */
Tree.prototype.writeCookie = function (changedNode)
{

	if (! this.cookieName) return;
	
	var root = this.model.getRoot();
	var ids = [];
	var parents = [root.id];
		
	do {
		var cId = parents.shift();
		var current = this.model.getNode(cId);
		
		/**
		 * Если ветка раскрыта, то запоминается идентификатор 
		 * и  поиск продолжается по дочерним узлам
		 */
		if (current.expanded) {
			ids.push(cId);
			parents.push.apply(parents, current.children);
		}
	}
	while (parents.length > 0);
	
	/**
	 * Сохранение cookie
	 */
	web2.setCookie(this.cookieName, ids.join(','));
};


