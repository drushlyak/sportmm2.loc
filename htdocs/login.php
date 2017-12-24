<?php 
if(!$_SESSION['pitanie_auth']) {
	$email = strip_tags(trim($_REQUEST['email']));
	$password = strip_tags(trim($_REQUEST['password']));
	$submitted = (int) $_REQUEST['submitted'];
	
	if ($submitted) {

		$error = false;
		$str_error = "";

		// проверки
		if (empty($email) || !validEmail($email)) {
				$error = true;
				$str_error .= _(" - введите корректный email!<br />");
			}
		if (empty($password)) {
			$error = true;
			$str_error .= _(" - введите пароль!<br />");

		}
		if(!$error) {
			$user = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE email = ? AND password = ?", $email, md5($password));
			if(!is_array($user)) {
				$error = true;
				$str_error .= _(" - логин или пароль неверный!<br />");
			}
		}
	}

	if (!$submitted || $error === true) :
?>
<div class="sidebar-wrapper"> 
        <div class="sidebar-login">
            <div class="title">авторизация</div>     
             <?php 	echo ($str_error) ? "<font color='#FF0000'>" . $str_error . "</font><br>" : '';?>     
            <form action="/" method="post" style="position:relative;">
                <input type="text" name="email" class="login" placeholder="E-mail">
                <input type="password" name="password" placeholder="password" class="password" >
                <input name="submitted" type="hidden" value="1" />
                <input type="submit" value="" class="login-btn">
                <a href="/registration/">Регистрация</a>/<a href="#">Забыли пароль?</a>
            </form>
           
        </div>
</div>
<?php 
	else:
		echo "<div class='sidebar-wrapper' style='margin-top: 17px;'><h2>Здравствуйте, " . $user['i_name'] . "!</h2></div>";
		//залогиневание пользователя
		$_SESSION['pitanie_id_user'] = $user['id'];
		$_SESSION['pitanie_email'] = $user['email'];
		$_SESSION['pitanie_auth'] = 1;
	endif;
} else {
	$user = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE id = ?", $_SESSION['pitanie_id_user']);
	echo "<div class='sidebar-wrapper' style='margin-top: 17px;'><h2>Здравствуйте, " . $user['i_name'] . "!</h2></div>";
}
?>