<?php
		$classFromUrl = array(
			'struct_site.main' => 'mp_struct',
			'admin.main' => 'mp_admin',
			'acl.main' => 'mp_acl',
			'modules.main' => 'mp_modules',
			'dictionaries.main' => 'mp_dictionaries'
		);

		function showMPElement($item) {
			global $lng, $db;
			?>
				<p class="mp_element_name"><a href="index.php?fuseaction=<?=$item['url']?>"><?=$lng->Gettextlng($item['title'])?></a></p>
			<?php

			$rsSubCats = $db->get_all("
					SELECT 	id,
							title,
							url,
							quick_help
					FROM " . CFG_DBTBL_NAVIGATION . "
					WHERE 	parent_id = ?
						AND menu=1
					ORDER BY ord
			", $item['id']);

			?>
				<ul class="ml_sub_block">
					<?php if($rsSubCats):
                            $j = 0;
							foreach($rsSubCats as $sub_item):
                                if ($j < 7):
                            ?>
                                <li class="mp_link_el" onMouseOver="mOver(this); return false;" onMouseOut="$('.mp_help_info').html('&nbsp;'); return false;"><a href="index.php?fuseaction=<?=$sub_item['url']?>" helpstr="<?=$lng->Gettextlng($sub_item['quick_help'])?>">
                                        <?=$lng->Gettextlng($sub_item['title'])?>
                                    </a>
                                </li>
					<?php       else:
                    ?>
                                    ...<br />
                                    <a href="index.php?fuseaction=<?=$item['url']?>"><?=_("Показать&nbsp;все")?>&nbsp;&rarr;</a>
                    <?php
                                    break;
                                endif;
                            $j++;
                            endforeach;
						  endif; ?>
				</ul>
			<?php
		}

		?><div class="mp_top_header">&nbsp;</div><?php

		$i = 0;
		if($rsTop):
			foreach($rsTop as $m_item):
				if ($i<3):
?><div class="mainPageElFL <?=$classFromUrl[$m_item['url']]?>">
					<?=showMPElement($m_item);?>
				</div>
<?php
				elseif ($i==3):
?>
				<div class="floatNone"></div>
				<div class="mainPageElSL <?=$classFromUrl[$m_item['url']]?>">
					<?=showMPElement($m_item);?>
				</div>
<?php
				else:
?>
				<div class="mainPageElSL <?=$classFromUrl[$m_item['url']]?>">
					<?=showMPElement($m_item);?>
				</div>
<?php
				endif;
			$i++;
			endforeach;
		endif;
?><div class="mainPageElNotice">
	<p class="mp_element_notice"><?=_("Справка")?>:</p>
	<div class="mp_help_info">&nbsp;</div>
</div>

<script type="text/javascript">
	function mOver(el) {
		var str = $(el).children("a").attr("helpstr");
		$(".mp_help_info").html(str == '' ? "&nbsp;" : str);
	}
</script>