<?
session_start();
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

		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=home');"><img src="gfx/buttons/<?=selection("home", "true").$_SESSION['language']?>_home.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=werkgebied');"><img src="gfx/buttons/<?=selection("werkgebied").$_SESSION['language']?>_werkgebied.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=werkwijze');"><img src="gfx/buttons/<?=selection("werkwijze").$_SESSION['language']?>_werkwijze.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=kandidaten');"><img src="gfx/buttons/<?=selection("kandidaten").$_SESSION['language']?>_kandidaten.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=opdrachtgevers');"><img src="gfx/buttons/<?=selection("opdrachtgevers").$_SESSION['language']?>_opdrachtgevers.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=referenties');"><img src="gfx/buttons/<?=selection("referenties").$_SESSION['language']?>_referenties.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=biografie');"><img src="gfx/buttons/<?=selection("biografie").$_SESSION['language']?>_biografie.jpg" border="0" /></div>
		<div class="menu_item" onclick="ajaxget('refresh', 'ajax/inc_refresh.php?p=contact');"><img src="gfx/buttons/<?=selection("contact").$_SESSION['language']?>_contact.jpg" border="0" /></div>
	
		<div id="language">
			<div class="language_btn" onclick="location.href='index.php?language=nl';"><img src="gfx/nl.jpg" border="0" /></div>
			<div class="language_btn" onclick="location.href='index.php?language=en';"><img src="gfx/en.jpg" border="0" /></div>
		</div>

	</div>
</div>

<div id="center">
	<?
		if(!$_GET['p'])
		{
		//	$_GET['p'] = "home";
			include 'inc_home.php';
		}else{
		include '/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/inc_'.$_GET['p'].'.php';
		}
	?>
</div>