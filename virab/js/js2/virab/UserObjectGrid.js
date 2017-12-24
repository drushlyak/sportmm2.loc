/**
 * source HTMLElement - таблица
 * backend string - url backend (ajax)
 * objout HTMLElement - объект вывода (div)
 */
function UserObjectGrid (source, backend, objout)
{
	this.source = source;
	this.backend = backend;
	this.objout = objout;
	
	this.roleId = null;
	this.moduleId = null;
	
	web2.event.bindDom(this.source,'click',this,this.onRowClick);
	
	this.ajaxListener = {
		onLoaded	: web2.event.callback(this, this.onAjaxLoaded),
		onSuccess 	: web2.event.callback(this, this.onAjaxSuccess)
	};

};

UserObjectGrid.prototype.onRowClick = function (e) 
{
	var row = e.getTarget();
//	try {
		while (row.nodeName != "TR" && row) {
			row = row.parentNode;
		}
		this.clearBG();
		row.style.backgroundColor = 'infobackground';
		// заполнение hidden полей
		var id = row.getAttribute('x-primary');
		if (this.source.getAttribute('id') == 'xv_ug_datagrid') {
			document.getElementById('role_id').value = id;
		} else {
			document.getElementById('module_id').value = id;
		}

		this.checkReqData();

//	} catch (e) {}
};

UserObjectGrid.prototype.clearBG = function ()
{
	for(var i=0; i<this.source.rows.length; i++) {
		this.source.rows[i].style.backgroundColor = 'white';
	}
};

UserObjectGrid.prototype.checkReqData = function ()
{
	var roleId = parseInt(document.getElementById('role_id').value, 10);
	var moduleId = parseInt(document.getElementById('module_id').value, 10);
	
	if (roleId && moduleId) {
		this._request({
			'role_id': roleId,
			'module_id': moduleId
		});
	}
	
	return;
	
	return console.log('request rights');
	
	
	var id = document.getElementsByName('idug')[0].value;
	var type = document.getElementsByName('type')[0].value;
	var idapl = document.getElementsByName('idapl')[0].value;
	if (id && type && idapl) {
		document.getElementById('rightsdiv').style.display = 'block';
		// запрос
		var data = {
			'action' 	: 'loadingrights', 
			'idug' 		: id, 
			'type' 		: type,
			'idapl'		: idapl
			};
		this._request(data);	
	}
};

/**
 * Установка фона для элемента с x-primary = id + прокрутка 
 */
UserObjectGrid.prototype.setBgColorAndScroll = function ()
{
	var id = document.getElementById('role_id').value;
	
	for(var i=0; i<this.source.rows.length; i++) {
		if (this.source.rows[i].getAttribute('x-primary') == id) {
			
			this.source.rows[i].style.backgroundColor = 'infobackground';
			//scroll
			var size = web2.elem.getBox(this.source);
			var sizerow = web2.elem.getBox(this.source.rows[i]);
			document.getElementById('usersdiv').scrollTop = (sizerow.y - size.y);
			
			return;
		}
	}
};

/**
 * Установка фона для первого элемента таблицы
 */
UserObjectGrid.prototype.setBgColorFirstEl = function ()
{
	var id = this.source.rows[1].getAttribute('x-primary');
	this.source.rows[1].style.backgroundColor = "infobackground";
	document.getElementById('module_id').value = id;
};

// AJAX

UserObjectGrid.prototype._request = function (data)
{
	var req = web2.ajax.createRequest(this.backend, {'data' : data});
	req.addListener(this.ajaxListener);
	req.send();
};

UserObjectGrid.prototype.onAjaxLoaded = function (request)
{
	//var transport = request.transport;
	//web2.Log.write(transport.responseText);
}

UserObjectGrid.prototype.onAjaxSuccess = function (request, responseText)
{
	this.objout.innerHTML = responseText;
	// подключение обработчиков
	var model = new virab.tree.Model(this.backend);
	var moduleId = request.params.data.module_id;
	var roleId = request.params.data.role_id;
	model.setUserData({
		'module_id' : moduleId, 
		'role_id' : roleId
	});
	
	var treeGrid = new virab.TreeGrid(web2.dom.get("xv_datagrid"), model, null, 'rights_tree_'+module_id, false);
	//treeGrid.hideRoot();
	
	var e = document.getElementById('rightsdiv');
	e.style.display = 'block';
};