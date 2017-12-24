<?php

    class STree {
    
        var $dataTable;
        
        var $id;
        
        var $parent_id;
        
        var $ord;
        
        var $level;
        
        function __construct($dataTableName, $fieldNames) {
            if (! $dataTableName = trim($dataTableName))
                trigger_error($this->__throw__(
                    sprintf(ERR_NSTREE_INVALID_TABLE, $dataTableName)
                ), E_USER_ERROR);
            $this->dataTable = $dataTableName;
            
            $tblFields = array('id', 'parent_id', 'ord');
            
            if (sizeof($fieldNames) != 3)
                trigger_error($this->__throw__(
                    ERR_NSTREE_NOT_ENOUGTH_PARAMS), E_USER_ERROR
                );

            foreach ($fieldNames as $k => $v) {
                if (! in_array($k, $tblFields))
                    trigger_error($this->__throw__(
                        sprintf(ERR_NSTREE_INVALID_TABLE_COLUMN, $k)
                    ), E_USER_ERROR);
                    
                eval('$this->'.$k.'="'.$v.'";');
            }
            $this->level = 0;
        }
        
        function __throw__($err) {
            return '<b>Exception</b> in class '.get_class($this).'<br/>'.$err;
        }
        
        function getNode($id, $additionalFields) {
            $db = getDBInstance();
            
            $sqlAdvSelect = '';
            if (is_array($additionalFields) && !empty($additionalFields)) {
                foreach ($additionalFields as $k => $name)
                    $additionalFields[$k] = 'd.'.$name;
                $sqlAdvSelect = implode(', ', $additionalFields);
            }
            
            $rsNode = $db->query("
                SELECT
                    d.$this->id        AS `id`,
                    d.$this->parent_id AS `parent_id`,
                    d.$this->ord AS 'ord'".
                    ($sqlAdvSelect ? ", $sqlAdvSelect" : "")."
                FROM
                    {$this->dataTable} AS d
                WHERE
                    d.{$this->id} = {$id}
                LIMIT 1
            ");
            if ($rsNode->num_rows)
                return $rsNode->fetch_assoc(); 
            return false;
        }
        
        function clear () {
            $db = getDBInstance();
            
            $db->query("TRUNCATE {$this->dataTable}");
        }
        
        function updateNode($id, $data) {
            if (! is_array($data)) return false;
            if (! $id = intval($id)) return false;
            
            $db = getDBInstance();
            
            // preparing data to be inserted
            foreach ($data as $n => $value)
                $sqlSet[] = "$n=?$n";
            $sqlSet = implode(', ', $sqlSet);
            
            $sql = sql_placeholder("UPDATE {$this->dataTable} SET {$sqlSet} WHERE {$this->id} = {$id}", $data);
            
            // insert data
            return $db->query($sql);
        }
        
        function &selectTNodes($id, $additionalData) {
            return $this->select($id, $additionalData, NSTREE_AXIS_DESCENDANT_OR_SELF);
        }
        
        function swapSiblings($id, $axis) {
            $db = getDBInstance();
        
            if ($sibling = $this->getSibling($id, $axis)) {
                
                $SiblingId  = $sibling[$this->id];
                $siblingData = $this->select($SiblingId, array($this->ord), NSTREE_AXIS_SELF);
                $SiblingOrd = $siblingData[0][$this->ord];
                
                if ($axis == NSTREE_AXIS_FOLLOWING_SIBLING) {
                    $NewSiblingOrd = $SiblingOrd-1;
                    $db->query("UPDATE $this->dataTable
                                SET
                                    $this->ord = {$NewSiblingOrd}
                                WHERE $this->id = {$SiblingId}
                    ");
                    $db->query("UPDATE $this->dataTable
                                SET
                                    $this->ord = {$SiblingOrd}
                                WHERE $this->id = {$id}
                    ");
                } else {
                    $NewSiblingOrd = $SiblingOrd+1;
                    $db->query("UPDATE $this->dataTable
                                SET
                                    $this->ord = {$NewSiblingOrd}
                                WHERE $this->id = {$SiblingId}
                    ");
                    $db->query("UPDATE $this->dataTable
                                SET
                                    $this->ord = {$SiblingOrd}
                                WHERE $this->id = {$id}
                    ");
                }
                return true;
            }
            return false;
        }
        
        function getSibling($id, $axis) {
            if ($axis == NSTREE_AXIS_FOLLOWING_SIBLING)
                return $this->getFollowingSibling($id);
            elseif ($axis == NSTREE_AXIS_PRECENDING_SIBLING)
                return $this->getPrecendingSibling($id);
            return false;
        }
        function getFollowingSibling($id, $additionalData) {
            $nodeSet = $this->select($id, $additionalData, NSTREE_AXIS_FOLLOWING_SIBLING, 1);
            if (! empty($nodeSet))
                return array_shift($nodeSet);
            return false;
        }
        
        function getPrecendingSibling($id, $additionalData) {
            $nodeSet = $this->select($id, $additionalData, NSTREE_AXIS_PRECENDING_SIBLING, 1, "$this->ord DESC");
            if (! empty($nodeSet))
                return array_shift($nodeSet);
            return false;
        }
        
        /**
        * Выбирает узлы дерева. 
        * Поиск ведётся относительно узла заданного $id. $axis может принимать следующие значения:
        *
        * NSTREE_AXIS_CHILD                 все дочерние элементы данного узла
        * NSTREE_AXIS_CHILD_OR_SELF         все дочерние элементы данного узла
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
        function &select($id, $additionalData, $axis, $amount = null, $order = "") {
          $this->level++;
          $db = getDBInstance();
          
          $sqlAdvSelect = "";
          if (is_array($additionalData) && (! empty($additionalData))) {
              foreach ($additionalData as $k => $name)
                  $additionalData_in[$k] = "d.$name";
              $sqlAdvSelect = implode(',', $additionalData_in);
          }
          
          $sqlSelect = "SELECT
                           d.$this->id AS `id`".
                           ($sqlAdvSelect ? ", $sqlAdvSelect" : "")."
                        FROM
                           $this->dataTable AS d
                        WHERE ";
          switch ($axis) {
            case NSTREE_AXIS_DESCENDANT:
            case NSTREE_AXIS_CHILD:
            case NSTREE_AXIS_LEAF:
                $sqlSelect .= "d.$this->parent_id ".($id <> 0 ? "= {$id}" : "is NULL OR d.$this->parent_id = 0");
            break;
            
            case NSTREE_AXIS_CHILD_OR_SELF:
            case NSTREE_AXIS_DESCENDANT_OR_SELF:
                $sqlSelect .= "d.$this->parent_id ".($id <> 0 ? "= {$id}" : "is NULL OR d.$this->parent_id = 0")." OR d.$this->id = {$id}";
            break;
            
            case NSTREE_AXIS_SELF:
                $sqlSelect .= "d.$this->id = {$id}";
            break;
            
            case NSTREE_AXIS_PARENT:
            case NSTREE_AXIS_ANCESTOR:
                $rsNodes_in = $db->query("
                          SELECT
                              d.$this->parent_id AS `id`
                          FROM
                              $this->dataTable AS d 
                          WHERE
                              d.$this->id = {$id}
                ");
                if ($node_in = $rsNodes_in->fetch_assoc())
                    $sqlSelect .= "d.$this->id = {$node_in['parent_id']}";
                else return false;
            break;
            
            case NSTREE_AXIS_ANCESTOR_OR_SELF:
                $rsNodes_in = $db->query("
                          SELECT
                              d.$this->parent_id AS `id`
                          FROM
                              $this->dataTable AS d 
                          WHERE
                              d.$this->id = {$id}
                ");
                if ($node_in = $rsNodes_in->fetch_assoc())
                    $sqlSelect .= "d.$this->id = {$node_in['parent_id']} OR";
                $sqlSelect .= "d.$this->id = {$id}";
            break;
            
            case NSTREE_AXIS_FOLLOWING_SIBLING:
            case NSTREE_AXIS_PRECENDING_SIBLING:
                $rsNodes_in = $db->query("
                          SELECT
                              d.$this->parent_id AS `parent_id`, d.$this->ord AS 'ord'
                          FROM
                              $this->dataTable AS d 
                          WHERE
                              d.$this->id = {$id}
                ");
                if ($node_in = $rsNodes_in->fetch_assoc()) {
                    $sqlSelect .= ($node_in['parent_id'] && $node_in['parent_id'] <> 0) ? "d.$this->parent_id = {$node_in['parent_id']} " : "(d.$this->parent_id = 0 OR d.$this->parent_id is NULL) ";
                    
                    if ($axis == NSTREE_AXIS_FOLLOWING_SIBLING)
                        $sqlSelect .= "AND d.$this->ord > {$node_in['ord']}";
                    elseif ($axis == NSTREE_AXIS_PRECENDING_SIBLING)
                        $sqlSelect .= "AND d.$this->ord < {$node_in['ord']}";
                } else return false;
            break;
          }
          
          $sqlSelect .= " ORDER BY d." . ($order ? $order : $this->ord);
          
          if (! is_null($amount))
              $sqlSelect .= " LIMIT " . intval($amount);
              
          $rsNodes = $db->query($sqlSelect);
          if ($rsNodes->num_rows) {
              $nodeSet = array();
              while ($node = $rsNodes->fetch_assoc()) {
                  $rsNodes_in = $db->query("
                            SELECT
                                d.$this->id AS `id`
                            FROM
                                $this->dataTable AS d 
                            WHERE
                                d.$this->parent_id = {$node['id']}
                  ");
                  $node['has_children'] = ($rsNodes_in->num_rows) ? 1 : 0;
                  if ($axis == NSTREE_AXIS_LEAF && $node['has_children'])
                      continue;
                  if ($axis == NSTREE_AXIS_DESCENDANT_OR_SELF)
                      $axis = NSTREE_AXIS_DESCENDANT;
                  if ($axis == NSTREE_AXIS_ANCESTOR_OR_SELF)
                      $axis = NSTREE_AXIS_ANCESTOR;
                  $node['level'] = $this->level;
                  $nodeSet[] = $node;
                  if (!$node['has_children'] || $axis == NSTREE_AXIS_SELF || $axis == NSTREE_AXIS_PARENT || (($axis == NSTREE_AXIS_CHILD_OR_SELF || $axis == NSTREE_AXIS_CHILD) && $node['has_children']))
                      continue;
                  $nodeSet_in = &$this->select($node['id'], $additionalData, $axis, $amount, $order);
                  if ($nodeSet_in)
                      foreach ($nodeSet_in as $node_in)
                          $nodeSet[] = $node_in;
              }
              $this->level--;
              return $nodeSet;
          }
          $this->level--;
          return false;
        }
        
        // Отображение содержимого БД не доделано.
        function dump($titleField) {
            $db = getDBInstance();
            
            $rsNodes = $db->query("
                SELECT d.{$titleField} AS title
                FROM 
                    {$this->dataTable} AS d
                ORDER BY {$this->ord}
            ");
            
            if ($rsNodes->num_rows) {
                $indent = 16;
                while ($node = $rsNodes->fetch_assoc()) {
                    if (! $node['title']) {
                        if ($node[$this->left] == 1)
                            $node['title'] = '#root';
                        else 
                            $node['title'] = 'Unnamed node';
                    }
                    
                    // output node
                    ?>
                    <div style="padding-left:<?=($indent*$node[$this->level])?>px">
                        <?=$node['title']?>
                        (id:    <?=$node[$this->id]?>;
                         parent_id:  <?=$node[$this->parent_id]?>;
                         ord: <?=$node[$this->ord]?>;
                         level: <?=$node[$this->level]?>)</div>
                    
                    
                    <?php
                }
            }
        }
        
        function getParentNode($id, $additionalData = array()) {
            $nodeSet = $this->select($id, $additionalData, NSTREE_AXIS_PARENT);
            if (! empty($nodeSet)) {
                return $nodeSet[0];
            }
            return false;
        }
        
        // Пересчет параметра Ord начиная с $ord
        // STREE_AXIS_FOLLOWING    увеличение индекса на единицу
        // STREE_AXIS_PRECENDING   уменьшение индекса на единицу
        function calcOrd($ord, $axis) {
            $db = getDBInstance();
                
            $rsOrd = $db->query("
                SELECT $this->id AS 'id', $this->ord AS 'ord'
                FROM
                    {$this->dataTable}
                WHERE $this->ord >= {$ord} ORDER BY $this->ord DESC
            ");
            while ($one_ord = $rsOrd->fetch_assoc()) {
                $neword = $one_ord['ord']+$axis;
                $rsOrd_in = $db->query("
                    UPDATE {$this->dataTable}
                    SET $this->ord = $neword
                    WHERE $this->id = {$one_ord['id']}
                ");
            }
            return true;
        }
        
        // Определение последнего Ord для указанного родительского элемента
        function lastOrd($parentId) {
            $db = getDBInstance();
            
            $rsNodes = $db->query("
                SELECT $this->id AS 'id', $this->ord AS 'ord'
                FROM 
                    {$this->dataTable}
                WHERE $this->parent_id ".($parentId<>0 ? "= {$parentId}" : "= {$parentId} OR {$this->parent_id} IS NULL")."
                ORDER BY $this->ord DESC
                LIMIT 1
            ");
            if ($rsNodes->num_rows) {
                $node = $rsNodes->fetch_assoc();
                $resOrd = $this->lastOrd($node['id']);
                return max($node['ord'], $resOrd);
            }
            return false;
        }
        
        function appendChild($parentId, $data, $dataId) {
            $parentId = intval($parentId);
            if (! is_array($data)) $data = array();
            $db = getDBInstance();
            
            if (!$node['ord'] = $this->lastOrd($parentId)) {
                if ($parentId) {
                    $rsNodes = $db->query("
                        SELECT {$this->ord} AS 'ord'
                        FROM 
                            {$this->dataTable}
                        WHERE $this->id = {$parentId}
                    ");
                    $node = $rsNodes->fetch_assoc();
                } else
                    $node['ord'] = 0;
            }
                
            $ordId = $node['ord']+1;
             
            // recalculate Ord
            $this->calcOrd($ordId, STREE_AXIS_FOLLOWING);
            
            // preparing data to be inserted
            foreach ($data as $n => $value)
                $sqlInsert[] = "$n=?$n";
            $sqlInsert = implode(', ', $sqlInsert);
            
//                    $this->id        = '',
            $sql = sql_placeholder("
                INSERT INTO {$this->dataTable} 
                SET 
                    $this->parent_id = $parentId,
                    $this->ord       = $ordId,
                    {$sqlInsert}
            ", $data);
            
            // insert structure & data
            $db->query($sql);
            
            $id = $db->insert_id;
            
            return $id;
        }

        function replaceNode($id, $newParentId) { 
            $db = getDBInstance();
            
            $rsNodes = $db->query("UPDATE {$this->dataTable} SET $this->parent_id = $newParentId WHERE $this->id = $id");
        }
        
        function removeNode($id) {
            $db = getDBInstance();
            
            $rsNodes = $db->query("
                SELECT $this->parent_id AS `parent_id`, $this->ord AS 'ord'
                FROM $this->dataTable
                WHERE $this->id = '$id'
            ");
            if ($rsNodes->num_rows) {
                $node = $rsNodes->fetch_assoc();
                 
                $rsChilds = $db->query("
                    SELECT $this->id AS `id`
                    FROM $this->dataTable
                    WHERE $this->parent_id = '$id'
                ");
                if ($rsChilds->num_rows)
                    while ($child = $rsChilds->fetch_assoc())
                        $db->query("
                            UPDATE {$this->dataTable}
                            SET $this->parent_id = {$node['parent_id']}
                            WHERE $this->id = {$child['id']}
                        ");
                
                $this->calcOrd($node['ord'], STREE_AXIS_PRECENDING);
                
                $db->query("DELETE FROM $this->dataTable WHERE $this->id = '$id'");
            }
        }
        
        // Возвращает массив индексов всех дочерних элементов
        function getChilds($id) {
            $db = getDBInstance();

            $rsChilds = $db->query("
                SELECT $this->id AS `id`
                FROM $this->dataTable
                WHERE $this->parent_id = '$id'
                ORDER BY $this->ord
            ");
            if ($rsChilds->num_rows) {
                while ($child = $rsChilds->fetch_assoc()) {
                    $childIds[] = $child['id'];
                    $childIds_in = $this->getChilds($child['id']);
                    if ($childIds_in)
                        foreach ($childIds_in as $child_in)
                            $childIds[] = $child_in;
                }
                return $childIds;
            } else
                return false;
        }
        
        // Удаление всех дочерних элементов
        function removeChilds($id) {
            $db = getDBInstance();
            
            $childIds = $this->getChilds($id);
            if (! empty($childIds)) {
                $child = implode(',', $childIds);
                
                // Deleting record(s)
                $db->query("
                    DELETE FROM $this->dataTable 
                    WHERE $this->id IN ($child)
                ");
                
                $rsNode = $db->query("
                    SELECT $this->parent_id AS `parent_id`, $this->ord AS 'ord'
                    FROM $this->dataTable
                    WHERE $this->id = '$id'
                ");
                if ($rsNode->num_rows) {
                    $node = $rsNode->fetch_assoc();
                    $ord = $node['ord'];
                    $rsNode = $db->query("
                        SELECT $this->ord AS 'ord'
                        FROM $this->dataTable
                        WHERE $this->parent_id = '{$node['parent_id']}' AND $this->ord > $ord
                        ORDER BY $this->ord
                    ");
                    if ($rsNode->num_rows) {
                        $node = $rsNode->fetch_assoc();
                        $neword = $node['ord']-$ord+1;
                        $this->calcOrd($node['ord'], $neword);
                    }
                }
                return true;
            } else
                return false;
        }
        
    }
