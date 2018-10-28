<?php

	// /*$user = "rethmeier";
	// $pass = "!!rethmeier!#";
	// $database = "rethmeier";
	// $host = "84.241.129.5";*/
	$user = "md143904db126857";
	$pass = "cg0pizVH";
	$database = "md143904db126857";
	$host = "db.rethmeieres.com";
// /*
// 	if($_SERVER['DOCUMENT_ROOT'] == 'C:/wamp/www/') {
// 		$user = "test";
// 		$pass = "test";
// 		$database = "rethmeier";
// 		$host = "localhost";
// 	}*/
$con = @mysqli_connect($host, $user, $pass, $database);
if (!$con) {
    echo "Error: " . mysqli_connect_error();
	exit();
}
?>