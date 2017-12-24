<?php
    /**
    * @package NSTree
    * @author Marat Komarov <marat@webta.net>
    * @author .ter
    * @copyright virab.info (c) 2005
    *
    * $Id: NSTree.class.php,v 0.9.2b
    */

    // Error strings
    define ("NONEXIST_NODE",     "node not found");
    define ("INVALID_TABLE",     "invalid %s table name");
    define ("INVALID_COLUMN",    "invalid structure column `%s`");
    define ("NOTENOUGTH_PARAMS", "structure columns not enougth");
    define ("INVALID_AXIS",      "invalid axis or axis not supported in this method");
    
    
    // Axis constants
	/**
	* NSTREE_AXIS_CHILD                 все дочерние элементы данного узла
	* NSTREE_AXIS_CHILD_OR_SELF         все дочерние элементы данного узла и сам узел
	* NSTREE_AXIS_DESCENDANT            все потомки
	* NSTREE_AXIS_DESCENDANT_OR_SELF    все потомки и сам узел
	* NSTREE_AXIS_PARENT                родительский элемент узла
	* NSTREE_AXIS_ANCESTOR              все предки
	* NSTREE_AXIS_ANCESTOR_OR_SELF      все предки и сам узел
	* NSTREE_AXIS_SELF                  сам узел
	* NSTREE_AXIS_LEAF                  все листья (элементы не имеющие потомков)
	* NSTREE_AXIS_FOLLOWING_SIBLING     следующие соседние элементы
	* NSTREE_AXIS_PRECENDING_SIBLING    предыдущие соседние элементы
	*
	*/    
    define ('NSTREE_AXIS_DESCENDANT',         0);
    define ('NSTREE_AXIS_CHILD',              1);
    define ('NSTREE_AXIS_ANCESTOR',           2);
    define ('NSTREE_AXIS_PARENT',             3);
    define ('NSTREE_AXIS_FOLLOWING_SIBLING',  4);
    define ('NSTREE_AXIS_PRECENDING_SIBLING', 5);
    define ('NSTREE_AXIS_SELF',               6);
    define ('NSTREE_AXIS_DESCENDANT_OR_SELF', 7);
    define ('NSTREE_AXIS_ANCESTOR_OR_SELF',   8);
    define ('NSTREE_AXIS_LEAF',               9);
    define ('NSTREE_AXIS_CHILD_OR_SELF',      10);
    
	$GLOBALS['NSTREE_DEFAULT_FIELDS'] = array(
		'id'      => 'id',
		'data_id' => 'data_id',
		'left'    => 'lft',
		'right'   => 'rgt',
		'level'   => 'level'	
	);

    class NSTree {
        /**
        * @desc Table with Nested Sets implemented
        * @var string
        */
        var $structTable;
  
        var $dataTable;
        /**
        * @desc Name of the id-auto_increment-field in the table.
        * @var int
        */
        var $id;
        
        /**
        * @var int
        */
        var $data_id;
        
        /**
        * @var int
        */
        var $left;
        
        /**
        * @var int
        */
        var $right;
        
        /**
        * @var int
        */
        var $level;
        
        var $_db;
        
        /**
        * @param string $structTableName       
        * @param string $dataTableName         
        * @param string $fieldNames            
        */
        function __construct($structTableName, $dataTableName, $fieldNames) {
        	$this->_db = &getDBInstance();
        	
            if (! $structTableName = trim($structTableName))
                $this->_displayError(sprintf(INVALID_TABLE, "struct"), __LINE__, __FILE__);
            $this->structTable = $structTableName;
            
            if (! $dataTableName = trim($dataTableName))
                $this->_displayError(sprintf(INVALID_TABLE, "data"), __LINE__, __FILE__);
            $this->dataTable = $dataTableName;
            
            $tblFields = array('id', 'data_id', 'left', 'right', 'level');
            
            if (sizeof($fieldNames) != 5)
                $this->_displayError(NOTENOUGTH_PARAMS, __LINE__, __FILE__);

            foreach ($fieldNames as $k => $v) {
                if (! in_array($k, $tblFields))
                    $this->_displayError(sprintf(INVALID_COLUMN, $k), __LINE__, __FILE__);
                eval('$this->'.$k.'="'.$v.'";');
            }
        }
        
        /**
        * @param string $message
        * @param int $line
        * @param string $file
        * @param bool $terminate
        */
        function _displayError($message, $line = '', $file = '', $terminate = true) {
            print "<b>NSTree error</b> $message on line $line, file $file<br/><br/>";
            if ($terminate) die();
        }
        
        /**
        * @param int $id
        * @return array
        */
        function getNodeInfo($id) {
            return $this->getNode($id, array());
        }
        
        /**
        * @return array
        */
        function getRootNodeInfo() {
            return $this->getNodeInfo(0);
        }
        
        /**
        * @param int $id
        * @return array
        */
        function getParentNodeInfo($id) {
            return $this->getParentNode($id, array());
        }
        
        /**
        * Returns parent element of the node for such level
        *
        * @param  int $id                
        * @param  string[] $additionalData    
        * @return array
        */
        function getParentNode($id, $additionalData = array()) {
            $nodeSet = $this->select($id, $additionalData, NSTREE_AXIS_PARENT);
            if (! empty($nodeSet))
                return $nodeSet[0];
            return false;
        }
        
        
        /**
        * @param  int $id
        * @param  string[] $additionalData  
        * @return array
        */
        function getNode($id, $additionalData) {
            $nodeSet = $this->select($id, $additionalData, NSTREE_AXIS_SELF);
            if (!empty($nodeSet))
                return $nodeSet[0];
            return false;
        }
        

        /**
        * @param  array $data
        * @return int
        */
        function clear($data = array()) {
            // clearing table
            $this->_db->query("TRUNCATE {$this->structTable}");
            $this->_db->query("TRUNCATE {$this->dataTable}");
            
            // create root node in struct table
            $this->_db->query("
                INSERT INTO {$this->structTable} 
                SET
                    {$this->left} = 1, 
                    {$this->right} = 2, 
                    {$this->level} = 0 
            ");
            $id = $this->_db->insert_id;
            
            // preparing data to be inserted
            $data[$this->id] = $id;
            foreach ($data as $n => $value)
                $sqlInsert[] = "$n=?$n";
            $sqlInsert = implode(', ', $sqlInsert);
            
            $sql = sql_placeholder("INSERT INTO {$this->dataTable} SET {$sqlInsert}", $data);
            
            // insert data
            $this->_db->query($sql);
            
            return $id;
        }

        /**
        * Updates a record
        *
        * @param  int $id
        * @param  array $data
        * @return bool
        */
        function updateNode($id, $data) {
        
            if (! is_array($data)) return false;
            if (! $id = intval($id)) return false;
            
            if ($idInfo = $this->getNodeInfo($id)) {
                foreach ($data as $n => $value)
                    $sqlSet[] = "$n=?$n";
                $sqlSet = implode(', ', $sqlSet);
                
                $sql = sql_placeholder("UPDATE {$this->dataTable} SET {$sqlSet} WHERE {$this->id} = {$idInfo['data_id']}", $data);
                
                
                // insert data
                return $this->_db->query($sql);
            } else
                return false;
        }
        
        /**
        * @desc   Inserts a record into the table with nested sets
        * @param  int $parentId              
        * @param  array $data                  
        * @return int
        */
        function appendChild($parentId, $data, $dataId) 
        {
            $parentId = intval($parentId);
            $dataId   = intval($dataId);
            if(!is_array($data)){
            	$data = array();
            }
            if($parentInfo = $this->getNodeInfo($parentId)){
                $leftId  = $parentInfo['left'];
                $rightId = $parentInfo['right'];
                $level   = $parentInfo['level'];
                if(!$dataId){
// preparing data to be inserted
					foreach ($data as $n => $value){
						$sqlInsert[] = "$n=?$n";
					}
					$sqlInsert = implode(', ', $sqlInsert);
					$sql = sql_placeholder("
						INSERT 
						INTO {$this->dataTable} 
						SET {$sqlInsert}", 
						$data
					);
// insert data
					$this->_db->query($sql);
					$dataId = $this->_db->insert_id;
                }
// creating a place for the record being inserted
				$this->_db->query("
					UPDATE $this->structTable
					SET 
                        $this->left  = IF($this->left  >  $rightId, $this->left  + 2, $this->left),
                        $this->right = IF($this->right >= $rightId, $this->right + 2, $this->right)
                    WHERE 
                        $this->right >= $rightId
                ");
// insert structure
                $this->_db->query("
                    INSERT 
                    INTO $this->structTable
                    SET
                        $this->data_id = $dataId,
                        $this->left    = $rightId,
                        $this->right   = $rightId+1,
                        $this->level   = $level+1
                ");
                $id = $this->_db->insert_id;
                return $id;
            }
            $this->_displayError(NONEXIST_NODE, __LINE__, __FILE__, false);
            return false;
        }
        /**
        * Inserts a record into the table with nested sets
        *
        * @param  int $id
        * @param  array $data          
        * @return int
        */
        function appendSibling($id, $data, $dataId) {
            $id = intval($id);
            $dataId = intval($dataId);
            if (! is_array($data)) $data = array();
            
            if ($info = $this->getNodeInfo($id)) {
                $leftId  = $info['left'];
                $rightId = $info['right'];
                $level   = $info['level'];

                if (! $dataId) {
                    // preparing data to be inserted
                    foreach ($data as $n => $value)
                        $sqlInsert[] = "$n=?$n";
                    $sqlInsert = implode(', ', $sqlInsert);
                    
                    $sql = sql_placeholder("INSERT INTO {$this->dataTable} SET {$sqlInsert}", $data);
                    
                    // insert data
                    $this->_db->query($sql);
                    $dataId = $this->_db->insert_id;
                }
                
                // creating a place for the record being inserted
                $this->_db->query("
                    UPDATE $this->structTable 
                    SET
                        $this->left  = IF($this->left  > $rightId, $this->left+2,  $this->left),
                        $this->right = IF($this->right > $rightId, $this->right+2, $this->right), 
                        $this->data_id = $dataId
                    WHERE 
                        $this->right > $rightId
                ");
                
                // insert structure
                $this->_db->query("
                    INSERT INTO $this->structTable
                    SET
                        $this->left  = $rightId+1,
                        $this->right = $rightId+2,
                        $this->level = $level
                ");
                
                $newId = $this->_db->insert_id;
                
                return $newId;
            }
            $this->_displayError(NONEXIST_NODE, __LINE__, __FILE__, false);            
            return false;
        }
        
        
        /**
        * @deprecated 
        *
        * @param  int $id                
        * @param  int $level             
        * @param  string[] $additionalData    
        * @return array
        */
        function &selectNodes($id, $level=0, $additionalData) {
            return $this->select($id, $additionalData, NSTREE_AXIS_DESCENDANT_OR_SELF);
        }
        
        
        /**
        *
        * @param string $titleField
        */
        function dump($titleField) {
            if (in_array($titleField, array($this->id, $this->left, $this->right, $this->level))) {
                $rsNodes = $this->_db->query("
                    SELECT * FROM {$this->structTable}
                    ORDER BY {$this->left}
                ");
            } else {
                $rsNodes = $this->_db->query("
                    SELECT s.*, d.{$titleField} AS title
                    FROM 
                        {$this->structTable} AS s
                    LEFT JOIN
                        {$this->dataTable} AS d ON s.{$this->id} = d.{$this->id}
                    ORDER BY {$this->left}
                ");
            }
            
            if ($rsNodes->num_rows) {
                $indent = 16;
                while ($node = $rsNodes->fetch_assoc($rsNodes)) {
                    if (! $node['title'])
                        if ($node[$this->left] == 1)
                            $node['title'] = '#root';
                        else 
                            $node['title'] = 'Unnamed node';
                    
                    // output node
                    ?>
                    <div style="padding-left:<?=($indent*$node[$this->level])?>px">
                        <?=$node['title']?>
                        (id:    <?=$node[$this->id]?>;
                         left:  <?=$node[$this->left]?>;
                         right: <?=$node[$this->right]?>;
                         level: <?=$node[$this->level]?>)</div>
                    <?php
                }
            }
        }


        /**
        * Assigns a node with all its children to another parent
        *
        * @param  int $id
        * @param  int $newParentId
        * @return bool
        */
        function replaceNode($id, $newParentId) { 
            if ($nodeInfo = $this->getNodeInfo($id)) {
                $parentInfo = $this->getParentNodeInfo($id);
                $newParentInfo = $this->getNodeInfo($newParentId);
                
                if ($newParentInfo && ($newParentInfo[$this->id] != $parentInfo[$this->id])) {
                    $leftId = $nodeInfo['left'];
                    $rightId = $nodeInfo['right'];
                    $level = $nodeInfo['level'];

                    $leftIdP = $newParentInfo['left'];
                    $rightIdP = $newParentInfo['right'];
                    $levelP = $newParentInfo['level'];
                    
                    // whether it is being moved upwards along the path
                    if ($leftIdP < $leftId && $rightIdP > $rightId && $levelP < $level - 1 ) { 
                        $sql = "
                            UPDATE $this->structTable
                            SET 
                                $this->level = IF(
                                    $this->left BETWEEN $leftId AND $rightId, 
                                    $this->level - ($level-$levelP-1),
                                    $this->level
                                ), 
                                $this->right = IF(
                                    $this->right BETWEEN ($rightId+1) AND ($rightIdP-1), 
                                    $this->right - ($rightId-$leftId+1), 
                                    IF(
                                        $this->left BETWEEN $leftId AND $rightId,
                                        $this->right + ($rightIdP-$rightId-1),
                                        $this->right
                                    )
                                ), 
                                $this->left = IF($this->left BETWEEN ($rightId+1) AND ($rightIdP-1), 
                                    $this->left-($rightId-$leftId+1), 
                                    IF(
                                        $this->left BETWEEN $leftId AND $rightId, 
                                        $this->left + ($rightIdP-$rightId-1),
                                        $this->left
                                    )
                                ) 
                            WHERE 
                                $this->left BETWEEN ($leftIdP+1) AND ($rightIdP-1) 
                        ";
                    } elseif ($leftIdP < $leftId) {
                        $leveldelta = -($level-1)+$levelP;
                        $sql =  "
                            UPDATE $this->structTable 
                            SET 
                                $this->level = IF(
                                    $this->left BETWEEN $leftId AND $rightId,
                                    $this->level".(($leveldelta >= 0) ? "+" : "-").abs($leveldelta).", 
                                    $this->level
                                ), 
                                $this->left = IF(
                                    $this->left BETWEEN $rightIdP AND ($leftId-1),
                                    $this->left + ($rightId-$leftId+1), 
                                    IF(
                                        $this->left BETWEEN $leftId AND $rightId,
                                        $this->left-($leftId-$rightIdP),
                                        $this->left
                                    ) 
                                ), 
                                $this->right = IF(
                                    $this->right BETWEEN $rightIdP AND $leftId, 
                                    $this->right+($rightId-$leftId+1), 
                                    IF(
                                        $this->right BETWEEN $leftId AND $rightId, 
                                        $this->right-($leftId-$rightIdP),
                                        $this->right
                                    ) 
                                ) 
                            WHERE 
                                $this->left BETWEEN $leftIdP AND $rightId OR
                                $this->right BETWEEN $leftIdP AND $rightId                         
                        ";
                    } else {
                        $leveldelta = -($level-1)+$levelP;
                        $sql = "
                            UPDATE $this->structTable
                            SET
                                $this->level = IF(
                                    $this->left BETWEEN $leftId AND $rightId, 
                                    $this->level".(($leveldelta >= 0) ? "+" : "-").abs($leveldelta).",
                                    $this->level
                                ), 
                                $this->left = IF(
                                    $this->left BETWEEN $rightId AND $rightIdP, 
                                    $this->left-($rightId-$leftId+1), 
                                    IF(
                                        $this->left BETWEEN $leftId AND $rightId, 
                                        $this->left+($rightIdP-1-$rightId), 
                                        $this->left
                                    ) 
                                ), 
                                $this->right = IF(
                                    $this->right BETWEEN ($rightId+1) AND ($rightIdP-1), 
                                    $this->right-($rightId-$leftId+1), 
                                    IF(
                                        $this->right BETWEEN $leftId AND $rightId,
                                        $this->right+($rightIdP-1-$rightId),
                                        $this->right
                                    ) 
                                ) 
                            WHERE 
                                $this->left BETWEEN $leftId AND $rightIdP OR 
                                $this->right BETWEEN $leftId AND $rightIdP 
                        ";
                    } 
                    return $this->_db->query($sql);
                }
            }
            $this->_displayError(NONEXIST_NODE, __LINE__, __FILE__, false);
            return false;
        }
        
        
        /**
        * @param int $id
        * @param $direction 
        *   NSTREE_AXIS_FOLLOWING_SIBLING
        *   NSTREE_AXIS_PRECENDING_SIBLING
        */
        function swapSiblings($id, $axis) {
            if ($siblingInfo = $this->getSiblingInfo($id, $axis)) {
                $leftIdS = $siblingInfo['left'];
                $rightIdS = $siblingInfo['right'];
                
                $nodeInfo = $this->getNodeInfo($id);
                $leftId = $nodeInfo['left'];
                $rightId = $nodeInfo['right'];
                
                $deltaS = $rightIdS - $leftIdS + 1;
                $delta = $rightId - $leftId + 1;
                
                if ($axis == NSTREE_AXIS_FOLLOWING_SIBLING) {
                    $sql = "
                        UPDATE $this->structTable
                        SET
                            $this->left = IF(
                                $this->left BETWEEN $leftId AND $rightId,
                                $this->left + $deltaS,
                                $this->left - $delta
                            ),
                            $this->right = IF(
                                $this->right BETWEEN $leftId AND $rightId,
                                $this->right + $deltaS,
                                $this->right - $delta
                            )
                        WHERE $this->left BETWEEN $leftId AND $rightIdS
                    ";
                } else {
                    $sql = "
                        UPDATE $this->structTable
                        SET
                            $this->left = IF(
                                $this->left BETWEEN $leftIdS AND $rightIdS,
                                $this->left + $delta,
                                $this->left - $deltaS
                            ),
                            $this->right = IF(
                                $this->right BETWEEN $leftIdS AND $rightIdS,
                                $this->right + $delta,
                                $this->right - $deltaS
                            )
                        WHERE $this->left BETWEEN $leftIdS AND $rightId                            
                    ";
                }
                
                return $this->_db->query($sql);
            }
            return false;
        }
        
        
        function getSiblingInfo($id, $axis) {
            if ($axis == NSTREE_AXIS_FOLLOWING_SIBLING)
                return $this->getFollowingSiblingInfo($id);
            elseif ($axis == NSTREE_AXIS_PRECENDING_SIBLING)
                return $this->getPrecendingSiblingInfo($id);
            return false;
        }
        
        
        /**
        * @param int $id
        * @return array
        */
        function getFollowingSiblingInfo($id) {
            return $this->getFollowingSibling($id, array());
        }
        
        
        /**
        * @param int $id
        * @return array
        */
        function getPrecendingSiblingInfo($id) {
            return $this->getPrecendingSibling($id, array());
        }
        
        
        /**
        * @param  int $id
        * @param  string[] $additionalData  
        * @return array
        */
        function getFollowingSibling($id, $additionalData) {
            $nodeSet = $this->select($id, $additionalData, NSTREE_AXIS_FOLLOWING_SIBLING, 1);
            if (! empty($nodeSet))
                return array_shift($nodeSet);
            return false;
        }
        

        /**
        * @param  int $id
        * @param  string[] $additionalData  
        * @return array
        */
        function getPrecendingSibling($id, $additionalData) {
            $nodeSet = $this->select($id, $additionalData, NSTREE_AXIS_PRECENDING_SIBLING, 1, "$this->left DESC");
            if (! empty($nodeSet))
                return array_shift($nodeSet);
            return false;
        }
        
        
        /**
        * @param  int $id
        * @param  int $beforeId
        * @return bool
        */
        function replaceBefore($id, $beforeId = 0) {
            if ($nodeInfo = $this->getNodeInfo($id)) {
                if (! $beforeId) 
                    return $this->swapSiblings($id, NSTREE_AXIS_PRECENDING_SIBLING);
                
                $beforeInfo = $this->getNodeInfo($beforeId);
                if ($beforeInfo['id'] != $nodeInfo['id']) {
                    $leftId  = $nodeInfo['left'];
                    $rightId = $nodeInfo['right'];
                    $level   = $nodeInfo['level'];

                    $leftIdB  = $beforeInfo['left'];
                    $rightIdB = $beforeInfo['right'];
                    $levelB   = $beforeInfo['level'];
                }                 
            }
        }
        
        /**
        * @param  int $id
        * @param  int $afterId
        * @return bool
        */
        function replaceAfter($id, $afterId = 0) {
        }
        
        function &select($id, $additionalData, $axis, $amount = null, $order = "", $identy = array(), $advjoin = null) {
            $id = (int) $id;
            $sqlIdent = $id ? "s%d.$this->id = $id" : "s%d.$this->left = 1";
			
			if (count($identy)) {
				foreach ($identy as $identy_key => $identy_value) {
					$sqlIdent .= " AND " . $identy_key . " = '" . $identy_value . "'";
				}
			}
            
            $sqlAdvSelect = "";
            if (is_array($additionalData) && (! empty($additionalData))) {
                foreach ($additionalData as $k => $name) 
                    $additionalData[$k] = "d.$name";
                $sqlAdvSelect = implode(',', $additionalData);
                unset($additionalData);
            }
         
            $sqlSelect = "
                SELECT
                s1.$this->id       									AS `id`,
                s1.$this->data_id  									AS `data_id`,
                s1.$this->left     									AS `left`,
                s1.$this->right    									AS `right`,
                s1.$this->level    									AS `level`,
                IF(s1.$this->left = s1.$this->right-1, '0', '1') 	AS `has_children`,
                s3.id 												AS `parent_id`
            ";
            if ($axis == NSTREE_AXIS_SELF) {
                $sqlIdent = sprintf($sqlIdent, 1);
                $sqlFrom = "
                    FROM $this->structTable AS s1
                    LEFT JOIN $this->structTable AS s3 ON (s3.level = s1.level - 1 AND s3.lft < s1.lft AND s3.rgt > s1.rgt)" . // для нахождения parent узла
                    ($sqlAdvSelect ? " LEFT JOIN $this->dataTable AS d ON s1.$this->data_id = d.$this->id" : "");
                $sqlWhere = "
                    WHERE $sqlIdent
                ";
            } else {
                $sqlIdent = sprintf($sqlIdent, 2);
                $sqlFrom = "
                    FROM $this->structTable AS s1
                    INNER JOIN $this->structTable AS s2 ON (%s)
                    LEFT JOIN $this->structTable AS s3 ON (s3.level = s1.level - 1 AND s3.lft < s1.lft AND s3.rgt > s1.rgt)" . // для нахождения parent узла
                    ($sqlAdvSelect ? " LEFT JOIN $this->dataTable AS d ON s1.$this->data_id = d.$this->id" : "");
                $sqlWhere = "
                    WHERE $sqlIdent
                ";
            }
            
            $stmts = array();
                
            switch ($axis) {
                case NSTREE_AXIS_CHILD:
                case NSTREE_AXIS_LEAF:
                case NSTREE_AXIS_DESCENDANT:
                case NSTREE_AXIS_DESCENDANT_OR_SELF:
                    if ($axis == NSTREE_AXIS_CHILD) {
                        $stmts[] = "s1.$this->level = s2.$this->level+1";
                	}
                    if ($axis == NSTREE_AXIS_LEAF) {
                        $stmts[] = "s1.$this->left = s1.$this->right - 1";
                    }
                    if ($axis == NSTREE_AXIS_DESCENDANT_OR_SELF) {
                    	$stmts[] = "(s1.$this->left BETWEEN s2.$this->left AND s2.$this->right)";
                    }
                    else {
                    	$stmts[] = "s1.$this->left > s2.$this->left AND s1.$this->right < s2.$this->right";
                    }

                    break;
                
                case NSTREE_AXIS_PARENT:
                	$stmts[] = "s1.$this->level = s2.$this->level-1";
                    
                case NSTREE_AXIS_ANCESTOR:
                case NSTREE_AXIS_ANCESTOR_OR_SELF:
                    if ($axis == NSTREE_AXIS_ANCESTOR_OR_SELF) {
                    	$stmts[] = "s1.$this->left <= s2.$this->left AND s1.$this->right >= s2.$this->right";
                	}
                    else {
                    	$stmts[] = "s1.$this->left < s2.$this->left AND s1.$this->right > s2.$this->right";
                    }
                        
                    break;
                
                case NSTREE_AXIS_FOLLOWING_SIBLING:
                case NSTREE_AXIS_PRECENDING_SIBLING:
                    if ($parentInfo = $this->getParentNodeInfo($id)) {
                    	$stmts[] = "s2.$this->level = s1.$this->level";
                        $stmts[] = "s1.$this->left > {$parentInfo['left']}";
                        $stmts[] = "s1.$this->right < {$parentInfo['right']}";
                        
                        if ($axis == NSTREE_AXIS_FOLLOWING_SIBLING) {
                        	$stmts[] = "s1.$this->left > s2.$this->right";
                        }
                        elseif ($axis == NSTREE_AXIS_PRECENDING_SIBLING) {
                        	$stmts[] = "s1.$this->right < s2.$this->left";
                        }
                    } else return false;
                    
                    break;
            }
         
            if ($stmts) {
            	$sqlFrom = sprintf($sqlFrom, join(' AND ', $stmts));
            }
            
            if (!is_array($advjoin)) {
            	$sql = $sqlSelect . ($sqlAdvSelect ? ", $sqlAdvSelect" : "") . $sqlFrom . $sqlWhere;
            } else {
	            /**
	             * 	Внимание! Применять только ЗНАЮЩИМ!
	             *  $advjoin вид:
	             * 	array(
	             * 		'selectPart' : 'dd.name AS name',
	             * 		'joinPart' : 'LEFT JOIN table AS dd ON dd.id = d.data_id'
	             * 	)
	             */            	
            	
				$sql = 	$sqlSelect . 
							($sqlAdvSelect ? ", $sqlAdvSelect" : "") . 
							($advjoin['selectPart'] ? ", " . $advjoin['selectPart'] : "") .
						$sqlFrom . 
							($advjoin['joinPart'] ? " " . $advjoin['joinPart'] : "") .
						$sqlWhere;
            }
                        
            $sql .= " ORDER BY s1." . ($order ? $order : $this->left);
            
            if (!is_null($amount))
                $sql .= " LIMIT " . $amount;
                
            //echo $sql."<br><br>";
            //fb($sql);

            $rsNodes = $this->_db->query($sql);
            $nodeSet = array();            
            if ($rsNodes->num_rows) { 
				while ($node = $rsNodes->fetch_assoc()) {
					$nodeSet[] = $node;
				}
            }
            return $nodeSet;
        } 



        function removeNodes($id, $removeChildren = true, $removeData = true) {
            if ($info = $this->getNodeInfo($id)) {
                $leftId = $info['left'];
                $dataId = $info['data_id'];
                $rightId = $info['right'];
                $level = $info['level'];
    
                if ($removeChildren) {
                    $childIds = array();
                    
                    $rsChilds = $this->_db->query("
                        SELECT $this->id AS `id`
                        FROM $this->structTable
                        WHERE $this->left BETWEEN $leftId AND $rightId
                    ");
                    if ($rsChilds->num_rows) {
                        while ($child = $rsChilds->fetch_assoc()) {
                            $childIds[]  = $child['id'];
                        }
                        unset($child);
                    }
                    
                    $rsDChilds = $this->_db->query("
                        SELECT t2.$this->data_id FROM $this->structTable AS t1
                        LEFT JOIN $this->structTable AS t2 ON t1.$this->id = t2.$this->id AND t2.$this->left BETWEEN $leftId AND $rightId
                        GROUP BY t1.$this->data_id HAVING SUM( IF(t2.$this->data_id IS NULL , 1, 0) ) = 0 
                    ");
                    if ($rsDChilds->num_rows) {
                        while ($child = $rsDChilds->fetch_assoc())
                            $childDIds[]  = $child['data_id'];
                        unset($child);
                    }
                    
                    if (! empty($childDIds)) {
                        $child = implode(',', $childDIds);
                        // Deleting record(s)
                        if($removeData){
                        	$this->_db->query(
                        	"
                            	DELETE FROM $this->dataTable 
                            	WHERE $this->id IN ($child)
                        	");
                        }
                    }
                    
                    if (! empty($childIds)) {
                        $child = implode(',', $childIds);
                        
                        // Deleting record(s)
                        $this->_db->query("
                            DELETE FROM $this->structTable 
                            WHERE $this->id IN ($child)
                        ");
                        
                        // Clearing blank spaces in a tree
                        $deltaId = ($rightId - $leftId) + 1;
                        return $this->_db->query("
                            UPDATE $this->structTable
                            SET 
                                $this->left = IF(
                                    $this->left > $leftId,
                                    $this->left - $deltaId,
                                    $this->left
                                ),
                                $this->right = IF(
                                    $this->right > $leftId,
                                    $this->right - $deltaId,
                                    $this->right
                                )
                            WHERE $this->right > $rightId 
                        ");
                    }
                    return false;
                } else {
                    $rsDChilds = $this->_db->query("
                        SELECT t2.$this->data_id FROM $this->structTable AS t1
                        LEFT JOIN $this->structTable AS t2 ON t1.$this->id = t2.$this->id AND t2.$this->id='$id'
                        GROUP BY t1.$this->data_id HAVING SUM( IF(t2.$this->data_id IS NULL , 1, 0) ) = 0 
                    ");
                    if ($rsDChilds->num_rows) {
                        $child = $rsDChilds->fetch_assoc();
                        if($removeData){
                        	$this->_db->query("
                        		DELETE 
                        		FROM $this->dataTable 
                        		WHERE $this->id = '{$child['data_id']}'"
                       		);
                        }
                    }
                    $this->_db->query("DELETE FROM $this->structTable WHERE $this->id = '$id'");
                    
                    return $this->_db->query("
                        UPDATE $this->structTable
                        SET
                            $this->left = IF(
                                $this->left BETWEEN $leftId AND $rightId,
                                $this->left-1,
                                $this->left
                            ),
                            $this->right = IF(
                                $this->right BETWEEN $leftId AND $rightId,
                                $this->right-1,
                                $this->right
                            ),
                            $this->level = IF(
                                $this->left BETWEEN $leftId AND $rightId,
                                $this->level-1,
                                $this->level
                            ),
                            $this->left = IF(
                                $this->left > $rightId,
                                $this->left-2,
                                $this->left),
                            $this->right = IF(
                                $this->right > $rightId,
                                $this->right-2,
                                $this->right
                            )
                        WHERE $this->right > $leftId
                    ");
                }
            }
            $this->_displayError(NONEXIST_NODE, __LINE__, __FILE__, false);
            return false;
        }

        /**
        * Returns all child nodes that has no childs
        *
        * @param  int $id
        * @param  string[] $additionalData           
        * @return array
        */
        function enumLeafs($id, $additionalData) {
            return $this->select($id, $additionalData, NSTREE_AXIS_LEAF);
        }
    }
