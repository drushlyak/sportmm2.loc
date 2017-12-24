<?php

	$refer = $_SERVER['HTTP_REFERER'];

	function redirect($url, $timeout=1000) {
		if (headers_sent()) {
			?>
				<script type="text/javascript">
					window.setTimeout(function () {self.location.replace("<?=$url?>");}, <?=$timeout?>);
				</script>
			<?php
		} else {
			header("Location: $url");
			die();
		}
	}


	$lng = (int) $_REQUEST['lng'];

	session_start();
	if ($lng) {
		$_SESSION['lng_selected'] = $lng;
	}

	if ($refer != '') {
		redirect($refer);
	} else {
		redirect('/virab/index.php');
	}

?>
