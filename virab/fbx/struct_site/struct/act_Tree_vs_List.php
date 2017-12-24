<?php
	$_SESSION['type_visual_site_nodes'] = ($_SESSION['type_visual_site_nodes'] === "tree") ? "list" : "tree";
	Location($_XFA['main'], 0);
?>