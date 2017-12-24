<meta charset="utf-8" />
<meta name="viewport" content="width="980" user-scalable="yes"/>
<meta name="robots" content="all" />

<?
	/*скрипт вставляет различные метатеги в head страница взависимости от раздела сайта и наименования товара, категории*/
	
	//задаем значение title, description, keywords по умолчанию
	//$currentAltTitle = "Интернет-магазин спортивного питания в Крыму. Купить спорт питание в Севастополе, Симферополе, Ялте";
	//$currentDescription = "Большой выбор спортивного питания в интернет-магазине СпортМегамаркет. Протеины, BCCA, аминокислоты, гейнеры, витамины для спортсменов. Доступные цены.";
	//$currentKeywords = "33333default";


$arrayOfMetaTags = array (array("/","Спортивное питание в Севастополе - купить в интернет-магазине с доставкой по Крыму","Большой выбор спортивного питания в интернет-магазине СпортМегамаркет. Протеины, BCCA, аминокислоты, гейнеры, витамины для спортсменов. Доступные цены.","спортивное питание, магазин спортивного питания, спортивное питание купить, спортивное питание в крыму, интернет магазин спортивного питания, купить спортивное питание в интернет магазине, купить спортивное питание в крыму, спортивное питание для суставов и связок, интернет магазин спортивного питания в крыму, заказать спортивное питание интернет, протеиновое спортивное питание, заказать спортивное питание через интернет, спорт питание, купить спортивное питание симферополь, спортивное питание симферополь, спортивное питание севастополь, спорт питание симферополь, спорт питание севастополь, спортивное питание симферополь, спортивное питание севастополь, спортивное питание ялта, спортивное питание евпатория, спортивное питание в феодосии, спортивное питание керчь, спортивное питание алушта, спортивное питание севастополь купить, спортивное питание симферополь интернет магазин"),
	array ("/payment_delivery/","Условии оплаты в интернет-магазине Спорт Мегамаркет в Крыму","Оплатите свой заказ наличными в пункте самовывоза, банковским переводом и с помощью электронных систем. Осуществляем доставку по всему Крыму!"),
	array ("/contact/","Контакты интернет-магазина Спорт Мегамаркет","Проконсультироваться и заказать, а также задать любые интересующие Вас вопросы, Вы можете только по телефону +7 (,978) 749 39 40. "),
	array ("/discount/","условия доставки в интернет-магазине Спорт Мегамаркет в Крыму","Мы доставляем спортивное питание по всему Крыму! Вам доступна доставка курьером, самовывоз и доставка транспортной компанией по Крыму."),
	array ("/about_us/","О команде интернет-магазина Спорт Мегамаркет в Крыму","Мы работаем для вас и можем предложить те товары, в которых вы нуждаетесь, и которые помогут сохранить здоровье, стать сильнее и привлекательнее."),
	array ("/encyclopedia/","Энциклопедия интернет-магазина Спорт Мегамаркет в Крыму","Мы знаем все о спортивном питании и готовы поделиться информацией с вами. Читайте интересные и полезные статьи о спорт питании на нашем сайте."),
	array ("/news/","Новости интернет-магазина Спорт Мегамаркет в Крыму","Следите за новостями и событиями в нашей компании. Узнавайте об акциях и распродажах первыми!"),
	array ("/articles/","Статьи интернет-магазина Спорт Мегамаркет в Крыму","Мы знаем все о спортивном питании и готовы поделиться информацией с вами. Читайте интересные и полезные материалы о спорт питании на нашем сайте."),
	array ("/catalog/","Каталог спортивного питания интернет-магазина Спорт Мегамаркет","Весь каталог спортивного питания. Спорт питание для новичков и опытных спортсменов. Доступные цены. Доставка по всему Крыму."),
	array ("/registration/","Регистрация в интернет-магазине Спорт Мегамаркет в Крыму","Зарегистрируйтесь, чтоб создать ваш личный кабинет в нашем интернет-магазине. Кабинет позволит быстро осуществить покупку и отслеживать статус вашего заказа.")
);

