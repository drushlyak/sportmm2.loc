/**
 * Namespace общих функций работы элементов форм
 * @type object
 */
var FormsNS = {

	/**
	 * Запись значения в поле с name
	 *
	 * @param string name
	 * @param string value
	 */
	setValueToInputName: function(name, value) {
		$('input[name="'+ name +'"]').val(value);
	},

	/**
	 * Установка даты в общее hidden поле
	 * (TFSELECTDATE)
	 */
	setDateToInput: function() {
		var name = $(this).attr('dname'),
			dayb = $('select[name="'+ name + '_day"]'),
			monthb = $('select[name="'+ name + '_month"]'),
			yearb = $('select[name="'+ name + '_year"]');

		var y = parseInt(yearb.val(), 10),
			m = parseInt(monthb.val(), 10),
			d = parseInt(dayb.val(), 10);

		if (!(y && m && d)) {
			y = 1900; m = 1; d = 1;
		}

		FormsNS.setValueToInputName(
			name,
			y + '-' + FormsNS.fillZero(m) + '-' + FormsNS.fillZero(d)
		);
	},

	/**
	 * Установка времени в общее hidden поле
	 * (TFSELECTTIME)
	 */
	setTimeToInput: function() {
		var name = $(this).attr('dname'),
			hourb = $('select[name="'+ name + '_hour"]'),
			minuteb = $('select[name="'+ name + '_minute"]'),
			secondb = $('*[name="'+ name + '_second"]');

		FormsNS.setValueToInputName(
			name,
			FormsNS.fillZero(hourb.val()) + ':' + FormsNS.fillZero(minuteb.val()) + ':' + FormsNS.fillZero(secondb.val())
		);
	},

	/**
	 * Установка даты-времени в общее hidden поле
	 * (TFSELECTDATETIME)
	 */
	setDateTimeToInput: function() {
		var name = $(this).attr('dname'),
			hourb = $('select[name="'+ name + '_hour"]'),
			minuteb = $('select[name="'+ name + '_minute"]'),
			secondb = $('*[name="'+ name + '_second"]'),
			dayb = $('select[name="'+ name + '_day"]'),
			monthb = $('select[name="'+ name + '_month"]'),
			yearb = $('select[name="'+ name + '_year"]');

		var y = parseInt(yearb.val(), 10),
			m = parseInt(monthb.val(), 10),
			d = parseInt(dayb.val(), 10);

		if (!(y && m && d)) {
			y = 1900; m = 1; d = 1;
		}

		FormsNS.setValueToInputName(
			name,
			(
				y + '-' + FormsNS.fillZero(m) + '-' + FormsNS.fillZero(d) +
				' ' +
				FormsNS.fillZero(hourb.val()) + ':' + FormsNS.fillZero(minuteb.val()) + ':' + FormsNS.fillZero(secondb.val())
			)
		);
	},

	/**
	 * Подстановка ведущего 0
	 * @param {int, string} val
	 * @return {string}
	 */
	fillZero: function(val) {
		val = parseInt(val, 10);
		if (val < 10) {
			return '0' + val;
		} else {
			return val;
		}
	},

	/**
	 * Триггер события формы
	 *
	 * @param {string} name
	 * @param {object} data
	 */
	triggerEvent: function(name, data) {
		$(document).trigger('FormsNS.event.' + name, data);
	},

	/**
	 * Постановка слушателя события
	 *
	 * @param {name} name
	 * @param {function} callback
	 */
	bindEvent: function(name, callback) {
		$(document).bind('FormsNS.event.' + name, callback);
	}
};

	// Инициализация lightbox превьюшек
	$(document).ready(function(){
		$("a[rel*='lightbox']").prettyPhoto({social_tools: false});		
	});

/**
 * Хранилище функций инициализации элементов
 */
FormsNS.formElsInitFnStorage = {};

/**
 *
 * @param object data
 * {
 *     js: 'path to js file',
 *     css: 'path to css file',
 *     callback: function() { ... callback after loading script ... }
 * }
 */
