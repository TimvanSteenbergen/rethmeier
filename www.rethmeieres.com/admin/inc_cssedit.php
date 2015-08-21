<?php

	/***************************************************************
	 * cssStyleOn - http://cssStyleOn.c0n.de 2008 by Stefan Baur
	 *--------------------------------------------------------------
	 *  index.php
	 *     main entry point
	 **************************************************************/

	//import configuration options
global $cssStyleOn;

if(!isset($cssStyleOn) || !isset($cssStyleOn['BASE_PATH'])) // no config given yet
	{
	include_once("cssStyleOn/src/config.php"); //guess src dir relative from current
	}
else
	{
		// we already have a config, merge default values into it
	include_once(
		isset($cssStyleOn['SRC_PATH'])?
			$cssStyleOn['SRC_PATH']."/config.php":
			$cssStyleOn['BASE_PATH']."/src/config.php");
	}

if(!$cssStyleOn['PERMISSIONS']['USAGE'])
	{
	die($cssStyleOn['STRINGS']['ERROR_NOT_ALLOWED_USAGE']);
	}

	// csseditor requested
if(isset($_REQUEST['show_editor']))
	{
	include($cssStyleOn['SRC_PATH']."/cssedit.php");
	cssedit_layout();
	exit;
	}

	// cssmanager requested
include($cssStyleOn['SRC_PATH']."/cssmanager.php");
cssmanager_layout();
?>