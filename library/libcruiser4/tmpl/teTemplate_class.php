<?php
require_once('teTemplate_interface.php');

class teTemplate implements teTemplateInterface
{
	protected $code;
	protected $_db;
	protected $teValue;
	protected $c_lng;
	protected $c_this;
	protected $data;
	protected $param_te_value = array();
//-------------------------------------------------------------
	public function __construct($te_value, $param_te_value, $data = array())
	{
		global $lng, $_this;
		$this->param_te_value = $param_te_value;
		$this->teValue = new teValue($te_value);
		$this->code = '';
		$this->data = $data;
		$this->_db = mydb::instance();
		$this->c_lng = $lng;
		$this->c_this = $_this;
		
		return true;
	}
//-------------------------------------------------------------
	public function getCode()
	{
		return true;
	}
//-------------------------------------------------------------
	public function makeCode()
	{
		return true;
	}
//-------------------------------------------------------------
}

?>