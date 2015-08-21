<?
 
if ($_GET["act"] == 2) {
	$sql = "UPDATE admin_users SET USER_ACTIVE = '0' WHERE USER_ID = " . $_GET["id"];
}else{
	$sql = "UPDATE admin_users SET USER_ACTIVE = '1' WHERE USER_ID = " . $_GET["id"];
}
$result = @mysql_query($sql); 
 

$to = "index.php?page=adminusers";

?>

<META HTTP-EQUIV="Refresh" Content= "0; URL=<?php echo $to;?>">
 