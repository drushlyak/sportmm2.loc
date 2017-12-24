<?php

class teFeedbackTemplate extends teTemplate {
	var $feedback_group;

	function __construct() {
		$args = func_get_args();
		parent::__construct($args[0], $args[1]);
		return true;
	}

	public function getCode() {
		$this->code = false;
		// Определим номер текущей страницы
		if(is_array($this->c_this['value'])){
			foreach($this->c_this['value'] as $elem){
				if(is_integer(strpos($elem, 'page'))){
					$pgnm = intval(substr($elem, 4));
				}
			}
		}
		$pgnm = ($pgnm) ? $pgnm : 1;

		$sql = sql_placeholder("
			SELECT
				fg.id,
				fg.count_per_page,
				fg.name,
				tcd.code AS 'code'
			FROM " . CFG_DBTBL_MOD_FEEDBACK_GROUP . " AS fg,
				" . CFG_DBTBL_TE_CONTTREE . " AS tcs,
				" . CFG_DBTBL_TE_CONTDATA . " AS tcd
			WHERE fg.id_te_value=?
				AND tcs.id=fg.code
				AND tcd.id=tcs.data_id
			", $this->teValue->getTeValueId()
		);
		$rsFeedbackGr = $this->_db->query($sql);
		if ($rsFeedbackGr->num_rows) {
			$feedbackGr = $rsFeedbackGr->fetch_assoc();
			$this->feedback_group = $feedbackGr['id'];

			$this->code = '';

			if ($_REQUEST['submitted']) {
				$result_save = $this->saveText();
				if ($result_save) {
					$this->code .= $result_save;
				}
			}

			if ($this->teValue->getTeValueName() !== 'faqe5144') {
				$product_id = $this->_db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE chpu = ?", $this->c_this['value'][0]);
				if ($product_id > 0) {
					$sql_where = ' AND id_product = ' . intval($product_id);
				} else {
					$sql_where = ' AND id_product = 0';
				}
			}

			$sql = sql_placeholder("
				SELECT fq.id,
						DATE_FORMAT(fq.idate, '%H:%i/%d/%m/%Y') AS idate,
						fq.group_id AS id_group,
						fq.text AS text,
						fq.author_name AS author_name,
						fq.author_mail AS author_mail,
						fq.id_product,
						mp.name AS product_name,
						mp.is_active AS product_is_active,
						mp.chpu,

						(
							SELECT dc.is_menu
								FROM " . CFG_DBTBL_DICT_CATEGORY . " AS dc, " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcp
							WHERE mcp.id_product = mp.id AND mcp.id_category = dc.id
							ORDER BY dc.is_menu DESC
							LIMIT 1
						) AS category_is_active
					FROM " . CFG_DBTBL_MOD_FEEDBACK_TEXT . " fq
						LEFT JOIN " . CFG_DBTBL_MOD_PRODUCT . " mp ON mp.id = fq.id_product
				WHERE fq.group_id=? AND priz_active=1" . $sql_where . " ORDER BY fq.idate DESC", $feedbackGr['id']
			);

			$rsFeedback = $this->_db->query($sql);
			// Построим список отзывов
			for ($i = 1; $i <= $rsFeedback->num_rows; $i++) {
				if ($feedback = $rsFeedback->fetch_assoc()) {
					$feedback_complet[] = $feedback;
				}
			}
			// Если отзывов больше, чем заданно в разделе то с формируем маршрутизатор страниц
			if ($feedbackGr['count_per_page'] < count($feedback_complet)) {
				// Определим номер, кол-во страниц и построим маршрутизатор
				$count_pg = ceil((count($feedback_complet) - $feedbackGr['count_per_page']) / $feedbackGr['count_per_page']) + 1;
				$str_router = $this->router(getAdrPage($this->c_this['chpu']), $pgnm, $count_pg);
			} else {
				$str_router = "";
			}

			$code_in = "";
			for ($i = 1; $i <= count($feedback_complet); $i++) {
				if ($i > ($pgnm - 1) * $feedbackGr['count_per_page'] && $i <= (($pgnm - 1) * $feedbackGr['count_per_page'] + $feedbackGr['count_per_page'])) {
					$code_in .= $feedbackGr['code'];
				}
				$code_in = $this->makeCode($code_in, $feedback_complet[$i - 1], $this->param_te_value['id'], getAdrPage($this->c_this['chpu']), $i);
			}
				//print_r($this->c_this);
		}

		$this->code .= $code_in;

		// отображаем комментарии и форму для комментирования
		$this->code = '<section class="testimonials">' . $this->code . '</section>' . $str_router . $this->makeInputTextCode();

		return $this->code;

	}

