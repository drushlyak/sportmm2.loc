function Node (id, parentId, level, hasChildren, childrenLoaded, children, expanded)
{
	this.id = id;
	this.parentId = parentId;
	this.hasChildren = Boolean(hasChildren);
	this.childrenLoaded = Boolean(childrenLoaded);
	this.children = children || [];
	this.expanded = Boolean(expanded);  
	this.level = level;
};

Node.prototype.isRoot = function ()
{
	return this.parentId == 0;
}