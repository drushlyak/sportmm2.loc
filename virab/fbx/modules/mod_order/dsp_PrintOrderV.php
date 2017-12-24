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

<img src="../images/print/logoV.jpg" />
<div style="margin-left: 70px;">
	<table border="0" cellspacing="0" cellpadding="0" >
	  <tr>
	    <td style="padding-right:40px">ID заказа</td>
	    <td>№ заказа</td>
	  </tr>
	  <tr>
	    <td><span><?=$order_data['id_order']?></span></td>
	    <td><span><?=$order_data['number']?></span></td>
	  </tr>
	</table>
	
	<br />
	Водитель:<br />
	<span><?=$worker_data['name']?></span><br />
	
	<br />
	Получатель:<br />
	<span><?=getRecipientFIO($order_data['id_contact_in_order'])?></span><br />
	
	<br />
	Контактный телефон(ы):<br />
	<span><?=join("<br />", getRecipientPhones($order_data['id_contact_in_order']))?></span><br />
	<h2>Доставка</h2>
	<hr align="left" height="2" color="#CCCCCC" width="731" />
	<table border="0" cellspacing="0" cellpadding="0" >
	  <tr>
	
	    <td width="230" style="padding-right:40px">Дата и время доставки:</td>
	    <td style="padding-right:40px">Город доставки:</td>
	  </tr>
	  <tr>
	
	    <td><span><?=$order_data['delivery_time_spo']?></span></td>
	    <td><span><?=$order_city?></span></td>
	  </tr>
	</table>
	<br>
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td width="230" style="padding-right:40px">Тип доставки:</td>
	    <td>Тип адреса:</td>
	  </tr>
	  <tr>
	    <td><span><?=$order_data['str_type_delivery']?></span><br><br></td>
	    <td><span><?=$typeOfAddressDictionary[(int) $order_data['type_of_address'] - 1]?></span><br><br></td>
	  </tr>
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
	            <td align="center" style="padding-right:10px" width="20"><span><?=($indx + 1)?>.</span></td>
	            <td style="padding-right:10px" width="60"><img src="<?=$product['main_foto50']?>" alt="" border="0" /></td>
	            <td style="padding-right:10px" width="70"><span><?=$product['article']?></span></td>
	            <td width="480"><span><?=$product['name']?></span></td>
	            <td><span><?=$product['count']?> шт.</span></td>
	          </tr>
	          <?php endforeach; ?>
	        </table>
	        <br>
	<table border="0" cellspacing="0" cellpadding="0" >
	  <tr>
	    <td width="500">Тип оплаты</td>
	    <td>Итоговая сумма</td>
	  </tr>
	  <tr>
	    <td><span><?=$order_data['type_payment_dict'][$order_data['type_payment']]['name']?></span></td>
	    <td><?php if ((int) $order_data['type_payment'] === 8): /* наличные при получении */ ?>
			<span><?=$order_data['price']?> руб.</span>
		<?php endif; ?></td>
	  </tr>
	</table>
	<h2>Дополнительно</h2>
	<hr align="left" height="1" color="#CCCCCC" width="731" />
	Комментарий оператора:<br />
	<span><?=!empty($order_data['descr']) ? $order_data['descr'] : "&mdash;"?></span><br /><br />
	Комментарий клиента:<br />
	<span><?=!empty($order_data['comments']) ? $order_data['comments'] : "&mdash;"?></span><br /><br />
	Текст открытки:<br />
	<span><?=!empty($order_data['text_card']) ? $order_data['text_card'] : "&mdash;"?></span>
</div>
</body>
</html>

