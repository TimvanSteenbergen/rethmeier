<?
require('includes/configure.php');

$portal = DB_PORTAL;
$server = DB_SERVER;
$username = DB_SERVER_USERNAME;
$password = DB_SERVER_PASSWORD;
$database = DB_DATABASE;

$dbcnx = mysql_connect($server, $username, $password); 
mysql_select_db($database, $dbcnx); 

$host  = DB_SERVER; 
$user  = DB_SERVER_USERNAME; 
$pass  = DB_SERVER_PASSWORD; 
$db    = DB_DATABASE; 

 /*
	Fill configurationkeys with corresponding values, based upon the configuration databasetable
*/
$query = "SELECT * FROM configuration WHERE (configuration_key = 'ABONNEMENT' AND configuration_value = 'true') OR (configuration_key = 'CREDITS' AND configuration_value = 'true')";
$result = mysql_query($query);
while ($row = mysql_fetch_object($result)) {
	define($row -> configuration_key, $row -> configuration_value);
}//while

if ($_GET['logout']==1){
	setcookie("adminuserid", "", time()+31536000, "/");
	setcookie("adminuser", "", time()+31536000, "/");
	setcookie("adminpass", "", time()+31536000, "/");
	setcookie("providerid", "", time()+31536000, "/");
	unset($_COOKIE['adminuserid']);
	unset($_COOKIE['adminuser']);
	unset($_COOKIE['adminpass']);
	unset($_COOKIE['providerid']);
	$to = "index.php";
	?><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<? echo $to; ?>"><?
	return 0;
}elseif ($_POST['user'] != "" && $_POST['pass'] != ""){
	$query = "SELECT * FROM admin_users WHERE USER_LOGIN = '".$_POST['user']."' AND USER_PASS = '".$_POST['pass']."' AND USER_ACTIVE = 1 AND USER_ARCHIVE = 0";
	$exec = mysql_query($query);
	if (mysql_num_rows($exec) == 0){
		$to = "index2.php?failed=1";
	}else{
		while ($row = mysql_fetch_array($exec)){
			setcookie("adminuserid", $row['USER_ID'], time()+31536000, "/");	
			setcookie("adminuser", $_POST['user'], time()+31536000, "/");	
			setcookie("adminpass", $_POST['pass'], time()+31536000, "/");
			setcookie("providerid", $row['USER_PROVIDER_ID'], time()+31536000, "/");
			$pageQuery = "SELECT * FROM admin_user_rights LEFT JOIN admin_items ON admin_user_rights.ITEM_ID = admin_items.ITEM_ID WHERE USER_ID = ".$row['USER_ID']." ORDER BY RIGHT_ID LIMIT 1";
			$pageExec = mysql_query($pageQuery);
			$to = "index.php";
			while ($pageRow = mysql_fetch_array($pageExec)){
				$to = $pageRow['ITEM_URL'];
			}
		}
	}
	?><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<? echo $to; ?>"><?
	return 0;
}
if ($_COOKIE['adminuserid'] != ""){
	$onload = "onload=\"document.getElementById('submit').focus();\"";
}else{
	$onload = "onload=\"document.getElementById('user').focus();\"";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><? echo DB_PORTAL?> - Backoffice</title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
<style>
.fields {border:1px #666666 groove; background-color:#EEEEEE;font-family:Arial;FONT-SIZE:12px;color:#000000;}
.buttons{border:1px #666666 groove; background-color:#EEEEEE;font-family:Arial;FONT-SIZE:12px;font-weight:bold;color:#000000;}
.login	{background-image: url(images/login.png);height:248px;width:358px;}
BODY	{FONT-FAMILY:Arial;FONT-SIZE:12px;}
TD		{FONT-FAMILY:Arial;FONT-SIZE:12px;font-weight:bold;}
</style>
</head>
<body <?=$onload;?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
	<tr>
		<td align="center" valign="middle" width="100%">
			<form method="post" action="<?=$_PHP_SELF;?>" name="frmLoginAdmin">
			<table cellspacing="0" cellpadding="0">

						<tr>	
							<TD colspan="2"><table width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="31">&nbsp;</td>
									<td height="248" width="358"  background="images/login.png" valign="bottom" align="center"><table cellspacing="0" cellpadding="0">
										<tr>
											<td width=18>&nbsp;</td>
											<td width=282>Username</td>
                                           	<td width=18>&nbsp;</td>
										</tr>
										<tr>
											<td width=18>&nbsp;</td>
											<td width=282><input class="fields" type="Text" name="user" size="44" value="<? echo $_COOKIE['adminuser']; ?>"></td>
                                            <td width=18>&nbsp;</td>
										</tr>
										<tr>
											<td width=18>&nbsp;</td>
											<td width=282><br>Password</td>
                                            <td width=18>&nbsp;</td>
										</tr>
										<tr>
											<td width=18>&nbsp;</td>
											<td width=282><input class="fields" type="Password" name="pass" size="44" value="<? echo $_COOKIE['adminpass']; ?>"></td>
                                            <td width=18>&nbsp;</td>
										</tr>
										<tr>
                                            <td width=18>&nbsp;</td>
                                            <td width=282 align="right" colspan=1><br><input class="buttons" type="Submit" name="submit" value="Login"><br><br></td>
                                            <td width=18>&nbsp;</td>
										</tr>                                        
									</table></TD>
								</tr>
							</table></td>
						</tr>

					</table>
			</form>
		</td>
	</tr>
</table>

</body>
</html>
