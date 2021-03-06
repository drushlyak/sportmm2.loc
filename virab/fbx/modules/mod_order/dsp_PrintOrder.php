<?php
	$order_city = $db->get_one("SELECT dc.name FROM " . CFG_DBTBL_DICT_CITY . " AS dc, " . CFG_DBTBL_MOD_PRODUCT . " AS mp WHERE mp.id = ? AND mp.id_city = dc.id LIMIT 1", $order_data['products'][0]['id']);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Заказ № <?=$order_data['number']?></title>
	<style type="text/css">
		body {
			color: #666666;
			font-size: 13px;
			font-family: Georgia;
		}

		.productLine {
			padding-left: 20px;
		}

		h2 {
			margin-bottom: 5px;
			color: #000;
			font-size: 21px;
			font-family: Georgia;
			font-weight: 100;
		}

		span {
			color: #000;
			font-size: 16px;
			font-weight: lighter;
		}

		.ftab td {
			border-bottom: 1px solid #ccc;
			line-height: 30px;
		}
	</style>
	<style type="text/css" media="print">
		.not_for_print {
			display: none;
		}
	</style>
</head>

<body>

<input type="button" class="not_for_print" value="Распечатать эту страницу" onClick="window.print();">
<br />

<img src="../images/print/logoOrder.jpg" />
<br><br>
<div style="margin-left: 80px;">
	<table border="0" cellspacing="0" cellpadding="0" width="731">
	  <tr>
	    <td width="100">ID заказа:</td>
	    <td width="100">№ заказа:</td>
	    <td width="100">Дата заказа:</td>
	    <td>Заказ создал:</td>
	    <td>Источник заказа:</td>
	  </tr>
	  <tr>
	    <td><span><?=$order_data['id_order']?></span></td>
	    <td><span><?=$order_data['number']?></span></td>
	    <td><span><?=$order_data['date_order']?></span></td>
	    <td><span><?=$username?></span></td>
	    <td><span><?=$source_order?></span></td>
	  </tr>
	</table>
	
	<h2>Клиент</h2>
	<hr align="left" height="2" color="#CCCCCC" width="731" />
	<table border="0" cellspacing="0" cellpadding="0" >
	  <tr>
	
	    <td width="230" style="padding-right:40px">ФИО:</td>
	    <td style="padding-right:40px">Дата рождения:</td>
	  </tr>
	  <tr>
	
	    <td><span><?=$order_data['client']?></span></td>
	    <td><span><?=$order_data['birth_date']?></span></td>
	  </tr>
	</table>
	<br>
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td width="230" style="padding-right:40px">Телефон(ы):</td>
	    <td>e-mail:</td>
	  </tr>
	  <tr>
	    <td><span><?=$order_data['phone']?></span><br><br></td>
	    <td><span><?=$order_data['email']?></span><br><br></td>
	  </tr>
	  <tr>
	    <td>Адрес:</td>
	    <td></td>
	  </tr>
	  <tr>
	    <td><span><?=$adressP?></span></td>
	    <td></td>
	  </tr>
	</table>
	
	<h2>Доставка</h2>
	<hr align="left" height="2" color="#CCCCCC" width="731" />
	<table border="0" cellspacing="0" cellpadding="0" >
	  <tr>
	    <td width="230" style="padding-right:40px">Получатель:</td>  
	  </tr>
	  <tr>
	    <td><span><?=$order_data['contact_fio']?></span></td>
	    
	  </tr>
	  <tr>
	    <td width="230" style="padding-right:40px"><br>Контактный телефон(ы):</td>  
	  </tr>
	  <tr>
	    <td><span><?=join("<br />", getRecipientPhones($order_data['id_contact_in_order']))?></span></td>
	    
	  </tr>
	</table>
	<br>
	<table border="0" cellspacing="0" cellpadding="0" width="400">
	  <tr>
	    <td style="padding-right:40px">Дата:</td>
	    <td>Время доставки:</td>
	    <td>Город доставки:</td>
	  </tr>
	  <tr>
	    <td><span><?=$date_order?></span><br><br></td>
	    <td><span><?=$time_order?></span><br><br></td>
	    <td><span><?=$order_city?></span><br><br></td>
	  </tr>
	</table width="400">
	<table >
	  <tr>
	    <td width="200">Тип доставки:</td>
	    <td>Тип адреса:</td>
	  </tr>
	  <tr>
	    <td><span><?=$order_data['str_type_delivery']?></span></td>
	    <td><span><?=$typeOfAddressDictionary[(int) $order_data['type_of_address'] - 1]?></span></td>
	  </tr>
	</table>
	<br>
	<table width="400">
		<tr>
			<td>Фото доставки:</td>
			<td>Адрес доставки:</td>
		</tr>
		<tr>
			<td><span><?=(int) $order_data['take_photo'] ? "делать" : "не делать"?></span></td>
			<td><span><?=$order_data['addressDelivery']?></span></td>
		</tr>
	</table>
	
	<h2>Заказ</h2>
	<hr align="left" height="2" color="#CCCCCC" width="731" />
			<table border="0" cellspacing="0" cellpadding="0" class="ftab" width="731">
	
			<?php foreach ($order_data['products'] as $indx => $product): ?>
	          <tr>
	           <!--  <td align="center" style="padding-right:10px" width="20"><span><?=($indx + 1)?>.</span></td> -->
	            <td style="padding-right:10px" width="60"><img src="<?=$product['main_foto50']?>" alt="" border="0" /></td>
	            <td style="padding-right:10px" width="70"><span><?=$product['article']?></span></td>
	            <td width="380"><span><?=$product['name']?></span></td>
	            <td><span><?=$product['count']?> шт.</span></td>
	            <td><span><?=$product['price']?> р.</span></td>
	          </tr>
	          <?php endforeach; ?>
	        </table>
	        <br>
	<table border="0" cellspacing="0" cellpadding="0" width="731">
	  <tr>
	    <td width="260" >Тип оплаты:</td>
	    <td>Использовано бонусов:</td>
	    <td>Стоимость доставки:</td>
	    <td>Промокод:</td>
	    <td>Скидка:</td>
	  </tr>
	  <tr class="ftab">
	    <td style="padding-right:10px; padding-bottom:5px;"><span><?=$order_data['type_payment_dict'][$order_data['type_payment']]['name']?></span></td>
	    <td><span><?=$order_data['count_bonuses']?></span></td>
	    <td><span><?=$order_data['price_delivery']?> р.</span></td>
	    <td><span><?=$promocode?></span></td>
	    <td><span><?=$order_data['count_discount']?>%</span></td>
	  </tr>
	  <tr>
	  	<td colspan="5"><br><div align="right">Итоговая сумма:</div></td>
	  </tr>
	  <tr>
	  	<td colspan="5"><div align="right"><?php// if ((int) $order_data['type_payment'] === 2): /* наличные нашему курьеру */ ?>
			<span><?=$order_data['price']?> р.</span>
	<?php// endif; ?></div></td>
	  </tr>
	</table>
</div>
<br><br>

</body>
</html>

