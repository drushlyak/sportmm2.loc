<?php

	$product_cookie = json_decode($_COOKIE['basketPIDs'], true);
	//fb($product_cookie);

	$fio = strip_tags(trim($_REQUEST['fio']));
	$email = strip_tags(trim($_REQUEST['email']));
	$phone = strip_tags(trim($_REQUEST['phone']));
	$city = strip_tags(trim($_REQUEST['city']));
	$comment = strip_tags(trim($_REQUEST['comment']));
	$type_payment_order = (int)($_REQUEST['type_payment']);
	$captcha = strip_tags(trim($_REQUEST['captcha']));
	$submitted = (int) $_REQUEST['submitted'];
	
	$total_cost = sum_order(json_decode($_COOKIE['basketPIDs']));
	
	// Если переданны данные формы регистрации, то проверим введенные данные и зарегистрируем пользователя
	if ($submitted) {

		$error = false;
		$str_error = "";

		// проверки
		if (empty($email) || !validEmail($email)) {
			$error = true;
			$str_error .= _(" - Введите корректный email!<br />");
		}
		if ($captcha !== $_SESSION['captcha_keystring']) {
			$error = true;
			$str_error .= _(" - Введенный Вами проверочный код не совпадает с указанным на картинке!<br />");
		}
		if(empty($city)) {
			$error = true;
			$str_error .= _(" - укажите город доставки!<br />");
		}
		if(empty($phone)) {
			$error = true;
			$str_error .= _(" - укажите номер телефона!<br />");
		}

		if ($error !== true) {
           	while(true) {
            	$number = rand(100000, 999999);
                $order = (int) $db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_ORDER . " WHERE number = ?",$number);
                if(!$order)
                break;
            }

			$sql = sql_placeholder("INSERT INTO " . CFG_DBTBL_MOD_ORDER . " 
						SET id_client = 0
						  , date_order = ?
						  , type_payment = ?
						  , price = ?
						  , number = ? 
						  , delivery_address = ?
						  , fio = ?
						  , email = ?
						  , phone = ?
						  , comment = ?
						  , id_state_order = 1"
					  , date("Y-m-d H:i:s")
					  , $type_payment_order
					  , $total_cost
					  , $number
					  , $city
					  , $fio
					  , $email
					  , $phone
					  , $comment
			);
			$db->query($sql);
			$insert_id = $db->insert_id;

//			setcookie ("payment_id_order", $insert_id, time()+3600, "/");
//			setcookie ("payment_sum_order", $total_cost, time()+3600, "/");

			$product_cookie = json_decode($_COOKIE['basketPIDs'], true);
			foreach($product_cookie as $product_id => $value) {
				if($value[count] > 0) {
					$pr = $db->get_row("SELECT article, name, main_foto80, chpu, cost_excess FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $product_id);
					$db->insert(CFG_DBTBL_MOD_ORDER_PRODUCT, array(
						'id_order' => $insert_id,
						'id_product' => $product_id,
						'count' => $value[count],
						'price' => $pr['cost_excess']
					));
//					$OrderProductsString .= $pr['article'] . ' | "' . $pr['name'] . '" (' . $value['count'] . 'шт) - ' . $pr['cost_excess'] . 'р. <br/>';
//					$OrderProductsStringWithFoto .= '<img src="' . SITE_URL . $pr['main_foto80'] . '" border="0" alt=""> | ' . $pr['article'] . ' | <a href="' . SITE_URL . '/product/' . $pr['chpu'] . '/' . '" target="_blank">' . $pr['name'] . '"</a> (' . $value['count'] . 'шт) - ' . $pr['cost_excess'] . 'р. <br/>';
				}
			}
			?>
			<script type="text/javascript">
				setTimeout(function () {
					window.location = "<?=$__urlPREFIX?>/";
				}, 6000);
			</script>

			<?php

			$prefixOfText = "Спасибо, Ваш заказ принят!";
			$endOfText = "Мы работаем ежедневно без праздников и выходных с 09:00 до 22:00 часов по московскому времени.";
			$answerForClient = "Номер заказа: ".$insert_id." от ".date("Y-m-d H:i:s").".";
			$answerInMail = $prefixOfText."\n".$answerForClient."\n".$endOfText ;

			mail($email, "Заказ на сайте sportmm.ru", $answerInMail,"From:office@sportmm.ru");
			?>

			<h1><?php echo $prefixOfText?></h1>
			<p><?php echo $answerForClient?> </p>
			<p>На Ваш электронный ящик <?php echo $email?>  направлено письмо с подтверждением заказа.</p>
			<p> <?php echo $endOfText ?> </p>



			<!--<h1>Заказ создан!</h1> -->
			<?php
			setcookie("basketPIDs", "", time() - 3600, '/');
		}
	}
	
	if (!$submitted || ($submitted && $error === true)) {	
	/*<script>
removeFromBasket(3556);
</script>*/

 	//<h3 class="title2">Корзина</h3>
 	if(count($product_cookie) != 0):
		//  /cart/to_order/
?>
	<form action="/cart/" id="del_cart" name="order" method="POST" >
<?php
    foreach($product_cookie as $product_id => $value) {
		$product_data = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $product_id);
		$product_data['cost_excess'] = ($product_data['discount'] >= 1) ? round($product_data['cost_excess'] - ($product_data['cost_excess']*$product_data['discount']/100)) : $product_data['cost_excess'];
?>
			
            <div class="cart-product-detail" id="row<?=$product_data['id']?>">
				<table>
					<tr>
						<td width="150">
							<img src="<?=$product_data['main_foto80']?>" alt="">
						</td>
						<td width="180" class="product-data">
							<a href="product/<?=$product_data['chpu']?>/" class="product-name"><?=$product_data['name']?></a>
							<div class="product-article">Артикул: <?=$product_data['article']?></div>
						</td>
						<td width="140">
							<div class="pieces">
								<a href="" class="pieces-down" id="<?=$product_data['id']?>"></a>
								<input class="pieces-data" type="text" value="<?=$value[count]?>" name="" price="<?=$product_data['cost_excess']?>">
								<a href="" class="pieces-up" onClick="addToProduct(<?=$product_data['id']?>, 1, 2); return false;"></a>
							</div>
						</td>
						<td width="140">
							<div class="product-price">
								<span class="price"><?=($product_data['cost_excess']*$value[count])?></span> руб.
							</div>
						</td>
						<td width="20">
							<a href="#" class="remove_product" onClick="del_product(<?=$product_data['id']?>); return false;"></a>
						</td>
					</tr>
				</table>
			</div>
<?php 
    }
