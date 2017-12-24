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
		<script type="text/javascript" src="/js/jquery.ui.js"></script>
		<script type="text/javascript" src="/js/autosuggest.js"></script>

		<script type="text/javascript" src="js/general.js"></script>

		<!-- Для удаления -->
		<script type="text/javascript" src="js/js2/web20.js"></script>
		<script type="text/javascript" src="js/js2/virab.js.php"></script>
		<script type="text/javascript" src="js/js2/common.js"></script>
		<!-- /Для удаления -->

		<script language="javascript" type="text/javascript" src="/virab/js/edit_area/edit_area_full.js"></script>
		<script type="text/javascript">
			btMon = new Image(); btMon.src="images/lf.gif";
			btMoff = new Image(); btMoff.src="images/spacer.gif";
		</script>
	</head>

	<body class="TVirabBody">
 			<div class="TVirabQuickHelpBlock">
 				<?php include ("fbx/dsp_QuickHelp.php");?>
 			</div>

 			<div class="TVirabContent"><?=trim($Fusebox["layout"]);?></div>

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