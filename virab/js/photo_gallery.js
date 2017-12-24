/**
 * Управление фото-галереей
 * @author ter
 */

	$(function() {
		
		var $privat = $("#privat"),
			$public = $("#public");
		
		$privat.sortable({ 
			forcePlaceholderSize: true
		  , revert: true
		  , stop: function(event, ui) { 
				var arr = $privat.sortable('toArray'),
					strdata = [],
					item = ui.item.get(0).id.replace("photo_", "");
				for ( var i = 0, len = arr.length; i < len; i++) {
					strdata.push("pos[" + i + "]=" + arr[i]);
				}
				strdata.push("privat=1");
				strdata.push("id_model=" + BackEndURLS.data_to_send.id_model);
				strdata.push("item=" + item);
				$.ajax({
					type: "POST",
					url: BackEndURLS.photo_reorder,
					data: strdata.join("&")
				});					
			} 
		});

		$public.sortable({ 
			forcePlaceholderSize: true
		  , revert: true
		  , stop: function(event, ui) { 
				var arr = $public.sortable('toArray'),
					strdata = [],
					item = ui.item.get(0).id.replace("photo_", "");
				for ( var i = 0, len = arr.length; i < len; i++) {
					strdata.push("pos[" + i + "]=" + arr[i]);
				}
				strdata.push("privat=0");
				strdata.push("id_model=" + BackEndURLS.data_to_send.id_model);
				strdata.push("item=" + item);
				$.ajax({
					type: "POST",
					url: BackEndURLS.photo_reorder,
					data: strdata.join("&")
				});					
			} 
		});
	});
		
	/**
	* Удаление фотографии
	**/
	function deletePhoto(id_photo) {
		$.ajax({
			type: "POST",
			url: BackEndURLS.photo_delete,
			data: "id=" + id_photo,
			success: function(msg){
				var el = $("#photo_" + id_photo);
				el.fadeOut(1000, function() {
					el.remove();
				});					
			}
		});
	}
	
	/**
	* Редактирование Alt-текста фотографии
	**/
	function editAltPhoto(id_photo, text_old) {
		$("#frm" + id_photo).css("visibility", "visible");
	}
	
	/**
	* В приватные
	**/		
	function toPrivate(id_photo) {
		$.ajax({
			type: "POST",
			url: BackEndURLS.photo_toprivate,
			data: "id=" + id_photo,
			success: function(msg){
				var el = $("#photo_" + id_photo);
				el.fadeOut(500, function() {
					el.remove();
					$("#privat").prepend(msg);
					$('a[@rel*=lightbox]').lightBox();
				});
			}
		});
	}
	
	/**
	* В публичные
	**/		
	function toPublic(id_photo) {
		$.ajax({
			type: "POST",
			url: BackEndURLS.photo_topublic,
			data: "id=" + id_photo,
			success: function(msg){
				var el = $("#photo_" + id_photo);
				el.fadeOut(500, function() {
					el.remove();
					$("#public").prepend(msg);
					$('a[@rel*=lightbox]').lightBox();
				});
			}
		});
	}
	