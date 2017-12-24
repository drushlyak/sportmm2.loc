<meta http-equiv="Content-Type" content="text/html; charset=utf-81">
<script language="JavaScript" src="js/list.js"></script>
<?php
if($attributes['str_error']){
	echo "<p class=\"cerr\">".$attributes['str_error']."</p>";
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>

<div id="usersdiv" class="scroll-table">
	<!-- Пользователи и группы -->
	<?php include('dsp_UserGroup.php') ?>
</div>

<div id="objsdiv" class="scroll-table">
	<!-- Объекты доступа -->
	<?php include('dsp_ObjRight.php') ?>
</div>
<div id="rightsdiv" class="scroll-table">
	&nbsp;
</div>
<input type="hidden" id="role_id" name="role_id" autocomplete="off">
<input type="hidden" id="module_id" name="module_id" autocomplete="off">

<script type="text/javascript">
	var userGrid = new virab.UserObjectGrid(web2.dom.get("xv_ug_datagrid"), "<?=$_XFA['backend']?>", web2.dom.get("rightsdiv"));
	var objGrid = new virab.UserObjectGrid(web2.dom.get("xv_obj_datagrid"), "<?=$_XFA['backend']?>", web2.dom.get("rightsdiv"));
	
	objGrid.setBgColorFirstEl();
	
	<?php
		// если выбраны id и type пользователя или группы
		if (isset($attr['idug']) && isset($attr['type'])) {
			?>
			userGrid.setBgColorAndScroll();
			window.setTimeout(function () {
				userGrid.checkReqData();
			}, 10);			
			<?php
		}
	?>
	
	
	function editRights(resourceId)
	{
		var roleId = document.getElementById('role_id').value;
		var moduleId = document.getElementById('module_id').value;
		location.href = '<?=$_XFA['form']?>&resource_id='+resourceId+'&role_id='+roleId+'&module_id='+moduleId;
	}

	function OKsend (param)
	{
		var update = new UpdateRi (param);
		
	}
	
	function UpdateRi (param) {
		this.ajaxListener = {
			onSuccess 	: web2.event.callback(this, this.onAjaxSuccess)
		};

		param.action = 'updaterights';
		this._request(param);		
	}
	
	UpdateRi.prototype._request = function (data)
	{
		var req = web2.ajax.createRequest('<?=$_XFA['backend']?>', {'data' : data});
		req.addListener(this.ajaxListener);
		req.send();
	};

	UpdateRi.prototype.onAjaxSuccess = function (request, responseText)
	{
		window.setTimeout(function () {
			userGrid.checkReqData();
		}, 10);
	};
	
</script>