<?php echo $_SESSION['pitanie_message_txt'];
	switch($_SESSION['action']) {
	case 'order':
		$url = "/cart/to_order/step2/";
		break;
	default:
		$url = '/';
	}
?>

<script type="text/javascript">
						setTimeout(function () {
							window.location = "<?=$url?>";
						}, 3000);
					</script>