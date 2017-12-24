<?php

   /**
    * @package DspHelper
    * @author Редькин Сергей, .ter [rou.terra@gmail.com]
    * @copyright Cruiser cruiser.com.ua
    */

	include_once ("table/TableViewer.php");
	include_once ("form/FormContainers.php");
	include_once ("DataPaging.class.php");

	/**
	 * Класс управления элементами отображения
	 *
	 * @package DspHelper
	 */
	class DspHelper {

		/**
		 * Экземпляр класса
		 *
		 * @var DspHelper
		 */
		private static $_instance;

		/**
		 * getInstance
		 *
		 * @return DspHelper instance
		 */
		static public function getInstance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Получение View для таблицы
		 *
		 * @param string $view константа типа View таблицы (см. TableViewer.php)
		 * @return class например VirabTableViewerDef
		 */
		public function getTableViewer($view = TTVDEFAULT) {
			return new $view();
		}

		/**
		 * Получение экземпляра контейнера для элементов формы
		 *
		 * @param array $config
		 * <pre>
		 * 		string type тип контейнера (см. form/FormContainers.php)
		 * </pre>
		 * @return class из FormContainers.php
		 */
		public function getFormContainer($config) {
			if (class_exists($config['type'])) {
				return new $config['type']($config);
			} else {
				return new VirabErrorContainer($config);
			}
		}

		/**
		 * Получение экземпляра класса управления пэйджингом данных
		 *
		 * @param array $config
		 * @return DataPaging
		 */
		public function getDataPager($config = array()) {
			return new DataPaging($config);
		}

		/**
		 * Вывод формы с единой конфигурацией
		 *
		 * @param array config конфигурация формы
		 * <pre>
		 * 	см. конфигурацию VirabFormContainer (form/FormContainers.php)
		 * 	array fields - Массив конфигураций элементов формы
		 * 	               см. конфигурации элементов форм
		 * 	      + параметр typeField для указания типа элемента формы, например TFTEXTFIELD
		 * </pre>
		 * @return void
		 */
		public function writeForm($config = array()) {
			if (
				$config['type'] === TFFORM				||
				$config['type'] === TFFIELDSSET 		||
				$config['typeField'] === TFFIELDSSET
			) {
				// вывод формы
				$fc = $this->getFormContainer($config);
				$fc->begin();
					$fblock = $fc->getFieldBlock();
					$this->_showFormBlockElements($fblock, $config['fields']);
				$fc->end();
			}
		}

		/**
		 * Вывод элементов формы
		 *
		 * @param VirabFieldBlock block блок для вывода элементов формы (form, fieldset)
		 * @param array fields массив настроек элементов форм
		 * <pre>
		 * 	см. конфигурации элементов форм (form/FieldBlock.php)
		 * 	+ параметр typeField для указания типа элемента формы, например TFTEXTFIELD
		 * </pre>
		 */
		private function _showFormBlockElements($block, $fields = array()) {
			foreach ( $fields as $fieldConf ) {
				if ( $fieldConf['type'] === TFFIELDSSET ) {
					$this->writeForm($fieldConf);
				} elseif( $fieldConf['typeField'] === TFFIELDSSET ) {
					$this->writeForm(array_merge($fieldConf, array( 'type' => TFFIELDSSET )));
				} elseif ($fieldConf['typeField']) {
					$block->show($fieldConf['typeField'], $fieldConf);
				}
			}
		}

		/**
		 * Функция быстрого вызова View для таблицы
		 *
		 * @param array $table_config конфигурация таблицы (см. TableViewer.php)
		 * @return void
		 */
		public function write_table($table_config) {
			$tviewer = $this->getTableViewer();
			$tviewer->setConfig($table_config);
			$tviewer->show();
		}
		
		public function write_tab($table_config) {
			$tviewer = $this->getTableViewer();
			$tviewer->setConfig($table_config);
			$tviewer->show_tab();
		}
		
		

		/**
		 * Синоним вызова функции write_table
		 *
		 * @return void
		 */
		public function writeTable($table_config) {
			$this->write_table($table_config);
		}

		/**
		 * Вывод фильтра данных для таблицы
		 *
		 * @param array $config
		 * <pre>
		 * 	string action - URL для action фильтр формы
		 * 	string tableID - ID таблицы
		 * 	array fields - Массив конфигураций элементов формы
		 * 	               см. конфигурации элементов форм
		 * 	      + параметр typeField для указания типа элемента формы, например TFTEXTFIELD
		 * 	      - параметр value (он инициализируется на основании параметра name)
		 * </pre>
		 * @param array $attributes - массив переданных значений
		 * @return void
		 */
		public function writeTableFilter($config, $attributes = array()) {

			$filter_actual = (bool) $attributes['filter'];

			// CSS для блока фильтров
			print '<link rel="stylesheet" type="text/css" href="./css/filter.css">';
			// JS для блока фильтров
			print '<script type="text/javascript" src="./js/filter.js"></script>';
			?>
			<script type="text/javascript">
				<?php if ($filter_actual):?>
					setPagerTitle('.&nbsp;<?=_("С фильтрацией.")?>');
				<?php else: ?>
					$(function() {
						setTimeout('triggFilter()', 250);
					});
				<?php endif; ?>
				$(function() {
					$('.tvirabForm').removeClass('tff_form_hidden');
					Cruiser.mainPageResize();
				});
			</script>
			<?php

			$fc = $this->getFormContainer(array(
				'type' => TFFORM,
				'name' => 'filter_form',
				'cls' => 'filter_form_class',
				'method' => 'post',
				'action' => $config['action'],
				'has_lng' => false,
				'legend_info' => _('Фильтрация')
			));
			$fc->begin();

				$fblock = $fc->getFieldBlock();

				if (is_array($config['fields'])) {
					$table_field_mass = array();
					foreach ( $config['fields'] as $field_value ) {
						$fblock->show(
							$field_value['typeField'],
							array_merge(
								$field_value,
								array(
									'value' => $attributes[$field_value['name']],
									'selected' => array( $attributes[$field_value['name']] )
								)
							)
						);
						$table_field_mass[] = $field_value['name'];
					}
					// запишем массив наименований параметров в сессию
					$_SESSION[$config['tableID']]['filter_fields'] = $table_field_mass;
				}

				$fblock->show(TFBUTTON, array(
					'type' => 'button',
					'value' => _('Фильтровать'),
					'params' => array(
						'onClick' => 'sendFilter("' . $config['tableID'] . '", "<br /><center>&nbsp;&nbsp;&bull;&nbsp;<b>' . _('Перезагружаю таблицу. Пожалуйста, подождите...') . '</b></center>")'
					)
				));

				$fblock->show(TFBUTTON, array(
					'type' => 'button',
					'value' => _('Очистить фильтры'),
					'params' => array(
						'onClick' => 'cleanFilter("' . $config['tableID'] . '", "<br /><center>&nbsp;&nbsp;&bull;&nbsp;<b>' . _('Фильтры очищены, перезагружаю таблицу. Пожалуйста, подождите...') . '</b></center>")'
					)
				));

				// скрытые поля
				$fblock->show(TFHIDDENFIELD, array(
					'name' => 'filter',
					'value' => 1
				));

			$fc->end();

		}
	}

