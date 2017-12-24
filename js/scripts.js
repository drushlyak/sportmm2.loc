$(function(){
  $('.radioblock').find('.radio').each(function(){
    $(this).click(function(){
      var valueRadio = $(this).attr('value');
      $(this).parent().find('.radio').removeClass('active');
      $(this).addClass('active');
      $(this).parent().find('input').val(valueRadio);
    });
  });

  $('.country, .city').combobox();

  $('.scroll-pane').jScrollPane({showArrows: true});

  $('.cart-prod-number, .pieces-data').on('keypress', function(event){
      var key, keyChar;
      if(!event) var event = window.event;
      if (event.keyCode) key = event.keyCode;
      else if(event.which) key = event.which;
      if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 || key == 190) return true;
      keyChar=String.fromCharCode(key);
      if(!/\d/.test(keyChar)){
          return false;
      }
  });
	$('a.pieces-up').on('click', function(e) {
		var elem = $(this).prevAll('.pieces-data'),
			price = $(this).prevAll('.pieces-data').attr('price');
		
		e.preventDefault();
		elem.val(parseInt(elem.val())+1);
		$(this).parents('.cart-product-detail').find('.price').html(price*elem.val());
		reCalc();
	});
	$('.pieces-down').on('click', function(e) {
		var elem = $(this).nextAll('.pieces-data'),
		price = $(this).nextAll('.pieces-data').attr('price');
		e.preventDefault();
		var id = $(this).attr('id');
		//console.log(elem.val());
		if (elem.val()>=2) {
			elem.val(parseInt(elem.val())-1);
			addToProduct(id, -1, 2)
			$(this).parents('.cart-product-detail').find('.price').html(price*elem.val());
			reCalc();
		}
	});
	
});
function submitSearch() {
	var src_str = '/search:' + $('input[name="search"]').val();
	window.location = '/catalog' + src_str;
}
