<?php
	//require_once(LIB_PATH . "/class.phpmailer.php");
	//require_once (LIB_PATH . "/MailSender.class.php");

	global $db, $site_config;

	$auth_submitted = (int) $_REQUEST['auth_submitted'];
	unset($_SESSION['pitanie_message_header'], $_SESSION['pitanie_message_txt']);

	$step3 = (int)$_REQUEST['step3'];

	// Определим номер запрошенного шага
	if(is_array($this->c_this['value'])){
		foreach($this->c_this['value'] as $elem){
			if(is_integer(strpos($elem, 'step'))){
				$step = intval(substr($elem, 4));
			}
		}
	}

	$step = ($step) ? $step : 2;

	$total_cost = (int)($_REQUEST['total-cost']);
	if($total_cost) { setcookie ("total_cost", $total_cost,time()+3600, "/"); }
	$city_order = ($step == 3) ? strip_tags(trim($_REQUEST['city_order'])) : $_COOKIE['city_order'];
	$street_order = ($step == 3) ? strip_tags(trim($_REQUEST['street_order'])) : $_COOKIE['street_order'];

	//$type_payment_order = ($step == 3) ? (int)($_REQUEST['type_payment_order']) : $_COOKIE['type_payment_order'];
	$step2 = ($step == 3) ? (int)$_REQUEST['step2'] : $_COOKIE['step2'];

	$date_order_day = strtok($date_order, '.');
	$date_order_mon = strtok('.');
	$date_order_yea = strtok('.');

	$product_cookie = json_decode($_COOKIE['basketPIDs'], true);

	if(count($product_cookie) != 0) {
		foreach($product_cookie as $product_id => $value) {
			$product_array_id[] = $product_id;
		}
	}

	if (empty($street_order) && $step == 3) {
		setcookie ("city_order", $city_order,time()+3600, "/");
		setcookie ("street_order", $street_order,time()+3600, "/");
		setcookie ("step2", $step2,time()+3600, "/");


		header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . SITE_URL . "/cart/to_order/step2/");
		header("Connection: close");
		exit;
	}

