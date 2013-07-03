<?php
include('functions.php');

$itunes_key = $_REQUEST['itunes_key'];

$album = itc_get_album($itunes_key);

?>


<div class="reveal-contents">
						
	<div class="clearfix" style="background-color:#<?php echo $album['bgcolor']; ?>; color:<?php echo itc_get_text_color($album['brightness']); ?>;">
	
		<div class="cover">
			<img class="album-thumb" src="<?php echo itc_make_album_cover_url($album['itunes_key'], '-500', $album['itunes_album_coverimg_ext']); ?>" title="<?php echo esc_attr($album['itunes_artist']); ?> - <?php echo esc_attr($album['itunes_album']); ?>" alt="<?php echo esc_attr($album['itunes_artist']); ?> - <?php echo esc_attr($album['itunes_album']); ?>" />
		</div>
		
		<div class="desc">
			<div class="year playfair900 talign-right slim" style="color:#<?php echo itc_get_text_color($album['brightness']); ?>;"><?php echo $album['itunes_album_year']; ?></div>
			<div class="artist small-caps" style="color:<?php echo itc_get_text_color($album['brightness']); ?>;"><?php echo $album['itunes_artist']; ?></div>
			
			<div class="bigtext album slim-top playfair900"><div class="" style="color:<?php echo itc_get_text_color($album['brightness']); ?>;"><?php echo $album['itunes_album']; ?></div></div>

		</div>
		
	</div>
	
</div>