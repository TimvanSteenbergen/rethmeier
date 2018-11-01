<?php
include "../config/inc_config.php";
$string = "SELECT * FROM `tbl_contents` WHERE `content_id` = '2'";
$query = mysqli_query($con, $string) or die(mysqli_error());
$result = mysqli_fetch_assoc($query) or die(mysqli_error());
?>

<div id="pictures">
	<div class="pic_left"><img src="images/<?php echo $result['pic_left']?>" border="0" width="120" height="120" /></div>
	<div class="pic_center"><img src="images/<?php echo $result['pic_center']?>" border="0" width="120" height="120" /></div>
	<div class="pic_right"><img src="images/<?php echo $result['pic_right']?>" border="0" width="120" height="120" /></div>
</div>
<div id="content">
	<div class="titel"><?php echo $result['content_title_'.$_SESSION['language']]?></div>
	<div class="text"><?php echo $result['content_'.$_SESSION['language']]?></div>
</div>