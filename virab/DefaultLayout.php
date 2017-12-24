<html>
	<head>
		<meta http-equiv="content-Type" content="text/html; charset=utf-8">
		<link rel="icon" type="image/png" href="/virab/images/favicon.png">
		<title>VIRAB Pro [<?=PROJECT_ID?> • r<?=VIRAB_REVISION?>]</title>

		<link rel="stylesheet" type="text/css" href="css/general.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/prettyPhoto.css">
		<link rel="stylesheet" type="text/css" href="css/ui.all.css">
		<link rel="stylesheet" type="text/css" href="css/autosuggest.css">

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		
		<script type="text/javascript" src="/js/jquery.prettyPhoto.js"></script>
		<script type="text/javascript" src="/js/jquery.lightbox.js"></script>
		<script type="text/javascript" src="/js/jquery.ui.js"></script>
		<script type="text/javascript" src="/js/autosuggest.js"></script>

		<script type="text/javascript" src="js/general.js"></script>

		<!-- Для удаления -->
		<script type="text/javascript" src="js/js2/web20.js"></script>
		<script type="text/javascript" src="js/js2/virab.js.php"></script>
		<script type="text/javascript" src="js/js2/common.js"></script>
		<!-- /Для удаления -->

		<script language="javascript" type="text/javascript" src="/virab/js/edit_area/edit_area_compressor.php"></script>
		<script type="text/javascript">
			btMon = new Image(); btMon.src="images/lf.gif";
			btMoff = new Image(); btMoff.src="images/spacer.gif";
			/* Config for tVirab */
			$tVirab = {};
			$tVirab.CONFIG = <?php
					include_once (LIB_PATH . "/ajax/JSON.php");
					$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
					echo $json->encode(array(
						'TYPE_TE_VALUE' => $__TYPE_TE_VALUE,
						'LNG' => $lng->lng_array
					));
			?>;
		</script>
	</head>

	<body class="TVirabBody">
 		<div class="TVirabLeftBox">
 			<?php include ("fbx/dsp_Menu.php");?>
 		</div>

 		<div class="TVirabRightBox">
 			<div class="TVirabVersion light_grey">
 				<?=_("Язык интерфейса:&nbsp;")?>
 				<?php foreach ( $__LNG_ARRAY as $dict_lng_value ): ?>
 					<?php if ($dict_lng_value['id'] != $lng->getNowLng()): ?>
 						<a href="/virab/setLanguage.php?lng=<?=$dict_lng_value['id']?>"><img class="TVirabLanguageChange TVirabLanguageNotSelect" src="<?=$dict_lng_value['flag']?>" title="<?=$dict_lng_value['ind_name']?>"></a>&nbsp;
 					<?php else: ?>
 						<img class="TVirabLanguageChange" src="<?=$dict_lng_value['flag']?>" title="<?=$dict_lng_value['ind_name']?>">&nbsp;
 					<?php endif; ?>
 				<?php endforeach; ?>
 				&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;
 				<a href="http://www.cruiser.com.ua" class="light_grey" target="blank">&copy; Cruiser design & software group. </a>
 				<b>[<?=strtoupper(CFG_DB_DATABASE)?>]</b> <span class="small_red">Ver 0.9b [r<?=VIRAB_REVISION?>]</span> - <a href="/virab/index.php?logout=1">Выход</a>
 			</div>

 			<div class="TVirabNavigation">
 				<?php include ("fbx/dsp_Navigation.php");?>
 			</div>

 			<div class="TVirabQuickHelpBlock">
 				<?php include ("fbx/dsp_QuickHelp.php");?>
 			</div>

 			<div class="TVirabContent"><?=trim($Fusebox["layout"]);?></div>
 		</div>

 		<script type="text/javascript">
			<?php
				$mtime = microtime(1);
				$totaltime = round(($mtime - $tstart), 3);
			?>
 			$(function() {
				$(".TVirabVersion").attr('title', '<?=_("Генерация страницы")?>: <?=$totaltime?> сек.');
 	 		});
 		</script>

	</body>
</html>