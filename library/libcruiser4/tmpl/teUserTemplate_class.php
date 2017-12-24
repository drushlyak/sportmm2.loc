<?php
//======================================================
class teUserTemplate extends teTemplate
{
	
//-----------------------------------------
	function __construct()
	{
		$args = func_get_args();
		parent::__construct($args[0], $args[1], $args[2]);
		return true;
	}
//-----------------------------------------
	public function getCode()
	{
		$this->code = false;
		return $this->code;
	}
//----------------------------------------
}

?>