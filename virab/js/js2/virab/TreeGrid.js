/**
 * dragflag bool (true - enable dragdrop)
 */
function TreeGrid (source, model, viewWorker, cookieName, dragflag)
{
	Tree.prototype.constructor.call(this, 
		source, model, new virab.tree.ViewWorker(source), cookieName
	);
	
	// drag
	/*
	if (dragflag || isNaN(dragflag)) {
		
		this.dragRows = new DraggableRows (this.source);
		web2.event.bind(this.dragRows,'drop',this,this.onRowDrop);
	}
	*/
};

TreeGrid.prototype = new Tree();

TreeGrid.prototype.onRowDrop = function (fromIndex,toIndex) 
{
	var vw = this.viewWorker;
	var nodeId = vw.getNodeAttribute(this.getRowEl(fromIndex),'id');
	var beforeId = vw.getNodeAttribute(this.getRowEl(toIndex),'id');
	try {
		this.model.replaceBefore(nodeId, beforeId);
	}
	catch (e) {
		alert('Невозможно перемеcтить узел в указанное место');
	}
};

TreeGrid.prototype.getRowEl = function (index)
{
	return this.source.rows[index];
};