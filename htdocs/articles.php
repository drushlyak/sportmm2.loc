<?php 
	$articles = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_ARTICLES . " WHERE chpu = ?", $this->c_this['value'][0]);
	if($this->c_this['value'][0]) {
?>
<h1 ><?=$articles['name']?></h1>
<p><?=$articles['text']?></p>
<?php 
	} else {
		//echo "<h1>Все новости</h1>";
		$articles_array = $db->get_all("SELECT ma.*, DATE_FORMAT(ma.i_date,'%d.%m.%Y') as data FROM " . CFG_DBTBL_MOD_ARTICLES . " AS ma WHERE ma.id_category = 2");
		if(is_array($articles_array)) {
			foreach($articles_array as $articles) {
?>
	<p><?=$articles['data']?></p><a href="/articles/<?=$articles['chpu']?>/"><?=$articles['name']?></a><br>
	<p><?=$articles['anonce_text']?></p><br>
<?php
			}
		}
	}
?>