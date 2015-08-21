<?php

	/***************************************************************
	 * cssStyleOn - http://cssStyleOn.c0n.de 2008 by Stefan Baur
	 *--------------------------------------------------------------
	 *  src/cssmanager_layout.php
	 *     layout functions for cssmanager
	 *--------------------------------------------------------------
	 *  TODO: ?
	 **************************************************************/

global $cssStyleOn;

	/*************************************************
	 * tab layout for tags
	 ************************************************/
	 
function _layoutTabForTag($tag)
	{
?>
<div id="<?=$tag?>TagHolder" class="tagHolder">
  <input type="hidden" value="<?=$tag?>" />
  <!--<div style="display: none;" id="<?=$tag?>EntryTemplate"><span>class</span><span> { </span><input type="text" value="" name="" id="<?=$tag?>InputTemplate" style="width: 80%;"/><span> } </span><a href="#">edit</a><span>&nbsp;</span><a href="#">del</a></div>-->
  <div style="display: none;" id="<?=$tag?>EntryTemplate" class="tagClassEntry"><label for="<?=$tag?>InputTemplate">class</label><span> { </span><input type="text" value="" name="" id="<?=$tag?>InputTemplate" /><span> } </span><a href="#">edit</a><span>&nbsp;</span><a href="#">del</a></div>
  <b><?=$tag?></b> : <a href="#" onclick="javascript: appendNewCSSEntry('<?=$tag?>');">add new class</a>
  <hr />
</div>
<?
	}

function _layoutTabByMapping($tabID)
	{
	global $cssStyleOn;

	$tags=array_keys($cssStyleOn['MAPPING_TAG2TAB'],$tabID);

	foreach($tags as $tag)
		{
		_layoutTabForTag($tag);
		}
	}

	/*************************************************
	 * tab layout for import/export
	 ************************************************/

function _layoutTabImportExport()
	{
	global $cssStyleOn;
?>
<div class="tagHolder">
  <p><b>Import</b>:</p>
  <hr />
  <p>
    <label for="import_css_file">local css file: </label>
    <input type="file" id="import_css_file" name="import_css_file" <?=$cssStyleOn['PERMISSIONS']['IMPORT']?"":" disabled=\"disabled\""?> /><br />
    contents: <input type="radio" name="import_css_merge" value="merge" id="import_css_merge_merge" /><label for="import_css_merge_merge"> merge</label> or <input type="radio" name="import_css_merge" value="replace" id="import_css_merge_replace" checked="checked" /><label for="import_css_merge_replace"> replace</label><br />
    <input type="submit" name="import" value="import" <?=$cssStyleOn['PERMISSIONS']['IMPORT']?"":"disabled=\"disabled\""?> />
  </p>
  <p class="note">Note: currently only tags of the form: TAG.CLASS { ... } with each definition in one line will be considered.</p>
</div>

<div class="tagHolder">
  <p><b>Export</b>:</p>
  <hr />
  <p>
    Export current stylesheet as complete <input type="submit" name="export_css" value="style.css" <?=$cssStyleOn['PERMISSIONS']['EXPORT']?"":" disabled=\"disabled\""?> />.
  </p>
  <p> &nbsp; </p>
  <p>
    For integrating this stylesheet in <a href="http://www.fckeditor.net/" target="_blank">FCKEditor</a> use <input type="submit" name="export_fckstyles" value="fckstyles.xml" <?=$cssStyleOn['PERMISSIONS']['EXPORT']?"":"disabled=\"disabled\""?> />.
  </p>
</div>
<?
	}

	/*************************************************
	 * tab layout for view-server
	 ************************************************/

function _layoutTabViewServer()
	{
	browserReloadOutContent();
?>
<p>
<a href="javascript: browserForceReload();">reload</a>
</p>
<?
	}

?>
