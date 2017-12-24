<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<table width=100% cellpadding=0 cellspacing=0 border=0>
 <tr>
   <td colspan=3 height=30>&nbsp;</td>
 </tr>
 <tr>
<?
  // Определим текущий раздел
  $rsTop = $db->query("
      SELECT id FROM " . CFG_DBTBL_NAVIGATION . "
      WHERE url='{$fuseaction}' AND menu=1 
  ");
  if ($mitem = $rsTop->fetch_assoc()) {
						$rsSubCats = $db->query("
										SELECT id, title, url, quick_help FROM " . CFG_DBTBL_NAVIGATION . "
										WHERE parent_id = '{$mitem['id']}' AND menu=1 ORDER BY ord 
						");
						for ($i = 0; $msubitem = $rsSubCats->fetch_assoc(); $i++) {
										if ($i == 3) {
										     echo "</tr><tr>";
										     $i = 0;
										}
										echo "<td width=33% style=\"padding: 15px; text-align: justify;\" valign=\"top\"><a href=\"index.php?fuseaction={$msubitem['url']}\" title=\"".$lng->Gettextlng($msubitem['quick_help'])."\"><b><center>".$lng->Gettextlng($msubitem['title'])."</center></b><br>".$lng->Gettextlng($msubitem['quick_help'])."</a></td>";
						}
		}
?>
 </tr>
</table>