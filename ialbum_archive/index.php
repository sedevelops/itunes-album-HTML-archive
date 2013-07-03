<?php include('inc/functions.php'); ?>

<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title><?php echo $site_title; ?></title>


  <link href='http://fonts.googleapis.com/css?family=Playfair+Display:700,900,700italic,900italic|Playfair+Display+SC:400' rel='stylesheet' type='text/css'>
  
  
  <link type="stylesheet" rel="stylesheet" href="stylesheets/main.min.css">

  
</head>
<body>
<?php
	
	if(isset($_POST['submit'])){
		
		
		$start_year = $_POST['display_options']['start_year'];
		$end_year = $_POST['display_options']['end_year'];
		
		if ($start_year != '' && $end_year != ''){
			$query_vars['year_range'] = array($_POST['display_options']['start_year'], $_POST['display_options']['end_year'] );
		}
		
		$orderby = $_POST['display_options']['orderby'];
		if($orderby == ''){ $orderby = 'itunes_album_year'; }
		
		$order = $_POST['display_options']['order'];
		if($order == ''){ $order = 'ASC'; }

		if ($_POST['keywords'] != ''){
			$query_vars['keywords'] = $_POST['keywords'];
		}
		
		$album_query = itc_get_albums('48', $order, $orderby, 0, $query_vars);
		
	} else {
		$album_query = itc_get_albums();
	}
	
	$albums = $album_query['records'];
	$total = $album_query['total_found'];
	
	

	
/* --------------------------------------------------------- */

	
	
?>
					
<meta id="orderby" value="<?php echo $orderby; ?>" />
<meta id="order" value="<?php echo $order; ?>" />
<meta id="query_vars" value="<?php echo base64_encode(serialize($query_vars) ) ; ?>" />

<meta id="total_found" value="<?php echo $total['FOUND_ROWS()']; ?>" />
<meta id="first_load" value="<?php echo sizeof($albums); ?>" />


<div class="row">
	
	<?php include('inc/filters.php'); ?>

</div>

<hr />

<div class="row">

		<ul id="albums" class="clearfix">

			<?php
			if (sizeof($albums)){
				foreach($albums as $album){ ?>
				
				<li class="album-item" id="album-<?php echo $album['itunes_key']; ?>" onclick="javascript:revealAlbum('<?php echo $album['itunes_key']; ?>', '<?php echo $itunes_connect_url; ?>');">
					
						<img class="album-thumb" style="" src="<?php echo itc_make_album_cover_url($album['itunes_key'], '-thumb', $album['itunes_album_coverimg_ext']); ?>" title="<?php echo esc_attr($album['itunes_artist']); ?> - <?php echo esc_attr($album['itunes_album']); ?>" alt="<?php echo esc_attr($album['itunes_artist']); ?> - <?php echo esc_attr($album['itunes_album']); ?>" />
					
										
				</li>
			<?php } ?>
		</ul>
		
		<?php } else { ?>
			<h2 class="talign-center"><em>Sorry, no album matches.</em></h2>
	<?php } ?>

</div>

		
		<div id="loadmoreajaxloader" style="display:none;"><center><img src="<?php echo $itunes_connect_url; ?>/inc/img/lg-loader.gif" /></center></div>


  <!-- Footer -->

  <div class="row">
      <hr />

  </div>
	
	
<div id="album-modal" class="reveal-modal xlarge">
  
  <div class="modal-content"><div class="bigtext">Album Title</div></div>
  
    <a class="close-reveal-modal">&#215;</a>

  
</div>
	
  
  <!-- Included JS Files (Compressed) -->
  <script src="js/jquery.js"></script>
  <script src="js/jquery.nouislider.min.js"></script>
  <script src="js/bigtext.js"></script>
  <script src="js/jquery.reveal.js" type="text/javascript"></script>
  <script src="js/app.js"></script>
  
</body>
</html>