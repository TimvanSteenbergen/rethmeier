<?
$query = "UPDATE ADMIN_USERS SET USER_ARCHIVE = 1 WHERE USER_ID = ".$_GET['id'];
$exec = mysql_query($query);
$to = "index.php?page=adminusers";
?><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<? echo $to; ?>">