FormsNS.loadScriptAndCSS = function(data) {
	if (data['css']) {
		$("head").append("<link>");
		var css = $("head").children(":last");
		css.attr({
			rel:  "stylesheet",
			type: "text/css",
			href: data['css']
		});
	}

	if (data['js']) {
		$.getScript(data['js'], function(datad, textStatus) {
			if (data['callback']) {
				data['callback'].apply(this, arguments);
			}
		});
	}
}

/**
 * Преобразование строчки формата "d.m.Y - d.m.Y", в массив из двух дат
 * @param range
 */
FormsNS.parseDateRange = function(range) {
	var match = range.match(/([\d]{2}.[\d]{2}.[\d]{4}) - ([\d]{2}.[\d]{2}.[\d]{4})/im);
	if (match != null) {
		return [
			match[1],
			match[2]
		]
	} else {
		return [];
	}
}

/**
 * Действия при отправке любой формы
 *
 * @param {HtmlElement} form
 */
FormsNS.NecessaryOnSubmit = function(form) {
	if (typeof VirabTFWYSIWYG !== 'undefined') {
		// очищаем WYSIWYG элементы от "ненужных" элементов кода (внедрения firebug-a)
		$.each(VirabTFWYSIWYG, function() {
			window[this['obj_el']].updateFields();
		});
		$.each(VirabTFWYSIWYG, function() {
			$.each(this, function(key) {
				if (key !== 'obj_el') {
					var el = $("#" + this)[0];
					el.value = el.value.replace(
						/<br \/>[^<]*<div id="_firebugConsole"[^>]*><\/div>/img,
						""
					);
				}
			});
		});
		$.each(VirabTFWYSIWYG, function() {
			var objel = window[this['obj_el']],
				pages = objel.pages;

			for (var i = 0, len = pages.length; i < len; i++) {
				objel.updatePageDoc(pages[i]);
			}
		});
	}
}

/**
 * VirabTFSelect
 */
FormsNS.VirabTFSelect = function() {
	return {
		/**
		 * Установка поля
		 */
		set: function(id) {
			$('#' + id).change(function() {
				var currval = $(this).val(),
					currval = (currval === null) ? "" : currval;
				$('#' + id + '_hidden').val(currval);

				FormsNS.triggerEvent('VirabTFSelect' + id, {
					el: this,
					val: currval
				});
			});
			
			// установка значения выделенного элемента в hidden поле
			$('#' + id + '_hidden').val(
				$('#' + id + ' option:selected').val()
			);
		}
	}
}();

/**
 * VirabTFSelectLng
 */
FormsNS.VirabTFSelectLng = function() {
	return {
		/**
		 * Установка поля
		 */
		set: function(name) {
			var lang = this._getLngSelectArray(name),
				defval = lang[0].children('option:selected').val();

			$('input[name="' + name + '"]').val(defval)

			for (var i=0, len=lang.length; i < len; i++) {
				lang[i].change(function() {
					var currval = $(this).val(),
						currval = (currval === null) ? "" : currval;

					$('input[name="' + name + '"]').val(currval);
					for (var j=0, len=lang.length; j < len; j++) {
						lang[j].val(currval);
					}

					FormsNS.triggerEvent('VirabTFSelectLng' + name, {
						el: this,
						val: currval
					});
				});
			}
		},

		_getLngSelectArray: function(name) {
			var lang = [];

			for (var i = 0, len = LNG.length; i < len; i++) {
				lang.push($('select[name="' + name + '_field_' + LNG[i]['id'] + '"]'));
			}

			return lang;
		}
	}
}();

/**
 * VirabTFSelectTemplatePage
 */
FormsNS.VirabTFSelectTemplatePage = function() {
	return {
		/**
		 * Установка поля
		 */
		set: function(id, imageSet, emptyImg ) {
			$('#' + id).change(function() {
				var currval = $(this).val(),
					currval = (currval === null) ? "" : currval,
					pic = $('#' + id + "_preview_image");

				if (currval && typeof imageSet[currval] !== 'undefined' && imageSet[currval]['tmb_img']) {
					pic.attr('src', imageSet[currval]['tmb_img']);
				} else {
					pic.attr('src', emptyImg);
				}
			});

		}
	}
}();

