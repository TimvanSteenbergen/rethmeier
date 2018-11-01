<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if(isset($_GET['language']))
{
	$_SESSION['language'] = $_GET['language'];
}
else if(!$_SESSION['language'])
{
	$_SESSION['language'] = $default_language;
}
?>