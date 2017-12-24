<?
define('VIRAB_PRO', true);
require_once ("../conf/core.cfg.php");
require_once (LIB_PATH . "/Common.php");	
$id = intval($_REQUEST["id"]);
$sql = "
	SELECT * 
	FROM ".CFG_DBTBL_MOD_FOTO." 
	WHERE id = $id
";
$rsNodes = $db->query($sql);
$v = $rsNodes->fetch_assoc();
if($v){
	$image_width = $site_config['width_norm_fotogr'];
	$image_height = $site_config['height_norm_fotogr'];
	$v["title"] = $lng->Gettextlng($v["name"]);
	$v["desc"]  = $lng->Gettextlng($v["description"]);
	$v["alt"] = $v["title"].". ".$v["desc"];
	$v["src"] = $v['url']."_o.".$v['exten'];
?>
<html>
<head>
<title><?=$v["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body style="padding:10px 0px 0px 0px; margin:0; background-color:#ffffff; font-family: Tahoma; font-size:11px; text-align:center;"><img src="<?=$v["src"]?>" alt="Кликните по фотографии, чтобы закрыть окно" onclick="self.opener=null;self.close();" style="cursor:pointer;"><br />
<span style="text-align:center"><b><?=$v["title"]?></b></span><br />
<?=$v["desc"]?>
</body>		
</html><?	} ?>