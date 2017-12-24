<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @version 0.55
	 * @copyright Cruiser cruiser.com.ua
	 */

	// =====================================================================================================
	// Блоки, специфичные для проекта
	// =====================================================================================================


	/**
	 * Поле выбора и редактирования состава товара
	 */
	define('TFSELECTPRODUCTITEMWITHEDIT', 'VirabTFSelectProductItemWithEdit');

	/**
	 * Поле выбора и редактирования состава заказа
	 */
	define('TFSELECTPRODUCTWITHEDIT', 'VirabTFSelectProductWithEdit');

	/**
	 * Поле выбора и редактирования состава товара [TFSELECTPRODUCTITEMWITHEDIT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		string name Наименование
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectProductItemWithEdit extends VirabTFInteractiveAutosuggestSelectItem {

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

						// get article
						var match = /(.*) \(([\d\w\W]*)\)/i.exec(obj.value);

						if (match != null) {
							var name = match[1],
								article = match[2];
						} else {
							var name = "",
								article = "";
						}

						window._currentVirabTFISIEl['<?=$this->tableID?>'] = {
							id: obj.id,
							name: name,
							article: article,
							data: obj.data
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
					window['<?=$this->addFunctionName?>'] = function () {
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
									'<td>&nbsp;', current.article, '&nbsp;</td>',
									'<td>&nbsp;', current.name, '&nbsp;</td>',
									'<td>', '<input type="text" name="itemCountByID[', current.id ,']" value="1" />', '</td>',
									'<td>', '<select name="viewOptionByID[', current.id ,']"><option value="0">Не отображать</option><option value="1">Название</option><option selected="selected" value="2">Название/кол-во</option></select>', '</td>',
									'<td>',
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
		 * Получить заголовок таблицы данных
		 * @return string
		 */
		protected function getTableHeader() {
			?>
				<thead class="VirabTFISIHeader">
					<td>Артикул</td>
					<td>Заголовок</td>
					<td>Количество</td>
					<td>Отображение</td>
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
					$res[] = 	'<td>&nbsp;' . $val['article'] . '&nbsp;</td>';
					$res[] = 	'<td>&nbsp;' . $val['name'] . '&nbsp;</td>';
					$res[] = 	'<td><input type="text" name="itemCountByID[' . $val['id'] . ']" value="' . $val['count'] . '" /></td>';
					$res[] = 	'<td><select name="viewOptionByID[' . $val['id'] . ']">
										<option value="0" ' . ($val['viewOption'] == 0 ? 'selected="selected"' : '') . '>Не отображать</option>
										<option value="1" ' . ($val['viewOption'] == 1 ? 'selected="selected"' : '') . '>Название</option>
										<option value="2" ' . ($val['viewOption'] == 2 ? 'selected="selected"' : '') . '>Название/кол-во</option>
									</select>
								</td>';
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
	 * Поле выбора и редактирования состава товара [TFSELECTPRODUCTWITHEDIT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string label Название поля
	 * 		string name Наименование
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFSelectProductWithEdit extends VirabTFSelectProductItemWithEdit {

		/**
		 * Установка скрипта add Function
		 * @return void
		 */
		protected function setScriptAddFunctionBlock() {
			?>
				<script type="text/javascript">
					// функция расчета суммы
					window['calcSumm_<?=$this->tableID?>'] = function (data) {
						if (typeof data !== 'undefined' && data !== null) {
								return data.cost_excess * data.count;	
						} else {
							return 0;
						}
					}

					// функция обновления суммы при изменении количества
					window['countChange_<?=$this->tableID?>'] = function (el) {
						var ell = $(el),
							elID = parseInt(ell.attr("elID"), 10),
							count = parseInt(ell.val(), 10),
							cost_excess = parseInt(ell.attr("cost_excess"), 10);


						var sum = window['calcSumm_<?=$this->tableID?>']({
							'count': count,
							'cost_excess': cost_excess
						});

						ell.parent().parent()
							.find(".sumLine span")
								.html(sum)
							.end()
							.find(".sumhid")
								.val(sum);
					}

					// добавление нового продукта
					window['<?=$this->addFunctionName?>'] = function () {
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

						var current = window._currentVirabTFISIEl[el];

							current.data.count = 1;

						var add = [
								'<tr class="VirabTFISITR_ VirabTFISITR', current.id, '">',
									'<td class="centerText"><img src="', current.data.photo ,'" alt="" border="0" /></td>',
									'<td class="centerText">&nbsp;', current.article, '&nbsp;</td>',
									'<td>&nbsp;', current.name, '&nbsp;</td>',
									'<td class="centerText">',
										'<input type="text" name="<?=$this->config['name']?>[', current.id ,'][count]" style="width: 50px;" value="1" elID="', current.id ,'" cost_excess="', current.data.cost_excess ,'" onChange="countChange_<?=$this->tableID?>(this);" />',
									'</td>',
									'<input type="hidden" class="sumhid" name="<?=$this->config['name']?>[', current.id ,'][sum]" value="', window['calcSumm_<?=$this->tableID?>'](current.data), '">',
									'<td class="sumLine centerText"><span>', window['calcSumm_<?=$this->tableID?>'](current.data), '</span>&nbsp;руб.</td>',
									'<td class="centerText">',
										'<a href="#" title="Удалить" onclick="<?=$this->removeFunctionName?>(', current.id, '); return false;"><img src="images/but/trash.gif" border="0" alt="" /></a>',
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
		 * Получить заголовок таблицы данных
		 * @return string
		 */
		protected function getTableHeader() {
			?>
				<thead class="VirabTFISIHeader">
					<td>Фото</td>
					<td>Артикул</td>
					<td>Заголовок</td>
					<td>Количество</td>
					<td>Стоимость</td>
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
					$res[] = 	'<td class="centerText"><img src="' . $val['photo'] . '" alt="" border="0" /></td>';
					$res[] = 	'<td class="centerText">&nbsp;' . $val['article'] . '&nbsp;</td>';
					$res[] = 	'<td class="centerText">&nbsp;' . $val['name'] . '&nbsp;</td>';
					$res[] = 	'<td class="centerText"><input type="text" name="' . $this->config['name'] . '[' . $val['id'] . '][count]" style="width: 50px;" value="' . $val['count'] . '" elID="' . $val['id'] . '"  cost_excess="' . (($val['discount']) ? round($val['cost_excess'] - ($val['cost_excess']*$val['discount']/100) ) : $val['cost_excess']) . '" onChange="countChange_' . $this->tableID . '(this);" /></td>';
					$res[] = 	'<input type="hidden" class="sumhid" name="' . $this->config['name'] . '[' . $val['id'] . '][sum]" value="' . $this->calcSum($val) . '">';
					$res[] = 	'<td class="sumLine centerText"><span>' . (($val['discount']) ? round($val['cost_excess'] - ($val['cost_excess']*$val['discount']/100) ) : $val['cost_excess']) . '</span>&nbsp;руб.</td>';
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

		/**
		 * Расчет суммы
		 *
		 * @param array $data
		 * @param int $count
		 * @return string
		 */
		protected function calcSum($data) {
			$data['cost_excess'] = (($data['discount']) ? round($data['cost_excess'] - ($data['cost_excess']*$data['discount']/100) ) : $data['cost_excess']);	
			return $data['cost_excess'] * (int) $data['count'];	
		}
	}