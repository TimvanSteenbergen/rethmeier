<?PHP

	/*$user = "rethmeier";
	$pass = "!!rethmeier!#";
	$database = "rethmeier";
	$host = "84.241.129.5";*/
	$user = "md143904db126857";
	$pass = "cg0pizVH";
	$database = "md143904db126857";
	$host = "db.rethmeieres.com";
/*
	if($_SERVER['DOCUMENT_ROOT'] == 'C:/wamp/www/') {
		$user = "test";
		$pass = "test";
		$database = "rethmeier";
		$host = "localhost";
	}*/
	
	mysql_connect($host,$user,$pass) or die (mysql_error());
	mysql_select_db($database) or die(mysql_error());
?>