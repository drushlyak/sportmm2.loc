<meta charset="utf-8" />
<meta name="viewport" content="width="980" user-scalable="yes"/>
<meta name="robots" content="all" />
<? 
	/*скрипт вставляет различные метатеги в head страница взависимости от раздела сайта и наименования товара, категории*/
	
	//задаем значение title, description, keywords по умолчанию
	$currentAltTitle = "Интернет-магазин спортивного питания в Крыму. Купить спорт питание в Севастополе, Симферополе, Ялте"; 
	$currentDescription = "Большой выбор спортивного питания в интернет-магазине СпортМегамаркет. Протеины, BCCA, аминокислоты, гейнеры, витамины для спортсменов. Доступные цены.";
	$currentKeywords = "33333default";
	
	$urlHost =  strval($_SERVER['REQUEST_URI']);
	$positionOfMaskProduct = strpos($urlHost, '/product/'); 
	$positionOfMaskCatalog = strpos($urlHost, '/catalog/');
	if ($positionOfMaskCatalog === 0) $mask2 = ":\d+"; //часть маски для каталога
	$mask = "(\/(\w+)\W(\w+".$mask2.")\/)"; //маска общая
	preg_match_all ($mask,$urlHost,$chpuArrayFromUri); //создаем массив с вхождением 2й части URI
	$chpuFromUri = $chpuArrayFromUri [2][0]; // выбираем из массива вхождение 2й части URI		
	
	if ($positionOfMaskProduct === 0 && isset ($chpuFromUri)) { //если URI начинается с /product/, т.е. это товар
		$sqlQueryOfTagsValues = "SELECT alternative_title,description_for_product,keywords_for_product FROM mod_product WHERE chpu LIKE '".$chpuFromUri."'" ;
		$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
		$currentAltTitle = $resultOfTagsValues [0]['alternative_title'];
		$currentDescription = $resultOfTagsValues [0]['description_for_product'];
		$currentKeywords = $resultOfTagsValues [0]['keywords_for_product'];
		}
	
	else {		if ($positionOfMaskCatalog === 0 && isset ($chpuFromUri)) { //если URI начинается с /catalog/, т.е. это каталог
				$chpuFromUri = substr ( $chpuFromUri, 6 );
				$chpuFromUri = (int)$chpuFromUri;
					if ($chpuFromUri < 1000) {
						
					}
					else {
						$chpuFromUri = $chpuFromUri - 983;
						$sqlQueryOfTagsValues = "SELECT category_title,category_description,category_keywords FROM config WHERE id LIKE '".$chpuFromUri."'" ;
						$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
						$currentAltTitle = $resultOfTagsValues [0]['category_title'];
						$currentDescription = $resultOfTagsValues [0]['category_description'];
						$currentKeywords = $resultOfTagsValues [0]['category_keywords'];}
					}
		}
	$currentAltTitle = "<title>".$currentAltTitle."</title>\n" ;
	$currentDescription = "<meta name=\"description\" content=\"".$currentDescription."\">\n";
	$currentKeywords = "<meta name=\"keywords\" content=\"".$currentKeywords."\">\n";	
		
	echo 	 $currentAltTitle.$currentDescription.$currentKeywords;
?>
<base href="http://<?=$this->c_this['domen']?>">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/jquery-ui.css">
<link rel="stylesheet" href="/css/combobox.css">
<link rel="stylesheet" href="/css/jscrollpane.css">
<link rel="stylesheet" href="/css/slider-top.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet"/>
