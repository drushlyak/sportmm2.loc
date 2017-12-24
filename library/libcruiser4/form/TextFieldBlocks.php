<?php

	/**
	 * @package DspHelper
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @version 0.55
	 * @copyright Cruiser cruiser.com.ua
	 */

	// =====================================================================================================
	// Текстовые поля
	// =====================================================================================================

	/**
	 * Обычное текстовое поле без ввода [TFTEXT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID поля
	 * 		string label Название поля
	 * 		mix value Значение
	 * 		array textblock Массив со значениями стартовой и конечной строчки оформления поля: array('begin' => '', 'end' => '')
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFText extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('id', $this->getRndId());

			$id = $this->config['id'];

			$this->config['textblock'] = (array) $this->config['textblock'];
			$this->setDefaultValue('type', 'text');
			?>
				<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
				<?php if ($this->config['textblock']['begin']): ?>
					<span class="txtbegin"><?=$this->config['textblock']['begin']?></span><?php endif; ?><strong><?=$this->modificationValueString($this->config['value'])?></strong>
				<?php if ($this->config['textblock']['end']): ?>
					<span class="txtend"><?=$this->config['textblock']['end']?></span>
				<?php endif; ?>
				<br />
				<?php if(!$this->isManualBrSet()): ?>
					<br />
				<?php endif; ?>
			<?php
		}
	}

	/**
	 * Обычное текстовое поле ввода [TFTEXTFIELD]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID поля
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		mix value Значение
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array textblock Массив со значениями стартовой и конечной строчки оформления поля:
	 * 			array(
	 * 				'begin' => '',
	 * 				'end' => '',
	 * 				'addinresult' => true // флаг добавления этих значений в value
	 * 			)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * 		string allowContentCheck Регэксп проверки правильности ввода символов ВСЕГО поля, например '^[a-zA-Z0-9_-]+$'
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFTextField extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('id', $this->getRndId("tVhidden_", 3));
			$this->config['textblock'] = (array) $this->config['textblock'];
			$this->setDefaultValue('type', 'text');

			$id = $this->config['id'];

			if (!$this->config['textblock']['addinresult']):
				?>
					<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
					<?php if ($this->config['textblock']['begin']): ?>
						<span class="txtbegin"><?=$this->config['textblock']['begin']?></span><?php endif; ?><input
							id="<?=$id?>"
							type="<?=$this->config['type']?>"
							name="<?=$this->config['name']?>"
							value="<?=$this->modificationValueString($this->config['value'])?>"
							class="textfield <?=$this->config['cls']?>"
							style="width: <?=$this->config['width']?>px"
							autocomplete="off"
							<?=$this->genParamStr();?>
						/>
					<?php if ($this->config['textblock']['end']): ?>
						<span class="txtend"><?=$this->config['textblock']['end']?></span>
					<?php endif; ?>
					<?php if(!$this->isManualBrSet()): ?>
						<br />
					<?php endif; ?>
				<?php
			else:
				// добавление текстовых блоков в результат поля
				?>
					<input
						type="hidden"
						name="<?=$this->config['name']?>"
						value="<?=(string) $this->config['textblock']['begin'] . $this->modificationValueString($this->config['value']) . (string) $this->config['textblock']['end']?>"
					>
					<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
					<?php if ($this->config['textblock']['begin']): ?>
						<span class="txtbegin <?=$id?>_b"><?=$this->config['textblock']['begin']?></span><?php endif; ?><input
							id="<?=$id?>"
							type="<?=$this->config['type']?>"
							name="<?=$this->config['name']?>_fake"
							value="<?=$this->modificationValueString($this->config['value'])?>"
							class="textfield <?=$this->config['cls']?>"
							style="width: <?=$this->config['width']?>px"
							autocomplete="off"
							<?=$this->genParamStr();?>
						/>
					<?php if ($this->config['textblock']['end']): ?>
						<span class="txtend <?=$id?>_e"><?=$this->config['textblock']['end']?></span>
					<?php endif; ?>
					<?php if(!$this->isManualBrSet()): ?>
						<br />
					<?php endif; ?>
					<script type="text/javascript">
						$(function() {
							var idd = '<?php echo $id ?>',
								namehidden = '<?php echo $this->config['name'] ?>';

							$("#" + idd).bind('keyup change click', function() {
								var inp = $("input[name='" + namehidden + "']"),
									startwrap = inp.siblings('.' + idd + '_b').html() || "",
									endwrap = inp.siblings('.' + idd + '_e').html() || "";

								inp.val(
									(startwrap + this.value + endwrap)
								);
							});
						});
					</script>
				<?php
			endif;
			if (!empty($this->config['allowContentCheck'])) {
				?>
				<script type="text/javascript">
				$(function() {
					var idd = '<?php echo $id ?>',
						regEx = /<?php echo $this->config['allowContentCheck'] ?>/,
						chF = function(val) {
							var restr = "";
							for ( var i = 0, len = val.length; i < len; i++) {
								restr += regEx.test(val[i]) ? val[i] : "";
							}

							return restr;
						};

					$("#" + idd)
						.bind('keyup blur', function() {
							if (!regEx.test(this.value)) {
								this.value = chF(this.value);
							}
						})
						.bind('keypress', function(e) {
							var code = e.charCode || e.keyCode || e.which;
							if ((code != 9 && code != 13 && code != 8 && code != 46 && code != 44)) {
								return regEx.test(String.fromCharCode( code ));
							}
							return true;
						});
				});
				</script>
				<?php
			}
		}
	}

	/**
	 * Текстовое поле с поддержкой многоязыкового ввода [TFTEXTFIELDLNG]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		string or array (lng) value Значение
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFTextFieldLng extends VirabTFFieldLng {

		/**
		 * Отображение блока
		 */
		public function show() {
			if (!is_array($this->config['value'])) {
				$this->config['value'] = $this->lng->get_all($this->config['value']);
			}
			?>
				<label><?=$this->config['label']?><br /></label>
				<input type="hidden" name="<?=$this->config['name']?>[msgid]" value="<?=$this->config['value']['msgid']?>">
				<?php foreach ($this->lng->lng_array as $lang):
						$class = ($lang['id'] != $this->lng->now_lng) ? "hidden_field" : "";
				?>
					<input 	name="<?=$this->config['name']?>[<?=$lang['id']?>]"
							type="text"
							class="flng<?=$lang['ind_name']?> textfield <?=$class?> <?=$this->config['cls']?>"
							value="<?=$this->modificationValueString($this->config['value'][$lang['id']])?>"
							style="width: <?=$this->config['width']?>px"
							autocomplete="off"
							<?=$this->genParamStr();?> />
					<?php if(!$this->isManualBrSet()): ?>
						<br class="brflng<?=$lang['ind_name']?> <?=$class?>" />
					<?php endif; ?>
				<?php endforeach;
		}
	}

	/**
	 * Textarea [TFTEXTAREA]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *		string name Наименование
	 * 		string label Название поля
	 * 		int cols Количество колонок
	 * 		int rows Количество столбцов
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		string value Текст
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFTextarea extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$id = $this->getRndId();

			$this->config['id'] = $id;

			?>
				<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
				<textarea 	id="<?=$id?>"
							name="<?=$this->config['name']?>"
							cols="<?=$this->config['cols']?>"
							rows="<?=$this->config['rows']?>"
							class="textfield <?=$this->config['cls']?>"
							style="width: <?=$this->config['width']?>px"
							<?=$this->genParamStr();?>><?=$this->modificationValueString($this->config['value'])?></textarea><?php
					if(!$this->isManualBrSet()): ?><br /><?php endif; ?>
			<?php
		}
	}

	/**
	 * Textarea c поддержкой языков [TFTEXTAREALNG]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *		string name Наименование
	 * 		string label Название поля
	 * 		int cols Количество колонок
	 * 		int rows Количество столбцов
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		string value Текст
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFTextareaLng extends VirabTFFieldLng {

		/**
		 * @var array ID textareas
		 */
		protected $textareaIDs = array();

		/**
		 * Отображение блока
		 */
		public function show() {
			if (!is_array($this->config['value'])) {
				$this->config['value'] = $this->lng->get_all($this->config['value']);
			}
			?>
				<label><?=$this->config['label']?><br /></label>
				<input type="hidden" name="<?=$this->config['name']?>[msgid]" value="<?=$this->config['value']['msgid']?>">
				<?php foreach ($this->lng->lng_array as $lang):
					$id = $this->getRndId();
					array_push($this->textareaIDs, array('id' => $id, 'lng' => $lang['ind_name']));
					$class = ($lang['id'] != $this->lng->now_lng) ? "hidden_field" : "";
				?>
					<textarea 	id="<?=$id?>"
								name="<?=$this->config['name']?>[<?=$lang['id']?>]"
								cols="<?=$this->config['cols']?>"
								rows="<?=$this->config['rows']?>"
								class="flng<?=$lang['ind_name']?> textfield <?=$class?> <?=$this->config['cls']?>"
								style="width: <?=$this->config['width']?>px"
								<?=$this->genParamStr();?>><?=$this->modificationValueString($this->config['value'][$lang['id']])?></textarea><br class="brflng<?=$lang['ind_name']?> <?=$class?>" />
				<?php endforeach;
		}
	}

	/**
	 * Textarea c редактором (с подсветкой) [TFTEXTAREAEXT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *		string name Наименование
	 * 		string label Название поля
	 * 		int cols Количество колонок
	 * 		int rows Количество столбцов
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		string value Текст
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFTextareaExt extends VirabTFTextarea {

		/**
		 * Отображение блока
		 */
		public function show() {
			parent::show();

			?>
				<script type="text/javascript">
					FormsNS.VirabTFTextareaExt.set(
						'<?php echo $this->config['id'] ?>'
					);
				</script>
			<?php
		}
	}

	/**
	 * Textarea c поддержкой языков и редактором (с подсветкой) [TFTEXTAREALNGEXT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *		string name Наименование
	 * 		string label Название поля
	 * 		int cols Количество колонок
	 * 		int rows Количество столбцов
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		string value Текст
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFTextareaLngExt extends VirabTFTextareaLng {

		/**
		 * @var JSON экзепляр JSON
		 */
		protected $json;

		/**
		 * Отображение блока
		 */
		public function show() {
			parent::show();

			throw new Exception("Textarea c поддержкой языков и редактором пока не работает!");

			$this->json = new JSON(JSON_LOOSE_TYPE);
			$ids = $this->json->encode($this->textareaIDs);

			$hashID = $this->getRndId('TA', 4); // для отделения функционала от других копий VirabTFTextareaLngExt
			// подключение редактора с подсветкой
			?>
				<script type="text/javascript">
					FormsNS.VirabTFTextareaLngExt.set(
						<?php echo $ids ?>,
						'<?php echo $hashID ?>'
					);
				</script>
			<?php
		}
	}

	/**
	 * WYSIWYG редактор [TFWYSIWYG]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *  	string name Наименование
	 * 		string label Название поля
	 * 		string value Значение
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		int height Значение высоты поля
	 * 		string stylesheet Путь к stylesheet file
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFWYSIWYG extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
		?>
			<label><?=$this->config['label']?><br /></label>
			<textarea style="display:none" name="<?=$this->config['name']?>"><?=$this->config['value']?></textarea>
			<?php
				$spaw = new SpawEditor($this->config['name'], $this->config['value'], '', '', 'spaw2lite', $this->config['width'], $this->config['height'], $this->config['stylesheet'], '');
				$spaw->show();
		}
	}

	/**
	 * WYSIWYG редактор с поддержкой языков [TFWYSIWYGLNG]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 *  	string name Наименование
	 * 		string label Название поля
	 * 		string or array (lng) value Значение
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		int height Значение высоты поля
	 * 		string stylesheet Путь к stylesheet file
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFWYSIWYGLNG extends VirabTFFieldLng {

		/**
		 * @var JSON экзепляр JSON
		 */
		protected $json;

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->json = new JSON(JSON_LOOSE_TYPE);

			if (!is_array($this->config['value'])) {
				$this->config['value'] = $this->lng->get_all($this->config['value']);
			}

			?>
				<label><?=$this->config['label']?><br /></label>
				<input type="hidden" name="<?=$this->config['name']?>[msgid]" value="<?=$this->config['value']['msgid']?>">
				<?php

					$vals = array();
					foreach ($this->lng->lng_array as $lang) {
						if ($lang['id'] == $this->lng->now_lng) {
							$def_tab_name = $lang['ind_name'];
							$def_name = $this->config['name'] . '[' . $lang['id'] . ']';
							$def_txt = $this->config['value'][$lang['id']];
						} else {
							$vals[] = array(
								'name' => $this->config['name'] . '[' . $lang['id'] . ']',
								'tab_name' => $lang['ind_name'],
								'txt' => $this->config['value'][$lang['id']]
							);
						}
					}

					$ta_array = array();

					// SpawEditor -> $name, $value='', $lang='', $toolbarset='', $theme='', $width='', $height='', $stylesheet='', $page_caption=''
					$spaw = new SpawEditor($def_name, $def_txt, '', '', 'spaw2lite', $this->config['width'], $this->config['height'], $this->config['stylesheet'], $def_tab_name);
					$defobj = $spaw->getActivePage()->name;

					$ta_array['obj_el'] = $defobj . "_obj";
					$ta_array[$def_tab_name] = $defobj;

					// Add language page
					foreach ($vals as $txt) {
						$sep = new SpawEditorPage($txt['name'], $txt['tab_name'], $txt['txt']);
						$spaw->addPage($sep);
						$ta_array[$txt['tab_name']] = $sep->name;
					}
					$spaw->show();

					// Сохраним информацию о VirabTFWYSIWYGLNG элементах
					$ta_array = $this->json->encode($ta_array);
					?>
						<script type="text/javascript">
							var taArray = <?=$ta_array?>;
							VirabTFWYSIWYG.push(taArray);

							for ( var ta in taArray) {
								if (ta !== 'obj_el') {
									$("#" + taArray[ta]).bind("change focus blur select", function() {
										this.value = this.value.replace(
											/<br \/>[^<]*<div id="_firebugConsole"[^>]*><\/div>/img,
											""
										);
									});
								}
							}
						</script>
						<?php if(!$this->isManualBrSet()): ?>
							<br />
						<?php endif; ?>
					<?php
		}
	}

	/**
	 * Поле пароля с проверкой [TFPASSWORDFIELD]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		mix value Значение
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFPasswordField extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$id = $this->getRndId();
			$this->config['textblock'] = (array) $this->config['textblock'];
			?>
				<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
				<input 	id="<?=$id?>"
						type="password"
						name="<?=$this->config['name']?>"
						value="<?=$this->config['value']?>"
						class="textfield <?=$this->config['cls']?>"
						style="width: <?=$this->config['width']?>px; margin-bottom:0"
						autocomplete="off"
						<?=$this->genParamStr();?> />
				<div style="background-color:#E8E8E8;height:10px;margin-left:13.5em;width:<?=($this->config['width']-4)?>px;"></div>
				<?php $id = $this->getRndId(); ?>
				<label for="<?=$id?>"><?=_("Повторный ввод пароля")?><?=$this->genMarkRequired()?>:<br /></label>
				<input 	id="<?=$id?>"
						type="password"
						name="<?=$this->config['name']?>_check"
						value="<?=$this->config['value']?>"
						class="textfield <?=$this->config['cls']?>"
						style="width: <?=$this->config['width']?>px"
						autocomplete="off"
						<?=$this->genParamStr();?> />
				<?php if(!$this->isManualBrSet()): ?>
					<br />
				<?php endif; ?>
			<?php
		}
	}

	/**
	 * Checkbox [TFCHECKBOX]
	 *
	 * см. VirabTFTextField
	 *
	 * @package DspHelper
	 */
	class VirabTFCheckbox extends VirabTFTextField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$name = $this->config['name'];
			$this->config['name'] = $this->config['name'] . "_" .  $this->getRndId("", 3);
			$this->config['type'] = 'checkbox';
			$this->setDefaultValue('params', array());
			$this->config['value'] ? ($this->config['params']['checked'] = 'on') : null;
			$val = $this->config['value'] ? 1 : 0;
			$this->config['value'] = 1;
			$this->config['width'] = 'auto';
			$this->config['cls'] = "checkbox_" . $this->getRndId("", 3);
			$this->config['textblock']['addinresult'] = false;

			parent::show();
			$cbcls = $this->config['cls'];
			?>
				<input id="<?=$cbcls?>" type="hidden" name="<?=$name?>" value="<?=$val?>" />
				<script type="text/javascript">
					$(".tvirabForm .<?=$cbcls?>").bind("change", function() {
						$("#" + "<?=$cbcls?>").get(0).value = this.checked ? 1 : 0;
					});
				</script>
			<?php
		}
	}

	/**
	 * Group checkboxs [TFCHECKBOXGROUP]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID элемента
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		array options Массив значений вида array ( 'Наименование параметра' => 'Название параметра' ) (example: ( 'is_folder' => 'Флаг каталога', 'remove_node' => 'Удалить ноду' ))
	 * 		array selected Массив выделенных значений
	 * 		boolean blockUncheckSelected Флаг блокирования снятия метки cheched c уже выделенных селектбоксов
	 *
	 * 	После sumbit-a на backend-e результат вида: 'test' => array( 'is_folder' => 1, 'remove_node' => 1 ), где test - Наименование TFCHECKBOXGROUP, а is_folder и т.д. - отмеченное selectbox поле
	 * 	Неотмеченные поля в результирующий массив не попадают!
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFCheckboxGroup extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('id', $this->getRndId());

			$this->setDefaultValue('options', array());
			$this->setDefaultValue('selected', array());
			$this->setDefaultValue('blockUncheckSelected', false);

			$id = $this->config['id'];
			?>
				<label for="<?=$id?>"><?=$this->config['label']?><br /></label>
				<ul class="CheckboxGroupUL">
					<?php foreach ($this->config['options'] as $key => $value):
						$sel = in_array($key, $this->config['selected']) ? " checked " : "";
						$dis = ($this->config['blockUncheckSelected'] && $sel) ? " disabled " : "";
					?>
						<li><input type="checkbox" class="<?=(($this->config['blockUncheckSelected'] && $sel) ? "hhideDisabled" : "")?>" name="<?=$this->config['name']?>[<?=$key?>]" value="1" <?=$sel?> <?=$dis?> />&nbsp;<?=$this->modificationValueString($value)?></li>
					<?php endforeach; ?>
				</ul>
				<?php if(!$this->isManualBrSet()): ?>
					<br class="floatNone" />
				<?php endif; ?>
			<?php
		}
	}

	/**
	 * Autosuggest field (поле с поиском) [TFAUTOSUGGESTFIELD]
	 * [использует autosuggest.js версии 2.1.3 author: Timothy Groves - http://www.brandspankingnew.net]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID поля
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		array value Значение в формате array( 'id' => 1, 'name' => 'Наименование значения' )
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * 		string backend URL backend части для запросов
	 * 		string varname имя переменной для GET запроса
	 * 		boolean shownoresults отображать "пустой" результат
	 * 		int maxresults количество выводимых результатов
	 * 		array addparam дополнительные параметры в GET запрос
	 * 		string callback функция вызова при нахождении
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFAutosuggestField extends VirabTFField {

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('id', $this->getRndId("tVhidden_", 3));

			$this->setDefaultValue('varname', 'input');
			$this->setDefaultValue('shownoresults', false);
			$this->setDefaultValue('maxresults', 10);
			$this->setDefaultValue('callback', "");

			$id_hf = $this->config['id'];
			$id_mf = $this->config['id'] . "_input";

			$addparam = "?limit={$this->config['maxresults']}&";
			if (is_array($this->config['addparam'])) {
				$params = array();
				foreach ($this->config['addparam'] as $name => $value) {
					$params[] = $name . "=" . $value;
				}
				$addparam = $addparam . join("&", $params) . "&";
			}

			if (is_array($this->config['value'])) {
				$value_val = $this->config['value']['id'];
				$value_name = $this->config['value']['name'];
			} else {
				$value_val = "";
				$value_name = "";
			}

			?>
				<input id="<?=$id_hf?>" type="hidden" name="<?=$this->config['name']?>" value="<?=$value_val?>" />
				<label for="<?=$id_mf?>"><?=$this->config['label']?><br /></label>
				<input 	id="<?=$id_mf?>"
						type="text"
						value="<?=$this->modificationValueString($value_name)?>"
						class="textfield <?=$this->config['cls']?> autosuggestTextField"
						style="width: <?=$this->config['width']?>px"
						autocomplete="off"
						<?=$this->genParamStr();?> />
				<?php if(!$this->isManualBrSet()): ?>
					<br />
				<?php endif; ?>

				<script type="text/javascript">
					var options = {
						script: "<?php echo $this->config['backend'] . $addparam; ?>",
						varname: "<?php echo $this->config['varname']; ?>",
						json: true,
						shownoresults: <?php echo $this->config['shownoresults'] ? "true" : "false"; ?>,
						maxresults: <?php echo $this->config['maxresults']; ?>,
						cache: false,
						noresults: '<?=_("По запросу ничего не найдено")?>',
						callback: function (obj) {
							document.getElementById('<?php echo $id_hf?>').value = obj.id;
							<?=(!empty($this->config['callback']) ? ($this->config['callback'] . "(obj);") : "")?>
						}
					};
					new _b.AutoSuggest('<?=$id_mf?>', options);
				</script>
			<?php
		}
	}

	/**
	 * Текстовое поле с функцией проверки дублирования [TFTEXTFIELDWITHCHECKPRESENT]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		mix value Значение
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array textblock Массив со значениями стартовой и конечной строчки оформления поля: array('begin' => '', 'end' => '')
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * 		string checkCall Путь к файлу, которому будет передаваться значение поля на проверку. Он возвращает boolean (уже используется - false, нет - true)
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFTextFieldWithCheckPresent extends VirabTFTextField {

		/**
		 * Отображение блока
		 */
		public function show() {
			parent::show();

			if ($this->config['checkCall']) {
				?>
					<script type="text/javascript">
						$(function() {
							FormsNS.TextFieldWithCheckPresent.set(
								'<?php echo $this->config['id'] ?>',
								'<?php echo $this->config['checkCall'] ?>',
								'<?php echo $this->config['name'] ?>'
							);
						});
					</script>
				<?php
			}
		}
	}

	/**
	 * Datepicker [TFDATEPICKER]
	 * [использует datepicker.js Author: Stefan Petre www.eyecon.ro. Автозагрузка]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID поля
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		mix value Значение в формате "d.m.Y"
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFDatePicker extends VirabTFField {

		/**
		 * Конструктор класса
		 *
		 * @param array $config
		 */
		public function __construct($config) {
			$this->config = $config;
			$this->setDefaultValue('width', 90);

			parent::__construct($this->config);
		}

		/**
		 * Отображение блока
		 */
		public function show() {
			$this->setDefaultValue('id', $this->getRndId("tVDatePicker_", 3));
			?>
				<label for="<?=$this->config['id']?>"><?=$this->config['label']?><br /></label>
				<input 	id="<?=$this->config['id']?>"
						type="text"
						name="<?=$this->config['name']?>"
						value="<?=$this->modificationValueString($this->config['value'])?>"
						class="textfield <?=$this->config['cls']?>"
						style="width: <?=$this->config['width']?>px"
						autocomplete="off"
						<?=$this->genParamStr();?> />
				<?php if(!$this->isManualBrSet()): ?>
					<br />
				<?php endif; ?>

				<script type="text/javascript">
					FormsNS.formElsInitFnStorage['<?=$this->config['id']?>'] = function() {
						var el = $('#<?=$this->config['id']?>');

						<?=$this->jsInitPart()?>
					}

					$(function() {
						if (typeof window.__datePickerInit === 'undefined') {
							window.__datePickerInit = true;
							FormsNS.loadScriptAndCSS({
								css: '/virab/css/datepicker.css',
								js: '/virab/js/datepicker.js',
								callback: function() {
									FormsNS.formElsInitFnStorage['<?=$this->config['id']?>']();
								}
							});
						} else {
							$(document).one('datePickerReady', function() {
								FormsNS.formElsInitFnStorage['<?=$this->config['id']?>']();
							});
						}

						FormsNS.createEraseButton('#<?=$this->config['id']?>');
					});
				</script>
			<?php
		}

		protected function jsInitPart() {
			return "
				el.DatePicker({
					format: 'd.m.Y',
					date: new Date(),
					current: new Date(),
					starts: 1,
					onBeforeShow: function() {
						var val = el.val();

						if (val == '') val = '" . date("d.m.Y") . "'
						el.DatePickerSetDate(
							val,
							true
						);
					},
					onChange: function(formated) {
						el.val(formated);
					},
					locale: {
						days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Субота', 'Воскресенье'],
						daysShort: ['Вск', 'Пнд', 'Втр', 'Срд', 'Чтв', 'Пят', 'Суб', 'Вск'],
						daysMin: ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'],
						months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
						monthsShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
						weekMin: ''
					}
				});
			";
		}
	}


	/**
	 * Datepicker диапазона дат [TFDATEPICKERRANGE]
	 * [использует datepicker.js Author: Stefan Petre www.eyecon.ro. Автозагрузка]
	 *
	 * <pre>
	 * Значения массива конфигурации:
	 * 		string id ID поля
	 * 		string name Наименование
	 * 		string label Название поля
	 * 		int calendarsCount (по-умолчанию - 3 штуки)
	 * 		mix value Значение в формате "d.m.Y - d.m.Y"
	 * 		int width Значение ширины поля, по умолчанию 300 (px)
	 * 		array params Дополнительные параметры поля (по-умолчанию array())
	 * </pre>
	 *
	 * @package DspHelper
	 */
	class VirabTFDatePickerRange extends VirabTFDatePicker {

		/**
		 * Конструктор класса
		 *
		 * @param array $config
		 */
		public function __construct($config) {
			$this->config = $config;
			$this->setDefaultValue('width', 170);

			parent::__construct($this->config);
		}

		/**
		 * Отображение блока
		 */
		public function show() {
			/*
			if (!preg_match('/[\d]{2}.[\d]{2}.[\d]{4} - [\d]{2}.[\d]{2}.[\d]{4}/im', $this->config['value'], $regs)) {
				$this->config['value'] = date('d.m.Y') . " - " . date('d.m.Y');
			}
			*/
			$this->setDefaultValue('calendarsCount', 3);

			parent::show();
		}

		protected function jsInitPart() {
			return "
				el.DatePicker({
					format: 'd.m.Y',
					date: new Date(),
					current: new Date(),
					calendars: {$this->config['calendarsCount']},
					mode: 'range',
					starts: 1,
					onBeforeShow: function() {
						var val = el.val();

						if (val == '') val = '" . date("d.m.Y") . " - " . date("d.m.Y") . "'

						el.DatePickerSetDate(
							FormsNS.parseDateRange(val),
							true
						);
					},
					onChange: function(formated) {
						el.val(formated.join(' - '));
					},
					locale: {
						days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Субота', 'Воскресенье'],
						daysShort: ['Вск', 'Пнд', 'Втр', 'Срд', 'Чтв', 'Пят', 'Суб', 'Вск'],
						daysMin: ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'],
						months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
						monthsShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
						weekMin: ''
					}
				});
			";
		}
	}