<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @version 0.55
	 * @copyright Cruiser cruiser.com.ua
	 */

	// =====================================================================================================
	// Разное
	// =====================================================================================================

	/**
	 * Картинки c lightbox просмотром [TFIMAGES]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		array imgs Массив картинок в формате: array(array('img' => '...image_path...', 'tmb_img' => '...image_thumb_path...', 'title' => '...'),...)
	 * 		emptyImgPath string Путь к картинке с инфо об отсутствующем изображении
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFImages extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('imgs', array());
			$this->setDefaultValue('emptyImgPath', 'images/no_photo.gif');
			$id_group = $this->getRndId();
			?>
				<label><?=$this->config['label']?><br /></label>
				<ul class="VirabTFImages <?=$this->config['cls']?>" style="width: <?=$this->config['width']?>px">
					<?php foreach ($this->config['imgs'] as $imgar): ?>
						<li>
							<?php if (empty($imgar['img']) && empty($imgar['tmb_img'])): ?>
								<img src="<?=$this->config['emptyImgPath']?>" alt="" border="0" />
							<?php elseif (empty($imgar['img'])): ?>
								<img src="<?=$imgar['tmb_img']?>" alt="" border="0" />
							<?php else: ?>
								<a href="<?=$imgar['img']?>" title="<?=$imgar['title']?>" rel="lightbox-<?=$id_group?>">
									<img src="<?=$imgar['tmb_img']?>"  alt="" border="0" />
								</a>
							<?php endif; ?>

						</li>
					<?php endforeach; ?>
				</ul>
			<?php
		}
	}

	/**
	 * Поле с простым текстом [TFSIMPLETEXT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		string text Текст поля
	 * 		int width Размер в пикселях
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSimpleText extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('text', '&nbsp;');
			?>
				<label><?=$this->config['label']?><br /></label>
				<div class="SimpleText" style="<?php echo ($this->config['width'] ? "width:" . $this->config['width'] . "px" : "") ?>">
					<?=$this->modificationValueString($this->config['text'])?>
				</div>
			<?php
		}
	}

	/**
	 * Поле выбора флага [TFSELECTFLAG]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		string name Наименование
	 * 		string value Значение (принимает значение ID флага или путь к картинке с флагом)
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectFlag extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			global $__COUNTRY_FLAGS;

			if (strlen($this->config['value']) < 4) {
				// передан ID
				$value = (int) $this->config['value'];
			} else {
				// передан путь
				// найдем ID
				$value = 0;
				foreach ( $__COUNTRY_FLAGS as $ID => $path_array ) {
					if ($this->config['value'] == $path_array['path']) {
						$value = $ID;
					}
				}
			}

			$id_flag_block = $this->getRndId('FLG_');
			$id_selected = $this->getRndId('FLG_');
			$selected_src = ($value != 0) ? $__COUNTRY_FLAGS[$value]['path'] : '';
			?>
				<input type="hidden" name="<?=$this->config['name']?>" value="<?=$value?>" autocomplete="off" />
				<label><?=$this->config['label']?><br /></label>
				<ul id="<?=$id_flag_block?>" class="VirabTFImages <?=$this->config['cls']?>" style="width: <?=$this->config['width']?>px; margin-left: 110px;">
					<?php foreach ($__COUNTRY_FLAGS as $ID => $path_array): ?>
						<li>
							<img class="flag_ID" id="flag_ID_<?=$ID?>" alt="" src="<?=$path_array['path']?>" border="0" title="<?=$this->getCountryName($path_array['path'])?>" style="border: <?=($value != 0 && $value == $ID) ? "1px solid red" : "0"?>" />
						</li>
					<?php endforeach; ?>
					<br />
					<div id="<?=$id_selected?>__container">
						<?php if ($value !== 0): ?>
							<?=_("Выбран:&nbsp;")?><img id="<?=$id_selected?>" src="<?=$selected_src?>" alt="" border="0" />&nbsp;[<span id="<?=$id_selected?>__name"><?=$this->getCountryName($selected_src)?></span>]
						<?php endif; ?>
					</div>
				</ul>
				<script type="text/javascript">
					$(function() {
						$("#<?php echo $id_flag_block; ?>").bind('click', function(e) {
							if (e.target.nodeName === "IMG") {
								var targ = $(e.target);
								if (targ.hasClass('flag_ID')) {
									var tt = targ.get(0);
									var ID = tt.id.replace("flag_ID_", "");

									var cont = $("#<?=$id_selected?>__container img");
									if (cont.length == 0) {
										$("#<?=$id_selected?>__container").html('<?=_("Выбран:&nbsp;")?><img id="<?=$id_selected?>" src="" />&nbsp;[<span id="<?=$id_selected?>__name">-</span>]');
									}

									$("input[name='<?php echo $this->config['name']?>']").val(ID);
									$('#<?=$id_selected?>').attr('src', tt.src);
									$('#<?=$id_selected?>__name').html($(tt).attr('title'));
								}
							}
						});
					});
				</script>
			<?php
		}

		/**
		 * Получение названия страны из пути (например: /virab/images/flags/m/Mozambique.gif -> Mozambique)
		 *
		 * @param string $path путь
		 * @return string название страны
		 */
		private function getCountryName($path) {
 			return preg_replace('%/virab/images/flags/[\w]{1}/([^.]*).gif%im', '${1}', $path);
		}
	}

	/**
	 * Select элемент выбора узла сайта [TFSELECTSITENODE]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		array selected Массив выделенных значений
	 * 		boolean empty Флаг присутствия "пустого" значения "-- Выберите элемент --" (по-умолчанию true)
	 * 		boolean multiple Флаг режима multiple (по-умолчанию false)
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectSiteNode extends VirabTFSelect {

		/**
		 * Дерево узлов
		 *
		 * @var NSTree
		 */
		private $_nsTree;

		/**
		 * Language class
		 *
		 * @var Language
		 */
		private $_lng;

		/**
		 * Конструктор класса
		 *
		 * @param array $config
		 * @return void
		 */
		public function __construct($config) {
			global $nsTree, $lng;

			parent::__construct($config);
			$this->_nsTree = $nsTree;
			$this->_lng = $lng;
		}

		/**
		 * Отображение блока
		 */
		public function show() {
			// подготовка данных
			$nodeSet = $this->_nsTree->selectNodes(0, 0, array('name', 'chpu'));
			if (is_array($nodeSet)) {
				foreach ($nodeSet as $node) {
					if ($node['data_id'] == 0) {
						continue;
					}

					$this->config['options'][$node['id']] = $this->setIndent($node['level'] - 1) . $this->_lng->get($node['name']);
				}
			}

			$this->config['without_output_modification'] = true;
			parent::show();
		}

	}

	/**
	 * Select элемент выбора шаблона отображения [TFSELECTTEMPLATEPAGE]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		array selected Массив выделенных значений
	 * 		boolean empty Флаг присутствия "пустого" значения "-- Выберите элемент --" (по-умолчанию true)
	 * 		boolean multiple Флаг режима multiple (по-умолчанию false)
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectTemplatePage extends VirabTFSelect {

		/**
		 * Дерево шаблонов
		 *
		 * @var NSTree
		 */
		private $_nsTree;

		/**
		 * Language class
		 *
		 * @var Language
		 */
		private $_lng;

		/**
		 * @var JSON экземпляр JSON класса
		 */
		private $_json;

		/**
		 * Конструктор класса
		 *
		 * @param array $config
		 * @return void
		 */
		public function __construct($config) {
			global $tmplTree, $lng;

			parent::__construct($config);
			$this->_nsTree = $tmplTree;
			$this->_lng = $lng;
			$this->_json = new JSON(JSON_LOOSE_TYPE);
		}

		/**
		 * Отображение блока
		 */
		public function show() {
			$nodeSet = $this->_nsTree->select(
				0,
				array('lngh_name', 'tmpl_type'),
				NSTREE_AXIS_DESCENDANT,
				null,
				null,
				array('d.tmpl_type' => TMPL_TYPE_PAGE),
				array(
					'selectPart' => '
						stor.`name` AS template_name,
						ptp.`path` AS img_path,
						ptp.`path_tmb` AS img_tmb_path
					',
					'joinPart' => '
						JOIN ' . CFG_DBTBL_TMPL_STORAGE . ' AS stor ON stor.`id` = d.`id_tmpl_storage`
						LEFT JOIN ' . CFG_DBTBL_TMPL_PAGE_TMPL_PICTURE . ' AS ptp ON ptp.`id_tmpl` = s1.id
					'
				)
				//array( 'fb' => true, 'explain' => true, 'result' => true )
			);

			$pictures = array();
			if (is_array($nodeSet)) {
				$used = array();
				foreach ($nodeSet as $node) {
					if ($node['data_id'] == 0) {
						continue;
					}
					// удаление дубликатов
					if (in_array($node['data_id'], $used)) {
						continue;
					} else {
						$used[] = $node['data_id'];
					}

					$this->config['options'][$node['id']] = $this->_lng->get($node['lngh_name']);
					$pictures[$node['id']] = array(
						'img' => $node['img_path'],
						'tmb_img' => $node['img_tmb_path']
					);
				}
			}

			$this->config['without_output_modification'] = true;
			$this->config['id'] = $this->getRndId();

			parent::show();

			$this->setDefaultValue('emptyImgPath', 'images/no_photo.gif');
			?>
				<label>&nbsp;<br /></label>
				<ul class="VirabTFImages <?=$this->config['cls']?>" style="width: <?=$this->config['width']?>px">
					<?php
						$imgar = (array) $pictures[$this->config['selected'][0]];
					?>
					<li>
						<?php if (empty($imgar['img']) && empty($imgar['tmb_img'])): ?>
							<img id="<?=$this->config['id']?>_preview_image" src="<?=$this->config['emptyImgPath']?>" alt="" border="0" />
						<?php else: ?>
							<img id="<?=$this->config['id']?>_preview_image" src="<?=$imgar['tmb_img']?>" alt="" border="0" />
						<?php endif; ?>
					</li>
				</ul>
				<script type="text/javascript">
					$(function() {
						var id = '<?php echo $this->config['id']; ?>',
							imageSet = <?=$this->_json->encode($pictures)?>;

						FormsNS.VirabTFSelectTemplatePage.set( id, imageSet, '<?php echo $this->config['emptyImgPath']?>' );
					});
				</script>
			<?php
		}

	}

	/**
	 * Select элемент выбора шаблонов с меткой селективного [TFSELECTTEMPLATESELECTIVE]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		array selected Массив выделенных значений
	 * 		boolean empty Флаг присутствия "пустого" значения "-- Выберите элемент --" (по-умолчанию true)
	 * 		boolean multiple Флаг режима multiple (по-умолчанию false)
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectTemplateSelective extends VirabTFSelect {

		/**
		 * Дерево шаблонов
		 *
		 * @var NSTree
		 */
		private $_nsTree;

		/**
		 * Language class
		 *
		 * @var Language
		 */
		private $_lng;

		/**
		 * Конструктор класса
		 *
		 * @param array $config
		 * @return void
		 */
		public function __construct($config) {
			global $tmplTree, $lng;

			parent::__construct($config);
			$this->_nsTree = $tmplTree;
			$this->_lng = $lng;
		}

		/**
		 * Отображение блока
		 */
		public function show() {
			$nodeSet = $this->_nsTree->select(
				0,
				array('lngh_name', 'tmpl_type', 'id_tmpl_storage', 'executor_type'),
				NSTREE_AXIS_DESCENDANT,
				null,
				null,
				array('is_selective' => 1),
				array(
					'selectPart' => '
						tt.`lngh_type_name`,
						stor.`name` AS template_name,
						ec.`lngh_name` AS executor_name
					',
					'joinPart' => '
						JOIN ' . CFG_DBTBL_TMPL_TYPES . ' AS tt ON tt.`id` = d.`tmpl_type`
						JOIN ' . CFG_DBTBL_TMPL_STORAGE . ' AS stor ON stor.`id` = d.`id_tmpl_storage`
						LEFT JOIN ' . CFG_DBTBL_TMPL_EXPANDED_CONSTANTS . ' ec ON ec.`id` = d.`executor_type`
					'
				)
			);

			if (is_array($nodeSet)) {
				$used = array();
				foreach ($nodeSet as $node) {
					if ($node['data_id'] == 0) {
						continue;
					}
					// удаление дубликатов
					if (in_array($node['data_id'], $used)) {
						continue;
					} else {
						$used[] = $node['data_id'];
					}

					$template_type = ((int) $node["executor_type"] !== 0) ? (_("исполнитель") . " - " . $this->_lng->get($node["executor_name"])) : $this->_lng->get($node["lngh_type_name"]);

					$this->config['options'][$node['id']] =
						$this->_lng->get($node['lngh_name']) .
						" [ " .
						_("переменная") .
						": " .
						$node['template_name'] .
						", " .
						_("тип") .
						": " .
						$template_type .
						" ]";
				}
			}

			$this->config['without_output_modification'] = true;
			parent::show();
		}

	}

	/**
	 * Select элемент смены родителя в дереве [TFSELECTTREEPARENT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		NSTREE tree Дерево
	 * 		int treeNodeId ID элемента дерева, для которого изменяется родитель
	 * 		array mapping Маппинг данных (по-умолчанию array('value' => 'id', 'name' => 'name'))
	 *
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectTreeParent extends VirabTFSelect {

		protected $parentNode;

		/**
		 * Модификация строки названия
		 *
		 * @param string $value
		 */
		protected function valueModificate($value) {
			return $value;
		}

		/**
		 * Получить данные о содержимом дерева
		 */
		protected function getTreeData() {

			$nsTree = $this->config['tree'];
			$treeNodeId = $this->config['treeNodeId'];

			$nameVal = $this->config['mapping']['value'];
			$nameName = $this->config['mapping']['name'];

			// Прочитаем список всех узлов
			$nodeSet = $nsTree->selectNodes(0, 0, array($nameName));

			// Прочитаем список дочерних узлов, чтоб их исключить
			$exclude_nodeSet = &$nsTree->selectNodes($treeNodeId, 0, array($nameName));
			$excluded = array();
			if (is_array($exclude_nodeSet)) {
				foreach ($exclude_nodeSet as $exnode) {
					$excluded[] = $exnode[$nameVal];
				}
			}

			// Определим родительский элемент, который является текущим
			$this->parentNode = $nsTree->getParentNode($treeNodeId);

			if (is_array($nodeSet)) {
				// уберем лишние узлы
				foreach ($nodeSet as $ii => $node) {
					if (in_array($node[$nameVal], $excluded)) {
						unset($nodeSet[$ii]);
					}
				}
				// определим имена нод
				foreach ($nodeSet as &$node) {
					if ((int) $node['left'] === 1) {
						$node[$nameName] = _("Корень дерева");
					} else {
						$node[$nameName] = $this->valueModificate($node[$nameName]);
					}
				}
			}

			return $nodeSet;
		}

		/**
		 * Отображение блока
		 */
		public function show() {

			if (!($this->config['tree'] instanceof NSTree)) {
				throw new Exception('В параметре конфигурации tree должно присутствовать дерево NSTree класса!');
			}
			$this->setDefaultValue('mapping', array('value' => 'id', 'name' => 'name'));

			$nodeSet = $this->getTreeData();

			if (is_array($nodeSet)) {
				foreach ($nodeSet as $node) {
					$this->config['options'][$node[$this->config['mapping']['value']]] = $this->setIndent($node['level']) . $node[$this->config['mapping']['name']];
				}
			}

			$this->config['empty'] = false;
			$this->config['without_output_modification'] = true;
			$this->config['selected'] = array((int) $this->parentNode[$this->config['mapping']['value']]);
			parent::show();
		}

	}

	/**
	 * Select элемент смены родителя в дереве (+ многоязыковость) [TFSELECTTREEPARENTLNG]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		NSTREE tree Дерево
	 * 		int treeNodeId ID элемента дерева, для которого изменяется родитель
	 * 		array mapping Маппинг данных (по-умолчанию array('value' => 'id', 'name' => 'name'))
	 *
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectTreeParentLng extends VirabTFSelectTreeParent {

		/**
		 * Language class
		 *
		 * @var Language
		 */
		private $_lng;

		/**
		 * Конструктор класса
		 *
		 * @param array $config
		 * @return void
		 */
		public function __construct($config) {
			global $lng;

			parent::__construct($config);
			$this->_lng = $lng;
		}

		/**
		 * Модификация строки названия
		 *
		 * @param string $value
		 */
		protected function valueModificate($value) {
			return $this->_lng->get($value);
		}

	}

	/**
	 * Поле интерактивного выбора элементов (c VirabTFAutosuggestField) [TFINTERACTIVEAUTOSUGGESTSELECTITEM]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		string label_block Название всего блока
	 * 		string label_search Название поля поиска значений
	 *		string backend урл бэкэнда запроса поиска
	 * 		string defDictTable дефолтная таблица справочника в базе данных по которому осуществляется поиск
	 *		array addparam дополнительные параметры в GET запрос
	 * 		boolean shownoresults отображать "пустой" результат
	 * 		string searchvarname переменная, передаваемая на бэкэнд для поиска
	 * 		int maxresults количество выводимых результатов
	 * 		string name Наименование
	 * 		array value Массив массивов значений вида array( array( 'id' => 1, 'name' => 2 ), ... )
	 *
	 * Поле отправляет данные в формате array( name поля => array( массив выбранных элементов ) )
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFInteractiveAutosuggestSelectItem extends VirabTFField {

		/**
		 * Namespace
		 * @var string
		 */
		protected $nameSpace;

		/**
		 * @var string
		 */
		protected $tableID;

		/**
		 * @var string
		 */
		protected $searchFieldID;

		/**
		 * @var string
		 */
		protected $callbackFunctionName;

		/**
		 * @var string
		 */
		protected $addFunctionName;

		/**
		 * @var string
		 */
		protected $removeFunctionName;

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('label_block', "");
			$this->setDefaultValue('label_search', "");
			$this->setDefaultValue('backend', SITE_URL . "/library/libcruiser4/ajax/findItemsInDict.php");
			$this->setDefaultValue('defDictTable', "");
			$this->setDefaultValue('addparam', array());
			$this->setDefaultValue('shownoresults', true);
			$this->setDefaultValue('searchvarname', "search");
			$this->setDefaultValue('maxresults', 10);


			$this->nameSpace = 'VirabTFISI_' . $this->config['name'] . '_';

			$this->tableID = $this->getRndId($this->nameSpace);
			$this->searchFieldID = $this->getRndId($this->nameSpace);
			$this->callbackFunctionName = $this->getRndId($this->nameSpace);
			$this->addFunctionName = $this->getRndId($this->nameSpace);
			$this->removeFunctionName = $this->getRndId($this->nameSpace);

			$this->setStyleBlock();
			$this->setScriptCallbackFunctionBlock();
			$this->setScriptAddFunctionBlock();
			$this->setScriptRemoveFunctionBlock();
			?>

			<fieldset class="VirabTFISIFieldset">
				<legend><?=$this->config['label_block']?></legend>
				<label><?=$this->config['label']?><br /></label>
				<table id="<?=$this->tableID?>" class="VirabTFISITable">
					<?=$this->getTableHeader()?>
					<?=$this->getTableRows()?>
				</table>
				<?php
					$suggest = new VirabTFAutosuggestField(array(
						'id' => $this->searchFieldID,
						'name' => $this->getRndId(),
						'label' => $this->config['label_search'],
						'backend' => $this->config['backend'],
						'shownoresults' => $this->config['shownoresults'],
						'varname' => $this->config['searchvarname'],
						'maxresults' => $this->config['maxresults'],
						'width' => 320,
						'manual_set_br' => true,
						'callback' => $this->callbackFunctionName,
						'addparam' => array_merge(array(
							'table' => $this->config['defDictTable']
						), $this->config['addparam'])
					));
					$suggest->show();
					$button = new VirabTFButton(array(
						'type' => 'button',
						'value' => _('Добавить'),
						'cls' => 'VirabTFISIButton',
						'params' => array(
							'onClick' => $this->addFunctionName . '();'
						)
					));
					$button->show();
				?>
				<br />
			</fieldset>
			<?php
		}

		/**
		 * Установка стилей
		 * @return void
		 */
		protected function setStyleBlock() {
			// убрал в .css
		}

		/**
		 * Установка скрипта callback Function
		 * @return void
		 */
		protected function setScriptCallbackFunctionBlock() {
			?>
				<script type="text/javascript">
					window['<?=$this->callbackFunctionName?>'] = function (obj) {
						if (
								(typeof window._currentVirabTFISIEl === 'undefined') ||
								!(window._currentVirabTFISIEl instanceof Array)
						) {
							window._currentVirabTFISIEl = [];
						}

						window._currentVirabTFISIEl['<?=$this->tableID?>'] = {
							id: obj.id,
							name: obj.value
						};
					}
				</script>
			<?php
		}

		/**
		 * Установка скрипта add Function
		 * @return void
		 */
		protected function setScriptAddFunctionBlock() {
			?>
				<script type="text/javascript">
					window['<?=$this->addFunctionName?>'] = function() {
						var el = '<?=$this->tableID?>';

						if (
							typeof window._currentVirabTFISIEl === 'undefined'
								||
							typeof window._currentVirabTFISIEl[el] === 'undefined'
								||
							$("#<?=$this->tableID?> tbody tr.VirabTFISITR" + window._currentVirabTFISIEl[el].id).length
						) {
							// чистим
							$("#<?=$this->searchFieldID?>").val("");
							$("#<?=$this->searchFieldID?>_input").val("");

							return;
						}

						var current = window._currentVirabTFISIEl[el],
							add = [
								'<tr class="VirabTFISITR_ VirabTFISITR', current.id, '">',
									'<input type="hidden" name="<?=$this->config['name']?>[]" value="', current.id ,'">',
									'<td>&nbsp;', current.name, '&nbsp;</td>',
									'<td class="centerText">',
										'&nbsp;<a href="#" onclick="<?=$this->removeFunctionName?>(', current.id, '); return false;"><img src="images/but/trash.gif" border="0" alt="" /></a>&nbsp;',
									'</td>',
								'</tr>'
							];

						var tblast = $("#<?=$this->tableID?> tbody tr:last");
						if (tblast.length) {
							tblast.after(add.join(''));
						} else {
							$("#<?=$this->tableID?> tbody").append(add.join(''));
						}

						// чистим
						delete(window._currentVirabTFISIEl[el]);
						$("#<?=$this->searchFieldID?>").val("");
						$("#<?=$this->searchFieldID?>_input").val("");
					}
				</script>
			<?php
		}

		/**
		 * Установка скрипта remove Function
		 * @return void
		 */
		protected function setScriptRemoveFunctionBlock() {
			?>
				<script type="text/javascript">
					window['<?=$this->removeFunctionName?>'] = function(id) {
						$('#<?=$this->tableID?> tr.VirabTFISITR' + id).remove();
					}
				</script>
			<?php
		}

		/**
		 * Получить заголовок таблицы данных
		 * @return string
		 */
		protected function getTableHeader() {
			?>
				<thead class="VirabTFISIHeader">
					<td><?=_("Наименование")?></td>
					<td>&nbsp;x&nbsp;</td>
				</thead>
			<?php
		}

		/**
		 * Получить строчки таблицы данных
		 * @return string
		 */
		protected function getTableRows() {
			$res = array();

			if (is_array($this->config['value']) && count($this->config['value'])) {
				foreach ($this->config['value'] as $val) {
					$res[] = '<tr class="VirabTFISITR_ VirabTFISITR' . $val['id'] . '">';
					$res[] = 	'<input type="hidden" name="' . $this->config['name'] . '[]" value="' . $val['id'] . '">';
					$res[] = 	'<td>&nbsp;' . $val['name'] . '&nbsp;</td>';
					$res[] = 	'<td>&nbsp;<a href="#" onclick="' . $this->removeFunctionName . '(' . $val['id'] . '); return false;"><img src="images/but/trash.gif" alt="" border="0" /></a>&nbsp;</td>';
					$res[] = '</tr>';
				}
			}

			?>
				<tbody>
					<?=join("", $res)?>
				</tbody>
			<?php
		}
	}

	/**
	 * Поле интерактивного выбора элементов из селекта [TFINTERACTIVESELECTITEM]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		string label_block Название всего блока
	 * 		string label_search Название у селекта
	 * 		string name Наименование
	 *		array options Массив значений селекта вида array ( 'Значение' => 'Наименование' )
	 * 		array value Массив массивов значений вида array( array( 'id' => 1, 'name' => 2 ), ... )
	 *
	 * Поле отправляет данные в формате array( name поля => array( массив выбранных элементов ) )
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFInteractiveSelectItem extends VirabTFInteractiveAutosuggestSelectItem {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('label_block', "");
			$this->setDefaultValue('label_search', "");

			$this->nameSpace = 'VirabTFISI_' . $this->config['name'] . '_';

			$this->tableID = $this->getRndId($this->nameSpace);
			$this->searchFieldID = $this->getRndId($this->nameSpace);
			$this->callbackFunctionName = $this->getRndId($this->nameSpace);
			$this->addFunctionName = $this->getRndId($this->nameSpace);
			$this->removeFunctionName = $this->getRndId($this->nameSpace);

			$this->setStyleBlock();
			$this->setScriptCallbackFunctionBlock();
			$this->setScriptAddFunctionBlock();
			$this->setScriptRemoveFunctionBlock();
			?>

			<fieldset class="VirabTFISIFieldset">
				<legend><?=$this->config['label_block']?></legend>
				<label><?=$this->config['label']?><br /></label>
				<table id="<?=$this->tableID?>" class="VirabTFISITable">
					<?=$this->getTableHeader()?>
					<?=$this->getTableRows()?>
				</table>
				<?php
					$select = new VirabTFSelect(array(
						'id' => $this->searchFieldID,
						'name' => $this->getRndId(),
						'label' => $this->config['label_search'],
						'options' => $this->config['options'],
						'manual_set_br' => true,
						'empty' => true,
						'width' => 320,
						'without_output_modification' => $this->config['without_output_modification']
					));
					$select->show();
					$button = new VirabTFButton(array(
						'type' => 'button',
						'value' => _('Добавить'),
						'cls' => 'VirabTFISIButton',
						'params' => array(
							'onClick' => $this->addFunctionName . '();'
						)
					));
					$button->show();
				?>
				<br />
			</fieldset>
			<?php
		}

		/**
		 * Установка скрипта callback Function
		 * @return void
		 */
		protected function setScriptCallbackFunctionBlock() {
			?>
				<script type="text/javascript">
					window['<?=$this->callbackFunctionName?>'] = function (e, data) {
						var val = data.val,
							name = $(data.el).children('option[value="' + val + '"]').html();

						if (parseInt(val, 10) === 0) {
							delete(window._currentVirabTFISIEl['<?=$this->tableID?>']);
							return;
						}

						if (
								(typeof window._currentVirabTFISIEl === 'undefined') ||
								!(window._currentVirabTFISIEl instanceof Array)
						) {
							window._currentVirabTFISIEl = [];
						}

						window._currentVirabTFISIEl['<?=$this->tableID?>'] = {
							id: val,
							name: name
						};
					}

					$(function() {
						FormsNS.bindEvent('VirabTFSelect<?=$this->searchFieldID?>', <?=$this->callbackFunctionName?>);
					});
				</script>
			<?php
		}

	}
	/**
	 * MultyUploader картинок [TFMULTYUPLOADIMAGES]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		array imgs Массив картинок в формате: array(array('img' => '...image_path...', 'tmb_img' => '...image_thumb_path...', 'title' => '...'),...)
	 * 		emptyImgPath string Путь к картинке с инфо об отсутствующем изображении
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFMUImages extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('photo_reorder', '');
			$this->setDefaultValue('photo_delete', '');
			$this->setDefaultValue('photo_toprivate', '');
			$this->setDefaultValue('photo_topublic', '');
			$this->setDefaultValue('photo_alt_store', '');
			$this->setDefaultValue('id_album', 0);
			$id_photogal = $this->getRndId();
	?>
<iframe id="photogallery<?=$id_photogal?>" src="/virab/iframe_gallery.php?id_album=<?=urlencode($this->config['id_album'])?>&altstore=<?=urlencode($this->config['photo_alt_store'])?>&reorder=<?=urlencode($this->config['photo_reorder'])?>&delete=<?=urlencode($this->config['photo_delete'])?>&toprivate=<?=urlencode($this->config['photo_toprivate'])?>&topublic=<?=urlencode($this->config['photo_topublic'])?>" width="100%" height="480" align="left" style="border:none;"></iframe>
			<?php
		}
	}
