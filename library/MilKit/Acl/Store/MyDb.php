<?php

/**
 * MilKit_Acl_Store_Interface
 */
require_once (MAIN_LIB_PATH . "/MilKit/Acl/Store/Interface.php");
require_once (MAIN_LIB_PATH . "/MilKit/Acl/Role.php");
require_once (MAIN_LIB_PATH . "/MilKit/Acl.php");
require_once (MAIN_LIB_PATH . "/Zend/Acl/Resource.php");
require_once (LIB_PATH . "/db/class.mydb.php");
require_once (LIB_PATH . "/Nstree.class.php");

class MilKit_Acl_Store_MyDb implements MilKit_Acl_Store_Interface
{
	static $defaults = array(
		'resourceSeqTable' => 'acl_resource_seq',
		'resourceTreeTable' => 'acl_resource',
		'resourceTreeFields' => array(
			'id' => 'id',
			'left' => 'lft',
			'right' => 'rgt',
			'level' => 'level',
			'data_id' => 'resource_id'
		),
		'roleTable' => 'acl_role',
		'roleRefTable' => 'acl_role_ref',
		'privilegeTable' => 'acl_privilege',
		'ruleTable' => 'acl_rule',
		'roleSeqTable' => 'acl_role_seq'
	);
	/**
	 * @var mydb
	 */
	private $db;
	/**
	 * Дерево ресурсов
	 *
	 * @var NSTree
	 */
	private $resourceTree;
	/**
	 * Имена таблиц
	 *
	 * @var string
	 */
	private $roleTable, $roleRefTable, $privilegeTable, $ruleTable, $roleSeqTable;
	/**
	 * @var MilKit_Acl
	 */
	private $acl;
	public $privileges = array();
	const ACL_ALLOW = 1;
	const ACL_DENY = 0;
	const ACL_INHERIT = 2;
	private $typeMap = array(
		MilKit_Acl::TYPE_ALLOW => self::ACL_ALLOW,
		MilKit_Acl::TYPE_DENY => self::ACL_DENY
	);
	/**
	 * @cfg mydb $db
	 * @cfg string resourceSeqTable
	 * @cfg string resourceTreeTable
	 * @cfg string
	 */
	public function __construct ($config)
	{
		$config = array_merge(self::$defaults, $config);
		$this->db = $config['db'];
		$this->resourceTree = new NSTree(
			$config['resourceTreeTable'], $config['resourceSeqTable'], $config['resourceTreeFields']
		);
		$this->roleTable = $config['roleTable'];
		$this->roleRefTable = $config['roleRefTable'];
		$this->privilegeTable = $config['privilegeTable'];
		$this->ruleTable = $config['ruleTable'];
		$this->roleSeqTable = $config['roleSeqTable'];
	}
	/**
	 *
	 */
	public function getConfig()
	{
		$configTable = array();
		$configTable['roleTable'] = $this->roleTable;
		$configTable['roleRefTable'] = $this->roleRefTable;
		$configTable['roleSeqTable'] = $this->roleSeqTable;
		$configTable['privilegeTable'] = $this->privilegeTable;
		$configTable['ruleTable'] = $this->ruleTable;

		return $configTable;
	}
	/**
	 * @param MilKit_Acl $acl
	 */
	public function bindAcl($acl)
	{
		if ($this->acl) {
			$this->unbindAcl();
		}
// Добавить слушателей событий addrule, removerule
		$evm = $acl->getEventManager();
		$evm->on('setrule', 		array($this, 'onSetRule'));
		$evm->on('removerule', 		array($this, 'onRemoveRule'));
		$evm->on('addrole', 		array($this, 'onAddRole'));
		$evm->on('removerole', 		array($this, 'onRemoveRole'));
		$evm->on('removeroleall',	array($this, 'onRemoveRoleAll'));
		$evm->on('addresource',		array($this, 'onAddResource'));
		$evm->on('removeresource',	array($this, 'onRemoveResource'));
		$evm->on('removeresourceall',array($this, 'onRemoveResourceAll'));
		$this->acl = $acl;
	}