	function router($addr, $pg, $count_pg) {
		// Маршрутизатор
		$html  = '<div style="clear: both; height: 5px"></div>';
		if ($count_pg > 1) {


			$html .= '<div id="pagination"><div class="paginator_p_wrap"><div class="paginator_p_bloc"></div></div>';
			$html .= '<a class="control" id="over_backward"></a>';
			$html .= '<div class="paginator_slider_wrap">'
						. '<div class="paginator_slider" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">'
                	    . '<a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>'
          	          . '</div>'
					. '</div>';
			$html .= '<a class="control" id="over_forward"></a><a class="control" id="max_backward">Первая страница</a><a class="control" id="max_forward">Последняя страница</a></div>';
        }
        $html .= '<div style="clear: both; height: 50px"></div>';
		$html .= '<script type="text/javascript">

						$(document).ready(function(){
							var active_page = ' . $pg . ';
							$("#pagination").jPaginator({
								nbPages:' . $count_pg . ',
								marginPx:6,
								minSlidesForSlider:2,
								selectedPage:' . $pg . ',
								length:15,
								overBtnLeft:"#over_backward",
								overBtnRight:"#over_forward",
								maxBtnLeft:"#max_backward",
								maxBtnRight:"#max_forward",
								onPageClicked: function(a,num) {
									if (num != active_page) {
										window.location = "/feedback/page" + num;
										$("#page").html("Page " + num);
									}
								}
							});

						  if (!($("#pagination .paginator_slider").is(\':visible\'))) {
							   $("#pagination .paginator_slider_wrap").hide();
							   $("#pagination #over_backward").hide();
							   $("#pagination #over_forward").hide();
							   $("#pagination #max_backward").hide();
							   $("#pagination #max_forward").hide();
							   $("#pagination .paginator_p_wrap").css({marginLeft: (700 - $("#pagination .paginator_p_wrap").width()) / 2 + "px"});
						  }

						});
				</script>';




/*		if ($pg == 1) {
			$html .= '<div title="Предыдущая страница" class="pagerLeftArrow pagerLeftArrowDisabled"></div>';
		} else {
			$html .= '<div title="Предыдущая страница" onClick="location.href=\'' . $addr . 'p_' . ($pg - 1) . '/\'" class="pagerLeftArrow"></div>';
		}

		for ($i = 1; $i <= $count_pg; $i++) {
			$class_str = ($pg == $i) ? 'pagerPageEL pagerCurrentPage' : 'pagerPageEL pagerPage';
			$title_str = ($pg == $i) ? 'Текущая страница' : 'Переход на страницу номер  ' . $i;

			$html .= '<div title="' . $title_str . '" onClick="location.href=\'' . $addr . 'p_' . $i . '/\'" pagenum="' . $i . '" class="' . $class_str . '">' . $i . '</div>';
		}

		if ($pg == $count_pg) {
			$html .= '<div title="Следующая страница" class="pagerRightArrow pagerRightArrowDisabled"></div>';
		} else {
			$html .= '<div title="Следующая страница" onClick="location.href=\'' . $addr . 'p_' . ($pg + 1) . '/\'" class="pagerRightArrow"></div>';
		}
		$html .= '</div></div>';
		$html .= '<div class="clear"></div>';
*/
		return $html;

	}

	function makeCode($text, $feedback, $ptv, $addr, $i) {
		global $__MONTH_NAME;
		$dt = explode('/', $feedback['idate']);
		$text = preg_replace("/{feedbacknum}/i", $i, $text);
		$feedback['text'] = preg_replace("/\n/i", "<br/>", $this->c_lng->Gettextlng($feedback['text']));
		$text = preg_replace("/{feedbacktext}/i", $feedback['text'], $text);
		$text = preg_replace("/{feedbackdate}/i", $dt[1] . ' ' . $__MONTH_NAME[2][(int)$dt[2]] . ' ' . $dt[3], $text);
		$text = preg_replace("/{feedbackauthor}/i", $this->c_lng->Gettextlng($feedback['author_name']), $text);
		$text = preg_replace("/{feedbackmail}/i", $feedback['author_mail'], $text);

		/*if ((int) $feedback['product_is_active'] && (int) $feedback['category_is_active']) {
			// http://obradoval.cruiser.com.ua/product/orhideja_v_konjachnom_bokale
			// onclick="Order.basket.addToBasket(' . $feedback['id_product'] . '); return false;"
			$text = preg_replace("/{feedbackbuy}/i", '<div class="complex_button wf"><a title="Купить такой же" href="/product/' . $feedback['chpu'] . '"><div class="complex_button_left"><div class="complex_button_right"><div class="buttonIconEl icon_right">Купить "' . $feedback['product_name'] . '"</div></div></div></a></div><br><br>', $text);
		}*/

		return $text;
	}

	private function makeInputTextCode() {
		ob_start();

		$product_id = $this->_db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE chpu = ?", $this->c_this['value'][0]);
		?>
		<script type="text/javascript">
			function clearDefaultField() {
				var form = $("#testimonials-form"),
					nf = form.find("input[name='name']"),
					nmbf = form.find("input[name='order_number']");

				if (nf.val() == 'Ваше имя') {
					nf.val("");
				}

				if (nmbf.length && (nmbf.val() == 'Номер вашего заказа')) {
					nmbf.val("");
				}

				form.get(0).submit();
			}
		</script>
		<div class="col-inner">
			<h1>Оставить свой отзыв о товаре</h1>

			<form class="basic-form" id="testimonials-form" action="" method="post" onsubmit="clearDefaultField(); return false;">


							<fieldset>
                                <div class="label-field-wide-row">
                                    <label for="name">Имя:</label>
                                    <input type="text" name="name" class="textfield" placeholder="Ваше имя">
                                </div>


								<input type="hidden" name="id_product" value="<?=intval($product_id)?>">

                                <div class="label-field-wide-row">
                                	<input type="hidden" name="submitted" value="1">
                                    <label for="text">Отзыв:</label>
                                    <textarea name="text" class="textarea">Очень красивый букет"</textarea>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="centered-row">
                                    <input type="image" name="save" src="images/buttons/leave-comment.png">
                                </div>
                            </fieldset>



			</form>
		</div>
	<?
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	private function saveText()	{
		global $lng, $site_config;

		$text = strip_tags(trim($_REQUEST['text']));
		$author_name = strip_tags(trim($_REQUEST['name']));
		$id_product = intval($_REQUEST['id_product']);
		$order_number = (int) $_REQUEST['order_number'];

		//$captcha = strip_tags(trim($_REQUEST['captcha']));

		if (!$author_name) {
			$this->addError(_("Необходимо указать имя"));
		}
		//if (!$order_number && $id_product == 0) {
		//	$this->addError("Необходимо указать номер выполненного заказа");
		//}

		if (!$text) {
			$this->addError(_("Необходимо написать текст отзыва"));
		}
		//if ($captcha != $_SESSION['captcha_keystring']) {
		//	$this->addError(_("Неверная контрольная фраза"));
		//}

		if (!$this->getError()) {
			$auth_name_lng[$lng->deflt_lng] = $author_name;
			$text_lng[$lng->deflt_lng] = $text;
			$lng_name = $lng->SetTextlng($auth_name_lng);
			$lng_text = $lng->SetTextlng($text_lng);

			/*if (!$id_product && $order_number) {
				$id_product = $this->_db->get_one("
					SELECT mop.id_product
						FROM " . CFG_DBTBL_MOD_ORDER_PRODUCT . " mop
							JOIN " . CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO . " moai ON moai.id_order = mop.id_order
					WHERE moai.number = ?
				", $order_number );
			}*/

			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_MOD_FEEDBACK_TEXT . "
				SET
					author_name=?,
					author_mail='',
					text=?,
					idate=NOW(),
					id_product = ?,
					group_id=?",
				$lng_name, $lng_text, $id_product, $this->feedback_group
			);

			$this->_db->query($sql);
			$insert_id = $this->_db->insert_id;

			if ($id_product > 0) {
				$product = $this->_db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $id_product);
			}

			/*require_once(LIB_PATH . "/class.phpmailer.php");

			$mail_manager = new PHPMailer();
			//Письмо менеджеру
			$mail_manager -> Subject = ('Новый отзыв на сайте');
			$mail_manager -> From = 'robot@obradoval.ru';
			$mail_manager -> FromName = 'Обрадовал.Ru';
			// Отправим письмо о обратной связи
			$mail_manager->Body = "
				<html>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
				</head>
				<body>
					Поступил новый отзыв.<br/><br/>
					<b>Данные:</b><br/>
					Имя: " . $author_name . "<br/>
					Телефон: " . $author_phone . "<br/>
					Текст отзыва: " . $text . "<br/>
					" . (($id_product > 0) ? $product['name'] . ' | Артикул - ' . $product['article'] : '') . "
					<br>
					Изменить отображение отзыва можно <a href=\"http://obradoval.ru/virab/index.php?fuseaction=mod_feedback.form&type=2&id_feedback=" . $this->feedback_group . "&id=" . $insert_id . "\">тут</a>
				</body>
				</html>
			";
			// Отправим письмо
			$mail_manager->IsHTML(true);
			$mail_manager->CharSet = "utf-8";
			$mail_manager->AddAddress($site_config['manager_email']);
			$mail_manager->Send();
				*/
			return '
				<div style="padding-top:10px; border-top: 2px solid #E2CA7C;">
					<center>
						<table width="469" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="102" valign="top" rowspan="2"><img src="/images/errorProductsNotFound.gif"></td>
								<td width="298" valign="middle" height="35"><p class="inconflict"><strong>Ваш отзыв принят!<br /> После проверки модератором он будет доступен всем.<strong></p></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</center>
				</div>
				<div style="margin-bottom:20px; border-top: 2px solid #E2CA7C;" class="col-inner"></div>
			';
		} else {
			return '
				<div style="padding-top:10px; border-top: 2px solid #E2CA7C;" id="error">
					<center>
						<table width="469" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="102" valign="top" rowspan="2"><img src="/images/errorErrorLoading.gif"></td>
								<td width="298" valign="middle" height="35"><p class="inconflict"><strong>Ошибка добавления отзыва<strong></p></td>
							</tr>
							<tr>
								<td><p class="inconflict_text">' . $this->getError() . '</p></td>
							</tr>
						</table>
					</center>
				</div>
				<div style="margin-bottom:20px; border-top: 2px solid #E2CA7C;" class="col-inner"></div>
			';
		}
	}

	public function getFeedbackGroup() {
		return $this->feedback_group;
	}

	public function addError($text) {
		$this->ERROR .= $text . "<br/>";
	}

	public function getError() {
		return substr($this->ERROR, 0, 350);
	}

}

?>