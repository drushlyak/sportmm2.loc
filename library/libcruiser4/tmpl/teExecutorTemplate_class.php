<?php
//======================================================
class teExecutorTemplate extends teTemplate
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
		global $lng, $mnTree, $nsTree, $db;
		$sql = sql_placeholder("
			SELECT * FROM ".CFG_DBTBL_TE_CONTDATA."
			WHERE id_te_value=?", $this->teValue->getTeValueId()
		);
		$contaner_data = $this->_db->get_row($sql);

		if($contaner_data){
			$contaner_vis = (($contaner_data['printable'] == TE_PRINT_HTML && ! $this->c_this['printable']) || ($contaner_data['printable'] == TE_PRINT_PRINTABLE && $this->c_this['printable']) || $contaner_data['printable'] == TE_PRINT_ALL) ?  1 : 0;
			switch($contaner_data['type_executor']){
// Простой текст
				case TE_EXECUTOR_SIMPLE:
// WYSIWYG - элемент
				case TE_EXECUTOR_WYSIWYG:
					$tbl = ($contaner_data['type_executor'] == TE_EXECUTOR_SCREEN_WYSIWYG)?CFG_DBTBL_TE_EXECWCODE:CFG_DBTBL_TE_EXECSCODE;
// Определим кол-во страниц
					$sql = sql_placeholder("
						SELECT count(e.id) as cnt
						FROM ".$tbl." AS e,
							".CFG_DBTBL_TE_CONTTREE." AS t
						WHERE e.id_executor=t.id
							AND t.data_id=?
							AND e.id_map=?",
						$contaner_data['id'], $this->c_this['page']['id']
					);
					$counter = $this->_db->get_row($sql);
					if($counter){
// Если эта страница многостранична и данный исполнитель многостраничен
						if($counter['cnt'] > 1){
// Определим номер страницы
							$pgnm = ($this->c_this['page'][$contaner_data['id']]['typeblock'] == TMPL_TYPE_CONTAINER && $this->c_this['page'][$contaner_data['id']]['pagenum']) ? $this->c_this['page'][$contaner_data['id']]['pagenum'] : 1;
//  Построим маршрутизатор страниц
							$str_router = routerPageStr(getAdrPage($this->c_this['page']['addr']), $pgnm, $counter['cnt'], $this->c_this['arg'], "c".$contaner_data['id']);
							$sql = sql_placeholder("
								SELECT e.text FROM ".$tbl." AS e,
									".CFG_DBTBL_TE_CONTTREE." AS t
								WHERE e.id_map=?
									AND e.id_executor=t.id
									AND t.data_id=?
									AND e.page=?",
								$this->c_this['page']['id'], $contaner_data['id'], $pgnm
							);
							$codeexec = $this->_db->get_row($sql);
							if($codeexec){
								if($this->c_this['page']['article'] != 'text' || ! $contaner_data['content']){
 									$this->code = $codeexec ? $str_router."<div>".$lng->Gettextlng($codeexec['text'])."</div>".$str_router : "";
								}else{
   	 					 			preg_match_all("/(?<={)([a-zA-Z0-9_-]+)(?=})/i", $lng->Gettextlng($codeexec['text']), $matches);
			    				  	foreach($matches[0] as $match){
		 				   			 	$this->code .= $codeexec ? "{".$match."}" : "";
  									}
								}
								$this->code = $codeexec ? $str_router."<div>".$lng->Gettextlng($codeexec['text'])."</div>".$str_router : "";
							}
						}else{
							$sql = sql_placeholder("
								SELECT e.text FROM ".$tbl." AS e,
									".CFG_DBTBL_TE_CONTTREE." AS t
								WHERE id_map=?
									AND e.id_executor=t.id
									AND t.data_id=?", $this->c_this['page']['id'], $contaner_data['id']
							);
							$codeexec = $this->_db->get_row($sql);
							if($this->c_this['page']['article'] != 'text' || ! $contaner_data['content']){
 								$this->code = $codeexec ? $lng->Gettextlng($codeexec['text']) : "";
							}else{
   						 		preg_match_all("/(?<={)([a-zA-Z0-9_-]+)(?=})/i", $lng->Gettextlng($codeexec['text']), $matches);
			   				  	foreach($matches[0] as $match){
						   			 $this->code .= $codeexec ? "{".$match."}" : "";
			   				  	}
							}
						}
					}
// Если контентные шаблоны не заполненны информацией (без учета HTML тегов) то выполняем определенное для узла действие
					if($contaner_data['content'] && ! strip_tags($this->code)){
						mkGoTo();
					}
					break;
// Данные из файла
				case TE_EXECUTOR_FILE:
					ob_start("EmptyFunc");
					include(BASE_PATH . '/' . $contaner_data['code']);
					$this->code = ob_get_contents();
					ob_end_clean();
					break;
// Код в базе данных
				case TE_EXECUTOR_CODE:
					$this->code = $this->c_lng->Gettextlng($contaner_data['code']);
					break;
// URL в базе данных
				case TE_EXECUTOR_URL:
					$this->code = "";
					break;
			}
// Если данный исполнитель выводится на экран не должен то оставим в нем только шаблонные переменные
			if(!$contaner_vis){
				preg_match_all("/(?<={)([a-zA-Z0-9_-]+)(?=})/i", $this->code, $matches);
				$this->code = "";
				foreach($matches[0] as $match){
					$this->code .= "{".$match."}";
				}
			}
		}
		return $this->code;
	}
//----------------------------------------
}

?>