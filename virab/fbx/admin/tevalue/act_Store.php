<?

$id       = intval($attributes['id']);
$pg       = intval($attributes['pg']);
$count_pg = intval($attributes['count_pg']);
$FORM_ERROR = "";
$name           = $attributes['name'];
$file           = $attributes['file'];
if(!$name){
	$FORM_ERROR .= _("Необходимо указать название переменной");
}
if(!$FORM_ERROR){
	$description = $lng->SetTextlng($attributes['description']);
	if($id){
		$sql = sql_placeholder("
			UPDATE ".CFG_DBTBL_TE_VALUE."
			SET name=?, description=?, file=?
			WHERE id=?",
			$name, $description, $file, $id
		);
	}else{
		$sql = sql_placeholder("
			INSERT INTO ".CFG_DBTBL_TE_VALUE."
			SET name=?, description=?, file=?",
			$name, $description, $file
		);
	}
	$db->query($sql);
	Location(sprintf($_XFA['main'], $pg, $count_pg, ""), 0);
}else{
	include ("qry_Form.php");
	include ("dsp_Form.php");
}

?>