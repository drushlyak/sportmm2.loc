<?php
		/**
		 * Флаг использования memcache
		 */
		define('MEMCACHE_USE', true);
		/**
		 * IP адрес сервера с memcached демоном
		 */
		define('MEMCACHE_CONFIG_HOST', 'localhost');
		/**
		 * Порт memcached демона
		 */
		define('MEMCACHE_CONFIG_PORT', 11211);
		/**
		 * Наименование проекта.
		 * Необходимо для разграничений записей в memcache по различным проектам
		 */
		define('MEMCACHE_CONFIG_PROJECT', 'pitanie_virab');
