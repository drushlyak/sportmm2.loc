
<?php
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
						$sqlQueryOfTagsValues = "SELECT subcategory_text FROM dict_category WHERE id LIKE '".$chpuFromUri."'" ;
						$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
						$currentSubcategoryText = $resultOfTagsValues [0]['subcategory_text'];
						}
					else {
						$chpuFromUri = $chpuFromUri - 983;
						$sqlQueryOfTagsValues = "SELECT category_text FROM config WHERE id LIKE '".$chpuFromUri."'" ;
						$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
						$currentSubcategoryText = $resultOfTagsValues [0]['category_text'];
						}
		}

	elseif ($positionOfMaskBrand === 0 && isset ($chpuFromUri)) {

		$chpuFromUri = substr ( $chpuFromUri, 6 );
		$chpuFromUri = (int)$chpuFromUri;
		$sqlQueryOfTagsValues = "SELECT brand_text FROM mod_brands WHERE id LIKE '".$chpuFromUri."'" ;
		$resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
		$currentSubcategoryText = $resultOfTagsValues [0]['brand_text'];
		}
	
	print_r ($currentSubcategoryText);

?>
	
