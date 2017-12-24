<div class="videoslider">
    <ul>
    <?php 
    	$video_array = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_VIDEO . " as vs WHERE vs.is_active = 1 GROUP BY vs.id ORDER BY vs.id DESC LIMIT 7");
    	foreach($video_array as $video) {
			echo '<iframe width="200" height="120" src="http://www.youtube.com/embed/' . $video['url'] . '" frameborder="0" allowfullscreen></iframe>';		
    	}
    ?>
    </ul>
</div>