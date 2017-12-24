<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>


<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/mytmp/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/mytmp/css/body.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/mytmp/css/new_style.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/mytmp/css/carousel.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/mytmp/css/jsCarousel.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/mytmp/js/jqtransformplugin/jqtransform.css" type="text/css" media="all">
<meta name='yandex-verification' content='73b5986298e4e400' />
<script type="text/javascript" src="templates/mytmp/js/jq183.js"></script>
<script type="text/javascript" src="templates/mytmp/js/jsCarousel2.js"></script>
<jdoc:include type="head" />
<?php JHTML::_('behavior.modal'); ?>

</head>

<body>
<span class="page_line_t"></span>
<div class="page_width">
	<div class="header">
<!-- ЛОГОТИП -->
		<a href="/"><div id="logo"></div></a>
<!-- ПОИСК -->
		<jdoc:include type="modules" name="user4" />
<!-- ТЕЛЕФОН -->
		<div class="phone">
			<p class="phone_inf">8 (495) 720 36 07</p>
		</div>
	</div>

<!-- МЕНЮ -->
	<div class="menu">
		<div class="m_nav">
			<div class="m_nav_bg">
				<div class="m_nav_pos">
					<jdoc:include type="modules" name="menu" />
					<p class="help_bl"><a href="/ask-question">Есть вопросы?</a></p>
				</div>
			</div>
		</div>			
	</div>

<!-- Content -->
	<div class="center">
		<jdoc:include type="message" />
		<?php if($this->countModules('left')) : ?>
			<div id="leftcolumn">
				<jdoc:include type="modules" name="left" style="rounded" />
				<?php if (JURI::current() == JURI::base()) : ?>
					<div class="futured_prod">
						<h3>Рекомендуемые товары</h3>
						<jdoc:include type="modules" name="futured_prod" style="rounded" />
						<script>
						    $('.vmproduct-vert').jsCarousel({
						        onthumbnailclick: function(src) {alert(src); },
						        autoscroll: true,
						        masked: false,
						        itemstodisplay: 3,
						        orientation: 'v'
						    });
						</script>
			        </div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<div class="content">
			<jdoc:include type="modules" name="breadcrumb" />
			<jdoc:include type="modules" name="index-module" />

<!-- =================== СЛАЙДЕР =================== -->
			<?php if (JURI::current() == JURI::base()) : ?>
				<div class="slider-cat">
					<jdoc:include type="modules" name="slider-cat" />
		        </div>
			<?php endif; ?>
<!-- ================= END СЛАЙДЕР ================= -->

			<jdoc:include type="component" />
			<jdoc:include type="modules" name="yandex-map" />
			<?php if($this->countModules('yandex-map')) : ?>
				<div style="width:550px; margin:0 auto;">
					<iframe marginwidth='0' marginheight='0' frameborder='0' width='550' height='400' scrolling='no' src='http://makemap.ru/show.php?id=18001'> </iframe>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- КОНЕЦ Content -->

