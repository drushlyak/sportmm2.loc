<?php
	global $__TYPE_PAYMENT;
	$id  = (int)$attributes['id'];
	$type = (int) $attributes['type'];

	// Проверка доступа
	if ($id && ($type == 2)) {
		if (!$auth_in->aclCheck($resourceId, EDIT)) {
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)) {
			$ACL_ERROR = _("У вас нет прав на создание");
			return;
		}
	}

	if ($id) {
		$mod_data = $db->get_row("
			SELECT mo.id AS id_order,
					mo.*

			FROM " . CFG_DBTBL_MOD_ORDER . " AS mo
					WHERE mo.id = ?
		", $id );
	} else {
		$mod_data = array(
			'number' => rand(100000,999999),
			'date_order' => date("Y-m-d H:i:s"),
			'delivery_date' => date("Y-m-d"),
			'delivery_time' => date("H:i:s"),

		);
	}

	$mod_data['type_payment_dict'] = $__TYPE_PAYMENT;//$db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_TYPE_PAYMENT);




	// заказанные продукты
	$mod_data['products'] = $db->get_all("
	SELECT *, mp.main_foto50 AS photo
		FROM " . CFG_DBTBL_MOD_ORDER_PRODUCT . " AS mop
		   , " . CFG_DBTBL_MOD_PRODUCT . " AS mp
		WHERE mop.id_order = ?
		  AND mop.id_product = mp.id
	", $id);


	//print_r($mod_data);

	if(!is_array($mod_data)){
		Location($_XFA['main'], 0);
	}

