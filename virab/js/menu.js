
	/**
	 * Функция подсветки поля
	 *
	 * @param {boolean} hover
	 * @param {object} e
	 **/
	function hoverLI(hover, e) {
		var t = $(e.target);
		if (e.target.nodeName == 'LI' ||  e.target.nodeName == 'SPAN' || e.target.nodeName == 'A') {
			if (!t.hasClass('TVMli')) {
				t = t.parent();
			}
			t[hover ? 'addClass' : 'removeClass']('HoverLI');
		}
	}

	/**
	 * Запись состояния меню в cookie
	 *
	 * @param {int} id - ID узла
	 * @param {boolean} remove - флаг удаления из массива свернутых нод
	 */
	function setMenuStateInCookie(id, remove) {
		var virab_menu_state = Cruiser.getCookie('t_virab_menu_state');
			virab_menu_state = ((virab_menu_state !== null && virab_menu_state !== "") ? virab_menu_state.split(',') : []),
			check = (virab_menu_state.indexOf(id) === -1);

		if (remove) {
			if (!check) {
				virab_menu_state.splice(virab_menu_state.indexOf(id), 1);
			}
		} else {
			if (check) {
				virab_menu_state[virab_menu_state.length] = id;
			}
		}

		Cruiser.setCookie('t_virab_menu_state', virab_menu_state.join(','), 999);
	}

	/**
	 * Функция-обработчик нажатия
	 *
	 * @param {object} e
	 * @return {Boolean}
	 */
	function onMenuElClick(e) {
		var t = $(e.target);
		if (e.target.nodeName == 'LI' ||  e.target.nodeName == 'SPAN' || e.target.nodeName == 'A') {
			if (!t.hasClass('TVMli')) {
				t = t.parent();
			}

			if (t.hasClass('TVMli')) {
				var ID = t.get(0).id.replace('mainMenu_ID_', '');
			} else {
				return false;
			}

			if (t.hasClass('fLI')) {
				if (t.hasClass('fLIcollapsed')) {
					// развернем
					t.removeClass('fLIcollapsed');
					setMenuStateInCookie(ID, true);
				} else {
					// свернем
					t.addClass('fLIcollapsed');
					setMenuStateInCookie(ID, false);
				}
			} else if (t.hasClass('sLI')) {
				// перейдем
				window.location.href = 'index.php?fuseaction=' + __VirabMenuSRC[ID];
			}
		}
	}

	$(function() {
		$("ul.TVirabMenu").bind('mouseover', function(e) {
			hoverLI(true, e);
		});

		$("ul.TVirabMenu").bind('mouseout', function(e) {
			hoverLI(false, e);
		});

		$("ul.TVirabMenu").bind('click', function(e) {
			onMenuElClick(e);
		});

		$(".TVirabTITLE").bind('click', function(e) {
			window.location.href = 'index.php';
		});
	});