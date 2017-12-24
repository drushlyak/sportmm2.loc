<?php
//======================================================
class tePhotoTemplate extends teTemplate
{
    
//-----------------------------------------
    function __construct()
    {
        $args = func_get_args();
        parent::__construct($args[0], $args[1]);
        return true;
    }   
//-----------------------------------------
    public function getCode()
    {
        $this->code = '<link media="screen" type="text/css"  href="css/template.css" rel="stylesheet">
						<link media="screen" type="text/css" href="css/lightbox.css"rel="stylesheet">
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
						<script type="text/javascript" src="/js/libs/jquery-lightbox.js"></script>
						<script>var $jq = jQuery.noConflict();</script>
						<script src="/js/libs/scripts.js" type="text/javascript"></script>';
        
        // Определим номер текущей страницы
        $pgnm = ($this->c_this['page'][$this->param_te_value['id']]['pagenum']) ? $this->c_this['page'][$this->param_te_value['id']]['pagenum'] : 1;
        
        // Выберем по шаблонной переменной группу фотографий
        $sql = sql_placeholder("SELECT fg.* FROM " . CFG_DBTBL_MOD_PHOTO_GRDATA." AS fg WHERE fg.id_te_value=?", $this->teValue->getTeValueId());
        $rsFotoGr = $this->_db->get_row($sql);

        
        if (is_array($rsFotoGr)) {
            
            // Построим список изображений
            $sql = sql_placeholder("SELECT ph.* FROM " . CFG_DBTBL_MOD_PHOTO . " AS ph WHERE ph.id_album = ? ORDER BY ph.pos", $rsFotoGr['id']);
            $foto_complet = $this->_db->get_all($sql);
            
            // Если фотографий больше чем заданно разделе то с формируем маршрутизатор страниц
            if($rsFotoGr['count_per_page'] < count($foto_complet)){
            	$num_element = count($foto_complet);
				$n_p = $num_element / $rsFotoGr['count_per_page'];
				$num_page = ($n_p == 1) ? 1 : ((ctype_digit(trim($n_p))) ? $n_p : intval($n_p + 1)); //количество страниц
                // Определим номер, кол-во страниц и построим маршрутизатор
                //$count_pg = ceil((count($foto_complet) - $rsFotoGr['count_per_page'])/$rsFotoGr['count_per_page'])+1;
                $str_router .= '<div style="height:40px;margin:0 auto;">
					                <table style="width: 100%; height: 21px;" cellpadding="0" cellspacing="0">
					                    <tbody><tr>
					                        <td style="width: 43px;" valign="top">
					                            <div style="height:26px"><div id="gallery_prev_page"></div></div>
					                        </td>
					                        <td align="center" valign="top">
					                            <div id="gallery_numb_page">';
					                            	
					                            	for($y=1;$y<=$num_page;$y++) {
					                            		$str_router .= '<div style="margin-right:10px;" class="' . (($y == 1) ? "sel_page" : "unsel_page") . '">' . $y . '</div>';
					                            	}
													
				$str_router .=                  '</div>
					                        </td>
					                        <td style="width: 43px;" valign="top">
					                            <div style="height:26px"><div style="margin-top: 0px;" id="gallery_next_page"></div></div>
					                        </td>
					                    </tr>
					                </tbody></table>
    							</div>';
            }else{ 
                $str_router = "";
            }
            
            $code_in .= '<div class="contetnt_box">
						    <div id="object_gallery" style="min-height: 200px;">
						    	<div id="page_id_0" class="one_page_gallery" style="display: block;">';
            $i = 1;
            if (is_array($foto_complet)) {
                foreach ($foto_complet as $foto) {                  
                    $code_in .= '<div class="one_img_gallery">
			                        <div>
			                            <img src="' . $foto['path'] . '" alt="" class="img-max-height">
			                        </div>
			                        <a class="one_img_gallery_hover" href="' . $foto['path_orig'] . '" title="' . $foto['alt_text'] . '">
			                            <div>
			                                <span class="gallery_description">' . $foto['alt_text'] . '</span>
			                                <span class="gallery_date"></span>
			                            </div>
			                        </a>
			                    </div>';
                    if($i%$rsFotoGr['count_per_page'] == 0 && $i != $num_element){
                        $code_in .= '</div><div id="page_id_' . $i . '" class="one_page_gallery" style="display: none;">';
                    }
                    $i++;
                }
                $code_in .= '</div>';
            }
        }
        $this->code .= $code_in . '</div>' . $str_router . '</div>';
        return $this->code;
    }
//--------------------------------------------------------------------
    function makeCode($text, $foto, $ptv, $addr)
    {
        $name = $this->c_lng->Gettextlng($foto['name']);
        $span_name = ($name) ? "$name" : ""; 
        $text = preg_replace("/{fototitle}/i", $span_name, $text);
        $text = preg_replace("/{fotodate}/i", $foto['idate'], $text);
        $text = preg_replace("/{fototime}/i", "", $text);
        $text = preg_replace("/{fotodescr}/i", $this->c_lng->Gettextlng($foto['description']), $text);
        $text = preg_replace("/{fotoimage}/i", $foto['img'], $text);
        $text = preg_replace("/{fototmb}/i", $foto['tmb'], $text);
        $text = preg_replace("/{fotoid}/i", $foto['id'], $text);
        $img  = BASE_PATH.$foto['url']."_o.".$foto['exten'];
        if(file_exists($img)){
            $pictu_info = getimagesize($img);
            clearstatcache();
            $width  = $pictu_info[0];
            $height = $pictu_info[1];
            $text   = preg_replace("/{fotolink}/i", "<a href=\"{$foto['url']}_n.{$foto['exten']}\"  return false;\" style=\"text-decoration:none;\">", $text);
            $text   = preg_replace("/{_fotolink}/i", "</a>", $text);
        }else{
//          $text = preg_replace("/{fotoimage}/i", "", $text);
        }       
        return $text;
    }
//---------------------------------------------------------------------
}

?>