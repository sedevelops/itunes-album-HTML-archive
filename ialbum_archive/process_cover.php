<?php



/* --------------------------------------------------------- */
//iTunes Connect Functions
include_once(dirname(__FILE__) . '/inc/functions.php');


if ($_POST['pwd'] == $password){
	
	$itunes_item_coverimg = $_POST['coverimg'];
	
	echo itc_process_itunes_coverimg($itunes_item_coverimg);
	
} else {

	echo 'password error';

}