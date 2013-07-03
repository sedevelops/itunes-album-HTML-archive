<?php 

include('inc/functions.php'); 



$offset = $_REQUEST['offset'];
$number = $_REQUEST['number'];
$orderby = $_REQUEST['orderby'];
$order = $_REQUEST['order'];

if($orderby == ''){ $orderby = 'itunes_album_year'; }
if($order == ''){ $order = 'ASC'; }

$query_vars = unserialize(base64_decode($_REQUEST['query_vars']));

//echo '<pre>'; var_dump($query_vars); echo '</pre>';

$album_query = itc_get_albums($number, $order, $orderby, $offset, $query_vars );
$albums = $album_query['records'];

foreach($albums as $album){ ?>

<li class="album-item" id="album-<?php echo $album['itunes_key']; ?>" onclick="javascript:revealAlbum('<?php echo $album['itunes_key']; ?>', '<?php echo $itunes_connect_url; ?>');">
					
						<img class="album-thumb" style="" src="<?php echo itc_make_album_cover_url($album['itunes_key'], '-thumb', $album['itunes_album_coverimg_ext']); ?>" title="<?php echo esc_attr($album['itunes_artist']); ?> - <?php echo esc_attr($album['itunes_album']); ?>" alt="<?php echo esc_attr($album['itunes_artist']); ?> - <?php echo esc_attr($album['itunes_album']); ?>" />
					
										
				</li>


<?php } ?>