//	$max_delivery = 0; // $db->get_one("SELECT MAX(delivery) FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id IN (?@)", $product_array_id);
	//$sum_order = $db->get_one("SELECT SUM(cost_excess) FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id IN (?@)", $product_array_id);

	//Шаг 1 "Авторизация, Регистрация"
	/**
	 * ===============================================================================================================================
	 * Авторизация пользователя:
	 * ===============================================================================================================================
	 **/

	if(!$_SESSION['pitanie_auth']) {
		$_SESSION['pitanie_message_header'] = 'Авторизация';
		$_SESSION['pitanie_message_txt'] = 'Прежде чем перейти к следующим этапам - необходимо авторизоваться!';

		// Если переданны данные формы авторизации, то авторизуемся
		if ($auth_submitted) {
			$email    = strip_tags(trim($_REQUEST['email']));
			$pswd = strip_tags(trim($_REQUEST['pswd']));

			$user = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE email = ? AND password = ?", $email, md5($pswd));
			if (is_array($user)) {
				$_SESSION['pitanie_id_user'] = $user['id'];
				$_SESSION['pitanie_email'] = $user['email'];
				$_SESSION['pitanie_auth'] = 1;
				$_SESSION['pitanie_message_header'] = 'Авторизация';
				$_SESSION['pitanie_message_txt'] = $user['i_name'] . ', Вы успешно авторизовались!';
				$_SESSION['action'] = 'order';
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: " . SITE_URL . "/info_page/");
				header("Connection: close");
				exit;
			} else {
				$_SESSION['pitanie_message_header'] = 'Авторизация';
				$_SESSION['pitanie_message_txt'] = 'Неверный e-mail или пароль';
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: " . SITE_URL . "/info_page/");
				header("Connection: close");
				exit;
			}
		} else {
?>
	
	<ul class="registration-order-nav">
		<li class="active">1. авторизация</li>
		<li>2. доставка</li>
		<li>3. оплата</li>
	</ul>
	<p class="attention">Если вы уже заказывали, либо регистрировались, у нас, введите ваш e-mail и пароль</p>
	<div class="authorization">
		<div class="title">авторизация</div>
		<form action="/cart/to_order/step2/" method="POST">
			<p>e-mail:</p>
			<input type="text" placeholder="vovan@mail.ru" name="email" class="email">
			<p>пароль:</p>
			<input type="password" placeholder="Пароль" name="pswd" class="password">
			<input type="submit" value="войти" class="login-login">
			<input name="auth_submitted" type="hidden" value="1" />
			<a href="" class="remember">забыли пароль?</a>
		</form>
	</div>
	<div class="line"></div>

<?php
		}

		// Проинициализируем переменные
		$phone = $_REQUEST['phone'];
		$f_name = strip_tags(trim($_REQUEST['f_name']));
		$i_name = strip_tags(trim($_REQUEST['i_name']));
		$password = strip_tags(trim($_REQUEST['password']));
		$repitpassword = strip_tags(trim($_REQUEST['repitpassword']));
		$email = strip_tags(trim($_REQUEST['email']));
		$captcha = strip_tags(trim($_REQUEST['captcha']));
		$submitted = (int) $_REQUEST['submitted'];

		// Если переданны данные формы регистрации, то проверим введенные данные и зарегистрируем пользователя
		if ($submitted) {

			$error = false;
			$str_error = "";

			// проверки
			if (empty($email) || !validEmail($email)) {
				$error = true;
				$str_error .= _(" - Введите корректный email!<br />");
			} else {
				if ($db->get_one(sql_placeholder("SELECT id FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE email = ?", $email))) {
					$error = true;
					$str_error .= _(" - Этот email уже используется, если Вы забыли пороль, воспользуйтесь формой восстановления пароля <a href='/restore_password/'>тут</a>!<br />");
				}
			}
			if (empty($password)) {
				$error = true;
				$str_error .= _(" - Введите пароль!<br />");

			}
			if ($repitpassword !== $password) {
				$error = true;
				$str_error .= _(" - Пароли не совпадают!<br />");

			}
			if ($captcha !== $_SESSION['captcha_keystring']) {
				$error = true;
				$str_error .= _(" - Введенный Вами проверочный код не совпадает с указанным на картинке!<br />");
			}

			if ($error !== true) {
				$insert_id = $db->insert(CFG_DBTBL_MOD_CLIENT, array(
						'f_name' => $f_name,
						'i_name' => $i_name,
						'phone' => $phone,
						'email' => $email,
						'password' => md5($password)
				));

				//залогиневание пользователя
				$_SESSION['pitanie_id_user'] = $insert_id;
				$_SESSION['pitanie_email'] = $email;
				$_SESSION['pitanie_auth'] = 1;

				$_SESSION['email_client_name'] = $i_name;
				$_SESSION['email_client_fname'] = $f_name;
				$_SESSION['email_client'] = $email;
				$_SESSION['password_client'] = $password;

				$EmailText = new teController(410);

				// Подготовим данные для отправки письма
				$MailSender=new MailSender(array(
					"login"		=> "robot@premiumbuket.ru",
					"password"	=> "DLjsB5mr",
					"smtpHost"	=> "premiumbuket.ru"
				));

				// Отправим письмо о регистрации клиенту
				$MailSender->send(array(
					"fromFIO"		=> "PremiunBouquet",
					"fromMail"		=> "robot@premiumbuket.ru",
					"toMail"		=> $email,
					"subject"		=> "Регистрация на " . SITE_URL,
					"text"			=> "
											<html>
											<head>
												<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
											</head>
											<body>
											" . $EmailText . "
											</body>
											</html>
										",
					"logFileName"	=> "/tmp/mail_log_premiumbuket_ru"
				));

				// Отправим письмо о регистрации менеджеру
				/*$MailSender->send(array(
					"fromFIO"		=> "PremiunBouquet",
					"fromMail"		=> "robot@premiumbuket.ru",
					"toMail"		=> $site_config['manager_email'],
					"subject"		=> "Новый пользователь на " . SITE_URL,
					"text"			=> "
											<html>
											<head>
												<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
											</head>
											<body>
												Был успешно зарегистрирован новый пользователь на сайте <a href=\"" . SITE_URL . "\">PremiunBouquet.ru</a>.<br/><br/>
												<b>Его данные:</b><br/>
												Фамилия: " . $f_name . "<br/>
												Имя: " . $i_name . "<br/>
												Email: " . $email . "<br/>
												Телефон: " . $phone . "<br/>
												Просмотреть и отредактировать данные пользователя можно <a href=\"http://bouquet.cruiser.com.ua/virab/index.php?fuseaction=mod_clients.form&type=2&id=".$insert_id."\">тут</a>.
											</body>
											</html>
										",
					"logFileName"	=> "/tmp/mail_log_premiumbuket_ru"
				));*/
				?>
					<script type="text/javascript">
						setTimeout(function () {
							window.location = "<?=$__urlPREFIX?>/cart/to_order/step2/";
						}, 3000);
					</script>
					<h1>Вы успешно зарегистрировались!</h1>
				<?php
			}
		}

		if (!$submitted || $error === true) :
?>
	<div class="registration">
		<div class="title">регистрация</div>
		<?php 	echo ($str_error) ? "<font color='#FF0000'>" . $str_error . "</font><br>" : '';?>
		<form method="post" action="/cart/to_order/step2/">
			<p>фаамилия:</p>
			<input type="text" placeholder="Иванов" value="<?=$f_name?>" name="f_name" class="fullname">
			<p>имя:</p>
			<input type="text" placeholder="Владимир" value="<?=$i_name?>" name="i_name" class="name">
			<p>e-mail:</p>
			<input type="text" placeholder="vovan@mail.ru" value="<?=$email?>" name="email" class="email">
			<p>телефон:</p>
			<input type="text" placeholder="+38 050 999 99 99" value="<?=$phone?>" name="phone" class="email">
			<p>пароль:</p>
			<input type="password" placeholder="******" name="password" class="name">
			<p>повторите пароль:</p>
			<input type="password" placeholder="******" name="repitpassword" class="name">
			<p>введите текст с картинки:</p>
			<input type="text" name="captcha" class="email">
			<input type="submit" value="регистрация" class="login-registration">
			<img src="/htdocs/keyimg.php?<?=session_name()?>=<?=session_id()?>&rnd=<?=rand()?>" alt="<?=_("картинка с текстом")?>" class="capcha">
			<input name="submitted" type="hidden" value="1" />
		</form>
	</div>

<?php
		endif;

	} else {
		$_SESSION['pitanie_message_header'] = 'Авторизация';
		$_SESSION['pitanie_message_txt'] = 'Если Вы хотите изменить свои данные - Вы это можете сделать в <a href="/cabinet/">личном кабинете</a>!';

		//Шаг 2 "Доставка"


		if ($step == 2) {



			if (strlen($street_order) > 1 || $step2) {
				
				if(empty($street_order)) {
						$error = true;
						$str_error .= _(" - укажите адрес доставки!<br />");
				}
				
			}
		?>

			<ul class="registration-order-nav">
				<li>1. авторизация</li>
				<li class="active">2. доставка</li>
				<li>3. оплата</li>
			</ul>
			<form method="post" action="/cart/to_order/step3/">
				<div class="delivery-adress">
					<div class="title">адрес доставки</div>
					
					<p>Город</p>
					<input type="text" value="<?=$_COOKIE['city_order']?>" name="city_order" id="">
					<p>адрес</p>
					<input type="text" value="<?=$_COOKIE['street_order']?>" name="street_order" id="">					
				</div>
				<div class="line" style="margin-bottom:15px;"></div>
				<input type="hidden" value="1" name="step2">
				<input type="submit" value="продолжить" class="delivery-continue">
			</form>
<br></br>
	<?php
		} else {

			//Шаг 3 "Оплата"
			if($step == 3) {
				
				setcookie ("city_order", $city_order,time()+3600, "/");
				setcookie ("street_order", $street_order,time()+3600, "/");
				$total_cost = ($total_cost) ? $total_cost : $_COOKIE['total_cost'];

				//$type_payment = $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_TYPE_PAYMENT);
	
               	while(true) {
                	$number = rand(100000, 999999);
                    $order = (int) $db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_ORDER . " WHERE number = ?",$number);
                    if(!$order)
                    break;
                }

				?>
				
			<ul class="registration-order-nav">
				<li>1. авторизация</li>
				<li>2. доставка</li>
				<li class="active">3. оплата</li>
			</ul>
			<form action="/cart/to_order/step4/" method="POST">
				<div class="payment-payment">
					<div class="radioblock">
						<div class="radio" value="1">НАЛИЧНЫМИ КУРЬЕРУ (ПО СЕВАСТОПОЛЮ)</div>
						<div class="radio" value="2">ПО ПРЕДОПЛАТЕ</div>
						<input type="hidden" name="type_payment"/>
					</div>
				</div>
				<div class="line"></div>
				<div class="payment-user-data">
					<p>Номер заказа: <?=$number?></p>
					<p>Адрес доставки: <?=(($city_order) ? $city_order . ', ' : '') . $street_order?></p>
					<p>сумма к оплате: <span><?=$total_cost?> руб</span>. без учета доставки</p>
				</div>
				<div class="line" style="margin-bottom: 20px;"></div>
				<input type="submit" value="оплатить" class="payment-pay">
				<input type="submit" onClick="window.location='/cart/to_order/step2/'; return false;" value="назад" class="payment-back">
				<input type="hidden" value="1" name="step3">
                <input type="hidden" value="<?=$total_cost?>" name="total-cost">
                <input type="hidden" value="<?=$city_order?>" name="city_order">
                <input type="hidden" value="<?=$street_order?>" name="street_order">              
                <input type="hidden" value="<?=$number?>" name="number_order">
			</form>
			

	<?php
			} else {
			echo "Заказ создан";
			$number = (int)($_REQUEST['number_order']);
			$type_payment_order = (int)($_REQUEST['type_payment']);
			$client_data = $db->get_all("SELECT *, CONCAT(f_name,' ',i_name) as fio FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE id = ?",$_SESSION['pitanie_id_user']);
			/*$id_order = $db->insert_(CFG_DBTBL_MOD_ORDER, array(
				'id_client' => $_SESSION['pitanie_id_user'],
				'date_order' => date("Y-m-d H:i:s"),
				'type_payment' => $type_payment_order,
				'price' => $total_cost,
				'number' => $number,
				'delivery_address' => (($city_order) ? $city_order . ', ' : '') . $street_order,
				'id_state_order' => 1
			));*/
			
			$sql = sql_placeholder("INSERT INTO " . CFG_DBTBL_MOD_ORDER . " 
						SET id_client = ?
						  , date_order = ?
						  , type_payment = ?
						  , price = ?
						  , number = ? 
						  , delivery_address = ?
						  , id_state_order = 1",
						$_SESSION['pitanie_id_user']
					  , date("Y-m-d H:i:s")
					  , $type_payment_order
					  , $total_cost
					  , $number
					  , (($city_order) ? $city_order . ', ' : '') . $street_order
			);
			$db->query($sql);
			$insert_id = $db->insert_id;

			setcookie ("payment_id_order", $insert_id, time()+3600, "/");
			setcookie ("payment_sum_order", $total_cost, time()+3600, "/");

			$product_cookie = json_decode($_COOKIE['basketPIDs'], true);
			foreach($product_cookie as $product_id => $value) {
				if($value[count] > 0) {
					$pr = $db->get_row("SELECT article, name, main_foto80, chpu, cost_excess FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $product_id);
					$db->insert(CFG_DBTBL_MOD_ORDER_PRODUCT, array(
						'id_order' => $insert_id,
						'id_product' => $product_id,
						'count' => $value[count],
						'price' => $pr['cost_excess']
					));
					$OrderProductsString .= $pr['article'] . ' | "' . $pr['name'] . '" (' . $value['count'] . 'шт) - ' . $pr['cost_excess'] . 'р. <br/>';
					$OrderProductsStringWithFoto .= '<img src="' . SITE_URL . $pr['main_foto80'] . '" border="0" alt=""> | ' . $pr['article'] . ' | <a href="' . SITE_URL . '/product/' . $pr['chpu'] . '/' . '" target="_blank">' . $pr['name'] . '"</a> (' . $value['count'] . 'шт) - ' . $pr['cost_excess'] . 'р. <br/>';
				}
			}
			
			setcookie("basketPIDs", "", time() - 3600, '/');

			//$total_cost = ($total_cost < 3000) ? $total_cost + 500 : $total_cost;
			//$client = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE id = ?",$_SESSION['bouquet_id_user']);

			/*
			$_SESSION['email_client_name'] = $client['i_name'];
			$_SESSION['email_client_fname'] = $client['f_name'];
			$_SESSION['email_order_client_phone'] = $client['phone'];
			$_SESSION['email_order_number'] = $number;
			$_SESSION['email_date_delivery'] = $date_order;
			$_SESSION['email_time_delivery'] = $time_order;
			$_SESSION['email_message_composition_order'] = $OrderProductsStringWithFoto;

			if($myself_order) {
				$_SESSION['email_recipient_name']  = $client['f_name'] . " " . $client['i_name'];
				$_SESSION['email_recipient_phone'] = $client['phone'];
			} else {
				$_SESSION['email_message_recipient_name'] = $fio_order;
				$_SESSION['email_recipient_phone'] = $phone_order;
			}
			$_SESSION['email_recipient_address'] = $street_order . ", дом " . $bld_order . (($aprt_order) ? ", квартира " . $aprt_order : "") . (($corp_order) ? ", корпус " . $corp_order : "");
			$_SESSION['email_type_payment'] = $sIncCurrLabel;
			$_SESSION['email_total_cost'] = $total_cost;
			$_SESSION['email_order_id'] = $insert_id;

			$EmailTextManadger = new teController(416);
			$EmailTextClient = new teController(433);

			// Подготовим данные для отправки письма
			$MailSender=new MailSender(array(
				"login"		=> "robot@premiumbuket.ru",
				"password"	=> "DLjsB5mr",
				"smtpHost"	=> "premiumbuket.ru"
			));

			// Отправим письмо о регистрации менеджеру
			$MailSender->send(array(
				"fromFIO"		=> "PremiunBouquet",
				"fromMail"		=> "robot@premiumbuket.ru",
				"toMail"		=> $site_config['manager_email'],
				"subject"		=> 'Заказ №' . $number,
				"text"			=> "
										<html>
										<head>
											<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
										</head>
										<body>
										" . $EmailTextManadger . "
										</body>
										</html>
									",
				"logFileName"	=> "/tmp/mail_log_premiumbuket_ru"
			));

			// Отправим письмо о регистрации клиенту
			$MailSender->send(array(
				"fromFIO"		=> "PremiunBouquet",
				"fromMail"		=> "robot@premiumbuket.ru",
				"toMail"		=> $client['email'],
				"subject"		=> 'Заказ №' . $number,
				"text"			=> "
										<html>
										<head>
											<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
										</head>
										<body>
										" . $EmailTextClient . "
										</body>
										</html>
									",
				"logFileName"	=> "/tmp/mail_log_premiumbuket_ru"
			));
			*/
			/*$db->insert(CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO, array(
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
			));*/
			/*
			$amount = number_format($total_cost, 2, '.', '');
			$orderId = $insert_id;
			$merchantLogin = "premiumbouquet.ru";
			$securityKey = "premiumbouquet_secure_pass1734";
			$description = 'Сумма заказа: ' . $amount . ' Номер заказа: ' . $orderId;

			$baseQuery =
					"MrchLogin=" . $merchantLogin .
					"&InvId=" . $orderId .
					"&OutSum=" . $amount .
					"&IncCurrLabel=" . $sIncCurrLabel .
					"&Desc=" . $description;

			$queryWithSecurityKey = $merchantLogin . ":" . $amount . ":" . $orderId . ':' . $securityKey;

			$hash = md5($queryWithSecurityKey);

//			$clientQuery = $baseQuery . "&SignatureValue=" . $hash . "&sCulture=ru";

			echo '<section id="personal-data" class="form-page">';
			if ($exist_pay_form) {
				echo '<form action="https://merchant.roboxchange.com/Index.aspx" method="get">';
			}
			?>
		            	<header>
		                	<h1>Спасибо за покупку.</h1>
		                </header>
		                <div style="text-align: center;">
		                    <p>Ваш заказ № <?=$number?> принят в обработку.</p>
		                    <p>Наш оператор вскоре свяжется с вами.</p>
		                </div>
				<?php
					if ($exist_pay_form) {
				?>
						<div class="centered-row">
                            <input type="image" name="save" src="images/buttons/continue.png">
                            <input type="hidden" name="MrchLogin" value="<?=$merchantLogin?>" />
                            <input type="hidden" name="InvId" value="<?=$orderId?>" />
                            <input type="hidden" name="OutSum" value="<?=$amount?>" />
                            <input type="hidden" name="IncCurrLabel" value="<?=$sIncCurrLabel?>" />
                            <input type="hidden" name="Desc" value="Сумма заказа: <?=$amount?> Номер заказа: <?=$orderId?>" />
                            <input type="hidden" name="SignatureValue" value="<?=$hash?>" />
                            <input type="hidden" name="sCulture" value="ru" />
						</div>
					</form>
				<?php
					} else {
				?>
						<div class="centered-row">
							<a href="/"><img src="images/buttons/continue.png" border="0" alt="" /></a>
						</div>
				<?php
					}
				?>
            	</section>
            	<!-- <script type="text/javascript">
					setTimeout(function () {
						window.location = "<?=$__urlPREFIX?>/cart/";
					}, 1000);
				</script> -->
			
	<?php
			*/
			}
		}
	 }
	 ?>

