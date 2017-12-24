<?php

   /**
    * @package DataHelper
    * @author Редькин Сергей, .ter [rou.terra@gmail.com]
    * @copyright Cruiser cruiser.com.ua
    */

	/**
	 * Класс управления входными данными
	 *
	 * @package DataHelper
	 */
	class DataHelper {
		private static $_instance;

		/**
		 * Получение одного и того же экземпляра класса
		 *
		 * @return DataHelper instance
		 */
		static public function getInstance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Получение строчки для сортировки данных (ORDER BY ...)
		 *
		 * @param string tableID ID таблицы
		 * @param array config конфигурация сборки
		 * <pre>
		 *  Пример массива конфигурации:
		 *   'id'  => 'l.id',
		 *   'var' => 'l.name_value',
		 *   'lng' => 'l.id_dict_language'
		 * </pre>
		 * @param string default_line строчка сортировки по-умолчанию
		 *
		 * @return string в формате ORDER BY field ASC
		 */
		public function getOrderLine($tableID, $conf = array(), $default_line = "") {
			$result_order_line = $default_line;

			$sort_field = $_SESSION[$tableID]['sort_field'];
			$sort_dir = $_SESSION[$tableID]['sort_dir'] == 'ASC' ? 'ASC' : 'DESC';

			if ($conf[$sort_field]) {
				$result_order_line = "ORDER BY {$conf[$sort_field]} {$sort_dir}";
			}

			return $result_order_line;
		}

		/**
		 * Получение строчки для фильтрации данных (часть WHERE строчки)
		 *
		 * @param string $tableID
		 * @param array $attributes
		 * @param array $conf
		 * @param string $logic
		 * @param string $default_line
		 * @return string
		 *
		 * <pre>
		 *  Пример массива конфигурации:
		 *   'var_name' => "l.name_value LIKE '*%'",
		 *   'text' => "l.text LIKE '%*%'",
		 *   'test' => "t.id_test IN ( * )",
		 *   'test' => "t.id = *",
		 *   'value_range' => "value %between%" (принимает на вход структуры типа "value - value")
		 *   'date_range' => "date %between_date%" (принимает на вход структуры типа "d.m.Y - d.m.Y")
		 * </pre>
		 */
		public function getWhereLine($tableID, &$attributes, $conf = array(), $logic = "AND", $default_line = "TRUE") {
			$logic = ($logic === "AND") ? "AND" : "OR";

			// массив названий полей фильтрации
			$filter_fields = $_SESSION[$tableID]['filter_fields'];
			if ($attributes['filter'] === null) {
				$is_filter = (int) $_SESSION[$tableID]['filter'];
				$from_session = true;
			} else {
				$is_filter = (int) $attributes['filter'];
				$from_session = false;
			}

			$attributes['filter'] = $is_filter;

			// Сохранение в сессии
			if ($is_filter && !$from_session) {
				// сохраним в сессию данные
				$filter_data = array();
				if (is_array($filter_fields)) {
					foreach ( $filter_fields as $filter_attr_name ) {
						$filter_data[$filter_attr_name] = $attributes[$filter_attr_name];
					}
				}

				$_SESSION[$tableID]['filter_data'] = $filter_data;
				$_SESSION[$tableID]['filter'] = 1;
			} elseif ($is_filter && $from_session) {
				// получение данных фильтров из сессии
				if (is_array($filter_fields)) {
					foreach ( $filter_fields as $filter_attr_name ) {
						$attributes[$filter_attr_name] = $_SESSION[$tableID]['filter_data'][$filter_attr_name];
					}
				}

				$_SESSION[$tableID]['filter'] = 1;
			} else {
				// фильтр неактивен
				$_SESSION[$tableID]['filter'] = 0;
				$_SESSION[$tableID]['filter_data'] = array();

				if (is_array($filter_fields)) {
					foreach ( $filter_fields as $filter_attr_name ) {
						$attributes[$filter_attr_name] = '';
					}
				}
			}

			$where_line = " " . $default_line;
			if ($is_filter) {
				// пройдемся по всем полям
				if (is_array($filter_fields)) {
					foreach ( $filter_fields as $filter_attr_name ) {
						if ($conf[$filter_attr_name] && $attributes[$filter_attr_name]) {
							$fval = $attributes[$filter_attr_name];
							$fstr = $conf[$filter_attr_name];

							if (strpos($fstr, '*') !== false) {
								$wstr = str_replace('*', $fval, $fstr);
							} else if (strpos($fstr, '%between%') !== false) {
								if (is_array($rval = $this->parseRangeValue($fval))) {
									$wstr = str_replace('%between%', _psql("BETWEEN ? AND ?", $rval['start'], $rval['end']), $fstr);
								}
							} else if (strpos($fstr, '%between_date%') !== false) {
								if (is_array($rval = $this->parseRangeValue($fval))) {
									$wstr = str_replace(
										'%between_date%',
										"
										BETWEEN
											str_to_date('{$rval['start']} 00:00:00', '%d.%m.%Y %H:%i:%s')
												AND
											str_to_date('{$rval['end']} 23:59:59', '%d.%m.%Y %H:%i:%s')
										",
										$fstr);
								}
							}

							if (!empty($wstr)) {
								$where_line .= " {$logic} {$wstr} ";
							}
						}
					}
				}
			}

			return $where_line;
		}

		/**
		 * Разбор диапазонного значения
		 *
		 * @param  $rangeValue
		 * @return array|bool
		 */
		protected function parseRangeValue($rangeValue) {
			if (preg_match('/^(.*) - (.*)$/im', $rangeValue, $regs)) {
				return array(
					'start' => $regs[1],
					'end' => $regs[2]
				);
			}

			return false;
		}

	}