$arrayEndOfPages = array ( //массив с добавлением пагинации в тайтл
	'page:1'=>' - страница 1',
	'page:2'=>' - страница 2',
	'page:3'=>' - страница 3',
	'page:4'=>' - страница 4',
	'page:5'=>' - страница 5',
	'page:6'=>' - страница 6',
	'page:7'=>' - страница 7',
	'page:8'=>' - страница 8',
	'page:9'=>' - страница 9',
	'page:10'=>' - страница 10',
	'page:11'=>' - страница 11',
	'page:12'=>' - страница 12',
	'page:13'=>' - страница 13',
	'page:14'=>' - страница 14',
	'page:15'=>' - страница 15',
	'show:all'=>' - все страницы',
);

$arrayRedirect = array (
	'/catalog/group:38/'=>'http://sportmm.ru/catalog/group:1013/',
	'/catalog/group:37/'=>'http://sportmm.ru/catalog/group:1012/',
	'/catalog/group:39/'=>'http://sportmm.ru/catalog/group:1014/',
	'/catalog/group:17/'=>'http://sportmm.ru/catalog/group:1015/',
	'/catalog/group:1016/'=>'http://sportmm.ru/catalog/',
	'/catalog/group:1016/page:7/'=>'http://sportmm.ru/catalog/page:7/',
	'/catalog/group:1016/show:all/'=>'http://sportmm.ru/catalog/show:all/',
	'/catalog/group:1016/page:4/'=>'http://sportmm.ru/catalog/page:4/',
	'/catalog/group:1016/page:3/'=>'http://sportmm.ru/catalog/page:3/',
	'/catalog/group:1016/page:6/'=>'http://sportmm.ru/catalog/page:6/',
	'/catalog/group:1016/page:5/'=>'http://sportmm.ru/catalog/page:5/',
	'/catalog/group:1016/page:2/'=>'http://sportmm.ru/catalog/page:2/',
	'/catalog/group:1016/page:1/'=>'http://sportmm.ru/catalog/page:1/'
);


$urlHost =  strval($_SERVER['REQUEST_URI']);

//echo $urlHost;



