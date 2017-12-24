<?php
	require_once ("../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");

	session_start();

?>
<script type="text/javascript">
//pop - up
function incr_pop(el) {
	var el = el;
	var count = el.closest('.counter').children('.count');
	var i = parseInt(count.attr('value'));
	var id = $('.content').attr('id');
	var num_stock = $('.content').attr('num_stock');
		if(num_stock <= i) {
			return true;
		} else {
			i++;
			addToProduct(id, 1);
			count.attr('value',i);
			return true;
		}
	}
	
function decr_pop(el) {			
	var el = el;
	var count = el.closest('.counter').children('.count');		
	var i = count.attr('value');
	var id = $('.content').attr('id');
	i--;
	if(i < 1) {
		return true;
	} else {
		addToProduct(id, -1);
	}
	count.attr('value',i);
	return true;
	}		
// end pop-up
$(".counter .inc_pop").click(function(){incr_pop($(this))});
$(".counter .dec_pop").click(function(){decr_pop($(this))});
var count = countProduct();
if(count) {
	$(".counter .count").attr('value',countProduct());
}

</script>
    <div class="pop-up" id="yesno">
        <img src="images/cart.png" border="0" />
        <br />
        Товар добавлен в корзину<br /><br />
        Для просмотра корзины воспользуйтесь этой <a href="/cart/">ссылкой</a><br /><br />
        <div class="counter" style="width:110px; margin:0 auto 20px;">
        
            <input id="id_product_pop" type="hidden" value="0" name="id_product_pop">
            <a class="dec_pop"></a>
            <input class="count" value="1" type="text" />
            <span class="type" style="padding: 0px;">шт.</span>
            <a class="inc_pop"></a>
                            
            <span class="clear"></span>
        </div>
   
        <a href="/cart/" class="pop-link">Оформить заказ</a>
        <div class="close pop-link" onClick="getCookieNum(); $('#popup').addClass('hidden'); $('#opaco').addClass('hidden').removeAttr('style').unbind('click'); return false;">Продолжить покупки</div>
    </div>
