<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @version 0.55
	 * @copyright Cruiser cruiser.com.ua
	 */

	include_once LIB_PATH . '/form/TextFieldBlocks.php';
	include_once LIB_PATH . '/form/SelectFieldBlocks.php';
	include_once LIB_PATH . '/form/MiscellaneousFieldBlocks.php';
	include_once LIB_PATH . '/form/ProjectFieldBlocks.php';

	// Зарегистрированные типы блоков формы TF*

	// =====================================================================================================
	// Текстовые поля
	// =====================================================================================================

	/**
	 * Текстовое поле без возможности ввода
	 */
	define('TFTEXT', 'VirabTFText');

	/**
	 * Текстовое поле ввода
	 */
	define('TFTEXTFIELD', 'VirabTFTextField');

	/**
	 * Текстовое поле с поддержкой многоязыкового ввода
	 */
	define('TFTEXTFIELDLNG', 'VirabTFTextFieldLng');

	/**
	 * Textarea
	 */
	define('TFTEXTAREA', 'VirabTFTextarea');

	/**
	 * Textarea c поддержкой языков
	 */
	define('TFTEXTAREALNG', 'VirabTFTextareaLng');

	/**
	 * Textarea c редактором (с подсветкой)
	 */
	define('TFTEXTAREAEXT', 'VirabTFTextareaExt');

	/**
	 * Textarea c поддержкой языков и редактором (с подсветкой)
	 */
	define('TFTEXTAREALNGEXT', 'VirabTFTextareaLngExt');

	/**
	 * WYSIWYG редактор
	 */
	define('TFWYSIWYG', 'VirabTFWYSIWYG');

	/**
	 * WYSIWYG редактор с поддержкой языков
	 */
	define('TFWYSIWYGLNG', 'VirabTFWYSIWYGLNG');

	/**
	 * Поле пароля с проверкой
	 */
	define('TFPASSWORDFIELD', 'VirabTFPasswordField');

	/**
	 * Checkbox
	 */
	define('TFCHECKBOX', 'VirabTFCheckbox');

	/**
	 * Group checkbox
	 */
	define('TFCHECKBOXGROUP', 'VirabTFCheckboxGroup');

	/**
	 * Checkbox элементы c поддержкой ввода dataset-а из БД (реализовано в фабрике VirabTFSelectDataSetFactory)
	 */
	define('TFCHECKBOXGROUPDATASET', 'VirabTFCheckboxGroupDataSet');

	/**
	 * Autosuggest field (поле с поиском)
	 */
	define('TFAUTOSUGGESTFIELD', 'VirabTFAutosuggestField');

	/**
	 * Текстовое поле с функцией проверки дублирования
	 */
	define('TFTEXTFIELDWITHCHECKPRESENT', 'VirabTFTextFieldWithCheckPresent');

	/**
	 * Datepicker
	 */
	define('TFDATEPICKER', 'VirabTFDatePicker');

	/**
	 * Datepicker диапазона дат
	 */
	define('TFDATEPICKERRANGE', 'VirabTFDatePickerRange');

	/**
	 * Картинки c lightbox просмотром
	 */
	define('TFMUIMAGES', 'VirabTFMUImages');

	// =====================================================================================================
	// Select поля
	// =====================================================================================================

	/**
	 * Select элемент
	 */
	define('TFSELECT', 'VirabTFSelect');

	/**
	 * Select элемент с поддержкой многоязыковости
	 */
	define('TFSELECTLNG', 'VirabTFSelectLng');

	/**
	 * Select элемент c поддержкой ввода dataset-а из БД
	 */
	define('TFSELECTDATASET', 'VirabTFSelectDataSet');

	/**
	 * Select элемент c поддержкой ввода dataset-а из БД (+ многоязыковость)
	 */
	define('TFSELECTDATASETLNG', 'VirabTFSelectDataSetLng');

	/**
	 * Select элемент c поддержкой ввода dataset-а из БД
	 */
	define('TFSELECTDATASETWFILTER', 'VirabTFSelectDataSetWFilter');

	/**
	 * Select элемент c поддержкой ввода dataset-а из БД (+ многоязыковость) + фильтрация
	 */
	define('TFSELECTDATASETWFILTERLNG', 'VirabTFSelectDataSetWFilterLng');

	$__SELECTDATASETFACTORY = array(
		'els' => array(
			/* классы фабрики */
			TFSELECTDATASET,
			TFSELECTDATASETLNG,
			TFSELECTDATASETWFILTER,
			TFSELECTDATASETWFILTERLNG,
			TFCHECKBOXGROUPDATASET
		),
		/* фабрика */
		'cls' => 'VirabTFSelectDataSetFactory',
		'calls' => array( /* конфигурация вызовов */ ),
		'filterin' => array(
			/* классы с механизмом фильтрации */
			TFSELECTDATASETWFILTER,
			TFSELECTDATASETWFILTERLNG
		)
	);
	$__SELECTDATASETFACTORY['calls'][TFSELECTDATASET] = 'VirabTFSelect';
	$__SELECTDATASETFACTORY['calls'][TFSELECTDATASETLNG] = 'VirabTFSelectLng';
	$__SELECTDATASETFACTORY['calls'][TFSELECTDATASETWFILTER] = 'VirabTFSelect';
	$__SELECTDATASETFACTORY['calls'][TFSELECTDATASETWFILTERLNG] = 'VirabTFSelectLng';
	$__SELECTDATASETFACTORY['calls'][TFCHECKBOXGROUPDATASET] = 'VirabTFCheckboxGroup';

	/**
	 * Select-ор даты
	 */
	define('TFSELECTDATE', 'VirabTFSelectDate');

	/**
	 * Select-ор времени
	 */
	define('TFSELECTTIME', 'VirabTFSelectTime');

	/**
	 * Select-ор даты и времени
	 */
	define('TFSELECTDATETIME', 'VirabTFSelectDateTime');

	// =====================================================================================================
	// Файловые поля
	// =====================================================================================================

	/**
	 * Поле ввода файла
	 */
	define('TFFILEFIELD', 'VirabTFFileField');

	// =====================================================================================================
	// Вспомогательные поля
	// =====================================================================================================

	/**
	 * Скрытое поле
	 */
	define('TFHIDDENFIELD', 'VirabTFHiddenField');

	// =====================================================================================================
	// Кнопки
	// =====================================================================================================

	/**
	 * Кнопка формы
	 */
	define('TFBUTTON', 'VirabTFButton');

	// =====================================================================================================
	// Разное
	// =====================================================================================================

	/**
	 * Картинки c lightbox просмотром
	 */
	define('TFIMAGES', 'VirabTFImages');

	/**
	 * Поле с простым текстом
	 */
	define('TFSIMPLETEXT', 'VirabTFSimpleText');

	/**
	 * Поле выбора флага
	 */
	define('TFSELECTFLAG', 'VirabTFSelectFlag');

	/**
	 * Поле выбора узла сайта
	 */
	define('TFSELECTSITENODE', 'VirabTFSelectSiteNode');

	/**
	 * Поле выбора шаблона отображения (тип - Страница)
	 */
	define('TFSELECTTEMPLATEPAGE', 'VirabTFSelectTemplatePage');

	/**
	 * Поле выбора шаблонов с меткой селективного
	 */
	define('TFSELECTTEMPLATESELECTIVE', 'VirabTFSelectTemplateSelective');

	/**
	 * Поле смены родителя в дереве
	 */
	define('TFSELECTTREEPARENT', 'VirabTFSelectTreeParent');

	/**
	 * Поле смены родителя в дереве (+ многоязыковость)
	 */
	define('TFSELECTTREEPARENTLNG', 'VirabTFSelectTreeParentLng');

	/**
	 * Поле интерактивного выбора элементов из селекта
	 */
	define('TFINTERACTIVESELECTITEM', 'VirabTFInteractiveSelectItem');

	/**
	 * Поле интерактивного выбора элементов (c VirabTFAutosuggestField)
	 */
	define('TFINTERACTIVEAUTOSUGGESTSELECTITEM', 'VirabTFInteractiveAutosuggestSelectItem');

	// =====================================================================================================
	// =====================================================================================================
	// =====================================================================================================

	/**
	 * Класс-фабрика блоков формы
	 *
	 * @package DspHelper
	 */
	class VirabFieldBlock {

		/**
		 * Конфигурация фабрики селектов из датасета
		 * @var array
		 */
		protected $selectDatasetFactroryConfig;

		/**
		 * Конструктор
		 */
		public function __construct() {
			global $__SELECTDATASETFACTORY;

			$this->selectDatasetFactroryConfig = $__SELECTDATASETFACTORY;
		}

		/**
		 * Отображение блока
		 *
		 * @param string $type тип блока
		 * @param array $config конфигурация блока
		 * @return void
		 */
		public function show($type, $config) {

			// проверяем присутствие в фабрике selectDatasetFactory
			if (in_array($type, $this->selectDatasetFactroryConfig['els'])) {
				try {
					$class = new $this->selectDatasetFactroryConfig['cls'](
						array_merge(
							$config,
							array(
								'__factory_calls__' => $this->selectDatasetFactroryConfig['calls'],
								'__factory_filterin__' => $this->selectDatasetFactroryConfig['filterin']
							)
						)
					);
					$class->show($type);
				} catch (Exception $e) {
					print '<strong style="color: red;">' . _("Ошибка вывода элемента формы") . ':' . $e->getMessage() . '</strong><br /><br />';
				}

				return;
			}

			if (class_exists($type)) {
				try {
					$class = new $type($config);
					$class->show();
				} catch (Exception $e) {
					print "<strong style=\"color: red;\">" . _("Ошибка вывода элемента формы") . ": {$e->getMessage()}</strong><br /><br />";
				}
			} else {
				print "<strong style=\"color: red;\">" . _("Неверный тип блока формы") . ": {$type}</strong><br /><br />";
			}
		}
	}

	/**
	 * Основной класс (общие методы)
	 *
	 * <pre>
	 * Общие значения массива конфигурации:
	 * 		array params массив дополнительных параметров поля. Туда можно, например, передать и реакцию на onClick.
	 * 		string cls класс оформления поля
	 * 		boolean required флаг обязательного поля
	 * 		boolean without_output_modification флаг отмены выходного преобразования значения
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFField {

		/**
		 * Конфигурация блока
		 *
		 * @var array
		 */
		protected $config;

		/**
		 * Конструктор класса
		 *
		 * @param array $config конфигурация блока
		 * @return void
		 */
		public function __construct($config) {

			$this->config = $config;
			// Параметры по-умолчанию для всех полей формы
			$this->setDefaultValue('params', array());
			$this->setDefaultValue('width', 500);
			$this->setDefaultValue('cls', '');	// оформление
			$this->setDefaultValue('required', false);	// флаг обязательного поля
			$this->setDefaultValue('without_output_modification', false); // флаг отмены выходного преобразования значения
			$this->setDefaultValue('manual_set_br', false); // флаг ручной установки <br> тэга после контента поля

			$this->config['label'] = ((isset($this->config['label'])) ? $this->config['label'] : '') . $this->genMarkRequired();
			$this->config['label'] = ($this->config['label'] !== '') ? $this->config['label'] . ":" : $this->config['label'];
		}

		/**
		 * Получение случайного ID
		 *
		 * @param string $NS - namespace
		 * @param integer $count - число знаков
		 * @return string
		 */
		public function getRndId($NS = "tV_", $count = 10) {
			$rid = substr(md5(uniqid(rand())), 1, $count);
			return $NS . $rid;
		}

		/**
		 * Установка значения по-умолчанию
		 *
		 * @param string $name_value
		 * @param $default
		 * @return viod
		 */
		public function setDefaultValue($name_value, $default) {
			$this->config[$name_value] = (isset($name_value) && isset($this->config[$name_value])) ? $this->config[$name_value] : $default;
//			$this->config[$name_value] = (
//				!$this->config[$name_value]
//					&&
//				$this->config[$name_value] !== false
//					&&
//				$this->config[$name_value] !== 0
//			) ? $default : $this->config[$name_value];
		}

		/**
		 * Генерация строчки параметров поля из массива params
		 *
		 * @return string
		 */
		public function genParamStr() {
			$res = "";
			foreach ($this->config['params'] as $pkey => $pvalue):
				$res .= " {$pkey}='{$pvalue}' ";
			endforeach;
			return $res;
		}

		/**
		 * Генерация метки об обязательности заполнения поля
		 *
		 * @return string
		 */
		public function genMarkRequired() {
			return $this->config['required'] ? '<span class="required_field" title="' . _("Поле обязательно для ввода") . '"> *</span>' : '';
		}

		/**
		 * Проверка флага ручной установки br тэга
		 *
		 * @return string
		 */
		public function isManualBrSet() {
			return (boolean) $this->config['manual_set_br'];
		}

		/**
		 * Преобразования с итоговой строчкой (например для препятствия XSS)
		 *
		 * @param string $str
		 * @return string
		 */
		public function modificationValueString($str) {
			return !$this->config['without_output_modification'] ? htmlspecialchars($str, ENT_QUOTES) : $str;
		}

	}

	/**
	 * Основной класс для многоязыковых элементов (общие методы)
	 *
	 * @package DspHelper
	 */
	class VirabTFFieldLng extends VirabTFField {

		/**
		 * Экземпляр языкового класса
		 *
		 * @var Language
		 */
		protected $lng;

		/**
		 * Конструктор класса
		 *
		 * @param array $config конфигурация блока
		 * @return void
		 */
		public function __construct($config) {
			global $lng;

			parent::__construct($config);
			$this->lng = $lng;
		}
	}

	// =====================================================================================================
	// Файловые поля
	// =====================================================================================================

	/**
	 * Поле ввода файла [TFFILEFIELD]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *		string name Наименование
	 * 		string label Название поля
	 * 		int width Значение ширины поля, по-умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFFileField extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$id = $this->getRndId();
			?>
				<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
				<input 		id="<?=$id?>"
							type="file"
							name="<?=$this->config['name']?>"
							class="textfield <?=$this->config['cls']?>"
							size="<?=($this->config['width']/6.25)?>"
							<?=$this->genParamStr();?> autocomplete="off" />
				<?php if(!$this->isManualBrSet()): ?>
					<br />
				<?php endif; ?>
			<?php
		}
	}

	// =====================================================================================================
	// Вспомогательные поля
	// =====================================================================================================

	/**
	 * Скрытое поле [TFHIDDENFIELD]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string value Значение
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFHiddenField extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			?>
				<input 	type="hidden"
						name="<?=$this->config['name']?>"
						value="<?=$this->modificationValueString($this->config['value'])?>"
						<?=$this->genParamStr();?> />
			<?php
		}
	}

	// =====================================================================================================
	// Кнопки
	// =====================================================================================================

	/**
	 * Кнопка формы [TFBUTTON]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string type Тип кнопки (например submit)
	 * 		string name Наименование
	 * 		string value Значение
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFButton extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			?>
				<input 	type="<?=$this->config['type']?>"
						name="<?=isset($this->config['name']) ? $this->config['name'] : ''?>"
						value="<?=$this->config['value']?>"
						<?=$this->genParamStr();?>
						class="noresize submit <?=$this->config['cls']?>" />
			<?php
		}
	}

?>