/**
 * VirabTFTextareaExt
 */
FormsNS.VirabTFTextareaExt = function() {
	return {
		/**
		 * Установка поля EditArea
		 */
		set: function(id) {
			editAreaLoader.init({
				id : id,
				syntax: 'html',
				syntax_selection_allow: "html, css,js,php",
				start_highlight: true,
				display: 'later',
				language: 'ru',
				plugins: 'zencoding',
				toolbar: "search, go_to_line, |, undo, redo, |, syntax_selection, |, word_wrap, change_smooth_selection, highlight, reset_highlight, |, help",
				word_wrap: true,
				allow_resize: "y",
				EA_init_callback: 'FormsNS.VirabTFTextareaExt.setChanger'
			});
		},

		/**
		 * Новый переключатель
		 */
		setChanger: function(id) {
			$("#EditAreaArroundInfos_" + id)
				.css({
					'display' : 'block'
				})
				.children("div")
				.addClass('hidden_field');

			var inp = $("#" + id),
				inppos = inp.position();

			inp.before($(
				"<div id='" + id + "_cB' class='VirabTFTextareaExtChanger' title='Переключить редактор'>&lt;&gt;</div>"
			));
			$("#" + id + "_cB")
				.css({
					left: (inppos.left + inp.width() + 10),
					top: inppos.top
				})
				.bind('click', function() {
					eAL.toggle(id);
				});
		}
	}
}();

/**
 * VirabTFTextareaLngExt
 *
 * TODO
 */
FormsNS.VirabTFTextareaLngExt = function() {
	return {
		set: function(iDs, hashID) {
			// сохраним пару в хранилище для одновременного переключения
			VirabTFTextareaExt.push(iDs);

			/*
			// создаем уникальную функцию в глобальной области
			window['setSwitchElements' + hashID] = function() {
				var ids = iDs;
				for (var i = 0, len=ids.length; i < len; i++) {
					var el = $('#' + ids[i].id),
						has_hidden = el.hasClass('hidden_field');
					el.addClass('textarea_w_edit');
					$('#EditAreaArroundInfos_' + ids[i].id)
						.addClass('switchspan')
						.addClass('brflng' + ids[i].lng)
						[has_hidden ? "addClass" : "removeClass"]('hidden_field')
				}
			}
			*/

			for (var i = 0, len = iDs.length; i < len; i++) {
				/*
				editAreaLoader.init({
					id : iDs[i]['id'],
					syntax: 'html',
					syntax_selection_allow: "css,js,php",
					start_highlight: true,
					display: 'later',
					language: 'ru',
					plugins: 'zencoding',
					EA_init_callback: 'FormsNS.VirabTFTextareaLngExt.setChanger'
				});
				*/

			}
		},

		_getArrayLinkedElement: function(id) {
			for (var i = 0, len = VirabTFTextareaExt.length; i < len; i++) {

			}
		},

		setChanger: function(id) {
			$("#EditAreaArroundInfos_" + id).remove();

			var inp = $("#" + id);
			__(inp);
			//var inppos = inp.position();
/*
				inp.before($(
					"<div id='" + id + "_cB' class='TextFieldWithCheckPresentCheckButton' title='Переключить редактор'>&lt;&gt;</div>"
				));
				$("#" + id + "_cB")
					.css({
						left: (inppos.left + inp.width() + 15),
						top: inppos.top
					})
					.bind('click', function() {
						// eAL.toggle("tV_9c1ec121ac");
					});*/
		}
	}
}();

/**
 * VirabTFTextFieldWithCheckPresent
 */
