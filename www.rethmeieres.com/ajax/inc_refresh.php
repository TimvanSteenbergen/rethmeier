<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
include_once "../functions/selection.php";
include "/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/translation/inc_translation.php";
include "../config/inc_language.php";
include "../config/inc_config.php";
?>

<div id="left">
	<div id="menu">
		
		<div id="logo" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=home');">
			<img src="gfx/logo.jpg" border="0" />
		</div>

        <?php $language = (isset($_SESSION['language'])?$_SESSION['language']:'nl');echo $language; ?>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=home');"><img src="gfx/buttons/<?php echo "selected_".$language?>_home.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=werkgebied');"><img src="gfx/buttons/<?php echo "selected_".$language ?>_werkgebied.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=werkwijze');"><img src="gfx/buttons/<?php echo "selected_".$language?>_werkwijze.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=kandidaten');"><img src="gfx/buttons/<?php echo "selected_".$language?>_kandidaten.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=opdrachtgevers');"><img src="gfx/buttons/<?php echo "selected_".$language?>_opdrachtgevers.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=referenties');"><img src="gfx/buttons/<?php echo "selected_".$language?>_referenties.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=biografie');"><img src="gfx/buttons/<?php echo "selected_".$language?>_biografie.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=pers');"><img src="gfx/buttons/<?php echo "selected_".$language?>_pers.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=contact');"><img src="gfx/buttons/<?php echo "selected_".$language?>_contact.jpg" border="0" /></div>
	
		<div id="language">
			<div class="language_btn" onclick="location.href='index.php?language=nl';"><img src="gfx/nl.jpg" border="0" /></div>
			<div class="language_btn" onclick="location.href='index.php?language=en';"><img src="gfx/en.jpg" border="0" /></div>
		</div>

<div class="menu_item"><a href="Privacy Statement Rethmeier Executive Search BV 16 mei 2018.pdf" target="_parent" style="color: black; text-decoration: none; font-size: 18px;">Privacy statement</a></div>
	</div>
</div>

<div id="center">
	<?php
		if(!isset($_GET['p']))
		{
			include '../inc_home.php';
//Bugfix: Omdat deze include niet meer inc_home.php toont, is hier een kopie van inc_home.php neergezet.
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
</div><?php
//Einde Bugfix: Omdat deze include niet meer inc_home.php toont, is hier een kopie van inc_home.php neergezet.
		}else{
			include '../inc_'.$_GET['p'].'.php';
		}
	?>
</div>