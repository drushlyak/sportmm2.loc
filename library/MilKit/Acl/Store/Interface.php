<?php

interface MilKit_Acl_Store_Interface {
	
	/**
	 * @param MilKit_Acl $acl
	 *
	 */
	public function bindAcl ($acl);
	
	public function unbindAcl ();
	
	public function load ();

	public function newResourceId ();
	
	public function newRoleId ();

	public function getPrivilegeId ($name); 
}

?>