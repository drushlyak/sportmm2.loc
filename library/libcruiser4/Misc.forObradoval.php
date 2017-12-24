<?php

	/**
	 * @author: .ter (rou.terra@gmail.com)
	 * @copyright Cruiser (cruiser.com.ua)
	 */

	/**
	 * Форматирование строчки адреса
	 *
	 * @param array $addressSet
	 * @return string
	 */
	function formatAddressString($addressSet) {
		$view = array(
			// рабочий
			1 => array(
				array( 'field' => 'company', 'prefix' => 'компания: "', 'after' => '"' ),
				array( 'field' => 'city', 'prefix' => 'г. ', 'after' => '' ),
				array( 'field' => 'street', 'prefix' => 'ул. ', 'after' => '' ),
				array( 'field' => 'house', 'prefix' => 'д. ', 'after' => '' ),
				array( 'field' => 'building', 'prefix' => 'строение ', 'after' => '' ),
				array( 'field' => 'entrance', 'prefix' => 'корпус ', 'after' => '' ),
				array( 'field' => 'porch', 'prefix' => 'подъезд ', 'after' => '' ),
				array( 'field' => 'office', 'prefix' => 'офис ', 'after' => '' ),
				array( 'field' => 'doorphone', 'prefix' => 'код домофона: ', 'after' => '' )
			),
			// домашний
			2 => array(
				array( 'field' => 'city', 'prefix' => 'г. ', 'after' => '' ),
				array( 'field' => 'street', 'prefix' => 'ул. ', 'after' => '' ),
				array( 'field' => 'house', 'prefix' => 'д. ', 'after' => '' ),
				array( 'field' => 'building', 'prefix' => 'строение ', 'after' => '' ),
				array( 'field' => 'entrance', 'prefix' => 'корпус ', 'after' => '' ),
				array( 'field' => 'porch', 'prefix' => 'подъезд ', 'after' => '' ),
				array( 'field' => 'flat', 'prefix' => 'кв. ', 'after' => '' ),
				array( 'field' => 'doorphone', 'prefix' => 'код домофона: ', 'after' => '' )
			),
			// частный дом
			3 => array(
				array( 'field' => 'city', 'prefix' => 'г. ', 'after' => '' ),
				array( 'field' => 'street', 'prefix' => 'ул. ', 'after' => '' ),
				array( 'field' => 'house', 'prefix' => 'д. ', 'after' => '' )
			),
			// гостиница
			4 => array(
				array( 'field' => 'institutionName', 'prefix' => 'Гостиница "', 'after' => '"' ),
				array( 'field' => 'city', 'prefix' => 'г. ', 'after' => '' ),
				array( 'field' => 'street', 'prefix' => 'ул. ', 'after' => '' ),
				array( 'field' => 'house', 'prefix' => 'д. ', 'after' => '' ),
				array( 'field' => 'building', 'prefix' => 'строение ', 'after' => '' ),
				array( 'field' => 'entrance', 'prefix' => 'корпус ', 'after' => '' ),
				array( 'field' => 'hotelRoom', 'prefix' => 'комната ', 'after' => '' )
			),
			// больница
			5 => array(
				array( 'field' => 'institutionName', 'prefix' => '"', 'after' => '"' ),
				array( 'field' => 'department', 'prefix' => 'отделение ', 'after' => '' ),
				array( 'field' => 'city', 'prefix' => 'г. ', 'after' => '' ),
				array( 'field' => 'street', 'prefix' => 'ул. ', 'after' => '' ),
				array( 'field' => 'house', 'prefix' => 'д. ', 'after' => '' ),
				array( 'field' => 'building', 'prefix' => 'строение ', 'after' => '' ),
				array( 'field' => 'entrance', 'prefix' => 'корпус ', 'after' => '' ),
				array( 'field' => 'ward', 'prefix' => 'палата ', 'after' => '' )
			)
		);

		$addA = array();
		if (is_array($view[$addressSet['type_of_address']])) {
			foreach($view[$addressSet['type_of_address']] as $fields) {
				if ($addressSet[ $fields['field'] ]) {
					$addA[] = $fields['prefix'] . $addressSet[ $fields['field'] ] . $fields['after'];
				}
			}
		}

		return join(", ", $addA);
	}

	//формирование адреса клиента
	function formatAddressClient($addressSet) {
		$view = array(
			// домашний
			1 => array(
				array( 'field' => 'country', 'prefix' => '', 'after' => '' ),
				array( 'field' => '_city', 'prefix' => 'г. ', 'after' => '' ),
				array( 'field' => 'street', 'prefix' => 'ул. ', 'after' => '' ),
				array( 'field' => 'house', 'prefix' => 'д. ', 'after' => '' ),
				array( 'field' => 'building', 'prefix' => 'строение ', 'after' => '' ),
				array( 'field' => 'entrance', 'prefix' => 'корпус ', 'after' => '' ),
				array( 'field' => 'porch', 'prefix' => 'подъезд ', 'after' => '' ),
				array( 'field' => 'flat', 'prefix' => 'кв. ', 'after' => '' ),
				array( 'field' => 'doorphone', 'prefix' => 'код домофона: ', 'after' => '' )
			)
		);

		$addA = array();
		if (is_array($view[1])) {
			foreach($view[1] as $fields) {
				if ($addressSet[ $fields['field'] ]) {
					$addA[] = $fields['prefix'] . $addressSet[ $fields['field'] ] . $fields['after'];
				}
			}
		}

		return join(", ", $addA);
	}

	/**
	 * Получение названия типа адреса
	 *
	 * @param int $idType
	 * @return string
	 */
	function getNameTypeOfAddress($idType) {
		$typeOfAddressDictionary = array('Рабочий', 'Домашний', 'Частный дом', 'Гостиница', 'Больница');

		return $typeOfAddressDictionary[$idType - 1];
	}

	/**
	 * Make an http POST request and return the response content and headers
	 * @param string $url url of the requested script
	 * @param array $data hash array of request variables
	 * @return returns a hash array with response content and headers in the following form:
	 * array (
	 *			'content'=>'<html></html>',
	 * 			'headers'=>array ('HTTP/1.1 200 OK', 'Connection: close', ...)
	 * )
	*/
	function httpPost($url, $data) {
		$data_url = http_build_query($data);
		$data_len = strlen($data_url);

		return array(
			'content' => file_get_contents(
				$url,
				false,
				stream_context_create(array(
					'http' => array(
						'method' => 'POST',
						'header' => "Connection: close\r\nContent-Length: $data_len\r\n",
						'content' => $data_url
					)
				))
			)
		);
	}

	/**
	 * Добавление нового телефона в хранилище
	 *
	 * @return int
	 */
	function addNewPhoneToStorage($phone, $isMobile) {
		global $db;

		return $db->insert(
			CFG_DBTBL_MOD_PHONES_STORAGE,
			array(
				'phone' => $phone,
				'is_mobile' => $isMobile
			)
		);
	}

	/**
	 * @param int $idClient
	 * @param array $phoneData
	 *
	 * array(
	 * 		'codeCountry' => '',
	 * 		'codeOperator' => '',
	 * 		'number' -> '',
	 * 		'isMobile' => false
     * )
	 *
	 * @return void
	 */
	function addNewPhoneToClient($idClient, $phoneData) {
		global $db;

		$IDphoneStorage = addNewPhoneToStorage(
			(
				'+' .
				$phoneData['codeCountry'] .
				' (' . $phoneData['codeOperator'] . ') ' .
				$phoneData['number']
			),
			$phoneData['isMobile']
		);

		// запишем новую связку клиента с телефоном
		$db->insert(CFG_DBTBL_MOD_CLIENT_PHONES, array(
			'id_client' => $idClient,
			'id_phone_storage' => $IDphoneStorage
		));
	}

	/**
	 * Добавление нового получателя в хранилище
	 *
	 * @param $data
	 * @return int
	 */
	function insertNewRecipient($IDclient, $data) {
		global $db;

		$fio = $data['lastName'] . ' ' . $data['firstName'] . ' ' . $data['patronymic'];

		$idRecipient = (int) $db->insert(CFG_DBTBL_MOD_CONTACT_IN_ORDER, array(
			'fio' => htmlspecialchars($fio, ENT_QUOTES),
			'is_himself' => ((int) $data['typeOfKnowledge'] === 3 ? 1 : 0)
		));

		// связка
		$db->insert(CFG_DBTBL_MOD_CLIENT_RECIPIENT, array(
			'id_contact_in_order' => $idRecipient,
			'id_client' => $IDclient
		));

		if ((int) $data['typeOfKnowledge'] !== 3) {
			// телефон
			$mainPhone = '+' .
						$data['mainPhoneCodeCountry'] .
						' (' . $data['mainPhoneCodeOperator'] . ') ' .
						$data['mainPhoneNumber'];
			$mainPhoneIsMobile = (int) $data['mainPhoneIsMobile'];

			addNewRecipientPhone($idRecipient, $mainPhone, $mainPhoneIsMobile);
		}

		return $idRecipient;
	}

	/**
	 * Добавление телефоного номера получателя
	 *
	 * @param  $idRecipient
	 * @param  $mainPhone
	 * @param  $mainPhoneIsMobile
	 * @return int
	 */
	function addNewRecipientPhone($idRecipient, $mainPhone, $mainPhoneIsMobile) {
		global $db;

		$newIDphone = addNewPhoneToStorage($mainPhone, $mainPhoneIsMobile);

		// связка телефона
		$db->insert(CFG_DBTBL_MOD_CONTACT_IN_ORDER_PHONES, array(
			'id_contact_in_order' => $idRecipient,
			'id_phone_storage' => $newIDphone
		));

		return $newIDphone;
	}

	/**
	 * Создание номера заказа
	 *
	 * @return void
	 */
	function createOrderNumber() {
		global $db;

		srand((double) microtime() * 1000000);
		$uid = rand(100000, 999999);

		$id_rec = $db->get_one("
			SELECT id
			FROM " . CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO . "
			WHERE number = ?
		", $uid);
		if ($id_rec) {
			$uid = createOrderNumber();
		}

		return $uid;
	}

	/**
	 * @param  $id_client
	 * @return void
	 */
	function getClientRecipients($id_client) {
		global $db;

		$contacts = $db->get_all("
			 SELECT *
				FROM " . CFG_DBTBL_MOD_CLIENT_RECIPIENT . " cr
					JOIN " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " cio ON cio.`id` = cr.`id_contact_in_order`
			 WHERE cr.`id_client` = ?
				 AND NOT cio.`is_himself`
		", $id_client);

		$clientContacts = array(
			array(
				'id' => '0-0',
				'fio' => 'Получатель - сам клиент'
			)
		);
		if (is_array($contacts)) {
			foreach ($contacts as $contact) {
				$res = $db->get_all("
					SELECT mas.*
						FROM " . CFG_DBTBL_MOD_ADDRESS_STORAGE . " mas
							JOIN " . CFG_DBTBL_MOD_CONTACT_IN_ORDER_ADDRESSES . " mcioa ON mcioa.`id_address_storage` = mas.`id`
					WHERE mcioa.`id_contact_in_order` = ?
				", $contact['id_contact_in_order']);

				if (is_array($res)) {
					foreach ($res as &$r) {
						$r['id'] = $contact['id_contact_in_order'] . "-" . $r['id'];
						$r['fio'] = $contact['fio'];

						$clientContacts[] = $r;
					}
				} else {
					// известен, наверное только телефон
					$phone = $db->get_one("
						SELECT mps.`phone`
							FROM " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " mco
								JOIN " . CFG_DBTBL_MOD_CONTACT_IN_ORDER_PHONES . " mcr ON mcr.`id_contact_in_order` = mco.`id`
								JOIN " . CFG_DBTBL_MOD_PHONES_STORAGE . " mps ON mps.`id` = mcr.`id_phone_storage`
						WHERE mco.`id` = ?
					", $contact['id_contact_in_order']);

					$clientContacts[] = array(
						'id' => $contact['id_contact_in_order'] . "-0",
						'fio' => $contact['fio'] . " (только телефон: " . $phone . ")"
					);
				}
			}
		}

		foreach ($clientContacts as &$clientContact) {
			if (!preg_match('/^[\d]+-0/im', $clientContact['id'])) {
				$clientContact['name'] = $clientContact['fio'] . " (" . formatAddressString($clientContact) . ")";
			} else {
				$clientContact['name'] = $clientContact['fio'];
			}
		}

		return $clientContacts;
	}

	function getClientRecipientsLight($id_client) {
		global $db;

		$contacts = $db->get_all("
			 SELECT cio.id,
			 		cio.fio
				FROM " . CFG_DBTBL_MOD_CLIENT_RECIPIENT . " cr
					JOIN " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " cio ON cio.`id` = cr.`id_contact_in_order`
			WHERE cr.`id_client` = ?
					AND NOT cio.`is_himself`
			ORDER BY cio.`id`
		", $id_client);

		return is_array($contacts) ? $contacts : array();
	}

	/**
	 * Для записи получателя
	 *
	 * $param int $idClient
	 * @param string $strRecipient_Address в формате 2-12 (0-0 = получатель сам)
	 * @param array $addressStorageRec массив для записи в mod_address_storage
	 *
	 * @return array
	 */
	function parseOrSetContactInOrder($idClient, $strRecipient_Address, $addressStorageRec = array()) {
		global $db;

		if ($strRecipient_Address === '0-0') {
			// получает сам, нужно создать записи
			$id_recip = $db->insert(CFG_DBTBL_MOD_CONTACT_IN_ORDER, array(
				'fio' => '',
				'is_himself' => 1
			));

			$id_addr = $db->insert(CFG_DBTBL_MOD_ADDRESS_STORAGE, array(
				'type_of_address' => 2, // домашний
				'company' => '',
				'institution_name' => '',
				'department' => '',
				'city' => $addressStorageRec['city'],
				'street' => $addressStorageRec['street'],
				'house' => $addressStorageRec['house'],
				'building' => $addressStorageRec['building'],
				'entrance' => $addressStorageRec['entrance'],
				'porch' => $addressStorageRec['porch'],
				'hotel_room' => '',
				'ward' => '',
				'flat' => $addressStorageRec['flat'],
				'office' => '',
				'doorphone' => $addressStorageRec['doorphone']
			));

			// связки

			$db->insert(CFG_DBTBL_MOD_CLIENT_RECIPIENT, array(
				'id_client' => $idClient,
				'id_contact_in_order' => $id_recip
			));
			$db->insert(CFG_DBTBL_MOD_CONTACT_IN_ORDER_ADDRESSES, array(
				'id_contact_in_order' => $id_recip,
				'id_address_storage' => $id_addr
			));
		} else {
			list($id_recip, $id_addr) = explode('-', $strRecipient_Address);
		}

		return array(
			'id_contact_in_order' => $id_recip,
			'id_address_storage' => $id_addr
		);
	}

	/**
	 * Получение ФИО получателя
	 *
	 * @param int $id_contact_in_order
	 * @return void
	 */
	function getRecipientFIO($id_contact_in_order) {
		global $db;

		$fio = $db->get_one("
			SELECT IF (mco.`is_himself`, CONCAT(mc.`f_name`, ' ', mc.`i_name`, ' ', mc.`o_name`), mco.fio) AS fio
				FROM " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " mco
					JOIN " . CFG_DBTBL_MOD_CLIENT_RECIPIENT . " mcr ON mcr.`id_contact_in_order` = mco.`id`
					JOIN " . CFG_DBTBL_MOD_CLIENT . " mc ON mc.`id` = mcr.`id_client`
			WHERE mco.`id` = ?
		", $id_contact_in_order);

		return $fio ? $fio : "-";
	}

	/**
	 * Получение телефонов получателя
	 *
	 * @param int $id_contact_in_order
	 * @return void
	 */
	function getRecipientPhones($id_contact_in_order) {
		global $db;

		$phones = $db->get_vector("
			SELECT mc.`phone`
				FROM " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " mco
					JOIN " . CFG_DBTBL_MOD_CLIENT_RECIPIENT . " mcr ON mcr.`id_contact_in_order` = mco.`id`
					JOIN " . CFG_DBTBL_MOD_CLIENT . " mc ON mc.`id` = mcr.`id_client`
			WHERE mco.`id` = ? AND mco.`is_himself`
			UNION
			SELECT CONCAT(
						mps.`phone`,
						IF(mps.`is_mobile`, ' (моб.)', '')
					) AS phone
				FROM " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " mco
					JOIN " . CFG_DBTBL_MOD_CONTACT_IN_ORDER_PHONES . " mcr ON mcr.`id_contact_in_order` = mco.`id`
					JOIN " . CFG_DBTBL_MOD_PHONES_STORAGE . " mps ON mps.`id` = mcr.`id_phone_storage`
			WHERE mco.`id` = ? AND NOT mco.`is_himself`
		", $id_contact_in_order
		 , $id_contact_in_order );

		return is_array($phones) ? $phones : array();
	}

	/**
	 * Преобразование телефона в набор полей
	 *
	 * @param array $phones
	 * @return array
	 */
	function reformatPhones($phones = array()) {
		$cPhones = array();
		if (is_array($phones)) {
			foreach ($phones as $phone) {
				if (preg_match('/^\+([\d]+) \(([\d]+)\) ([\d]+)/im', $phone['phone'], $regs)) {
					$cPhones[$phone['id']] = array(
						'phoneCodeCountry' => $regs[1],
						'phoneCodeOperator' => $regs[2],
						'phoneNumber' => $regs[3],
						'isMobile' => $phone['is_mobile']
					);
				} else {
					$cPhones[$phone['id']] = array(
						'phoneCodeCountry' => "",
						'phoneCodeOperator' => "",
						'phoneNumber' => "",
						'isMobile' => $phone['is_mobile']
					);
				}
			}
		}

		return $cPhones;
	}

	/**
	 * @param array $arrAddr
	 * @return bool
	 */
	function checkFillAddressField($arrAddr = array()) {
		$changed = false;

		foreach ($arrAddr as $key => $val) {
			if ($key === 'type_of_address') continue;

			if (!empty($val)) {
				$changed = true;
			}
		}

		return $changed;
	}

	/**
	 * @param int $idClient
	 * @return int
	 */
	function calculateDiscountForLegalEntity($idClient) {
		global $site_config, $db;

		// Получим сумму всех заказов сделанных и оплаченных за опреденный период
		$sum_orders = $db->get_one("SELECT SUM(moai.price)
									FROM " . CFG_DBTBL_MOD_ORDER . " AS mo
									   , " . CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO . " AS moai
									WHERE moai.id_order = mo.id
									  AND mo.id_client = ?
									  AND mo.date_order BETWEEN SUBDATE(NOW(), INTERVAL ? DAY) AND NOW()
									  AND ((mo.id_state_order >= 4 AND mo.id_state_order <= 9) OR (moai.is_pay = 1))
		", $idClient, $site_config['bonus_legal_entity_count_actual_day']);

		// Расчитаем новую актуальную скидку для юридического лица
		$discount_today = 0;
		if ($sum_orders <= $site_config['bonus_legal_entity_upper_limit_10']) {
			$discount_today = (int) $site_config['bonus_legal_entity_amount_discount_10'];
		} elseif ($sum_orders > $site_config['bonus_legal_entity_upper_limit_10'] && $sum_orders <= $site_config['bonus_legal_entity_upper_limit_50']) {
			$discount_today = (int) $site_config['bonus_legal_entity_amount_discount_50'];
		} elseif ($sum_orders > $site_config['bonus_legal_entity_upper_limit_50'] && $sum_orders <= $site_config['bonus_legal_entity_upper_limit_100']) {
			$discount_today = (int) $site_config['bonus_legal_entity_amount_discount_100'];
		} elseif ($sum_orders > $site_config['bonus_legal_entity_upper_limit_100'] && $sum_orders <= $site_config['bonus_legal_entity_upper_limit_200']) {
			$discount_today = (int) $site_config['bonus_legal_entity_amount_discount_200'];
		} elseif ($sum_orders > $site_config['bonus_legal_entity_upper_limit_200'] && $sum_orders <= $site_config['bonus_legal_entity_upper_limit_500']) {
			$discount_today = (int) $site_config['bonus_legal_entity_amount_discount_500'];
		} elseif ($sum_orders > $site_config['bonus_legal_entity_upper_limit_500']) {
			$discount_today = (int) $site_config['bonus_legal_entity_amount_discount_upper'];
		}

		return $discount_today;
	}

	/**
	 * @param int $idClient
	 * @return int
	 */
	function calculateBonusPercentForIndividuals($idClient, $idOrder) {
		global $site_config, $db;

		// Получим клоичество оплаченных заказов без учета указанного и тех которые позже.
		$count_orders = (int) $db->get_one("
			SELECT COUNT(mo.id)
				FROM " . CFG_DBTBL_MOD_ORDER . " AS mo
				   , " . CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO . " AS moai
				WHERE moai.id_order = mo.id
				  AND mo.id_client = ?
				  AND mo.id < ?
				  AND ((mo.id_state_order >= 4 AND mo.id_state_order <= 9) OR (moai.is_pay = 1))
		", $idClient, $idOrder);

		// Расчитем из количества заказов размер бонусного процента
		$bonus_percent = 0;
		if ($count_orders <= $site_config['bonus_discount_individuals_orders_count_1']) {
			$bonus_percent = (int) $site_config['bonus_discount_individuals_ammount_bonuse_1'];
		} elseif ($count_orders > $site_config['bonus_discount_individuals_orders_count_1'] && $count_orders <= $site_config['bonus_discount_individuals_orders_count_2']) {
			$bonus_percent = (int) $site_config['bonus_discount_individuals_ammount_bonuse_2'];
		} elseif ($count_orders > $site_config['bonus_discount_individuals_orders_count_2'] && $count_orders <= $site_config['bonus_discount_individuals_orders_count_3']) {
			$bonus_percent = (int) $site_config['bonus_discount_individuals_ammount_bonuse_3'];
		} else {
			$bonus_percent = (int) $site_config['bonus_discount_individuals_ammount_bonuse_upper'];
		}

		return $bonus_percent;
	}

	/**
	 * @param int $idClient
	 * @return int
	 */
	function makeDiscountForLegalEntity($idClient) {
		global $site_config, $db;

		// Получим текущее значение скидки у клиента
		$client = $db->get_row("SELECT discount, type_client FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE id = ?", $idClient);
		$discount_now = (int) $client['discount'];

		if (intval($client['type_client']) == 1) {

			// Получим значение скидки которое должно быть
			$discount_today = calculateDiscountForLegalEntity($idClient);

			// Если скидка различаются - добавим запись в историю скидок и обновим в записи о клиенте
			if (intval($discount_today) != $discount_now) {
				$db->insert(CFG_DBTBL_MOD_CLIENT_DISCOUNT, array(
					'id_client' => $idClient,
					'discount' => $discount_today,
					'idate' => date('Y-m-d H:i:s'),
					'comment' => 'автоматический перерасчет скидки юрлица от суммы заказов'
				));
				$db->update(CFG_DBTBL_MOD_CLIENT, array(
					'discount' => $discount_today
				), array(
					'id' => $idClient
				));
			}
		} else {
			return $discount_now;
		}

		return $discount_today;
	}

	/**
	 * @param int $idClient
	 * @return void
	 */
	function deactivateExpiredBonuses($idClient) {
		global $db;

		$db->query("UPDATE " . CFG_DBTBL_MOD_CLIENT_BONUS . " SET is_active = 0 WHERE is_active = 1 AND ADDDATE(idate, INTERVAL duration DAY) < NOW() AND id_client = ?", $idClient);

		return true;
	}

	/**
	 * @param int $idClient
	 * @return void
	 */
	function deletedErrorBonuses($idOrder) {
		global $db;

		// Проверим что по данному заказу только одна или менее записей бонусов и лишние как ошибку - удалим
		$order_bonuses_exist_all = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_CLIENT_BONUS . " WHERE id_order = ?", $idOrder);
		if (is_array($order_bonuses_exist_all)) {
			$teeemp = 0;
			foreach ($order_bonuses_exist_all as $ord_bonus) {
				if ($teeemp) {
					$db->delete(CFG_DBTBL_MOD_CLIENT_BONUS, array('id' => $ord_bonus['id']));
				}
				$teeemp++;
			}
		}

		return true;
	}
	
	function sum_order($product_cookie) {
		global $db;
		
		$summ = 0;
		if(count($product_cookie) != 0) {
		    foreach($product_cookie as $product_id => $value) {
				$product_data = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $product_id);
				$product_data['cost_excess'] = ($product_data['discount'] >= 1) ? round($product_data['cost_excess'] - ($product_data['cost_excess']*$product_data['discount']/100)) : $product_data['cost_excess'];
				$summ += $product_data['cost_excess'] * $value->count;
			}
		}
		return $summ;
	}
	function validEmail($email) {
		return preg_match('/\A(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)\Z/si', $email);
	}
