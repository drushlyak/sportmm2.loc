<?php
//--------------------------------------
class teController
{
	private $_db;
	private $templateType;
	private $cntTree;
	private $container;
	private $code;
	private $teValue;
	private $data = array();
//----------------------------------------
	function __construct($id_contaner)
	{
		global $_this;
		$this->_db = mydb::instance();
		$this->container = $id_contaner;
		$this->cntTree = new NSTree(
			CFG_DBTBL_TE_CONTTREE,
			CFG_DBTBL_TE_CONTDATA,
			array(
				'id'      => TREE_STRUCT_ID,
				'data_id' => TREE_STRUCT_DATA_ID,
				'left'    => TREE_STRUCT_LEFT,
				'right'   => TREE_STRUCT_RIGHT,
				'level'   => TREE_STRUCT_LEVEL
			)
		);
		if(!$this->getData()){
			mkGoTo();
		}
		$contanerSet = $this->cntTree->select($this->container, array('code'), NSTREE_AXIS_SELF);
		$this->code = $contanerSet[0]['code'];
		if($this->code){
			$this->code = $this->getRecursiveHtml($this->code);
			return true;
		}else{
			return false;
		}
	}
//----------------------------------------
	function getRecursiveHtml($text_html) {
		// TODO добавить проверку рекусии вызова шаблонов, т.е. предусмотреть случай, когда шаблон-потомок вызывает
		// в себе построение своего родительского шаблона.

		// Переберем все шаблонные переменные и запросим их код
		$count_res = preg_match_all("/(?<={)([a-zA-Z0-9_-]+)(?=})/i", $text_html, $matches);
		if($count_res){
			foreach($matches[0] as $match){
				$code = $this->getTemplateData($match);
				// исправление бага с $1...
				$code = str_replace('$', '___dollarbug___', $code);
				$text_html = preg_replace("/{".$match."}/i", $code, $text_html);
				$text_html = str_replace('___dollarbug___', '$', $text_html);
			}
			return $this->getRecursiveHtml($text_html);
		}else{
			return $text_html;
		}
	}
//----------------------------------------------------
	function getTemplateData($te_value)
	{
		$this->teValue = new teValue($te_value);
// Проверим есть ли такая шаблонная переменная в базе
		$sql = sql_placeholder("
			SELECT
				tv.id AS id,
				tv.typ AS typ,
				tv.sys AS sys,
				tv.file AS file,
				ti.class AS class_name,
				ti.file AS inc_file,
				tt.id AS typ_id,
				ti.sys_var AS sys_var,
				module.var AS mod_var
			FROM ".CFG_DBTBL_TE_VALUE." as tv
			JOIN  ".CFG_DBTBL_TE_TYPE." as tt
				ON (tv.typ = tt.te_value)
			LEFT JOIN ".CFG_DBTBL_TE_INCLUDES." as ti
				ON (ti.typ_id = tt.id)
			LEFT JOIN ".CFG_DBTBL_MODULE." AS module
				ON (ti.mod_id = module.id)
			WHERE tv.name=?", $this->teValue->getTeValueName()
		);
		$param_te_value = $this->_db->get_row($sql);
		if(is_array($param_te_value)){
			if($param_te_value['file']){
				require_once('teFileTemplate_class.php');
				$teData = new teFileTemplate($this->teValue->getTeValueName(), $param_te_value, $this->data);
			}else{
				if($param_te_value['sys_var']){
					require_once(LIB_PATH."/tmpl/".$param_te_value['inc_file']);
				}else{
					require_once(MODULE_PATH."/".$param_te_value['mod_var']."/".$param_te_value['inc_file']);
				}
				$teData = new $param_te_value['class_name']($this->teValue->getTeValueName(), $param_te_value, $this->data);
			}
		}
		if($teData){
			return $teData->getCode();
		}else{
			return false;
		}
	}
//---------------------------------------------------
	public function __toString()
	{
		return (string)$this->code;
	}
//---------------------------------------------------
	private function getData()
	{
		if(!$this->getGETData()){
			return false;
		}
		if(!$this->getPOSTData()){
			return false;
		}
		if(!$this->getCOOKIEData()){
			return false;
		}
		if(!$this->getFILESData()){
			return false;
		}
		return true;
	}
//---------------------------------------------------
	private function getGETData()
	{
		global $_this;
// Получаем список всех переменных для текущей страницы
		$sql = sql_placeholder("
			SELECT *
			FROM ".CFG_DBTBL_ATTR."
			WHERE
			page_id = ?
			 AND method = 'GET'", $_this['page']['id']
		);
		$varSet = $this->_db->get_all($sql);
		if(is_array($varSet)){
			foreach($varSet as $var){
				$requires_exist = false;
				if(is_array($_this['value'])){
					foreach($_this['value'] as $attr){
						if(is_integer(strpos($attr, $var['name']))){
							$temp_var = substr($attr, mb_strlen($var['name'], "UTF-8"));
							$requires_exist = true;
						}
					}
				}
// Обрабатываем массив $_GET
				if(is_array($_GET)){
					if(isset($_GET[$var['name']])){
						$temp_var = $_GET[$var['name']];
						$requires_exist = true;
					}
				}
				if($var['default_value'] && !$requires_exist){
					$temp_var = $var['default_value'];
					$requires_exist = true;
				}
				if($requires_exist){
					switch($var['var_type']){
						case "int":
							$this->data['GET'][$var['name']] = intval($temp_var);
							break;
						case "float":
							$this->data['GET'][$var['name']] = floatval($temp_var);
							break;
						case "string":
							$this->data['GET'][$var['name']] = strip_tags(trim($temp_var));
							break;
						default:
							$this->data['GET'][$var['name']] = intval($temp_var);
							break;
					}
				}
			}
		}
// Очищаем $_GET
		//unset($_GET);
		return true;
	}
//-----------------------------------------------------------------
	private function getPOSTData()
	{
		global $_this;
		if($_SERVER['REQUEST_METHOD'] != "POST"){
			return true;
		}
// Получаем список всех переменных для текущей страницы
		$sql = "
			SELECT *
			FROM ".CFG_DBTBL_ATTR."
			WHERE page_id = ?
				AND method = 'POST'
		";
		$varSet = $this->_db->get_all($sql, $_this['page']['id']);
		if(is_array($varSet)){
			foreach($varSet as $var){
				$requires_exist = false;
// Обрабатываем массив $_POST
				if(is_array($_POST)){
					if(isset($_POST[$var['name']])){
						$temp_var = $_POST[$var['name']];
						$requires_exist = true;
					}
				}
				if($var['default_value'] && !$requires_exist){
					$temp_var = $var['default_value'];
					$requires_exist = true;
				}
				if($requires_exist){
					switch($var['var_type']){
						case "int":
							$this->data['POST'][$var['name']] = intval($temp_var);
							break;
						case "float":
							$this->data['POST'][$var['name']] = floatval($temp_var);
							break;
						case "string":
							$this->data['POST'][$var['name']] = strip_tags(trim($temp_var));
							break;
						default:
							$this->data['POST'][$var['name']] = intval($temp_var);
							break;
					}
				}
			}
		}
// Очищаем $_POST
		//unset($_POST);
		return true;
	}
//-----------------------------------------------------------------
	private function getCOOKIEData()
	{
		global $_this;
// Получаем список всех переменных для текущей страницы
		$sql = "
			SELECT *
			FROM ".CFG_DBTBL_ATTR."
			WHERE page_id = ?
				AND method = 'COOKIE'
		";
		$varSet = $this->_db->get_all($sql, $_this['page']['id']);
		if(is_array($varSet)){
			foreach($varSet as $var){
				$requires_exist = false;
// Обрабатываем массив $_COOKIE
				if(is_array($_COOKIE)){
					if(isset($_COOKIE[$var['name']])){
						$temp_var = $_COOKIE[$var['name']];
						$requires_exist = true;
					}
				}
				if($var['default_value'] && !$requires_exist){
					$temp_var = $var['default_value'];
					$requires_exist = true;
				}
				if($requires_exist){
					switch($var['var_type']){
						case "int":
							$this->data['COOKIE'][$var['name']] = intval($temp_var);
							break;
						case "float":
							$this->data['COOKIE'][$var['name']] = floatval($temp_var);
							break;
						case "string":
							$this->data['COOKIE'][$var['name']] = strip_tags(trim($temp_var));
							break;
						default:
							$this->data['COOKIE'][$var['name']] = intval($temp_var);
							break;
					}
				}
			}
		}
// Очищаем $_COOKIE
		//unset($_COOKIE);
		return true;
	}
//-----------------------------------------------------------------
	private function getFILESData()
	{
		global $_this;
		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			return true;
		}
// Получаем список всех переменных для текущей страницы
		$sql = sql_placeholder("
			SELECT *
			FROM ".CFG_DBTBL_ATTR."
			WHERE page_id = ?
				AND method = 'FILE'",
			$_this['page']['id']
		);
		$varSet = $this->_db->get_all($sql);
		if(is_array($varSet)){
			foreach($varSet as $var){
				$requires_exist = false;
// Обрабатываем массив $_FILES
				if(is_array($_FILES)){
					if(is_uploaded_file($_FILES[$var['name']]['tmp_name'])){
						$this->data['FILES'][$var['name']] = $_FILES[$var['name']];
						$requires_exist = true;
					}
				}
			}
		}
// Очищаем $_FILES
		//unset($_FILES);
		return true;
	}
//-----------------------------------------------------------------
}
?>