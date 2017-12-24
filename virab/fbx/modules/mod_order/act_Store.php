<?php
	require_once (LIB_PATH . "/Sms.class.php");
	require_once (LIB_PATH . "/class.phpmailer.php");

	// Обработка входных данных
	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'is_fastorder' => array(
			'type' => 'int'
		),
		'without_redirect' => array(
			'type' => 'int'
		),

		// быстрый заказ //

		'descr' => array(
			'type' => 'string',
			'trim' => true
		),
		'state_order' => array(
			'type' => 'int'
		),
		'delivery_myself' => array(
			'type' => 'int'
		),
		'state_order_hid' => array(
			'type' => 'int'
		),
		
		'id_diclined_reason' => array(
			'type' => 'int'
		),
		'id_result_delivery' => array(
			'type' => 'int'
		),
		'id_source_order' => array(
			'type' => 'int'
		),
		'fact_delivery_date' => array(
			'type' => 'string'
		),
		'fact_delivery_time' => array(
			'type' => 'string'
		),
		'date_order' => array(
			'type' => 'string'
		),

		'products' => array(
			'type' => 'array'
		),

		// обычный заказ //

		'number' => array(
			'type' => 'string',
			'trim' => true
		),
		'id_client' => array(
			'type' => 'int'
		),
		'main_phone_client' => array(
			'type' => 'string',
			'trim' => true
		),
		'old_id_client' => array( // для начисления остатка бонусов
			'type' => 'int'
		),
		'recipient' => array(
			'type' => 'string'
		),
		'fio' => array(
			'type' => 'string',
			'trim' => true
		),

		'delivery_city' => array(
			'type' => 'string',
			'trim' => true
		),
		'delivery_house' => array(
			'type' => 'string',
			'trim' => true
		),
		'delivery_address' => array(
			'type' => 'string',
			'trim' => true
		),
		'delivery_corp' => array(
			'type' => 'string',
			'trim' => true
		),
		'delivery_aprt' => array(
			'type' => 'string',
			'trim' => true
		),
		'delivery_flat' => array(
			'type' => 'string',
			'trim' => true
		),
		'delivery_doorphone' => array(
			'type' => 'string',
			'trim' => true
		),

		'type_delivery' => array(
			'type' => 'int'
		),
		'id_place_delivery' => array(
			'type' => 'int'
		),
		'km_count' => array(
			'type' => 'int'
		),

		'fact_delivery_date' => array(
			'type' => 'string'
		),
		'fact_delivery_time' => array(
			'type' => 'string'
		),

		'specify_name' => array(
			'type' => 'int'
		),
		'take_photo' => array(
			'type' => 'int'
		),
		'allow_placement_photo' => array(
			'type' => 'int'
		),

		'text_card' => array(
			'type' => 'string',
			'trim' => true
		),
		'comments' => array(
			'type' => 'string',
			'trim' => true
		),

		'type_payment' => array(
			'type' => 'int'
		),


		'payment_date' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_city' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_street' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_house' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_building' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_entrance' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_porch' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_flat' => array(
			'type' => 'string',
			'trim' => true
		),
		'payment_doorphone' => array(
			'type' => 'string',
			'trim' => true
		),

		'count_discount' => array(
			'type' => 'int'
		),
		'promo_code' => array(
			'type' => 'int'
		),
		'count_bonuses' => array(
			'type' => 'int'
		),
		'old_count_bonuses' => array(
			'type' => 'int'
		),
		'sum' => array(
			'type' => 'int'
		),
		'base_cost' => array(
			'type' => 'int'
		),
		'price_delivery' => array(
			'type' => 'int'
		),
		'price' => array(
			'type' => 'int'
		),
		'florist_select' => array(
			'type' => 'int'
		),
		'driver_select' => array(
			'type' => 'int'
		),
		'username' => array(
			'type' => 'int'
		),
		'is_pay' => array(
			'type' => 'int'
		),
		'client_phone' => array(
			'type' => 'string',
			'trim' => true
		),
		'comment' => array(
			'type' => 'string',
			'trim' => true
		)

	));
	
	
	$date_order = $attributes['date_order'];
	$id  = $attributes['id'];
	$type = $attributes['type'];
	$is_fastorder = $attributes['is_fastorder'];

	$descr = $attributes['descr'];
	$state_order = $attributes['state_order'];
	$state_order_hid = $attributes['state_order_hid'];
	$id_diclined_reason = $attributes['id_diclined_reason'];
	$id_result_delivery = $attributes['id_result_delivery'];
	$id_source_order = $attributes['id_source_order'];
	$fact_delivery_date = $attributes['fact_delivery_date'];
	$fact_delivery_time = $attributes['fact_delivery_time'];
	$fact_delivery_datetime = $fact_delivery_date . ' ' . $fact_delivery_time;
	$products = $attributes['products'];

	$number = $attributes['number'];
	$id_client = $attributes['id_client'];
	$old_id_client = $attributes['old_id_client'];
	$main_phone_client = $attributes['main_phone_client'];
	$recipient = $attributes['recipient'];
	//$fio = $attributes['fio'];
	$username = $attributes['username'];
	$is_pay = $attributes['is_pay'];

	$delivery_city = $attributes['delivery_city'];

	$delivery_house = $attributes['delivery_house'];
	$delivery_address = $attributes['delivery_address'];
	$delivery_corp = $attributes['delivery_corp'];
	$delivery_aprt = $attributes['delivery_aprt'];
	$delivery_flat = $attributes['delivery_flat'];
	$delivery_doorphone = $attributes['delivery_doorphone'];
	
	$delivery_myself = $attributes['delivery_myself'];

	$type_delivery = $attributes['type_delivery'];
	$id_place_delivery = $attributes['id_place_delivery'];
	$km_count = $attributes['km_count'];

	$delivery_date = $attributes['delivery_date'];
	$delivery_time = $attributes['delivery_time'];

	$specify_name = $attributes['specify_name'];
	$take_photo = $attributes['take_photo'];
	$allow_placement_photo = $attributes['allow_placement_photo'];

	$text_card = $attributes['text_card'];
	$comments = $attributes['comments'];

	$type_payment = $attributes['type_payment'];

	$payment_date = $attributes['payment_date'];
	$payment_city = $attributes['payment_city'];
	$payment_street = $attributes['payment_street'];
	$payment_house = $attributes['payment_house'];
	$payment_building = $attributes['payment_building'];
	$payment_entrance = $attributes['payment_entrance'];
	$payment_porch = $attributes['payment_porch'];
	$payment_flat = $attributes['payment_flat'];
	$payment_doorphone = $attributes['payment_doorphone'];

	$count_discount = $attributes['count_discount'];
	$promo_code = $attributes['promo_code'];
	$count_bonuses = $attributes['count_bonuses'];
	$old_count_bonuses = $attributes['old_count_bonuses'];

	$sum = $attributes['sum'];
	$base_cost = $attributes['base_cost'];
	$price_delivery = $attributes['price_delivery'];
	// Полная стоимость - товары + доставка без скидок и бонусов
	$cost = $sum + $price_delivery;
	$price = $attributes['price'];

	$florist_select = $attributes['florist_select'];
	$driver_select = $attributes['driver_select'];
	$client_phone = $attributes['client_phone'];
	$fio = $attributes['fio'];
	$comment = $attributes['commnet'];

	//Определяем скидку по промо-коду

	/*if ($promo_code) {
		$promo_discount = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PROMO_CODE . " WHERE promo_code = ?",$promo_code);
	}
	//Сраним скидку и оставим ту, которая больше
	if ($promo_discount['discount'] > $count_discount) {
		$count_discount = $promo_discount['discount'];
	}
	*/
	// Проверки
	if (empty($state_order)) {
		$FORM_ERROR = "<br />" . _("Необходимо выбрать состояние заказа") . "<br />";
	}

	if (!$FORM_ERROR) {

		if ($type == 2 && $id) {
			// Редактируем заказ

			if ($is_fastorder) {
				$orderData = array(
					'id_state_order' => $state_order,
					'descr' => $descr,
					'price' => $price
				);
			} else {
				$orderData = array(
					'id_state_order' => $state_order,
					'id_client' => $id_client,
					'descr' => $descr,
					'price' => $price,			
					'delivery_date' => $fact_delivery_date,
					'type_delivery' => $type_delivery,
					'delivery_time' => $fact_delivery_time,
					'type_payment' => $type_payment,
					'delivery_city' => $delivery_city,
					'delivery_address' => $delivery_address
				);
			}
			$client_data = array(
				'fio' => $fio,
				'phone' => $client_phone
			);
			

			$db->update(CFG_DBTBL_MOD_ORDER, $orderData, array(
				'id' => $id
			));
			$db->update(CFG_DBTBL_MOD_CLIENT, $client_data, array(
				'id' => $id_client
			));

			// удалим связанные товары
			$db->delete(CFG_DBTBL_MOD_ORDER_PRODUCT, array(
				'id_order' => $id
			));

			// заново создадим список товаров
			if (is_array($products)) {
				$count_products = count($products);
				$bonusAddSumCost = 0;
				$bonusAddSumBons = 0;
				$bonusSubSum = 0;
				foreach ($products as $id_product => $product_data) {
					$db->insert(CFG_DBTBL_MOD_ORDER_PRODUCT, array(
						'id_order' => $id,
						'id_product' => $id_product,
						'count' => $product_data['count'],
						'price' => $product_data['sum']
					));
					$extend_data = $db->get_row("SELECT is_not_allocation_bonuses, amount_deduction_bonuses FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $id_product);
					if (intval($extend_data['is_not_allocation_bonuses'])) {
						$bonusSubSum += $product_data['sum'];
					}
					if ($count_products == 1 && intval($extend_data['amount_deduction_bonuses'])) {
						$bonusAddSumCost += $product_data['sum'];
						$bonusAddSumBons += intval($extend_data['amount_deduction_bonuses']);
					}
				}
			}

			$id_order = $id;
		} else {
			// создаем заказ
			$id_order = $db->insert(CFG_DBTBL_MOD_ORDER, array(
					'date_order' => date("Y-m-d H:i:s"),
					'number' => $number,
					'id_state_order' => $state_order,
					'id_client' => $id_client,
					'descr' => $descr,
					'price' => $price,
					'delivery_date' => $fact_delivery_date,
					'type_delivery' => $type_delivery,
					'delivery_time' => $fact_delivery_time,
					'type_payment' => $type_payment,
					'delivery_city' => $delivery_city,
					'delivery_address' => $delivery_address
			));
			$db->insert(CFG_DBTBL_MOD_CLIENT, array(
				'fio' => $fio,
				'phone' => $client_phone
			));

			if (is_array($products)) {
				$count_products = count($products);
				$bonusAddSumCost = 0;
				$bonusAddSumBons = 0;
				$bonusSubSum = 0;
				foreach ($products as $id_product => $product_data) {
					$db->insert(CFG_DBTBL_MOD_ORDER_PRODUCT, array(
						'id_order' => $id_order,
						'id_product' => $id_product,
						'count' => $product_data['count'],
						'price' => $product_data['sum']
					));
					$extend_data = $db->get_row("SELECT is_not_allocation_bonuses, amount_deduction_bonuses FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $id_product);
					if (intval($extend_data['is_not_allocation_bonuses'])) {
						$bonusSubSum += $product_data['sum'];
					}
					if ($count_products == 1 && intval($extend_data['amount_deduction_bonuses'])) {
						$bonusAddSumCost += $product_data['sum'];
						$bonusAddSumBons += intval($extend_data['amount_deduction_bonuses']);
					}
				}
			}
		}
		
		// причина отмены
		$db->delete(
			CFG_DBTBL_MOD_ORDER_DECLINED_ORDER,
			array(
				'id_order' => $id_order
			)
		);
		if ($id_diclined_reason) {
			$db->insert(
				CFG_DBTBL_MOD_ORDER_DECLINED_ORDER,
				array(
					'id_order' => $id_order,
					'id_declined_reason' => $id_diclined_reason
				)
			);
		}

		// обычный заказ
		if (!$is_fastorder) {
			// получатель
			/*$recipientData = parseOrSetContactInOrder(
				$id_client,
				$recipient,
				array(
					'city' => $delivery_city,
					'street' => $delivery_street,
					'house' => $delivery_house,
					'building' => $delivery_building,
					'entrance' => $delivery_entrance,
					'porch' => $delivery_porch,
					'flat' => $delivery_flat,
					'doorphone' => $delivery_doorphone
				)
			);

			// разрываем связь по mod_order_address_payment (если она нужна, она будет пересоздана)
			$db->delete(CFG_DBTBL_MOD_ORDER_ADDRESS_PAYMENT, array(
				'id_order' => $id_order
			));

			// адрес оплаты при типе оплаты - наличные нашему курьеру
			if ($type_payment === 2) {
				// наличные курьеру
				$id_payment_addr = $db->insert(CFG_DBTBL_MOD_ADDRESS_STORAGE, array(
					'type_of_address' => 2, // домашний
					'company' => '',
					'institution_name' => '',
					'department' => '',
					'city' => $payment_city,
					'street' => $payment_street,
					'house' => $payment_house,
					'building' => $payment_building,
					'entrance' => $payment_entrance,
					'porch' => $payment_porch,
					'hotel_room' => '',
					'ward' => '',
					'flat' => $payment_flat,
					'office' => '',
					'doorphone' => $payment_doorphone
				));

				$db->insert(
					CFG_DBTBL_MOD_ORDER_ADDRESS_PAYMENT,
					array(
						'id_address_storage' => $id_payment_addr,
						'id_order' => $id_order
					)
				);

				$db->update(CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO, array(
					'courier_for_money_date' => $payment_date
				), array(
					'id_order' => $id_order
				));
			}
*/
			if ($type == 2 && $id) {
				// редактируем
				/*$db->update(CFG_DBTBL_MOD_ORDER, array(
					'delivery_date' => $delivery_date,
					'type_delivery' => $type_delivery,
					'delivery_time' => $delivery_time,
					'date_order' => $date_order,
					'type_payment' => $type_payment,
					'specify_name' => $specify_name,
					'take_photo' => $take_photo,
					'allow_placement_photo' => $allow_placement_photo,
					'text_card' => $text_card,
					'comments' => $comments,
					'type_payment' => $type_payment,
					'count_bonuses' => $count_bonuses,
					'sum' => $sum,
					'price' => $price,
					'count_discount' => $count_discount,
					'price_delivery' => $price_delivery,
					'id_result_delivery' => $id_result_delivery,
					'fact_delivery_date' => $fact_delivery_datetime,
					'id_source_order' => $id_source_order,
					'florist_select' => $florist_select,
					'driver_select' => $driver_select,
					'base_cost' => $base_cost,
					'is_pay' => $is_pay,
					'id_promocode' => $promo_discount['id']
				), array(
					'id_order' => $id_order
				));

				// delivery
				$db->update(
					CFG_DBTBL_MOD_ORDER_DELIVERY,
					array(
						'id_type_delivery' => $type_delivery,
						'id_place_delivery' => $id_place_delivery,
						'km_count' => $km_count
					),
					array(
						'id_order' => $id_order
					)
				);
				*/
				// Если изменился статус заказа то отправим уведомление
				if ($state_order != $state_order_hid) {
					$count_res = preg_match_all("/\+(\d+) \((\d+)\) (\d+)/i", $main_phone_client, $matches);
					$phoneToSendSMS = $matches[1][0] . $matches[2][0] . $matches[3][0];

					$send = $db->get_row("SELECT receive_mail, email, f_name, i_name FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE id = ?", $id_client);
				
					//имя клиента
					$_SESSION['email_client_name'] = $send['f_name'] . " " . $send['i_name'];
					
					//Номер заказа
					$_SESSION['email_order_number'] = $number;
					
					//Получатель
					$_SESSION['email_recipient_name'] = ($delivery_myself) ? $send['f_name'] . " " . $send['i_name'] : $fio;

					
					$_SESSION['sms_order_city'] = $db->get_one("SELECT dc.name FROM " . CFG_DBTBL_DICT_CITY . " AS dc, " . CFG_DBTBL_MOD_PRODUCT . " AS mp WHERE mp.id = ? AND mp.id_city = dc.id LIMIT 1", key($products));
					switch ($type_delivery) {
						case 2:  // 3 часа
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+3 hour', strtotime($delivery_time)));
							break;
						case 3:  // 1 час
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+1 hour', strtotime($delivery_time)));
							break;
						case 4: // точное время
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. в ' . date('H:i', strtotime($delivery_time));
							break;
						case 5:
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. самовывозом';
							break;
						case 6:  // 3 часа за пределы МКАД
							$_SESSION['sms_order_city'] = $db->get_one("SELECT mp.name FROM " . CFG_DBTBL_MOD_PLACES . " AS mp WHERE mp.id = ?", $id_place_delivery);
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+3 hour', strtotime($delivery_time)));
							break;
						case 7:  // 2 часа
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+2 hour', strtotime($delivery_time)));
							break;
						default:
							$_SESSION['sms_order_delivery_date'] = ' не определено - ' . $type_delivery;
							break;
					}
					$_SESSION['email_order_city'] = $_SESSION['sms_order_city'];
					$_SESSION['email_order_delivery_date'] = $_SESSION['sms_order_delivery_date'];

					//$_SESSION['sms_client_name'] = getRecipientFIO($recipientData['id_contact_in_order']);
					//$_SESSION['email_client_name'] = $_SESSION['sms_client_name'];

					switch ($type_payment) {
						case 1:
							$_SESSION['email_order_type_payment'] = 'Наличными в нашем <a href="' . SITE_URL . '/location_map/" target="_blank">салоне цветов</a>';
							break;
						case 2:
							$_SESSION['email_order_type_payment'] = 'Наличными нашему курьеру';
							break;
						case 3:
							$_SESSION['email_order_type_payment'] = 'Через личный кабинет в терминале КИВИ';
							break;
						case 4:
							$_SESSION['email_order_type_payment'] = 'Банковские карты Visa/MC в <a href="' . SITE_URL . '/location_map/" target="_blank">салоне цветов</a>';
							break;
						case 5:
							$_SESSION['email_order_type_payment'] = 'Банковские карты Visa/MC через интернет';
							break;
						case 6:
							$_SESSION['email_order_type_payment'] = 'Банковской квитанцией (<a href="' . SITE_URL . '/resources/documents/blank.pdf" target="_blank">скачать квитанцию</a>)';
							break;
						case 7:
							$_SESSION['email_order_type_payment'] = '<a href="' . SITE_URL . '/pay_online/?number_order=' . $orderNumber . '&ammount=' . $price . '" target="_blank">WebMoney</a>';
							break;
						case 8:
							$_SESSION['email_order_type_payment'] = 'Наличные при получении';
							break;
						case 9:
							$_SESSION['email_order_type_payment'] = '<a href="' . SITE_URL . '/pay_online/?number_order=' . $orderNumber . '&ammount=' . $price . '" target="_blank">Yandex.Money</a>';
							break;
						case 10:
							$_SESSION['email_order_type_payment'] = 'Со счета мобильного телефона МТС';
							break;
						case 11:
							$_SESSION['email_order_type_payment'] = 'Со счета мобильного телефона Билайн';
							break;
						case 12:
							$_SESSION['email_order_type_payment'] = 'Наличные в терминале оплаты Absolutplat';
							break;
						case 13:
							$_SESSION['email_order_type_payment'] = 'Наличные в терминале оплаты Pinpay';
							break;
						case 14:
							$_SESSION['email_order_type_payment'] = 'Наличные в терминале оплаты Мобил Элемент';
							break;
						case 15:
							$_SESSION['email_order_type_payment'] = 'Наличные в терминале оплаты Новоплат';
							break;
						case 16:
							$_SESSION['email_order_type_payment'] = 'Наличные в терминале оплаты Уникасса';
							break;
						case 17:
							$_SESSION['email_order_type_payment'] = 'Наличные в терминале оплаты Элекснет';
							break;
						case 18:
							$_SESSION['email_order_type_payment'] = 'Перевод в системе CONTACT';
							break;
						case 19:
							$_SESSION['email_order_type_payment'] = 'iPhone';
							break;
						case 20:
							$_SESSION['email_order_type_payment'] = 'QIWI кошелек';
							break;
						case 21:
							$_SESSION['email_order_type_payment'] = 'Банкомат ВТБ24';
							break;
						case 22:
							$_SESSION['email_order_type_payment'] = 'Банкомат Петрокоммерц';
							break;
						case 23:
							$_SESSION['email_order_type_payment'] = 'Евросеть';
							break;
						case 24:
							$_SESSION['email_order_type_payment'] = 'Связной';
							break;
						case 25:
							$_SESSION['email_order_type_payment'] = 'Интернет-банк Альфа-Клик';
							break;
						case 26:
							$_SESSION['email_order_type_payment'] = 'EasyPay';
							break;
						case 27:
							$_SESSION['email_order_type_payment'] = 'MoneyMail';
							break;
						case 28:
							$_SESSION['email_order_type_payment'] = 'RBK Money';
							break;
						case 29:
							$_SESSION['email_order_type_payment'] = 'TeleMoney';
							break;
						case 30:
							$_SESSION['email_order_type_payment'] = 'WebCreds';
							break;
						case 31:
							$_SESSION['email_order_type_payment'] = 'Z-Payment';
							break;
						case 32:
							$_SESSION['email_order_type_payment'] = 'ВКонтакте';
							break;
						case 33:
							$_SESSION['email_order_type_payment'] = 'Единый Кошелек';
							break;
						case 34:
							$_SESSION['email_order_type_payment'] = 'Деньги@Mail.Ru';
							break;
						case 35:
							$_SESSION['email_order_type_payment'] = 'Банковские карты Visa/MC через Platron';
							break;
						case 36:
							$_SESSION['email_order_type_payment'] = 'Банковской картой Robokassa.ru';
							break;
						case 37:
							$_SESSION['email_order_type_payment'] = 'Банковской карточй через Platezh.ru';
							break;
					}

					/*if ($send['receive_sms']) {

						switch ($state_order) {
							case 4: // оплачен
//								echo "41 - " . $phoneToSendSMS;
								$SmsText = new teController(515);
								break;
							case 8: // доставляется
								$SmsText = new teController(517);
								break;
							case 9: // доставлен
								$_SESSION['sms_variable_delivery'] = $db->get_one("SELECT name FROM " . CFG_DBTBL_DICT_RESULT_DELIVERY . " WHERE id = ?", $id_result_delivery);
								$SmsText = new teController(521);
								break;
						}

						if ($state_order == 4 || $state_order == 8 || $state_order == 9) {
							$parts = ceil(strlen($SmsText) / 65);
							Sms::send($phoneToSendSMS, iconv("UTF-8", "CP1251", $SmsText), 'obradoval', 1, $parts);
						}
					}*/
					// Вышлем Email
					if ($send['receive_mail']) {
						switch ($state_order) {
							
							case 9: // доставлен
								$state_order_str = 'доставлен';
								
								$EmailText = new teController(398);
								break;
						}

						if ($state_order == 9) {
							$mail_client = new PHPMailer();
							//Письмо менеджеру
							$mail_client->Subject = 'Ваш заказ № ' . $number . ' ' . $state_order_str;
							$mail_client->From = 'robot@bouquet.com.ua';
							$mail_client->FromName = '«Премиум Букет»';
							// Отправим письмо о заказе
							$mail_client->Body = "
								<html>
								<head>
									<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
								</head>
								<body>
									" . $EmailText . "
								</body>
								</html>
							";
							// Отправим письмо
							$mail_client->IsHTML(true);
							$mail_client->CharSet = "utf-8";
							$mail_client->AddAddress($send['email']);
							$mail_client->Send();
						}

					}
				}

			} else {
				// создаем

				$db->insert(CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO, array(
					'id_order' => $id_order,
					'delivery_date' => $delivery_date,
					'type_delivery' => $type_delivery,
					'time' => $delivery_time,
					'id_contact_in_order' => $recipientData['id_contact_in_order'],
					'id_address_storage' => $recipientData['id_address_storage'],
					'specify_name' => $specify_name,
					'take_photo' => $take_photo,
					'allow_placement_photo' => $allow_placement_photo,
					'text_card' => $text_card,
					'comments' => $comments,
					'type_payment' => $type_payment,
					'count_bonuses' => $count_bonuses,
					'sum' => $sum,
					'price' => $price,
					'number' => $number,
					'count_discount' => $count_discount,
					'price_delivery' => $price_delivery,
					'id_result_delivery' => $id_result_delivery,
					'fact_delivery_date' => $fact_delivery_datetime,
					'id_source_order' => $id_source_order,
					'florist_select' => $florist_select,
					'driver_select' => $driver_select,
					'base_cost' => $base_cost,
					'id_user_created' => $username,
					'is_pay' => $is_pay,
					'id_promocode' => $promo_discount['id']
				));

				// delivery
				$db->insert(
					CFG_DBTBL_MOD_ORDER_DELIVERY,
					array(
						'id_type_delivery' => $type_delivery,
						'id_order' => $id_order,
						'id_place_delivery' => $id_place_delivery,
						'km_count' => $km_count
					)
				);


				$count_res = preg_match_all("/\+(\d+) \((\d+)\) (\d+)/i", $main_phone_client, $matches);
				$phoneToSendSMS = $matches[1][0] . $matches[2][0] . $matches[3][0];

				if ($send = $db->get_one("SELECT receive_sms FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE id = ?", $id_client)) {

					$_SESSION['sms_order_number'] = $number;
					$_SESSION['sms_order_city'] = $db->get_one("SELECT dc.name FROM " . CFG_DBTBL_DICT_CITY . " AS dc, " . CFG_DBTBL_MOD_PRODUCT . " AS mp WHERE mp.id = ? AND mp.id_city = dc.id LIMIT 1", key($products));
					switch ($type_delivery) {
						case 2:  // 3 часа
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+3 hour', strtotime($delivery_time)));
							break;
						case 3:  // 1 час
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+1 hour', strtotime($delivery_time)));
							break;
						case 4: // точное время
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. в ' . date('H:i', strtotime($delivery_time));
							break;
						case 5:
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. самовывозом';
							break;
						case 6:  // 3 часа за пределы МКАД
							$_SESSION['sms_order_city'] = $db->get_one("SELECT mp.name FROM " . CFG_DBTBL_MOD_PLACES . " AS mp WHERE mp.id = ?", $id_place_delivery);
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+3 hour', strtotime($delivery_time)));
							break;
						case 7:  // 2 часа
							$_SESSION['sms_order_delivery_date'] = date('d.m.Y', strtotime($delivery_date)) . 'г. с ' . date('H:i', strtotime($delivery_time)) . '  по ' . date('H:i', strtotime('+2 hour', strtotime($delivery_time)));
							break;
						default:
							$_SESSION['sms_order_delivery_date'] = ' не определено - ' . $type_delivery;
							break;
					}

					$SmsText = new teController(511);
					$parts = ceil(strlen($SmsText) / 65);

					Sms::send($phoneToSendSMS, iconv("UTF-8", "CP1251", $SmsText), 'obradoval', 1, $parts);
				}

			}

		}

		// Тип формы собственности клиента (физлицо или юрлицо)
		$client_data = $db->get_one("
			SELECT is_ph_person, type_client
				FROM " . CFG_DBTBL_MOD_CLIENT . "
			WHERE id = ?
		", $id_client);
		$is_ph_person = (int) $client_data['is_ph_person'];

		if (!intval($is_ph_person)) {
//			echo " | yur";
			// Если это юридическое лицо пересчитаем скидку этого лица в соответствии с нормативами
			makeDiscountForLegalEntity($id_client);
		} else {
//			echo " | fiz";
			// Если физическое лицо, то отключим все бонусы которые прекратили свое действие по дате но еще не отмеченны как не действующие
			deactivateExpiredBonuses($id_client);

			// Если статус заказа олпачен, передан в производство, передан партнеру, собран, доставляется, доставлен или проставлен признак заказа - оплачен
			if ((($state_order == 9) || $is_pay) && $client_data['type_client'] == 1) {
//				echo " | status accepted";
				// Расчитаем бонусный процент
				$bonus_percent = calculateBonusPercentForIndividuals($id_client, $id_order);
//				echo " | bonus_percent = " . $bonus_percent;

				// Удалим двойные записи бонусов на заказ если есть
				deletedErrorBonuses($id_order);

				// Получим сумму уже начисленных бонусов по данному заказу
				$sum_exist_bonus = (int) $db->get_one_("SELECT SUM(bonus) FROM " . CFG_DBTBL_MOD_CLIENT_BONUS . " WHERE type_bonus_action = 1 AND id_order = ?", $id_order);
//				echo " | sum_exist_bonus = " . $sum_exist_bonus;

				//$bonusSubSum - стоимость товаров которые не должны участвовать в начислении бонусов (по максимуму кладем в объем товаров компенсированных бонусами использованными при этом заказе - то есть в пользу клиента)
				//$bonusAddSumCost - стоимость товаров которые имею специфичную бонусную политику (действует только если заказан один товар)
				//$bonusAddSumBons - количество бонусов на товары со специфичной бонусной политикой (действует только если заказан один товар)
				$podarkiSum = $db->get_one("
					SELECT SUM(mop.sum)
						FROM " . CFG_DBTBL_MOD_ORDER_PRODUCT . " AS mop
							LEFT JOIN " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcp ON mcp.id_product = mop.id_product
							LEFT JOIN " . CFG_DBTBL_DICT_CATEGORY . " AS dc ON dc.id = mcp.id_category
							LEFT JOIN " . CFG_DBTBL_DICT_MAIN_CATEGORY . " AS dmc ON dmc.id = dc.id_main_category
						WHERE mop.id_order = ?
						  AND (dmc.id = 4 OR mop.id_product = 1163)
				", $id_order);

				// Расчет бонусов которые должны были быть начисленны по данному заказу
				if ($bonusAddSumBons) {
					$bonus_for_this_order = $bonusAddSumBons;
				} else {
					$bonus_for_this_order = $cost - $bonusSubSum;
					$sum_of_discont = $cost - $price;   // Сумма дисконта сформировавшаяся с учетом и бонусов использованных и скидочного процента.

					if ($sum_of_discont > $bonusSubSum) {
						$bonus_for_this_order = round(intval($price) / 100 * $bonus_percent);
					} else {
						$bonus_for_this_order = round(intval($price - ($bonusSubSum - $sum_of_discont)) / 100 * $bonus_percent);
					}
				}
//				echo " | bonus_for_this_order = " . $bonus_for_this_order . " | sum_exist_bonus = " . $sum_exist_bonus;

				// Если бонусы не до начисленны то начислим
				if ($bonus_for_this_order != $sum_exist_bonus && $bonus_for_this_order > 0) {
					// Если бонусы на этот заказ уже начислялись
					if ($sum_exist_bonus) {
//						echo " -111";
						$db->update(CFG_DBTBL_MOD_CLIENT_BONUS, array(
							'bonus' => $bonus_for_this_order,
						), array(
							'id_order' => $id_order,
							'type_bonus_action' => 1
						));
					} else {
//						echo " -222";
						$db->insert(CFG_DBTBL_MOD_CLIENT_BONUS, array(
							'id_client' => $id_client,
							'bonus' => $bonus_for_this_order,
							'idate' => date('Y-m-d H:i:s'),
							'type_bonus_action' => 1,
							'comment' => '',
							'id_order' => $id_order,
							'duration' => (int) $site_config['bonus_discount_individuals_actual_day'],
							'is_active' => 1
						));
					}
				}
			} else {
				$db->delete(CFG_DBTBL_MOD_CLIENT_BONUS, array('id_order' => $id_order));
			}
		}
//		die();

		if ($attributes['without_redirect']) {
			Location(sprintf($_XFA['form'], $type, $id), 0);
		} else {
			Location($_XFA['main'], 0);
		}

	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>