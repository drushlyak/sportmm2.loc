<?php

	$_SESSION['t_mod_order_grid']['filter'] = 1;
	$_SESSION['t_mod_order_grid']['filter_data'] = array(
		'id_client' => (int) $attributes['id_client']
	);
	$_SESSION['t_mod_order_grid']['filter_fields'] = array('id_client');

	Location($_XFA['client_orders_redirect'], 0);