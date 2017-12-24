
  btNon= new Image(); btNon.src="images/but/open_folder.gif";
  btNoff= new Image(); btNoff.src="images/but/fld1.gif";


/**
 * Старая раскрывалка
 * @param {} id
 */
function clickTree(id) {
		var targetId_d, targetId_f, targetElement_d, targetElement_f, counter;

		for (var counter = 1; true; counter++) {
				targetId_d = "d" + id + "_" + counter;
				targetId_f = "f" + id + "_" + counter;
				if (!document.getElementById(targetId_d)) break;
				targetElement_d = document.getElementById(targetId_d);
				targetElement_f = document.getElementById(targetId_f);
				targetElement_d.style.display = (targetElement_d.style.display == "none") ? "" : "none";
				targetElement_f.style.display = targetElement_d.style.display;
		}
  chn = 'n' + id;
		if (document[chn].src==btNon.src) document[chn].src=btNoff.src;
				else document[chn].src=btNon.src;
}

/**
 * ===========================================================================================================
 * Операции с деревьями
 * @author ter
 * ===========================================================================================================
 */

/**
	 * Хранилище ID рут ноды таблиц
	 * @type array
	 */
	Cruiser.tablesRootID = [];

	/**
 * Построение структуры таблицы
 * @param id_table
 * @return object
 */
	function reDumpTree(tableID) {
		var struct = {'id' : tableID },
			trs = $('#' + tableID + ' tr[gid]');

	for (var i=0, len=trs.length; i < len; i++) {
		var el = trs[i],
			id = parseInt(el.getAttribute('gid'), 10),
			pid = parseInt(el.getAttribute('pid'), 10),
			level = parseInt(el.getAttribute('lv'), 10),
			has_child = parseInt(el.getAttribute('has_child'), 10),
			collapsed = parseInt(el.getAttribute('collapsed'), 10);

			if (isNaN(pid)) {
				pid = 0;
			}

		struct[id] = {
			id			: id,
			id_tr		: (el.id).replace(/^[\w]/, ''),
			pid 		: pid,
			level 		: level,
			has_child	: !!has_child,
				collapsed	: !!collapsed,
				parents		: [ pid ]
		};

			// первая нода всегда "прицеплена" к рут ноде
			if (i === 0) {
				Cruiser.tablesRootID[tableID] = pid;
			}
	}

	// child nodes
	var childs = {},
		ch_nodes = {};
	for (var k in struct) {
			if (typeof struct[k] === 'object') {
			var pid = struct[k]['pid'];
			if (typeof childs[pid] == 'undefined') {
				childs[pid] = 1;
				ch_nodes[pid] = [struct[k]['id']];
			} else {
				childs[pid] += 1;
				ch_nodes[pid].push(struct[k]['id']);
			}

				// рекурсивная расстановка parent нод
				struct[k]['parents'] = array_values(getArrayParentNodes(k, [], struct, tableID));
		}
	}

	// расставим в исходную структуру
	for (var k in childs) {
		if (typeof struct[k] != 'undefined') {
			struct[k]['count_child'] = childs[k];
			struct[k]['childs'] = ch_nodes[k];
		}
	}

	return struct;
}

/**
	 * Получение массива родительских нод
	 * @param {int} id
	 * @param {array} nodes
	 * @param {object} struct
	 * @return {array}
	 */
	function getArrayParentNodes(id, nodes, struct, tableID) {
		var pid = struct[id]['pid'],
			arrNodes = array_merge(nodes, [ pid ]);

		if (pid !== Cruiser.tablesRootID[tableID]) {
			arrNodes = array_merge(arrNodes, getArrayParentNodes(pid, arrNodes, struct, tableID));
		}

		return array_unique(arrNodes);
	}

	/**
	 * Переход на ноду с открытием родительских
	 * @param {} id
	 * @param {} tableID
	 */
	function gotoAndShowNode(id, tableID) {
		var struct = window[tableID],
			parents = struct[id]['parents'];

		if (parents.length) {
			for (var i = 0, len = parents.length; i < len; i++) {
				expandNode(parents[i], struct);
			}
		}

		setTimeout(function() {
			var target = $("tr#d" + id);

			var top = target.offset().top;
			$('html,body').animate({scrollTop: top}, 200, null, function() {
				target.effect("highlight", { color: '#FFFFAA' }, 1000);
			});

		}, 250);

	}

	/**
 * Триггер событий нод дерева
 *
 * @param {} struct
 * @param {} id
 */
function triggerNode(struct, id) {
	if (struct[id].collapsed) {
		// разворачиваем
		expandNode(id, struct);
	} else {
		// сворачиваем
		recursiveCollapseNode(id, struct);
	}
}

/**
 * Разворачивание ноды
 * @param {} id
 * @param {} struct
 */
function expandNode(id, struct) {
	var curr_struct = struct[id] || {};
	if (curr_struct['has_child'] && curr_struct['childs']) {
		for (var i=0, len=curr_struct['childs'].length; i < len; i++) {
			var idtr = struct[curr_struct['childs'][i]]['id_tr'];
			//$("#d" + idtr).show();
			$("#d" + idtr).animate({"opacity": "show"}, 250);
		}
		curr_struct['collapsed'] = false;
		changeInStore(struct.id, id, true);

			var img = $('#foldimage' + id),
				fo = img.parent().parent().attr('ofic');

			img.attr('src', (fo ? fo : btNon.src));
	}
}

/**
 * Рекурсивное сворачивание нод
 * @param {int} id
 * @return void
 */
function recursiveCollapseNode(id, struct) {
	var cstruct = struct[id];
	if (cstruct['has_child'] && cstruct['childs']) {
		for (var i=0, len=cstruct['childs'].length; i < len; i++) {
			var cst = cstruct['childs'][i],
				idtr = struct[cst]['id_tr'];
			//$("#d" + idtr).hide();
			$("#d" + idtr).animate({"opacity": "hide"}, 250);
			if (struct[cst]['has_child']) {
				// если имеются child nodes свернем и их
				recursiveCollapseNode(cst, struct);
			}
		}
		cstruct['collapsed'] = true;
		changeInStore(struct.id, id, false);

			var img = $('#foldimage' + id),
				fic = img.parent().parent().attr('fic');

			img.attr('src', (fic ? fic : btNoff.src));
	}
}

/**
 * Объект хранения истории развернутых нод
 * @type object
 */
window.__storeNodesState = {};

/**
 * Изменения в объекте хранения истории развернутых нод
 * @param {string} id_table
 * @param {int} id_node
 * @param {boolean} expand
 */
	function changeInStore(tableID, id_node, expand) {
		if (typeof __storeNodesState[tableID] == 'undefined') {
			expand ? __storeNodesState[tableID] = [id_node] : null;
	} else {
			var store = __storeNodesState[tableID];
		if (expand && store.indexOf(id_node) == -1) {
			store.push(id_node);
		} else if(!expand && store.indexOf(id_node) != -1) {
			store.splice(store.indexOf(id_node), 1);
		}
	}
		Cruiser.setCookie(tableID, __storeNodesState[tableID].join(','), 999);
}

/**
 * Функция восстановления состояния нод
 * @param {string} id_table
 * @param {object} struct
 */
	function restoreNodeState(tableID, struct) {
		var state_str = Cruiser.getCookie(tableID);
	if (state_str) {
		state = state_str.split(',');
		// развернем
		for (var i = 0, len=state.length; i < len; i++) {
			expandNode(parseInt(state[i],10), struct);
		}
	}
}
