<?php 
	/**
	 * @copyright Alex Cruiser (cruiser.com.ua)
	 */
	//exit;  

	require_once ("../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	
	$product_array = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT);
	if (is_array($product_array)) {

		foreach($product_array as $pr) {
			//удалим фото
			$old_photo_array = $db->get_all_("SELECT tmb_path, path, path_orig FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " WHERE id_product = ?", $pr['id']);
			
			if(is_array($old_photo_array)) {
				foreach($old_photo_array as $old_photo) {
					fb(BASE_PATH . $old_photo['tmb_path']);
					($old_photo['tmb_path']) ? unlink(BASE_PATH . $old_photo['tmb_path']) : null;
					($old_photo['path']) ? unlink(BASE_PATH . $old_photo['path']) : null;
					($old_photo['path_orig']) ? unlink(BASE_PATH . $old_photo['path_orig']) : null;
				}
				$db->query("DELETE FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " WHERE id_product = ?", $pr['id']);
			}
			($pr['main_foto50']) ? unlink(BASE_PATH . $pr['main_foto50']) : null;
			($pr['main_foto80']) ? unlink(BASE_PATH . $pr['main_foto80']) : null;
			($pr['main_foto176']) ? @unlink(BASE_PATH . $pr['main_foto176']) : null;
			($pr['main_foto340']) ? @unlink(BASE_PATH . $pr['main_foto340']) : null;
			($pr['main_foto_orig']) ? @unlink(BASE_PATH . $pr['main_foto_orig']) : null;
			
			// Удалим признак товара
			$db->query("DELETE FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " WHERE id_product = ?", $pr['id']);
			
			//Удалим все коллекции данного товара
			$db->delete(CFG_DBTBL_MOD_COLLECTION_PRODUCT, array('id_product' => $pr['id']));
			
			//Удалим все коллекции
			$db->query("DELETE FROM" . CFG_DBTBL_DICT_COLLECTIONS);
			
			//Удалим все категории данного товара
			$db->delete(CFG_DBTBL_MOD_CATEGORY_PRODUCT, array('id_product' => $pr['id']));
			
			//Удалим все категории
			$db->query("DELETE FROM " . CFG_DBTBL_DICT_CATEGORY);
		}
	}
	//Удалим все продукты
	$db->query("DELETE FROM " . CFG_DBTBL_MOD_PRODUCT);
	
	header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="UTF-8"?>
				<delete>
				        <record>
				                <code>0</code>
				        </record>
				</delete>';