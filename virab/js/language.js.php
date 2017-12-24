var lng = Array();
<?
  foreach ($lng->lng_array as $dlng)
    echo "lng[".$dlng['id']."] = '".$dlng['ind_name']."';\n";
?>
var lng_now = <?=$lng->deflt_lng;?>;

function chngLng(new_lng) { 
 var $str_lng = '';
 
 for (var i = 1; i <= lng.length-1; i++) {
     $str_lng += '<a href="#" onClick="chngLng('+i+');">';
     $str_lng += (new_lng != i) ? lng[i] : '<b>'+lng[i]+'</b>';
     $str_lng += '</a>&nbsp;&nbsp;';
     for (var j = 1; j <= elem_array.length-1; j++) {
									targetId = elem_array[j] + '_' + i;
									if (document.getElementById(targetId)) {
													targetElement = document.getElementById(targetId); 
													targetElement.style.display = (new_lng == i) ? "" : "none"; 
									}
				 }
 }
 document.getElementById('Translater').innerHTML = $str_lng;
 lng_now = new_lng;
}