	public function unbindAcl()
	{
// Убрать слушателей событий
		$evm = $this->acl->getEventManager();
		$evm->un('setrule', 		array($this, 'onSetRule'));
		$evm->un('removerule', 		array($this, 'onRemoveRule'));
		$evm->un('addrole', 		array($this, 'onAddRole'));
		$evm->un('removerole', 		array($this, 'onRemoveRole'));
		$evm->un('removeroleall',	array($this, 'onRemoveRoleAll'));
		$evm->on('addresource',		array($this, 'onAddResource'));
		$evm->on('removeresource',	array($this, 'onRemoveResource'));
		$evm->on('removeresourceall',array($this, 'onRemoveResourceAll'));
		$this->acl = null;
	}

	public function load()
	{
		// загрузить данные
		$this->loadResources();
		$this->loadRoles();
		$this->loadPrivileges();
		$this->loadRules();
	}
	private function loadResources()
	{
		$resourceNodeSet = $this->resourceTree->select(0, array(), NSTREE_AXIS_DESCENDANT);
		$parentStack = array();
		foreach($resourceNodeSet as $node) {
			$parentStack[$node['level']] = $node['data_id'];
			$this->acl->add(new Zend_Acl_Resource($node['data_id']), $parentStack[$node['level']-1]);
		}
	}

	private function checkNodeAdd($hkey, $hvalue, $hash, $allreadyadd, $roleNodeSet) {
		if ($hvalue['parent']) {
			// проверяем на добавленность родителя родительской ноды
			$parent_node = $hash[$hvalue['parent']];
			if ($parent_node['parent']) {
				// рекурсивный вызов
				$this->checkNodeAdd((int) $hvalue['parent'], $parent_node, $hash, $allreadyadd, $roleNodeSet);
			}
			// добавляем родителя, потом потомка
			if (!in_array($hvalue['parent'], $allreadyadd)) {
				$roleNodeSet[] = $parent_node;
				$allreadyadd[] = $hvalue['parent'];
			}
			if (!in_array($hkey, $allreadyadd)) {
				$roleNodeSet[] = $hvalue;
				$allreadyadd[] = $hkey;
			}

			return $roleNodeSet;
		} else {
			if (!in_array($hkey, $allreadyadd)) {
				$roleNodeSet[] = $hvalue;
				$allreadyadd[] = $hkey;
			}

			return $roleNodeSet;
		}
	}

	private function loadRoles ()
	{
		$sql = "
			SELECT t1.id AS `id`, t1.name AS `name`, t2.parent as `parent`
			FROM $this->roleTable AS t1
			LEFT JOIN $this->roleRefTable AS t2
			ON (t1.id = t2.role_id) ORDER BY `parent`
		";
		$roleNodeSet = $this->db->get_all($sql);

		// пересортируем записи для предотвращения появления ситуации, когда регистрируется роль перед родительской
		if (is_array($roleNodeSet)) {
			// заполним хэш быстрого доступа
			$hash = array();
			foreach ( $roleNodeSet as $nsvalue ) {
				$hash[$nsvalue['id']] = $nsvalue;
			}
			// расставим в правильном порядке итоговый рекордсет
			$roleNodeSet = array(); $allreadyadd = array();
			foreach ( $hash as $hkey => $hvalue ) {
				$roleNodeSet = $this->checkNodeAdd($hkey, $hvalue, $hash, $allreadyadd, $roleNodeSet);
			}
		}

		$currentRole = 0;
		$parents = array();
		foreach($roleNodeSet as $roleNode){
			if($currentRole != $roleNode['id']){
				if($currentRole){
					$this->acl->addRole(new MilKit_Acl_Role($currentRole), $parents ? $parents : null);
					$parents = array();
				}
				$currentRole = $roleNode['id'];
			}
			if($roleNode['parent']){
				$parents[] = $roleNode['parent'];
			}
		}
		if($currentRole){
			$this->acl->addRole(new MilKit_Acl_Role($currentRole), $parents ? $parents : null);
		}
	}

