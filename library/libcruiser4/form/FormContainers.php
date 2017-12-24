<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @copyright Cruiser cruiser.com.ua
	 */

	/**
	 * Контейнер формы
	 */
	define('TFFORM', 'VirabFormContainer');

	/**
	 * Контейнер полей (fieldset)
	 */
	define('TFFIELDSSET', 'VirabFieldsetContainer');


	include_once ("FieldBlocks.php");
	include_once (LIB_PATH . "/ajax/class.json.php");

	/**
	 * Класс генерации контейнера формы
	 *
	 * @package DspHelper
	 */
	class VirabFormContainer {

		/**
		 * @var array конфигурация контейнера
		 */
		protected $config;

		/**
		 * @var Language экземпляр Language класса
		 */
		private $_lng;

		/**
		 * @var JSON экземпляр JSON класса
		 */
		private $_json;

		/**
		 * Конструктор класса
		 *
		 * <pre>
		 * Значения массива конфигурации:
		 *		string name имя формы
		 *		string method метод (post, get)
		 *		string cls Класс оформления form
		 *		string fieldset_cls Класс оформления fieldset
		 *		string action URL
		 *		boolean has_file_field флаг присутствия полей загрузки файлов (для создания enctype)
		 * 		boolean has_lng флаг присутствия языкозависимых полей
		 * 		string legend_info Дополнительная информация в main legend
		 * 		string addInOnSubmit Вызовы функций при событии onSubmit
		 * </pre>
		 *
		 * @return void
		 */
		public function __construct($config) {
			global $lng;

			$this->config = $config;
			$this->_lng = $lng;
			$this->_json = new JSON(JSON_LOOSE_TYPE);
		}

		/**
		 * Старт контейнера
		 */
		public function begin() {
			$this->config['addInOnSubmit'] = empty($this->config['addInOnSubmit']) ? " return true;" : (" " . $this->config['addInOnSubmit']);
			?>
				<form name="<?=$this->config['name']?>"
					 class="tvirabForm <?=$this->config['cls'] ? $this->config['cls'] : ""?>"
					 method="<?=$this->config['method']?>"
					 action="<?=$this->config['action']?>"
					 <?=$this->config['has_file_field'] ? "enctype=\"multipart/form-data\"" : ""?>
					 onSubmit="FormsNS.NecessaryOnSubmit(this);<?=$this->config['addInOnSubmit']?>"
				>
					<script type="text/javascript">

					</script>
					<fieldset class="main <?=$this->config['fieldset_cls'] ? $this->config['fieldset_cls'] : ""?>">
						<legend class="main">
							<script type="text/javascript" src="../../virab/js/forms.js"></script>
							<?php if ($this->config['has_lng']):
								$lngjson = $this->_json->encode($this->_lng->lng_array); ?>
								&nbsp;&darr;&nbsp;<?=_("Переключение языка ввода")?>:&nbsp;
								<?php foreach ($this->_lng->lng_array as $lnge): ?>
									<span id="lngSwitch<?=$lnge['ind_name']?>" class="<?=($lnge['id'] == $this->_lng->now_lng) ? "lng_checked" : "lng"?>" lngid="<?=$lnge['id']?>"><?=$lnge['ind_name']?></span>&nbsp;
								<?php endforeach; ?>
								<script type="text/javascript"><?="LNG = " . $lngjson?></script>
								<script type="text/javascript" src="../../virab/js/form_lng.js"></script>
							<?php else: ?>
								&nbsp;&darr;&nbsp;
							<?php endif; ?>
							<?php if ($this->config['legend_info']): ?>
								&nbsp;<?=$this->config['legend_info']?>&nbsp;
							<?php endif; ?>
						</legend>
			<?php
		}

		/**
		 * Получить блок для полей
		 *
		 * @return VirabFieldBlock экземпляр VirabFieldBlock
		 */
		public function getFieldBlock() {
			return new VirabFieldBlock();
		}

		/**
		 * Окончание контейнера
		 */
		public function end() {
			?>
					</fieldset>
				</form>
				<script type="text/javascript">
					// фокус на первом поле ввода
					$(function() {
						$('.tvirabForm label:first + *').focus();
					});
				</script>
			<?php
		}
	}

	/**
	 * Класс вывода ошибки выбора типа контейнера формы
	 *
	 * @package DspHelper
	 */
	class VirabErrorContainer extends VirabFormContainer {

		/**
		 * Старт контейнера
		 */
		public function begin() {
			print "<b style=\"color: red;\">" . _("Неверный тип контейнера формы!") . "</b>";
		}

		/**
		 * Окончание контейнера
		 */
		public function end() {
			print "<b style=\"color: red;\">" . _("Неверный тип контейнера формы!") . "</b>";
		}
	}

	/**
	 * Контейнер fieldset
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *		string legend Название блока
	 *		string cls Класс оформления
	 *		string style Стиль оформления
	 *		string name Наименование fieldset-а
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabFieldsetContainer extends VirabFormContainer {

		/**
		 * Старт контейнера
		 */
		public function begin() {
			?>
				<fieldset class="tvirabFieldset <?=$this->config['cls'] ? $this->config['cls'] : ""?>" <?=$this->config['style'] ? ('style="' . $this->config['style'] . '"') : ""?><?=$this->config['name'] ? ' name="'.$this->config['name'].'"' : ''?>>
					<?php if (!empty($this->config['legend'])): ?>
						<legend><?=$this->config['legend']?></legend>
					<?php endif; ?>
			<?php
		}

		/**
		 * Окончание контейнера
		 */
		public function end() {
			?>
				</fieldset>
			<?php
		}
	}

?>