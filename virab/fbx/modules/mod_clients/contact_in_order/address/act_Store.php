<?php
	/**
	 * =====================================================================
	 * Обработка входных данных
	 * =====================================================================
	 */

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'id_client' => array(
			'type' => 'int'
		),
		'id_contact' => array(
			'type' => 'int'
		),
		'type_of_address' => array(
			'type' => 'int'
		),
		'company' => array(
			'type' => 'string',
			'trim' => true
		),
		'institution_name' => array(
			'type' => 'string',
			'trim' => true
		),
		'department' => array(
			'type' => 'string',
			'trim' => true
		),
		'city' => array(
			'type' => 'string',
			'trim' => true
		),
		'street' => array(
			'type' => 'string',
			'trim' => true
		),
		'house' => array(
			'type' => 'string',
			'trim' => true
		),
		'building' => array(
			'type' => 'string',
			'trim' => true
		),
		'entrance' => array(
			'type' => 'string',
			'trim' => true
		),
		'porch' => array(
			'type' => 'string',
			'trim' => true
		),
		'hotel_room' => array(
			'type' => 'string',
			'trim' => true
		),
		'ward' => array(
			'type' => 'string',
			'trim' => true
		),
		'flat' => array(
			'type' => 'string',
			'trim' => true
		),
		'office' => array(
			'type' => 'string',
			'trim' => true
		),
		'doorphone' => array(
			'type' => 'string',
			'trim' => true
		)
	));

	$id  = $attributes['id'];
	$type  = $attributes['type'];
	$id_client  = $attributes['id_client'];
	$id_contact  = $attributes['id_contact'];
	$type_of_address  = $attributes['type_of_address'];
	$company = $attributes['company'];
	$institution_name = $attributes['institution_name'];
	$department = $attributes['department'];
	$city = $attributes['city'];
	$street = $attributes['street'];
	$house = $attributes['house'];
	$building = $attributes['building'];
	$entrance = $attributes['entrance'];
	$porch = $attributes['porch'];
	$hotel_room = $attributes['hotel_room'];
	$ward = $attributes['ward'];
	$flat = $attributes['flat'];
	$office = $attributes['office'];
	$doorphone = $attributes['doorphone'];

	$params = array(
		'type_of_address' => $type_of_address,
		'company' => $company,
		'institution_name' => $$institution_name,
		'department' => $department,
		'city' => $city,
		'street' => $street,
		'house' => $house,
		'building' => $building,
		'entrance' => $entrance,
		'porch' => $porch,
		'hotel_room' => $hotel_room,
		'ward' => $ward,
		'flat' => $flat,
		'office' => $office,
		'doorphone' => $doorphone
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (!$type_of_address) {
		$FORM_ERROR .= _("Необходимо выбрать тип адреса") . "<br />";
	}

	if (!$FORM_ERROR) {

		$new_data = array(
			'type_of_address' => $type_of_address,
			'company' => $company,
			'institution_name' => $$institution_name,
			'department' => $department,
			'city' => $city,
			'street' => $street,
			'house' => $house,
			'building' => $building,
			'entrance' => $entrance,
			'porch' => $porch,
			'hotel_room' => $hotel_room,
			'ward' => $ward,
			'flat' => $flat,
			'office' => $office,
			'doorphone' => $doorphone
		);

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_ADDRESS_STORAGE, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_ADDRESS_STORAGE, $new_data);

			$db->insert(CFG_DBTBL_MOD_CONTACT_IN_ORDER_ADDRESSES, array('id_address_storage' => $idClient, 'id_contact_in_order' => $id_contact));
		}

		Location(sprintf($_XFA['cio_address'], $id_client, $id_contact), 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['cio_address_formf'], 1, $id_client, $id_contact, serialize($params)), 0);
	}

?>