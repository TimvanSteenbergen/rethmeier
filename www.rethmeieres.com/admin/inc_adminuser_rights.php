<?php
if (isset($_POST['save'])){
	$query = "DELETE FROM admin_user_rights WHERE USER_ID = ".$_GET['id'];
	$exec = mysql_query($query);
	foreach ($_POST as $key => $value){
		if ($value == "on"){
			$query = "INSERT INTO admin_user_rights (USER_ID, ITEM_ID) VALUES (".$_POST['userid'].", ".$key.")";
			$exec = mysql_query($query);
		}
	}
	$to = "index.php?page=adminusers";
	?><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<? echo $to; ?>"><?
	return 0;
}



$query = "SELECT * FROM admin_user_rights WHERE USER_ID = ".$_GET['id'];
$exec = mysql_query($query);
while ($row = mysql_fetch_array($exec)){
	$rightArr[$row['ITEM_ID']] = "checked";
}
?>
<span class="pageHeading">Administratie rechten toevoegen</span><BR><BR>

<form method="POST">
<input type=hidden name=save value=1>
<input type=hidden name=userid value=<?=$_GET['id'];?>>
<table cellpadding="0" cellspacing="0" width="200" style="font-family:verdana;font-size:10px;">
<?
$query = "SELECT * FROM admin_items";
$exec = mysql_query($query);
while ($row = mysql_fetch_array($exec)){
	extract($row);
	echo "<TR><TD>".$ITEM_DESCRIPTION."</TD><TD><input type='checkbox' name='".$ITEM_ID."' ".$rightArr[$ITEM_ID]."></TD></TR>";
}
?>
<TR height=10><TD colspan=2></TD></TR>
<tr><td colspan=2><input type="submit" value="Opslaan"></td></tr>
</table>
</form>

 
