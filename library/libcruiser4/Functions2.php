<?php
//-------------------------------------------------------------------------
	function checkBackSlash($domen, $url) {
		if (preg_match('%\.%i', $url) || !$url) {
			return;
		}

		$params = "";
		if (preg_match('/(\?[^?]*$)/i', $url, $paramsSection)) {
			$params = $paramsSection[1];
		}

		if ($params) {
			$url = str_replace($params, "", $url);
		}

		if (!preg_match('%/$%i', $url)) {
			$url .= "/" . $params;

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://{$domen}{$url}");
			header("Connection: close");
			exit;
		}
	}

	function checkRU($domen, $url) {
		global $_this;

//		if (strpos($_this['domen'], 'as-vilis')) {
//			if (preg_match('%/en/%i', $url) || !$url) {
//				$url = str_replace("/en/", "/", $url);

//				header("HTTP/1.1 301 Moved Permanently");
//				header("Location: http://{$domen}{$url}");
//				header("Connection: close");
//				exit;
//			}
//		} else {
			if (preg_match('%/ru/%i', $url) || !$url) {
				$url = str_replace("/ru/", "/", $url);

				header("HTTP/1.1 301 Moved Permanently");
				header("Location: http://{$domen}{$url}");
				header("Connection: close");
				exit;
			}
//		}
	}

	function checkLn($domen, $url) {
		global $_this;

		if (preg_match('%\?ln=%i', $url) || !$url) {
			$url = str_replace("?ln=", "/", $url);

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://{$domen}{$url}");
			header("Connection: close");
			exit;
		}
	}

	function checkUndefined($domen, $url) {
		if (preg_match('%/undefined/%i', $url) || !$url) {
			$url = str_replace("/undefined/", "/", $url);

			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://{$domen}{$url}");
			header("Connection: close");
			exit;
		}
	}

	function checkInternalDomen($domen, $url) {
		global $_this, $local_ip_array;

		if (strpos($_this['domen'], 'vils-casino') && !in_array(get_ip(), $local_ip_array)) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://lasvilis.com{$url}");
			header("Connection: close");
			exit;
		}
	}

	function getUrlByArtChpu($chpu) {
		global $db;
		$chpu = strip_tags(trim($chpu));
		$info = array();
		// проверяем искомый chpu  в статья
		$sql = sql_placeholder("
			SELECT
				ad.*,
				ag.id_te_value AS cnt_id
			FROM
				".CFG_DBTBL_MOD_ARTICLE." AS ad,
				".CFG_DBTBL_MOD_AG_TREE." AS at,
				".CFG_DBTBL_MOD_AG_DATA." AS ag
			WHERE
				ad.chpu = ?	AND
				ad.id_article_group = at.id AND
				at.data_id = ag.id
			",
			$chpu
		);
		$info = $db->get_row($sql);
		if(is_array($info)) {
			return $info;
		} else {
			return false;
		}
	}

	//-------------------------------------------------------------------------
	function ParseUrl()
	{
		global $_this, $db, $lng, $nsTree;
		$_this['node_id'] = 0;
		$_this['404'] = false;
		$node_id_array = array(44, 45, 46, 47, 48, 56);

		// Проведем правки форвардов по урлу
		checkBackSlash($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
		checkRU($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
		checkLn($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
		checkUndefined($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
		//checkInternalDomen($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);

		// Определим ветку для текущего домена (по корневым узлам)
		$nodeSet = &$nsTree->select(0, array('chpu'), NSTREE_AXIS_CHILD);
		foreach($nodeSet as $node){
			if(stristr($node['chpu'], $_this['domen'])) {
				$_this['node_id'] = $node['id'];
				break;
			}
		}
	// Если ветка присутствует (т.е. запрошен поддерживаемый системой домен)
		if($_this['node_id']){
			$dot = strtok($_this['chpu'], "/");
			$node_id = $_this['node_id'];
	// Перебираем все узлы от корня данного домена
			while($dot){
	// Заполним дерево данного домена в массив для обработки. Ввод всего дерева производится так как обработка
	// массива быстрее чем множества запросов
				$nodeSet = $nsTree->select($node_id, array('name', 'chpu', 'id_contaner', 'title', 'description', 'keywords', 'enable', 'printable', 'wile'), NSTREE_AXIS_CHILD);
				foreach($nodeSet as $node){
					if($dot == strtolower(trim($node['chpu']))){
	// Проинициализируем массив для градусника
						// FIXME сменить наименование на нормальное (например route)
						$_this['gradusnik']['id'][]   = $node['id'];
						$_this['gradusnik']['chpu'][] = $node['chpu'];
						$_this['gradusnik']['name'][] = $node['name'];
	// Проинициализируем массив параметров выбранной страницы
						$_this['page']['id']          = $node['id'];
						$_this['page']['id_contaner'] = $node['id_contaner'];
						$_this['page']['title']       = $node['title'];
						$_this['page']['description'] = $node['description'];
						$_this['page']['keywords']    = $node['keywords'];
						$_this['page']['enable']      = $node['enable'];
						$_this['page']['printable']   = $node['printable'];
						$_this['page']['wile']        = $node['wile'];
						$_this['page']['addr']       .= $dot."/";
	// Инициализируем индекс родительского элемента для поиска дочерних если ЧПУ не закончится
						$node_id = $node['id'];
						break;
					}
				}
	// Если узел не найден значит либо это язык и продолжаем сбор адреса, либо найдена переменная и из цикла выходим
				if($dot <> strtolower(trim($node['chpu']))){
					// FIXME зачем запрос когда в $lng уже есть этот массив
					$sql = sql_placeholder("
						SELECT id
						FROM ".CFG_DBTBL_DICT_LANGUAGE."
						WHERE ind_name=?",
						$dot
					);
					$lang_id = $db->get_one($sql);
					if($lang_id){
						$_this['lng'] = $lang_id;
					}else{
						// реферал и тогда запись в сессию и редирект либо 404
						if (!$_this['page']['id'] && is_integer(strpos($dot, 'ref'))) {
							$ref_hash = substr($dot, 3);
							$ref_id = $db_p->get_one("SELECT mps.id_partner FROM mod_partner_adv_platform AS mpap, mod_partner_site AS mps WHERE mps.id = mpap.id_mod_partner_site AND mpap.hash = ?", $ref_hash);
							if($ref_id){
								$_SESSION['vilis_ref_hash'] = $ref_hash;
								SetCookie("vilis_ref_hash", $ref_hash, time() + 604800, '/');
								$_SESSION['vilis_ref_id'] = $ref_id;
								SetCookie("vilis_ref_id", $ref_id, time() + 604800, '/');

								// учтем клик по баннеру
								$click_row = $db_p->get_row("
									SELECT mpapc.count, mpap.id
										FROM mod_partner_adv_platform_click AS mpapc
										   , mod_partner_adv_platform AS mpap
										WHERE mpap.hash = ? AND mpapc.date = DATE_FORMAT(NOW(), '%Y-%m-%d') AND mpapc.id_mod_partner_adv_platform = mpap.id
								", $ref_hash);

								if ($click_row['count']) {
									$db_p->query("UPDATE mod_partner_adv_platform_click SET count = count + 1 WHERE id_mod_partner_adv_platform = ? AND date = DATE_FORMAT(NOW(), '%Y-%m-%d')", $click_row['id']);
								} else {
									if (!$click_row['id']) {
										$click_row['id'] = $db_p->get_one("SELECT mpap.id FROM mod_partner_adv_platform AS mpap WHERE mpap.hash = ?", $ref_hash);
									}
									$db_p->query("INSERT INTO mod_partner_adv_platform_click SET count = 1, date = NOW(), id_mod_partner_adv_platform = ?", $click_row['id']);
								}
							}
							header("HTTP/1.1 301 Moved Permanently");
							header("Location: http://" . $_SERVER['HTTP_HOST']);
							header("Connection: close");
							exit;
						} else {
							if (!in_array($_this['page']['id'], $node_id_array) && $dot != 'logout') {
								$nodeSet404 = $nsTree->select(43, array('name', 'chpu', 'id_contaner', 'title', 'description', 'keywords', 'enable', 'printable', 'wile'), NSTREE_AXIS_SELF);
								$_this['page']['id']          = $nodeSet404[0]['id'];
								$_this['page']['id_contaner'] = $nodeSet404[0]['id_contaner'];
								$_this['page']['title']       = $nodeSet404[0]['title'];
								$_this['page']['description'] = $nodeSet404[0]['description'];
								$_this['page']['keywords']    = $nodeSet404[0]['keywords'];
								$_this['page']['enable']      = $nodeSet404[0]['enable'];
								$_this['page']['printable']   = $nodeSet404[0]['printable'];
								$_this['page']['wile']        = $nodeSet404[0]['wile'];
								$_this['page']['addr']       .= $dot."/";
								$_this['404'] = true;
							}
							break;
						}
					}
				}
	// Читаем следующий узел и если он пустой значит закончился УРЛ
				$dot = strtok("/");
				if(!$dot){
					break;
				}
			}
	// Перебераем параметры передаваемые через ЧПУ и ищем номер страницы для многостраничности до тех пор пока
	// не кончится УРЛ
			// TODO описать конструкции
			while($dot){
				if(preg_match("/^printable_(\d+)$/", $dot, $match)){
					$_this['printable']      = $match[1];
					$_this['printableblock'] = $match[2];
				}elseif(preg_match("/^p_(\d+)_c(\d+)$/", $dot, $match)){
					$_this['page'][$match[2]]['chpu']      = $match[0];
					$_this['page'][$match[2]]['pagenum']   = $match[1];
					$_this['page'][$match[2]]['typeblock'] = TE_VALUE_CONTANER;
				}elseif(preg_match("/^p_(\d+)_al(\d+)_id(\d+)$/", $dot, $match)){
					$_this['page']['article']              = 'list';
					$_this['page']['dotid_article']        = $match[2];
					$_this['page'][$match[2]]['chpu']      = $match[0];
					$_this['page'][$match[2]]['pagenum']   = $match[1];
					$_this['page'][$match[2]]['typeblock'] = TE_VALUE_ARTICLEL;
					$_this['page'][$match[2]]['idn']       = $match[3];
				}elseif(preg_match("/^p_(\d+)_at(\d+)_id(\d+)$/", $dot, $match)){
					$_this['page']['article']              = 'text';
					$_this['page']['dotid_article']        = $match[2];
					$_this['page']['article_id']        = $match[3];
					$_this['page'][$match[2]]['chpu']      = $match[0];
					$_this['page'][$match[2]]['pagenum']   = $match[1];
					$_this['page'][$match[2]]['typeblock'] = TE_VALUE_ARTICLET;
					$_this['page'][$match[2]]['idn']       = $match[3];
				}elseif(preg_match("/^p_(\d+)_ad(\d+)$/", $dot, $match)){
					$_this['page'][$match[2]]['chpu']      = $match[0];
					$_this['page'][$match[2]]['pagenum']   = $match[1];
					$_this['page'][$match[2]]['typeblock'] = TE_VALUE_ADVERTISE;
				}elseif(preg_match("/^p_(\d+)_fl(\d+)_id(\d+)$/", $dot, $match)){
					$_this['page']['article']              = 'list';
					$_this['page'][$match[2]]['chpu']      = $match[0];
					$_this['page'][$match[2]]['pagenum']   = $match[1];
					$_this['page'][$match[2]]['typeblock'] = TE_VALUE_FOTO;
					$_this['page'][$match[2]]['idn']       = $match[3];
				}elseif(preg_match("/^p_(\d+)$/", $dot, $match)){
					$_this['page']['pagenum']   = $match[1];
				}elseif(preg_match("/^printable$/", $dot, $match)){
					$_this['printable'] = 1;
				}else{
					$_this['value'][] = $dot;
				}
				$dot = strtok("/");
			}
		}
		// TODO а это еще зачем?
		if(!$_this['page']['id']){
			$node = $nsTree->getNode($_this['node_id'],
				array(
					'chpu',
					'id_contaner',
					'title',
					'description',
					'keywords',
					'enable',
					'printable')
			);
			$_this['page']['id']          = $node['id'];
			$_this['page']['id_contaner'] = $node['id_contaner'];
			$_this['page']['title']       = $node['title'];
			$_this['page']['description'] = $node['description'];
			$_this['page']['keywords']    = $node['keywords'];
			$_this['page']['enable']      = $node['enable'];
			$_this['page']['printable']   = $node['printable'];
			$_this['page']['wile']        = $node['wile'];
			$_this['page']['addr']        = "/";
		}
		if(!isset($_this['page']['pagenum']) || !$_this['page']['pagenum']){
			$_this['page']['pagenum'] = 1;
		}
		$_this['html'] = "";
		$_this['lng'] = (isset($_this['lng']) && $_this['lng']) ? $_this['lng'] : $lng->deflt_lng; // TODO deflt или now_lng ?
	}

// Парсер URL-адреса. Инициализация массива this
function GetIdDot($id_main_page, $chpu, $nsTree)
{
	global $current_page_addr, $current_page_num;
// Отделим левую папку
	if(! $slash_pos = strpos($chpu, '/')){
		$slash_pos = strlen($chpu);
	}
	$left_chpu = substr($chpu, 0, $slash_pos);
	$right_chpu = substr($chpu, $slash_pos+1, strlen($chpu)-$slash_pos-1);
// Переберем дочерние элементы для нахождения идентификатора левой папки
	$chpuSelect = &$nsTree->select($id_main_page, array('chpu'), NSTREE_AXIS_CHILD);
	foreach($chpuSelect as $chpuS){
		if($chpuS['chpu'] == $left_chpu){
			$current_page_addr .= $chpuS['chpu']."/";
			break;
		}
	}
// Если такой комбинации в базе нет то страница не найдена
	if($chpuS['chpu'] <> $left_chpu){
		die("404-1");
		return false;
	}
// Если справа еще что-то осталось и это не номер страницы для многостраничного отображения то рекурсия
	if($right_chpu && ! preg_match("/^p_([0-9])+([\/]*)$/", $right_chpu, $match)){
		return GetIdDot($chpuS['id'], $right_chpu, $nsTree);
	}else{
		$current_page_num = $match[1];
	}
// Вернем искомый идентификатор
	return $chpuS['id'];
}
//-------------------------------------------------------------------------
// Определение ЧПУ по Id
function GetDotId($id, $nsTree)
{
	global $_this, $lng;
	$chpu_path = "";
// Переберем дочерние элементы для нахождения идентификатора левой папки
	$chpuSelect = &$nsTree->select($id, array('chpu'), NSTREE_AXIS_ANCESTOR_OR_SELF);
	foreach($chpuSelect as $chpuS){
		if($chpuS['level'] > 1){
			$chpu_path .= ($chpuS['chpu']) ? $chpuS['chpu']."/" : "";
		}else{
			if($lng->now_lng == $lng->deflt_lng){ // $_this['lng']
				$chpu_path .= ($chpuS['chpu']) ? $_this['domen']."/" : "";
			}else{
				$chpu_path .= ($chpuS['chpu']) ? $_this['domen']."/".strtolower($lng->GetNameLng($lng->now_lng))."/" : "";
			}
		}
	}
// Вернем искомый ЧПУ
	return $chpu_path;
}
//-------------------------------------------------------------------------
function getAdrPage($page_adr)
{
	global $_this, $lng;
	return "http://".$_this['domen'].(($lng->deflt_lng <> $lng->now_lng) ? "/".strtolower($lng->GetNameLng($lng->now_lng)) : "").((substr($page_adr, 0, 1) == "/") ? "" : "/").$page_adr;
}
//-------------------------------------------------------------------------
// Маршрутизатор страниц
function routerPageStr($addr, $pg, $count_pg, $arg, $codeblock = "")
{
	$center_page_str = ($pg > 1) ? "<a href=\"".$addr."p_".($pg-1).(($codeblock) ? "_".$codeblock : "")."/".(($arg) ? "?".$arg : "")."\"><u>"._("пред.")."</u></a>&nbsp;" : "&nbsp;";
	for($i = 1; $i <= $count_pg; $i++){
		$center_page_str .= ($i-4 <= $pg && $i+4 >= $pg) ? ($pg == $i) ? "<b>$i</b>&nbsp;" : "<a href=\"".$addr."p_".$i.(($codeblock) ? "_".$codeblock : "")."/".(($arg) ? "?".$arg : "")."\"><u>$i</u></a>&nbsp;" : "";
	}
	$center_page_str .= ($pg < $count_pg) ? "<a href=\"".$addr."p_".($pg+1).(($codeblock) ? "_".$codeblock : "")."/".(($arg) ? "?".$arg : "")."\"><u>"._("след.")."</u></a>" : "";
	if($count_pg > 1){
		return "<p style=\"text-align: center\">"._("Страницы").":&nbsp;".$center_page_str."</p>";
	}
}
//-------------------------------------------------------------------------
function encode_ip($dotquad_ip)
{
	$ip_sep = explode('.', $dotquad_ip);
	return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
}
//-------------------------------------------------------------------------
function decode_ip($int_ip)
{
	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}
//-------------------------------------------------------------------------
// Функция обработки события для неотоброжающейся страницы (отсутствие контента, выбранного языка)
function mkGoTo($type = 0)
{
	global $_this, $site_config;
	switch($_this['page']['wile']){
		case NODE_WILE_UP:
			$temp_chpu = substr($site_config['chpu'], 0, strpos($site_config['chpu'], '/'));
			$temp_chpu = (strlen($temp_chpu) == (strlen($site_config['chpu'])-1)) ? substr($temp_chpu, 0, strpos($temp_chpu, '/')) : $temp_chpu;
			$location = 'http://'.$_this['www'].$_this['domen'].'/'.$temp_chpu.'/';
			print('<script language="JavaScript"><!--
				location.replace("'.$location.'");
				// --></script>
				<noscript><META http-equiv="Refresh" content="0;URL='.$location.'"></noscript>'
			);
			exit;
			break;
		case NODE_WILE_CONSTRUCT:
		case NODE_WILE_SIMPLE:
		case NODE_WILE_404:
			break;
	}
	return true;
}
//-------------------------------------------------------------------------
// Определение отдельно имени файла и расширения
function parseFileName($fileName)
{
	$file_name = strtolower($fileName);
	$delta_fn = explode("/", $file_name);
	unset($file_name);
	for($i = 0; $i < count($delta_fn)-1; $i++){
		$file_name['path'] = ($i) ? $file_name['path']."/".$delta_fn[$i] : $delta_fn[0];
	}
	$file_name['path'] .= "/";
	$file_name['name'] = $delta_fn[$i];
	$delta_fn = explode(".", $file_name['name']);
	for($i = 0; $i < count($delta_fn)-1; $i++){
		$file_name['name'] = ($i) ? $file_name['name'].".".$delta_fn[$i] : $delta_fn[0];
	}
	$file_name['type'] = $delta_fn[$i];
	return $file_name;
}
//-------------------------------------------------------------------------
// Пустая функция
function EmptyFunc($buffer)
{
	return "";
}
//-------------------------------------------------------------------------
function getTeValueName($te_value_id = 0)
{
	global $db;
    if ($te_value_id) {
    	$getTeVal = $db->query(sql_placeholder("SELECT name FROM ".CFG_DBTBL_TE_VALUE." WHERE id=?", $te_value_id));
    	if($getTeVal->num_rows){
    		$te_val = $getTeVal->fetch_assoc();
    		return $te_val['name'];
    	}
    }
    return false;
}
//--------------------------------------------------------------------------
function getTeValueId($te_value_name)
{
	global $db;
	$getTeVal = $db->query(sql_placeholder("SELECT id FROM ".CFG_DBTBL_TE_VALUE." WHERE name=?", trim($te_value_name)));
	if($getTeVal->num_rows){
		$te_val = $getTeVal->fetch_assoc();
		return $te_val['id'];
	}else{
		return false;
	}
}
//--------------------------------------------------------------------------------------------


