
<?php
$urlHost =  strval($_SERVER['REQUEST_URI']);
$positionOfMaskCatalog = strpos($urlHost, '/catalog/group:');
$positionOfMaskBrand = strpos($urlHost, '/catalog/brand:');
$chpuLenght = strlen ($urlHost);

if ($positionOfMaskCatalog === 0 || $positionOfMaskBrand === 0) {
    $mask = "(\/(\w+)\W((\w+:\d+)([A-Za-z0-9-]*))\/(\w+:\d+|\w+:\w+)?)";
};
preg_match_all ($mask,$urlHost,$chpuArrayFromUri); //создаем массив с вхождением 2й части URI
$chpuFromUri = $chpuArrayFromUri [3][0];// выбираем из массива вхождение 2й части URI

if ($positionOfMaskCatalog === 0 && isset ($chpuFromUri)) { //если URI начинается с /catalog/, т.е. это каталог
    $chpuFromUri = substr ( $chpuFromUri, 6 );
    $chpuFromUri = (int)$chpuFromUri;

    echo "<div class=\"videoslider\">
                 <ul>";

    if ($chpuFromUri < 1000) {


        for ($currentVideo = 1; $currentVideo < 7 ; $currentVideo++)
        {
            $currentVideoCell = "subcategory_video_".$currentVideo;
            $sqlQueryOfTagsValues = "SELECT ".$currentVideoCell." FROM dict_category WHERE id LIKE '".$chpuFromUri."'" ;
            $resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
            $currentVideoUrl = $resultOfTagsValues [0][$currentVideoCell];
                if ($currentVideoUrl != "" ) {
                    echo '<iframe width="200" height="120" src="http://www.youtube.com/embed/' . $currentVideoUrl . '" frameborder="0" allowfullscreen></iframe>';
                }
                }
    }

    else {
        $chpuFromUri = $chpuFromUri - 983;


        for ($currentVideo = 1; $currentVideo < 7 ; $currentVideo++)
        {
            $currentVideoCell = "category_video_".$currentVideo;
            $sqlQueryOfTagsValues = "SELECT ".$currentVideoCell." FROM config WHERE id LIKE '".$chpuFromUri."'" ;
            $resultOfTagsValues = $db->get_all($sqlQueryOfTagsValues);
            $currentVideoUrl = $resultOfTagsValues [0][$currentVideoCell];
            if ($currentVideoUrl != "" ) {
                echo '<iframe width="200" height="120" src="http://www.youtube.com/embed/' . $currentVideoUrl . '" frameborder="0" allowfullscreen></iframe>';
            }
            }

    }

    echo "</ul>
     </div>";
}

if ($chpuLenght==9) include "htdocs/videoslider.php";

?>

