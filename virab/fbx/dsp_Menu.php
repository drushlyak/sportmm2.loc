<script type="text/javascript" src="/virab/js/menu.js"></script>
<script type="text/javascript">
	var __VirabMenuSRC = {
		<?php
			foreach ( $adminMenu as $amenu ):
				foreach ( $amenu['subcat'] as $vmenu ):
		?>
			'<?=$vmenu['id']?>' : '<?=$vmenu['url']?>',
		<?php	endforeach;
			endforeach; ?>
			'-' : '-'
	};
</script>
<div class="TVirabTITLE"></div>
<ul class="TVirabMenu">
	<?php
		$sel_page = str_replace('fuseaction=', '', $_this['arg']);
		if (preg_match('/fuseaction=([^.]*)./', $_this['arg'], $regs)) {
			$res_page = $regs[1];
		} else {
			$res_page = '#UNDEFINED#';
		}

		$t_virab_menu_state = explode(",", $_COOKIE['t_virab_menu_state']);
	?>
	<?php foreach ( $adminMenu as $amenu ): ?>
		<li id="mainMenu_ID_<?=$amenu['id']?>" class="TVMli fLI <?=in_array($amenu['id'], $t_virab_menu_state) ? "fLIcollapsed" : ""?>"><span><?=$lng->Gettextlng($amenu['title'])?></span>
			<ul>
				<?php foreach ( $amenu['subcat'] as $vmenu ): ?>
					<li id="mainMenu_ID_<?=$vmenu['id']?>" class="TVMli sLI <?=($vmenu['url'] == $sel_page) ? 'selected_node' : (preg_match('/^' . $res_page . './im', $vmenu['url']) ? 'selected_node_l' : '')?>">
						<a href="index.php?fuseaction=<?=$vmenu['url']?>" onClick="return false;">
							<?=$lng->Gettextlng($vmenu['title'])?>
							<?php if ($vmenu['url'] == $sel_page): ?>
								&nbsp;&rarr;
							<?php elseif (preg_match('/^' . $res_page . './im', $vmenu['url'])): ?>
								&nbsp;...
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
</ul>
