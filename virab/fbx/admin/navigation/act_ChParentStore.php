<?php

$id = intval($attributes['id']);
$parent_id = intval($attributes['parent_id']);
$sTree->replaceNode($id, $parent_id);
Location($_XFA['main'], 0);

?>
