<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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

		.productCont {
			padding-left: 20px;
		}

		.contLine {
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
<img src="../images/print/logoF.jpg"/>
<div style="margin-left: 70px;">
	<table border="0" cellspacing="0" cellpadding="0" width="550">
		<tr>
			<td>ID заказа:</td>
			<td>№ заказа:</td>
			<td>Дата заказа:</td>
			<td>Дата и время доставки:</td>
		</tr>
		<tr>
			<td><span><?=$order_data['id_order']?></span></td>
			<td><span><?=$order_data['number']?></span></td>
			<td><span><?=$order_data['date_order']?></span></td>
			<td><span><?=$order_data['delivery_pdate']?> <?=$order_data['delivery_time']?></span></td>
		</tr>
		<tr>
			<td>Флорист:</td>
			<td colspan="3"><span><?=$worker_data['name']?></span></td>
		</tr>
	</table>
	<br/>
	
	<h2>Заказ</h2>
	
	<hr align="left" height="2" color="#CCCCCC" width="731"/>
	
	<?php foreach ($order_data['products'] as $indx => $product): ?>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="padding-right:10px"><span><?=($indx + 1)?>.</span></td>
			<td style="padding-right:10px"><img src="<?=$product['main_foto50']?>" alt="" border="0"/></td>
			<td style="padding-right:10px"><span><?=$product['article']?></span></td>
			<td width="500"><span><?=$product['name']?></span></td>
			<td><span><?=$product['count']?> шт.</span></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
			<td colspan="2">
				<table border="0" cellspacing="0" cellpadding="0" class="ftab">
					<tr>
						<th width="20" scope="col">Состав:</th>
						<th width="78" scope="col">&nbsp;</th>
						<th width="380" scope="col">&nbsp;</th>
						<th width="50" scope="col">&nbsp;</th>
					</tr>
					<?php foreach ($product['cont'] as $indp => $content): ?>
					<tr>
						<td align="center"><?=($indp + 1)?></td>
						<td align="center"><?=$content['article']?></td>
						<td><?=$content['name']?></td>
						<td><?=$content['count']?> шт.</td>
					</tr>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
	</table>
	<img src="../images/print/line.jpg"/>
		<?php endforeach; ?>
	
	<h2>Дополнительно</h2>
	<hr align="left" height="1" color="#CCCCCC" width="731"/>
	Комментарий оператора:<br/>
	<span><?=!empty($order_data['descr']) ? $order_data['descr'] : "&mdash;"?></span><br/><br/>
	Комментарий клиента:<br/>
	<span><?=!empty($order_data['comments']) ? $order_data['comments'] : "&mdash;"?></span><br/><br/>
	Текст открытки:<br/>
	<span><?=!empty($order_data['text_card']) ? $order_data['text_card'] : "&mdash;"?></span>
</div>
</body>
</html>
