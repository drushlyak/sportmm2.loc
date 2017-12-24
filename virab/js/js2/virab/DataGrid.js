function DataGrid (table)
{
	if (!table) return;
	this.bindTable(table);
};

DataGrid.prototype.bindTable = function (table)
{
	this.table = table;
	this.rows = table.rows;
	
	for (var i=1; i<table.rows.length; i++) {
		this.bindRow(table.rows.item(i));
	}
};

DataGrid.prototype.bindRow = function (row)
{
	return;
	with (web2.event) {
		addDomListener(row, 'mouseover', this.mouseOverRowHandler);
		addDomListener(row, 'mouseout', this.mouseOutRowHandler);
	}
};

DataGrid.prototype.getWrapperRow = function (el)
{
	var row;
	for (row = el; 
		 row.nodeName != 'TR' && row.parentNode; 
		 row = row.parentNode)	{};
		 
	return row;
}