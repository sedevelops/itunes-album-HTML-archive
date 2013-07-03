<?php



/* --------------------------------------------------------- */
//iTunes Connect Functions
include_once(dirname(__FILE__) . '/inc/functions.php');



if ($_POST['pwd'] == $password){
	
	$itunes_item_key = intval($_POST['key']);
	$itunes_item_artist = $_POST['artist'];
	$itunes_item_album = $_POST['album'];
	$itunes_item_album_year = $_POST['album_year'];
	$itunes_item_last_modified = $_POST['last_modified'];
	$itunes_item_coverimg = $_POST['coverimg'];
	
	$record = array(
		'key' => $itunes_item_key,
		'artist' => $itunes_item_artist,
		'album' => $itunes_item_album,
		'album_year' => $itunes_item_album_year,
		'last_modified' => $itunes_item_last_modified
	);
	
	echo itc_receive_itunes_record($record);
		
		
} else { 

	echo 'Password Error';

	
}


	
?>