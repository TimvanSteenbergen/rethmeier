<?php
	
	/***************************************************************
	 * cssStyleOn - http://cssStyleOn.c0n.de 2008 by Stefan Baur
	 *--------------------------------------------------------------
	 *  src/browser_reload.php
	 *     try to provide a hack to let the user reload
	 *       modified stylesheet files
	 *--------------------------------------------------------------
	 *  TODO: any good ideas how to force the browser to reload
	 *        specific files are welcome...
	 **************************************************************/

global $browserReloadFiles;

if(!is_array($browserReloadFiles))$browserReloadFiles=array();

//$browserReloadFiles[]="_fck/fckstyles.xml"
//$browserReloadFiles[]="custom_styles.css";

function browserReloadAddFile($url)
	{
	global $browserReloadFiles;

	if(!in_array($url,$browserReloadFiles))$browserReloadFiles[]=$url;
	}

function browserReloadOutScripts()
	{
	global $browserReloadFiles;
?>
function __browser_reload(frame)
	{
	try
		{
		document.getElementById(frame).src=document.getElementById(frame).src;
		} catch( err ) { alert(err); }

	try
		{
		if( document.getElementById(frame).contentDocument && document.getElementById(frame).contentDocument.location && document.getElementById(frame).contentDocument.location.reload)
			document.getElementById(frame).contentDocument.location.reload(true);
		} catch( err ) { alert(err); }

	try
		{
		if( document.getElementById(frame).contentWindow && document.getElementById(frame).contentWindow.location && document.getElementById(frame).contentWindow.location.reload )
			document.getElementById(frame).contentWindow.location.reload(true);
		} catch( err ) { alert(err); }
	}

function browserForceReload()
	{
<? foreach($browserReloadFiles as $id => $file) { ?>
	__browser_reload('browserReloadFrame<?=$id?>');
<? } ?>
	}
<?
	}

function browserReloadOutContent()
	{
	global $browserReloadFiles;

	foreach($browserReloadFiles as $id => $file)
		{
?>
<iframe id="browserReloadFrame<?=$id?>" class="browserReloadFrame" src="<?=$file?>" width="775" height="200"></iframe>
<?
		}
	}

function outReloadTest()
	{
?>
<html>
<head>
<script type="text/javascript">
<? browserReloadOutScripts() ?>
</script>
</head>
<body onLoad="browserForceReload();">
<? browserReloadOutContent() ?>
<p>
<a href="javascript: browserForceReload();">reload</a>
</p>
</body>
<?
	}