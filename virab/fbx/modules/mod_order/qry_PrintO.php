<?php
	$id  = (int) $attributes['id'];
	

	$order_data = array();

	if ($id) {
		$order_data = $db->get_row("
			SELECT mo.id AS id_order,
					mo.*,
					moai.*,
					DATE_FORMAT(mo.date_order, '%d.%m.%Y %H:%i:%s') AS date_order,
					DATE_FORMAT(moai.fact_delivery_date, '%d.%m.%Y') AS fact_delivery_date,
					DATE_FORMAT(moai.fact_delivery_date, '%H:%i') AS fact_time,
					DATE_FORMAT(moai.delivery_date, '%d.%m.%Y') AS delivery_pdate,
					DATE_FORMAT(moai.time, '%H:%i') AS delivery_time,
					od.`id_place_delivery`,
					od.`km_count`,
					IF (mcio.`is_himself`, '0-0', CONCAT(moai.`id_contact_in_order`, '-', moai.`id_address_storage`)) AS id_recipient_id_address,
					mas.*,
					CONCAT(mc.f_name, ' ', mc.i_name, ' ', mc.o_name) AS client,
					mc.phone,
					mc.email,
					mcio.fio AS contact_fio,
					DATE_FORMAT(mc.birth_date, '%d.%m.%Y') AS birth_date,
					mc.bonus AS client_bonuses,
					IF(ISNULL(moai.delivery_date), 1, 0) AS is_fastorder,
					oddo.`id_declined_reason`,
					mtd.name AS str_type_delivery,
					mtd.interval_hours
			FROM " . CFG_DBTBL_MOD_ORDER . " AS mo
				JOIN " . CFG_DBTBL_MOD_CLIENT . " AS mc ON mc.id = mo.id_client
				JOIN " . CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO . " AS moai ON mo.id = moai.id_order
				LEFT JOIN " . CFG_DBTBL_MOD_TYPE_DELIVERY . " AS mtd ON mtd.id = moai.type_delivery
				LEFT JOIN " . CFG_DBTBL_MOD_ORDER_DECLINED_ORDER . " AS oddo ON oddo.`id_order` = mo.`id`
				LEFT JOIN " . CFG_DBTBL_MOD_ORDER_DELIVERY . " AS od ON od.`id_order` = mo.`id`
				LEFT JOIN " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " mcio ON moai.`id_contact_in_order` = mcio.`id`
				LEFT JOIN " . CFG_DBTBL_MOD_ADDRESS_STORAGE . " mas ON mas.id = moai.`id_address_storage`
			WHERE mo.id = ?
		", $id );
		
		$promocode = $db->get_one("SELECT promo_code FROM " . CFG_DBTBL_MOD_PROMO_CODE . " WHERE id = ?",$order_data['id_promocode']);
		
		if(!$order_data['id_user_created']) {
			$username = "Клиент на сайте";
		} else {
			$sql = "
				SELECT *
					FROM " . CFG_DBTBL_UDATA . "
				WHERE id = ? ";
			$auth = $db->get_row($sql, $order_data['id_user_created']);
			$username = $lng->get($auth['full_name']);
		}
		
		
		$source_order = $db->get_one("SELECT name FROM " . CFG_DBTBL_DICT_SOURCE_ORDERS . " WHERE id = ?",$order_data['id_source_order']);


		$order_data['products'] = $db->get_all("
		SELECT *, mp.main_foto50 AS photo
			FROM " . CFG_DBTBL_MOD_ORDER_PRODUCT . " AS mop
			   , " . CFG_DBTBL_MOD_PRODUCT . " AS mp
			WHERE mop.id_order = ?
			  AND mop.id_product = mp.id
		", $id);

		if (is_array($order_data['products'])) {
			foreach ($order_data['products'] as &$pproduct) {
				$cont = $db->get_all("
					SELECT mu.*, mup.count
						FROM " . CFG_DBTBL_MOD_UNIT . " AS mu
							JOIN " . CFG_DBTBL_MOD_UNIT_PRODUCT . " mup ON mup.id_units = mu.id
					WHERE mup.id_product = ?
				", $pproduct['id']);

				$pproduct['cont'] = is_array($cont) ? $cont : array();
			}
		}
		
		// адрес оплаты, если выбран тип оплаты - наличными нашему курьеру
		$order_data['payment_address'] = array();
		if ((int) $order_data['type_payment'] === 2) {
			$order_data['payment_address'] = $db->get_row("
				SELECT mas.*
					FROM " . CFG_DBTBL_MOD_ORDER_ADDRESS_PAYMENT . " moap
						JOIN " . CFG_DBTBL_MOD_ADDRESS_STORAGE . " mas ON mas.id = moap.id_address_storage
				WHERE moap.id_order = ?
			", $id);
		}

		
		//адрес клиента
		$addressDataP = $db->get_row("
			SELECT *
				FROM " . CFG_DBTBL_MOD_CLIENT . "
			WHERE id = ?
		", $order_data['id_client']);
		
		$adressP = is_array($addressDataP) ? formatAddressClient($addressDataP) : "&mdash;";
		
		//print_r($adressP);
		//адрес получателя
		$addressData = $db->get_row("
			SELECT *
				FROM " . CFG_DBTBL_MOD_ADDRESS_STORAGE . "
			WHERE id = ?
		", $order_data['id_address_storage']);

		$order_data['addressDelivery'] = formatAddressString($addressData) ? formatAddressString($addressData) : $adressP;

		//print_r($addressData);
		
		$order_data['addressDelivery'] = formatAddressString($order_data['payment_address']) ? formatAddressString($order_data['payment_address']) : $order_data['addressDelivery']; 
		
	}

	//echo $order_data['interval_hours'];
	if ((int) $order_data['interval_hours'] > 0) {
		$order_data['delivery_time_spo'] =
			date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. с ' . date('H:i', strtotime($order_data['delivery_time'])) . ' по ' .
			date('H:i', strtotime('+' . $order_data['interval_hours'] . ' hour', strtotime($order_data['delivery_time'])));
	} elseif ((int) $order_data['interval_hours'] == 0) {
		$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. в точное время: ' . date('H:i', strtotime($order_data['delivery_time']));
	} else {
		$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. самовывозом';
	}
	
	if ((int) $order_data['interval_hours'] > 0) {
		$date_order = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г.';
		$time_order = date('H:i', strtotime($order_data['delivery_time'])) . ' - ' .
			date('H:i', strtotime('+' . $order_data['interval_hours'] . ' hour', strtotime($order_data['delivery_time'])));
	} elseif ((int) $order_data['interval_hours'] == 0) {
		$time_order = date('H:i', strtotime($order_data['delivery_time']));
	} else {
		$time_order = 'самовывозом';
	}
	
	
	//время и дата. оплата наличными курьеру
	if($order_data['courier_for_money_date']) {
		$date_time_pay = explode(' ', $order_data['courier_for_money_date']);
		$date_order = date('d.m.Y', strtotime($date_time_pay[0])) . 'г.';
		$time_order = date('H:i', strtotime($date_time_pay[1]));
	}
	
	/*
	switch ($order_data['type_delivery']) {
		case 2:  // 3 часа
			$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. с ' . date('H:i', strtotime($order_data['delivery_time'])) . '  по ' . date('H:i', strtotime('+3 hour', strtotime($order_data['delivery_time'])));
			$order_data['str_type_delivery'] = '3-х часовой интервал';
			break;
		case 3:  // 1 час
			$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. с ' . date('H:i', strtotime($order_data['delivery_time'])) . '  по ' . date('H:i', strtotime('+1 hour', strtotime($order_data['delivery_time'])));
			$order_data['str_type_delivery'] = 'Интервал в 1 час';
			break;
		case 4: // точное время
			$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. в ' . date('H:i', strtotime($order_data['delivery_time']));
			$order_data['str_type_delivery'] = 'Точное время';
			break;
		case 5:
			$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. самовывозом';
			$order_data['str_type_delivery'] = 'Самовывоз';
			break;
		case 6:  // 3 часа за пределы МКАД
			$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. с ' . date('H:i', strtotime($order_data['delivery_time'])) . '  по ' . date('H:i', strtotime('+3 hour', strtotime($order_data['delivery_time'])));
			$order_data['str_type_delivery'] = '3-х часовой интервал';
			break;
		case 7:  // 2 часа
			$order_data['delivery_time_spo'] = date('d.m.Y', strtotime($order_data['delivery_date'])) . 'г. с ' . date('H:i', strtotime($order_data['delivery_time'])) . '  по ' . date('H:i', strtotime('+2 hour', strtotime($order_data['delivery_time'])));
			$order_data['str_type_delivery'] = '2-х часовой интервал';
			break;
		default:
			$order_data['delivery_time_spo'] = ' не определено - ' . $order_data['type_delivery'];
			$order_data['str_type_delivery'] = 'Не определено';
			break;
	}
	*/

	$typeOfAddressDictionary = array(
		'Рабочий', 'Домашний', 'Частный дом', 'Гостиница', 'Больница'
	);

	$order_data['type_payment_dict'] = array(
		1 => array(
			'id' => 1,
			'name' => 'Наличными в нашем салоне цветов'
		),
		2 => array(
			'id' => 2,
			'name' => 'Наличными нашему курьеру'
		),
		3 => array(
			'id' => 3,
			'name' => 'Через личный кабинет в терминале КИВИ'
		),
		4 => array(
			'id' => 4,
			'name' => 'Банковские карты Visa/MC в салоне цветов'
		),
		5 => array(
			'id' => 5,
			'name' => 'Банковские карты Visa/MC через интернет'
		),
		6 => array(
			'id' => 6,
			'name' => 'Банковской квитанцией'
		),
		7 => array(
			'id' => 7,
			'name' => 'WebMoney'
		),
		8 => array(
			'id' => 8,
			'name' => 'Наличные при получении'
		),
		9 => array(
			'id' => 9,
			'name' => 'Yandex.Money'
		),
		10 => array(
			'id' => 10,
			'name' => 'Со счета мобильного телефона МТС'
		),
		11 => array(
			'id' => 11,
			'name' => 'Со счета мобильного телефона Билайн'
		),
		12 => array(
			'id' => 12,
			'name' => 'Наличные в терминале оплаты Absolutplat'
		),
		13 => array(
			'id' => 13,
			'name' => 'Наличные в терминале оплаты Pinpay'
		),
		14 => array(
			'id' => 14,
			'name' => 'Наличные в терминале оплаты Мобил Элемент'
		),
		15 => array(
			'id' => 15,
			'name' => 'Наличные в терминале оплаты Новоплат'
		),
		16 => array(
			'id' => 16,
			'name' => 'Наличные в терминале оплаты Уникасса'
		),
		17 => array(
			'id' => 17,
			'name' => 'Наличные в терминале оплаты Элекснет'
		),
		18 => array(
			'id' => 18,
			'name' => 'Перевод в системе CONTACT'
		),
		19 => array(
			'id' => 19,
			'name' => 'iPhone'
		),
		20 => array(
			'id' => 20,
			'name' => 'QIWI кошелек'
		),
		21 => array(
			'id' => 21,
			'name' => 'Банкомат ВТБ24'
		),
		22 => array(
			'id' => 22,
			'name' => 'Банкомат Петрокоммерц'
		),
		23 => array(
			'id' => 23,
			'name' => 'Евросеть'
		),
		24 => array(
			'id' => 24,
			'name' => 'Связной'
		),
		25 => array(
			'id' => 25,
			'name' => 'Интернет-банк Альфа-Клик'
		),
		26 => array(
			'id' => 26,
			'name' => 'EasyPay'
		),
		27 => array(
			'id' => 27,
			'name' => 'MoneyMail'
		),
		28 => array(
			'id' => 28,
			'name' => 'RBK Money'
		),
		29 => array(
			'id' => 29,
			'name' => 'TeleMoney'
		),
		30 => array(
			'id' => 30,
			'name' => 'WebCreds'
		),
		31 => array(
			'id' => 31,
			'name' => 'Z-Payment'
		),
		32 => array(
			'id' => 32,
			'name' => 'ВКонтакте'
		),
		33 => array(
			'id' => 33,
			'name' => 'Единый Кошелек'
		),
		34 => array(
			'id' => 34,
			'name' => 'Деньги@Mail.Ru'
		),
		35 => array(
			'id' => 35,
			'name' => 'Банковские карты Visa/MC через Platron'
		),
		36 => array(
			'id' => 36,
			'name' => 'Банковской картой Robokassa.ru'
		),
		37 => array(
			'id' => 37,
			'name' => 'Банковской карточй через Platezh.rus'
		)
	);

	$order_data['products'] = is_array($order_data['products']) ? $order_data['products'] : array();