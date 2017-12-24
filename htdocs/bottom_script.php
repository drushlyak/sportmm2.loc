<?php 
	if(isset($this->c_this['value']) && is_array($this->c_this['value'])){
          foreach($this->c_this['value'] as $elem){
          	   if(is_integer(strpos($elem, 'min:'))){
                    $min = intval(substr($elem, 4));
               }
          	   if(is_integer(strpos($elem, 'max:'))){
                    $max = intval(substr($elem, 4));
               }
          }
     }
     
$max_bd = $db->get_one("SELECT MAX(cost_excess) FROM " . CFG_DBTBL_MOD_PRODUCT);
$min_bd = $db->get_one("SELECT MIN(cost_excess) FROM " . CFG_DBTBL_MOD_PRODUCT);
$max = (isset($max) && $max) ? $max : $max_bd;
$min = (isset($min) && $min) ? $min : $min_bd;

?>
<script src="/js/jquery.js"></script>
<script src="/js/jquery-ui.js"></script>
<script src="/js/jcarousellite.min.js"></script>
<script src="/js/combobox.js"></script>
<script src="/js/jscrollpane.js"></script>
<script src="/js/mousewheel.js"></script>
<script src="/js/scripts.js"></script>
<script src="/js/cookie.js"></script>
<script src="/js/slides.js"></script>
<script src="/js/place_mark.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	  $( "#slider-range" ).slider({
	    range: true,
	    min: <?=$min_bd?>,
	    max: <?=$max_bd?>,
	    values: [ <?=$min?>, <?=$max?> ],
	    slide: function( event, ui ) {
	      $( "#amount" ).val(ui.values[ 0 ] +  " руб до " +  + ui.values[ 1 ] +  " руб");
	      setTimeout(function(){ window.location = '/catalog/' + 'min:' + ui.values[ 0 ] + '/max:' + ui.values[ 1 ] + '/'; }, 2000);
	    }
	  });
	  $( "#amount" ).val($( "#slider-range" ).slider( "values", 0 ) + " руб до " + $( "#slider-range" ).slider( "values", 1 )  + " руб");

	  // $('.slider').bxSlider();
	  $('.slider').jCarouselLite({
	    btnNext: '.next',
	    btnPrev: '.prev',
	    autoCSS: true,
	    auto: true,
	    speed: 400
	  });
});
</script>