	private function loadPrivileges()
	{
		$arr = $this->db->get_hashtable("
			SELECT var, id
			FROM $this->privilegeTable
		");
		if ($arr) {
			$this->privileges = $arr;
		}
	}
	private function loadRules () {
		$ruleNodeSet = $this->db->get_all("
			SELECT resource_id, role_id, privilege_id, type
			FROM $this->ruleTable
		");
		if(!$ruleNodeSet){
			return;
		}
		foreach($ruleNodeSet as $rule){
			if ($rule['role_id'] == 0){
				$rule['role_id'] = null;
			}
			if ($rule['resource_id'] == 0){
				$rule['resource_id'] = null;
			}
			if ($rule['privilege_id'] == 0){
				$rule['privilege_id'] = null;
			}
			if ($rule['type'] == self::ACL_ALLOW) {
				$this->acl->allow($rule['role_id'], $rule['resource_id'], $rule['privilege_id']);
			} else {
				$this->acl->deny($rule['role_id'], $rule['resource_id'], $rule['privilege_id']);
			}
		}
	}
	public function newResourceId()
	{
// вставляем запись в resource_seq
		$this->db->query("INSERT INTO ".$this->resourceTree->dataTable." VALUES()");
		return $this->db->insert_id;
	}
	public function newRoleId()
	{
// вставляем запись в role_seq
		$this->db->query("INSERT INTO $this->roleSeqTable VALUES()");
		return $this->db->insert_id;
	}

	public function getPrivilegeId ($name)
	{
		if (!array_key_exists($name, $this->privileges)) {
			throw new MilKit_Acl_Exception("Undefined prvilege '$name'");
		}
		return $this->privileges[$name];
	}
	// Коммент:
	// Все операции по сохранению, изменению и удалению правил - это реакция на события $acl
	public function onRemoveRule ($flags, $resourceId=null, $roleId=null, $privilegeId=null, $type=null) {
		$where = array();
		if ($resourceId !== null) {
			$where['resource_id'] = $resourceId;
		} else if (!($flags & MilKit_Acl::ANY_RESOURCE)) {
			$where['resource_id'] = 0;
		}
		if ($roleId !== null) {
			$where['role_id'] = $roleId;
		} else if (!($flags & MilKit_Acl::ANY_ROLE)) {
			$where['role_id'] = 0;
		}
		if ($privilegeId !== null) {
			$where['privilege_id'] = $privilegeId;
		} else if (!($flags & MilKit_Acl::ANY_PRIVILEGE)) {
			$where['privilege_id'] = 0;
		}
		if ($type !== null) {
			$where['type'] = $this->typeMap[$type];
		}
		$whereStmt = array();
		foreach ($where as $k => $v) {
			$whereStmt[] = sprintf("%s='%s'", $k, addslashes($v));
		}
		$where = !empty($whereStmt) ? 'WHERE ' . join(' AND ', $whereStmt) : '';
		$sql = "DELETE FROM $this->ruleTable $where";
		$this->db->query($sql);
	}
	/**
	 * Enter description here...
	 *
	 * @param int $resourceId
	 * @param int $roleId
	 * @param int $privilegeId
	 * @param int $type
	 */
	public function onSetRule ($resourceId, $roleId, $privilegeId, $type)
	{
		$data['resource_id'] = $resourceId !== null ? $resourceId : 0;
		$data['role_id'] = $roleId !== null ? $roleId : 0;
		$data['privilege_id'] = $privilegeId !== null ? $privilegeId : 0;
		if ($type !== null) {
			$data['type'] = $this->typeMap[$type];
		}

		$cond = array();
		foreach ($data as $k => $v) {
			$cond[] = sprintf("%s='%s'", $k, addslashes($v));
		}

		$where = 'WHERE ' . join(' AND ', $cond);
		$set = 'SET ' . join(',', $cond);

		$sql = "SELECT `id` FROM $this->ruleTable $where";
		if (($id = $this->db->get_one($sql))) {
			$sql = "UPDATE $this->ruleTable $set WHERE id='$id'";
		} else {
			$sql = "INSERT INTO $this->ruleTable $set";
		}
		$this->db->query($sql);
	}
	/**
	 * @param Zend_Acl_Role_Interface $role
	 * @param array $parentsId
	 */
	public function onAddRole(Zend_Acl_Role_Interface $role, $parentsId)
	{
		$data = array();
		if($dataArray = $role->getData()){
			foreach ($dataArray as $key => $value){
				$data[] = sprintf("%s='%s'", $key, addslashes($value));
			}
		}
		$roleExist = $this->db->query("SELECT id FROM $this->roleTable WHERE id=?", $role->getRoleId());
		if (!$roleExist->num_rows) {
			$data[] = sprintf("id='%s'", $role->getRoleId());
			$set = 'SET ' . join(',', $data);
			$sql = "INSERT INTO  $this->roleTable $set";
		} else {
			$set = 'SET ' . join(',', $data);
			$sql = "UPDATE $this->roleTable $set WHERE id=".$role->getRoleId();
		}
		$this->db->query($sql);
		$this->db->query("DELETE FROM $this->roleRefTable WHERE role_id=?", $role->getRoleId());
		foreach ($parentsId as $parentId) {
			$this->db->query("INSERT INTO  $this->roleRefTable SET role_id=?, parent=?", $role->getRoleId(), $parentId);
		}
	}

	/**
	 * @param Zend_Acl_Role_Interface $role
	 */
	public function onRemoveRole(Zend_Acl_Role_Interface $role)
	{
		$this->db->query("UPDATE $this->roleRefTable SET parent=0 WHERE parent=?", $role->getRoleId());
		$this->db->query("DELETE FROM $this->roleRefTable WHERE role_id=?", $role->getRoleId());
		$this->db->query("DELETE FROM $this->roleTable WHERE id=?", $role->getRoleId());
		$this->db->query("DELETE FROM $this->roleSeqTable WHERE id=?", $role->getRoleId());
	}
	/**
	 * Enter description here...
	 *
	 */
	public function onRemoveRoleAll()
	{
		$this->db->query("TRUNCATE $this->roleRefTable");
		$this->db->query("TRUNCATE $this->roleTable");
		$this->db->query("TRUNCATE $this->roleSeqTable");
	}
	/**
	 * @param Zend_Acl_Resource_Interface $resource
	 * @param array $parentsId
	 */
	public function onAddResource(Zend_Acl_Resource_Interface $resource, $parent)
	{
		$this->resourceTree->appendChild($parent, array(), $resource->getResourceId());
	}
	/**
	 * @param Zend_Acl_Resource_Interface $resource
	 */
	public function onRemoveResource(Zend_Acl_Resource_Interface $resource, $removeChild=false)
	{
		$nodeSet = $this->resourceTree->selectNodes(0, 0, array());
		$nodeId = $this->db->get_one("SELECT id FROM ".$this->resourceTree->structTable."
			WHERE data_id=?", $resource->getResourceId()
		);
		if($removeChild){
			$this->resourceTree->removeNodes($nodeId);
		}else{
			$this->resourceTree->removeNodes($nodeId, false);
		}
	}
	/**
	 * Enter description here...
	 *
	 */
	public function onRemoveResourceAll () {
		$this->resourceTree->clear();
	}

	/**
	 * @return NSTree
	 */
	public function getResourceTree () {
		return $this->resourceTree;
	}
}

?>