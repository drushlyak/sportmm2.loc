<table width="100%" cellpadding="0" cellspacing="15" border="0">
	<tr>
<?
global $nsTree;
$temp = 0;
$menuSet =  $mnTree->select(35, 
	array(
		'name', 
		'id_node', 
		'url', 
		'enable'), 
	NSTREE_AXIS_DESCENDANT
);
$i = 0;
$html = "";
foreach($menuSet as $node){
	if($node['level'] != 2){
		continue;
	}
	$i++;
	$url = ($node['url'])?$node['url']:"http://".GetDotId($node['id_node'], $nsTree);
$html .=<<<EOF
	<td id="tm_{$i}" nowrap="nowrap">
		<div id="tm_div_{$i}" onmouseover="tm_roll({$i});" onmouseout="tm_rest({$i});">&nbsp; 
			<a href="{$url}" id="tm_link_{$i}">{$lng->Gettextlng($node['name'])}</a> &nbsp;
		</div>
	</td>
EOF;
}
print $html;
?>
</tr>
</table>