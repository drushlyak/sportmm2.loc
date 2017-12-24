<?php
if(!$_SESSION['pitanie_auth']) {
	//require_once(LIB_PATH . "/class.phpmailer.php");
	//require_once (LIB_PATH . "/MailSender.class.php");

	global $db, $site_config;

	$phone = strip_tags(trim($_REQUEST['phone']));
	$f_name = strip_tags(trim($_REQUEST['f_name']));
	$i_name = strip_tags(trim($_REQUEST['i_name']));
	$password = strip_tags(trim($_REQUEST['password']));
	$repitpassword = strip_tags(trim($_REQUEST['repitpassword']));
	$email = strip_tags(trim($_REQUEST['email']));
	$captcha = strip_tags(trim($_REQUEST['captcha']));
	$submitted = (int) $_REQUEST['submitted'];


	if ($submitted) {

		$error = false;
		$str_error = "";

		// проверки
		if (empty($email) || !validEmail($email)) {
				$error = true;
				$str_error .= _(" - введите корректный email!<br />");
			} else {
				if ($db->get_one(sql_placeholder("SELECT id FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE email = ?", $email))) {
					$error = true;
					$str_error .= _(" - этот email уже используется, если Вы забыли пороль, воспользуйтесь формой восстановления пароля <a href='/restore_password/'>тут</a>!<br />");
				}
			}
		if (empty($phone)) {
			$error = true;
			$str_error .= _(" - укажите телефон!<br />");

		}
		if (empty($password)) {
			$error = true;
			$str_error .= _(" - введите пароль!<br />");

		}
		if (empty($repitpassword)) {
			$error = true;
			$str_error .= _(" - введите повторно ваш пароль!<br />");

		}
		if ($repitpassword !== $password) {
			$error = true;
			$str_error .= _(" - пароли не совпадают!<br />");

		}
		if ($captcha !== $_SESSION['captcha_keystring']) {
			$error = true;
			$str_error .= _(" - Введенный Вами код не совпадает с указанным на картинке!<br />");
		}
	}

	if (!$submitted || $error === true) :

?>

     	
     	<div class="registration">
			<?php 	echo "<font color='#FF0000'>" . $str_error . "</font><br>";?>
			<form action="" name="reg_form" method="post">
				<p>фамилия:</p>
				<input type="text" name="f_name" value="<?=$f_name?>" class="fullname">
				<p>имя:</p>
				<input type="text" name="i_name" value="<?=$i_name?>" class="fullname">				
				<p>e-mail:</p>
				<input type="text" name="email" value="<?=$email?>" class="email">
				<p>телефон:</p>
				<input type="text" name="phone" value="<?=$phone?>" class="email">
				<p>пароль:</p>
				<input type="password" name="password" class="name">
				<p>повторите пароль:</p>
				<input type="password" name="repitpassword" class="name">
				<p>введите текст с картинки:</p>
				<input type="text" name="captcha" id="captcha" class="email">
				<input type="submit" value="регистрация" class="login-registration">
				<img src="/htdocs/keyimg.php?<?=session_name()?>=<?=session_id()?>&rnd=<?=rand()?>" alt="<?=_("картинка с текстом")?>">
				<input name="submitted" type="hidden" value="1" />
			</form>
		</div>
     	
     	
     	<script>
		//$(document).on('keyup', '#captcha', function (event) {
	    //    if (event.keyCode == 13) {
	    //    	reg_form.submit();
	    //        return false;
	    //    }
	    // });
	</script>
<?php
	else:



		$sql = sql_placeholder("
				INSERT INTO " . CFG_DBTBL_MOD_CLIENT . "
		           	SET f_name = ?
		           	  , i_name = ?
		           	  , phone = ?
		           	  , email = ?
		           	  , password = ?
			", $f_name
			 , $i_name
			 , $phone
			 , $email
			 , md5($password)
			 );


			$db->query($sql);
			$insert_id = $db->insert_id;

			//залогиневание пользователя
			$_SESSION['pitanie_id_user'] = $insert_id;
			$_SESSION['pitanie_email'] = $email;
			$_SESSION['pitanie_auth'] = 1;
			/*
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
						window.location = "<?=$__urlPREFIX?>/";
					}, 3000);
				</script>
				<h1>Вы успешно зарегистрировались.</h1>

		<?php

	endif;
} else {
	$user = $db->get_row("SELECT f_name,i_name FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE id = ?", $_SESSION['pitanie_id_user']);
		echo "<h1>" . $user['i_name'] . ", Вы уже авторизованы</h1>";

}
?>