for ($i = 0; $i<=10; $i++)
{
	if ($arrayOfMetaTags[$i][0] == $urlHost) {
		$currentAltTitle = $arrayOfMetaTags[$i][1];
		$currentDescription = $arrayOfMetaTags[$i][2];
		$currentKeywords = $arrayOfMetaTags[$i][3];
	}
}



	//$urlHost =  strval($_SERVER['REQUEST_URI']);
	//if ($urlHost == "/") {
		//$currentAltTitle = "Интернет-магазин спортивного питания в Крыму. Купить спорт питание в Севастополе, Симферополе, Ялте";
		//$currentDescription = "Большой выбор спортивного питания в интернет-магазине СпортМегамаркет. Протеины, BCCA, аминокислоты, гейнеры, витамины для спортсменов. Доступные цены.";
	//$currentKeywords = "спортивное питание, магазин спортивного питания, спортивное питание купить, спортивное питание в крыму, интернет магазин спортивного питания, купить спортивное питание в интернет магазине, купить спортивное питание в крыму, спортивное питание для суставов и связок, интернет магазин спортивного питания в крыму, заказать спортивное питание интернет, протеиновое спортивное питание, заказать спортивное питание через интернет, спорт питание, купить спортивное питание симферополь, спортивное питание симферополь, спортивное питание севастополь, спорт питание симферополь, спорт питание севастополь, спортивное питание симферополь, спортивное питание севастополь, спортивное питание ялта, спортивное питание евпатория, спортивное питание в феодосии, спортивное питание керчь, спортивное питание алушта, спортивное питание севастополь купить, спортивное питание симферополь интернет магазин";
	//};
	$positionOfMaskProduct = strpos($urlHost, '/product/'); 
	$positionOfMaskCatalog = strpos($urlHost, '/catalog/group:');
	$positionOfMaskBrand = strpos($urlHost, '/catalog/brand:');
	$positionOfMaskOnlyCatalog = strpos($urlHost, '/catalog/');




	if ($positionOfMaskCatalog === 0) {//перенаправление некоторых страниц
		foreach ($arrayRedirect as $key=>$value){
			if ($key == $urlHost) {
				header("HTTP/1.1 301 Moved Permanently");
				header('Location:'.$value);
				exit();
			}
		}

	};


	$mask = "(\/(\w+)\W(\w+)\/)"; //маска общая (\/(\w+)\W((\w+:\d+)([A-Za-z0-9-]+))\/(\w+:\d+|\w+:\w+)?)

	if ($positionOfMaskCatalog === 0 || $positionOfMaskBrand === 0) {
		$mask = "(\/(\w+)\W((\w+:\d+)([A-Za-z0-9-]*))\/(\w+:\d+|\w+:\w+)?)";
	}; //часть маски для каталога


	/*if ($positionOfMaskCatalog === 0 || $positionOfMaskBrand === 0) {
		$mask2 = ":\d+";
		$mask3 = "(\w+:\d+|\w+:\w+)?";
	}; //часть маски для каталога
	$mask = "(\/(\w+)\W(\w+".$mask2.")\/".$mask3.")"; */


	preg_match_all ($mask,$urlHost,$chpuArrayFromUri); //создаем массив с вхождением 2й части URI
	$chpuFromUri = $chpuArrayFromUri [2][0]; // выбираем из массива вхождение 2й части URI
	$chpuUrlString = $chpuArrayFromUri [4][0]; // выбираем из массива часть с ЧПУ
	$chpuUrlString = substr ($chpuUrlString,1); //убираем первый символ тире
	$lastPartFromUri = $chpuArrayFromUri [5][0]; //последняя часть URI -для пагинации, если в каталоге более 1 стр
	$chpuFromUriForBrand = $chpuArrayFromUri [3][0];
	$lastPartFromUriCat = $chpuArrayFromUri [3][0];


	if ($positionOfMaskProduct === 0 && isset ($chpuFromUri)) { //если URI начинается с /product/, т.е. это товар
		$sqlQueryOfTagsValues = "SELECT name FROM mod_product WHERE chpu LIKE '".$chpuFromUri."'" ;
		$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
		$currentAltTitle = "Купить ".$resultOfTagsValues [0]['name']." в интернет-магазине Спорт Мегамаркет";
		$currentDescription = $resultOfTagsValues [0]['name']." с доставкой по всему Крыму. Доступная цена, высокое качество. Большой выбор спортивного питания в Крыму в интернет-магазине Спорт Мегамаркет.";
		$currentKeywords = "Купить ".$resultOfTagsValues [0]['name'];

	}
	
	elseif ($positionOfMaskCatalog === 0 && isset ($chpuFromUri)) { //если URI начинается с /catalog/, т.е. это каталог

				$chpuFromUri = substr ( $chpuFromUri, 6 );
				$chpuFromUri = (int)$chpuFromUri;
						if ($chpuFromUri < 1000) {
							$sqlQueryOfTagsValues = "SELECT subcategory_cpu_url,subcategory_title,subcategory_description,subcategory_keywords FROM dict_category WHERE id LIKE '".$chpuFromUri."'" ;
							$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);

							$currentSubcategoryCpuUrl = $resultOfTagsValues [0]['subcategory_cpu_url'];

							if ($chpuUrlString != $currentSubcategoryCpuUrl && !empty ($currentSubcategoryCpuUrl)) { //проверяем равна ли часть парсеной ЧПУ данным из базы
								header("HTTP/1.1 301 Moved Permanently");
								header('Location:'.'/catalog/group:'.$chpuFromUri.'-'.$currentSubcategoryCpuUrl);
								exit();
							};

						if (!empty($resultOfTagsValues [0]['subcategory_title'])) $currentAltTitle = $resultOfTagsValues [0]['subcategory_title'].$arrayEndOfPages[$lastPartFromUri];
						if (!empty($resultOfTagsValues [0]['subcategory_description'])) $currentDescription = $resultOfTagsValues [0]['subcategory_description'];
						if (!empty($resultOfTagsValues [0]['subcategory_keywords'])) $currentKeywords = $resultOfTagsValues [0]['subcategory_keywords'];
					}
					else {
						$chpuFromUri = $chpuFromUri - 983;
						$sqlQueryOfTagsValues = "SELECT category_cpu_url,category_title,category_description,category_keywords FROM config WHERE id LIKE '".$chpuFromUri."'" ;
						$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);

						$currentSubcategoryCpuUrl = $resultOfTagsValues [0]['category_cpu_url'];

						if ($chpuUrlString != $currentSubcategoryCpuUrl && !empty ($currentSubcategoryCpuUrl)) { //проверяем равна ли часть парсеной ЧПУ дагным из базы
							$chpuFromUri = $chpuFromUri +983;
							header("HTTP/1.1 301 Moved Permanently");
							header('Location:'.'/catalog/group:'.$chpuFromUri.'-'.$currentSubcategoryCpuUrl);
							exit();
						};

						if (!empty($resultOfTagsValues [0]['category_title'])) $currentAltTitle = $resultOfTagsValues [0]['category_title'].$arrayEndOfPages[$lastPartFromUri];
						if (!empty($resultOfTagsValues [0]['category_description'])) $currentDescription = $resultOfTagsValues [0]['category_description'];
						if (!empty($resultOfTagsValues [0]['category_keywords'])) $currentKeywords = $resultOfTagsValues [0]['category_keywords'];}
					}

	elseif ($positionOfMaskBrand === 0 && isset ($chpuFromUri)) {
				$chpuFromUri = substr ( $chpuFromUriForBrand, 6 );

				$sqlQueryOfTagsValues = "SELECT brand_cpu_url,brand_title,brand_description,brand_keywords FROM mod_brands WHERE id LIKE '".$chpuFromUri."'" ;
				$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
				if (!empty($resultOfTagsValues [0]['brand_title'])) $currentAltTitle = $resultOfTagsValues [0]['brand_title'].$arrayEndOfPages[$lastPartFromUri];
				if (!empty($resultOfTagsValues [0]['brand_description'])) $currentDescription = $resultOfTagsValues [0]['brand_description'];
				if (!empty($resultOfTagsValues [0]['brand_keywords'])) $currentKeywords = $resultOfTagsValues [0]['brand_keywords'];

		$currentBrandCpuUrl = $resultOfTagsValues [0]['brand_cpu_url'];

		if ($chpuUrlString != $currentBrandCpuUrl  && !empty ($currentBrandCpuUrl)) { //проверяем равна ли часть парсеной ЧПУ данным из базы
			header("HTTP/1.1 301 Moved Permanently");
			header('Location:'.'/catalog/brand:'.$chpuFromUri.'-'.$currentBrandCpuUrl);
			exit();
		};

	}

	elseif ($positionOfMaskOnlyCatalog ===0 )	{
		$lastPartFromUriCat = "page:".$lastPartFromUriCat;
		if($lastPartFromUriCat == "page:all") $lastPartFromUriCat = "show:all";
		$currentAltTitle = $arrayOfMetaTags[8][1].$arrayEndOfPages[$lastPartFromUriCat];
		$currentDescription = $arrayOfMetaTags[8][2].$arrayEndOfPages[$lastPartFromUriCat];
		$currentKeywords = $arrayOfMetaTags[8][3].$arrayEndOfPages[$lastPartFromUriCat];
	}

	$currentAltTitle = "<title>".$currentAltTitle."</title>\n" ;
	$currentDescription = "<meta name=\"description\" content=\"".$currentDescription."\">\n";
	$currentKeywords = "<meta name=\"keywords\" content=\"".$currentKeywords."\">\n";	

	echo $currentAltTitle.$currentDescription.$currentKeywords;
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
<link href="/css/currentStyle.css" rel="stylesheet"/>



<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="//vk.com/js/api/openapi.js?151"></script>

<script type="text/javascript">
	VK.init({apiId: 6304664, onlyWidgets: true});
</script>
