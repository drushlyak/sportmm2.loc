<?php

	/**
	 * @author Чапни Николай [boksweb@gmail.com]
	 * 
	 * Класс отправки писем через сокет
	 * 
	 */
	
	Class MailSender {
		private $connectInfo=array();	// Параметры подключения к smtp серверу
		private $smtpConn;				// Сокет кодключения к SMTP
		private $logMsg="";				// Лог сообщение
		private $curMail;				// Конфиг отправки текущего письма
		public	$errorNum;				// Код ошибки
		public	$errorText;				// Текст ошибки
		
		/**
		 * Конструктор класса, сохраняет параметры обращения к SMTP серверу
		 *
		 * @param array $config
		 * 					string 'smtpHost' - Хост smtp сервера
		 * 					string 'login' - логин для джоступа к smtp
		 * 					string 'password' - пароль для джоступа к smtp
		 * 
		*/
		public function __construct($config=FALSE) {
			if ($config) $this->connectInfo=$config;
		}
		
		/*
		 * Записывает ошибку
		 * 
		 * @param int $num - код ошибки
		 * 
		*/
		private function setError($num, $text) {
			$this->errorNum=$num;
			$this->errorText=$text;
			if ($this->smtpConn) fclose($this->smtpConn);
			
			// Запист в лог файл
			if (isset($this->curMail['logFileName'])) {
				$this->logMsg.=" | RES:$num, text:$text\n"; // Дополняем фразу лог файла
				$fh = @fopen($this->curMail['logFileName'], 'a');
				if ($fh) {
					fwrite($fh, $this->logMsg);
					fclose($fh);
				} else {
					// 2do тут что-то делаем, если файл логирования открыть не получилось
				}
			}
		}
		
		/*
		 * Проверка валидности указанного mail адреса
		 * 
		 * @param string $mail - собственно mail
		 * 
		 * @return boolean: TRUE - корректный mail, FALSE - неправильный mail
		*/  
		public function mailSyntaxChech($mail) {
			//if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $mail)) return FALSE;
			if (count(explode("@", $mail))!=2) return FALSE;

			list ($userName, $domain) = split ("@",$mail);
			if (getmxrr ($domain, $mxHost)) return TRUE;
			else {
				$socket=@fsockopen($domain, 25, $errno, $errstr, 10);
				if ($socket) {
					fclose($socket);
					return TRUE;
				} else return FALSE;
			}
		}
		
		/*
		 * Чтение информации с сокета
		 * @return string поток
		*/
		private function socketRead() {
			$data="";
			while($str = fgets($this->smtpConn, 512)) {
				$data .= $str;
				if(substr($str, 3, 1) == " ") { break; }
			}
			return $data;
		}
		
		/*
		 * Отправка письмо адресату
		 * 
		 * @param array $config
		 * 					string 'fromFIO'	- ФИО отправителя 
		 * 					string 'fromMail'	- mail отправителя 
		 * 					string 'toMail'		- mail получателя 
		 * 					string 'subject'	- тема 
		 * 					string 'text'		- письмо 
		 * 					string 'logFileName'- имя лог файла
		*/
		public function send($c) {
			$this->curMail=$c; // сохраняем параметра отправки текущего письма
			
			$this->logMsg=@date("d.m.y H:i:s")." - ".$c['toMail']." | ".$c['subject']; // формируем начало сообщения в лог файле
			
			if (!($this->mailSyntaxChech($c['toMail']))) {
				$this->setError(1, "Mail адрес введен неправильно.");
				return FALSE;
			}
			
			$header="Date: ".@date("D, j M Y G:i:s")." +0200\r\n";
			$header.="From: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($c['fromFIO'])))."?=<".$c['fromMail'].">\r\n";
//			$header.="X-Mailer: The Bat! (v3.99.3) Professional\r\n";
			$header.="Reply-To: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($c['fromFIO'])))."?=<".$c['fromMail'].">\r\n";
			$header.="X-Priority: 3 (Normal)\r\n";
			$header.="Message-ID: <1403615275.".@date("YmjHis")."@lasvilis.com>\r\n";
//			$header.="To: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($c['toMail'])))."?=<".$c['toMail'].">\r\n";
			$header.="To: ".$c['toMail'].">\r\n";
			$header.="Subject: =?UTF-8?Q?".str_replace("+","_",str_replace("%","=",urlencode($c['subject'])))."?= \r\n";
			$header.="MIME-Version: 1.0\r\n";
			$header.="Content-Type: text/html; charset=UTF-8\r\n";
			$header.="Content-Transfer-Encoding: 8bit\r\n";
	
			$sizeMsg=strlen($header."\r\n".$c['text']);
			
			$this->smtpConn = fsockopen($this->connectInfo['smtpHost'], 25,$errno, $errstr, 10);
			if (!$this->smtpConn) { $this->setError(2, "Соединение с серверов не прошло."); return FALSE; }
			$data = $this->socketRead();
			
			fputs($this->smtpConn, "EHLO localhost\r\n");
			$code = substr($this->socketRead(), 0, 3);
			if ($code != 250) { $this->setError($code, "Ошибка приветсвия EHLO."); return FALSE; }
			
			fputs($this->smtpConn, "AUTH LOGIN\r\n");
			$code = substr($this->socketRead(), 0, 3);
			if ($code != 334) { $this->setError($code, "Сервер не разрешил начать авторизацию."); return FALSE; }
	
			fputs($this->smtpConn, base64_encode($this->connectInfo['login'])."\r\n");
			$code = substr($this->socketRead(), 0, 3);
			if ($code != 334) { $this->setError($code, "Ошибка доступа к такому юзеру."); return FALSE; }
	
			fputs($this->smtpConn, base64_encode($this->connectInfo['password'])."\r\n");
			$code = substr($this->socketRead(), 0, 3);
			if ($code != 235) { $this->setError($code, "Неправильный пароль."); return FALSE; }
	
			fputs($this->smtpConn, "MAIL FROM:<".$c['fromMail']."> SIZE=".$sizeMsg."\r\n");
			$code = substr($this->socketRead(), 0, 3);
			if ($code != 250) { $this->setError($code, "Сервер отказал в команде MAIL FROM."); return FALSE; }
	
			fputs($this->smtpConn, "RCPT TO:<".$c['toMail'].">\r\n");
			$serverOut=$this->socketRead();
			$code = substr($serverOut, 0, 3);
			if ($code != 250 AND $code != 251) { $this->setError($code, "Сервер не принял команду RCPT TO (".$serverOut.")"); return FALSE; }
	
			fputs($this->smtpConn, "DATA\r\n");
			$code = substr($this->socketRead(), 0, 3);
			if ($code != 354) { $this->setError($code, "Сервер не принял DATA."); return FALSE; }
	
			fputs($this->smtpConn, $header."\r\n".$c['text']."\r\n.\r\n");
			$serverOut=$this->socketRead();
			$code = substr($serverOut, 0, 3);
			if ($code != 250) { $this->setError($code, "ошибка отправки письма (".$serverOut.")"); return FALSE; }
	
			fputs($this->smtpConn, "QUIT\r\n");
			$this->setError(0, "Ok");
			return TRUE;
		}
	}
