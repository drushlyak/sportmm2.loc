<?php

	/**
	 * @package Auth
	 * @copyright Cruiser cruiser.com.ua
	 */

	/**
	 * Класс реализации ACL
	 *
	 * @package Auth
	 */
	class Auth {

		/**
		 * @var string логин пользователя
		 */
		private $user = "";

		/**
		 * @var string пароль пользователя
		 */
		private $password = "";

		/**
		 * @var string роль
		 */
		private $role = "";

		/**
		 * @var integer ID пользователя
		 */
		public $user_id;

		/**
		 * @var MilKit_Acl_Store_MyDb экземпляр MilKit_Acl_Store_MyDb
		 */
		public $store;

		/**
		 * @var MilKit_Acl экземпляр MilKit_Acl
		 */
		public $acl;

		/**
		 * @var mydb экземпляр mydb
		 */
		private $_db;

		/**
		 * @var boolean признак успешной аутентификации
		 */
		public $authed = false;

		/**
		 * @var boolean признак разрешения доступа
		 */
		private $allowed = false;

		/**
		 * Конструктор класса
		 */
		function __construct() {

			$this->_db = mydb::instance();
			$this->store = new MilKit_Acl_Store_MyDb(array(
				'db' => $this->_db,
				'resourceTreeFields' => array(
					'id' => 'id',
					'left' => 'lft',
					'right' => 'rgt',
					'level' => 'level',
					'data_id' => 'data_id'
				)
			));
//			$this->acl = new MilKit_Acl($this->store);

			// Выход из сессии
			if (isset($_REQUEST['logout']) && $_REQUEST['logout']) {
				unset(
					$_SESSION['id_tvirab_user'],
					$_SESSION['auth']
				);

				redirect("/virab/index.php");
				return true;
			}

			if ($_SESSION['id_tvirab_user'] && $_SESSION['auth']) {
				$auth = true;
			} else {
				$auth = false;
			}

			// Авторизация пользователя
			if (isset($_REQUEST['enter_submitted']) && $_REQUEST['enter_submitted'] && !$auth) {
				$login    = strip_tags(trim($_REQUEST['login']));
				$remember = intval($_REQUEST['remember_me']);
				$password = strip_tags(trim($_REQUEST['password']));

				$sql = "
					SELECT *
						FROM " . CFG_DBTBL_UDATA . "
					WHERE login = ?
					  AND password = ? ";
				$auth = $this->_db->get_row($sql, $login, md5($password));

			} else {
				if ($_SESSION['id_tvirab_user']) {
					$sql = "
						SELECT *
							FROM " . CFG_DBTBL_UDATA . "
						WHERE id = ? ";
					$auth = $this->_db->get_row($sql, $_SESSION['id_tvirab_user']);
				}
			}

			if (is_array($auth)) {
				$this->user					= $auth['login'];
				$this->password				= $auth['password'];
				$this->role					= $auth['role_id'];
				$this->user_id				= $auth['id'];
				$_SESSION['id_tvirab_user'] = $auth['id'];
				$_SESSION['auth']			= 1;
				$auth						= true;
				$this->authed				= true;
			} else {
				$_SESSION['current_message_header'] = _("Авторизация");
				$_SESSION['current_message'] = _("Логин или пароль введены ошибочно, либо учетная запись не активирована.");

				$this->askAuth(_("ЛОГИН ИЛИ ПАРОЛЬ ВВЕДЕНЫ ОШИБОЧНО!"));
				return false;
			}

			if (isset($_REQUEST['enter_submitted']) && $_REQUEST['enter_submitted']){
				// Ставить куки для запоминания
				if($remember){
					setcookie("id_user", $auth['id'], time()+60*60*24*30, "/");
					setcookie("user_hash", $auth['login'], time()+60*60*24*30, "/");
				}
			}

			return true;
		}

		/**
		 * Вывод формы авторизации
		 *
		 * @param string $msg сообщение
		 * @return boolean
		 */
		function askAuth($msg = '') {
			?>
				<html>
					<head>
						<meta http-equiv="content-Type" content="text/html; charset=utf-8">
						<title>VIRAB Professional</title>

						<link rel="stylesheet" type="text/css" href="css/general.css">
						<link rel="stylesheet" type="text/css" href="css/main.css">
						<link rel="stylesheet" type="text/css" href="css/jquery.lightbox.css">
						<link rel="stylesheet" type="text/css" href="css/ui.all.css">
						<link rel="stylesheet" type="text/css" href="css/autosuggest.css">

						<script type="text/javascript" src="/js/jquery.js"></script>
						<script type="text/javascript" src="/js/jquery.lightbox.js"></script>
						<script type="text/javascript" src="/js/jquery.ui.js"></script>
						<script type="text/javascript" src="/js/autosuggest.js"></script>

						<script type="text/javascript" src="js/general.js"></script>

						<style>
							html, body {height: 100%;}
						</style>

						<script type="text/javascript">
							$(function() {
								$('div.authBox .authForm-passw').keypress(function(e) {
									if (e.which == 13) {
										$('#enter_form').get(0).submit();
									}
								});
							});
						</script>
					</head>

					<body>

					<div class="authBox">
					<?php if ($msg != ''): ?>
						<!-- <div class="authErrorMsg"><?=$msg?></div> -->
					<?php endif; ?>
						<div class="auth">
							<form action="" method="post" name="enter_form" id="enter_form">
								<input class="authForm-login" id="login" name="login" type="text" value="<?=_("Логин")?>" onfocus="if(this.value=='<?=_("Логин")?>'){this.value=''}" onblur="if(this.value==''){this.value='<?=_("Логин")?>'}"/>
								<input class="authForm-passw" type="password" id="password" name="password" value="<?=_("Пароль")?>" onfocus="if(this.value=='<?=_("Пароль")?>'){this.value=''}" onblur="if(this.value==''){this.value='<?=_("Пароль")?>'}"/>
								<input type="hidden" name="enter_submitted" value="1"/>
								<a class="authForm-submit" id="start_enter" href="#" onclick="enter_form.submit(); return false;"></a>
							</form>
						</div>
					</div>

					</body>
				</html>
			<?php
			return true;
		}

		/**
		 * Проверка доступа
		 *
		 * @param integer $res_id ID ресурса
		 * @param string $privilege наименование привилегии
		 *
		 * @return boolean
		 */
		public function aclCheck($res_id, $privilege) {
//			if ($this->acl->isAllowed($this->role, $res_id, $privilege)) {
				$this->allowed = true;
//			} else {
//				$this->allowed = false;
//			}

			return $this->allowed;
		}

		/**
		 * Выдача флага allowed
		 *
		 * @return boolean
		 */
		public function isAllowed() {
			return $this->allowed;
		}

		/**
		 * Получить ресурс по наименованию модуля
		 *
		 * @param string $mod_name наименование модуля
		 * @return integer ID ресурса
		 */
		public function getModuleResource($mod_name) {
			$sql = "
				SELECT top_id
				FROM " . CFG_DBTBL_MODULE . "
				WHERE var = ?
			";
			$resTree = $this->store->getResourceTree();
			$top_id = $this->_db->get_one($sql, $mod_name);
			$res = $resTree->getNodeInfo($top_id);
			return $res['data_id'];
		}

	}
?>