<?php
define('DB_SERVER', 'sql211.unaux.com');
define('DB_USER', 'unaux_34018835');
define('DB_PASS', 'ndg1yrpln');
define('DB_NAME', 'unaux_34018835_khalid_a1nx');
define('BASE_URL', GetBaseUrl());

$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
date_default_timezone_set("America/Bahia");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
 
function GetBaseUrl() {

	// Определим протокол
	if(array_key_exists('HTTPS', $_SERVER) && ($_SERVER['HTTPS'] == 'on')) {
		$url = 'https://';
	} else {
		$url = 'http://';
	}

	$url .= $_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
	$parts = parse_url($url);

	if (substr($parts['path'],-1,1)=='/') {
		$parts['dirpath'] = $parts['path'];
	} else {
		$parts['dirpath'] = substr($parts['path'], 0, strrpos($parts['path'],'/') + 1);
	}
	
	if ((int)$_SERVER['SERVER_PORT'] != 80) {
		$url = $parts['scheme'].'://'.$parts['host'].':'.$_SERVER['SERVER_PORT'].$parts['dirpath'];
	} else {
		$url = $parts['scheme'].'://'.$parts['host'].$parts['dirpath'];	
	}

	return $url;
}
?>