//<a href="" onclick="order.submit(); return false;" class="oformit-zakaz">оформить заказ</a>
?>
			<div class="cart-prodprice-summary">
				ИТОГО: <span class="orange" id="total_sum"><?=$total_cost?> руб.</span>
			</div>
			<input type="hidden" name="total-cost" id="total-cost" value="<?=$total_cost?>">
			                         
   
		<div class="registration">
			<div class="title">Заполните эту форму</div>
			<?php 	echo ($str_error) ? "<font color='#FF0000'>" . $str_error . "</font><br>" : '';?>
				<p>ФИО:</p>
				<input type="text" placeholder="Иванов Владимир Петрович" value="<?=$fio?>" name="fio" class="fullname">
				<p>e-mail:</p>
				<input type="text" placeholder="vovan@mail.ru" value="<?=$email?>" name="email" class="email">
				<p>телефон:</p>
				<input type="text" placeholder="+7 978 999 99 99" value="<?=$phone?>" name="phone" class="email">
				<p>Город</p>
				<input type="text" value="<?=$city?>" name="city" id="">
				<p>Комментарий</p>
				<textarea name="comment" rows="10"><?=$comment?></textarea>
				<p>Способ оплаты</p>
				<div class="payment-payment">
					<div class="radioblock">
						<div class="radio" value="1">НАЛИЧНЫМИ КУРЬЕРУ (ПО СЕВАСТОПОЛЮ)</div>
						<div class="radio" value="2">ПО ПРЕДОПЛАТЕ</div>
						<input type="hidden" name="type_payment"/>
					</div>
				</div>
				<p>введите текст с картинки:</p>
				<input type="text" name="captcha" class="email">
				<input type="submit" value="оформить заказ" class="login-registration">
				<img src="/htdocs/keyimg.php?<?=session_name()?>=<?=session_id()?>&rnd=<?=rand()?>" alt="<?=_("картинка с текстом")?>" class="capcha">
				<input name="submitted" type="hidden" value="1" />
		</div>
	</form>

  <?php
  	else:
  ?>
  <br></br>
<h3>В корзине нет товаров</h3>
<a href="/catalog/" class="buy" style="float: left !important">Сделать покупки</a>
  <?php endif; 
	}
	?>