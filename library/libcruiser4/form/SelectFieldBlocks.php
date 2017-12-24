<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @version 0.55
	 * @copyright Cruiser cruiser.com.ua
	 */

	// =====================================================================================================
	// Select поля
	// =====================================================================================================

	/**
	 * Select элемент [TFSELECT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID элемента
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		array options Массив значений вида array ( 'Значение' => 'Наименование' )
	 * 		array selected Массив выделенных значений
	 * 		boolean empty Флаг присутствия "пустого" значения "-- Выберите элемент --" (по-умолчанию true)
	 * 		boolean multiple Флаг режима multiple (по-умолчанию false)
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelect extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('id', $this->getRndId());

			$this->setDefaultValue('empty', true);
			$this->setDefaultValue('multiple', false);
			$this->setDefaultValue('options', array());
			$this->setDefaultValue('selected', array());

			$id = $this->config['id'];
			?>
				<input id="<?=$id?>_hidden" type="hidden" name="<?=$this->config['name']?>" value="<?=join(',', $this->config['selected'])?>">
				<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
				<select id="<?=$id?>"
						class="textfield <?=$this->config['cls']?>"
						style="width: <?=$this->config['width']?>px"
						<?=($this->config['multiple']) ? "multiple" : ""?>
						autocomplete="off"
						<?=$this->genParamStr();?>
						>
				<?php if ($this->config['empty']): ?>
					<option value="0"><?=_("-- Выберите элемент --")?></option>
				<?php endif; ?>
				<?php foreach ($this->config['options'] as $key => $value):
					$sel = in_array($key, $this->config['selected']) ? " selected " : "";
				?>
					<option value="<?=$key?>"<?=$sel?>><?=$this->modificationValueString($value)?></option>
				<?php endforeach; ?>
				</select><?php if(!$this->isManualBrSet()): ?><br /><?php endif; ?>
				<script type="text/javascript">
					$(function() {
						var id = '<?php echo $id; ?>';
						FormsNS.VirabTFSelect.set( id );
					});
				</script>
			<?php
		}

		/**
		 * Установка отступов
		 *
		 * @param int $level
		 * @return string строчка типа "&nbsp;&nbsp;&nbsp;" (для $level = 1)
		 */
		protected function setIndent($level = 0) {
			$resIdnt = "";

			if ((int) $level) {
				for ($i = 0; $i < (int) $level; $i++) {
					$resIdnt .= "&nbsp;&nbsp;&nbsp;";
				}
			}

			return $resIdnt;
		}
	}

	/**
	 * Select элемент с поддержкой многоязыковости [TFSELECTLNG]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		array options Массив значений вида array ( 'Значение' => 'Хэш lng' )
	 * 		array selected Массив выделенных значений
	 * 		boolean empty Флаг присутствия "пустого" значения "-- Выберите элемент --" (по-умолчанию true)
	 * 		boolean multiple Флаг режима multiple (по-умолчанию false)
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectLng extends VirabTFFieldLng {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('empty', true);
			$this->setDefaultValue('multiple', false);
			$this->setDefaultValue('options', array());
			$this->setDefaultValue('selected', array());

			// Получение языковых значений
			foreach ($this->config['options'] as $lkey => &$lvalue) {
				$lvalue = $this->lng->get_all($lvalue);
			}
			?>
				<label><?=$this->config['label']?><br /></label>
				<input id="" type="hidden" name="<?=$this->config['name']?>" value="<?=join(',', $this->config['selected'])?>">
				<?php foreach ($this->lng->lng_array as $lang):
						$class = ($lang['id'] != $this->lng->now_lng) ? "hidden_field" : "";
				?>
					<select name="<?=$this->config['name']?>_field_<?=$lang['id']?>"
							class="flng<?=$lang['ind_name']?> textfield <?=$class?> <?=$this->config['cls']?>"
							style="width: <?=$this->config['width']?>px"
							<?=($this->config['multiple']) ? "multiple" : ""?>
							autocomplete="off"
							<?=$this->genParamStr();?>
							>
					<?php if ($this->config['empty']): ?>
						<option value="0"><?=_("-- Выберите элемент --")?></option>
					<?php endif; ?>
					<?php foreach ($this->config['options'] as $key => $value):
						$sel = in_array($key, $this->config['selected']) ? " selected " : "";
					?>
						<option value="<?=$key?>"<?=$sel?>><?=$this->modificationValueString($value[$lang['id']])?></option>
					<?php endforeach; ?>
					</select>
					<br class="brflng<?=$lang['ind_name']?> <?=$class?>" />
				<?php endforeach;
				?>
					<script type="text/javascript">
						$(function() {
							FormsNS.VirabTFSelectLng.set(
								'<?=$this->config['name']?>'
							);
						});
					</script>
				<?php
		}
	}

	/**
	 * Класс-фабрика элементов c поддержкой ввода dataset-а из БД и фильтрацией
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		смотри VirabTFSelect +
	 * 		array dataSet Датасет с данными
	 * 		array mapping Маппинг данных (по-умолчанию array('value' => 'id', 'name' => 'name'))
	 * 		для фильтрации в зависимости от текущего значения селекта:
	 * 		array filter в формате:
	 * 			$filter[0] = 'one'; // по-умолчанию для всех остальных
	 * 			$filter[TMPL_TYPE_PAGE] = 'one';
	 * 			$filter[TMPL_TYPE_NOTYPE] = 'all';
	 * 			$filter[TMPL_TYPE_CONTAINER] = array( TMPL_TYPE_PAGE, TMPL_TYPE_CONTAINER );
	 * 			$filter[TMPL_TYPE_EXECUTOR] = array( TMPL_TYPE_EXECUTOR, TMPL_TYPE_NOTYPE, TMPL_TYPE_CONTAINER );
	 * 			$filter[TMPL_TYPE_CONTAINER_SELECT] = 'one';
	 * 			$filter[TMPL_TYPE_FOLDER] = 'one';
	 * 		где
	 * 			'one' - единственное значение
	 * 			'all' - без фильтрафии
	 * </pre>
	 *
	 * @package DspHelper
	 *
	 */
	class VirabTFSelectDataSetFactory extends VirabTFField {

		/**
		 * Конструктор класса
		 *
		 * @param array $config конфигурация блока
		 * @return void
		 */
		public function __construct($config) {
			$this->config = $config;
		}

		/**
		 * Отображение блока
		 */
		public function show( $type = "" ) {
			// подготовка данных
			$this->setDefaultValue('dataSet', array());
			$this->setDefaultValue('mapping', array('value' => 'id', 'name' => 'name'));

			if (is_array($this->config['dataSet'])) {
				foreach ($this->config['dataSet'] as $data) {
					$this->config['options'][$data[$this->config['mapping']['value']]] = $data[$this->config['mapping']['name']];
				}
			}

			if ($type === "") {
				throw new Exception("Не передан тип поля в фабрику VirabTFSelectDataSetFactory");
			}

			if (in_array($type, $this->config['__factory_filterin__'])) {
				// механизм фильтрации
				$curr_value = $this->config['selected'][0];
				$filter = $this->config['filter'];

				if ($curr_value && is_array($filter)) {
					// фильтрация работает только при известном текущем значении поля
					if (array_key_exists($curr_value, $filter)) {
						$filter_val = $filter[$curr_value];
					} else {
						// значение фильтра по-умолчанию
						$filter_val = $filter[0];
					}

					if (is_string($filter_val)) {
						switch ($filter_val) {
							case 'one':
								$vone = $this->config['options'][$curr_value];
								$this->config['options'] = array();
								$this->config['options'][(int) $curr_value] = $vone;
								break;

							case 'all':
								break;

							default:
								break;
						}
					} elseif (is_array($filter_val)) {
						$this->config['options'] = $this->filterDataset($this->config['options'], $filter_val);
					}
				}
			}

			$classn = $this->config['__factory_calls__'][$type];
			if (!class_exists($classn)) {
				throw new Exception("Передан неверный тип поля в фабрику VirabTFSelectDataSetFactory");
			}

			try {
				$class = new $classn($this->config);
				$class->show();
			} catch (Exception $e) {
				throw new Exception("Ошибка в фабрике VirabTFSelectDataSetFactory: " . $e->getMessage());
			}
		}

		/**
		 * Фильтрация датасета
		 * @param array $dataSet
		 * @param array $filter
		 *
		 * @return array
		 */
		protected function filterDataset($dataSet, $filter) {
			$res_arr = array();
			if (is_array($dataSet)) {
				foreach ($dataSet as $k => $val) {
					if (in_array((int) $k, $filter)) {
						$res_arr[$k] = $val;
					}
				}
			}

			return $res_arr;
		}

	}

	/*
		class VirabTFSelectDataSet // реализован в VirabTFSelectDataSetFactory
		class VirabTFSelectDataSetLng // реализован в VirabTFSelectDataSetFactory
	*/

	/**
	 * Select-ор даты и времени [TFSELECTDATETIME]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID элемента
	 * 		string name Наименование
	 * 		string label Название поля
	 *		string value Значение даты (формат YYYY-MM-DD HH:MM:SS)
	 * 		boolean empty Использование значения для неустановленного поля
	 * 		int d_year Значение диапазона годов "вниз" (по-умолчанию 100 лет)
	 * 		int b_year Значение диапазона годов "вверх" (по-умолчанию 0 - год "вверх" не увеличивается)
	 *		boolean showSecond (по-умолчанию true)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectDateTime extends VirabTFField {

		protected $year = false;
		protected $month = false;
		protected $day = false;
		protected $hour = false;
		protected $minute = false;
		protected $second = false;

		protected $today = array();

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->parseValue();

			$this->setDefaultValue('id', $this->getRndId());
			$this->setDefaultValue('d_year', 100);
			$this->setDefaultValue('b_year', 0);
			$this->setDefaultValue('showSecond', true);

			$id = $this->config['id'];
			$this->today = getdate();

			?>
				<label><?=$this->config['label']?><br /></label>
				<input id="<?=$id?>" type="hidden" name="<?=$this->config['name']?>" value="<?=$this->getDefValue()?>" autocomplete="off" />
			<?php
			$this->datePart();
			$this->timePart();
			?>
				<br />
			<?php
			$this->addJSFunctions();
		}

		/**
		 * Парсинг значения
		 *
		 * @return void
		 */
		protected function parseValue() {
			if (preg_match('/([\d]{4})-([\d]{2})-([\d]{2}) ?(([\d]{2}):([\d]{2}))?(:([\d]{2}))?/im', $this->config['value'], $parts)) {
				$this->year = $parts[1];
				$this->month = $parts[2];
				$this->day = $parts[3];

				$this->hour = $parts[5] ? $parts[5] : false;
				$this->minute = $parts[6] ? $parts[6] : false;
				$this->second = $parts[8] ? $parts[8] : false;
			}
		}

		/**
		 * Дефолтное значение даты-времени
		 *
		 * @return string
		 */
		protected function getDefValue() {
			return (($this->year && $this->month && $this->day) ?
					$this->year . "-" . $this->month . "-" . $this->day
						:
					"1900-01-01"
				) . " " . (
					($this->hour ? $this->hour : "00")
					.
						":"
					.
					($this->minute ? $this->minute : "00")
					.
						":"
					.
					($this->second ? $this->second : "00")
				);
		}

		/**
		 * Часть даты
		 *
		 * @return void
		 */
		protected function datePart() {
			global $__MONTH_NAME;

			// день
			$resArray = array(); $selected = array();
			if ($this->day) $selected[] = $this->day;

			if ($this->config['empty']) {
				$resArray[0] = "--";
			}

			for ($i = 1; $i <= 31; $i++) {
				$resArray[$i] = sprintf("%02s", $i);
			}
			html_select($resArray, $selected, $this->config['name'] . "_day", false);

			// месяц
			$resArray = array(); $selected = array();
			if ($this->month) $selected[] = $this->month;

			if ($this->config['empty']) {
				$resArray[0] = "----";
			}

			for ($i = 1; $i <= 12; $i++) {
				$resArray[$i] = $__MONTH_NAME[2][$i];
			}
			html_select($resArray, $selected, $this->config['name'] . "_month", false);

			// год
			$resArray = array(); $selected = array();
			if ($this->year) $selected[] = $this->year;

			if ($this->config['empty']) {
				$resArray[0] = "--";
			}

			for($i = ($this->today['year'] + $this->config['b_year']); $i >= ($this->today['year'] - $this->config['d_year']); $i--){
				$resArray[$i] = $i;
			}
			html_select($resArray, $selected, $this->config['name'] . "_year", false);

			echo "&nbsp;&nbsp;&nbsp;";
		}

		/**
		 * Часть времени
		 *
		 * @return void
		 */
		protected function timePart() {

			// час
			$resArray = array(); $selected = array();
			if ($this->hour) $selected[] = $this->hour;

			for ($i = 0; $i <= 23; $i++) {
				$resArray[$i] = sprintf("%02s", $i);
			}
			html_select($resArray, $selected, $this->config['name'] . "_hour", false);

			echo " : ";

			// минуты
			$resArray = array(); $selected = array();
			if ($this->minute) $selected[] = $this->minute;
			for ($i = 0; $i <= 59; $i++) {
				$resArray[$i] = sprintf("%02s", $i);
			}
			html_select($resArray, $selected, $this->config['name'] . "_minute", false);

			if ($this->config['showSecond']) {
				echo " : ";
				// секунды
				$resArray = array(); $selected = array();
				if ($this->second) $selected[] = $this->second;
				for ($i = 0; $i <= 59; $i++) {
					$resArray[$i] = sprintf("%02s", $i);
				}
				html_select($resArray, $selected, $this->config['name'] . "_second", false);
			} else {
				?>
					<input type="hidden" name="<?=$this->config['name'] . "_second"?>" value="00" autocomplete="off" />
				<?php
			}
		}

		/**
		 * javascript функционал
		 *
		 * @return void
		 */
		protected function addJSFunctions() {
			?>
				<script type="text/javascript">
					$(function() {
						var name = "<?php echo $this->config['name']; ?>",
							dayb = $('select[name="' + name + '_day"]'),
							monthb = $('select[name="' + name + '_month"]'),
							yearb = $('select[name="' + name + '_year"]'),
							hourb = $('select[name="' + name + '_hour"]'),
							minuteb = $('select[name="' + name + '_minute"]'),
							secondb = $('select[name="' + name + '_second"]');

						dayb
							.attr('dname', name)
							.bind('change', FormsNS.setDateTimeToInput);

						monthb
							.attr('dname', name)
							.bind('change', FormsNS.setDateTimeToInput);

						yearb
							.attr('dname', name)
							.bind('change', FormsNS.setDateTimeToInput);

						hourb
							.attr('dname', name)
							.bind('change', FormsNS.setDateTimeToInput);

						minuteb
							.attr('dname', name)
							.bind('change', FormsNS.setDateTimeToInput);

						secondb
							.attr('dname', name)
							.bind('change', FormsNS.setDateTimeToInput);
					})
				</script>
			<?php
		}
	}

	/**
	 * Select-ор даты [TFSELECTDATE]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 *		string value Значение даты (формат YYYY-MM-DD)
	 * 		boolean empty Использование значения для неустановленного поля
	 * 		int d_year Значение диапазона годов "вниз" (по-умолчанию 100 лет)
	 * 		int b_year Значение диапазона годов "вверх" (по-умолчанию 0 - год "вверх" не увеличивается)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectDate extends VirabTFSelectDateTime {

		/**
		 * Дефолтное значение даты-времени
		 *
		 * @return string
		 */
		protected function getDefValue() {
			return (
				($this->year && $this->month && $this->day) ?
					$this->year . "-" . $this->month . "-" . $this->day
						:
					"1900-01-01"
			);
		}

		/**
		 * Часть времени
		 *
		 * @return void
		 */
		protected function timePart() {}

		/**
		 * javascript функционал
		 *
		 * @return void
		 */
		protected function addJSFunctions() {
			?>
				<script type="text/javascript">
					$(function() {
						var name = "<?php echo $this->config['name']; ?>",
							dayb = $('select[name="' + name + '_day"]'),
							monthb = $('select[name="' + name + '_month"]'),
							yearb = $('select[name="' + name + '_year"]');

						dayb
							.attr('dname', name)
							.bind('change', FormsNS.setDateToInput);

						monthb
							.attr('dname', name)
							.bind('change', FormsNS.setDateToInput);

						yearb
							.attr('dname', name)
							.bind('change', FormsNS.setDateToInput);

					})
				</script>
			<?php
		}
	}


	/**
	 * Select-ор времени [TFSELECTTIME]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 *		string value Значение даты (формат HH:MM:SS)
	 * 		boolean showSecond (по-умолчанию true)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectTime extends VirabTFSelectDateTime {

		/**
		 * Парсинг значения
		 *
		 * @return void
		 */
		protected function parseValue() {
			if (preg_match('/([\d]{2}):([\d]{2})(:([\d]{2}))?/im', $this->config['value'], $parts)) {
				$this->hour = $parts[1];
				$this->minute = $parts[2];
				$this->second = $parts[4] ? $parts[4] : false;
			}
		}

		/**
		 * Дефолтное значение даты-времени
		 *
		 * @return string
		 */
		protected function getDefValue() {
			return (
				($this->hour ? $this->hour : "00")
				.
					":"
				.
				($this->minute ? $this->minute : "00")
				.
					":"
				.
				($this->second ? $this->second : "00")
			);
		}

		/**
		 * Часть даты
		 *
		 * @return void
		 */
		protected function datePart() {}

		/**
		 * javascript функционал
		 *
		 * @return void
		 */
		protected function addJSFunctions() {
			?>
				<script type="text/javascript">
					$(function() {
						var name = "<?php echo $this->config['name']; ?>",
							hourb = $('select[name="' + name + '_hour"]'),
							minuteb = $('select[name="' + name + '_minute"]'),
							secondb = $('select[name="' + name + '_second"]');

						hourb
							.attr('dname', name)
							.bind('change', FormsNS.setTimeToInput);

						minuteb
							.attr('dname', name)
							.bind('change', FormsNS.setTimeToInput);

						secondb
							.attr('dname', name)
							.bind('change', FormsNS.setTimeToInput);
					})
				</script>
			<?php
		}
	}