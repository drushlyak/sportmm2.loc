<div class="news-articles-encyclopedy clearfix">
    <div class="news">
        <div class="title">
            <p class="ico-news">новости</p>
        </div>
        <?php
        	$news_array = $db->get_all("SELECT ma.*, DATE_FORMAT(ma.i_date,'%d.%m.%Y') as data FROM " . CFG_DBTBL_MOD_ARTICLES . " AS ma WHERE ma.id_category = 1 ORDER BY id DESC LIMIT 1");
        	if (is_array($news_array)) {
        		foreach($news_array as $news) {
        ?>		

        <div class="block-subnews">
            <?=(($news['main_foto']) ? '<img src="' . $news['main_foto'] . '" alt="">' : '')?>
            <p id ="forHeadingH3"><?=$news['name']?></p>
            <div class="data"><?=$news['data']?></div>
            <p>
                <?=$news['anonce_text']?>
            </p>
            <a class="readmore" href="/news/<?=$news['chpu']?>/">Подробнее »</a>
        </div>
        <div class="line-dotted"></div>
        <?php 
               }
        	}
        ?>
        <a href="/news/" class="all-events">Все новости »</a>
    </div>
    <div class="articles">
        <div class="title">
            <p class="ico-articles">статьи</p>
        </div>
        <?php 
        	$art_array = $db->get_all("SELECT ma.*, DATE_FORMAT(ma.i_date,'%d.%m.%Y') as data FROM " . CFG_DBTBL_MOD_ARTICLES . " AS ma WHERE ma.id_category = 2 ORDER BY id DESC LIMIT 1");
        	if (is_array($art_array)) {
        		foreach($art_array as $art) {
        ?>		

        <div class="block-subnews">
            <?=(($art['main_foto']) ? '<img src="' . $art['main_foto'] . '" alt="">' : '')?>
            <p id ="forHeadingH3"><?=$art['name']?></p>
            <div class="data"><?=$art['data']?></div>
            <p>
                <?=$art['anonce_text']?>
            </p>
            <a class="readmore" href="/articles/<?=$art['chpu']?>/">Подробнее »</a>
        </div>
        <div class="line-dotted"></div>
        <?php 
               }
        	}
        ?>
        <a href="/articles/" class="all-events">Все статьи »</a>
    </div>
    <div class="encyclopedy last-element">
        <div class="title">
            <p class="ico-encyclopedy">энциклопедия</p>
        </div>
        <?php 
        	$enc_array = $db->get_all("SELECT ma.*, DATE_FORMAT(ma.i_date,'%d.%m.%Y') as data FROM " . CFG_DBTBL_MOD_ARTICLES . " AS ma WHERE ma.id_category = 3 ORDER BY id DESC LIMIT 1");
        	if (is_array($enc_array)) {
        		foreach($enc_array as $enc) {
        ?>		

        <div class="block-subnews">
            <?=(($enc['main_foto']) ? '<img src="' . $enc['main_foto'] . '" alt="">' : '')?>
            <p id ="forHeadingH3"><?=$enc['name']?></p>
            <div class="data"><?=$enc['data']?></div>
            <p>
                <?=$enc['anonce_text']?>
            </p>
            <a class="readmore" href="/encyclopedia/<?=$enc['chpu']?>/">Подробнее »</a>
        </div>
        <div class="line-dotted"></div>
        <?php 
               }
        	}
        ?>
        <a href="/encyclopedia/" class="all-events">Энциклопедия »</a>
    </div>
</div>