<?php 
$product_cookie = json_decode($_COOKIE['basketPIDs'], true);
$count = (is_array($product_cookie)) ? count($product_cookie) : 0;
?>
<div class="sidebar-wrapper"> 
    <div class="sidebar-cart">
        <div class="title">корзина</div>
        <div class="sidebart-cart-data">
                <span id="num"><?=$count?></span>
                <?=(($count) ? '<a href="/cart/">Оформить заказ</a>' : '<div id="text_cart">Корзина пуста</div>')?>
        </div>
    </div>
</div>