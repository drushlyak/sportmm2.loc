<?php
$id_album = (int) $_GET['id_album'];
$photo_reorder = $_GET['reorder'];
$photo_delete = $_GET['delete'];
$photo_toprivate = $_GET['toprivate'];
$photo_topublic = $_GET['topublic'];
$photo_alt_store = $_GET['altstore'];
?>
<html>
	<head>
		<link rel='stylesheet' href='css/jqfu/bootstrap.min.css'>
		<link rel='stylesheet' href='css/jqfu/bootstrap-responsive.min.css'>
		<!--[if lt IE 7]><link rel='stylesheet' href='css/jqfu/bootstrap-ie6.min.css'><![endif]-->
		<link rel='stylesheet' href='css/jqfu/bootstrap-image-gallery.min.css'>
		<link rel='stylesheet' href='css/jqfu/jquery.fileupload-ui.css'>
		<!--[if lt IE 9]><script src='js/jqfu/html5shiv.js'></script><![endif]-->
		<link rel="icon" type="image/png" href="/virab/images/favicon.png">
		<title>VIRAB Pro [<?=PROJECT_ID?> • r<?=VIRAB_REVISION?>]</title>

		<link rel="stylesheet" type="text/css" href="css/general.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.lightbox.css">
		<link rel="stylesheet" type="text/css" href="css/ui.all.css">
		<link rel="stylesheet" type="text/css" href="css/autosuggest.css">

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="/js/jquery.lightbox.js"></script>
		<script type="text/javascript" src="/js/jquery.ui.js"></script>
		<script type="text/javascript" src="/js/autosuggest.js"></script>

		<script type="text/javascript" src="js/general.js"></script>
	</head>

	<body style="background-color: #FBFBFB;">
		<div class="container">
			<br/>
			<br/>
		    <form id="fileupload" action="/virab/jqfu/index.php?id_album=<?=$id_album?>" method="POST" enctype="multipart/form-data">
		        <div class="row fileupload-buttonbar">
		            <div class="span7">
		                <span class="btn btn-success fileinput-button">
		                    <i class="icon-plus icon-white"></i>
		                    <span>Добавить...</span>
		                    <input type="file" name="files[]" multiple>
		                </span>
		                <button type="submit" class="btn btn-primary start">
		                    <i class="icon-upload icon-white"></i>
							<span>Загрузка</span>
		                </button>
		                <button type="reset" class="btn btn-warning cancel">
		                    <i class="icon-ban-circle icon-white"></i>
							<span>Отмена</span>
		                </button>
		                <button type="button" class="btn btn-danger delete">
		                    <i class="icon-trash icon-white"></i>
		                    <span>Удалить</span>
		                </button>
		                <input type="checkbox" class="toggle">
		            </div>
		            <div class="span5">
		                <div class="progress progress-success progress-striped active fade">
		                    <div class="bar" style="width:0%;"></div>
		                </div>
		            </div>
		        </div>
		        <div class="fileupload-loading"></div>
		        <br>
		        <div class="file_upload_wrapper"><div class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></div><br style="clear:both"/></div>
		    </form>
		    <br>
		    <div class="well">
		        <h3>Примечания</h3>
		        <ul>
		            <li>Максимальный размер загружаемого файла ограничен <strong>5 MB</strong>.</li>
		            <li>Допустимы только изображения следующих форматов (<strong>JPG, GIF, PNG</strong>).</li>
		            <li>Вы можете <strong>перетаскивать (drag &amp; drop)</strong> с ващего рабочего стола на эту страницу в Google Chrome, Mozilla Firefox и Apple Safari.</li>
		        </ul>
		    </div>
		</div>
		<div id="modal-gallery" class="modal modal-gallery hide fade">
		    <div class="modal-header">
		        <a class="close" data-dismiss="modal">&times;</a>
		        <h3 class="modal-title"></h3>
		    </div>
		    <div class="modal-body"><div class="modal-image"></div></div>
		    <div class="modal-footer">
		        <a class="btn modal-download" target="_blank">
		            <i class="icon-download"></i>
		            <span>Скачать</span>
		        </a>
		        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
		            <i class="icon-play icon-white"></i>
		            <span>Показ</span>
		        </a>
		        <a class="btn btn-info modal-prev">
		            <i class="icon-arrow-left icon-white"></i>
		            <span>Предыдущая</span>
		        </a>
		        <a class="btn btn-primary modal-next">
		            <span>Следующая</span>
		            <i class="icon-arrow-right icon-white"></i>
		        </a>
		    </div>
		</div>
		<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-upload fade" onmouseover="this.className='template-upload hover'" onmouseout="this.className='template-upload'">
        <div class="preview" rowspan=""><span class="fade"></span></div>
        <!--div class="name">{%=file.name%}</div-->
        {% if (file.error) { %}
            <div class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
        {% } else if (o.files.valid && !i) { %}
            <div>
				<input type="text" style="width:100px;" value="{%=o.formatFileName(file.name)%}" name="alt_text[{%=file.name%}]">
                <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div><span class="size">{%=o.formatFileSize(file.size)%}</span></div>
            </div>
            <div class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</div>
        {% } else { %}
            <div colspan="2"></div>
        {% } %}
        <div class="cancel">{% if (!i) { %}
            <button class="btn btn-warning" style="width:100px;">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</div>
    </div>
{% } %}
	</script>
	<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-download fade sort_child" id="sort_{%=file.id%}" onmouseover="this.className='template-download hover sort_child'" onmouseout="this.className='template-download sort_child'">
        {% if (file.error) { %}
        	<div class="preview"><img src="/resources/fotogr/error.gif"></div>
            <div class="name"><span>{%=file.name%}</span></div>
            <div class="size"><span>{%=o.formatFileSize(file.size)%}</span></div>
            <div class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
        {% } else { %}
            <div class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}" style="max-height:80px"></a>
            {% } %}</div>
            <div class="name">
                <input type="text" style="width:100px;" value="{%=file.name%}" name="alt_text[{%=file.name%}]" onchange="editAltPhotoNew({%=file.id%}, this);">
            </div>
            <!-- <div class="size"><span>{%=o.formatFileSize(file.size)%}</span></div> -->
        {% } %}
        <div class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i>
                <span>{%=locale.fileupload.destroy%}</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </div>
    </div>
{% } %}
	</script>
	<script src="/js/jquery.ui.widget.js"></script>
	<script src="/js/jquery.ui.mouse.js"></script>
	<script src="/js/jquery.ui.sortable.js"></script>
	<script src="/js/tmpl.min.js"></script>
	<script src="/js/load-image.min.js"></script>
	<script src="/js/canvas-to-blob.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/bootstrap-image-gallery.min.js"></script>
	<script src="js/jqfu/jquery.iframe-transport.js"></script>
	<script src="js/jqfu/jquery.fileupload.js"></script>
	<script src="js/jqfu/jquery.fileupload-ip.js"></script>
	<script src="js/jqfu/jquery.fileupload-ui.js"></script>
	<script src="js/jqfu/locale.js"></script>
	<script src="js/jqfu/main.js"></script>
	<!--[if gte IE 8]><script src="js/jqfu/cors/jquery.xdr-transport.js"></script><![endif]-->
	<script type="text/javascript">
		BackEndURLS = {
			photo_reorder		: "<?=$photo_reorder?>",
			photo_delete 		: "<?=$photo_delete?>",
			photo_toprivate 	: "<?=$photo_toprivate?>",
			photo_topublic	 	: "<?=$photo_topublic?>",
			main_photo_astore   : "<?=$photo_alt_store?>",
			data_to_send		: {
				id_album		: <?=$id_album?>
			}
		};
	</script>

	</body>
</html>