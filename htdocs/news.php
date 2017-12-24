<?php 
	$news = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_ARTICLES . " WHERE chpu = ?", $this->c_this['value'][0]);
	if($this->c_this['value'][0]) {
?>
<h1 ><?=$news['name']?></h1>
<p><?=$news['text']?></p>
<?php 
	} else {
		//echo "<h1>Все новости</h1>";
		$news_array = $db->get_all("SELECT ma.*, DATE_FORMAT(ma.i_date,'%d.%m.%Y') as data FROM " . CFG_DBTBL_MOD_ARTICLES . " AS ma WHERE ma.id_category = 1");
		if(is_array($news_array)) {
			foreach($news_array as $news) {
?>
	<p><?=$news['data']?></p><a href="/news/<?=$news['chpu']?>/"><?=$news['name']?></a><br>
	<p><?=$news['anonce_text']?></p><br>
<?php
			}
		}
	}
?>