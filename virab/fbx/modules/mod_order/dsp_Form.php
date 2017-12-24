<?php
	require_once (LIB_PATH . "/ajax/JSON.php");
	$json = new Services_JSON();
?>
<script type="text/javascript" src="./js/serialize.js"></script>
<script type="text/javascript">
	// справочники типов доставки и мест доставки (для перерасчета)
	window['$__typeDeliveryDict'] = <?=$json->encode($type_delivery_to_js)?>;
	window['$__placeDeliveryDict'] = <?=$json->encode($place_delivery_to_js)?>;

	function recalculateFOSumms() {
		var sumOrder = 0;

		$(".VirabTFISITR_ .sumLine span").each(function(i, el) {
			sumOrder += parseInt($(el).html(), 10);
		});

		$("label[for='sum_text'] + strong").html(sumOrder);
	}

	function recalculateSumms() {
		var sumOrder = 0,
			sumDelivery = 0,
			discount = 0,
			countBonuses = 0,
			price = 0;

		$(".VirabTFISITR_ .sumLine span").each(function(i, el) {
			sumOrder += parseInt($(el).html(), 10);
		});

		$("label[for='sum_text'] + strong").html(sumOrder + "");
		$("input[name='price']").val(sumOrder);
		
	}

	function showHideAddressDelivery() {
		window.setTimeout(function() {
			var recipient = $("input[name='recipient']").val();
			$(".addressDeliveryFieldset")
				[(recipient === '0-0') ? 'removeClass' : 'addClass']('hidden')
				.find('input')
					[(recipient === '0-0') ? 'addClass' : 'val']('');

			var h = $("#selector_recipient").find("option[value='" + recipient + "']").html(),
				match = h ? h.match(/\((.*)\)$/im) : null,
				addr = (match != null) ? match[1] : "-";

			$("label[for='address_recipient'] + strong")
				.addClass("addressBlock")
				.html(addr);
		}, 200);
	}

	function showHideAddressPayment() {
		window.setTimeout(function() {
			var d = parseInt($("input[name='type_payment']").val(), 10);

			$(".addressPaymentFieldset")
				[(d === 2) ? 'removeClass' : 'addClass']('hidden')
				.find('input')
					[(d === 2) ? 'addClass' : 'val']('');
		}, 200);
	}

	function changeClientCallback(obj) {
		$("label[for='name_client'] + strong").html(obj.value);
		$("input[name='id_client']").val(obj.id);
		$("label[for='linkedit_client_recipient'] + strong").find("a").attr('href', 'index.php?fuseaction=mod_clients.contact_in_order&id_client=' + obj.id);

		// перепостроение селекта получателей
		var options = ['<option selected="" value="0">-- Выберите элемент --</option>'],
			rec = obj.data;

		for	(var i = 0, len = rec.length; i < len; i++) {
			options.push('<option value="' + rec[i]['id'] + '">' + rec[i]['name'] + '</option>');
		}

		$("#selector_recipient").html(options.join(""));
		showHideAddressDelivery();
	}

	function serializeRecipientValues() {
		var optionsEls = $("#selector_recipient option"),
			data = {};

		optionsEls.each(function(i, el) {
			var eld = $(el);

			data[eld.attr("value")] = eld.html();
		});

		console.log(data);
	}
</script>

<style type="text/css">
	.productForm label {
		width: 22em;
	}
	.productFormFieldset label {
		width: 21em !important;
	}
	.addressDeliveryFieldset label,
	.addressPaymentFieldset label {
		width: 20em !important;
	}

	.hidden {
		display: none;
	}
	.addressBlock {
		float: left;
		width: 500px;
	}
