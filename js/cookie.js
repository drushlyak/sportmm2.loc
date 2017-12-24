/**
 * setDataToCookie
 *
 * @param {object} data
 * формат: { _id_: { count: 1 } }
 */
function setDataToCookie(data) {
	setCookie('basketPIDs', JSON.stringify(data || {}), 360, '/');
}


/**
 * getDataFromCookie
 *
 * @return object
 */
function getDataFromCookie() {
	var data = getCookie('basketPIDs');

	if (data === null) {
		return {};
	}

	try {
		data = JSON.parse(data);
	} catch(e) {
		data = {};
	}

	return data;
}
/**
 * Получение значения cookie
 *
 * @param {string} name
 * @return {string}
 */
function getCookie(name) {
	var idx = document.cookie.lastIndexOf(name + '=');

	if (idx == -1) {
		return null;
	}

	var value = document.cookie.substring(idx + name.length + 1),
		end = value.indexOf(';');

	if (end == -1) {
		end = value.length;
	}

	value = value.substring(0, end);
	value = unescape(value);
	return value;
}
function getCookieNum() {
	if (document.cookie.length > 0) {
		var arr_num = getDataFromCookie();	
		var num = arr_num.length;	
		var count = 0; 
	    for(var prs in arr_num) 
	    { 
	        count++;
	    } 
	    $('#num').html(count);
	    $('#text_cart').html('<a href="/cart/">Оформить заказ</a>');

		}
	
	return "";
}
/**
 * Добавление в корзину
 * @param {int} id_product
 * @param {int} count (по-умолчанию 1)
 */
function addToBasket(id_product, count) {
	
	var count_ = parseInt((count || 1), 10),
		id_product_ = parseInt(id_product, 10);

	// добавление в cookie
	var data = this.getDataFromCookie();
	if (typeof data[id_product_] === 'object') {
		data[id_product_]['count'] += count_;
	} else {
		data[id_product_] = { count: count_ };
	}
	this.setDataToCookie(data);
	
	if (confirm("Перейти в корзину для оформления заказа?")) {
		window.location = '/cart/';
	} else {
		return getCookieNum();		
	}

	// событие изменения содержимого корзины
	//$(document).trigger('basketChange');
	
	//$(document).one('basketDataChange', function() {
	//	this.showConfirmDialog();
	//}.bind(this))
	
	return true;
}
/**
 * Добавление в корзину
 * @param {int} id_product
 * @param {int} count (по-умолчанию 1)
 */
function addToBasketConfirm(id_product, count, my_confirm, num_stock) {
	
	var count_ = parseInt((count || 1), 10),
		id_product_ = parseInt(id_product, 10);

	// добавление в cookie
	var data = this.getDataFromCookie();
	if (typeof data[id_product_] === 'object') {
		data[id_product_]['count'] += count_;
	} else {
		data[id_product_] = { count: count_ };
	}
	this.setDataToCookie(data);
	
	if (my_confirm == 1) {
		if ($('#popup').hasClass('hidden')) {
			$('#opaco').height($(document).height()).removeClass('hidden')[$.browser.msie ? 'attr': 'fadeTo']('fast', 0.7);

			$('#popup').css({
				'dispay': 'none'
			}).html('').load('htdocs/popup-forms/add_catalog.php', [], function () {});
				
			$('#popup').css({
				'margin-left': '-165px',
				'margin-top': '-10000px'
			}).animate({
				marginTop: '-87px'
			},
			500);
			
			$('#popup').removeClass('hidden');
			$('.content').attr('id',id_product);
			$('.content').attr('num_stock',num_stock);
			//console.log(id_product);
		} else {
			$('#opaco').addClass('hidden').removeAttr('style').unbind('click');
			$('#popup').addClass('hidden');
		}

	//if (confirm("Перейти в корзину для оформления заказа?")) {
	//		window.location = '/cart/';
	//	} else {
	//		return getCookieNum();		
	//	}
	}

	// событие изменения содержимого корзины
	//$(document).trigger('basketChange');
	
	//$(document).one('basketDataChange', function() {
	//	this.showConfirmDialog();
	//}.bind(this))
	
	return true;
}
/**
 * Добавление в корзину
 * @param {int} id_product
 * @param {int} count (по-умолчанию 1)
 */
