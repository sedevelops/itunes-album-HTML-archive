<?php




include_once('config.php');


//image resizer class
include( 'resize-class.php');


//logger class
include_once( 'logger-class.php');

/* ========================================== */
/* Backend Functions */
/* ========================================== */


function itc_get_dominant_color($filename) {

	global $coverimg_dir;
	
	$filePathInfo = pathinfo($coverimg_dir . $filename);
	
	if( $filePathInfo['extension'] == 'png' ){
		 $image = imagecreatefrompng($coverimg_dir . $filename);
	} else {
		 $image = imagecreatefromjpeg($coverimg_dir . $filename);
	}
	
	$width = imagesx($image);
	$height = imagesy($image);
	$pixel = imagecreatetruecolor(1, 1);
	imagecopyresampled($pixel, $image, 0, 0, 0, 0, 1, 1, $width, $height);
	$rgb = imagecolorat($pixel, 0, 0);
	$color = imagecolorsforindex($pixel, $rgb);
	
	$color = rgb2html($color['red'], $color['green'], $color['blue'] );
	
	return $color;
}

function rgb2html($r, $g=-1, $b=-1)
{
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    
    return $color;
}



function imagemostcommoncolor($filename, $num_colors = 2)
{

	global $coverimg_dir;
	
	$filePathInfo = pathinfo($coverimg_dir . $filename);
	
	if( $filePathInfo['extension'] == 'png' ){
		 $image = imagecreatefrompng($coverimg_dir . $filename);
	} else {
		 $image = imagecreatefromjpeg($coverimg_dir . $filename);
	}


    $width = imagesx($image);
    $height = imagesy($image);
    $colors = array();
    
    for ($x = 0; $x < $width; $x++)
    {
        for ($y = 0; $y < $height; $y++)
        {
            $index = imagecolorat($image, $x, $y);
            $rgb = imagecolorsforindex($image, $index);
            $hexcolor = rgbhex($rgb['red'], $rgb['green'], $rgb['blue']);
            
            if (isset($colors[$hexcolor]))
            {
                $colors[$hexcolor]++;
            }
            else
            {
                $colors[$hexcolor] = 1;
            }
        }
    }
    
    arsort($colors);
    array_splice($colors, $num_colors);
    return array_keys($colors);
}

function rgbhex($red, $green, $blue)
{
    return sprintf('%02X%02X%02X', $red, $green, $blue);
}

/* --------------------------------------------------------- */

function itc_get_text_color($brightness){
	
	if ($brightness > 130){
		return 'rgba(0,0,0,.5)';
	} else {
		return 'rgba(255,255,255,.7)';
	}
	
}


function itc_get_border_color($brightness){
	
	if ($brightness > 130){
		return 'aaaaaa';
	} else {
		return '999999';
	}
	
}

function itc_get_color_brightness($hex){

	// returns brightness value from 0 to 255
	
	// strip off any leading #
	$hex = str_replace('#', '', $hex);
	
	$r = hexdec(substr($hex, 0, 2));
	$g = hexdec(substr($hex, 2, 2));
	$b = hexdec(substr($hex, 4, 2));
	
	$brightness = (0.299*$r + 0.587*$g + 0.114*$b);
	
	//$brightness =  (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
	
	return $brightness;
	
}