</style>
<?php


	// Ошибки
	$FORM_ERROR = $attributes['str_error'];
	if ($FORM_ERROR) {
		?><p class="cerr"><?=$FORM_ERROR?></p><?php
	}
	// Доступ
	if (!$auth_in->isAllowed()) {
		echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
		return;
	}



		// обычный заказ

		$fields = array(
			array(
				'typeField' => TFTEXT,
				'label' => _('Номер заказа'),
				'value' => $mod_data['number']
			),
				array(
					'typeField' => TFHIDDENFIELD,
					'name' => 'number',
					'value' => $mod_data['number']
				),
			array(
				'typeField' => TFTEXT,
				'label' => _('Дата заказа'),
				'value' => $mod_data['date_order']
			),
			array(
				'typeField' => TFSELECTDATASET,
				'name' => 'state_order',
				'label' => 'Состояние заказа',
				'multiple' => false,
				'empty' => false,
				'dataSet' => array(array('id' => 1, 'name' => 'Заказ создан на сайте'),
									array('id' => 2, 'name' => 'Заказ оплачен'),
									array('id' => 3, 'name' => 'Заказ отправлен'),
									array('id' => 4, 'name' => 'Заказ доставлен')),
				'selected' => array($mod_data['id_state_order'])
			),
			array(
				'typeField' => TFTEXTAREA,
				'name' => 'descr',
				'label' => _('Комментарий'),
				'value' => $mod_data['comment']
			),
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'state_order_hid',
				'value' => $mod_data['id_state_order']
			),
			array(
				'type' => TFFIELDSSET,
				'legend' => 'Получатель',
				'cls' => 'productFormFieldset',
				'fields' => array(
					array(
						'typeField' => TFTEXTFIELD,
						'name' => 'fio',
						'label' => _('ФИО'),
						'value' => $mod_data['fio']
					),
					array(
						'typeField' => TFTEXTFIELD,
						'name' => 'client_phone',
						'label' => _('Телефон'),
						'value' => $mod_data['phone']
					),
					array(
						'typeField' => TFTEXTFIELD,
						'name' => 'delivery_address',
						'label' => _('Город'),
						'value' => $mod_data['delivery_address']
					)
				)
			),

			array(
				'typeField' => TFSELECTPRODUCTWITHEDIT,
				'name' => 'products',
				'label_block' => 'Выбор состава заказа',
				'label' => _('Состав'),
				'label_search' => 'Поиск по названию',
				'searchvarname' => 'name',
				'backend' => SITE_URL . '/library/libcruiser4/ajax/find_product.php',
				'value' => $mod_data['products']
			),

			array(
				'typeField' => TFSELECTDATASET,
				'name' => 'type_payment',
				'label' => 'Тип оплаты',
				'multiple' => false,
				'empty' => true,
				'options' => $mod_data['type_payment_dict'],
				'selected' => array($mod_data['type_payment']),

			),
			/*array(
				'typeField' => TFSELECTDATASET,
				'name' => 'type_delivery',
				'label' => 'Тип доставки',
				'multiple' => false,
				'empty' => true,
				'dataSet' => $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_TYPE_DELIVERY),
				'selected' => array($mod_data['type_delivery'])
			),*/
			array(
				'typeField' => TFTEXT,
				'id' => 'sum_text',
				'label' => 'Сумма заказанных товаров',
				'textblock' => array('end' => '&nbsp;руб.'),
				'value' => $mod_data['price']
			),
				array(
					'typeField' => TFHIDDENFIELD,
					'name' => 'price',
					'value' => $mod_data['price']
				),
			
			array(
				'typeField' => TFBUTTON,
				'type' => 'button',
				'value' => _('Пересчитать суммы заказа'),
				'params' => array(
					'onClick' => "recalculateSumms(); return false;"
				)
			),





			// Кнопка
			array(
				'typeField' => TFBUTTON,
				'type' => 'button',
				'value' => _('Сохранить'),
				'params' => array(
					'onClick' => "$(\"form.productForm\").get(0).submit();"
				)
			),/*
			array(
				'typeField' => TFBUTTON,
				'type' => 'button',
				'value' => _('Сохранить и вернуться'),
				'params' => array(
					'onClick' => "
						$(\"input[name=without_redirect]\").val(1);
						$(\"form.productForm\").get(0).submit();
					"
				)
			),*/

			// Скрытые поля данных по записи
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'id',
				'value' => $attributes['id']
			),
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'id_client',
				'value' => $mod_data['id_client']
			),
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'type',
				'value' => $attributes['type']
			),
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'is_fastorder',
				'value' => $mod_data['is_fastorder']
			),
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'without_redirect',
				'value' => 0
			),
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'state_order_hid',
				'value' => $mod_data['id_state_order']
			)
		);


	$dsp_helper->writeForm(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'cls' => 'productForm',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_lng' => false,
		'addInOnSubmit' => 'return false;',
		'fields' => $fields
	));
?>
<script type="text/javascript">
	$(function() {
		showHideAddressDelivery();
		showHideAddressPayment();
	});
</script>