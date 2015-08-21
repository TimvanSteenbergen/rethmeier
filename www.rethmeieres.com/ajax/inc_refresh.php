<?
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
include_once "/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/functions/selection.php";
//include "/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/translation/inc_translation.php";
include "/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/config/inc_language.php";
include "/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/config/inc_config.php";
?>

<div id="left">
	<div id="menu">
		
		<div id="logo" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=home');">
			<img src="gfx/logo.jpg" border="0" />
		</div>

        <?php echo 'sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss'; $language = (isset($_SESSION['language'])?$_SESSION['language']:'nl');echo $language; ?>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=home');"><img src="gfx/buttons/<?=selection("home", "true").$language?>_home.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=werkgebied');"><img src="gfx/buttons/<?=selection("werkgebied").$language?>_werkgebied.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=werkwijze');"><img src="gfx/buttons/<?=selection("werkwijze").$language?>_werkwijze.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=kandidaten');"><img src="gfx/buttons/<?=selection("kandidaten").$language?>_kandidaten.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=opdrachtgevers');"><img src="gfx/buttons/<?=selection("opdrachtgevers").$language?>_opdrachtgevers.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=referenties');"><img src="gfx/buttons/<?=selection("referenties").$language?>_referenties.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=biografie');"><img src="gfx/buttons/<?=selection("biografie").$language?>_biografie.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=contact');"><img src="gfx/buttons/<?=selection("contact").$language?>_contact.jpg" border="0" /></div>
	
		<div id="language">
			<div class="language_btn" onclick="location.href='index.php?language=nl';"><img src="gfx/nl.jpg" border="0" /></div>
			<div class="language_btn" onclick="location.href='index.php?language=en';"><img src="gfx/en.jpg" border="0" /></div>
		</div>

	</div>
</div>

<div id="center">
	<?
		if(!isset($_GET['p']))
		{
		//	$_GET['p'] = "home";
			include 'inc_home.php';
		}else{
		include '/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/inc_'.$_GET['p'].'.php';
		}
	?>
</div>