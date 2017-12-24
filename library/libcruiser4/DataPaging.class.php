<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @copyright Cruiser cruiser.com.ua
	 */

	/**
	 * Класс управления пэйджингом данных
	 *
	 * Настройки, передаваемые в конструктор:
	 * <pre>
	 *  string pageID идентификатор страницы для Cookie
	 * 	array countsList список значений ограничений на вывод
	 * 	int countDispPages максимальное количество отображаемых страниц для переключения
	 * 						(в одну сторону, т.е. максимально - (2 * countDispPages) линков)
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class DataPaging {

		/**
		 * @var array конфигурация
		 */
		protected $config;

		/**
		 * @var array параметры пейджера
		 */
		protected $params;

		/**
		 * @var int количество записей
		 */
		protected $total;

		/**
		 * @var string URL страницы
		 */
		protected $URL;

		/**
		 * @var array список значений ограничений на вывод
		 */
		protected $countsList = array(20, 40, 80, 100, 200, 400);

		/**
		 * @var int максимальное количество отображаемых страниц для переключения (в одну сторону, т.е. максимально - (2 * countDispPages) линков)
		 */
		protected $countDispPages = 5;

		/**
		 * Число, определяющее количество записей на странице, т.е.
		 * если оно передается, то количество записей на странице = общему количеству записей
		 *
		 * lucky number, hack, причина: возможность несовпадения значения из cookie c total
		 */
		private $_magicNUM = 5555555;

		/**
		 * Конструктор класса
		 *
		 * [настройки см. в оглавлении класса]
		 */
		function __construct($config) {
			$this->config = $config;
			$this->total = 0;
			$this->URL = "";

			$pageID = $config['pageID'] ? $config['pageID'] : 'default_pg';

			if (is_array($config['countsList'])) {
				$this->countsList = $config['countsList'];
			}

			if ($config['countDispPages']) {
				$this->countDispPages = (int) $config['countDispPages'];
			}

			// параметры по-умолчанию
			$this->params = array(
				'pageID' => substr(md5($pageID), 0, 10),
				'pageNum' => 1,
				'countRecordsOnPage' => $this->countsList[0]
			);
		}

		/**
		 * Получение параметров для пейджинга (должно идти после вызова setTotal!)
		 *
		 * @param array $attributes массив входных данных
		 */
		public function getPagingParams($attributes = array()) {

			if ($attributes['pgngcfg']) {
				$this->params = $this->parseParamStr((string) $attributes['pgngcfg']);
				// запишем состояние в Cookie
				setcookie($this->params['pageID'], implode(',', array(
					$this->params['pageNum'],
					$this->params['countRecordsOnPage']
				)), time()+60*60*24*30, "/");
			} else {
				// получаем из cookies
				if ($_COOKIE[$this->params['pageID']]) {
					$tmp = explode(',', $_COOKIE[$this->params['pageID']]);
					if (is_array($tmp) && count($tmp) == 2) {
						$this->params['pageNum'] = $tmp[0];
						$this->params['countRecordsOnPage'] = $tmp[1];
					}
				}
			}
		}

		/**
		 * Разбор строчки параметров
		 *
		 * @param string строчка параметров
		 * <pre>
		 * 		Формат строки:
		 * 		10 символов md5 от PageID	- md5
		 * 		t 							- разделитель
		 * 		номер страницы
		 * 		e 							- разделитель
		 * 		количество элементов на стр.
		 * 		r 							- разделитель
		 * </pre>
		 *
		 * @return array параметры
		 */
		private function parseParamStr($param_str) {
			$pageID = substr($param_str, 0, 10);
			$pptmp = preg_split('/[^0-9]/si', substr($param_str, 10), -1, PREG_SPLIT_NO_EMPTY);

			// проверим правильность переданных данных исходя из количества записей на странице и total значения
			$count_pages = ceil($this->total / $this->params['countRecordsOnPage']);

			if (
				$pptmp[0] > $count_pages // переданный номер страницы больше, чем есть страниц
				||
				( $pptmp[1] > $this->total && $pptmp[1] != $this->_magicNUM ) // количество записей на странице больше, чем записей всего
			) {
				// сбрасываем на дефолтные значения
				$pptmp[0] = 1;
				$pptmp[1] = $this->countsList[0];
			}

			return array(
				'pageID' => $pageID,
				'pageNum' => $pptmp[0],
				'countRecordsOnPage' => $pptmp[1]
			);
		}

		/**
		 * Создание строчки параметров
		 *
		 * @param array $param массив параметров
		 *
		 * @return string строчка параметров
		 * <pre>
		 * 		Формат строки:
		 * 		10 символов md5 от PageID	- md5
		 * 		t 							- разделитель
		 * 		номер страницы
		 * 		e 							- разделитель
		 * 		количество элементов на стр.
		 * 		r 							- разделитель
		 * </pre>
		 */
		private function createParamStr($param = array()) {
			return 	substr($param['pageID'], 0, 10) .
					't' .
					$param['pageNum'] .
					'e' .
					$param['countRecordsOnPage'] .
					'r' ;
		}

		/**
		 * Выдача LIMIT строчки для запроса
		 *
		 * @return string в формате LIMIT x, y
		 */
		public function getLimitLine() {
			$record_now = ($this->params['pageNum'] - 1) * $this->params['countRecordsOnPage'];
			return "LIMIT {$record_now}, {$this->params['countRecordsOnPage']}";
		}

		/**
		 * Выдача массива для итерации по данным
		 *
		 * @return array в формате array( 'start' => 1, 'count' => 10 )
		 */
		public function getLimits() {
			$start = (($this->params['pageNum'] - 1) * $this->params['countRecordsOnPage']);

			return array(
				'start' => $start,
				'count' => $this->total - $start
			);
		}

		/**
		 * Установка общего количества записей
		 *
		 * @param int количество
		 */
		public function setTotal($total = 0) {
			$this->total = (int) $total;
		}

		/**
		 * Установка URL-а страницы
		 *
		 * @param string URL
		 */
		public function setURL($url = "") {
			$this->URL = (string) $url;
		}

		/**
		 * Получение строчки записей - "Записи: 121 - 140 из 238"
		 *
		 * @return string
		 */
		public function getRecordCountStr() {
			if (!$this->total) {
				return "";
			}

			$max_count = (($this->params['pageNum'] - 1) * $this->params['countRecordsOnPage'] + $this->params['countRecordsOnPage']);

			$res_str = (($this->params['pageNum'] - 1) * $this->params['countRecordsOnPage'] + 1) . "&nbsp;&mdash;&nbsp;";
			$res_str .= ($this->total < $max_count) ? $this->total : $max_count;
			$res_str .= "&nbsp;" . _("из") . "&nbsp;" . $this->total;

			return "&nbsp;" . _("Записи") . ":&nbsp;" . $res_str;
		}

		/**
		 * Получение строчки пейджера - "«  ←   ... 2 3 4 5 6 7 8 9 10 11 12  →  »"
		 *
		 * @return string
		 */
		public function getPagerStr() {
			$res_str = "";

			// число страниц
			$count_pages = ceil($this->total / $this->params['countRecordsOnPage']);

			// если всего одна страница данных, то не выводим пейджер
			if ($count_pages <= 1) {
				return $res_str;
			}

			$left_overflow = ($this->params['pageNum'] > $this->countDispPages);
			$right_overflow = (($count_pages - $this->params['pageNum']) > $this->countDispPages);

			if ($this->params['pageNum'] > 1) {
				$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
					'pageID' => $this->params['pageID'],
					'pageNum' => 1,
					'countRecordsOnPage' => $this->params['countRecordsOnPage']
				));
				$res_str .= '<a href="' . $url . '" title="' . _("Переход на первую страницу") . '">&laquo;</a>&nbsp;&nbsp;';
			} else {
				$res_str .= '<span style="color: #CCC;">&laquo;&nbsp;&nbsp;</span>';
			}

			// переход на один шаг
			if ($this->params['pageNum'] > 1) {
				$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
					'pageID' => $this->params['pageID'],
					'pageNum' => $this->params['pageNum'] - 1,
					'countRecordsOnPage' => $this->params['countRecordsOnPage']
				));
				$res_str .= '<a href="' . $url . '" title="' . _("Переход на страницу назад") . '">&larr;</a>&nbsp;&nbsp;';
			} else {
				$res_str .= '<span style="color: #CCC;">&larr;&nbsp;&nbsp;</span>';
			}

			// левая часть
			if ($left_overflow) {
				$start_index = $this->params['pageNum'] - $this->countDispPages;
				$stop_index = $this->params['pageNum'];
			} else {
				$start_index = 1;
				$stop_index = $this->params['pageNum'];
			}

			if ($left_overflow && $this->params['pageNum'] != ($this->countDispPages + 1)) {
				$res_str .= '&nbsp;...&nbsp;';
			}
			for ( $i = $start_index; $i <= $stop_index; $i++ ) {
				$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
					'pageID' => $this->params['pageID'],
					'pageNum' => $i,
					'countRecordsOnPage' => $this->params['countRecordsOnPage']
				));
				if ($this->params['pageNum'] == $i) {
					$res_str .= '<b>' . $i . '</b>&nbsp;';
				} else {
					$res_str .= '<a href="' . $url . '" title="' . _("Переход на страницу") . '"><u>' . $i . '</u></a>&nbsp;';
				}
			}

			// правая часть
			if ($right_overflow) {
				$start_index = $this->params['pageNum'] + 1;
				$stop_index = $start_index + ($this->countDispPages - 1);
			} else {
				$start_index = $this->params['pageNum'] + 1;
				$stop_index = $count_pages;
			}
			for ( $i = $start_index; $i <= $stop_index; $i++ ) {
				$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
					'pageID' => $this->params['pageID'],
					'pageNum' => $i,
					'countRecordsOnPage' => $this->params['countRecordsOnPage']
				));
				if ($this->params['pageNum'] == $i) {
					$res_str .= '<b>' . $i . '</b>&nbsp;';
				} else {
					$res_str .= '<a href="' . $url . '" title="' . _("Переход на страницу") . '"><u>' . $i . '</u></a>&nbsp;';
				}
			}
			if ($right_overflow && $this->params['pageNum'] != $count_pages - 5) {
				$res_str .= '&nbsp;...&nbsp;';
			}

			// переход на один шаг
			if ($this->params['pageNum'] < $count_pages) {
				$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
					'pageID' => $this->params['pageID'],
					'pageNum' => $this->params['pageNum'] + 1,
					'countRecordsOnPage' => $this->params['countRecordsOnPage']
				));
				$res_str .= '&nbsp;<a href="' . $url . '" title="' . _("Переход на страницу вперед") . '">&rarr;</a>';
			} else {
				$res_str .= '&nbsp;<span style="color: #CCC;">&rarr;</span>';
			}

			if ($this->params['pageNum'] < $count_pages) {
				$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
					'pageID' => $this->params['pageID'],
					'pageNum' => $count_pages,
					'countRecordsOnPage' => $this->params['countRecordsOnPage']
				));
				$res_str .= '&nbsp;&nbsp;<a href="' . $url . '" title="' . _("Переход на последнюю страницу") . '">&raquo;</a>';
			} else {
				$res_str .= '&nbsp;&nbsp;<span style="color: #CCC;">&raquo;</span>';
			}

			return $res_str;
		}

		/**
		 * Получение строчки количества записей на странице - "Всего на стр.: 20 40 80 100  |  все записи"
		 *
		 * @return string
		 */
		public function getCountRecordsOnPageStr() {
			$res_str = "";

			if ($this->total < $this->countsList[0]) {
				return "";
			}

			foreach ( $this->countsList as $cnt ) {
				// выводим только те значения массива $this->countsList, что меньше $this->total
				if ($cnt < $this->total) {
					if ($cnt === (int) $this->params['countRecordsOnPage']) {
						$res_str .= "<b>{$cnt}</b>&nbsp;";
					} else {
						$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
							'pageID' => $this->params['pageID'],
							'pageNum' => 1,
							'countRecordsOnPage' => $cnt
						));
						$res_str .= "<a href=\"{$url}\" title=\"" . _("Выводить по") . "&nbsp;{$cnt}&nbsp;" . _("записей на страницу") . "\"><u>{$cnt}</u></a>&nbsp;";
					}
				}
			}

			// все записи:
			if ((int) $this->params['countRecordsOnPage'] === $this->_magicNUM) {
				$res_str .= "&nbsp;&bull;&nbsp;&nbsp;<b>" . _("все&nbsp;записи") . "</b>&nbsp;";
			} else {
				$url = $this->URL . "&pgngcfg=" . $this->createParamStr(array(
					'pageID' => $this->params['pageID'],
					'pageNum' => 1,
					'countRecordsOnPage' => $this->_magicNUM
				));
				$res_str .= "&nbsp;&bull;&nbsp;&nbsp;<a href=\"{$url}\" title=\"" . _("Внимание! Вывод может потребовать большого количества времени!") . "\"><u style=\"color: #A5A5A5 !important;\">" . _("все&nbsp;записи") . "</u></a>&nbsp;";
			}


      		if ($this->total > 20) {
      			return _("Всего&nbsp;на&nbsp;стр.") . ":&nbsp;" . $res_str;
      		}
		}
	}

