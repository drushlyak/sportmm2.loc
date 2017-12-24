<?php

	/**
	 * Изолятор старого кода. Для дальнейшего полного удаления
	 *
	 * @package Core
	 * @author куча "авторов"
	 * @author Редькин Сергей, .ter [rou.terra@gmail.com]
	 * @copyright Cruiser cruiser.com.ua
	 */

	/**
	 * @ignore
	 */
	function _OLDCODINSULTR_LNG_textField ($num, $name, $text, $params=array(), $lng_array, $deflt_lng) {
		?>
			<input type="hidden" name="<?=$name?>[msgid]" id="<?=$name?>_0" value="<?=$text["msgid"]?>">
			<?php
			foreach ($lng_array as $lang) {
				?>
				<div id="d<?=$num?>_<?=$lang['id']?>" <?=($lang['id'] == $deflt_lng) ? "" : ' style="display:none;"'?>>
					<input 	name="<?=$name?>[<?=$lang["id"]?>]"
							type="text"
							id="<?=$name?>_<?=$lang["id"]?>"
							size="<?=$params['size'] ? $params['size'] : 20?>"
							value="<?=htmlspecialchars($text[$lang["id"]], ENT_QUOTES)?>">
				</div>
				<?php
			}
	}

	/**
	 * @ignore
	 */
	function _OLDCODINSULTR_LNG_textArea ($num, $name, $text, $params=array(), $lng_array, $deflt_lng) {
		?>
			<input type="hidden" name="<?=$name?>[msgid]" id="<?=$name?>_0" value="<?=$text["msgid"]?>">
			<?php
			foreach ($lng_array as $lang) {
				?>
				<script language="javascript" type="text/javascript">
				editAreaLoader.init({
					id : "td<?=$num?>_<?=$lang['id']?>"
					,syntax: "html"
					,start_highlight: true
					,min_width: 800,
					min_height: 400,
					display: "later",
					language: "ru"
				});
				</script>
				<div id="d<?=$num?>_<?=$lang['id']?>" <?=($lang['id'] == $deflt_lng) ? "" : ' style="display:none;"'?>>
					<textarea  id="td<?=$num?>_<?=$lang['id']?>" name="<?=$name?>[<?=$lang["id"]?>]"
								id="<?=$name?>_<?=$lang["id"]?>"
								cols="<?=(isset($params['cols']) && $params['cols']) ? $params['cols'] : 50?>"
								rows="<?=(isset($params['rows']) && $params['rows']) ? $params['rows'] : 8?>"><?=htmlspecialchars($text[$lang["id"]], ENT_QUOTES)?></textarea>
				</div>
				<?php
			}
	}

	/**
	 * @ignore
	 */
	function _OLDCODINSULTR_LNG_richEdit ($num, $name, $text, $lng_array, $deflt_lng) {
		?>
			<input type="hidden" name="<?=$name?>[msgid]" id="<?=$name?>_0" value="<?=$text["msgid"]?>">
		<?php
		foreach ($lng_array as $lang) {
			?>
				<div id="d<?=$num?>_<?=$lang['id']?>" <?=($lang['id'] == $deflt_lng) ? "" : ' style="display:none;"'?>>
			<?php
			$controlName = $name . '[' . $lang["id"] . ']';
			$spaw = new SpawEditor($controlName, stripslashes($text[$lang["id"]]));
			$spaw->show();
			?>
			</div>
			<?php
		}
	}

	/**
	 * @ignore
	 */
	function leftPageStr() {
		global $pg, $count_pg, $count_records, $record_now;

		$left_page_str = ($count_records) ? ($pg * $count_pg + 1)." - " : "0 - ";
		$left_page_str .= ($count_records < ($pg * $count_pg + $count_pg)) ? $count_records : ($pg * $count_pg + $count_pg);
		$left_page_str .= "&nbsp;"._("из")."&nbsp;".$count_records;

		return _("Записи").":&nbsp;".$left_page_str;
	}

	/**
	 * @ignore
	 */
	function centerPageStr($_XFA) {
		global $pg, $count_pg, $count_records, $record_now;

		$center_page_str = ($pg > 0) ? "<a href=\"".$_XFA."&pg=$pg&count_pg=$count_pg\"><u>"._("пред.")."</u></a>&nbsp;" : "&nbsp;";
		for ($i = 0, $j = 1; $i < $count_records; $i = $i + $count_pg, $j++)
			$center_page_str .= ($j-5 <= $pg && $j+3 >= $pg) ? ($pg == $j-1) ? "<b>$j</b>&nbsp;" : "<a href=\"".$_XFA."&pg=".$j."&count_pg=$count_pg\"><u>$j</u></a>&nbsp;" : "";
			$center_page_str .= ($record_now+$count_pg < $count_records) ? "<a href=\"".$_XFA."&pg=".($pg+2)."&count_pg=$count_pg\"><u>"._("след.")."</u></a>" : "";
		if ($j > 2)
			return _("Страница").":&nbsp;".$center_page_str;
	}

	/**
	 * @ignore
	 */
	function rightPageStr($_XFA) {
		global $pg, $count_pg, $count_records, $record_now;

		$right_page_str  = ($count_pg == 20) ? "<b>20</b>&nbsp;" : "<a href=\"".$_XFA."&pg=".($pg+1)."&count_pg=20\"><u>20</u></a>&nbsp;";
		$right_page_str .= ($count_pg == 40) ? "<b>40</b>&nbsp;" : "<a href=\"".$_XFA."&pg=".($pg+1)."&count_pg=40\"><u>40</u></a>&nbsp;";
		$right_page_str .= ($count_pg == 80) ? "<b>80</b>&nbsp;" : "<a href=\"".$_XFA."&pg=".($pg+1)."&count_pg=80\"><u>80</u></a>&nbsp;";

		if ($count_records > 20)
			return _("Всего на стр.").":&nbsp;".$right_page_str;
	}
