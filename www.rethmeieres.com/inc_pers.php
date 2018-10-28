<?
$string = "SELECT * FROM `tbl_contents` WHERE `content_id` = '28'";
$query = mysqli_query($con, $string) or die(mysqli_error());
$result = mysqli_fetch_assoc($query) or die(mysqli_error());
?>

<div id="pictures">
	<div class="pic_left"><img src="images/<?=$result['pic_left']?>" border="0" width="120" height="120" /></div>
	<div class="pic_center"><img src="images/<?=$result['pic_center']?>" border="0" width="120" height="120" /></div>
	<div class="pic_right"><img src="images/<?=$result['pic_right']?>" border="0" width="120" height="120" /></div>
</div>
<div id="content">
	<div class="titel"><?=$result['content_title_'.$_SESSION['language']]?></div>
	<div class="text">
	
	
	<div class="text" style="background-color:#FFFFFF;
float:right;
height:135px;
margin-bottom:20px;
margin-left:20px;
margin-right:15px;
margin-top:22px;
width:120px;"><img src="gfx/joost.jpg" border="1" /></div>
	<?=$result['content_'.$_SESSION['language']]?>
	
	</div>
</div>