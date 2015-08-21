<?
$string = "SELECT * FROM `tbl_contents` WHERE `content_id` = '23'";
$query = mysql_query($string) or die(mysql_error());
$result = mysql_fetch_assoc($query) or die(mysql_error());
?>

<div id="pictures">
	<div class="pic_left"><img src="images/<?=$result['pic_left']?>" border="0" width="120" height="120" /></div>
	<div class="pic_center"><img src="images/<?=$result['pic_center']?>" border="0" width="120" height="120" /></div>
	<div class="pic_right"><img src="images/<?=$result['pic_right']?>" border="0" width="120" height="120" /></div>
</div>
<div id="content">
	<div class="titel"><?=$result['content_title_'.$_SESSION['language']]?></div>
	<div class="text"><?=$result['content_'.$_SESSION['language']]?></div>
</div>