$(function() {
	// найдем элементы переключения
	for (var i = 0, len=LNG.length; i < len; i++) {
		var idel = '#lngSwitch' + LNG[i].ind_name; 
		$(idel).click(changeLngBlock(LNG[i].ind_name, idel));
	}
});

/**
 * Хранилище настроек переключения языков WYSIWYG элементов
 * @type array
 * 
 * пример: VirabTFWYSIWYG = [
 * 		[ 'Ru' : 'wwg_1__1',
 * 		  'Ua' : 'wwg_2__2',
 * 		  'En' : 'wwg_3__3' ]
 * ]
 * Где wwg - значение поля name VirabTFWYSIWYG
 * 
 */
VirabTFWYSIWYG = [];

/**
 * Хранилище настроек переключения языков EditArea элементов
 * @type array
 *
 * пример: VirabTFTextareaExt = [
 * ]
 *
 */
VirabTFTextareaExt = [];

function changeLngBlock(lng, idel) {
	return function() {
		if (!$(idel).hasClass('lng_checked')) {
			// переключим все элементы
			for (var i = 0, len=LNG.length; i < len; i++) {
				var els = $('.tvirabForm .flng' + LNG[i].ind_name),
					brels = $('.tvirabForm .brflng' + LNG[i].ind_name);
					swtc = $('#lngSwitch' + LNG[i].ind_name);
					
				if (LNG[i].ind_name == lng) {
					// включить
					els.removeClass('hidden_field');
					brels.removeClass('hidden_field');
					swtc.addClass('lng_checked');
					swtc.removeClass('lng');
					
					// переключим WYSIWYG элементы
					for (var j = 0, jlen = VirabTFWYSIWYG.length; j < jlen; j++) {
						var __objstr = VirabTFWYSIWYG[j][LNG[i].ind_name],
							__obj_el = VirabTFWYSIWYG[j].obj_el;
						// вызов функции переключения
						window[__obj_el].setActivePage(__objstr);
					}
				} else {
					// выключить
					els.addClass('hidden_field');
					brels.addClass('hidden_field');
					swtc.removeClass('lng_checked');
					swtc.addClass('lng');
				}
			}			
		}
	}
}