<?php
    /**
    * @desc меню
    * @var array
    */
    $adminMenu = array();

    /**
    * @desc градусник
    * @var array
    */
    $level = array();

    /**
    * @desc краткая справка
    * @var string
    */
    $quickHelp = '';

    // Читаем элементы меню и сабменю и заполняем ими массив adminMenu
    $rsTop = $db->query("
        SELECT id
			 , title
			 , quick_help
			FROM " . CFG_DBTBL_NAVIGATION . "
        WHERE (parent_id = '' OR parent_id = '0' OR parent_id IS NULL)
		  AND menu=1
		ORDER BY ord
    ");
    while ($mitem = $rsTop->fetch_assoc()) {
        $rsSubCats = $db->query("
            SELECT id
				 , title
				 , url
				 , quick_help
				FROM " . CFG_DBTBL_NAVIGATION . "
            WHERE parent_id = ?
			  AND menu = 1
			ORDER BY ord
        ", $mitem['id']);

        $mitem['subcat'] = array();
        while ($msubitem = $rsSubCats->fetch_assoc()) {
            array_push($mitem['subcat'], $msubitem);
        }
        if (count($mitem['subcat']) > 0) {
        	array_push($adminMenu, $mitem);
		}
    }

	// ===========================================================================================

    $gradusnik_str1 = "";
    $gradusnik_str2 = "";
    // Построение строки идентификаторов для уровней по градуснику
    function getIdStr($count_id) {
    	global $attributes;

    	$ignore = array('type', 'fuseaction', 'id', 'params');
    	$result = '';

    	if (is_array($attributes)) {
    		foreach ($attributes as $name => $attr) {
    			if (!in_array($name, $ignore)) {
    				$result .= "&" . $name . "=" . $attr;
    			}
    		}
    	}

		return $result;
    }

    // Построение градусника
    function getParamNavigationDot($parentId, $fuseaction) {
        global $gradusnik_str1, $gradusnik_str2, $lng, $quickHelp, $count_id, $count_id_d;

        $db = getDBInstance();
        $sqlUp = ($fuseaction) ? "url = '{$fuseaction}'" : "id = '{$parentId}'";
        $rsNavigation = $db->query("
            SELECT id, title, url, parent_id, quick_help, edt FROM " . CFG_DBTBL_NAVIGATION . "
            WHERE {$sqlUp} ORDER BY ord
        ");

        if ($mitem = $rsNavigation->fetch_assoc()) {
            $quickHelp = ($fuseaction) ? $mitem['quick_help'] : $quickHelp;
            $count_id_d = ($fuseaction) ? $mitem['edt'] : $count_id_d;
            $gradusnik_str1 = $gradusnik_str2 ? ($mitem['url'] ? "<a href=\"index.php?fuseaction={$mitem['url']}".getIdStr($count_id-1+$count_id_d)."\" title=\"".$lng->Gettextlng($mitem['quick_help'])."\">" : "").$lng->Gettextlng($mitem['title']).($mitem['url'] ? "</a>" : "").($gradusnik_str1 ? " | " : "").$gradusnik_str1 : "";
            $gradusnik_str2 = $gradusnik_str2 ? $gradusnik_str2 : "<span class=\"gradusnik\" id=\"CurrentNavPosition\">".$lng->Gettextlng($mitem['title'])."</span>";
            $count_id--;
            getParamNavigationDot($mitem['parent_id'],'');
            return true;
        } else {
            $gradusnik_str1 = "<a href=\"index.php\" title=\""._("Главная")."\">"._("Главная")."</a>".($gradusnik_str1 ? " | " : "").$gradusnik_str1;
            return true;
        }
    }

    getParamNavigationDot(0, $attributes['fuseaction']);
?>