function itc_receive_itunes_record($itunes_record){
	
	global $mysql_info, $logfile;
	
	$log = new logger($logfile); //if file doesnt exiists, will be created

	$log->log_start(); //this will insert timestap to mark the beginning

	$log->log_message('[ RECEIVER Initiated ]');
	
	$itunes_item_key = $itunes_record['key'];
	$itunes_item_artist = $itunes_record['artist'];
	$itunes_item_album = $itunes_record['album'];
	$itunes_item_album_year = $itunes_record['album_year'];
	$itunes_item_last_modified = $itunes_record['last_modified'];
	
	// Connect to MySQL
	$mysqli = new mysqli($mysql_info['host'], $mysql_info['user'], $mysql_info['pwd'], $mysql_info['db_name']);
	
	/* Select queries return a resultset */
	$check_sql = "SELECT id, date_modified FROM itc_albums 
				  WHERE itunes_key = '$itunes_item_key' 
				  OR (itunes_album = '$itunes_item_album' AND itunes_artist = '$itunes_item_artist' AND itunes_album_year = '$itunes_item_album_year') 
				  LIMIT 1";
	
	if ($check = $mysqli->query($check_sql)) {
	    
		if( $check->num_rows == 0 ){
			//IF NEW RECORD

			$sql = "INSERT INTO itc_albums (itunes_key, itunes_artist, itunes_album, itunes_album_year) VALUES ('$itunes_item_key', '$itunes_item_artist', '$itunes_item_album', '$itunes_item_album_year')";
			
			if($mysqli->query($sql)){
				$return = 'send_coverimg';
				$log->log_message('[ New record added ]');
				$log->log_message( json_encode($itunes_record) );
			} else {
				$return = $mysqli->error;
				$log->log_message('[ Error adding new record ]');
				$log->log_message( $mysqli->error );
			}
			
		} else {
			
		    while ($row = $check->fetch_assoc()) {
		        $update_row_id = $row["id"];
		        $date_modified = $row['date_modified'];
		    }
			

			if ($date_modified < $itunes_item_last_modified){
			
				$sql = "UPDATE itc_albums 
				SET itunes_key = '$itunes_item_key', 
				itunes_artist = '$itunes_item_artist', 
				itunes_album = '$itunes_item_album', 
				itunes_album_year = '$itunes_item_album_year' 
				WHERE id = $update_row_id";
				
				if($mysqli->query($sql)){
					$return = 'send_coverimg';
					$log->log_message('[ Existing record updated ]');
					$log->log_message( json_encode($itunes_record) );
				} else {
					$return = $mysqli->error;
					$log->log_message('[ Error updating existing record ]');
					$log->log_message( $mysqli->error );
				}
				
				
			} else {
				//Repeat… don't update and don't send cover
				$return = 'dont_send';
				$log->log_message('[ Record exists and has not changed ]');
				$log->log_message( json_encode($itunes_record) );
			}
		}
		$check->close();
		$mysqli->close();
		$log->log_end(); //this will insert timestap to mark the end of current log

		return $return;
	}
}



function itc_process_itunes_coverimg($itunes_item_coverimg){
	
	global $mysql_info, $logfile;
	
	
	$log = new logger($logfile); //if file doesnt exiists, will be created

	$log->log_start(); //this will insert timestap to mark the beginning

	$log->log_message('[ COVER PROCESSOR Initiated ]');
	
	
	// *** 1) Initialise / load image
	$resizeObj = new resize('covers/' . $itunes_item_coverimg);
	// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
	$resizeObj -> resizeImage(500, 500, 'auto');
	// *** 3) Save image
	
	$coverImageNameBits = explode('.', $itunes_item_coverimg);
	
	$itunes_key = $coverImageNameBits[0];
	
	$thumb_name = $itunes_key . '-500.' . $coverImageNameBits[1];
	
	$resizeObj -> saveImage('covers/' . $thumb_name , 80);
	
	// *** 1) Initialise / load image
	$resizeObj = new resize('covers/' . $itunes_item_coverimg);
	// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
	$resizeObj -> resizeImage(200, 200, 'auto');
	// *** 3) Save image
	
	$coverImageNameBits = explode('.', $itunes_item_coverimg);
	
	$itunes_key = $coverImageNameBits[0];
	
	$thumb_name = $itunes_key . '-thumb.' . $coverImageNameBits[1];
	
	$resizeObj -> saveImage('covers/' . $thumb_name , 80);
	
	
	
	$log->log_message('[ thumb processed ]');
	
		
	$bg_color = imagemostcommoncolor( $thumb_name, 1);
	$bg_color = $bg_color[0];
	
	if ($bg_color == 0){
		$bg_color = itc_get_dominant_color($thumb_name);
	}
	
	$brightness = itc_get_color_brightness($bg_color);
	
	$ext = $coverImageNameBits[1];
	
	// Connect to MySQL
	$mysqli = new mysqli($mysql_info['host'], $mysql_info['user'], $mysql_info['pwd'], $mysql_info['db_name']);
	

	$sql = "UPDATE itc_albums 
			SET itunes_album_coverimg = '$itunes_item_coverimg', 
			itunes_album_coverimg_ext = '$ext', 
			bgcolor = '$bg_color', 
			brightness = '$brightness' 
			WHERE itunes_key = '$itunes_key'";
			
	if($mysqli->query($sql)){
		$return = 'image processed!';
		$log->log_message('[ Cover filenames added to DB ]');
		$log->log_message( 'cover: ' . $itunes_item_coverimg . "\n" . 'ext: ' . $ext );
	} else {
		$return = $mysqli->error;
		$log->log_message('[ Error adding cover filenames to DB ]');
		$log->log_message( $mysqli->error );
	}

	
	$mysqli->close();
	return $return;

}



