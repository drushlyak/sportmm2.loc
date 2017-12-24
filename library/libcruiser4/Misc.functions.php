<?php

	/**
	 * Add include path
	 */
//	$include_path = get_include_path();
//	$include_path = LIB_PATH . PATH_SEPARATOR . $include_path;
//	set_include_path($include_path);
//	unset($include_path);

	/**
	 * Преобразование типов
	 *
	 * @param array $validation_rules
	 * @param array $vars
	 */
	function validate_var_types($validation_rules, &$vars) {
		foreach ($validation_rules as $varname => $rule){
			if (!key_exists($varname, $vars)) {
				$vars[$varname] = $rule["default"];
				continue;
			}
			settype($vars[$varname], $rule["type"]);
		}
	}

	if (!function_exists("redirect")) {
		/**
		 * Функция редиректа
		 *
		 * @param string $url
		 * @param int $timeout
		 */
		function redirect($url, $timeout=2) {
			if (headers_sent()) {
				$timeout = 1000;
				?>
					<script type="text/javascript">
						window.setTimeout(function () {self.location.replace("<?=$url?>");}, <?=$timeout?>);
					</script>
				<?php
			} else {
				header("Location: $url");
				die();
			}
		}
	}

	/**
	 * Функция замены mailto
	 *
	 * @param string $email
	 * @my_visuality int $my_visuality
	 * $visual string $visual
	 * @param array $params
	 * @return string
	 */
	function mailto($email, $my_visuality = false, $visual = '', $params = array()) {
		static $decode_func_sent = false;
		static $decode_func_name;

		$chars = preg_split('//', $email, -1, PREG_SPLIT_NO_EMPTY);
		$keys = array_rand($chars, sizeof($chars));
		$shuffled = "";
		foreach ($keys as $p) {
			$shuffled .= $email{$p};
		}
		$data = array($keys, $shuffled);

		$params_str = '';
		foreach ($params as $key => $value) {
			$params_str .= $key . '="' . $value . '" ';
		}

		require_once(LIB_PATH . "/ajax/class.json.php");
		$json = new JSON();
		$js_encoded = $json->encode($data);
		$base64_encoded = base64_encode($js_encoded);

		ob_start();
		if (!$decode_func_sent) {
			$decode_func_name = uniqid("d");
			?>
				<script src="/js/base64.js" type="text/javascript"></script>
				<script type="text/javascript">window['<?=$decode_func_name?>'] = function(d) {var d = eval(decodeBase64(d));var a = [];a.length = d[0].length;for (var i=0; i<d[0].length; i++) {a[d[0][i]] = d[1].charAt(i);}return a.join('');}</script>
			<?php
			$decode_func_sent = true;
		}

		if (!$my_visuality) {
		?>
			<a href="#" onmouseover="this.href='mailto:' + <?=$decode_func_name?>('<?=$base64_encoded?>')" onmouseout="this.href = '#'" <?=$params_str?>>
				<script type="text/javascript">document.write(<?=$decode_func_name?>('<?=$base64_encoded?>'))</script>
			</a>
		<?php
		} else {
		?>
			<a href="#" onmouseover="this.href='mailto:' + <?=$decode_func_name?>('<?=$base64_encoded?>')" onmouseout="this.href = '#'" <?=$params_str?>>
			<?=$visual?>
			</a>
		<?php
		}
		$ret = ob_get_contents();
		ob_end_clean();

		return $ret;
	}

	/**
	 * Генерация случайного пароля
	 *
	 * @param int $plength
	 * @return string
	 * @author unknow
	 */
	function rand_password($plength=null) {
		if ((!is_numeric($plength)) ||($plength <= 0)) {
			$plength = 8;
		}
			if ($plength > 32) {
				$plength = 32;
			 }
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		mt_srand(microtime() * 1000000);
			for ($i = 0; $i < $plength; $i++) {
				$key = mt_rand(0,strlen($chars)-1);
				$pwd = $pwd . $chars{$key};
			 }
		for ($i = 0; $i < $plength; $i++) {
			$key1 = mt_rand(0,strlen($pwd)-1);
			$key2 = mt_rand(0,strlen($pwd)-1);
			$tmp = $pwd{$key1};
			$pwd{$key1} = $pwd{$key2};
			$pwd{$key2} = $tmp;
			}
			return $pwd;
	}

	/**
	 * Генерация select элемента
	 *
	 * @param array $options
	 * @param array $selected
	 * @param string $name
	 * @param boolean $empty
	 * @param boolean $multi
	 * @param array $params
	 */
	function html_select($options, $selected, $name, $empty = true, $multi=false, $params=array()) {
		print "<select name=" . $name . " id=" . $name . " ";
		foreach ($params as $key => $value) {
			print $key . "=" . $value . " ";
		}
		if ($multi) {
			print " multiple ";
		}
		print ">";
		if ($empty) {
			?>
				<option value='0'><?=_("-- Выберите элемент --")?></option>
			<?php
		}
		$selected = (array) $selected;
		foreach ($options as $key => $value) {
			$sel = in_array($key, $selected) ? " selected" : "";
			?>
				<option value="<?=$key?>"<?=$sel?>><?=$value?></option>
			<?php
		}
		?>
			</select>
		<?php
	}

	/**
	 * Генерация option элементов
	 *
	 * @param array $options
	 * @param array $selected
	 */
	function html_options ($options, $selected) {
		$selected = (array) $selected;
		foreach ($options as $key => $value) {
			$sel = in_array($key, $selected) ? " selected" : "";
			?>
				<option value="<?=$key?>"<?=$sel?>><?=$value?></option>
			<?php
		}
	}

	/**
	 * Рекурсивное удаление директории
	 *
	 * @param string $directory
	 * @param boolean $empty
	 * @return boolean
	 */
	function remove_dir($directory, $empty = false) {
		if (substr($directory,-1) == '/') {
			$directory = substr($directory, 0, -1);
		}
		if (!file_exists($directory) || !is_dir($directory)) {
			return false;
		} elseif (!is_readable($directory)) {
			return false;
		} else {
			$handle = opendir($directory);
			while (false !== ($item = readdir($handle))) {
				if ($item != '.' && $item != '..') {
					$path = $directory.'/'.$item;
					if (is_dir($path)) {
						remove_dir($path);
					} else {
						unlink($path);
					}
				}
			}
			closedir($handle);
			if ($empty == false) {
				if (!rmdir($directory)) {
					return false;
				}
			}
			return true;
		}
	}

	/**
	 * Рекурсивная установка прав
	 *
	 * @param string $path
	 * @param hex $filemode
	 * @return boolean
	 */
	function chmodRecursive($path, $filemode) {
		if (!is_dir($path)) {
			return chmod($path, $filemode);
		}

		$dh = opendir($path);
		while ($file = readdir($dh)) {
			if ($file != '.' && $file != '..') {
				$fullpath = $path.'/'.$file;
				if (is_link($fullpath)) {
					return false;
				} elseif (!is_dir($fullpath)) {
					if (!chmod($fullpath, $filemode)) {
						return false;
					}
				} elseif (!chmodRecursive($fullpath, $filemode)) {
					return false;
				}
			}
		}
		closedir($dh);
		if (chmod($path, $filemode)) {
			return true;
		} else {
			return false;
		}
	}

	function createRoundFramingJpg($file_source, $size_width, $size_height, $backcolor = array(0, 0, 0), $quality = 100, $file_destination) {
		$result = ImageCreateTrueColor($size_width, $size_height);
		if (is_resource($result) === true) {
			ImageSaveAlpha($result, true);
			ImageAlphaBlending($result, true);
			ImageFill($result, 0, 0, ImageColorAllocate($result, $backcolor[0], $backcolor[1], $backcolor[2]));
		}

		$image = ImageCreateFromJpeg($file_source);
		if (is_resource($image) === true) {
			$widthOrig = imagesx($image);
			$heightOrig = imagesy($image);
			$size_width  = (int) $size_width;
			$size_height = (int) $size_height;

			$ratioOrig = $widthOrig/$heightOrig;
			$ratio = $size_width / $size_height;

			if ($ratioOrig < $ratio) {
				// фиксируем по высоте
				$height = $size_height;
				$width = $height * $ratioOrig;
				$x = ceil(($size_width / 2) - ($width / 2));
				$y = 0;
			} else {
				// фиксируем по ширине
				$width = $size_width;
				$height = $width / $ratioOrig;
				$x = 0;
				$y = ceil(($size_height / 2) - ($height / 2));
			}
			$temp_result = ImageCreateTrueColor($width, $height);
			if (is_resource($temp_result) === true) {
				ImageSaveAlpha($temp_result, true);
				ImageAlphaBlending($temp_result, true);
			}
			ImageCopyResampled($temp_result, $image, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig);

			imagecopy($result, $temp_result, $x, $y, 0, 0, $width, $height);
		}

		ImageInterlace($result, true);
		ImageJPEG($result, $file_destination, $quality);

		ImageDestroy($image);
		ImageDestroy($temp_result);
		ImageDestroy($result);
		return true;
	}

	/**
	 * Создание уменьшенной копии изображения
	 *
	 * @param int $width_fotogr
	 * @param int $height_fotogr
	 * @param int $quality_fotogr
	 * @param string $file_type
	 * @param string $file_orig
	 * @param string $file_adr
	 * @param boolean $cut
	 * @param boolean $resize
	 * @param int $start_x начальная координата (X) точки от которой делается вырезка (используется при resize = false) (по-умолчанию 0)
	 * @param int $start_y начальная координата (Y) точки от которой делается вырезка (используется при resize = false) (по-умолчанию 0)
	 * @param int $corner_radius радиус скругления
	 *
	 * @return boolean
	 */
	function createResizeImg ($width_fotogr, $height_fotogr, $quality_fotogr, $file_type, $file_orig, $file_adr, $cut = false, $resize = true, $start_x = 0, $start_y = 0, $corner_radius = 0) {
		if(!$width_fotogr || !$height_fotogr){
			return false;
		}

		// Создадим уменьшенную и нормальную копию изображения
		if ($cut) {
			$new_img = imagecreatetruecolor($width_fotogr, $height_fotogr);
		}

		// Получим коэфициенты пропорций
		$koef = $height_fotogr / $width_fotogr;

		// прочитаем оригинальное изображение
		clearstatcache();
		if ($file_type == "jpg") {
			$img = imagecreatefromjpeg($file_orig);
		} elseif ($file_type == "gif") {
			$img = imagecreatefromgif($file_orig);
			$ct = imagecolortransparent($img);
			$color_tran = imagecolorsforindex($img, $ct);
			$ct2 = imagecolorexact($new_img, $color_tran['red'], $color_tran['green'], $color_tran['blue']);
		}

		// Расчитаем размеры изображения пропорционального нормальному и уменьшенному изображению для вырезки
		clearstatcache();
		$width_orig = imagesx($img);
		$height_orig = imagesy($img);

		if ($cut) {
			if ($resize) {
				$width = (($width_orig*$koef) >= $height_orig) ? round($height_orig / $koef) : $width_orig;
				$height = (($width_orig*$koef) >= $height_orig) ?  $height_orig : round($width_orig * $koef);
				$center_x = round(($width_orig - $width) / 2);
				$center_y = round(($height_orig - $height) / 2);
			} else {
				$width = (($width_orig - $start_x) >= $width_fotogr) ? $width_fotogr : ((($width_orig - $start_x) < 0) ? 0 : $width_orig - $start_x);
				$height = (($height_orig - $start_y) >= $height_fotogr) ? $height_fotogr : ((($height_orig - $start_y) < 0) ? 0 : $height_orig - $start_y);
				$center_x = $start_x;
				$center_y = $start_y;
			}
		} else {
			$width = $width_fotogr;
			$height = $height_fotogr;
			if (($height_orig/$width_orig) > $koef) {
					 $width = (int) $height_fotogr/($height_orig/$width_orig);
			} else {
					 $height = (int) $width_fotogr/($width_orig/$height_orig);
			}
		}

		if (!$cut) {
			$new_img = imagecreatetruecolor($width, $height);
		}

		if ($file_type == "gif") {
			imagefill($new_img, 0, 0, $ct2);
		}
		clearstatcache();

		if ($cut) {
			$nn = imagecopyresampled($new_img, $img, 0, 0, $center_x, $center_y, $width_fotogr, $height_fotogr, $width, $height);
		} else {
			$nn = imagecopyresampled($new_img, $img, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		}
		clearstatcache();

		if ($corner_radius) {
			$rad = $corner_radius;
			$width = $width_fotogr;
			$height = $height_fotogr;

			$corn = imagecreatetruecolor($rad, $rad);
			$fuck = imagecolorallocate($corn, 255, 0, 255);
			$black = imagecolorallocate($corn, 0, 0, 0);
			imagefill($corn, 0, 0, $fuck);
			imagefilledellipse($corn, $rad, $rad, 2*$rad, 2*$rad, $black);
			imagecolortransparent($corn, $black);

			imagecopyresized($new_img, $corn, 0, 0, 0, 0, $rad, $rad, $rad, $rad);
			imagedestroy($corn);

			//
			$corn=imagecreatetruecolor($rad, $rad);
			$fuck = imagecolorallocate($corn, 255, 0, 255);
			$black = imagecolorallocate($corn, 0, 0, 0);
			imagefill($corn, 0, 0, $fuck);
			imagefilledellipse($corn, 0, $rad, 2*$rad, 2*$rad, $black);
			imagecolortransparent($corn, $black);

			imagecopyresized($new_img, $corn, $width-$rad, 0, 0, 0, $rad, $rad, $rad, $rad);
			imagedestroy($corn);

			//
			$corn=imagecreatetruecolor($rad, $rad);
			$fuck = imagecolorallocate($corn, 255, 0, 255);
			$black = imagecolorallocate($corn, 0, 0, 0);
			imagefill($corn, 0, 0, $fuck);
			imagefilledellipse($corn, 0, 0, 2*$rad, 2*$rad, $black);
			imagecolortransparent($corn, $black);

			imagecopyresized($new_img, $corn, $width-$rad, $height-$rad, 0, 0, $rad, $rad, $rad, $rad);
			imagedestroy($corn);

			//
			$corn=imagecreatetruecolor($rad, $rad);
			$fuck = imagecolorallocate($corn, 255, 0, 255);
			$black = imagecolorallocate($corn, 0, 0, 0);
			imagefill($corn, 0, 0, $fuck);
			imagefilledellipse($corn, $rad, 0, 2*$rad, 2*$rad, $black);
			imagecolortransparent($corn, $black);

			imagecopyresized($new_img, $corn, 0, $height-$rad, 0, 0, $rad, $rad, $rad, $rad);
			imagedestroy($corn);

			//
			$backgr = imagecolorallocate($new_img, 255, 0, 255);
			imagecolortransparent($new_img, $backgr);

			imagepng($new_img, $file_adr);
		} else {
			if ($file_type == "jpg") {
				$res = imagejpeg($new_img, $file_adr, $quality_fotogr);
			} elseif ($file_type == "gif") {
				imagecolortransparent($new_img, $ct2);
				$res = imagegif($new_img, $file_adr);
			}
		}
		imagedestroy($new_img);
		imagedestroy($img);
		return true;
	}

	/**
	 * Создание уменьшенной копии изображения с фиксированной шириной
	 *
	 * @param int $width_fotogr
	 * @param int $quality_fotogr
	 * @param string $file_type
	 * @param string $file_orig
	 * @param string $file_adr
	 * @return boolean
	 */
	function createResizeImgW($width_fotogr, $quality_fotogr, $file_type, $file_orig, $file_adr) {

		$size_img = getimagesize($file_orig);
		$src_ratio=$size_img[0]/$size_img[1];

		$h = $width_fotogr/$src_ratio;
		if ($size_img[0] < $width_fotogr) {
			$width_fotogr = $size_img[0]; // не будем растягивать
			$h = $size_img[1];
		}

		$dest_img = imagecreatetruecolor($width_fotogr, $h);

		// прочитаем оригинальное изображение
		clearstatcache();
		if ($file_type == "jpg") {
			$src_img = imagecreatefromjpeg($file_orig);
		} elseif ($file_type == "gif") {
			$src_img = imagecreatefromgif($file_orig);
			$ct = imagecolortransparent($src_img);
			$color_tran = imagecolorsforindex($src_img, $ct);
			$ct2 = imagecolorexact($dest_img, $color_tran['red'], $color_tran['green'], $color_tran['blue']);
		}

		clearstatcache();
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width_fotogr, $h, $size_img[0], $size_img[1]);

		if ($file_type == "jpg") {
			imagejpeg($dest_img, $file_adr, $quality_fotogr);
		} elseif ($file_type == "gif") {
			imagecolortransparent($dest_img, $ct2);
			imagegif($dest_img, $file_adr);
		}

		imagedestroy($dest_img);
		imagedestroy($src_img);
		return true;
	}

	/**
	 * Получение IP адреса
	 *
	 * @return string or boolean
	 */
	function get_ip() {
		global $REMOTE_ADDR;
		global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
		global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
		global $HTTP_SERVER_VARS, $HTTP_ENV_VARS;

		if (empty($REMOTE_ADDR)) {
			if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
				$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
			} elseif (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
				$REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
			} elseif (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) {
				$REMOTE_ADDR = $HTTP_SERVER_VARS['REMOTE_ADDR'];
			} elseif (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['REMOTE_ADDR'])) {
				$REMOTE_ADDR = $HTTP_ENV_VARS['REMOTE_ADDR'];
			} elseif (@getenv('REMOTE_ADDR')) {
				$REMOTE_ADDR = getenv('REMOTE_ADDR');
			}
		}

		if (empty($HTTP_X_FORWARDED_FOR)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
				$HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
				$HTTP_X_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'])) {
				$HTTP_X_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'];
			} elseif(@getenv('HTTP_X_FORWARDED_FOR')) {
				$HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
			}
		}

		if (empty($HTTP_X_FORWARDED)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
				$HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
			} elseif (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
				$HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
			} elseif (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED'])) {
				$HTTP_X_FORWARDED = $HTTP_SERVER_VARS['HTTP_X_FORWARDED'];
			} elseif(!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED'])) {
				$HTTP_X_FORWARDED = $HTTP_ENV_VARS['HTTP_X_FORWARDED'];
			} elseif(@getenv('HTTP_X_FORWARDED')) {
				$HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
			}
		}

		if (empty($HTTP_FORWARDED_FOR)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
				$HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
			} elseif (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
				$HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
			} elseif (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'])) {
				$HTTP_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'];
			} elseif (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED_FOR'])) {
				$HTTP_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_FORWARDED_FOR'];
			} elseif (@getenv('HTTP_FORWARDED_FOR')) {
				$HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
			}
		}

		if (empty($HTTP_FORWARDED)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
				$HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
			} elseif (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
				$HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
			} elseif (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED'])) {
				$HTTP_FORWARDED = $HTTP_SERVER_VARS['HTTP_FORWARDED'];
			} elseif (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED'])) {
				$HTTP_FORWARDED = $HTTP_ENV_VARS['HTTP_FORWARDED'];
			} elseif (@getenv('HTTP_FORWARDED')) {
				$HTTP_FORWARDED = getenv('HTTP_FORWARDED');
			}
		}

		if (empty($HTTP_VIA)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
				$HTTP_VIA = $_SERVER['HTTP_VIA'];
			} elseif (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
				$HTTP_VIA = $_ENV['HTTP_VIA'];
			} elseif (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_VIA'])) {
				$HTTP_VIA = $HTTP_SERVER_VARS['HTTP_VIA'];
			} elseif (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_VIA'])) {
				$HTTP_VIA = $HTTP_ENV_VARS['HTTP_VIA'];
			} elseif (@getenv('HTTP_VIA')) {
				$HTTP_VIA = getenv('HTTP_VIA');
			}
		}

		if (empty($HTTP_X_COMING_FROM)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
				$HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
			} elseif (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
				$HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
			} elseif (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_COMING_FROM'])) {
				$HTTP_X_COMING_FROM = $HTTP_SERVER_VARS['HTTP_X_COMING_FROM'];
			} elseif (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_COMING_FROM'])) {
				$HTTP_X_COMING_FROM = $HTTP_ENV_VARS['HTTP_X_COMING_FROM'];
			} elseif(@getenv('HTTP_X_COMING_FROM')) {
				$HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
			}
		}

		if (empty($HTTP_COMING_FROM)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
				$HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
			} elseif (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
				$HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
			} elseif (!empty($HTTP_COMING_FROM) && isset($HTTP_SERVER_VARS['HTTP_COMING_FROM'])) {
				$HTTP_COMING_FROM = $HTTP_SERVER_VARS['HTTP_COMING_FROM'];
			} elseif (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_COMING_FROM'])) {
				$HTTP_COMING_FROM = $HTTP_ENV_VARS['HTTP_COMING_FROM'];
			} elseif (@getenv('HTTP_COMING_FROM')) {
				$HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
			}
		}

		if (!empty($REMOTE_ADDR)) {
			$direct_ip = $REMOTE_ADDR;
		}

		$proxy_ip = '';
		if (!empty($HTTP_X_FORWARDED_FOR)) {
			$proxy_ip = $HTTP_X_FORWARDED_FOR;
		} elseif (!empty($HTTP_X_FORWARDED)) {
			$proxy_ip = $HTTP_X_FORWARDED;
		} elseif (!empty($HTTP_FORWARDED_FOR)) {
			$proxy_ip = $HTTP_FORWARDED_FOR;
		} elseif (!empty($HTTP_FORWARDED)) {
			$proxy_ip = $HTTP_FORWARDED;
		} elseif (!empty($HTTP_VIA)) {
			$proxy_ip = $HTTP_VIA;
		} elseif (!empty($HTTP_X_COMING_FROM)) {
			$proxy_ip = $HTTP_X_COMING_FROM;
		} elseif (!empty($HTTP_COMING_FROM)) {
			$proxy_ip = $HTTP_COMING_FROM;
		}

		if (empty($proxy_ip)) {
			return $direct_ip;
		} else {
			$is_ip = ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}', $proxy_ip, $regs);
			if ($is_ip && (count($regs) > 0)) {
				return $regs[0];
			} else {
				return FALSE;
			}
		}
	}

	/**
	 * Error class
	 *
	 */
