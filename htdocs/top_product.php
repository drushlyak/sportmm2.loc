<?php 
	$main_sections_array = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS . " ORDER BY ord ASC");
	if(is_array($main_sections_array)) {
		foreach($main_sections_array as $main_sections) {
			$sql = sql_placeholder("
					SELECT
						mp.*					
					FROM " . CFG_DBTBL_MOD_PRODUCT . " AS mp
						LEFT JOIN " . CFG_DBTBL_MOD_MAIN_SECTIONS_PRODUCT . " AS mmsp ON mmsp.id_product = mp.id
					WHERE  mmsp.id_section = ? /* AND mp.is_active = 1 AND mp.	is_view_main = 1*/
					GROUP BY mp.id
					ORDER BY RAND()
					LIMIT " . $main_sections['page'] . "
				",$main_sections['id']);
				// AND mp.is_active = 1 AND mp.	is_view_main = 1 - закоменчена строка т.к. заказчик пожелал чтобы на главной товары выводились независимо от галочки

				$products_result = $db->get_all($sql);
				if(is_array($products_result)) {
?>

					<div>
					<h1>Спортивное питание для новичков и профессионалов</h1>
					</div>
					<div class="title">
        <p class="ico-star"><?=$main_sections['text']?></p>
    </div>
    <div class="hot-deals">
            	<?php
				if (is_array($products_result)) {
					$i = 1;
					foreach($products_result as $product) {
							$product['main_foto176'] = ($product['main_foto176']) ? $product['main_foto176'] : "/images/zaglushka_middle.gif";
?>		
	        <div class="category-product">
			<a href="/product/<?=$product['chpu']?>/">
				<img src="<?=$product['main_foto176']?>" alt="">
				<p><?=$product['name']?></p>
			</a>
			<!--<span class="orange" style="display:none"> <?=$product['cost_excess']?> руб.</span>
		<input type="submit" value="<?=$product['cost_excess']?> руб." class="orange"> -->

				<?php if ($product['is_active']==1) : ?>
					<span class="orange" style="display:none"> <?=$product['cost_excess']?> руб.</span>
					<input type="submit" onclick="addToBasket(<?php echo $product['id'];?>,countProduct()); return false;" value="<?=$product['cost_excess']?> руб." class="orange">
				<?php //onclick="addToBasket(3961,countProduct()); return false;"
				else : ?>
					<span class="orange" style="display:none">Ждем поступление</span>
					<input type="text"  value="Ждем поступление" class="orange" disabled>
				<?php endif ;?>



			</div>
<?php 		//onclick="addToBasket(3961,countProduct()); return false;"
					}
?>
			</div>
<?php 
								} else {
					$html_products = 'Соответствующих товаров нет';
				}

				echo $html_products;
				 
			}
		}
	}

?>