FormsNS.TextFieldWithCheckPresent = function() {

	var checkStats = {
		empty: {
			sym : ' ',
			color: ''
		},
		yes: {
			sym : '√',
			color: '#5DE35D'
		},
		no: {
			sym : 'x',
			color: '#FF4343'
		}
	};

	return {
		/**
		 * Установка
		 */
		'set': function(id, url, name) {
			var inp = $("#" + id),
				inppos = inp.position();

			inp
				.before($(
					"<div id='" + id + "_cB' class='TextFieldWithCheckPresentCheckButton' title='Проверить'>↻</div>" +
					"<div id='" + id + "_checkRes' class='TextFieldWithCheckPresentCheckResult'>" + checkStats.empty.sym + "</div>"
				))
				.bind('blur', function() {
					this.check(id, url, name);
				}.cDelegate(this))
				.bind('keydown', function() {
					this._setDefState(id);
				}.cDelegate(this))

			$("#" + id + "_cB")
				.css({
					left: (inppos.left + inp.width() + 20),
					top: inppos.top
				})
				.bind('click', function() {
					this.check(id, url, name);
				}.cDelegate(this));

			$("#" + id + "_checkRes").css({
				left: (inppos.left + inp.width() + 37),
				top: inppos.top
			});
		},

		/**
		 * Проверка
		 */
		check: function(id, url, name) {
			var val = $("input[name='" + name + "']").val();

			if (val !== '') {
				$.getJSON(url + "?val=" + val, function (data) {
					data ?
						this._setNormalState(id) :
						this._setErrorState(id);
				}.cDelegate(this));
			} else {
				this._setErrorState(id);
			}
		},

		/**
		 * Установка состояния
		 */
		_setState: function(id, stateCfg) {
			var inp = $("#" + id),
				res = $("#" + id + "_checkRes");

			inp.css('background-color', stateCfg.color);
			res
				.html(stateCfg.sym)
				.css('color', stateCfg.color);
		},

		/**
		 * Ошибка
		 */
		_setErrorState: function(id) {
			this._setState(id, checkStats.no);
		},

		/**
		 * Нормальное состояние
		 */
		_setNormalState: function(id) {
			this._setState(id, checkStats.yes);
		},

		/**
		 * Дефолтное состояние
		 */
		_setDefState: function(id) {
			this._setState(id, checkStats.empty);
		}
	}
}();

