<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @copyright Cruiser cruiser.com.ua
	 */

	/**
	 * Вьювер для таблиц по-умолчанию
	 */
	define('TTVDEFAULT', 'VirabTableViewerDef');

	/**
	 * Класс-вьювер для таблиц по-умолчанию
	 *
	 * @package DspHelper
	 */
	class VirabTableViewerDef {

		/**
		 * @var array $config конфигурация View
		 */
		protected $config;

		/**
		 * @var Auth экземпляр Auth класса
		 */
		private $_auth;

		/**
		 * @var int количество колонок для объединения
		 */
		private $_colspan;

		/**
		 * @var array массив настроек заголовка таблицы
		 */
		private $_table_header = array();

		/**
		 * Конструктор класса
		 */
		function __construct() {
			$this->_auth = new Auth();
		}

		/**
		 * Установка конфигурации
		 *
		 * @param array $config
		 * Описание конфигурации:
		 * <pre>
		 *	"id"				=> 't_nav_grid', 					// ID таблицы. Должно быть уникальным в пределах проекта!
		 *	"type"				=> 'tree', 		 					// Тип вывода данных таблицы (tree, list)
		 *	"pager"				=> $datapager,						// Экземпляр класса управления пэйджером данных (если не установлен, то используется старый вывод пейджера)
		 * 	"hide_pager"		=> false,							// Если выбран тип list, установка флага в true не выводит pager страниц (по умолчанию установлен в false)
		 *	"url"				=> $_XFA['main'],					// Url для pager-a
		 *	"nodeSet"			=> $nodeSet,						// Массив нод (для типа tree обязательны поля id, parent_id, level, has_children)
		 *	"resID"				=> $resourceId,						// ID ресурса в ACL
		 *	"acl_rule_view"		=> VIEW,							// Доступ на просмотр дерева
		 *	"select_nodes"		=> true,							// Механизм выбора нод (в том числе чекбокс массового выбора)
		 *	"action_nodes"		=> true,							// Элементы действий над нодами
		 *  "sorting"			=> false,							// Сортировка (работает только в list mode) по-умолчанию выключена. (Для работы необходимы name и sorting поля в table_header!)
		 *	"form_action"		=> sprintf($_XFA['delete'], 0),		// Action основной формы (обязателен при select_nodes = true)
		 *
		 *	// Массив настроек заголовка таблицы
		 * 		Поля:	width (string) - ширина колонки в процентах или auto
		 * 				title (string) - Название колонки в title (+ дополнительная информация)
		 * 				html (string) - Название колонки
		 * 				class (string) - Дополнительный класс оформления
		 *  	Внимание: 	если select_nodes = true, то первым элементом будет колонка чекбокса! (width=1%)
		 * 					если action_nodes = true, то последним элементом будет колонка действий ! (width=auto)
		 *
		 *	"table_header"		=> array(
		 *		array('name' => 'name', 'width' => '30%', 'title' => _('Название'), 'html' => _('Название'), 'sorting' => true),
		 *		array('name' => 'desc', 'width' => '40%', 'title' => _('Описание'), 'html' => _('Описание'), 'sorting' => false),
		 *		array('name' => 'peremennaya', 'width' => '10%', 'title' => _('Переменная'), 'html' => _('Переменная'))
		 *	),
		 *
		 * 	// Указание на то, где в nodeSet рекордсете находится информация о цвете строчки таблицы в HEX виде, например "#ffdfdf"
		 *  // Если изменение цвета не предусмотрено, то данная настройка игнорируется
		 * 	"row_color"			=> 'color',
		 *
		 * 	// lambda функция проверки активности поля выбора. Должна возвращать boolean значение разрешения или отключения checkbox поля
		 * 	"enable_select_el"	=> create_function(
		 * 		'$node',
		 * 		' return $node["sys"] != 1;'
		 *  ),
		 *
		 *	// Управление иконками
		 *	"tree_icon_elements" => array(
		 *		'icons' => array(
		 *			'page' => array(
		 *				'empty' => "images/but/page_wchild.gif",
		 *				'folder' => "images/but/page_child.gif",
		 *				'open_folder' => "images/but/page_open.gif"
		 *			),
		 *			'executor_url' => array(
		 *				'empty' => "images/but/executor_url.gif",
		 *				'folder' => "",
		 *				'open_folder' => ""
		 *			)
		 *		),
		 *		"func" => create_function(
		 *			'$node',
		 *			'
		 *				$tmpltype = ($node["tmpl_type"] != TMPL_TYPE_EXECUTOR) ? $node["tmpl_type"] : ($node["executor_type"] + 1000);
		 *
		 *				switch ($tmpltype) {
		 *					case TMPL_TYPE_PAGE:
		 *						return "page";
		 *						break;
		 *
		 *					case (TE_EXECUTOR_URL + 1000):
		 *						return "executor_url";
		 *						break;
		 *				}
		 *
		 *				return false;
		 *			'
		 *		)
		 *	),
		 *
		 *	// Фунции логики вывода данных (внимательно к экранированию!) должны возвращать строку подстановки в таблицу
		 *		Поля: 	align (string) - устанавливает класс выравнивания текста в ячейке
		 * 				args - пока не используется
		 * 				func (lambda) - функция, возвращающая строку с логикой вывода данных (еще раз: внимательно к экранированию!)
		 *
		 *	"data_nodes_cfg"	=> array(
		 *		// Функция поля "Название"
		 *		array(
		 *			"align"	=> 'left',
		 *			"args"	=> '$node',
		 *			"func"	=> create_function(
		 *				'$node',
		 *				'	global $lng;
		 *					return "<span title=\"" . $node[\'url\'] . "\">" . $lng->Gettextlng($node[\'title\']). "</span>";'
		 *			)
		 *		), .... и т.д.
		 *	),
		 *
		 *	// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		 *		Поля:	acl_rule (int) - правило доступа
		 * 				res_innod (boolean) - флаг, в значении true показывает, что ID ресурса надо брать из данных ноды ($node['res_id']) и при false из общего resID
		 * 				href (string) - URL действия
		 * 				has_id (boolean) - устанавливается в true если есть необходимость передать $node['id']! в url должна присутствовать "...&id=0"
		 *				url_id (string) - если задано, то в url ищется данная строка (пример: url_id = "id" )
		 * 				make_href (lambda) - функция получения сложного href (если задана, то значения href, has_id, url_id игнорируются)
		 * 				check_func (lambda) - при наличии будет произведена дополнительная проверка по результатам функции. Функция дополнительной проверки должна возвращать boolean результат
		 * 				img_src (string) - URL картинки
		 * 				title (string) - описание к действию
		 * 				confirm (string) - если будет присутствовать, то будет создан механизм запроса подтверждения
		 *
		 *	"action_nodes_cgf" 	=> array(
		 *		// Редактировать содержимое
		 *		array(
		 *			"acl_rule"	=> EDIT,
		 *			"res_innod"	=> true,
		 *			"href"		=> sprintf($_XFA['mainexecp'], '!node_id!'),
		 *			"has_id"	=> true,
		 *			"url_id"	=> 'id1',
		 * 			"make_href" => create_function(
		 *				'$node',
		 *				'	global $_XFA, $id1;
		 * 					return sprintf($_XFA["editexeccont"], $id1, $node["id"], $node["type_executor"]);'
		 *			),
		 *			"check_func"=> create_function(
		 *				'$node',
		 *				'return $node["id_contaner"] != 0;'
		 *			),
		 *			"img_src"	=> 'images/but/edusr.gif',
		 *			"title"		=> _('Редактировать содержимое')
		 *		), .... и т.д.
		 *	),
		 *
		 *	// настройки глобальных действий
		 * 		Поля:	acl_rule (int) - правило доступа
		 * 				href (string) - url
		 * 				html (string) - текст
		 * 				confirm (string) - если будет присутствовать, то будет создан механизм запроса подтверждения и submit-a формы
		 *
		 *	"main_action_block" => array(
		 *		array(
		 *			"acl_rule"	=> CREATE,
		 *			"href"		=> sprintf($_XFA['form'], 1, 0),
		 *			"html"		=> _('Создать раздел меню<br>верхнего уровня')
		 *		),
		 *		array(
		 *			"acl_rule"	=> DELETE,
		 *			"href"		=> '#',   // если href отличен от # - при клике подменяется action для формы
		 *			"html"		=> _('Удалить выбранные<br>разделы меню'),
		 *			"confirm"	=> _('Вы уверены что хотите удалить выделенные разделы?')
		 *		)
		 *	)
		 *	</pre>
		 *
		 */
		public function setConfig($config = array()) {
			$this->config = $config;
		}

		/**
		 * Вывод таблицы
		 */
		
		public function show_tab() {
			$this->_createTableHeaderConfig();
			$this->_colspan = count($this->_table_header);
			?>  <br>
				<form id="<?=$this->config['id']?>_form" name="form" method="post" action="<?=$this->config['form_action']?>">
					<table id="<?=$this->config['id']?>" class="t_virab_tree">

						<?=$this->_showActionBlock();?>

					</table>
				</form>
			<?php
		}
		
		public function show() {

			$this->_createTableHeaderConfig();
			$this->_colspan = count($this->_table_header);
			// вывод таблицы
				?>
				<form id="<?=$this->config['id']?>_form" name="form" method="post" action="<?=$this->config['form_action']?>">
					<table id="<?=$this->config['id']?>" class="t_virab_tree">

						<?=$this->_showPager();?>

						<?=$this->_showTableHeader();?>

						<?=$this->_showEmptySpacer()?>

						<?=$this->_showTableContent();?>

						<?=$this->_showPager();?>

						<?=$this->_showActionBlock();?>

					</table>
				</form>
			<?php

			// вывод Javascript функционала
			$this->_setJSfunctions();
		}

		/**
		 * Установка javascript функционала
		 */
		protected function _setJSfunctions() {
			?>
				<script type="text/javascript" src="./js/list.js"></script>
				<script type="text/javascript">
					var _tVirab_table_id = '<?=$this->config['id']?>';
				</script>

			<?php
			if(isset($this->config['sorting']) && $this->config['sorting']): ?>
				<script type="text/javascript">
					// сортировка полей
					function sendSortParams(field, dir) {
						$.ajax({
							type: 'POST',
							url: '/library/libcruiser4/ajax/setSortingData.php',
							data: 'table_id=' + _tVirab_table_id + '&' + 'field=' + field + '&' + 'dir=' + dir,
							success: function() {
								window.location.replace(window.location.href);
							}
						});
					}
				</script>

			<?php endif; ?>
				<script type="text/javascript">
					function __sendTableForm(action_url) {
						var tform = $("#" + _tVirab_table_id + "_form").get(0);
						tform.action = action_url;
						tform.submit();
					}
				</script>

			<?php if($this->config['type'] === 'tree'): ?>
				<script type="text/javascript">
					var cnstr = _tVirab_table_id + '_cnstr';

					window[cnstr] = reDumpTree(_tVirab_table_id);
					restoreNodeState(_tVirab_table_id, window[cnstr]);
				</script>
			<?php endif;
		}

		/**
		 * Создание конфигурации заголовка таблицы
		 */
		protected function _createTableHeaderConfig() {
			// заполним массив названий колонок таблицы
			if ($this->config['select_nodes']) {
				$this->_table_header = array(
					array(
						'width'	=> '1%',
						'title' => _('Отметить / снять отметку со всех элементов'),
						'html'	=> ($this->config['nodeSet'] && count($this->config['nodeSet']) > 0) ? '<input type="checkbox" name="allbox" value="1" onclick="CheckAll(\'form\');" class="chbox" autocomplete="off" />' : '',
						'class'	=> 'nonTextEl'
					)
				);
			} else {
				$this->_table_header = array();
			}

			foreach ($this->config['table_header'] as $th) {
				array_push($this->_table_header, $th);
			}

			if ($this->config['action_nodes']) {
				array_push($this->_table_header, array('width' => 'auto', 'title' => _('Действия'), 'html' => _('Действия')));
			}
		}

		/**
		 * Создание заголовка таблицы
		 */
		protected function _showTableHeader() {
			?>
			<tr>
			<?php

			foreach ($this->_table_header as $th): ?>
				<?php if (	!isset($this->config['sorting']) || !$this->config['sorting'] ||
							$this->config['type'] !== 'list' ||
							!isset($th['sorting']) ||
							!$th['sorting'] ): ?>
					<td width="<?=$th['width']?>" class="headTable<?=!empty($th['class']) ? " ".$th['class'] : ''?>" title="<?=_($th['title']);?>"><?=$th['html']?></td>
				<?php else:
					$sort_dir = (isset($_SESSION[$this->config['id']]['sort_dir'])) ? $_SESSION[$this->config['id']]['sort_dir'] : '';
					$sort_field = (isset($_SESSION[$this->config['id']]['sort_field'])) ? $_SESSION[$this->config['id']]['sort_field'] : '';

					$check_present = (isset($sort_field) && $sort_field == $th['name']);
					$sorting_string = '';
					// сначала AJAX на определенный адрес, в котором будет записана $_SESSION переменная
					// потом перезагрузка текущей страницы
					$sorting_string .= '<a href="#" title="' . _("Сортировка по полю") . ' &laquo;' . $th['html'] . '&raquo;" onClick="sendSortParams(\'' . $th['name'] . '\', \'' . (($sort_dir == 'ASC' && $sort_field == $th['name']) ? 'DESC' : 'ASC') . '\'); return false;">' . $th['html'] . '</a>&nbsp;';
					$sorting_string .= $check_present ? '<span class="arrow_sorting_header" title="' . _("Направление сортировки") . ': ' . $sort_dir . '">' . (($sort_dir == 'ASC') ? '&darr;' : '&uarr;') . '</span>&nbsp;' : '';
					$sorting_string .= $check_present ? '<a class="del_sorting_header" href="#" title="' . _("Удалить сортировку по полю") . '" onClick="sendSortParams(\'\', \'\'); return false;">x</a>' : '';
				?>
					<td width="<?=$th['width']?>" class="headTable sortingHeadTable <?=!empty($th['class']) ? " ".$th['class'] : ''?>" title="<?=_($th['title']);?>"><?=$sorting_string?></td>
				<?php endif;
			endforeach; ?>

			</tr>
			<?php
		}

		/**
		 * Вывод содержимого таблицы
		 */
		protected function _showTableContent() {

			if (!$this->config['nodeSet'] || count($this->config['nodeSet']) === 0): ?>
				<tr class="noRecordInTable">
					<td colspan=<?=$this->_colspan?>>&nbsp;&mdash;&nbsp;&nbsp;<?=_("Записей нет")?>&nbsp;&nbsp;&mdash;&nbsp;</td>
				</tr>
			<?php endif;

			if (is_array($this->config['nodeSet']) && $this->_auth->aclCheck($this->config['resID'], $this->config['acl_rule_view'])):
				foreach ($this->config['nodeSet'] as $node):
					$row_color = isset($this->config["row_color"]) ? ($node[$this->config["row_color"]] ? "background-color:" . $node[$this->config["row_color"]] : "") : '';
					// цикл заполнения таблицы
					if ($this->config['type'] === 'tree'): ?>
						<tr	id = "d<?=$node['id']?>"
							onmouseout = "delite(this)"
							onmouseover = "hilite(this)"
							gid = <?=$node['id']?>
							pid = <?=$node['parent_id']?>
							lv = <?=$node['level']?>
							has_child = <?=$node['has_children']?>
							collapsed = 1
							<?=($node['level'] > 1) ? " style='display: None;" . $row_color . "'" : " style='" . $row_color . "'"?>>
					<?php else: ?>
						<tr onmouseout = "delite(this)" onmouseover = "hilite(this)" style="<?=$row_color?>">
					<?php endif; ?>

					<?php if ($this->config['select_nodes']):
							$checkbox_enable = true;

							if (isset($this->config['enable_select_el']) && $this->config['enable_select_el']) {
								$checkbox_enable = $this->config['enable_select_el']($node);
							}
					?>
							<td class="nodechbox nonTextEl">
								<input type="checkbox" class="chbox" name="did[]" value="<?=$node['id']?>" <?=($checkbox_enable ? '' : 'disabled="disable"')?> autocomplete="off" />
							</td>
					<?php endif; ?>

					<?php foreach ($this->config['data_nodes_cfg'] as $ii => $dncfg):

						if ($ii == 0 && $this->config['type'] === 'tree'):
							// иконки
							$icon_def = true;
							$empty_icon = "images/but/usr.gif";
							$folder_icon = "images/but/fld1.gif";
							$open_folder_icon = "images/but/open_folder.gif";

							if (isset($this->config['tree_icon_elements']) && is_array($this->config['tree_icon_elements'])) {
								$ic = $this->config['tree_icon_elements']['func']($node);

								if (is_string($ic) && is_array($this->config['tree_icon_elements']['icons'][$ic])) {
									$icns = $this->config['tree_icon_elements']['icons'][$ic];

									$icon_def = false;
									$empty_icon = $icns['empty'];
									$folder_icon = $icns['folder'];
									$open_folder_icon = $icns['open_folder'];
								}
							}

							// сдвиги нод в дереве
						?>
							<td
								class="nodetd"
								style="padding-left:<?=($node['level'])*TREE_NODE_INDENT?>px;"
								<?=(!$icon_def) ?
									' emic="' . $empty_icon . '" fic="' . $folder_icon . '" ofic="' . $open_folder_icon . '" '
									:
									""
								?>
							>
								<?php if ($node['has_children']): ?>
									<a onclick="triggerNode(<?=$this->config['id']?>_cnstr, <?=$node['id']?>)">
										<img id="foldimage<?=$node['id']?>" src="<?=$folder_icon?>" border=0 alt="">
									</a>
								<?php else: ?>
									<img src="<?=$empty_icon?>" border=0 alt="">
								<?php endif; ?>
								&nbsp;&nbsp;<?=$dncfg['func']($node);?>
							</td> <?php
						else: ?>
							<td class="nodetd txt<?=$dncfg['align']?>"><?=$dncfg['func']($node);?></td> <?php
						endif;
					endforeach; ?>

					<!-- Действия над нодами -->
					<?php if($this->config['action_nodes']): ?>
					<td class="nodetd txtcenter nodesaction">
						<?php foreach ($this->config['action_nodes_cgf'] as $ancfg): ?>
							<?php $dop_ch = true;
								if (isset($ancfg['check_func']) && $ancfg['check_func']) {
									$dop_ch = $ancfg['check_func']($node);
								}
								if ($this->_auth->aclCheck(($ancfg['res_innod'] ? $node['res_id'] : $this->config['resID']), $ancfg['acl_rule']) && $dop_ch):
								if (!isset($ancfg['make_href']) || !$ancfg['make_href']) {
									$href = ($ancfg['has_id']) ? $this->_setNodeId($ancfg, $node['id']) : $ancfg['href'];
								} else {
									$href = $ancfg['make_href']($node);
								}
								?>
								<a href="<?=$href?>" <?=(isset($ancfg['confirm']) && $ancfg['confirm']) ? 'onClick="return confirm(\'' . $ancfg['confirm'] . '\')"' : ''?>>
									<img src="<?=$ancfg['img_src']?>" border=0 title="<?=_($ancfg['title']);?>" alt="<?=_($ancfg['title']);?>">
								</a>&nbsp;
							<?php endif ?>
						<?php endforeach; ?>
					</td>
					<?php endif; ?>
				</tr> <?php
				endforeach;
			endif;
		}

		/**
		 * Отображение пустого блока оформления
		 */
		protected function _showEmptySpacer() {
			?>
			<tr class="spacer">
				<td colspan=<?=$this->_colspan?> class="nonTextEl"></td>
			</tr>
			<?php
		}

		/**
		 * Отображение блока основных действий
		 */
		protected function _showActionBlock() {
			if (count($this->config['main_action_block']) > 0): ?>
				<tr>
					<td colspan=<?=$this->_colspan?> class="action_title">
						<span><?=_("Действия")?></span>
					</td>
				</tr>
				<?=$this->_showEmptySpacer()?>
				<!-- Основные действия -->
				<tr>
					<td colspan=<?=$this->_colspan?> class="action_node">
						<ul class="t_virab_action_table">
							<?php foreach ($this->config['main_action_block'] as $mab): ?>
								<?php if ($this->_auth->aclCheck($this->config['resID'], $mab['acl_rule'])): ?>
									<li><a href="<?=$mab['href']?>" <?=(isset($mab['confirm']) && $mab['confirm']) ? 'onClick="if (confirm(\'' . $mab['confirm'] . '\')) {' . ($mab['href'] ===  '#' ? 'form.submit(); return false;' : '__sendTableForm(\'' . $mab['href'] . '\'); return false;') . '} else {return false;}"' : ''?>><?=_($mab['html']);?></a></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</td>
				</tr>
			<?php endif;
		}

		/**
		 * Отображение пэйджера записей
		 */
		protected function _showPager() {
			if ($this->config['type'] === 'list' && !$this->config['hide_pager']) {

				if ($this->config['pager'] && is_object($this->config['pager'])) {
					$this->config['pager']->setURL($this->config['url']);
					?>
					<tr>
						<td colspan=<?=$this->_colspan?> class="t_table_pager">
							<table class="t_virab_tree">
								<tr>
									<td style="text-align: left; width: 15%"><?=$this->config['pager']->getRecordCountStr()?></td>
									<td style="text-align: center;"><?=$this->config['pager']->getPagerStr()?></td>
									<td style="text-align: right; width: 20%"><?=$this->config['pager']->getCountRecordsOnPageStr()?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php
				} else {
					?>
					<tr>
						<td colspan=<?=$this->_colspan?> class="t_table_pager">
							<table class="t_virab_tree">
								<tr>
									<td style="text-align: left;"><?=leftPageStr()?></td>
									<td style="text-align: center;"><?=centerPageStr($this->config['url'])?></td>
									<td style="text-align: right;"><?=rightPageStr($this->config['url'])?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php
				}
			}
		}

		/**
		 * Подстановка id ноды в url
		 *
		 * @param string $url
		 * @param int $nodeId
		 * @return string
		 */
		private function _setNodeId($cfg, $nodeId) {
			$idt = (isset($cfg['url_id']) && $cfg['url_id']) ? $cfg['url_id'] : 'id';
			return str_replace($idt . '=0', $idt . '=' . $nodeId, $cfg['href']);
		}
	}
