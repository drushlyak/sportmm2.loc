<?php
class teValue
{
	private $value;
	private $teId;
	private $_db;
//------------------------------------------------------------
	public function __construct($value)
	{
		$this->_db = mydb::instance();
		$this->value = $value;
		$this->setTeValueId();
	}
//------------------------------------------------------------
	public function getTeValueId()
	{
		return $this->teId;
	}
//-------------------------------------------------------------
	public function getTeValueName()
	{
		return $this->value;
	}
//--------------------------------------------------------------
	private function setTeValueName()
	{
		$sql = sql_placeholder("SELECT name FROM ".CFG_DBTBL_TE_VALUE." WHERE id=?", $this->teId);
		$this->value = $this->_db->get_one($sql);
	}
//--------------------------------------------------------------
	private function setTeValueId()
	{
		$sql = sql_placeholder("SELECT id FROM ".CFG_DBTBL_TE_VALUE." WHERE name=?", $this->value);
		$this->teId = $this->_db->get_one($sql);
	}
//----------------------------------------------------------------
	
}

?>