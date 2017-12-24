<?php

require_once (MAIN_LIB_PATH . "/Zend/Acl/Role/Interface.php");

class MilKit_Acl_Role implements Zend_Acl_Role_Interface {
    /**
     * Unique id of Role
     *
     * @var string
     */
    protected $roleId;

    protected $data;

    /**
     * Sets the Role identifier
     *
     * @param  string $id
     * @return void
     */
    public function __construct($roleId, $data=null)
    {
        $this->roleId = (string) $roleId;
        $this->data = array();
        $this->data = $data;
    }

    /**
     * Defined by Zend_Acl_Role_Interface; returns the Role identifier
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    public function getData () {
    	return $this->data;
    }
}

?>