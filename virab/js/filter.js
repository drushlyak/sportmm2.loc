
	var triggFilter = function() {
		var els = $('.tvirabForm fieldset.main *'),
		cont = $('.tvirabForm fieldset.main');
		var is_collapsed = cont.hasClass('tff_collapse');

		for (var i=0, len = els.length; i<len; i++) {
			var el = $(els[i]);
			if (el.hasClass('main') ||
				el.attr('type') == 'hidden' ||
				el.get(0).nodeName == 'SCRIPT') {
				continue;
			}
			el[is_collapsed ? 'show' : 'hide']();
			cont[is_collapsed ? 'removeClass' : 'addClass']('tff_collapse');
			if (is_collapsed) {
				$('.tvirabForm label:first + *').focus();
			}
		}

		Cruiser.mainPageResize();
	}

	$(function() {
		$('.tvirabForm legend.main').click(triggFilter);
	});

	function sendFilter(tableID, smessage) {
		if (tableID) {
			$("#" + tableID).html(smessage || '');
		}

		$("input[name='filter']").val(1);
		$("form.filter_form_class").get(0).submit();
	}

	function cleanFilter(tableID, smessage) {
		if (tableID) {
			$("#" + tableID).html(smessage || '');
		}

		$("input[name='filter']").val(0);
		triggFilter();
		$("form.filter_form_class").get(0).submit();
	}