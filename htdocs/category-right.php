<?php 
	if(is_array($this->c_this['value'])){
          foreach($this->c_this['value'] as $elem){              
          	   if(is_integer(strpos($elem, 'group:'))){
                    $group = intval(substr($elem, 6));
                    if($group < 1000) {
                    	$cat_id = $group;
                    }
               }              
          }
     }

     $cat_array = $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_CATEGORY . " WHERE is_menu = 1 ORDER BY ord");
     
?>


<div class="sidebar-wrapper">
	<div class="sidebar-category">
    	<div class="title">категории</div>
            <ul>
            <?php
            	if(is_array($cat_array)) {
            		foreach($cat_array as $cat) {
            		    if (intval($cat['is_menu_mc']) == 1) {
                            $mc_name = $db->get_one("SELECT name,subcategory_cpu_url FROM " . CFG_DBTBL_DICT_MAIN_CATEGORY . " WHERE id = ?", $cat['id_main_category']);
                            $cat_name = $mc_name . ' / ' . $cat['name'];
                            $subcategoryCpuUrl = $mc_name ['subcategory_cpu_url'];
                        } else {
                            $cat_name = $cat['name'];
                            $subcategoryCpuUrl = $cat ['subcategory_cpu_url'];

                            if (!empty ($subcategoryCpuUrl)) {  //добавляем суффикс из ЧПУ из базы к ссылк ена категорию
                                $subcategoryCpuUrl = "-".$subcategoryCpuUrl;
                            }
                        }



						echo ((isset($cat_id) && $cat['id'] == $cat_id) ? '<li class="active">' . $cat_name . '</li>' : '<li><a href="/catalog/group:' . $cat['id'] .$subcategoryCpuUrl. '/">' . $cat_name . '</a></li>');
            		}
            	} else {
            ?>
            	<li>нет категорий</li>
            <?php 	
            	}
            ?>
            </ul>
	</div>
</div>