<?php 

	/**
	 * @copyright Alex Cruiser (cruiser.com.ua)
	 */
	//exit;
    

	require_once ("../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	
	//выделяем больше памяти для выполнения скрипта
	ini_set('memory_limit', '256M');
	
	$error = 0;
	$er_num = 0;
	
	$arch = UPLOAD_PATH . "/unloading.zip";
			
	require_once (LIB_PATH . "/Zip/pclzip.lib.php");
	
	// Создаем объект $zip. В качестве параметра передаем имя архива.
	$zip = new PclZip($arch);
	
	$filedir = UPLOAD_PATH . "/" . date("Y_m");
	if (!is_dir($filedir)) {
		mkdir($filedir);
		chmod($filedir, 0777);
	}
	
	//создаем текстовый в файл, в котором будут храниться id уже выгруженных товаров
	$is_file = file_exists(UPLOAD_PATH . '/id.txt');
	if(!$is_file) {
		$fp = fopen(UPLOAD_PATH . '/id.txt', 'w');		
	} else {
		//получим содержимое файла, в котором хранятся id выгруженных товаров;
		$read = file_get_contents(UPLOAD_PATH . '/id.txt', 'r');
		$id_array = explode('---', $read);
		$fp = fopen(UPLOAD_PATH . '/id.txt', 'a+');
	}
	

	$content = $zip->extract(PCLZIP_OPT_PATH, $filedir);
	if(!is_array($content)) {
		$error = 1;
		$er_num = "1";	
	}

	$filedir_xml = $filedir . "/products.xml";
	$xml_data = file_get_contents($filedir_xml);
	if($xml_data === false) {
		$error = 1;
		$er_num = "4";		
	}
	$sx = new SimpleXMLElement($xml_data);
	
	$tvr = $sx->products->product;
	$y = 0;
	
	$count_el = count($tvr); //количество товаров
	
	foreach($tvr as $key => $tv) {
	unset($t_id,$t_code,$t_name,$t_chpu,$t_material,$t_width,$t_height,
			$t_height,$t_depth,$t_num_stock,$t_collection,$t_category,$t_images_array,$t_images,$t_operation);
		$t_operation = (string) $tv->operation;
		$t_id = (int) $tv->id;
		$t_code = (string) $tv->code;
		$t_name = (string) $tv->name;
		$t_chpu = strtolower(translitIt($t_name));
		$t_material = (string) $tv->material;
		$t_width = (float) $tv->width;
		$t_height = (float) $tv->height;
		$t_depth = (float) $tv->depth;
		//$t_num_stock = (string) $tv->amount_in_box;
		$t_collection = (string) $tv->collection;
		$t_category = (string) $tv->category;
		$t_images_array = $tv->images->image;
		$t_images = '';
		foreach($t_images_array as $img) {
			$t_images[] = (string) $img;
			//echo $img . "<hr>";
		}
		while (true) {
			if ($db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE chpu = ?", $t_chpu)) {
				$t_chpu .= '_';
				continue;
			}
			break;
		}
		$product = array();
		$product = array(
			'gid' => $t_id,
			'article' => $t_code,
			'name' => $t_name,
			'chpu' => $t_chpu,
			'material' => $t_material,
			'h_w_l_size' => $t_width . 'x' . $t_height . 'x' . $t_depth,
			'is_active' => 1,
			'i_date' => date('Y-m-d')
		);
		//echo $i."<br>";
		if($t_operation == "Delete") {
			//$db->query("UPDATE " . CFG_DBTBL_MOD_PRODUCT . " SET is_active = 0 WHERE gid = ?", $t_id);
			//echo "Товар удален, GID:" . $t_id . "<br>";
			if(in_array($t_id, $id_array)) {
				continue;
			} else {
				$db->query("UPDATE " . CFG_DBTBL_MOD_PRODUCT . " SET is_active = 0 WHERE gid = ?", $t_id);
				fwrite($fp, $t_id . '---'); // Запись в файл
				$y++;
			}
		} elseif($t_operation == "Insert" || $t_operation == "Update") {
			$new_id = $db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE gid = ?", $t_id);
			if($new_id) {
				//Обновим продукт
				if(in_array($t_id, $id_array)) {
					continue;
				} else {					
					$db->UPDATE(CFG_DBTBL_MOD_PRODUCT, $product, array('id' => $new_id));
					//удалим старые фото
					$old_photo_array = $db->get_all("SELECT tmb_path, path, path_orig FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " WHERE id = ?", $new_id);
					if(is_array($old_photo_array)) {
						foreach($old_photo_array as $old_photo) {
							($old_photo['tmb_path']) ? @unlink(BASE_PATH . $old_photo['tmb_path']) : null;
							($old_photo['path']) ? @unlink(BASE_PATH . $old_photo['path']) : null;
							($old_photo['path_orig']) ? @unlink(BASE_PATH . $old_photo['path_orig']) : null;
						}
						$db->query("DELETE FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " WHERE id_product = ?", $new_id);
					}
					//добавим новые фотографии
					if(is_array($t_images)) {
						foreach($t_images as $images) {
							if(filesize($filedir . "/" . $images) > 300000) {
								continue;
							}					
							$file_path50 = '';
				 			$file_web50 = '';
				 			$file_path80 = '';
				 			$file_web80 = '';
				 			$file_path176 = '';
				 			$file_web176 = '';
				 			$file_path = '';
				 			$file_web = '';
							$file_path_orig = '';
				 			$file_web_orig = '';
			
							$file_info = pathinfo($filedir . "/" . $images);
							$extension = strtolower($file_info['extension']);
			
							// Новое имя файла
							$new_name = substr(md5(uniqid(rand())), 0, 15);
							// Имя папки для файла логов
							$new_filesdir = FOTOGR_PATH . "/" . date("Y_m");
							if (!is_dir($new_filesdir)) {
								mkdir($new_filesdir);
								chmod($new_filesdir, 0777);
							}
			
							// Создание путей
							$file_path50 = $new_filesdir . "/" . strtolower($new_name . "_50." . $extension);
							$file_web50 = str_replace(BASE_PATH, "", $file_path50);
							$file_path80 = $new_filesdir . "/" . strtolower($new_name . "_80." . $extension);
							$file_web80 = str_replace(BASE_PATH, "", $file_path80);
							$file_path176 = $new_filesdir . "/" . strtolower($new_name . "_176." . $extension);
							$file_web176 = str_replace(BASE_PATH, "", $file_path176);
							$file_path = $new_filesdir . "/" . strtolower($new_name . "_150." . $extension);
							$file_web = str_replace(BASE_PATH, "", $file_path);
							$file_path_orig = $new_filesdir . "/" . strtolower($new_name . "_orig." . $extension);
							$file_web_orig = str_replace(BASE_PATH, "", $file_path_orig);
							
							// Скопируем файл
							createRoundFramingJpg($filedir . "/" . $images, 58, 58, array(255,255,255), 95, $file_path50);
							createRoundFramingJpg($filedir . "/" . $images, 88, 88, array(255,255,255), 95, $file_path80);
							createRoundFramingJpg($filedir . "/" . $images, 210, 210, array(255,255,255), 95, $file_path176);
							createRoundFramingJpg($filedir . "/" . $images, 437, 438, array(255,255,255), 95, $file_path);
							
							$new_img = ImageCreateFromJpeg($file_path);
							$orig_img = ImageCreateFromJpeg($filedir . "/" . $images);
							if (is_resource($new_img) === true) {
								$widthOrig = imagesx($orig_img);
								$heightOrig = imagesy($orig_img);
								$widthNew = imagesx($new_img);
								$heightNew = imagesy($new_img);
								$kof_x = $widthNew / $widthOrig;
								$kof_y = $heightNew / $heightOrig;
								$min_kof = $kof_x;
								$min_kof = ($kof_y < $min_kof) ? $kof_y : $min_kof;
								
								$widthNew_ = round($widthNew / $min_kof);
								$heightNew_ = round($heightNew / $min_kof);
								
								createRoundFramingJpg($filedir . "/" . $images, $widthNew_, $heightNew_, array(255,255,255), 95, $file_path_orig);
							} 
							ImageDestroy($orig_img);
							ImageDestroy($new_img);
							$db->query("INSERT INTO " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " SET tmb_path = ?, path = ?, path_orig = ?, id_product = ?", $file_web50, $file_web, $file_web_orig, $new_id);
	
						}
					}
					$db->query("UPDATE " . CFG_DBTBL_MOD_PRODUCT . " SET main_foto50 = ?, main_foto80 = ?, main_foto176 = ?, main_foto340 = ?, main_foto_orig = ? WHERE id = ?", $file_web50, $file_web80, $file_web176, $file_web, $file_web_orig, $new_id);
					//echo "Товар обновлен, ID:" . $new_id . "<br>";				 
					 
					fwrite($fp, $t_id . '---'); // Запись в файл
					$y++;
				}
				
			} else {
				//добавим новый продукт
				if(in_array($t_id, $id_array)) {
					continue;
				} else {					
					$db->INSERT(CFG_DBTBL_MOD_PRODUCT, $product);
					$new_id = $db->insert_id;
					// Установим признак всех
					$db->query("INSERT INTO " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " SET id_product = ?, id_type_view = 1", $new_id);
					//добавим новые фотографии
					if(is_array($t_images)) {
						$i = 1;							
						foreach($t_images as $images) {
											
							$file_path50 = '';
				 			$file_web50 = '';
				 			$file_path80 = '';
				 			$file_web80 = '';
				 			$file_path176 = '';
				 			$file_web176 = '';
				 			$file_path = '';
				 			$file_web = '';
							$file_path_orig = '';
				 			$file_web_orig = '';
			
							$file_info = pathinfo($filedir . "/" . $images);
							$extension = strtolower($file_info['extension']);
			
							// Новое имя файла
							$new_name = substr(md5(uniqid(rand())), 0, 15);
							// Имя папки для файла логов
							$new_filesdir = FOTOGR_PATH . "/product/" . date("Y_m");
							if (!is_dir($new_filesdir)) {
								mkdir($new_filesdir);
								chmod($new_filesdir, 0777);
							}
			
							// Создание путей
							$file_path50 = $new_filesdir . "/" . strtolower($new_name . "_50." . $extension);
							$file_web50 = str_replace(BASE_PATH, "", $file_path50);
							$file_path80 = $new_filesdir . "/" . strtolower($new_name . "_80." . $extension);
							$file_web80 = str_replace(BASE_PATH, "", $file_path80);
							$file_path176 = $new_filesdir . "/" . strtolower($new_name . "_176." . $extension);
							$file_web176 = str_replace(BASE_PATH, "", $file_path176);
							$file_path = $new_filesdir . "/" . strtolower($new_name . "_150." . $extension);
							$file_web = str_replace(BASE_PATH, "", $file_path);
							$file_path_orig = $new_filesdir . "/" . strtolower($new_name . "_orig." . $extension);
							$file_web_orig = str_replace(BASE_PATH, "", $file_path_orig);
							
							// Скопируем файл
							createRoundFramingJpg($filedir . "/" . $images, 58, 58, array(255,255,255), 95, $file_path50);
							createRoundFramingJpg($filedir . "/" . $images, 88, 88, array(255,255,255), 95, $file_path80);
							createRoundFramingJpg($filedir . "/" . $images, 210, 210, array(255,255,255), 95, $file_path176);
							createRoundFramingJpg($filedir . "/" . $images, 437, 438, array(255,255,255), 95, $file_path);
							
							$new_img = ImageCreateFromJpeg($file_path);
							$orig_img = ImageCreateFromJpeg($filedir . "/" . $images);
							if (is_resource($new_img) === true) {
								$widthOrig = imagesx($orig_img);
								$heightOrig = imagesy($orig_img);
								$widthNew = imagesx($new_img);
								$heightNew = imagesy($new_img);
								$kof_x = $widthNew / $widthOrig;
								$kof_y = $heightNew / $heightOrig;
								$min_kof = $kof_x;
								$min_kof = ($kof_y < $min_kof) ? $kof_y : $min_kof;
								
								$widthNew_ = round($widthNew / $min_kof);
								$heightNew_ = round($heightNew / $min_kof);
								
								createRoundFramingJpg($filedir . "/" . $images, $widthNew_, $heightNew_, array(255,255,255), 95, $file_path_orig);
							} 
							
							$db->query("INSERT INTO " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " SET tmb_path = ?, path = ?, path_orig = ?, id_product = ?", $file_web50, $file_web, $file_web_orig, $new_id);
							//добавим фотографии в таблице продукта
							if($i == 1) {
								$sql_img = sql_placeholder("UPDATE " . CFG_DBTBL_MOD_PRODUCT . " SET main_foto50 = ?, main_foto80 = ?, main_foto176 = ?, main_foto340 = ?, main_foto_orig = ? WHERE id = ?", $file_web50, $file_web80, $file_web176, $file_web, $file_web_orig, $new_id);
								fb($sql_img);
								$db->query($sql_img);
							}
							$i++;
						}
					}
					
					//echo "Товар добавлен, ID:" . $insert_id . "<br>";
					fwrite($fp, $t_id . '---'); // Запись в файл
					$y++;
				}
				
				
			}
			
			//добавление категорий и коллекций
			$id_col = $db->get_one("SELECT id FROM " . CFG_DBTBL_DICT_COLLECTIONS . " WHERE name = ?", $t_collection);
			if(!$id_col) {
				$id_col = $db->insert(CFG_DBTBL_DICT_COLLECTIONS, array('name' => $t_collection));
				$new_id_col = $db->insert_id;
				//echo "Добавлена новая коллекция, ID:" . $new_id_col . "<br>";			
			}
			$db->delete(CFG_DBTBL_MOD_COLLECTION_PRODUCT, array('id_product' => $new_id));
			$db->insert(CFG_DBTBL_MOD_COLLECTION_PRODUCT, array('id_collection' => $id_col, 'id_product' => $new_id));
			
			$id_cat = $db->get_one("SELECT id FROM " . CFG_DBTBL_DICT_CATEGORY . " WHERE name = ?", $t_category);
			if(!$id_cat) {
				$max_ord = $db->get_one("SELECT MAX(ord) FROM " . CFG_DBTBL_DICT_CATEGORY);
				$max_ord = $max_ord + 1;
				$id_cat = $db->insert(CFG_DBTBL_DICT_CATEGORY, array('name' => $t_category, 'ord' => $max_ord));
				$new_id_cat = $db->insert_id;
				//echo "Добавлена новая категория, ID:" . $new_id_cat . "<br>";	
			}
			$db->delete(CFG_DBTBL_MOD_CATEGORY_PRODUCT, array('id_product' => $new_id));
			$db->insert(CFG_DBTBL_MOD_CATEGORY_PRODUCT, array('id_category' => $id_cat, 'id_product' => $new_id));
			//echo $id_prdcr .'<br>';
			
		}
		
		//Echo $y . " - " . $t_id . "<br>"; 
		if($y == 20) {			
			$error = 2;
			break;
		}
	}
	fclose($fp);
	
	
	$filedir_xml = $filedir . "/balances.xml";
	$xml_data = file_get_contents($filedir_xml);
	if($xml_data === false) {
		$error = 1;
		$er_num = "2";		
	}
	$sxp = new SimpleXMLElement($xml_data);

	$tvrp = $sxp->products->product;
	foreach($tvrp as $tvp) {
		$t_id = (int) $tvp->id;
		$t_price = (int) $tvp->price;
		$t_num_stock = (int) $tvp->quantity;

		
		$db->query("UPDATE " . CFG_DBTBL_MOD_PRODUCT . " SET cost_excess = ?, num_stock = ? WHERE gid = ?", $t_price, $t_num_stock, $t_id);
	}
	
	
	//функция удаления каталога
	function RemoveDir($path) {
		if(file_exists($path) && is_dir($path))	{
			$dirHandle = opendir($path);
			while (false !== ($file = readdir($dirHandle)))	{
				if ($file!='.' && $file!='..')// исключаем папки с назварием '.' и '..' 
				{
					$tmpPath=$path.'/'.$file;
					chmod($tmpPath, 0777);
					
					if (is_dir($tmpPath)) {  // если папка
						RemoveDir($tmpPath);
				   	} else { 
		  				if(file_exists($tmpPath)) {
							// удаляем файл 
		  					unlink($tmpPath);
						}
		  			}
				}
			}
			closedir($dirHandle);
			
			// удаляем текущую папку
			if(file_exists($path)) {
				rmdir($path);
				//echo "папка " . $path . " успешно удалена!<br>";
			}
		} else {
			//echo "Удаляемой папки не существует или это файл!";
			return false;
		}
	}
	
	//Удаляем каталог с новыми файлами
	
	if(RemoveDir($filedir) === false) {
		$error = 1;
		$er_num = "1";	
	} else {
		RemoveDir($filedir);
	}
	
	if(!$error) {
		@unlink(UPLOAD_PATH . '/id.txt');
		header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="UTF-8"?>
				<upload>
				        <record>
				                <code>0</code>
				        </record>
				</upload>';
	} elseif($error == 2) {
		header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="UTF-8"?>
				<upload>
				        <record>
				                <code>2</code>
				        </record>
				</upload>';
	} else {
		header("Content-Type: text/xml; charset=UTF-8");
		echo '<?xml version="1.0" encoding="UTF-8"?>
				<upload>
        			<record>
                		<code>-1</code>
                		<message>ОШИБКА!!! Код ошибки: '.$er_num.'</message>
        			</record>
				</upload>';
	}