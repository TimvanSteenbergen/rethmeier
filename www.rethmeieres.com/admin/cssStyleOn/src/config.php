<?php

	/***************************************************************
	 * cssStyleOn - http://cssStyleOn.c0n.de 2008 by Stefan Baur
	 *--------------------------------------------------------------
	 *  src/config.php
	 *     (default) configuration settings
	 *--------------------------------------------------------------
	 *  TODO: correctly merge cssStyleOn['STRINGS'] (another array merge?)
	 **************************************************************/

global $cssStyleOn;

$cssStyleOn_default=array();

$cssStyleOn_default['REQUEST_URL']='index.php?page=cssedit&selected_box=frontsite&';

	// cssStyleOn is located here (defaults to current directory we were called from)
$cssStyleOn_default['BASE_PATH']='./cssStyleOn/';
$cssStyleOn_default['WEBROOT_PATH']='./cssStyleOn/';

	// default: disallow everything for security reasons
$cssStyleOn_default['PERMISSIONS']=array(
	'USAGE' => true,
	'VIEWSERVER' => true,
	'EXPORT' => true,
	'IMPORT' => false,
	'SAVE' => true );


	// mapping tags -> tabs
$cssStyleOn_default['MAPPING_TAG2TAB']=array(
	'p'=>'paragraph', 'pre'=>'paragraph', 'div'=>'paragraph',
	'ul'=>'list', 'ol'=>'list', 'li'=>'list',
	'h1'=>'headline', 'h2'=>'headline', 'h3'=>'headline', 'h4'=>'headline', 'h5'=>'headline', 'h6'=>'headline',
	'span'=>'character',
	'img'=>'image');

	// mapping tabs -> more info
$cssStyleOn_default['MAPPING_TAB2INFO']=array(
	'paragraph' => array( 'title' => 'Paragraph' ),
	'list' => array( 'title' => 'List' ),
	'headline' => array( 'title' => 'Headline' ),
	'character' => array( 'title' => 'Character' ),
	'image' => array( 'title' => 'Image' ) );

	// string table
$cssStyleOn_default['STRINGS']=array(
	'ERROR_NOT_ALLOWED_USAGE' => 'you are not allowed to use <a href="http://cssStyleOn.c0n.de/">cssStyleOn</a> or configuration settings are not setup corretly.',
//	'ERROR_NOT_ALLOWED_EXPORT' => 'not allowed to export.',
//	'ERROR_NOT_ALLOWED_IMPORT' => 'not allowed to import.',
//	'ERROR_NOT_ALLOWED_SAVE' => 'not allowed to save.',
	'TEXT_BANNER_DISCLAIMER' => 'Note: This is a tech-preview only release.<br />Some functionality may still be broken.<br />If you find a serious bug, feel free to contact me on the <a href="http://cssStyleOn.c0n.de" target="_blank">cssStyleOn Website</a></p>',
	'NULL' => '');

/////////////////////////////////////////////////////////////////////////

	// merge config with default settings
if(!isset($cssStyleOn))$cssStyleOn=array();
$cssStyleOn=array_merge($cssStyleOn_default,$cssStyleOn);

	//construct some paths from known locations if not otherwise set 
if(!isset($cssStyleOn['SRC_PATH']))$cssStyleOn['SRC_PATH']=$cssStyleOn['BASE_PATH']."/src";

if(!isset($cssStyleOn['WEBROOT_IMAGE_PATH']))$cssStyleOn['WEBROOT_IMAGE_PATH']=$cssStyleOn['WEBROOT_PATH']."/images";
if(!isset($cssStyleOn['WEBROOT_SAMPLE_PATH']))$cssStyleOn['WEBROOT_SAMPLE_PATH']=$cssStyleOn['WEBROOT_PATH']."/images"; //FIXME: fetch image samples correctly
if(!isset($cssStyleOn['WEBROOT_RES_PATH']))$cssStyleOn['WEBROOT_RES_PATH']=$cssStyleOn['WEBROOT_PATH']."/res";

if(!isset($cssStyleOn['EXPORT_PATH']))$cssStyleOn['EXPORT_PATH']=$cssStyleOn['BASE_PATH']."/export";

if(!isset($cssStyleOn['EXPORT_CSS_PATH']))$cssStyleOn['EXPORT_CSS_PATH']=$cssStyleOn['EXPORT_PATH']."/custom_styles.css";
if(!isset($cssStyleOn['EXPORT_FCKSTYLES_PATH']))$cssStyleOn['EXPORT_FCKSTYLES_PATH']=$cssStyleOn['EXPORT_PATH']."/fckstyles.xml";

if(!isset($cssStyleOn['WEBROOT_EXPORT_CSS_PATH']))$cssStyleOn['WEBROOT_EXPORT_CSS_PATH']=$cssStyleOn['EXPORT_CSS_PATH'];
if(!isset($cssStyleOn['WEBROOT_EXPORT_FCKSTYLES_PATH']))$cssStyleOn['WEBROOT_EXPORT_FCKSTYLES_PATH']=$cssStyleOn['EXPORT_FCKSTYLES_PATH'];

?>