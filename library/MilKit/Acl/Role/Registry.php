<?php

require_once (MAIN_LIB_PATH . "/Zend/Acl/Role/Registry.php");

class MilKit_Acl_Role_Registry extends Zend_Acl_Role_Registry {

	/**
	 * @var MilKit_EventManager
	 */
	protected $eventManager;

	public function __construct () {
		$this->eventManager = new MilKit_EventManager();
		$this->eventManager->addEvents(array(
			/**
			 * New role was added
			 * @param Zend_Acl_Role_Interface $role
			 * @param array $parentsId
			 */
			'add',
			/**
			 * Role was removed
			 * @param Zend_Acl_Role_Interface $role
			 */
			'remove',
			/**
			 * All roles were removed
			 */
			'removeall'
		));
	}

	/**
	 * @return MilKit_EventManager
	 */
	public function getEventManager () {
		return $this->eventManager;
	}

	public function add(Zend_Acl_Role_Interface $role, $parents = null) {
		parent::add($role, $parents);

		$r = $this->_roles[$role->getRoleId()];
		$parentsId = array();
		foreach ($r['parents'] as $rp) {
			$parentsId[] = $rp->getRoleId();
		}
		$this->eventManager->fire('add', $role, $parentsId);
	}

	public function remove($role) {
		$role = $this->get($role);
		parent::remove($role);
		$this->eventManager->fire('remove', $role);
	}

	public function removeAll () {
		parent::removeAll();
		$this->eventManager->fire('removeall');
	}
}

