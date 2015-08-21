<?
//session_start();

if($_GET['language'])
{
	$_SESSION['language'] = $_GET['language'];
}
else if(!$_SESSION['language'])
{
	$_SESSION['language'] = $default_language;
}
?>