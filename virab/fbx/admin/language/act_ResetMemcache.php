<?php
/*
 * Project: tVirab
 * Author: Red`kin (ter) Serge [rou.terra@gmail.com]
 * Copyright by Cruiser [cruiser.com.ua]
 * Created on 04.01.2010, 14:23:10
 */

	if (class_exists('Memcache')) {
		$memcache = new Memcache();
		$memcache->connect(MEMCACHE_CONFIG_HOST, MEMCACHE_CONFIG_PORT);
		$memcache->flush();
	}

	Location($_XFA['main'], 0);

?>
