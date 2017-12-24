<?php 

	$product_data = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE chpu = ?", $this->c_this['value'][0]); // продукт
	/*
	 * строит hmlt для термометра
	 * @param array $nodeSet массив узлов
	 * @return string $html код hmtl
	 */




	/*
	 * определение главное и дочерней категории продуктов, в которых находится продукт который открыл пользователь
	 * @param int $product_id
	 * @return string код градусника
	 */
	function getProductCategoryHTML($product_id) {
		global $db;

		if($product_id){
			$category = $db->get_row("
				SELECT dc.id AS category_id
					 , dc.name AS category
					 , dmc.id AS main_category_id
					 , dmc.name AS main_category
					FROM " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcp
					   , " . CFG_DBTBL_DICT_CATEGORY . " AS dc
					   , " . CFG_DBTBL_DICT_MAIN_CATEGORY . " AS dmc
					WHERE mcp.id_product = ?
					  AND mcp.id_category = dc.id
					  AND dc.id_main_category = dmc.id
					LIMIT 1
			", $product_id);
			if(is_array($category)) {
    			$result  = '<li><a href="/catalog/group:10' . ((strlen($category['main_category_id']) > 1) ? $category['main_category_id'] : '0' . $category['main_category_id']) . '/">' . $category['main_category'] . '</a><span>»</span></li>';
    			$result .= '<li><a href="/catalog/group:' . $category['category_id'] . '/">' . $category['category'] . '</a><span>»</span></li>';
			}
			return $result;
		}
		return '';
	}
	
	function getProductCategoryName($product_id) {
		global $db;

		if($product_id){
			$category = $db->get_row("
				SELECT dc.name AS category
					FROM " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcp
					   , " . CFG_DBTBL_DICT_CATEGORY . " AS dc
					WHERE mcp.id_product = ?
					  AND mcp.id_category = dc.id
					LIMIT 1
			", $product_id);			
			if ($category['category']) {
				$result = '<p class="ico-star">' . $category['category'] . '</p>';
			}
			return $result;
		}
		return '';
	}

	
	
	/*
	 * определение продукта который открыл пользователь
	 * @param int $disc_cat
	 * @return string код градусника
	 */
	function getProductHTML($product_id) {
		global $db;

		if($product_id){
			$product = $db->get_row("SELECT mp.name FROM " . CFG_DBTBL_MOD_PRODUCT . " AS mp WHERE mp.id = ?", $product_id);
			$result  = "<li class='active'>" . $product['name'] . "</li>";

			return $result;
		}
		return '';
	}

	//-----------------------------------------------------------
	// Если мы в товаре
	$html = '';
	if (is_array($product_data)) {
		$html = '<li><a href="/">Главная</a><span>»</span></li><li><a href="/catalog/">Каталог</a><span>»</span></li>' . getProductCategoryHTML($product_data['id']) . getProductHTML($product_data['id']);
	} else {
		
	}
?>
<div class="breadcrumbs">
	<ul>
		<?=$html?>
	</ul>
</div>
<div class="title">
    <?=getProductCategoryName($product_data['id'])?>
</div>