//	class Error {
//		var $_message;

		/**
		 * @param string $message
		 */
//		function Error($message) {
//			$this->_message = $message;
//		}

		/**
		 * @return string Plaintext error message to display
		 */
//		function getMessage() {
//			return $this->_message;
//		}

		/**
		 * In following PEAR_Error model this could be formatted differently,
		 * but so far it's not.
		 * @return string
		 */
//		function toString() {
//			return $this->getMessage();
//		}

		/**
		 * Returns true if the given object is a WikiError-descended
		 * error object, false otherwise.
		 *
		 * @param mixed $object
		 * @return bool
		 * @static
		 */
//		function isError($object) {
//			return is_a($object, 'Error');
//		}
//	}


	/**
	* Byte converting with formated number
	*
	* @param int $bytes bytes
	* @return string
	*/
	function byteConvert(&$bytes) {
		$b = (int)$bytes;
		$s = array('Б', 'Кб', 'Мб', 'Гб', 'Тб');
		if ($b < 0) {
				return "0 " . $s[0];
		}
		$con = 1024;
		$e = (int)(log($b,$con));
		return number_format($b/pow($con,$e),2,',','.').' '.$s[$e];
	}

	/**
	 * Преобразования даты из формата 2000-01-01 00:00:00 в 01.01.2000
	 *
	 * @param string $date
	 */
	function convertDateToView ($date) {
		if (!$date || $date === '9999-01-01 00:00:00') return '—';
		$time = strtotime(call_user_func_array('sprintf',
			array_merge(array('%3$s.%2$s.%1$s'), sscanf($date, '%4s-%2s-%2s %2s:%2s:%2s')))
		);
		return date('d.m.Y', $time);
	}

	/**
	 * Преобразования даты из формата 2000-01-01 00:00:00 в 01.01.2000 00:00:00
	 *
	 * @param string $date
	 */
	function convertDateTimeToView ($date) {
		if (!$date || $date === '9999-01-01 00:00:00') return '—';
		$time = strtotime(call_user_func_array('sprintf',
			array_merge(array('%3$s.%2$s.%1$s %4$s:%5$s:%6$s'), sscanf($date, '%4s-%2s-%2s %2s:%2s:%2s')))
		);
		return date('d.m.Y H:i:s', $time);
	}

	/**
	 * Преобразование даты под требования ввода БД
	 *
	 * @param string $date
	 * @return string
	 */
	function convertDate ($date) {
		$time = stringToTime($date);
		if (!$time) return null;
		return date('Y-m-d H:i:s', $time);
	}

	/**
	 * Преобразование даты под требования ввода БД (с вводом полных суток)
	 *
	 * @param string $date
	 * @return string
	 */
	function convertDateFullDayTime ($date) {
		$time = stringToTime($date);
		if (!$time) return '';
		return date('Y-m-d 23:59:59', $time);
	}

	/**
	 * Преобразование строчки во время
	 *
	 * @param string $str
	 * @return time
	 */
	function stringToTime($str) {
		if (!$str) return 0;

		// d.m.Y
		if (preg_match('/\d{2}.\d{2}.\d{4}/', $str)) {
			$time = strtotime(call_user_func_array('sprintf',
				array_merge(array('%3$s/%2$s/%1$s'), sscanf($str, '%2s.%2s.%4s')))
			);
			return $time;
		}

		// Y-m-d
		if (preg_match('/\d{4}[\-]\d{2}[\-]\d{2}/', $str)) {
			$str = str_replace('-', '/', $str);
			return strtotime($str . " 00:00:00");
		}

		// Y/m/d H:i:s
		if (preg_match('/\d{4}[\/]\d{2}[\/]\d{2} \d{2}:\d{2}:\d{2}/', $str)) {
			return strtotime($str);
		}

		// Y-m-d H:i:s
		if (preg_match('/\d{4}[\-]\d{2}[\-]\d{2} \d{2}:\d{2}:\d{2}/', $str)) {
			$str = str_replace('-', '/', $str);
			return strtotime($str);
		}

		// d.m.y
		if (preg_match('/\d{2}.\d{2}.\d{2}/', $str)) {
			$time = strtotime(call_user_func_array('sprintf',
				array_merge(array('20%3$s/%2$s/%1$s'), sscanf($str, '%2s.%2s.%2s')))
			);
			return $time;
		}

		return $str;
	}

	/**
	 * Входной контроль и преобразование данных
	 *
	 * @param array $indata массив входных данных
	 * @param array $params массив конфигурации преобразования данных
	 * 		пример: array(
	 * 			'test1' => array(
	 * 				'type' => 'int'
	 * 			),
	 * 			'test2' => array(
	 * 				'type' => 'float'
	 * 			),
	 * 			'teststring' => array(
	 * 				'type' => 'string',
	 * 				'html' => true,
	 * 				'slash' => true,
	 * 				'trim' => true,
	 * 				'digit' => true
	 * 			),
	 * 			'teststring2' => array(
	 * 				'type' => 'string',
	 * 				'unsafe_html' => true,
	 * 				'trim' => true,
	 * 				'max_lenght' => 10
	 * 			),
	 * 			'filetest' => array(
	 * 				'type' => 'file'
	 * 			),
	 * 			'array' => array(
	 * 				'type' => 'array'
	 * 			)
	 * 		)
	 */
	function inputCheckpoint($indata, $params = array()) {
		global $lng;

		function stringAction($varparam, $str) {

			// Safe and Unsafe HTML
			if (!$varparam['unsafe_html']) {
				if ($varparam['html']) {
					// вывод html тэгов, но с преобразованием
					$str = htmlspecialchars($str, ENT_QUOTES);
				} else {
					// удаление html тэгов
					$str = strip_tags($str);
				}
			}

			// Remove screening
			if ($varparam['slash']) {
				$str = stripslashes($str);
			}

			// Trim spaces
			if ($varparam['trim']) {
				$str = trim($str);
			}

			// Remove non-digit information, except symbols: -,.
			if ($varparam['digit']) {
				$str = preg_replace('/[^\d-.,()]/i', '', $str);
			}

			// Cut to max_lenght
			if ($varparam['max_lenght']) {
				mb_internal_encoding("UTF-8");
				$str = mb_substr($str, 0, (int) $varparam['max_lenght']);
			}

			return $str;
		}

		$res_arr = array();
		$indata = array_merge($indata, $_REQUEST, $_FILES, $_SERVER);
		foreach ($params as $var => $varparam) {
			if (array_key_exists($var, $indata)) {
				switch ($varparam['type']) {
					case 'int':
						$res_arr[$var] = (int) $indata[$var];
					break;

					case 'float':
						$tmpval = str_replace(',', '.', $indata[$var]);
						$res_arr[$var] = (float) $tmpval;
					break;

					case 'string':
						if (is_array($indata[$var])) {
							$tmpval = $indata[$var];
							foreach ($lng->lng_array as $lang) {
								$tmpval[$lang['id']] = stringAction($varparam, $tmpval[$lang['id']]);
							}
						} else {
							$tmpval = $indata[$var];
							$tmpval = stringAction($varparam, $tmpval);
						}

						$res_arr[$var] = $tmpval;
					break;

					case 'file':
						$res_arr[$var] = $_FILES[$var];
					break;

					case 'array':
						$res_arr[$var] = (array) $indata[$var];
					break;

					default:
						// Without transformation
						$res_arr[$var] = $indata[$var];
					break;
				}
			}
		}
		return $res_arr;
	}

	/**
	 * Переброс данных из одного массива в другой в соответствии с маппингом
	 *
	 * @param array $arraySource
	 * @param array $arrayDest
	 * @param array $map в формате:
	 *			array(
	 *				array(
	 *					'source' => '',
	 *					'destination' => ''
	 *				)
	 *			)
	 */
	function copyMappingData($arraySource, &$arrayDest, $map) {
		if ( is_array($map) && is_array($arraySource) && is_array($arrayDest) ) {
			foreach ($map as $names) {
				$arrayDest[$names['destination']] = $arraySource[$names['source']];
			}
		}
	}

	/**
	 * Вывод строковых данных с удалением "лишнего"
	 *
	 * @param string $string
	 * @return string
	 */
	function prepareForShow($string) {
		return htmlspecialchars((stripslashes(trim($string))), ENT_QUOTES);
	}

	/**
	 * Установка значения по-умолчанию
	 *
	 * @param mix $value
	 * @param mix $default
	 * @return mix
	 */
	function setDefaultValue($value, $default) {
		return (!$value && $value !== false) ? $default : $value;
	}

	/**
	 * Константы типов ошибок функции uploadFile
	 */
	define('UF_ERROR_SIZE', 	10);
	define('UF_ERROR_UPLOAD', 	20);
	define('UF_ERROR_TYPE', 	30);
	define('UF_ERROR_LOAD', 	40);
	define('UF_ERROR_VAR', 		50);
	define('UF_ERROR_RES_PATH',	60);

	$__UF_ERROR_STRING = array(
		UF_ERROR_SIZE 		=> _('Размер файла превышает максимальный'),
		UF_ERROR_UPLOAD 	=> _('Невозможно загрузить файл. Попробуйте еще раз.'),
		UF_ERROR_TYPE 		=> _('Неверный тип файла'),
		UF_ERROR_LOAD 		=> _('Файл не загружен'),
		UF_ERROR_VAR 		=> _('Переменная не является загруженным файлом'),
		UF_ERROR_RES_PATH	=> _('Не указан путь к директории ресурсов')
	);

	/**
	 * Загрузка файла
	 *
	 * @param array $file массив с данными загружаемого файла
	 * @param array $params конфигурация
	 * 		- types (array) - разрешенные типы файлов. В формате: array("zip", "rar", ...)
	 * 		- res_path (string) - путь к директории ресурсов (параметр обязательный!, если не указан - ошибка. Пример: FOTOGR_PATH)
	 * 		- max_file_size (int) - значение максимального размера файла в байтах
	 * @return array В формате: array('path' => '', 'has_error' => false )
	 */
	function uploadFile($file, $params = array()) {

		$result = array('has_error' => false);
		// установим значения по-умолчанию
		$params['max_file_size'] = setDefaultValue($params['max_file_size'], 20000000);
		$params['types'] = setDefaultValue($params['types'], array());

		if (!$params['res_path']) {
			$result['has_error'] = UF_ERROR_RES_PATH;
			return $result;
		}

		if (is_array($file)) {
			if (is_uploaded_file($file['tmp_name'])) {
				// данные по файлу
				$file_info = pathinfo($file['name']);
				$extension = strtolower($file_info['extension']);
				// проверка типа
				if (in_array($extension, $params['types'])) {

						// проверка размера файла
						if ($params['max_file_size']) {
							if ((int) $file['size'] > (int) $params['max_file_size']) {
								$result['has_error'] = UF_ERROR_SIZE;
								return $result;
							}
						}

						// Создание папки с текущим месяцем
						$mont = strftime("%Y_%m", time());
						$filedir = $params['res_path'] . "/" . $mont;
						if (!is_dir($filedir)) {
							mkdir($filedir);
							chmod($filedir, 0775);
						}
						// Новое имя файла
						$new_name = substr(md5(uniqid(rand())), 0, 15);
						// Создание путей
						$file_path = $filedir . "/" . strtolower($new_name . "." . $extension);
						$file_web = str_replace(BASE_PATH, "", $file_path);

						// Скопируем файл
						if (move_uploaded_file($file['tmp_name'], $file_path) === false) {
							$result['has_error'] = UIO_ERROR_UPLOAD;
							return $result;
						}
						chmod($file_path, 0666);

						// Вернем путь файла
						$result['path'] = $file_web;

				} else {
					$result['has_error'] = UF_ERROR_TYPE;
				}
			} else {
				$result['has_error'] = UF_ERROR_LOAD;
			}
		} else {
			$result['has_error'] = UF_ERROR_VAR;
		}
		return $result;

	}

	/**
	 * Константы типов ошибок функции uploadImageOperation
	 */
	define('UIO_ERROR_SIZE', 	10);
	define('UIO_ERROR_UPLOAD', 	20);
	define('UIO_ERROR_TYPE', 	30);
	define('UIO_ERROR_LOAD', 	40);
	define('UIO_ERROR_VAR', 	50);

	$__UIO_ERROR_STRING = array(
		UIO_ERROR_SIZE 		=> _('Размер изображения превышает максимальный'),
		UIO_ERROR_UPLOAD 	=> _('Невозможно загрузить файл. Попробуйте еще раз.'),
		UIO_ERROR_TYPE 		=> _('Неверный тип файла. Разрешенные типы: gif, jpeg, png'),
		UIO_ERROR_LOAD 		=> _('Файл не загружен'),
		UIO_ERROR_VAR 		=> _('Переменная не является загруженным файлом')
	);

	/**
	 * Загрузка фотографий с обработкой
	 *
	 * @param array $image_file массив с данными загружаемого файла
	 * @param array $params конфигурация
	 * 		Параметры конфигурации:
	 * 			- max_file_size (int)				- значение максимального размера файла изображения в байтах
	 * 			- safe_original (boolean) 			- сохранять оригинальный файл (по-умолчанию true)
	 * 			- photo_no_resize (boolean) 		- флаг пропуска процедуры ресайза картинки (по-умолчанию false)
	 *			- image_array {						- массив размеров изображений которые будут создаваться из оригинального
	 *				- name_image {
	 * 					- width (int) 		- ширина фотографии
	 * 					- height (int) 		- высота фотографии
	 * 					- quality (int) 	- качество (по-умолчанию 75)
	 * 					- crop (boolean) 	- обрезание фотографии для получения указанных размеров (по-умолчанию false)
	 *					- resize (boolean)	- применять ли ресайз к данному формату (по-умолчанию true)
	 *					- start_x (int)		- начальная координата точки от которой делается вырезка (используется при resize = false) (по-умолчанию 0)
	 *					- start_y (int)		- начальная координата точки от которой делается вырезка (используется при resize = false) (по-умолчанию 0)
	 *					- corner_radius (int) - радиус скругления углов картинки
	 * 				}
	 * 			}
	 *
	 * @return array В формате: array('image_array' => array('image' => ''), 'has_error' => false )
	 */
	function uploadImageOperation($image_file, $params = array()) {

		$result = array('has_error' => false);
		// установим значения по-умолчанию
		$params['max_file_size'] 	= setDefaultValue($params['max_file_size'], 5000000);
		$params['safe_original']	= setDefaultValue($params['safe_original'], true);
		$params['photo_no_resize']  = setDefaultValue($params['photo_no_resize'], false);
		if (!$params['photo_no_resize'] && (!is_array($params['image_array']) || count($params['image_array']) < 1)) {
			$params['image_array']['tmb']['width'] 	 = 50;
			$params['image_array']['tmb']['height']  = 38;
			$params['image_array']['tmb']['quality'] = 95;
			$params['image_array']['tmb']['crop'] 	 = true;

			$params['image_array']['nrm']['width'] 	 = 800;
			$params['image_array']['nrm']['height']  = 600;
			$params['image_array']['nrm']['quality'] = 75;
			$params['image_array']['nrm']['crop'] 	 = false;
		} else {
			foreach ($params['image_array'] as $key => $param) {
				$params['image_array'][$key]['quality'] = setDefaultValue($param['quality'], 75);
				$params['image_array'][$key]['crop']    = setDefaultValue($param['crop'], false);
				$params['image_array'][$key]['resize']  = setDefaultValue($param['resize'], true);
				$params['image_array'][$key]['start_x'] = setDefaultValue($param['start_x'], 0);
				$params['image_array'][$key]['start_y'] = setDefaultValue($param['start_y'], 0);
				$params['image_array'][$key]['corner_radius'] = setDefaultValue($param['corner_radius'], 0);
			}
		}

		if (is_array($image_file)) {
			if (is_uploaded_file($image_file['tmp_name'])) {
				if ($image_file['type'] == 'image/gif' ||
					$image_file['type'] == 'image/jpeg' ||
					$image_file['type'] == 'image/pjpeg' ||
					$image_file['type'] == 'image/png' ||
					$image_file['type'] == 'image/x-png' ) {

						// проверка размера файла
						if ($params['max_file_size']) {
							if ((int) $image_file['size'] > (int) $params['max_file_size']) {
								$result['has_error'] = UIO_ERROR_SIZE;
								return $result;
							}
						}

						$file_info = pathinfo($image_file['name']);
						$extension = strtolower($file_info['extension']);

						// Создание папки с текущим месяцем
						$mont = strftime("%Y_%m", time());
						$fotodir = FOTOGR_PATH . "/" . $mont;
						if (!is_dir($fotodir)) {
							mkdir($fotodir);
							@chmod($fotodir, 0777);
						}
						// Новое имя файла
						$new_name = substr(md5(uniqid(rand())), 0, 15);
						// Создание пути к оригинальному файлу
						$photo_orig_path = $fotodir . "/" . strtolower($new_name . "_orig" . "." . $extension);

						// Скопируем файл
						if (move_uploaded_file($image_file['tmp_name'], $photo_orig_path) === false) {
							$result['has_error'] = UIO_ERROR_UPLOAD;
							return $result;
						}
						@chmod($photo_orig_path, 0777);

						// Если не сказано пропускать ресайз и есть картинки - переберем все требуемые размеры
						if (!$params['photo_no_resize'] && is_array($params['image_array'])) {
							foreach ($params['image_array'] as $name_image => $image_params) {
								if ($image_params['corner_radius']) {
									$photo_path = $fotodir . "/" . strtolower($new_name . "_" . $name_image . ".png");
									$photo_web = str_replace(BASE_PATH, "", $photo_path);
								} else {
									$photo_path = $fotodir . "/" . strtolower($new_name . "_" . $name_image . "." . $extension);
									$photo_web = str_replace(BASE_PATH, "", $photo_path);
								}
								// Изменение размеров
								if  ($image_params['resize']) {
									createResizeImg(
										$image_params['width']
									  , $image_params['height']
									  , $image_params['quality']
									  , $extension
									  , $photo_orig_path
									  , $photo_path
									  , $image_params['crop']
									  , $image_params['resize']
									  , $image_params['start_x']
									  , $image_params['start_y']
									  , $image_params['corner_radius']
									);
								} else {
									copy($photo_orig_path, $photo_path);
								}
								$result['image_array'][$name_image] = $photo_web;
							}
						}

						if ($params['photo_no_resize']) {
							$photo_path = $fotodir . "/" . strtolower($new_name . "." . $extension);
							$photo_web = str_replace(BASE_PATH, "", $photo_path);
							// копируем исходный файл в результирующий
							copy($photo_orig_path, $photo_path);
							$result['image'] = $photo_web;
						}

						@chmod($photo_path, 0666);

						if (!$params['safe_original']) {
							// удалим оригинал
							@unlink($photo_orig_path);
						} else
							$result['orig_image'] = str_replace(BASE_PATH, "", $photo_orig_path);
				} else {
					$result['has_error'] = UIO_ERROR_TYPE;
				}
			} else {
				$result['has_error'] = UIO_ERROR_LOAD;
			}
		} else {
			$result['has_error'] = UIO_ERROR_VAR;
		}
		return $result;
	}

	/**
	 * Синоним clearImageOperation
	 */
	function clearFileOperation($config = array()) {
		clearImageOperation($config);
	}

	/**
	 * Операции по очистке изображений
	 *
	 * @param array $config
	 * 		Параметры конфигурации:
	 * 			db_clean (array) конфигурация удаления из базы
	 * 				- table (string) название таблицы с данными фотографий
	 * 				- fields (array) массив полей, содержащих информацию о путях файлов
	 * 				- identy (array) массив значений для фильтрации записей
	 *				- clear_record (boolean) удалять значения в таблице после очистки фотографий
	 * 			path_clean (array) массив путей для удаления файлов фотографий
	 * @return boolean
	 */
	function clearImageOperation($config = array()) {
		global $db;

		// получение информации из базы
		if ($config['db_clean']) {
			$dbcfg = $config['db_clean'];
			if (is_array($dbcfg)) {
				$fields = join(",", $dbcfg['fields']);
				$sql = "SELECT {$fields} FROM `{$dbcfg['table']}` WHERE ?*";
				$res = $db->get_all($sql, $dbcfg['identy']);
				if (is_array($res)) {
					$delimgpath = array();
					foreach ($res as $paths) {
						foreach ($paths as $im_path) {
							$delimgpath[] = $im_path;
						}
					}
					clearImageOperation(array('path_clean' => $delimgpath));
					if ($dbcfg['clear_record']) {
						// очищаем записи
						$db->delete($dbcfg['table'], $dbcfg['identy']);
					}
				}
			}
		}

		// удаление файлов по переданным путям
		if ($config['path_clean']) {
			if (is_array($config['path_clean'])) {
				foreach ($config['path_clean'] as $path) {
					if (strpos($path, BASE_PATH) === false) {
						@unlink(BASE_PATH . $path);
					} else {
						@unlink($path);
					}
				}
			}
		}
		return true;
	}

	/**
	 * Удаление записей из DB с очисткой таблицы Language (связки удаляемых значений)
	 *
	 * @param string $table - таблица запроса (очистки)
	 * @param array $identy - фильтр выборки очищаемых значений (пример: array('id' => 1))
	 * @param array $lngFields - поля с записями, связанными с Language таблицей (пример: array('name', 'description'))
	 * @param boolean $removeRecord - флаг операции удаления "очищенных" записей
	 * @return boolean
	 */
	function clearLngRecords($table, $identy = array('1' => '1'), $lngFields, $removeRecord = true) {
		global $db, $lng;

		if (is_array($lngFields)) {
			$selected = $db->get_all("
				SELECT *
					FROM " . $table . "
				WHERE ?*
			", $identy);

			if (is_array($selected)) {
				// итерация по значениям
				foreach ( $selected as $svalue ) {
					// итерация по полям
					foreach ( $lngFields as $field ) {
						$lng->del($svalue[$field]);
					}
				}
			}
		}

		if ($removeRecord) {
			// удаляем очищенные записи
			$db->delete($table, $identy);
		}
	}

	/**
	 * ===========================================================================================================
	 * Функции валидации
	 * ===========================================================================================================
	 */

	/**
	 * Валидация логина
	 *
	 * @param string $login
	 * @return boolean
	 */
	function validLogin($login) {
		return preg_match('/^[\w]*$/si', $login);
	}

	/**
	 * Валидация даты в формате YYYY-MM-DD
	 *
	 * @param string $datastr
	 * @return boolean
	 */
	function validDate($datastr) {
		return preg_match('/(19|20)[0-9]{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])/i', $datastr);
	}

	/**
	 * Валидация даты и времени в формате YYYY-MM-DD HH:MM:SS
	 *
	 * @param string $datatimestr
	 * @return boolean
	 */
	function validDateTime($datatimestr) {
		return preg_match('/(19|20)[0-9]{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) [\d]{2}:[\d]{2}:[\d]{2}/i', $datatimestr);
	}


	/**
	 * ===========================================================================================================
	 * Старые, "неправильные", "жуткостильные" функции
	 *
	 * TODO необходимо избавиться везде от использования этих функций!
	 * ===========================================================================================================
	 */



/* From this project */

    function genDateRange($startM = 1, $startY = 2010, $count_month = 1) {
	$res = array(); $year_changed = false; $marr = array();

	for ($i = 0; $i < $count_month; $i++) {
	    $res[] = sprintf("%02d", $startM) . ".{$startY}";
	    $res_sql[] = sprintf("%04d-%02d-%02d", $startY, $startM, 1);
	    $marr[] = $startM;

	    $startM++;
	    if ($startM > 12) {
		$startM = 1;
		$startY++;
		$year_changed = true;
	    }
	}

	return array(
	    'str_dates' => $res,
	    'year_changed' => $year_changed,
	    'month_array' => $marr,
	    'sql_format' => $res_sql
	);
    }

    /**
     * Функция склонения числительных в русском языке
     *
     * @param int    $number Число которое нужно просклонять
     * @param array  $titles Массив слов для склонения
     * @return string
     **/
    function declOfNum($number, $titles) {
	$cases = array (2, 0, 1, 1, 1, 2);
	return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }