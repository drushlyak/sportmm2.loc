<?php

    define('VIRAB_PRO', true);
    
    require_once ("conf/core.cfg.php");
    require_once (LIB_PATH . "/Common.php"); 

    
    $products = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT);
    
    $header = "id" . Chr(9) . "name" . Chr(9) . "article" . Chr(9) . "cost" . Chr(9) . "description" . Chr(9) . "photo" . Chr(9) . "chpu" . Chr(9) . "producer\n";
    
    if (is_array($products)) {
        foreach ($products as $product) {
            $content .= $product['id']
             . Chr(9) . iconv("UTF-8", "WINDOWS-1251", $product['name'])
             . Chr(9) . iconv("UTF-8", "WINDOWS-1251", $product['article'])
             . Chr(9) . $product['cost_excess']
             . Chr(9) . iconv("UTF-8", "WINDOWS-1251", str_replace("\n", ' ', str_replace("\r", ' ', str_replace(Chr(9), ' ', $product['description']))))
             . Chr(9) . $product['main_foto_orig']
             . Chr(9) . $product['chpu']
             . Chr(9) . $product['producer'] . "\n";
        }
    }

    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=pitanie.csv");
    
    echo $header . $content;