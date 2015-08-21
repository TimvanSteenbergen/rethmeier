<?
$string = "SELECT * FROM `tbl_contents` WHERE `content_id` = '24'";
$query = mysql_query($string) or die(mysql_error());
$result = mysql_fetch_assoc($query) or die(mysql_error());

$taal = array();
$taal['nl']['name'] = "Naam";
$taal['nl']['email'] = "E-mail adres";
$taal['nl']['subject'] = "Onderwerp";
$taal['nl']['submit_value'] = "Verstuur";
$taal['nl']['send'] = "Uw mail is verzonden.";
$taal['nl']['error'] = "Uw dient alle velden in te vullen!";
$taal['nl']['submit_value'] = "Verstuur";
$taal['nl']['route'] = "Route beschrijving";

$taal['en']['name'] = "Name";
$taal['en']['email'] = "E-mail adress";
$taal['en']['subject'] = "Subject";
$taal['en']['submit_value'] = "Send";
$taal['en']['send'] = "Your mail is send.";
$taal['en']['error'] = "Please fill out all fields!";
$taal['en']['submit_value'] = "Submit";
$taal['en']['route'] = "Directions";

$name = $taal[$_SESSION['language']]['name'];
$email = $taal[$_SESSION['language']]['email'];
$subject = $taal[$_SESSION['language']]['subject'];
$message = "";
$fout = false;

if(isset($_POST['name']))
{
	if($_POST['name'] != "" && $_POST['name'] != $taal[$_SESSION['language']]['name'])
	{
		$name = $_POST['name'];
	}
	else
	{
		$fout = true;
	}
}

if(isset($_POST['email']))
{
	if($_POST['email'] != "" && $_POST['email'] != $taal[$_SESSION['language']]['email'])
	{
		$email = $_POST['email'];
	}
	else
	{
		$fout = true;
	}
}

if(isset($_POST['subject']))
{
	if($_POST['subject'] != "" && $_POST['subject'] != $taal[$_SESSION['language']]['subject'])
	{
		$subject = $_POST['subject'];
	}
	else
	{
		$fout = true;
	}
}

if(isset($_POST['message']))
{
	if($_POST['message'] != "")
	{
		$message = $_POST['message'];
	}
	else
	{
		$fout = true;
	}
}

if($fout == false && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message']))
{
	//$recipient = "mjwallink@hotmail.com";
	$recipient = "info@rethmeieres.com";
	$mail_body = '
	<body>
	<p>
	Deze mail is verstuurd via de website www.rethmeier.com
	</p>
	<strong>Naam:</strong>&nbsp;'.$name.'<br/>
	<strong>E-mail adres:</strong>&nbsp;'.$email.'<br/>
	<strong>Onderwerp:</strong>&nbsp;'.$subject.'<br/>
	<br/>
	<strong>Bericht:</strong><br/>
	'.$message;
	
	$string = array("�","�","è","é");
	$replace = array("&egrave;","&eacute;","&egrave;","&eacute;");
	$mail_body_rep = str_replace($string,$replace,$mail_body);

	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: ';

	mail($recipient, $subject, $mail_body_rep, $headers.$email);
}

?>

<div id="pictures">
	<div class="pic_left"><img src="images/<?=$result['pic_left']?>" border="0" width="120" height="120" /></div>
	<div class="pic_center"><img src="images/<?=$result['pic_center']?>" border="0" width="120" height="120" /></div>
	<div class="pic_right"><img src="images/<?=$result['pic_right']?>" border="0" width="120" height="120" /></div>
</div>
<div id="content">
	<div class="titel"><?=$result['content_title_'.$_SESSION['language']]?></div>
	<div class="text" style="float:left; width:150px;"><?=$result['content_'.$_SESSION['language']]?>
	<br/><a href="route_<?=$_SESSION['language']?>.pdf" class="std_link" style="text-decoration:underline;"><?=$taal[$_SESSION['language']]['route']?></a>
	</div>
</div>
<div id="content" style="margin-top:20px;">

<?
if($fout == false && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message']))
{
	echo '<div class="text">'.$taal[$_SESSION['language']]['send'].'</div>';
}
else
{
	if(isset($_POST['name']) || isset($_POST['email']) || isset($_POST['subject']) || isset($_POST['message']))
	{
		echo '<div class="text">'.$taal[$_SESSION['language']]['error'].'</div>';
	}
?>
<form name="mailform" method="post" onSubmit="ajaxpost('refresh','mailform','ajax/inc_refresh.php?p=contact')">
<table>
	<tr>
		<td>
			<input type="text" name="name" class="contact_input" value="<?=$name?>" <? echo ($name == $taal[$_SESSION['language']]['name']) ? 'onclick="this.value=\'\';"' : ''; ?> />
		</td>
	</tr>
	<tr>
		<td>
			<input type="text" name="email" class="contact_input" value="<?=$email?>" <? echo ($email == $taal[$_SESSION['language']]['email']) ? 'onclick="this.value=\'\';"' : ''; ?> />
		</td>
	</tr>
	<tr>
		<td>
			<input type="text" name="subject" class="contact_input" value="<?=$subject?>" <? echo ($subject == $taal[$_SESSION['language']]['subject']) ? 'onclick="this.value=\'\';"' : ''; ?> />
		</td>
	</tr>
	<tr>
		<td>
			<textarea name="message" class="contact_text"><?=$message?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			<input type="submit" value="<?=$taal[$_SESSION['language']]['submit_value']?>" />
		</td>
	</tr>

</table>
</form>

<?
}
?>
</div>