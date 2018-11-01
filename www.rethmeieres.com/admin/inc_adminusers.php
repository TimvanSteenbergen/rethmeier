<?php
if (!isset($_GET['order']) || $order == "DESC"){
	$order = "ASC";
}else{
	$order = "DESC";
}
if (!isset($_GET['orderby']) || $_GET['orderby'] == ""){
	$orderby = "USER_NAME";
}else{
	$orderby = $_GET['orderby'];
}
$query = "SELECT * FROM admin_users WHERE USER_ARCHIVE = 0 ORDER BY USER_NAME";

$exec = mysqli_query($query);
$totaalAantal = mysqli_num_rows($exec);
echo "<span class=\"pageHeading\">Administratie gebruikers (".$totaalAantal.")</span><BR><BR>"; 
echo "<a href=index.php?page=newadminuser>Voeg gebruiker toe</a>";
echo "<BR><BR>";

$query = "SELECT * FROM admin_users WHERE USER_ARCHIVE = 0 ORDER BY USER_NAME";

$exec = mysqli_query($query);
echo "<TABLE cellpadding=2 cellspacing=0 style=\"font-family:Verdana\" width=100%>";
echo "<TR class=\"dataTableHeadingRow\"><TD class=\"dataTableHeadingContent\"><a href=adminusers.php?orderby=USER_NAME&order=".$order."&search=".$_GET['search']."&page=".$_GET['page']."&recs=".$_GET['recs']." style=\"font-weight:bold;color:white\">Naam</a></TD><TD class=\"dataTableHeadingContent\"><a href=adminusers.php?orderby=USER_LOGIN&order=".$order."&search=".$_GET['search']."&page=".$_GET['page']."&recs=".$_GET['recs']." style=\"font-weight:bold;color:white\">Loginnaam</A></TD><TD class=\"dataTableHeadingContent\"><a href=adminusers.php?orderby=USER_EMAIL&order=".$order."&search=".$_GET['search']."&page=".$_GET['page']."&recs=".$_GET['recs']." style=\"font-weight:bold;color:white\">E-mail</a></TD><TD class=\"dataTableHeadingContent\"><a href=adminusers.php?orderby=USER_ACTIVE&order=".$order."&search=".$_GET['search']."&page=".$_GET['page']."&recs=".$_GET['recs']." style=\"font-weight:bold;color:white\">Actief</a></TD><TD class=\"dataTableHeadingContent\">&nbsp</TD></TR>";
if (mysqli_num_rows($exec)>0){
	while ($row = mysqli_fetch_array($exec)){
		extract($row);
		if ($USER_ACTIVE == '1') {
			$tabgreen = "<img src=./images/green_on.bmp border=0>";
		}else{	
			$tabgreen = "<a href=index.php?page=setadminuseractief&id=" . $USER_ID . "&act=1><img src=./images/green_off.bmp border=0></a>";
		}
		if ($USER_ACTIVE == '0') {
			$tabred  = "<img src=./images/red_on.bmp border=0>";
		}else{	
			$tabred  = "<a href=index.php?page=setadminuseractief&id=" . $USER_ID . "&act=2><img src=./images/red_off.bmp border=0></a>";
		}
		echo "<TR onMouseOver=\"this.bgColor='#C0C0C0';this.style.cursor='hand';this.style.color='white';\" onMouseOut=\"this.bgColor='white';this.style.color='black';\" style=\"font-size:10px\"><TD><a href=index.php?page=newadminuser&id=".$USER_ID.">".$USER_NAME."</a></TD><TD>".$USER_LOGIN."</TD><TD>".$USER_EMAIL."</TD><TD>".$tabgreen." ".$tabred."<TD align=right>[ <a href=\"index.php?page=adminuser_rights&id=".$USER_ID."\">rechten</a> ] [ <a href=# onClick=\"confirm_del('index.php?page=archiveadminuser&id=".$USER_ID."', '".addslashes($USER_NAME)."'); return false\">verwijder</a> ]</TD></TR>";
	}
}else{
	echo "<TR style=\"font-size:10\"><TD colspan=6 align=center>Nog geen gebruikers toegevoegd!</TD></TR>";
}
echo "</TABLE>";
?>


 

 
