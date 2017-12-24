<?php

	/**
	 * @package Language
	 *
	 * @author Vivaldy, Marat, Pasha
	 *
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @copyright Cruiser cruiser.com.ua
	 * @version 0.3
	 */

	/**
	 * Класс языковых преобразований с поддержкой memcache
	 *
	 * Пример использования:
	 * <code>
	 * 		$lng = new Language();
	 * 		$lng->Init();
	 * </code>
	 *
	 * @package Language
	 */
	class Language {

		/**
		 * @var integer Язык по-умолчанию
		 */
		public $deflt_lng;

		/**
		 * @var integer Текущий язык
		 */
		public $now_lng;

		/**
		 * @var array Массив языков
		 */
		public $lng_array;

		/**
		 * @var mydb Database Layer
		 */
		private $_db;

		/**
		 * @var boolean Флаг использования механизма memcache
		 */
		private $_use_memcache = MEMCACHE_USE;

		/**
		 * @var Memcache Экземпляр класса Memcache
		 */
		private $_memcache;

		/**
		 * @var string Поле имен
		 */
		private $_memcacheNamespace;

		/**
		 * @var boolean Флаг проверки уникальности UID
		 */
		private $_check_unique = true;

		/**
		 * Деструктор класса
		 */
		public function __destruct() {
			if ($this->_use_memcache) {
				$this->_memcache->close();
			}
		}

		/**
		 * Инициализация
		 *
		 * @return boolean
		 */
		public function Init() {
			$this->_db = getDBInstance();

			$this->lng_array = $this->_db->get_all("
				SELECT id
					, deflt
					, ind_name
				FROM " . CFG_DBTBL_DICT_LANGUAGE
			);
			$this->deflt_lng = $this->_db->get_one("
				SELECT id
					FROM " . CFG_DBTBL_DICT_LANGUAGE . "
				WHERE deflt = 1
			");
			$this->now_lng = $this->deflt_lng;

			if ($this->_use_memcache) {
				if (class_exists('Memcache')) {
					$this->_memcache = new Memcache();
					$this->_memcache->connect(MEMCACHE_CONFIG_HOST, MEMCACHE_CONFIG_PORT) or $this->_use_memcache = false;
					$this->_memcacheNamespace = "lng_" . MEMCACHE_CONFIG_PROJECT . "_";
				} else {
					$this->_use_memcache = false;
				}
			}

			return ($this->deflt_lng && is_array($this->lng_array));
		}

		/**
		 * Получение текущего ID языка
		 *
		 * @return int ID языка
		 */
		public function getNowLng() {
			return $this->now_lng;
		}

		/**
		 * Установка текущего языка
		 *
		 * @param int ID языка
		 * @return void
		 */
		public function setNowLng($lng_id) {
			$this->now_lng = $lng_id;
		}

		/**
		 * Генерация идентификатора языковой конструкции
		 *
		 * @return string
		 */
		public function newId() {
			srand((double) microtime() * 1000000);
			$uid = md5(uniqid(rand(), true));
			if ($this->_check_unique) {
				$id_rec = $this->_db->get_one("
					SELECT id
					FROM `" . CFG_DBTBL_LANGUAGE . "`
					WHERE `name_value` = ?
				", $uid);
				if ($id_rec) {
					$uid = $this->newId();
				}
			}
			return $uid;
		}

		/**
		 * Получение массива текстов по языковой конструкции
		 *
		 * @param string $msgid
		 * @return array
		 */
		public function getText($msgid) {
			if (!$msgid) {
				return false;
			}

			$sql = "
				SELECT d.id
					, l.text
					, d.deflt
				FROM " . CFG_DBTBL_DICT_LANGUAGE . " AS d
					LEFT JOIN " . CFG_DBTBL_LANGUAGE . " AS l ON l.id_dict_language = d.id
				WHERE l.name_value = ?
			";

			if ($this->_use_memcache) {
				$lng = $this->_memcache->get($this->_memcacheNamespace . $msgid);
				if ($lng === FALSE) {
					// получение и заполнение
					$lng = $this->_db->get_all($sql, $msgid);
					$this->_memcache->set($this->_memcacheNamespace . $msgid, ($lng !== FALSE ? $lng : '') , MEMCACHE_COMPRESSED, 0);
				}
			} else {
				$lng = $this->_db->get_all($sql, $msgid);
			}

			if (is_array($lng)) {
				foreach ($lng as $l) {
					$lngSet[$l['id']] = $l['text'];
				}
				return $lngSet;
			} else {
				return false;
			}
		}

		/**
		 * Получение текста по языковой конструкции для указанного языка
		 *
		 * @param string $msgid
		 * @param integer $lng_id
		 * @return string|boolean
		 */
		public function getTextLng($msgid, $lng_id=0) {
			if (!$msgid) {
				return false;
			}
			if ($lng_value_arr = $this->getText($msgid))
				if ($lng_id)
					return $lng_value_arr[$lng_id];
				else
					return $lng_value_arr[$this->now_lng];
			return false;
		}

		/**
		 * Получение массива языковой конструкции
		 *
		 * @param string $msgid
		 * @return array or boolean
		 */
		public function getTextLngAll ($msgid) {
			$gettext = $this->getText($msgid);
			if ($gettext) {
				$ret = array();
				$ret["msgid"] = $msgid;
				$ret = $ret + $gettext;
				return $ret;
			} else {
				return false;
			}
		}

		/**
		 * Получение наименования языка
		 *
		 * @param integer $lng_id
		 * @return string or boolean
		 */
		public function getNameLng($lng_id) {
			if (!$lng_id) {
				return false;
			}

			$name_lng = $this->_db->get_one("
				SELECT ind_name
					FROM " . CFG_DBTBL_DICT_LANGUAGE . "
				WHERE id=?
			", $lng_id );

			if ($name_lng) {
				return $name_lng;
			} else {
				return false;
			}
		}

		/**
		 * Запись текста по языковой конструкции и указанному языку
		 *
		 * @param string $msgid
		 * @param string $text
		 * @param integer $lng_id
		 * @return boolean
		 */
		public function setText($msgid, $text, $lng_id) {
			if (!$msgid || !$lng_id) {
				return false;
			}

			// удалим лишние пробелы
			$text = trim($text);

			$sql = sql_placeholder("
				SELECT id
				FROM " . CFG_DBTBL_LANGUAGE . "
				WHERE name_value = ?
				  AND id_dict_language = ?
			", $msgid
			 , $lng_id );
			$lng_exist = $this->_db->get_one($sql);

			if ($lng_exist && $text) {
				$this->_db->update(CFG_DBTBL_LANGUAGE, array(
					'text' 				=> $text
				), array(
					'name_value' 		=> $msgid,
					'id_dict_language' 	=> $lng_id
				));
			} elseif ($text) {
				$this->_db->insert(CFG_DBTBL_LANGUAGE, array(
					'text' 				=> $text,
					'name_value' 		=> $msgid,
					'id_dict_language' 	=> $lng_id
				));
			} elseif ($lng_exist) {
				$this->_db->delete(CFG_DBTBL_LANGUAGE, array(
					'name_value' 		=> $msgid,
					'id_dict_language' 	=> $lng_id
				));
			}

			// очистим значение из memcache при следующем обращении оно будет перезаписано из базы
			$this->clearCacheValue($msgid);

			return true;
		}

		/**
		 * Запись массива текстов по языковой конструкции
		 *
		 * @param array $value
		 * @return string
		 */
		public function setTextLng ($value) {
			$msgid = $value["msgid"] ? $value["msgid"] : $this->newId();
			foreach ($this->lng_array as $val) {
				$id = $val['id'];
				$this->setText($msgid, $value[$id], $id);
			}
			return $msgid;
		}

		/**
		 * Удаление языковой конструкции
		 *
		 * @param string $msgid
		 * @return boolean
		 */
		public function delText($msgid) {
			if (!$msgid) {
				return false;
			}

			$this->_db->delete(CFG_DBTBL_LANGUAGE, array(
				'name_value' => $msgid
			));

			// очистим значение из memcache
			$this->clearCacheValue($msgid);

			return true;
		}

		/**
		 * Синоним вызова getTextLng (Получение текста по языковой конструкции для указанного языка)
		 *
		 * @param string $msgid
		 * @param int $lng_id
		 */
		public function get($msgid, $lng_id = 0) {
			return $this->getTextLng($msgid, $lng_id);
		}

		/**
		 * Синоним вызова getTextLngAll (Получение массива языковой конструкции)
		 *
		 * @param string $msgid
		 */
		public function get_all($msgid) {
			return $this->getTextLngAll($msgid);
		}

		/**
		 * Синоним вызова setTextLng (Запись массива текстов по языковой конструкции)
		 *
		 * @param array $value
		 */
		public function set($value) {
			return $this->setTextLng($value);
		}

		/**
		 * Синоним вызова delText (Удаление языковой конструкции)
		 *
		 * @param string/array $msgid
		 */
		public function del($msgid) {
			if (is_array($msgid)) {
				foreach ($msgid as $_val) {
					$this->delText($_val);
				}
			} else {
				$this->delText($msgid);
			}

			return true;
		}

		/**
		 * Очистить значение в memcache
		 *
		 * @param unknown_type $msgid
		 */
		public function clearCacheValue($msgid) {
			if ($this->_use_memcache && $msgid) {
				$this->_memcache->delete($this->_memcacheNamespace . $msgid);
			}
		}

		// =======================================================================================================
		// Функции в изоляторе
		// =======================================================================================================
		/**
		 * Текстовое поле ввода для языковой конструкции (Обратная совместимость со старыми версиями кода)
		 *
		 * @ignore
		 */
		function textField ($num, $name, $text, $params=array()) {
			_OLDCODINSULTR_LNG_textField ($num, $name, $text, $params=array(), $this->lng_array, $this->deflt_lng);
		}

		/**
		 * Текстовое область ввода для языковой конструкции (Обратная совместимость со старыми версиями кода)
		 *
		 * @ignore
		 */
		function textArea ($num, $name, $text, $params=array()) {
			_OLDCODINSULTR_LNG_textArea ($num, $name, $text, $params=array(), $this->lng_array, $this->deflt_lng);
		}

		/**
		 * WYSIWYG для языковой конструкции (Обратная совместимость со старыми версиями кода)
		 *
		 * @ignore
		 */
		function richEdit ($num, $name, $text) {
			_OLDCODINSULTR_LNG_richEdit ($num, $name, $text, $this->lng_array, $this->deflt_lng);
		}
	 }
