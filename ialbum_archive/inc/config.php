<?php

/* ========================================== */
/* BEGIN USER CONFIG */
/* ========================================== */


// This password must match the password values you set in the .scrptd applescript file.
// ONLY USE: A-Z, a-z, 0-9, ! _ - +
// Go here: http://textmechanic.com/Random-String-Generator.html 
// Set the allowed characters to:  abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-+_!
// And set the length of the gerneated password to 30 or more.
// Then paste your password below.
$password = '';


$site_title = 'My Album Archive';



//Set the variables below to reflect the location and credentials for your MySQL server
$mysql_host = 'localhost';
$mysql_user = 'db_user';
$mysql_pwd = 'db_pass';
$mysql_db_name = 'db_name';


//BASE_DIRECTORY 
$itunes_connect_base = '/projects/';
/*
$itunes_connect_base variable:
This is the path to your itunes connect directory relative to your public html domain root.

   EG.
   if your iTunes Album Archive ("ialbum_archive" folder) is installed here:
   http://example.com/projects/ialbum_archive
   then you would enter:
   '/projects/'
   below
   (   MAKE SURE TO INCLUDE A TRAILING SLASH )
   
   
   if your iTunes Album Archive ("ialbum_archive" folder) is installed at the root of your site:
   http://example.com/ialbum_archive
   then you would enter:
   '/'
   below
   
   
*/ 


/* ========================================== */
/* END USER CONFIG */
/* ========================================== */





































/* ---[ Don't edit below this line, unless you're pro.  ;) ]--------------------------------------------- */

global $mysql_info, $itunes_connect_url, $itunes_connect_dir, $dbfile, $logfile, $coverimg_dir;

$mysql_info['host'] = $mysql_host;
$mysql_info['user'] = $mysql_user;
$mysql_info['pwd'] = $mysql_pwd;
$mysql_info['db_name'] = $mysql_db_name;

$itunes_connect_url = 'http://' . $_SERVER['HTTP_HOST'] . $itunes_connect_base . 'ialbum_archive/';
$itunes_connect_dir = $_SERVER['DOCUMENT_ROOT'] . $itunes_connect_base . 'ialbum_archive/';
$dbfile =  $itunes_connect_dir .  'itunes_db';
$logfile =  $itunes_connect_dir . 'logs/itunes_log';
$coverimg_dir = $itunes_connect_url . 'covers/';


ini_set("mysql.trace_mode", "0");

/* ========================================== */
/* MySQL DB check */
/* ========================================== */

function itc_init(){
	
	global $mysql_info;
		
	// Connect to MySQL
	$mysqli = new mysqli($mysql_info['host'], $mysql_info['user'], $mysql_info['pwd'], $mysql_info['db_name']);
	
	/* check connection */
	if ($mysqli->connect_errno) {
	    printf("Connect failed: %s\n", $mysqli->connect_error);
	    exit();
	}
	
	/* Create table doesn't return a resultset */
	$sql = "CREATE TABLE `itc_albums` (
 `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 `itunes_key` int(25) NOT NULL,
 `itunes_artist` VARCHAR(255) NOT NULL,
 `itunes_album` VARCHAR(255) NOT NULL,
 `itunes_album_year` VARCHAR(255) NOT NULL,
 `itunes_album_coverimg` VARCHAR(255) NOT NULL,
 `itunes_album_coverimg_ext` VARCHAR(5) NOT NULL,
  `date_modified` TIMESTAMP
 ) CHARACTER SET utf8 COLLATE utf8_general_ci"; 
	
	if ($mysqli->query($sql) === TRUE) {
	   //printf("Table itc_albums successfully created.\n");
	} 
	$mysqli->close();
	


}

itc_init();



?>