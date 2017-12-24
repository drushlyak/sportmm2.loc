<?php
	/*
	 * Created on 19.11.2009
	 */

	/**
	 * Функция, возвращающая массив имен архивов дампов БД
	 *
	 * @param string $dir_path
	 */
	function getDirStruct($dir_path) {
		$res = array();
		$d = dir($dir_path);
		while (false !== ($entry = $d->read())) {
			if ($entry !== '.' && $entry !== '..') {
				$res[] = $entry;
			}
		}
		$d->close();
		return $res;
 	}

	/**
	 *
	 */
	function getDumpParamSet($files = array()) {
		global $db;

		$res = array();

		/**
		 * Структура наименования файла (с разделением знаком _):
		 * dump ........................................ 0
		 * PROJECT_ID (с удаленным _) .................. 1
		 * ID пользователя ............................. 2
		 * дата-время в формате Unix timestamp ......... 3
		 * признак загруженного извне дампа (0 или 1) .. 4
		 */
		foreach ( $files as $file_name ) {
			$fa = explode('_', str_replace('.sql.gz', '', $file_name));
			if (count($fa) === 5) {
				$res[] = array(
					'id' => md5($file_name),
					'login' => $db->get_one("SELECT login FROM " . CFG_DBTBL_UDATA . " WHERE id = ?", $fa[2]),
					'utime' => (int) $fa['3'],
					'idate' => date("d.m.Y H:s:i", (int) $fa['3']),
					'file' => $file_name,
					'is_loaded' => (int) $fa['4']
				);
			}
		}

		// сортируем по времени создания (поле utime)
		function cmp($a, $b) {
		    if ($a['utime'] == $b['utime']) {
		        return 0;
		    }
		    return ($a['utime'] > $b['utime']) ? -1 : 1;
		}
		uasort($res, 'cmp');

		return $res;
	}

	/**
	 *
	 */
	function getIDS($files = array()) {
		$res = array();
		foreach ( $files as $file_name ) {
			$res[md5($file_name)] = $file_name;
		}
		return $res;
	}

?>