function addToProduct(id_product, count, key) {
	//var i = parseInt($('.counter').children('.count').attr('value'));
	//var num_stock = parseInt($('.counter').children('.count').attr('num_stock'));
	//console.log(i + ' ' + num_stock);
	if (key == 1) {
		if (i >= num_stock) {
			return true;
		}
		else {
			var count_ = parseInt((count || 1), 10), id_product_ = parseInt(id_product, 10);
			
			// добавление в cookie
			var data = this.getDataFromCookie();
			if (typeof data[id_product_] === 'object') {
				data[id_product_]['count'] += count_;
			}
			else {
				data[id_product_] = {
					count: count_
				};
			}
			this.setDataToCookie(data);
			
			return true;
		}
	} else {
			var count_ = parseInt((count || 1), 10), id_product_ = parseInt(id_product, 10);
			
			// добавление в cookie
			var data = this.getDataFromCookie();
			if (typeof data[id_product_] === 'object') {
				data[id_product_]['count'] += count_;
			}
			else {
				data[id_product_] = {
					count: count_
				};
			}
			this.setDataToCookie(data);
			
			return true;
	}
}
/**
 * Удаление из корзины
 *
 * @param {int} id_product
 */
function removeFromBasket(id_product) {
	var id_product_ = parseInt(id_product, 10);

	// удаление из cookie
	var data = this.getDataFromCookie();
	if (typeof data[id_product_] === 'object') {
		delete(data[id_product_]);
		this.setDataToCookie(data);
		return getCookieNum();	
		// событие изменения содержимого корзины
		//$(document).trigger('basketChange');
		//return true;
	}

	return false;
}

/**
 * Установка cookie
 *
 * @param {string} name
 * @param {string} value
 * @param {int} days
 * @param {string} path
 * @param {string} domain
 * @param {string} secure
 * @return {void}
 */
function setCookie(name, value, days, path, domain, secure) {
	var expires = -1;
	if (typeof days == "number" && days >= 0) {
		var d = new Date();
		d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = d.toGMTString();
	}

	var value = escape(value);
	document.cookie = name + "=" + value + ";"
		+ (expires != -1 ? " expires=" + expires + ";" : "")
		+ (path ? "path=" + path : "")
		+ (domain ? "; domain=" + domain : "")
		+ (secure ? "; secure" : "");
	}



/**
 * Удаление значения cookie

 *
 * @param {string} name
 */
function deleteCookie(name) {
	setCookie(name, "-", 0);
}
function countProduct() {
	var num = $('input[name="count"]').val();
	return num;
}
function reCalc() {
	var total = 0;
	$("#del_cart > .cart-product-detail").each(function() {	
			var count_el = $(this).find(".pieces-data").val();
			//console.log(count_el);
			var cost = count_el * $(this).find(".pieces-data").attr('price');
			//console.log(cost);
			total += cost;		
	});
	$("#total_sum").html(total + ' руб.');
	$("#total-cost").val(total);
}
function del_product(id) {	

		removeFromBasket(id);	
		$("#row" + id).remove();
		reCalc();
		var n = $('#num').html();
		console.log(n);
		if(n == 0) {
			$('#del_cart').remove();
			$('.content-page').html('<h3>В корзине нет товаров</h3><a href="/catalog/" class="buy" style="float: left !important">Сделать покупки</a>');
		}
		
}
/*
 * записываем в куки сколько элементов будет отображаться на 1-й странице в каталоге
 */
 function show_catalog(i) {
		setCookie("limit_catalog", i, 360, "/");
		submitFilter();
		//var str_cat = $('input:radio:checked').val(); 
		//var cat = (str_cat) ? '/cat:' + str_cat : '';
		//window.location.reload();
		//window.location = "/catalog" + cat + "/page1/"
	}