<!-- Категории с картинками -->
        <div class="cat-img">
			<?php if ($_SERVER['REQUEST_URI']=="/"){
				if($this->countModules('cat-img')) : ?>
					<jdoc:include type="modules" name="cat-img" />
			<?php endif;}?>
			<div class="clear_line"></div>
        </div>

	<!-- ИНФО БЛОК -->

	<div class="info_bl">
		<div class="info_bl_bg">
			<div class="info_bl_pos">
				<table width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<div>
							<i><img src="<?php echo $this->baseurl ?>/templates/mytmp/images/remeza/info_img1.png" alt=""></i>
							<big><a href="/about">О компании</a></big>
							Покупая продукцию у нас, Вы значительно экономите, так как минуете посредников.
						</div>
					</td>
					<td>
						<div>
							<i><img src="<?php echo $this->baseurl ?>/templates/mytmp/images/remeza/info_img2.png" alt=""></i>
							<big><a href="/service">Сервис</a></big>
							Мы обслуживаем  компрессорное оборудование, пневмосети предприятия, поставляем з/ч и расходные материалы.
						</div>
					</td>
					<td>
						<div>
							<i><img src="/templates/mytmp/images/remeza/info_img3.png" alt=""></i>
							<big><a href="/ask-question">Задать вопрос</a></big>
							Если вы не нашли какую-то нужную информацию или у вас остались вопросы, то задайте их нам прямо сейчас!
						</div>
					</td>
				</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="clear_line"></div>

	<!-- ИНФО БЛОК КОНЕЦ -->

	<!-- ФУТЕР -->

	<div class="footer_width">
		<!-- Подвал -->
		<div class="footer">
				<!-- правая колонка -->
				<div class="footer_col_r">
						<p class="copy_inf">ЗАО «<a href="/">ВК-Инжиниринг</a>» 2011 &copy;<br>Все права защищены.</p>
				</div>
				<!-- левая колонка -->
				<div class="footer_col_l">
						<div class="brands_inf">
							<p>Продукция сертифицирована</p>
							<img src="/templates/mytmp/images/remeza/brand_1.png" width="33" height="51" alt="">
							<img src="/templates/mytmp/images/remeza/brand_2.png" width="44" height="51" alt="">
							<img src="/templates/mytmp/images/remeza/brand_3.png" width="58" height="51" alt="">
							<img src="/templates/mytmp/images/remeza/brand_4.png" width="42" height="51" alt="">
							<img src="/templates/mytmp/images/remeza/brand_5.png" width="33" height="51" alt="">
						</div>
				</div>
				<!-- центральная колонка -->
				<div class="footer_col_c">
<div class="counter" style="margin: 20px 30px 0; float: left;">
	<!-- MyCounter v.2.0 -->
		<script type="text/javascript"><!--
		my_id = 123751;
		my_width = 88;
		my_height = 41;
		my_alt = "MyCounter - счётчик и статистика";
		//--></script>
		<script type="text/javascript"
		  src="http://scripts.mycounter.ua/counter2.0.js">
		</script><noscript>
		<a target="_blank" href="http://mycounter.ua/"><img
		src="http://get.mycounter.ua/counter.php?id=123751"
		title="MyCounter - счётчик и статистика"
		alt="MyCounter - счётчик и статистика"
		width="88" height="41" border="0" /></a></noscript>
	<!--/ MyCounter -->
</div>
<div class="counter" style="margin: 20px 30px 0 0; float: left;">
	<!-- Yandex.Metrika informer -->
	<a href="http://metrika.yandex.ru/stat/?id=22270633&amp;from=informer"
	target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/22270633/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
	style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:22270633,lang:'ru'});return false}catch(e){}"/></a>
	<!-- /Yandex.Metrika informer -->

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript">
	(function (d, w, c) {
	    (w[c] = w[c] || []).push(function() {
	        try {
	            w.yaCounter22270633 = new Ya.Metrika({id:22270633,
			    webvisor:true,
	                    clickmap:true,
	                    trackLinks:true,
	                    accurateTrackBounce:true});
	        } catch(e) { }
	    });

	    var n = d.getElementsByTagName("script")[0],
	        s = d.createElement("script"),
	        f = function () { n.parentNode.insertBefore(s, n); };
	    s.type = "text/javascript";
	    s.async = true;
	    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

	    if (w.opera == "[object Opera]") {
	        d.addEventListener("DOMContentLoaded", f, false);
	    } else { f(); }
	})(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="//mc.yandex.ru/watch/22270633" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
</div>	
<div class="counter" style="margin: 20px 30px 0 0;">
	<!--LiveInternet counter--><script type="text/javascript"><!--
	document.write("<a href='http://www.liveinternet.ru/click' "+
	"target=_blank><img src='//counter.yadro.ru/hit?t12.11;r"+
	escape(document.referrer)+((typeof(screen)=="undefined")?"":
	";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
	screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
	";"+Math.random()+
	"' alt='' title='LiveInternet: показано число просмотров за 24"+
	" часа, посетителей за 24 часа и за сегодня' "+
	"border='0' width='88' height='31'><\/a>")
	//--></script><!--/LiveInternet-->
</div>
				</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="templates/mytmp/js/slides.js"></script>
<script type="text/javascript" src="templates/mytmp/js/jcarousel.js"></script>
<script type="text/javascript" src="templates/mytmp/js/scripts.js"></script>
<script type="text/javascript" src="templates/mytmp/js/jqtransformplugin/jquery.jqtransform.js" ></script>

</body>
</html>
