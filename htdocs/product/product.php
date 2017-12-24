<?php

	$product_data = $db->get_row("SELECT p.*, b.name AS brand FROM " . CFG_DBTBL_MOD_PRODUCT . " AS p LEFT JOIN " . CFG_DBTBL_MOD_BRANDS . " AS b ON b.id = p.id_producer WHERE p.chpu = ?", $this->c_this['value'][0]);

	if (is_array($product_data) && $product_data['is_active']==1) {
		/*$type_view = $db->get_one("SELECT mtvp_inner.id_type_view FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " AS mtvp_inner WHERE mtvp_inner.id_product = ? ORDER BY mtvp_inner.id_type_view DESC LIMIT 1", $product_data['id']);
		switch ($type_view) {
			case 2:
				$str_type_view = '<div class="new-label"></div>';
				break;
			case 3:
				$str_type_view = '<div class="hit-label"></div>';
				break;
			default:
				$str_type_view = '';
				break;
		}*/
	
?>

	<div class="cart-product-description">
    <div class="cart-product-description-img">
        <?php if($product_data['main_foto176']) {?>
        <img src="<?=$product_data['main_foto176']?>" alt="">
        <?php } else {?>
        <img src="/images/no_photo.jpg" alt="">
        <?php }?>
    </div>
	<div class="cart-product-description-title"><h1 class = "revert-bootstrap"><?=$product_data['name']?></h1></div>
    <div class="cart-product-description-info">
        <table>
            <tr>
                <td class="name">Производитель:</td>
                <td class="data"><?=$product_data['brand']?></td>
            </tr>
            <tr>
                <td class="name">Цена:</td>
                <td class="data"><?=$product_data['cost_excess']?> руб.*</td>
            </tr>
        </table>

    </div>

    <div class="cart-product-description-namber-buy">
        <form action="">
            количество <input type="text" name="count" value="1" class="number cart-prod-number">
            <input type="submit" onClick="addToBasket(<?=$product_data['id']?>,countProduct()); return false;" value="в корзину" class="btn">
            <a href="" class="number-up"></a>
            <a href="" class="number-down"></a>
            <p class = "data_price_att">
                *Цены актуальны при оформлении покупки в интернет-магазине
            </p>

        </form>
    </div>
</div>
<div class="cart-product-description-more">
    <h2>описание товара</h2>
    <p><?=$product_data['description']?></p>
</div>
<div class="cart-product-description-table">
    <?=$product_data['description_table']?>
</div>     

<?php




        //echo "!!!!!!!!!!!!!!!!!!!";
        //print_r ($product_data);

	} else {

?>

<div class="cart-product-description">
    <div class="cart-product-description-img">
        <?php if($product_data['main_foto176']) {?>
            <img src="<?=$product_data['main_foto176']?>" alt="">
        <?php } else {?>
            <img src="/images/no_photo.jpg" alt="">
        <?php }?>
    </div>
    <div class="cart-product-description-title"><h1 class = "revert-bootstrap"><?=$product_data['name']?></h1></div>
    <div class="cart-product-description-info">
        <table>
            <tr>
                <td class="name">Производитель:</td>
                <td class="data"><?=$product_data['brand']?></td>
            </tr>
            <tr>
                <td class="name">Цена:</td>
                <td class="data"><??> ---.-- *</td>
            </tr>
        </table>

    </div>

    <div class="cart-product-description-namber-buy">
        <form action="">
           <!-- количество <input type="text" name="count" value="1" class="number cart-prod-number">
            <input type="submit" onClick="addToBasket(<?//=$product_data['id']?>,countProduct()); return false;" value="в корзину" class="btn">
            <a href="" class="number-up"></a>
            <a href="" class="number-down"></a>  -->
            <p class = "data_price_att">
                *ждем поступление
            </p>

        </form>
    </div>
</div>
<div class="cart-product-description-more">
    <h2>описание товара</h2>
    <p><?=$product_data['description']?></p>
</div>
<div class="cart-product-description-table">
    <?=$product_data['description_table']?>
</div>


<?php


//header('Location: /404.html', true, 302);
                //echo('<script>
				//window.location = "/404.html"</script>');


        //echo '<ul id="products"><li class="messageBlock"><br/><br/><center><table width="469" border="0" cellpadding="0" cellspacing="0"><tr><td width="102" rowspan="2" valign="top"><img src="/images/errorProductNotFound.png"></td><td width="298" height="35" valign="middle"><p class="inconflict">Товар не найден</p></td></tr><tr><td><p class="inconflict_text">Ждем поступление...</p><br><p><a href="/catalog/">Показать все товары</a></p></td></tr></table></center></li></ul>';


        /*
        echo '<ul id="products"><li class="messageBlock"><br/><br/><center><table width="469" border="0" cellpadding="0" cellspacing="0"><tr><td width="102" rowspan="2" valign="top"><img src="/images/errorProductNotFound.gif"></td><td width="298" height="35" valign="middle"><p class="inconflict">Товар не найден</p></td></tr><tr><td><p class="inconflict_text">Товар, соответствующий введеным данным, не&nbsp;найден. Возможно он удален или не доступен в данный момент.</p><br><p><a href="/catalog/">Показать все товары</a></p></td></tr></table></center></li></ul>';
        */
	}


?>


