<?

	include ("includes/application_top.php");
 
if ($_GET["act"] == 2) {
	//$sql = "UPDATE TBL_TABS SET TAB_VISIBLE = 'F' WHERE TAB_ID = " . $_GET["ID"];
	$sql = "UPDATE TBL_CONTENTS SET content_visible = 'F' WHERE CONTENT_ID = " . $_GET["id"];
}else{
	//$sql = "UPDATE TBL_TABS SET TAB_VISIBLE = 'T' WHERE TAB_ID = " . $_GET["ID"];
	$sql = "UPDATE TBL_CONTENTS SET content_visible = 'T' WHERE CONTENT_ID = " . $_GET["id"];
}
	  
$result = @mysql_query($sql); 
 
mysql_close ();
$to = 'index.php?page=cmsartikelen';

?>

<META HTTP-EQUIV="Refresh" Content= "0; URL=<?php echo $to;?>">
 