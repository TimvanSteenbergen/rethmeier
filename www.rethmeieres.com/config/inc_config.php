<?php

/* Lokale Server tieka.nl bij antagonist.nl*/
// $user = "deb103833_rethmeieres";
// $pass = "cg0pizVH";
// $database = "deb103833_rethmeieres";
// $host = "localhost";

/* Server rethmeieres.com bij mijndomein.com*/
	$user = "md143904db126857";
	$pass = "cg0pizVH";
	$database = "md143904db126857";
	$host = "db.rethmeieres.com";

// 
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
// echo 'Connected to my MySQL';
?>