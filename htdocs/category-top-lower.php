<?php 
	global $site_config;
?>


	<ul>

<?=(($site_config['cat_male'] == 1) ? '<li><a href="/catalog/group:1009-sport-pitanie-dlya-muzhchin/"><img src="images/ico/mens.png" alt="">Мужчинам</a></li>' : '');?>
<?=(($site_config['cat_female'] == 1) ? '<li><a href="/catalog/group:1010-sportivnoe-pitanie-dlya-zhenshchin/"><img src="images/ico/woomans.png" alt="">Женщинам</a></li>' : '');?>
<?=(($site_config['cat_mass'] == 1) ? '<li><a href="/catalog/group:1011-sportivnoe-pitanie-dlya-nabora-myshechnoj-massy/"><img src="images/ico/weight.png" alt="">Масса</a></li>' : '');?>
<?=(($site_config['cat_slimming'] == 1) ? '<li><a href="/catalog/group:37-sportivnoe-pitanie-dlya-pohudeniya-i-sushki/"><img src="images/ico/weight-loss.png" alt="">Похудение</a></li>' : '');?>
<?=(($site_config['cat_health'] == 1) ? '<li><a href="/catalog/group:1013-sportivnoe-pitanie-dlya-zdorovya/"><img src="images/ico/health.png" alt="">Здоровье</a></li>' : '');?>
<?=(($site_config['cat_energy'] == 1) ? '<li><a href="/catalog/group:1014-sportivnye-ehnergetiki/"><img src="images/ico/energy.png" alt="">Энергия</a></li>' : '');?>
<?=(($site_config['cat_accessory'] == 1) ? '<li><a href="/catalog/group:40-sportivnye-shejkery-butylki-dlya-vody/"><img src="images/ico/accessories.png" alt="">Шейкеры</a></li>' : '');?>

	</ul>

