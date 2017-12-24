<?php
// Проверка доступа
if(!$attributes['acl']){
	print "Ты как сюда попал?";
	return;
}
$id         = intval($attributes['id']);
$typ 		= intval($attributes['typ']);
$tmp_path = RESOURCE_PATH."/mod/";
$FORM_ERROR = "";
$teId = 0;
require_once(LIB_PATH."/pclzip.lib.php");
//---------------------------------------------------------------------
function unpackZip($file)
{
	global $FORM_ERROR, $site_config, $tmp_module_dir, $tmp_path;
	$zip = new PclZip($file);
	$tmp_module_dir = "tmp".time();
	if(is_writable($tmp_path)){
		$contents = $zip->extract(PCLZIP_OPT_PATH, $tmp_path.$tmp_module_dir);
	}else{
		$FORM_ERROR = _("Нет прав на запись во временную папку:") . $tmp_path . ". ";
		return false;
	}
	$tmp_module_dir = $tmp_path.$tmp_module_dir;
	if (!$contents) {
		$FORM_ERROR = _("Не удалось разархивировать файл:") . " '" . $zip->errorInfo(true) . "'. ";
		return false;
	}
	return true;
}
//----------------------------------------------------------------------
function checkRequest($xml)
{
	global $db, $mod_id;
	if($xml->requires){
		$sql = sql_placeholder("
			SELECT *
			FROM ".CFG_DBTBL_MODULE
		);
		$modSet = $db->get_all($sql);
		foreach($xml->requires->children() as $req){
			$ready = false;
			foreach($modSet as $mod){
				if($mod['var'] == $req['name']){
					$ready = true;
					break;
				}
			}
			if(!$ready){
				return false;
			}
		}
	}
	return true;
}
//----------------------------------------------------------------------
function installDepends($xml)
{
	global $db, $mod_id;
	if($xml->requires){
		$sql = sql_placeholder("
			SELECT *
			FROM ".CFG_DBTBL_MODULE
		);
		$modSet = $db->get_all($sql);
		foreach($xml->requires->children() as $req){
			foreach($modSet as $mod){
				if($mod['var'] == $req['name']){
					$sql = sql_placeholder("
						INSERT
						INTO ".CFG_DBTBL_DEPENDS."
						SET main_mod=?,
						depend_mod=?",
						$mod['id'], $mod_id
					);
					$db->query($sql);
				}
			}
		}
	}
}
//----------------------------------------------------------------------
function installTables($xml)
{
	global $db;
	if($xml->tables){
		foreach ($xml->tables->children() as $table) {
			$sql = sql_placeholder("
				INSERT
				INTO ".CFG_DBTBL_TABLES."
				SET
					tbl_const=?,
					tbl_name=?",
				$table['const'], $table['name']
			);
			$db->query($sql);
		}
	}
	return true;
}
//-----------------------------------------------------------------------
function installCircuits($xml)
{
	global $db, $mod_type;
	if($xml->circuits){
		$circuit_path = ($mod_type)?"home/fbx/dictionaries/":"home/fbx/modules/";
		foreach ($xml->circuits->children() as $circuit) {
			$path = $circuit_path.$xml->var."/".($circuit['path']);
			$sql = sql_placeholder("
				INSERT
				INTO ".CFG_DBTBL_CIRCUIT."
				SET
					name=?,
					path=?",
				$circuit['name'], $path
			);
			$db->query($sql);
		}
	}
	return true;
}
//------------------------------------------------------------------------
function createSql($xml)
{
	global $site_config, $db, $res_path;
	if($xml->sql){
		$sql = file_get_contents($res_path.$xml->var."/".$xml->sql);
		$db->multi_query($sql);
	}
	return true;
}
//------------------------------------------------------------------------
function installPrivileges($xml)
{
	global $db, $mod_id, $auth_in, $lng;
	$privileges = array();
	$configTable = $auth_in->store->getConfig();
	if($xml->acl->privileges){
		foreach($xml->acl->privileges->children() as $privilege){
			$privLng = $lng->NewId();
			foreach($lng->lng_array as $dlng){
				$lng->Settext($privLng, $privilege['name'], $dlng['id']);
			}
			$sql = sql_placeholder("
				SELECT *
				FROM {$configTable['privilegeTable']}
				WHERE var=?",
				$privilege['var']
			);
			$res = $db->query($sql);
			if($res->num_rows){
				$row = $res->fetch_assoc();
				$sql = sql_placeholder("
					INSERT
					INTO ".CFG_DBTBL_ACL_MOD_PRIV."
					SET
						module_id=?,
						privilege_id=?",
					$mod_id, $row['id']
				);
			}else{
				$sql = sql_placeholder("
					INSERT
					INTO {$configTable['privilegeTable']}
					SET
						name=? ,
						var=?",
					$privLng, $privilege['var']
				);
				$db->query($sql);
				$id = $db->insert_id;
				$sql = sql_placeholder("
					INSERT
					INTO ".CFG_DBTBL_ACL_MOD_PRIV."
					SET
						module_id=?,
						privilege_id=?",
					$mod_id, $id
				);
			}
			$db->query($sql);
		}
	}
	return true;
}
//---------------------------------------------------------------------------------
function installDspAcl($xml)
{
	global $db, $mod_id, $res_path;
	if($xml->displays){
		foreach ($xml->displays->children() as $dsp) {
			$sql = sql_placeholder(" INSERT INTO ".CFG_DBTBL_DSP_ACL."
				SET file=?, mod_id=?",
				$dsp['file'], $mod_id
			);
			$db->query($sql);
		}
	}
	return true;
}
//---------------------------------------------------------------------------------
function installMenus($xml, $parent)
{
	global $sTree, $db, $lng;
	foreach ($xml->children() as $item) {
		$titleLng = $lng->NewId();
		foreach ($lng->lng_array as $dlng) {
			$lng->Settext($titleLng, $item['title'], $dlng['id']);
		}
		$id = $sTree->appendChild($parent, array(
		'title'      => $titleLng,
		'url'        => $item['url'],
		'menu'       => $item['menu'],
		'edt'        => $item['edt']), 0);
		installMenus($item, $id);
	}
	return true;
}
//-----------------------------------------------------------------------------
function installSettings($xml) {
	
	global $db, $lng;
	
	if ($xml->settings) {
		
		foreach ($xml->settings->children() as $set) {
			
			$db->insert(CFG_DBTBL_CONFIG, array(
				'config_name' => $set['name'],
				'config_value' => $set['value']
			));
			
			/* TODO del this block
			$msgid = $lng->NewId();
			$valueArray = array();
			$valueArray['msgid'] = $msgid;
			$valueArray[$lng->deflt_lng] = $set['value'];
			$value = $lng->SetTextlng($valueArray);
			$sql = sql_placeholder("
				INSERT
				INTO ".CFG_DBTBL_CONFIG."
				SET
					config_name=?,
					config_value=?",
				$set['name'], $value
			);
			$db->query($sql);
			*/
		}
	}
	
	return true;
}
//-----------------------------------------------------------------------------
function installVars($xml)
{
	global $db;
	if($xml->vars){
		foreach($xml->vars->children() as $var){
			$sql = sql_placeholder("
				INSERT
				INTO ".CFG_DBTBL_TE_VALUE."
				SET
					name=?,
					typ=1,
					sys=0",
				$var['name']
			);
			$db->query($sql);
		}
	}
	return true;
}
//-----------------------------------------------------------------------------
function installTemplates($xml)
{
	global $db;
	if($xml->tmpls){
		foreach ($xml->tmpls->children() as $tmpl) {
			$sql = "SELECT MAX(te_value) FROM ".CFG_DBTBL_TE_TYPE;
			$value = $db->get_one($sql);
			$value++;
			$sql = sql_placeholder("
				INSERT
				INTO ".CFG_DBTBL_TE_TYPE."
				SET
					te_const=?,
					te_value=?",
				$tmpl['name'], $value
			);
			$db->query($sql);
		}
	}
	return true;
}
//-----------------------------------------------------------------------------
function installIncludes($xml)
{
	global $db, $teId, $mod_type, $mod_id;
	if($xml->includes){
		$path = ($mod_type) ? DICT_PATH : MODULE_PATH;
		foreach ($xml->includes->children() as $inc) {
			$sql = sql_placeholder("
				INSERT
				INTO ".CFG_DBTBL_TE_INCLUDES."
				SET
					file=?,
					class=?,
					typ_id=?,
					mod_id=?",
				$inc['file'], $inc['class'], $teId, $mod_id
			);
			$db->query($sql);
		}
	}
	return true;
}
//-----------------------------------------------------------------------------
function installTemplatesArray($xml)
{
	global $db, $teId;
	if($xml->tetypes){
		foreach ($xml->tetypes->children() as $tetype) {
			$sql = "
				SELECT id
				FROM ".CFG_DBTBL_TE_TYPE."
				WHERE te_const=?
			";
			$teId = $db->get_one($sql, $tetype['name']);

			if($teId){
				$sql = sql_placeholder("
					INSERT
					INTO ".CFG_DBTBL_TE_TYPE_ARRAY."
					SET
						te_type=?,
						text=?,
						firstchar=?",
					$teId, $tetype['title'], $tetype['firstchar']
				);
				$db->query($sql);
				return true;
			}else{
				return false;
			}
		}
	}
	return true;
}
//------------------------------------------------------------------------------
function install($xml)
{
	global $typ, $FORM_ERROR, $db, $mod_type, $res_path, $mod_id, $tmp_module_dir;
// Переименовывем временный каталог
	if(!rename($tmp_module_dir, $res_path.$xml->var)){
		$FORM_ERROR = _("Не удалось переименовать директорию");
		return false;
	}else{
		chmodRecursive($res_path.$xml->var, 0777);
	}
	if(!installPrivileges($xml)){
		$FORM_ERROR = _("Не удалось установить таблицы");
		return false;
	}
	if(!installTables($xml)){
		$FORM_ERROR = _("Не удалось установить таблицы");
		return false;
	}
	if(!installCircuits($xml)){
		$FORM_ERROR = _("Не удалось установить circuit-ы для Fusebox");
		return false;
	}
	$res_var = ($mod_type) ? NAVIGATION_DICT : NAVIGATION_MODULE;
	$sql = sql_placeholder("
		SELECT id
			FROM ".CFG_DBTBL_NAVIGATION."
			WHERE var='".$res_var."'"
		);
	$parentId = $db->get_one($sql);
	if(!installMenus($xml->menu, $parentId)){
		$FORM_ERROR = _("Не удалось установить пункты меню");
		return false;
	}
	if(!createSql($xml, $db)){
		$FORM_ERROR = _("Не удалось создать структуру БД");
		return false;
	}
	if(!installDspAcl($xml)){
		$FORM_ERROR = _("Не удалось установить файлы отображения доступа");
		return false;
	}
	require_once($res_path.$xml->var."/install.php");
	$top_id = createResources();
	$sql = sql_placeholder("
		UPDATE ".CFG_DBTBL_MODULE."
		SET
			top_id=?
		WHERE id=?",
		$top_id, $mod_id
	);
	$db->query($sql);
	if(!installTemplates($xml)){
		$FORM_ERROR = _("Не удалось установить типы шаблонов");
		return false;
	}
	if(!installVars($xml)){
		$FORM_ERROR = _("Не удалось установить шаблонные переменные");
		return false;
	}
	if(!installSettings($xml)){
		$FORM_ERROR = _("Не удалось настроить системы");
		return false;
	}
	if(!installTemplatesArray($xml)){
		$FORM_ERROR = _("Неверный данные в xml-конфиге модуля");
		return false;
	}
	if(!installIncludes($xml)){
		$FORM_ERROR = _("Не удалось уставить код для отображения шаблонов");
		return false;
	}
}
//-------------------------------------------------
eval("\$defl_name = trim(\$attributes['name'][".$lng->deflt_lng."]);");
if(!$defl_name){
	$FORM_ERROR .= _("Необходимо указать название для языка по-умолчанию");
}
if($typ == 1){
// Выполняем действия одинаковые для процесса установки и модуля и словаря
	if(is_uploaded_file($_FILES['zipfile']['tmp_name'])){
		$file_name = strtolower($_FILES['zipfile']['name']);
		$delta_fn = explode(".", $file_name);
		unset($file_name);
		for($i = 0; $i < count($delta_fn)-1; $i++){
			$file_name['name'] = ($i) ? $file_name['name'].".".$delta_fn[$i] : $delta_fn[0];
		}
		$file_name['type'] = $delta_fn[$i];
		if($file_name['type'] != 'zip'){
			$FORM_ERROR = _("Тип файла должен быть: zip");
		}
		$tmp_module_dir = 0;
// Распаковываем архив с кодом
		if(!unpackZip($_FILES['zipfile']['tmp_name'])){
			$FORM_ERROR = _("Не удалось разархивировать файл: ") . $FORM_ERROR;
		}
	}else{
		$FORM_ERROR .= _("Выберите файл");
	}
	if(!$FORM_ERROR){
// Загружаем xml-конфиг
		$xml = simplexml_load_file($tmp_module_dir."/config.xml");
// Проверяем установлен ли уже такой словарь
		$sql = sql_placeholder("
			SELECT var
			FROM ".CFG_DBTBL_MODULE."
			WHERE var=?",
			$xml->var
		);
		$res_path = ($xml->type == 'dict') ? DICT_PATH : MODULE_PATH;
		$mod_type = ($xml->type == 'dict') ? 1 : 0;
		if(!is_writeable($res_path)){
			$FORM_ERROR .= _("Нет прав на запись в ") .$res_path;
			$fail = true;
		}
		$res = $db->query($sql);
		if($res->num_rows){
			$FORM_ERROR .= _("Такой модуль в системе уже установлен");
			$fail = true;
		}
		// Проверка зависимостей для модулей
		if(!checkRequest($xml)){
			$FORM_ERROR .= _("Неудовлетворённые зависимости у модуля");
			$fail = true;
		}
		if(!$FORM_ERROR){
			$name = $lng->SetTextlng($attributes['name']);
			$description = $lng->SetTextlng($xml->description);

			$sql = sql_placeholder("
				INSERT
				INTO ".CFG_DBTBL_MODULE."
				SET
					var=?,
					name=?,
					description=?,
					sys=0,
					enabled=?,
					version=?,
					creation_date=?,
					mod_type=?",
				$xml->var, $name, $description,
				$attributes['enabled'], $xml->version,
				$xml->creation_date, $mod_type
			);
			$db->query($sql);
			$mod_id = $db->insert_id;
			installDepends($xml);
			if(!$FORM_ERROR){
				$fail = install($xml);
			}
		}
	}
}else{
	if(($id) && (!$FORM_ERROR) && ($typ == 2)){
		$name = $lng->SetTextlng($attributes['name']);
		$description = $lng->SetTextlng($attributes['description']);
		$sql = sql_placeholder("
			UPDATE ".CFG_DBTBL_MODULE."
			SET
				name=?,
				description=?,
				enabled=?
			WHERE id=?",
			$name, $description, $attributes['enabled'], $id
		);
		$db->query($sql);
	}
}
if(!$FORM_ERROR){
	Location($_XFA['main'], 0);
}else{
	$error = $FORM_ERROR;
	if($fail){
		include("act_Delete.php");
	}
	include("qry_Form.php");
	$FORM_ERROR = $error;
	include("dsp_Form.php");
}

?>