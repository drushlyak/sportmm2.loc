<?php
	define('VIRAB_PRO', true);
	require_once ("../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");

	if (class_exists('Memcache')) {
		$memcache = new Memcache();
		$memcache->connect(MEMCACHE_CONFIG_HOST, MEMCACHE_CONFIG_PORT);
		$memcache->flush();
	}
?>