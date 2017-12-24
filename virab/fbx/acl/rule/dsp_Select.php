<?php
function treeParent ($node)
{
	global $resTree;
	static $parents;
	if((!$parents) && ($node['level']>0)){
		$par = $resTree->getParentNode($node['id']);
		$parents[$node['level']-1] = $par['id'];
	}
// Поиск родительского узла
	$parentId = $parents[$node['level']-1];
	$parents[$node['level']] = $node['id'];
	return $parentId;
}

function treeDump ($nodeset)
{
	global $parents, $lng, $_XFA, $regRight, $rightVisualData, $type_nodename, $auth_in, $privSet, $role_id;

	foreach ($nodeset as $node) {
		$primary = $node['id'];
		$level = $node['level'];
		if($level == 0) {
			$expanded = 1;
		} else {
			$expanded = 0;
		}
		$parentId = (int) treeParent($node);
?>
		<tr x-primary="<?=$primary?>"
			x-parent="<?=$parentId?>"
			x-level="<?=$level?>"
	  		<?php if ($node['has_children']) { ?>
	  		x-has-children="1"
	  		<?php } ?>
	  		<?php if ($expanded) {?>
	  		x-expanded="1"
	  		<?php } ?>
		>
		<td width="1%"> </td>
		<td width="80%" style="padding-left:<?=12*($level)?>px"><img style="cursor:pointer;" src="<?=($node['has_children'])?($expanded ? 'images/tree_folder_open.gif' : 'images/tree_folder_closed.gif') : 'images/tree_leaf.gif'?>" />
		<?=$lng->Gettextlng($node['name'])?>
		</td>
		<td width="100%"  style="text-align: center;">
			<div>
<?php
if($privSet){
	foreach($privSet as $priv){
		if($auth_in->acl->isAllowed($role_id, $node['data_id'], $priv['id'])){
			print "<img src='images/but/yes.gif'>";
		}else{
			print "<img src='images/but/nor.gif'>";
		}
	}
}
?>
			</div>
		</td>
		<td class="actions">
			<div>
<?php       if($auth_in->aclCheck($resourceId, EDIT)): ?>
 			<a href="javascript:void(0);" onClick="editRights('<?=$node['id']?>')"><img src="images/but/ed.gif" width=17 height=16 border=0 title="<?=_("Редактировать");?>"></a> &nbsp;
<?php endif ?>
 			</div>
		</td>
		</tr>
		<?php
	}
}

function NodeName ($node, $type_nodename) {
	global $lng;
// выбор вывода:
	return $lng->Gettextlng($node['name']);
}
if ($nodeSet) {
	$parents = array();
	treeDump($nodeSet);
} else {
?>
	<tr x-primary="1" x-parent="0" x-level="0" x-has-children="1">
		<td>&nbsp;</td>
		<td><img src="images/check_no.gif" /> root </td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr x-primary="2" x-parent="1" x-level="1" x-has-children="0">
		<td width="1%">&nbsp;</td>
		<td width="80%"><img src="images/check_no.gif" /> <?=_("... пусто")?> </td>
		<td width="100%">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
<?php
}

?>