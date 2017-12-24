<?php 
	$encyclopedia = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_ARTICLES . " WHERE chpu = ?", $this->c_this['value'][0]);
	if($this->c_this['value'][0]) {
?>
<h1 ><?=$encyclopedia['name']?></h1>
<p><?=$encyclopedia['text']?></p>
<?php 
	} else {
		//echo "<h1>Все новости</h1>";
		$encyclopedia_array = $db->get_all("SELECT ma.*, DATE_FORMAT(ma.i_date,'%d.%m.%Y') as data FROM " . CFG_DBTBL_MOD_ARTICLES . " AS ma WHERE ma.id_category = 3");
		if(is_array($encyclopedia_array)) {
			foreach($encyclopedia_array as $encyclopedia) {
?>
	<p><?=$encyclopedia['data']?></p><a href="/encyclopedia/<?=$encyclopedia['chpu']?>/"><?=$encyclopedia['name']?></a><br>
	<p><?=$encyclopedia['anonce_text']?></p><br>
<?php
			}
		}
	}
?>