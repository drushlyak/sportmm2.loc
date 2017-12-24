<?php 
	global $site_config;
	//обработка адресной строки
	$pager_url = '';
	if(isset($this->c_this['value']) && is_array($this->c_this['value'])){

        // print_r ($this->c_this['value']);

          foreach($this->c_this['value'] as $elem){
          	   if(is_integer(strpos($elem, 'min:'))){
                    $min = intval(substr($elem, 4));
                    $pager_url .= 'min:' . $min . '/';
               }
          	   if(is_integer(strpos($elem, 'max:'))){
                    $max = intval(substr($elem, 4));
                    $pager_url .= 'max:' . $max . '/';
               }
          	   if(is_integer(strpos($elem, 'group:'))){
                    $group = intval(substr($elem, 6));
                    $pager_url .= 'group:' . $group . '/';
               }
               if(is_integer(strpos($elem, 'search:'))){
                    $search = strip_tags(trim(substr($elem, 7)));
                    $pager_url .= 'search:' . $search . '/';
               }
          	   if(is_integer(strpos($elem, 'page:'))){
                    $page = intval(substr($elem, 5));
               }
               if(is_integer(strpos($elem, 'brand:'))){
                    $brand = intval(substr($elem, 6));
                    $pager_url .= 'brand:' . $brand . '/';
               }
          	   if(is_integer(strpos($elem, 'show:'))){
                    $show = (substr($elem, 5) == 'all') ? substr($elem, 5) : '';
               }
          }
     }


     function del_filter($page_url,$str) {
     	$url = str_replace($str, '', $page_url);
     	return $url;
     }
     //основная и дочерняя категории для термометра
	if(isset($group) && $group && $group < 1000){
			$category = $db->get_row("
				SELECT dc.name AS category
					 , dmc.id AS id_main_category
					 , dmc.name AS main_category
					FROM " . CFG_DBTBL_DICT_CATEGORY . " AS dc
					   , " . CFG_DBTBL_DICT_MAIN_CATEGORY . " AS dmc
					WHERE dc.id = ?
							AND dc.id_main_category = dmc.id
					LIMIT 1
			", $group);
	} elseif(isset($group) && $group > 1000) {
		$category = $db->get_row("
				SELECT dmc.name AS category
					FROM " . CFG_DBTBL_DICT_MAIN_CATEGORY . " AS dmc
					WHERE dmc.id = ?
					LIMIT 1
			", ($group-1000));
	}
	
	// кол-во продуктов на странице
	if(!isset($page) || !$page) { $page = 1;}
    $limit = 9; //$site_config['limit_catalog']; //кол-во продуктов на странице
    $limit_start = ($page * $limit) - $limit; //c какой записи начать
	$limit_finish = $limit_start + 9 ;
	if ($show == "all") {
		$sql_limit = "";
	}
	else {
	$sql_limit = 'LIMIT '.$limit_start . ', ' . $limit;}

//echo "Страница".$page;

	$sql_where = '';
	$sql_join = '';

	//минимальная стоимость
	$sql_where .= (isset($min) && $min) ? ' AND mp.cost_excess >= ' . $min : '';
	//максимальная стоимость
	$sql_where .= (isset($max) && $max) ? ' AND mp.cost_excess <= ' . $max : '';
	//производитель
	$sql_where .= (isset($brand) && $brand) ? ' AND mp.is_active = 1 AND mp.id_producer = ' . $brand : '';	
	//категории продуктов
	$sql_where .= (isset($group) && $group && $group < 1000)  ? ' AND dc.id = ' . $group : '';
	$sql_join .= (isset($group) && $group && $group < 1000) ? " LEFT JOIN " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcap_in ON mcap_in.id_product = mp.id LEFT JOIN " . CFG_DBTBL_DICT_CATEGORY . " AS dc ON dc.id = mcap_in.id_category " : '';
	//главные категории
	$sql_where .= (isset($group) && $group && $group > 1000)  ? ' AND dmc.id = ' . ($group - 1000) : '';
	$sql_join .= (isset($group) && $group && $group > 1000) ? " LEFT JOIN " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcap_in ON mcap_in.id_product = mp.id LEFT JOIN " . CFG_DBTBL_DICT_CATEGORY . " AS dc ON dc.id = mcap_in.id_category LEFT JOIN " . CFG_DBTBL_DICT_MAIN_CATEGORY . " AS dmc ON dmc.id = dc.id_main_category " : '';

	//строка поиска

//WHERE is_active = 1" . $sql_where . "

$sql_join .= (isset($search) && $search) ? " LEFT JOIN " . CFG_DBTBL_MOD_PRODUCT_TAGS . " AS mpt ON mp.id = mpt.id_product LEFT JOIN " . CFG_DBTBL_MOD_TAGS . " AS mt ON mt.id = mpt.id_tag " : '';

$sql_where .= (isset($search) && $search) ? " AND (mp.name LIKE '%" . $search . "%' OR mt.name LIKE '%" . $search . "%' OR mp.article LIKE '%" . $search . "%' OR mp.description LIKE '%[>]%" . $search . "%[<]%')" : "";

	$sql = sql_placeholder("SELECT DISTINCT mp.* 
									FROM " . CFG_DBTBL_MOD_PRODUCT . " AS mp
									" . $sql_join . " 
									WHERE (is_active = 1 OR is_active <> 1) " . $sql_where . "
									ORDER BY is_active DESC, mp.id ASC 
									".$sql_limit." ");



	$sqlCount = sql_placeholder("SELECT COUNT(DISTINCT mp.name), mp.*  
									FROM " . CFG_DBTBL_MOD_PRODUCT . " AS mp
									" . $sql_join . " 
									WHERE (is_active = 1 OR is_active <> 1) " . $sql_where . "
									 ");


	//fb($sql);
	$product_array = $db->get_all($sql);
	$countArray = $db->get_all($sqlCount);
	// if (isset($countArray)) echo "countArray is set";
//echo $show;
//echo $countArray [0] ['COUNT(*)'] ;
//echo " REQUEST".$sql;
//echo " REQUEST".$sqlCount;


	$filter_str = "";
	if (isset($min) && isset($max) && $min && $max) {
		$filter_str .= '<span>цена:</span> <span class="category">от ' . $min . ' руб до ' . $max . ' руб</span><a href="/catalog/' . del_filter($pager_url,array('min:' . $min . '/', 'max:' . $max . '/')) . '" class="delete-category">X</a>';
	} elseif(isset($min) && $min) {
		$filter_str .= '<span>цена:</span> <span class="category">от ' . $min . ' руб</span><a href="/catalog/' . del_filter($pager_url,array('min:' . $min . '/')) . '" class="delete-category">X</a>';
	} elseif(isset($max) && $max) {
		$filter_str .= '<span>цена:</span> <span class="category">до ' . $max . ' руб</span><a href="/catalog/' . del_filter($pager_url,array('max:' . $max . '/')) . '" class="delete-category">X</a>';
	}
	if (isset($group) && $group) {
		$filter_str .= ($min || $max) ? ', ' : '';
		$filter_str .= '<span>категория:</span> <span class="category"><b>' . $category['category'] . '</b></span><a href="/catalog/' . del_filter($pager_url,array('group:' . $group . '/')) . '" class="delete-category">X</a>';
	}
	if (isset($brand) && $brand) {
		$br_name = $db->get_one("SELECT name FROM " . CFG_DBTBL_MOD_BRANDS . " WHERE is_active = 1 AND id = ?", $brand);
		$filter_str .= ($min || $max || $group) ? ', ' : '';
		$filter_str .= '<span>производитель:</span> <span class="category">' . $br_name . '</span><a href="/catalog/' . del_filter($pager_url,array('brand:' . $brand . '/')) . '" class="delete-category">X</a>';
	}
	
	
	if ((isset($min) && $min) || (isset($max) && $max) || (isset($group) && $group) || (isset($brand) && $brand)) {
		if (!stripos($filter_str, "категория:")) {

			?>
			<div class="cataloge-info-block">
				<?= $filter_str ?>
			</div>
			<?php
		}
	}
?>


<?php

/*вывод заголовка H1 в начале и поля с текстом в конце категории*/
$urlHost =  strval($_SERVER['REQUEST_URI']);
$positionOfMaskCatalog = strpos($urlHost, '/catalog/group:');
$positionOfMaskBrand = strpos($urlHost, '/catalog/brand:');

if ($positionOfMaskCatalog === 0 || $positionOfMaskBrand === 0) {
	$mask = "(\/(\w+)\W((\w+:\d+)([A-Za-z0-9-]*))\/(\w+:\d+|\w+:\w+)?)";
};

preg_match_all ($mask,$urlHost,$chpuArrayFromUri); //создаем массив с вхождением 2й части URI
$chpuFromUri = $chpuArrayFromUri [3][0];// выбираем из массива вхождение 2й части URI


//$currentSubcategoryTitleH1 = "qqqqqqqqqqqqqq";

if ($positionOfMaskCatalog === 0 && isset ($chpuFromUri)) { //если URI начинается с /catalog/, т.е. это каталог
	$chpuFromUri = substr ( $chpuFromUri, 6 );
	$chpuFromUri = (int)$chpuFromUri;
	if ($chpuFromUri < 1000) {
		$sqlQueryOfTagsValues = "SELECT subcategory_cpu_url,subcategory_text,subcategory_titleh1,subcategory_image_1 FROM dict_category WHERE id LIKE '".$chpuFromUri."'" ;
		$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
		$currentSubcategoryText = $resultOfTagsValues [0]['subcategory_text'];
		$currentSubcategoryTitleH1 = $resultOfTagsValues [0]['subcategory_titleh1'];
		$currentSubcategoryImageUrl = $resultOfTagsValues [0]['subcategory_image_1'];
	}
	else {
		$chpuFromUri = $chpuFromUri - 983;
		$sqlQueryOfTagsValues = "SELECT category_text,category_titleh1,category_image_1 FROM config WHERE id LIKE '".$chpuFromUri."'" ;
		$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
		$currentSubcategoryText = $resultOfTagsValues [0]['category_text'];
		$currentSubcategoryTitleH1 = $resultOfTagsValues [0]['category_titleh1'];
		$currentSubcategoryImageUrl = $resultOfTagsValues [0]['category_image_1'];
	}
}

elseif ($positionOfMaskBrand === 0 && isset ($chpuFromUri)) {

	$chpuFromUri = substr ( $chpuFromUri, 6 );
	$chpuFromUri = (int)$chpuFromUri;
	$sqlQueryOfTagsValues = "SELECT brand_text FROM mod_brands WHERE id LIKE '".$chpuFromUri."'" ;
	$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
	$currentSubcategoryText = $resultOfTagsValues [0]['brand_text'];
	$currentSubcategoryTitleH1 = "";
};


echo "<img src=\"/images/category-image/".$currentSubcategoryImageUrl."\" \"width=\"570\"  alt=\"\" >";



echo "<div>	
		<h1>".$currentSubcategoryTitleH1.
		"</h1> 
		</div>";

?>


<div class="title">
	<p class="ico-star">Каталог товаров</p>
</div>
<?php 
    $products_result_temp = $db->get_all_($sql);


    // уберем дубликаты 
    $result_array_ids = array();
    if (is_array($products_result_temp)) {
        foreach($products_result_temp as $product) {
            if (!in_array($product['id'], $result_array_ids)) {
                array_push($result_array_ids, $product['id']);
                $products_result[] = $product;
            }
        }
    }
    
	//кол-во страниц
	if(isset($show) && $show) {
		$num_page = 1;
	} else {
		//$num_element = count($products_result);//
		$num_element = $countArray [0] ['COUNT(DISTINCT mp.name)'];
		$n_p = $num_element / $limit;
		$num_page = ($n_p == 1) ? 1 : ((ctype_digit(trim($n_p))) ? $n_p : intval($n_p + 1)); //количество страниц
	}


	$counter = 0;
	if (is_array($products_result)) {
		foreach($products_result as $product) {
		    $counter++;
            if (!isset($show) && $show && ($counter <= $limit_start || $counter > $limit_start + $limit))
                continue;
			?>
	<div class="category-product">
		<a href="/product/<?=$product['chpu']?>/">
			<img src="<?=$product['main_foto176']?>" alt="">
			<p><?=mb_substr($product['name'], 0, 80)?></p>
		</a>
		<?php if ($product['is_active']==1) : ?>
		<span class="orange" style="display:none"> <?=$product['cost_excess']?> руб.</span>
		<input type="submit"  onclick="addToBasket(<?php echo $product['id'];?>,countProduct()); return false;" value="<?=$product['cost_excess']?> руб." class="orange">
				<?php
		else : ?>
			<span class="orange" style="display:none">Ждем поступление</span>
			<input type="text"  value="Ждем поступление" class="orange" disabled>
				<?php endif ?>
				</div>

<?php

		}



	} else {
		
		/*перенаправление страницы на 404 в случае если по нет товара соответствующего запрашиваемому УРЛ */

		//header('Location: /404.html', true, 302);
		//echo('<script>
			//window.location.replace ("/404.html")</script>');
		
		echo "<p>Нет товаров</p>";
	}


//echo "!!!!!!!!!!!!!!!!!!!";
//print_r ($products_result);

	if($num_page > 1) {
		
?>


	
	<div class="cataloge-pager">
        <ul>
        	<?php

			/*прибаление суффикса к пагинации*/
			$urlHost =  strval($_SERVER['REQUEST_URI']);
			$positionOfMaskCatalog = strpos($urlHost, '/catalog/group:');
			$positionOfMaskBrand = strpos($urlHost, '/catalog/brand:');

			if ($positionOfMaskCatalog === 0 || $positionOfMaskBrand === 0) {
				$mask = "(\/(\w+)\W((\w+:\d+)([A-Za-z0-9-]*))\/(\w+:\d+|\w+:\w+)?)";
			};

			preg_match_all ($mask,$urlHost,$chpuArrayFromUri); //создаем массив с вхождением 2й части URI
			$chpuFromUri = $chpuArrayFromUri [3][0];// выбираем из массива вхождение 2й части URI

			if ($positionOfMaskCatalog === 0 && isset ($chpuFromUri)) { //если URI начинается с /catalog/, т.е. это каталог
				$chpuFromUri = substr ( $chpuFromUri, 6 );
				$chpuFromUri = (int)$chpuFromUri;
				if ($chpuFromUri < 1000) {
					$sqlQueryOfTagsValues = "SELECT subcategory_cpu_url FROM dict_category WHERE id LIKE '".$chpuFromUri."'" ;
					$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
					$currentSubcategoryCpuUrl = $resultOfTagsValues [0]['subcategory_cpu_url'];

				}
				else {
					$chpuFromUri = $chpuFromUri - 983;
					$sqlQueryOfTagsValues = "SELECT category_cpu_url FROM config WHERE id LIKE '".$chpuFromUri."'" ;
					$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
					$currentSubcategoryCpuUrl = $resultOfTagsValues [0]['category_cpu_url'];
							}
			}

			elseif ($positionOfMaskBrand === 0 && isset ($chpuFromUri)) {

				$chpuFromUri = substr ( $chpuFromUri, 6 );
				$chpuFromUri = (int)$chpuFromUri;
				$sqlQueryOfTagsValues = "SELECT brand_cpu_url FROM mod_brands WHERE id LIKE '".$chpuFromUri."'" ;
				$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
				$currentSubcategoryCpuUrl = $resultOfTagsValues [0]['brand_cpu_url'];

			}

			$pager_url = substr($pager_url,0,-1);

			if (isset($search) && $search) { //убирает дефис из УРЛ, тк неверно работает пагинация для поиска по названию
				$pager_url = $pager_url.$currentSubcategoryCpuUrl."/";
			}
			else $pager_url = $pager_url."-".$currentSubcategoryCpuUrl."/";



 				for ($i=1; $i <= $num_page; $i++) {
					 echo '<li><a href="/catalog/' . $pager_url . 'page:' . $i . '/"' ;
					 if ($i==$page) {echo 'id="currentPage" >';}
						else {echo ' >';};
					 echo $i . '</a></li>';
				 }
	        	//echo pager_catalog($num_page,$page,$pager_url);  			
        	?>
        </ul>
    </div>
    <div class="pager-list">
        <a href="/catalog/<?=$pager_url?>page:1/" class="first">« Первая страница</a>
        <a href="/catalog/<?=$pager_url?>show:all/" class="show-all">Показать все товары</a>
        <a href="/catalog/<?=$pager_url?>page:<?=$num_page?>/" class="last">последняя страница »</a>
    </div>
	

	
<?php 
	}
	
	
	
?>