/* ========================================== */
/* Front-end Functions */
/* ========================================== */


function itc_get_album($itunes_key){

	global $mysql_info;

	$mysqli = new mysqli($mysql_info['host'], $mysql_info['user'], $mysql_info['pwd'], $mysql_info['db_name']);
	
	$sql = "SELECT * FROM itc_albums WHERE itunes_key = '$itunes_key' LIMIT 1;";
	
	
	
	if($result = $mysqli->query($sql)){
		
		while ($row = $result->fetch_assoc()){
			$album_row = $row;
		}
		$result->free();

		$mysqli->close();
		return $album_row;
		
	} else {
		$return = $mysqli->error;
		$mysqli->close();
		return $return;

	}

	

}



function itc_get_albums($number='48', $order='ASC', $orderby='itunes_album_year', $offset=0, $query_vars = array() ){
	
	global $mysql_info;

	$mysqli = new mysqli($mysql_info['host'], $mysql_info['user'], $mysql_info['pwd'], $mysql_info['db_name']);

	if(sizeof($query_vars)){
		if( isset($query_vars['year_range']) && $query_vars['year_range'][0] != '' && $query_vars['year_range'][1] != '' ){
			
			$start_year = $query_vars['year_range'][0];
			$end_year = $query_vars['year_range'][1];
			
			$where_clause = " WHERE itunes_album_year BETWEEN $start_year AND $end_year ";
			
		}
		if(isset($query_vars['keywords'])){
			$keywords = $query_vars['keywords'];
			$keywords = str_replace(' ', '% ', $keywords);
			$keywords = '%' . $keywords . '%';
			
		}	
		
	} else {
		$where_clause = '';
	}
	
	if(!isset($orderby)){
		$orderby = 'itunes_album_year';
	}
	
	if(isset($keywords)){
		
		if($where_clause == ''){
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM itc_albums WHERE lower(concat_ws(' ',itunes_album,itunes_artist)) like lower('$keywords') ORDER BY $orderby $order LIMIT $number OFFSET $offset;";
		} else {
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM itc_albums $where_clause AND lower(concat_ws(' ',itunes_album,itunes_artist)) like lower('$keywords') ORDER BY $orderby $order LIMIT $number OFFSET $offset;";
		}
		
		$sql .= "SELECT FOUND_ROWS()";

	} else {
	
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM itc_albums $where_clause ORDER BY $orderby $order LIMIT $number OFFSET $offset;";
		$sql .= "SELECT FOUND_ROWS()";
	}
	
	/* execute multi query */
	if ($mysqli->multi_query($sql)) {
		$count = 0;
	    do {
	    	$count++;
	        /* store first result set */
	        if ($result = $mysqli->store_result()) {
	            while ($row = $result->fetch_assoc()) {
	            	if($count == 1){
	              		$results['records'][] = $row;
	                } else {
	                	$results['total_found'] = $row;
	                }
	            }
	            $result->free();
	        }
	        
	    } while ($total = $mysqli->next_result() );
	}
	
	return $results;
	/* close connection */
	$mysqli->close();
	
}

function itc_make_album_cover_url($itunes_key, $size_str, $ext){
	
	global $coverimg_dir;
	
	return $coverimg_dir . $itunes_key . $size_str . '.' . $ext;
	
}



function esc_attr($string){

	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

}
?>