/* Функции формы для страницы управления-редактирования шаблонов */
FormsNS.TemplateFormController = function() {

	var showCFG = {
		controlElementsCls: [
			'form_type_executor_block',
			'form_code_block',
			'form_picture_block',
			'form_executor_code_block',
			'form_executor_file_block',
			'form_executor_url_block',
			'form_selective_selector',
			'form_content_selector'
		],
		typeTemplate: {
			0: 'Default',		// По-умолчанию
			10: 'Page',
			20: 'NoType',
			30: 'Container',
			40: 'Executor_WYSIWYG',
			41: 'Executor_WYSIWYG',
			42: 'Executor_File',
			43: 'Executor_Code',
			44: 'Executor_URL',
			45: 'Executor_Simple',
			50: 'Container_Selective',
			60: 'Folder'
		},

		'Default': {
			show: [ 'form_code_block', 'form_selective_selector', 'form_content_selector' ]
		},
		'Page': {
			show: [ 'form_code_block', 'form_picture_block', 'form_content_selector' ]
		},
		'NoType': {
			show: [  ]
		},
		'Container': {
			show: [ 'form_code_block', 'form_selective_selector', 'form_content_selector' ]
		},
		'Executor_WYSIWYG': {
			show: [ 'form_type_executor_block', 'form_selective_selector', 'form_content_selector' ]
		},
		'Executor_File': {
			show: [ 'form_type_executor_block', 'form_executor_file_block', 'form_selective_selector', 'form_content_selector' ]
		},
		'Executor_Code': {
			show: [ 'form_type_executor_block', 'form_executor_code_block', 'form_selective_selector', 'form_content_selector' ]
		},
		'Executor_URL': {
			show: [ 'form_type_executor_block', 'form_executor_url_block', 'form_selective_selector', 'form_content_selector' ]
		},
		'Executor_Simple': {
			show: [ 'form_type_executor_block', 'form_selective_selector', 'form_content_selector' ]
		},
		'Container_Selective': {
			show: [ 'form_content_selector' ]
		},
		'Folder': {
			show: [  ]
		}
	};

	return {
		showFieldByTemplateType: function(tmpl_type, executor_type) {
			if (isNaN(executor_type)) {
				executor_type = 0;
			}
			if (isNaN(tmpl_type) || tmpl_type == 0) {
				tmpl_type = 1;
			}

			var tmpl_type = parseInt(tmpl_type, 10),
				executor_type = parseInt(executor_type, 10);

			if (tmpl_type !== 4) {
				executor_type = 0;
			}

			var type = parseInt(("" + tmpl_type + "" + executor_type), 10);

			this.hideAll();
			this.showByType( showCFG.typeTemplate[type] );
		},

		changeType: function(name_type_template, name_type_executor) {
			// делаем задержку из-за срабатывания других скриптов
			setTimeout(function() {
				var val_tt = parseInt($("input[name='" + name_type_template + "']")[0].value, 10),
					val_te = parseInt($("input[name='" + name_type_executor + "']")[0].value, 10);

				if (val_tt !== 4) {
					// для не Executor-ов обнуляем значение поля типа исполнителя
					val_te = 0;
				}

				this.showFieldByTemplateType( val_tt, val_te );
			}.cDelegate(this), 50);
		},

		hideAll: function() {
			for (var i = 0, len = showCFG.controlElementsCls.length; i < len; i++) {
				var cls = showCFG.controlElementsCls[i];
				$(".tvirabForm ." + cls).addClass('hidden_field');
			}
		},

		showByType: function(type) {
			var showArr = showCFG[type].show;

			for (var i = 0, len = showArr.length; i < len; i++) {
				var cls = showArr[i];
				$(".tvirabForm ." + cls)
					.removeClass('hidden_field')
					.removeClass('retarded_field')
					.removeClass('collapse_field');
			}
		},

		changeTypeTemplate: function(type_template_selector, template_name) {
			var val = type_template_selector.value;

			if (typeof $tVirab.CONFIG.TYPE_TE_VALUE[val] !== 'undefined') {
				$("input[name='" + template_name + "']")
					.siblings("span.txtbegin")
					.html(
						$tVirab.CONFIG.TYPE_TE_VALUE[val].firstchar + "_"
					)
					.prev()
					.siblings("input[name='" + template_name + "_fake']")
					.change()
					.siblings(".TextFieldWithCheckPresentCheckButton")
					.click();
			}
		}
	}
}();

/* Функции формы для страницы управления-редактирования меню сайта */
FormsNS.MenuFormController = function() {
	return {
		changeType: function(el) {
			var v = parseInt($(el).val(), 10);
			
			$("fieldset.form_site_node_block")[(v === 1) ? "removeClass" : "addClass"]("hidden_field");
			$("fieldset.form_url_block")[(v === 1) ? "addClass" : "removeClass"]("hidden_field");
		}
	}
}();

FormsNS.ModuleFormController = function() {
	return {
		changeType: function(el) {
			var v = parseInt($(el).val(), 10);

			if (!isNaN(v)) {
				$("fieldset.module_load_block")[(v === 1) ? "removeClass" : "addClass"]("hidden_field");
				$("fieldset.module_info_block")[(v === 1) ? "addClass" : "removeClass"]("hidden_field");

				$("span.txtbegin + input#module_sys_name").prev().html((v === 2) ? "mod_" : "dict_");
			}
		}
	}
}();

/**
 * Создание кнопки очистки поля
 * @param string|jQuery clearingField
 */
FormsNS.createEraseButton = function(clearingField) {
	try {
		var clearingFieldJQ = $(clearingField),
			pos = clearingFieldJQ.position(),
			width = clearingFieldJQ.width();

		$('<div class="eraseFieldButton" title="Очистить поле">x</div>')
			.css({
				position: 'absolute',
				left: (pos.left + width + 20) + 'px',
				top: (pos.top) + 'px'
			})
			.bind('click', function() {
				clearingFieldJQ.val("")
			})
			.insertAfter(clearingFieldJQ);
	} catch(e) {}
}