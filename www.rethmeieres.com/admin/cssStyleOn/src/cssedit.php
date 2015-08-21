<?php

	/***************************************************************
	 * cssStyleOn - http://cssStyleOn.c0n.de 2008 by Stefan Baur
	 *--------------------------------------------------------------
	 *  src/cssedit.php
	 *     csseditor based on cssmate (http://cssmate.com)
	 *--------------------------------------------------------------
	 *  TODO: 
	 *		converting everything to lowercase destroys correct image file names
	 *         if they contain UPPERCASE chars
	 *		background-position-y does not work in firefox, when not
	 *         setting background-position-x and vice versa ?
	 *		text-justify does not work in firefox ? (IE only feature?)
	 *		border-color does not read corretly when using the format rgb(255, 255, 255) ...
	 *		when specifying the same value for background-position -x and -y,
	 *         firefox only writes out one, which fails when reading in
	 *         (firefox automatically adds 50% as second value in css!?)
	 *		when entering font name without '' or ',' at end the last 2 chars are stripped off
	 *		Font selection does not work in opera (because of stripping the last 2 chars?)
	 *		remove ;; or ; ; at the end of styles?
	 *		no-page-break does not work in opera
	 *		dropdown lists do not work in opera?
	 *		images / url("...") handling
	 *		outline ?
	 *		only show options applicable to current tag
	 **************************************************************/

	//configuration options
global $cssStyleOn;
if(!isset($cssStyleOn))exit;

function cssedit_layout()
	{
	global $cssStyleOn;

?>
<HTML XMLNS="http://www.w3.org/1999/xhtml">
  <HEAD>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <TITLE>
      cssStyleOn - CSS Editor
    </TITLE>
    <META HTTP-EQUIV="Content-Style-Type" CONTENT="text/css">
    <META NAME="robots" CONTENT="none">
    <LINK REL="stylesheet" TYPE="text/css" HREF="<?=$cssStyleOn['WEBROOT_RES_PATH']?>/display.css">
    </LINK>
    <SCRIPT LANGUAGE="javascript" TYPE="text/javascript" SRC="<?=$cssStyleOn['WEBROOT_RES_PATH']?>/cssedit.js">
    </SCRIPT>
  </HEAD>
  <BODY ONLOAD="BodyLoad()">
    <FORM ID="MainForm" ACTION="" CLASS="MainForm">
      <input type="hidden" id="CSSEditTargetID" value="<?=$_REQUEST['target']?>">
      <TABLE ID="MainTable">
        <TBODY>
          <TR>
            <TD ID="MainTableContent">
              <H1 ID="PageTitleHeader">
                <a href="http://cssStyleOn.c0n.de" target="_blank">cssStyleOn</a> : CSS Editor : <?=$_REQUEST['show_editor']?> &nbsp;<input type="button" value="done" onClick="javascript: CSSEditReturn(); return true" /> <input type="button" value="cancel" onClick="javascript: CSSEditCancel(); return true" />
              </H1>


<table id="MasterTable" class="csseditor">
	<tr>
		<!-- Editor Page Menu -->
		<td id="tdtabs" class="csseditor" colspan="2" style="margin: 0px; padding: 0px;">
		<table id="mainMenuTable" class="buttonTable">
			<tr>
				<td class="buttonNormal" onclick="buttonClick('FontButton');"
					onmouseover="buttonOver('FontButton')"
					onmouseout="buttonOut('FontButton')" id="FontButton" style="width: 94px;">Font</td>
				<td class="buttonNormal" onclick="buttonClick('BackgroundButton');"
					onmouseover="buttonOver('BackgroundButton')"
					onmouseout="buttonOut('BackgroundButton')" id="BackgroundButton" style="width: 94px;">
				Background</td>
				<td class="buttonNormal" onclick="buttonClick('TextButton');"
					onmouseover="buttonOver('TextButton')"
					onmouseout="buttonOut('TextButton')" id="TextButton" style="width: 94px;">Text</td>
				<td class="buttonNormal" onclick="buttonClick('PositionButton');"
					onmouseover="buttonOver('PositionButton')"
					onmouseout="buttonOut('PositionButton')" id="PositionButton" style="width: 94px;">
				Position</td>
				<td class="buttonNormal" onclick="buttonClick('LayoutButton');"
					onmouseover="buttonOver('LayoutButton')"
					onmouseout="buttonOut('LayoutButton')" id="LayoutButton" style="width: 94px;">Layout</td>
				<td class="buttonNormal" onclick="buttonClick('EdgesButton');"
					onmouseover="buttonOver('EdgesButton')"
					onmouseout="buttonOut('EdgesButton')" id="EdgesButton" style="width: 94px;">Borders</td>
				<td class="buttonNormal" onclick="buttonClick('ListsButton');"
					onmouseover="buttonOver('ListsButton')"
					onmouseout="buttonOut('ListsButton')" id="ListsButton" style="width: 94px;">Lists</td>
				<td class="buttonNormal" id="ReservedButton" style="width: 94px; cursor: default;">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
    <tr>
        <th class="csseditor">Attributes</th>
        <th class="csseditor">Selection Values</th>
    </tr>
	<tr>
		<!-- Editor Input Form -->
		<td id="tdedit" class="csseditor">
		<!-- Editor Page Font -->
		<table class="thinDarkblueBorders"
			style="display: none; width: 390px" id="FontTable">
			<tr>
				<td style="width: 147px" class="AusrichtungUnten">Font Family:</td>
				<td class="AusrichtungUnten"><input
					onfocus="showSelector('usedFonts', 'font')"
					onchange="manualFontInput()" type="text" name="usedFonts"
					id="usedFonts" size="35" /></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Text color:</td>
				<td><input type="text" id="CBox"
					onfocus="showSelector('CBox', 'color')" name="CBox" size="35"
					onchange="setFontStyle()" /></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Size:</td>
				<td><input type="text"
					onfocus="showSelector('FontSizeTextBox', 'fontSize')"
					name="FontSizeTextBox" id="FontSizeTextBox" size="20"
					onchange="setFontStyle()" /><select
					onfocus="showSelector('FontSizeTextBox', 'fontSize')" size="1"
					id="dropDownListFontSize" onchange="setFontStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Bold:</td>
				<td><input type="text" onfocus="showSelector('BoldTextBox', 'bold')"
					name="BoldTextBox" id="BoldTextBox" size="20"
					onchange="setFontStyle()" /></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Italic:</td>
				<td>Yes<input type="radio" value="V1" id="radioButtonItalic"
					onfocus="hideSelector()"
					onclick="setFontStyle(),changeRadioButtonItalic(1)" name="R1" />&nbsp;
				No<input type="radio" value="V1" id="radioButtonItalicNormal"
					onfocus="hideSelector()"
					onclick="setFontStyle(),changeRadioButtonItalic(2)" name="R2" />
				Inherit<input type="radio" value="V1" checked="checked"
					id="radioButtonItalicClear" onfocus="hideSelector()"
					onclick="setFontStyle(),changeRadioButtonItalic(3)" name="R3" /></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Small caps:</td>
				<td>Yes<input type="radio" value="V1" id="radioButtonSmallCaps"
					onfocus="hideSelector()"
					onclick="setFontStyle(),changeRadioButtonSmallCaps(1)" name="R4" />&nbsp;
				No<input type="radio" value="V1" id="radioButtonSmallCapsNormal"
					onfocus="hideSelector()"
					onclick="setFontStyle(),changeRadioButtonSmallCaps(2)" name="R5" />
				Inherit<input type="radio" value="V1" checked="checked"
					id="radioButtonSmallCapsClear" onfocus="hideSelector()"
					onclick="setFontStyle(),changeRadioButtonSmallCaps(3)" name="R6" /></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Capitalization:</td>
				<td><select onfocus="hideSelector()" size="1"
					id="dropDownListCapitalization" onchange="setFontStyle()">
					<option value=""></option>
					<option value="none">None</option>
					<option value="capitalize">Initial Cap</option>
					<option value="lowercase">lowercase</option>
					<option value="uppercase">UPPERCASE</option>
				</select></td>
			</tr>
			<tr>
				<td colspan="2">Effects<br />
				<input onfocus="hideSelector()" type="checkbox" id="CheckBoxNone"
					value="ON" onclick="uncheckCheckboxesIfNone()" />None<br />
				<input onfocus="hideSelector()" type="checkbox"
					id="CheckBoxUnderline" value="ON"
					onclick="setFontStyle(),uncheckCheckboxesElse()" />Underline<br />
				<input onfocus="hideSelector()" type="checkbox"
					id="CheckBoxStrikethrough" value="ON"
					onclick="setFontStyle(),uncheckCheckboxesElse()" />Strikethrough<br />
				<input onfocus="hideSelector()" type="checkbox"
					id="CheckBoxOverline" value="ON"
					onclick="setFontStyle(),uncheckCheckboxesElse()" />Overline</td>
			</tr>
		</table>
		<!-- Editor Page Background -->
		<table style="display: none; width: 390px" class="thinDarkblueBorders"
			id="BGTable">
			<tr>
				<td style="width: 147px" class="AusrichtungUnten">Background color:</td>
				<td><input type="text"
					onfocus="showSelector('bgColorTextBox', 'bgcolor')"
					id="bgColorTextBox" name="bgColorTextBox" size="20"
					onchange="setBGStyle()" /><input type="checkbox"
					id="transparentCheckBox" value="ON"
					onclick="aColorOrTransparent('trans')" onfocus="hideSelector()" />Transparent</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Background Image:</td>
				<td><input type="text" id="imageInputTextBox"
					name="imageInputTextBox" size="35"
					onfocus="showSelector('imageInputTextBox', 'Image')"
					onchange="setBGStyle()" /></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Tiling:</td>
				<td><select size="1" id="bgTilingDropDownList"
					name="bgTilingDropDownList" onchange="setBGStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="repeat-x">Tile in horizontal direction</option>
					<option value="repeat-y">Tile in vertical direction</option>
					<option value="repeat">Tile in both direction</option>
					<option value="no-repeat">Do not tile</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Scrolling:</td>
				<td><select size="1" id="bgScrollingDropDownList"
					name="bgScrollingDropDownList" onchange="setBGStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="scroll">Scrolling background</option>
					<option value="fixed">Fixed background</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Horizontal Position:</td>
				<td><input type="text" name="HorizontaleBGPositionTextBox"
					id="HorizontaleBGPositionTextBox" size="20" onchange="setBGStyle()"
					onfocus="showSelector('HorizontaleBGPositionTextBox', 'BGImgPosHor')" /><select
					size="1" name="HorizontalBGPositionUnitDropDownList"
					id="HorizontalBGPositionUnitDropDownList" onchange="setBGStyle()"
					onfocus="showSelector('HorizontaleBGPositionTextBox', 'BGImgPosHor')">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Vertical Position:</td>
				<td><input type="text" name="VerticalBGPositionTextBox"
					id="VerticalBGPositionTextBox" size="20" onchange="setBGStyle()"
					onfocus="showSelector('VerticalBGPositionTextBox', 'BGImgPosVer')" /><select
					size="1" id="VerticalBGPositionUnitDropDownList"
					name="VerticalBGPositionUnitDropDownList" onchange="setBGStyle()"
					onfocus="showSelector('VerticalBGPositionTextBox', 'BGImgPosVer')">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">User interface</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Cursor:</td>
				<td><select size="1" id="cursorDropDownList"
					name="cursorDropDownList" onchange="setBGStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="auto">Auto</option>
					<option value="default">Default</option>
					<option value="crosshair">Crosshair</option>
					<option value="pointer">Hand</option>
					<option value="move">Move</option>
					<option value="n-resize">Top Resize</option>
					<option value="s-resize">Bottom Resize</option>
					<option value="w-resize">Left Resize</option>
					<option value="e-resize">Right Resize</option>
					<option value="nw-resize">Top Left Resize</option>
					<option value="sw-resize">Bottom Left Resize</option>
					<option value="ne-resize">Top Right Resize</option>
					<option value="se-resize">Bottom Right Resize</option>
					<option value="text">Text</option>
					<option value="wait">Hourglass</option>
					<option value="help">Help</option>
				</select></td>
			</tr>
		</table>
		<!-- Editor Page Text -->
		<table style="display: none; width: 390px" class="thinDarkblueBorders"
			id="TextTable">
			<tr>
				<td style="width: 147px" class="AusrichtungUnten">Alignment</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Horizontal:</td>
				<td><select size="1" name="horizontalAlignmentDropDownList"
					id="horizontalAlignmentDropDownList" onchange="setTextStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="left">Left</option>
					<option value="center">Centered</option>
					<option value="right">Right</option>
					<option value="justify">Justified</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Justification:</td>
				<td><select size="1" name="justificationHorAlignmentDropDownList"
					id="justificationHorAlignmentDropDownList"
					onchange="setJustify(),setTextStyle()" onfocus="hideSelector()">
					<option value=""></option>
					<option value="auto">Auto</option>
					<option value="inter-word">Space words</option>
					<option value="newspaper">Newspaper style</option>
					<option value="distribute">Distribute spacing</option>
					<option value="distribute-all-lines">Distribute all lines</option>
					<option value="inter-cluster">Inter-cluster</option>
					<option value="inter-ideograph">Inter-ideograph</option>
					<option value="inter-word">Inter-word</option>
					<option value="kashida">Koshida</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Letters spacing:</td>
				<td><input type="text" name="spacingBetweenLettersTextBox"
					id="spacingBetweenLettersTextBox" size="20"
					onchange="setTextStyle()"
					onfocus="showSelector('spacingBetweenLettersTextBox', 'Letters')" /><select
					size="1" name="spacingBetweenLettersUnitsDropDownList"
					id="spacingBetweenLettersUnitsDropDownList"
					onchange="setTextStyle()"
					onfocus="showSelector('spacingBetweenLettersTextBox', 'Letters')">
					<option value=""></option>
					<option value="px">px</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Vertical:</td>
				<td><select size="1" name="verticalAlignmentDropDownList"
					id="verticalAlignmentDropDownList" onchange="setTextStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="baseline">baseline</option>
					<option value="sub">sub</option>
					<option value="super">super</option>
					<option value="top">top</option>
					<option value="text-top">text top</option>
					<option value="middle">middle</option>
					<option value="bottom">bottom</option>
					<option value="text-bottom">text bottom</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Lines spacing:</td>
				<td><input type="text" name="spacingBetweenLinesTextBox"
					id="spacingBetweenLinesTextBox" size="20" onchange="setTextStyle()"
					onfocus="showSelector('spacingBetweenLinesTextBox', 'Lines')" /><select
					size="1" name="spacingBetweenLinesUnitsDropDownList"
					id="spacingBetweenLinesUnitsDropDownList" onchange="setTextStyle()"
					onfocus="showSelector('spacingBetweenLinesTextBox', 'Lines')">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Text flow</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Indentation:</td>
				<td><input type="text" name="textFlowIdentationTextBox" size="20"
					id="textFlowIdentationTextBox" onchange="setTextStyle()"
					onfocus="hideSelector()" /><select size="1"
					name="textFlowIdentationDropDownList"
					id="textFlowIdentationDropDownList" onchange="setTextStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Text direction:</td>
				<td><select size="1" name="textFlowTextDirectionDropDownList"
					id="textFlowTextDirectionDropDownList" onchange="setTextStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="ltr">Left to right</option>
					<option value="rtl">Right to left</option>
				</select></td>
			</tr>
		</table>
		<!-- Editor Page Position -->
		<table style="display: none; width: 390px" class="thinDarkblueBorders"
			id="PositionTable">
			<tr>
				<td style="width: 147px" class="AusrichtungUnten">Position mode:</td>
				<td><select size="1" name="positionModeDropDownList"
					id="positionModeDropDownList"
					onchange="setPositionStyle(),changePositionSample()">
					<option value=""></option>
					<option value="static">Position is normal flow</option>
					<option value="relative">Offset from normal flow</option>
					<option value="absolute">Absolute position</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Top:</td>
				<td><input type="text" name="positionTopTextBox" size="10"
					id="positionTopTextBox" onchange="setPositionStyle()" /><select
					size="1" name="positionTopDropDownList"
					id="positionTopDropDownList" onchange="setPositionStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Height:</td>
				<td><input type="text" name="positionHeightTextBox" size="10"
					id="positionHeightTextBox" onchange="setPositionStyle()" /><select
					size="1" name="positionHeightDropDownList"
					id="positionHeightDropDownList" onchange="setPositionStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Left:</td>
				<td><input type="text" name="positionLeftTextBox" size="10"
					id="positionLeftTextBox" onchange="setPositionStyle()" /><select
					size="1" name="positionLeftDropDownList"
					id="positionLeftDropDownList" onchange="setPositionStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Width:</td>
				<td><input type="text" name="positionWitdthTextBox" size="10"
					id="positionWitdthTextBox" onchange="setPositionStyle()" /><select
					size="1" name="positionWidthDropDownList"
					id="positionWidthDropDownList" onchange="setPositionStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Z-Index:</td>
				<td><input type="text" name="positionZIndexTextBox" size="20"
					id="positionZIndexTextBox" onchange="setPositionStyle()" /></td>
			</tr>
			<tr>
				<td colspan="2" class="AusrichtungUnten">Flow control</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Visibility:</td>
				<td><select size="1" id="flowControlVisibilityDropDownList"
					name="flowControlVisibilityDropDownList"
					onchange="setPositionStyle()">
					<option value=""></option>
					<option value="hidden">Hidden</option>
					<option value="visible">Visible</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Display:</td>
				<td><select size="1" id="flowControlDisplayDropDownList"
					name="flowControlDisplayDropDownList" onchange="setPositionStyle()">
					<option value=""></option>
					<option value="none">Do not display</option>
					<option value="block">As a block element</option>
					<option value="inline">As an inflow element</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Floating position:</td>
				<td><select size="1" id="flowControlAllowTextToFlowDropDownList"
					name="flowControlAllowTextToFlowDropDownList"
					onchange="setPositionStyle()">
					<option value=""></option>
					<option value="none">No floating</option>
					<option value="right">Right</option>
					<option value="left">Left</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Allow floating:</td>
				<td><select size="1"
					id="flowControlAllowFloatingObjectsDropDownList"
					name="flowControlAllowFloatingObjectsDropDownList"
					onchange="setPositionStyle()">
					<option value=""></option>
					<option value="none">Do not allow</option>
					<option value="left">Left</option>
					<option value="right">Right</option>
					<option value="both">On both sides</option>
				</select></td>
			</tr>
		</table>
		<!-- Editor Page Layout -->
		<table style="display: none; width: 390px" class="thinDarkblueBorders"
			id="LayoutTable">
			<tr>
				<td style="width: 147px" class="AusrichtungUnten">Overflow:</td>
				<td><select size="1" id="contentOverflowDropDownList"
					name="contentOverflowDropDownList" onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="auto">Use scrollbars if need</option>
					<option value="scroll">Always use scrollbars</option>
					<option value="visible">Content is not clipped</option>
					<option value="hidden">Content is clipped</option>
				</select></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Clipping</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Top:</td>
				<td><input type="text" id="clippinTopTextBox"
					name="clippinTopTextBox" size="5" onchange="setLayoutStyle()" /><select
					size="1" id="clippinTopUnitsDropDownList"
					name="clippinTopUnitsDropDownList" onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Bottom:</td>
				<td><input type="text" id="clippinBottomTextBox"
					name="clippinBottomTextBox" size="5" onchange="setLayoutStyle()" /><select
					size="1" id="clippinBottomUnitsDropDownList"
					name="clippinBottomUnitsDropDownList" onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Left:</td>
				<td><input type="text" id="clippinLeftTextBox"
					name="clippinLeftTextBox" size="5" onchange="setLayoutStyle()" /><select
					size="1" id="clippinLeftUnitsDropDownList"
					name="clippinLeftUnitsDropDownList" onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Right:</td>
				<td><input type="text" id="clippinRightTextBox"
					name="clippinRightTextBox" size="5" onchange="setLayoutStyle()" /><select
					size="1" id="clippinRightUnitsDropDownList"
					name="clippinRightUnitsDropDownList" onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="px">px</option>
					<option value="%">%</option>
					<option value="pt">pt</option>
					<option value="pc">pc</option>
					<option value="mm">mm</option>
					<option value="cm">cm</option>
					<option value="in">in</option>
					<option value="em">em</option>
					<option value="ex">ex</option>
				</select></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Printing page breaks</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Before</td>
				<td><select size="1" id="pageBreakBefore" name="pageBreakBefore"
					onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="auto">Auto</option>
					<option value="always">Force a page break</option>
					<option value="">No page break</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">After:</td>
				<td><select size="1" id="pageBreakAfter" name="pageBreakAfter"
					onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="auto">Auto</option>
					<option value="always">Force a page break</option>
					<option value="">No page break</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Table layout</td>
				<td><select size="1" id="tablesLayoutDropDownList"
					name="tablesLayoutDropDownList" onchange="setLayoutStyle()">
					<option value=""></option>
					<option value="auto">Auto</option>
					<option value="fixed">Fixed layout</option>
				</select></td>
			</tr>
		</table>
		<!-- Editor Page Borders -->
		<table style="display: none; width: 390px" class="thinDarkblueBorders"
			id="EdgesTable">
			<tr>
				<td>Margins
				<table id="table28">
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">All:</td>
						<td><input type="text" onfocus="showSelector('Margin', 'Margin')"
							onchange="setEdgesStyle()" name="borderMarginAllTextBox"
							id="borderMarginAllTextBox" size="5" /><select
							onfocus="showSelector('Margin', 'Margin')"
							onchange="setEdgesStyle()" size="1"
							name="borderMarginAllDropDownList"
							id="borderMarginAllDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Top:</td>
						<td><input type="text" onfocus="showSelector('Margin', 'Margin')"
							onchange="setEdgesStyle()" name="borderMarginTopTextBox"
							id="borderMarginTopTextBox" size="5" /><select
							onfocus="showSelector('Margin', 'Margin')"
							onchange="setEdgesStyle()" size="1"
							name="borderMarginTopDropDownList"
							id="borderMarginTopDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Bottom:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="showSelector('Margin', 'Margin')" type="text"
							name="borderMarginBottomTextBox" id="borderMarginBottomTextBox"
							size="5" /><select onfocus="showSelector('Margin', 'Margin')"
							onchange="setEdgesStyle()" size="1"
							name="borderMarginBottomDropDownList"
							id="borderMarginBottomDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Left:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="showSelector('Margin', 'Margin')" type="text"
							name="borderMarginLeftTextBox" id="borderMarginLeftTextBox"
							size="5" /><select onfocus="showSelector('Margin', 'Margin')"
							onchange="setEdgesStyle()" size="1"
							name="borderMarginLeftDropDownList"
							id="borderMarginLeftDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Right:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="showSelector('Margin', 'Margin')" type="text"
							name="borderMarginRightTextBox" id="borderMarginRightTextBox"
							size="5" /><select onfocus="showSelector('Margin', 'Margin')"
							onchange="setEdgesStyle()" size="1"
							name="borderMarginRightDropDownList"
							id="borderMarginRightDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
				</table>
				</td>
				<td>Padding
				<table id="table29">
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">All:</td>
						<td><input type="text"
							onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" name="borderPaddingAllTextBox"
							id="borderPaddingAllTextBox" size="5" /><select
							onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" size="1"
							name="borderPaddingAllDropDownList"
							id="borderPaddingAllDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Top:</td>
						<td><input onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" type="text"
							name="borderPaddingTopTextBox" id="borderPaddingTopTextBox"
							size="5" /><select onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" size="1"
							name="borderPaddingTopDropDownList"
							id="borderPaddingTopDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Bottom:</td>
						<td><input onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" type="text"
							name="borderPaddingBottomTextBox" id="borderPaddingBottomTextBox"
							size="5" /><select onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" size="1"
							name="borderPaddingBottomDropDownList"
							id="borderPaddingBottomDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Left:</td>
						<td><input onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" type="text"
							name="borderPaddingLeftTextBox" id="borderPaddingLeftTextBox"
							size="5" /><select onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" size="1"
							name="borderPaddingLeftDropDownList"
							id="borderPaddingLeftDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td style="width: 48px" class="AusrichtungUnten">Right:</td>
						<td><input onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" type="text"
							name="borderPaddingRightTextBox" id="borderPaddingRightTextBox"
							size="5" /><select onfocus="showSelector('Padding', 'Padding')"
							onchange="setEdgesStyle()" size="1"
							name="borderPaddingRightDropDownList"
							id="borderPaddingRightDropDownList">
							<option value=""></option>
							<option value="px">px</option>
							<option value="%">%</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten" colspan="2">Table borders: <select
					size="1" id="tablesBordersDropDownList"
					name="tablesBordersDropDownList" onchange="setEdgesStyle()">
					<option value=""></option>
					<option value="separate">Seperate cell borders</option>
					<option value="collapse">Collapse cell borders</option>
				</select></td>
			</tr>
			<tr>
				<td colspan="2">
				<table id="table38">
					<tr>
						<td>&nbsp;</td>
						<td>Color</td>
						<td>Style</td>
						<td>Width</td>
					</tr>
					<tr>
						<td class="AusrichtungUnten">All:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="hideSelector(),showSelector('borderColorAllTextBox', 'borderColor')"
							type="text" name="borderColorAllTextBox"
							id="borderColorAllTextBox" size="12" /></td>
						<td><select onfocus="hideSelector()" onchange="setEdgesStyle()"
							size="1" name="borderStyleAllDropDownList"
							id="borderStyleAllDropDownList">
							<option value=""></option>
							<option value="none">None</option>
							<option value="dotted">Dotted</option>
							<option value="dashed">Dashed</option>
							<option value="solid">Solid line</option>
							<option value="double">Duble line</option>
							<option value="groove">Groove</option>
							<option value="ridge">Ridge</option>
							<option value="inset">Inset</option>
							<option value="outset">Outset</option>
						</select></td>
						<td><input onfocus="hideSelector()" onchange="setEdgesStyle()"
							type="text" name="borderWidthAllTextBox"
							id="borderWidthAllTextBox" size="4" /><select
							onfocus="hideSelector()" onchange="setEdgesStyle()" size="1"
							name="borderWidthAllDropDownList" id="borderWidthAllDropDownList">
							<option value=""></option>
							<option value="thin">Thin</option>
							<option value="medium">Medium</option>
							<option value="thick">Thick</option>
							<option value="px">px</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td class="AusrichtungUnten">Top:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="hideSelector(),showSelector('borderColorTopTextBox', 'borderColor')"
							type="text" name="borderColorTopTextBox"
							id="borderColorTopTextBox" size="12" /></td>
						<td><select onfocus="hideSelector()" onchange="setEdgesStyle()"
							size="1" name="borderStyleTopDropDownList"
							id="borderStyleTopDropDownList">
							<option value=""></option>
							<option value="none">None</option>
							<option value="dotted">Dotted</option>
							<option value="dashed">Dashed</option>
							<option value="solid">Solid line</option>
							<option value="double">Duble line</option>
							<option value="groove">Groove</option>
							<option value="ridge">Ridge</option>
							<option value="inset">Inset</option>
							<option value="outset">Outset</option>
						</select></td>
						<td><input onchange="setEdgesStyle()" onfocus="hideSelector()"
							type="text" name="borderWidthTopTextBox"
							id="borderWidthTopTextBox" size="4" /><select
							onfocus="hideSelector()" onchange="setEdgesStyle()" size="1"
							name="borderWidthTopDropDownList" id="borderWidthTopDropDownList">
							<option value=""></option>
							<option value="thin">Thin</option>
							<option value="medium">Medium</option>
							<option value="thick">Thick</option>
							<option value="px">px</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td class="AusrichtungUnten">Bottom:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="hideSelector(),showSelector('borderColorBottomTextBox', 'borderColor')"
							type="text" name="borderColorBottomTextBox"
							id="borderColorBottomTextBox" size="12" /></td>
						<td><select onfocus="hideSelector()" onchange="setEdgesStyle()"
							size="1" name="borderStyleBottomDropDownList"
							id="borderStyleBottomDropDownList">
							<option value=""></option>
							<option value="none">None</option>
							<option value="dotted">Dotted</option>
							<option value="dashed">Dashed</option>
							<option value="solid">Solid line</option>
							<option value="double">Duble line</option>
							<option value="groove">Groove</option>
							<option value="ridge">Ridge</option>
							<option value="inset">Inset</option>
							<option value="outset">Outset</option>
						</select></td>
						<td><input onchange="setEdgesStyle()" onfocus="hideSelector()"
							type="text" name="borderWidthBottomTextBox"
							id="borderWidthBottomTextBox" size="4" /><select
							onfocus="hideSelector()" onchange="setEdgesStyle()" size="1"
							name="borderWidthBottomDropDownList"
							id="borderWidthBottomDropDownList">
							<option value=""></option>
							<option value="thin">Thin</option>
							<option value="medium">Medium</option>
							<option value="thick">Thick</option>
							<option value="px">px</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td class="AusrichtungUnten">Left:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="hideSelector(),showSelector('borderColorLeftTextBox', 'borderColor')"
							type="text" name="borderColorLeftTextBox"
							id="borderColorLeftTextBox" size="12" /></td>
						<td><select onfocus="hideSelector()" onchange="setEdgesStyle()"
							size="1" name="borderStyleLeftDropDownList"
							id="borderStyleLeftDropDownList">
							<option value=""></option>
							<option value="none">None</option>
							<option value="dotted">Dotted</option>
							<option value="dashed">Dashed</option>
							<option value="solid">Solid line</option>
							<option value="double">Duble line</option>
							<option value="groove">Groove</option>
							<option value="ridge">Ridge</option>
							<option value="inset">Inset</option>
							<option value="outset">Outset</option>
						</select></td>
						<td><input onchange="setEdgesStyle()" onfocus="hideSelector()"
							type="text" name="borderWidthLeftTextBox"
							id="borderWidthLeftTextBox" size="4" /><select
							onfocus="hideSelector()" onchange="setEdgesStyle()" size="1"
							name="borderWidthLeftDropDownList"
							id="borderWidthLeftDropDownList">
							<option value=""></option>
							<option value="thin">Thin</option>
							<option value="medium">Medium</option>
							<option value="thick">Thick</option>
							<option value="px">px</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
					<tr>
						<td class="AusrichtungUnten">Right:</td>
						<td><input onchange="setEdgesStyle()"
							onfocus="hideSelector(),showSelector('borderColorRightTextBox', 'borderColor')"
							type="text" name="borderColorRightTextBox"
							id="borderColorRightTextBox" size="12" /></td>
						<td><select onfocus="hideSelector()" onchange="setEdgesStyle()"
							size="1" name="borderStyleRightDropDownList"
							id="borderStyleRightDropDownList">
							<option value=""></option>
							<option value="none">None</option>
							<option value="dotted">Dotted</option>
							<option value="dashed">Dashed</option>
							<option value="solid">Solid line</option>
							<option value="double">Duble line</option>
							<option value="groove">Groove</option>
							<option value="ridge">Ridge</option>
							<option value="inset">Inset</option>
							<option value="outset">Outset</option>
						</select></td>
						<td><input onchange="setEdgesStyle()" onfocus="hideSelector()"
							type="text" name="borderWidthRightTextBox"
							id="borderWidthRightTextBox" size="4" /><select
							onfocus="hideSelector()" onchange="setEdgesStyle()" size="1"
							name="borderWidthRightDropDownList"
							id="borderWidthRightDropDownList">
							<option value=""></option>
							<option value="thin">Thin</option>
							<option value="medium">Medium</option>
							<option value="thick">Thick</option>
							<option value="px">px</option>
							<option value="pt">pt</option>
							<option value="pc">pc</option>
							<option value="mm">mm</option>
							<option value="cm">cm</option>
							<option value="in">in</option>
							<option value="em">em</option>
							<option value="ex">ex</option>
						</select></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		<!-- Editor Page Lists -->
		<table style="display: none; width: 390px" class="thinDarkblueBorders"
			id="ListsTable">
			<tr>
				<td style="width: 147px" class="AusrichtungUnten">List&nbsp;Style:</td>
				<td><select size="1" id="ListsDropDownList" name="ListsDropDownList"
					onchange="setListsStyle()" onfocus="hideSelector()">
					<option value=""></option>
					<option value="Bulleted">Bulleted</option>
					<option value="Unbulleted">Unbulleted</option>
				</select></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Bullet</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Style:</td>
				<td><select size="1" id="bulletStyleDropDownList"
					name="bulletStyleDropDownList" onchange="setListsStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="circle">Circle</option>
					<option value="disc">Disc</option>
					<option value="square">Square</option>
					<option value="decimal">1 2 3 4...</option>
					<option value="lower-roman">Lowercase i ii iii iv...</option>
					<option value="upper-roman">Uppercase I II III IV...</option>
					<option value="lower-alpha">Lowercase a b c d...</option>
					<option value="upper-alpha">Uppercase A B C D...</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten">Position:</td>
				<td><select size="1" id="bulletPositionDropDownList"
					name="bulletPositionDropDownList" onchange="setListsStyle()"
					onfocus="hideSelector()">
					<option value=""></option>
					<option value="outside">Outside (Text is indented in)</option>
					<option value="inside">Inside (Text is not indented)</option>
				</select></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten"><input type="radio" value="V1"
					checked="checked" id="customBulletRadioButton_1"
					name="customBulletRadioButton_1"
					onclick="changeRadioButtonLists(1),setListsStyle()"
					onfocus="hideSelector()" />Image:</td>
				<td><input type="text" id="customBulletImageTextBox"
					name="customBulletImageTextBox" size="20"
					onfocus="showSelector('customBulletImageTextBox', 'Image')"
					onchange="setListsStyle()" /></td>
			</tr>
			<tr>
				<td class="AusrichtungUnten"><input type="radio" value="V1"
					id="customBulletRadioButton_2" name="customBulletRadioButton_2"
					onclick="changeRadioButtonLists(2),setListsStyle()"
					onfocus="hideSelector()" />None</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		</td>
		<!-- Editor Selection Form -->
		<td id="tdselect" class="csseditor">

		<div id="namedColorsDiv" style="display: none;">
				<div class="buttonPressed" style="width: 160px; float: left;">Named&nbsp;Colors</div>
				<div id="changeToWebPaletteButton"
					onclick="showWebPaletteAuswahl()"
					onmouseover="buttonOver('changeToWebPaletteButton')"
					onmouseout="buttonOut('changeToWebPaletteButton')"
					class="buttonNormal" style="width: 160px; float: left;">Web&nbsp;Palette</div>

<!--
			<tr>
				<td class="buttonPressed" style="width: 156px;" colspan="8">Named&nbsp;Colors</td>
				<td id="changeToWebPaletteButton"
					onclick="showWebPaletteAuswahl()"
					onmouseover="buttonOver('changeToWebPaletteButton')"
					onmouseout="buttonOut('changeToWebPaletteButton')"
					class="buttonNormal" style="width: 156px" colspan="8">Web&nbsp;Palette</td>
			</tr>
			<tr>
				<td colspan="16" style="width: 100%">Basic:</td>
			</tr>
-->
			<div style="width: 100%; clear: both;">Basic:</div>

		<table class="thinDarkblueBorders" id="namedColorsTable1">
			<tr>
				<td style="background-color: green" id="td_green"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_green'),setFontStyle()"
					onmouseover="selectColorMouseOverTDNamed('td_green')"
					onmouseout="selectColorMouseOutTDNamed('td_green')">&nbsp;</td>
				<td style="background-color: lime" id="td_lime"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lime')"
					onmouseover="selectColorMouseOverTDNamed('td_lime')"
					onmouseout="selectColorMouseOutTDNamed('td_lime')">&nbsp;</td>
				<td style="background-color: teal" id="td_teal"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_teal')"
					onmouseover="selectColorMouseOverTDNamed('td_teal')"
					onmouseout="selectColorMouseOutTDNamed('td_teal')">&nbsp;</td>
				<td style="background-color: aqua" id="td_aqua"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_aqua')"
					onmouseover="selectColorMouseOverTDNamed('td_aqua')"
					onmouseout="selectColorMouseOutTDNamed('td_aqua')">&nbsp;</td>
				<td style="background-color: navy" id="td_navy"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_navy')"
					onmouseover="selectColorMouseOverTDNamed('td_navy')"
					onmouseout="selectColorMouseOutTDNamed('td_navy')">&nbsp;</td>
				<td style="background-color: blue" id="td_blue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_blue')"
					onmouseover="selectColorMouseOverTDNamed('td_blue')"
					onmouseout="selectColorMouseOutTDNamed('td_blue')">&nbsp;</td>
				<td style="background-color: purple" id="td_purple"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_purple')"
					onmouseover="selectColorMouseOverTDNamed('td_purple')"
					onmouseout="selectColorMouseOutTDNamed('td_purple')">&nbsp;</td>
				<td style="background-color: fuchsia" id="td_fuchsia"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_fuchsia')"
					onmouseover="selectColorMouseOverTDNamed('td_fuchsia')"
					onmouseout="selectColorMouseOutTDNamed('td_fuchsia')">&nbsp;</td>
				<td style="background-color: maroon" id="td_maroon"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_maroon')"
					onmouseover="selectColorMouseOverTDNamed('td_maroon')"
					onmouseout="selectColorMouseOutTDNamed('td_maroon')">&nbsp;</td>
				<td style="background-color: red" id="td_red" class="td_selectColor"
					onclick="selectColorFromTDNamed('td_red')"
					onmouseover="selectColorMouseOverTDNamed('td_red')"
					onmouseout="selectColorMouseOutTDNamed('td_red')">&nbsp;</td>
				<td style="background-color: olive" id="td_olive"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_olive')"
					onmouseover="selectColorMouseOverTDNamed('td_olive')"
					onmouseout="selectColorMouseOutTDNamed('td_olive')">&nbsp;</td>
				<td style="background-color: yellow" id="td_yellow"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_yellow')"
					onmouseover="selectColorMouseOverTDNamed('td_yellow')"
					onmouseout="selectColorMouseOutTDNamed('td_yellow')">&nbsp;</td>
				<td style="background-color: white" id="td_white"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_white')"
					onmouseover="selectColorMouseOverTDNamed('td_white')"
					onmouseout="selectColorMouseOutTDNamed('td_white')">&nbsp;</td>
				<td style="background-color: silver" id="td_silver"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_silver')"
					onmouseover="selectColorMouseOverTDNamed('td_silver')"
					onmouseout="selectColorMouseOutTDNamed('td_silver')">&nbsp;</td>
				<td style="background-color: gray" id="td_gray"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_gray')"
					onmouseover="selectColorMouseOverTDNamed('td_gray')"
					onmouseout="selectColorMouseOutTDNamed('td_gray')">&nbsp;</td>
				<td style="background-color: black" id="td_black"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_black')"
					onmouseover="selectColorMouseOverTDNamed('td_black')"
					onmouseout="selectColorMouseOutTDNamed('td_black')">&nbsp;</td>
			</tr>
			</table>
			<div style="width: 100%; clear: both; margin-top: 8px;">Additional:</div>
<!--
			<tr>
				<td colspan="16" style="width: 100%">Additional:</td>
			</tr>
-->
		<table class="thinDarkblueBorders" id="namedColorsTable">
			<tr>
				<td style="background-color: #556B2F" id="td_darkolivegreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkolivegreen')"
					onmouseover="selectColorMouseOverTDNamed('td_darkolivegreen')"
					onmouseout="selectColorMouseOutTDNamed('td_darkolivegreen')">
				&nbsp;</td>
				<td style="background-color: #006400" id="td_darkgreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkgreen')"
					onmouseover="selectColorMouseOverTDNamed('td_darkgreen')"
					onmouseout="selectColorMouseOutTDNamed('td_darkgreen')">&nbsp;</td>
				<td style="background-color: #2F4F4F" id="td_darkslategray"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkslategray')"
					onmouseover="selectColorMouseOverTDNamed('td_darkslategray')"
					onmouseout="selectColorMouseOutTDNamed('td_darkslategray')">&nbsp;</td>
				<td style="background-color: #708090" id="td_slategray"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_slategray')"
					onmouseover="selectColorMouseOverTDNamed('td_slategray')"
					onmouseout="selectColorMouseOutTDNamed('td_slategray')">&nbsp;</td>
				<td style="background-color: #00008B" id="td_darkblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkblue')"
					onmouseover="selectColorMouseOverTDNamed('td_darkblue')"
					onmouseout="selectColorMouseOutTDNamed('td_darkblue')">&nbsp;</td>
				<td style="background-color: #191970" id="td_midnightblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_midnightblue')"
					onmouseover="selectColorMouseOverTDNamed('td_midnightblue')"
					onmouseout="selectColorMouseOutTDNamed('td_midnightblue')">&nbsp;</td>
				<td style="background-color: #4B0082" id="td_indigo"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_indigo')"
					onmouseover="selectColorMouseOverTDNamed('td_indigo')"
					onmouseout="selectColorMouseOutTDNamed('td_indigo')">&nbsp;</td>
				<td style="background-color: #8B008B" id="td_darkmagenta"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkmagenta')"
					onmouseover="selectColorMouseOverTDNamed('td_darkmagenta')"
					onmouseout="selectColorMouseOutTDNamed('td_darkmagenta')">&nbsp;</td>
				<td style="background-color: #A52A2A" id="td_brown"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_brown')"
					onmouseover="selectColorMouseOverTDNamed('td_brown')"
					onmouseout="selectColorMouseOutTDNamed('td_brown')">&nbsp;</td>
				<td style="background-color: #8B0000" id="td_darkred"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkred')"
					onmouseover="selectColorMouseOverTDNamed('td_darkred')"
					onmouseout="selectColorMouseOutTDNamed('td_darkred')">&nbsp;</td>
				<td style="background-color: #A0522D" id="td_sienna"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_sienna')"
					onmouseover="selectColorMouseOverTDNamed('td_sienna')"
					onmouseout="selectColorMouseOutTDNamed('td_sienna')">&nbsp;</td>
				<td style="background-color: #8B4513" id="td_saddlebrown"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_saddlebrown')"
					onmouseover="selectColorMouseOverTDNamed('td_saddlebrown')"
					onmouseout="selectColorMouseOutTDNamed('td_saddlebrown')">&nbsp;</td>
				<td style="background-color: #B8860B" id="td_darkgoldenrod"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkgoldenrod')"
					onmouseover="selectColorMouseOverTDNamed('td_darkgoldenrod')"
					onmouseout="selectColorMouseOutTDNamed('td_darkgoldenrod')">&nbsp;</td>
				<td style="background-color: #F5F5DC" id="td_beige"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_beige')"
					onmouseover="selectColorMouseOverTDNamed('td_beige')"
					onmouseout="selectColorMouseOutTDNamed('td_beige')">&nbsp;</td>
				<td style="background-color: #F0FFF0" id="td_honeydew"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_honeydew')"
					onmouseover="selectColorMouseOverTDNamed('td_honeydew')"
					onmouseout="selectColorMouseOutTDNamed('td_honeydew')">&nbsp;</td>
				<td style="background-color: #696969" id="td_dimgray"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_dimgray')"
					onmouseover="selectColorMouseOverTDNamed('td_dimgray')"
					onmouseout="selectColorMouseOutTDNamed('td_dimgray')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #6B8E23" id="td_olivedrab"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_olivedrab')"
					onmouseover="selectColorMouseOverTDNamed('td_olivedrab')"
					onmouseout="selectColorMouseOutTDNamed('td_olivedrab')">&nbsp;</td>
				<td style="background-color: #228B22" id="td_forestgreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_forestgreen')"
					onmouseover="selectColorMouseOverTDNamed('td_forestgreen')"
					onmouseout="selectColorMouseOutTDNamed('td_forestgreen')">&nbsp;</td>
				<td style="background-color: #008B8B" id="td_darkcyan"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkcyan')"
					onmouseover="selectColorMouseOverTDNamed('td_darkcyan')"
					onmouseout="selectColorMouseOutTDNamed('td_darkcyan')">&nbsp;</td>
				<td style="background-color: #778899" id="td_lightslategray"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightslategray')"
					onmouseover="selectColorMouseOverTDNamed('td_lightslategray')"
					onmouseout="selectColorMouseOutTDNamed('td_lightslategray')">
				&nbsp;</td>
				<td style="background-color: #0000CD" id="td_mediumblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumblue')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumblue')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumblue')">&nbsp;</td>
				<td style="background-color: #483D8B" id="td_darkslateblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkslateblue')"
					onmouseover="selectColorMouseOverTDNamed('td_darkslateblue')"
					onmouseout="selectColorMouseOutTDNamed('td_darkslateblue')">&nbsp;</td>
				<td style="background-color: #9400D3" id="td_darkviolet"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkviolet')"
					onmouseover="selectColorMouseOverTDNamed('td_darkviolet')"
					onmouseout="selectColorMouseOutTDNamed('td_darkviolet')">&nbsp;</td>
				<td style="background-color: #C71585" id="td_mediumvioletred"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumvioletred')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumvioletred')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumvioletred')">
				&nbsp;</td>
				<td style="background-color: #CD5C5C" id="td_indianred"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_indianred')"
					onmouseover="selectColorMouseOverTDNamed('td_indianred')"
					onmouseout="selectColorMouseOutTDNamed('td_indianred')">&nbsp;</td>
				<td style="background-color: #B22222" id="td_firebrick"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_firebrick')"
					onmouseover="selectColorMouseOverTDNamed('td_firebrick')"
					onmouseout="selectColorMouseOutTDNamed('td_firebrick')">&nbsp;</td>
				<td style="background-color: #D2691E" id="td_chocolate"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_chocolate')"
					onmouseover="selectColorMouseOverTDNamed('td_chocolate')"
					onmouseout="selectColorMouseOutTDNamed('td_chocolate')">&nbsp;</td>
				<td style="background-color: #CD853F" id="td_peru"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_peru')"
					onmouseover="selectColorMouseOverTDNamed('td_peru')"
					onmouseout="selectColorMouseOutTDNamed('td_peru')">&nbsp;</td>
				<td style="background-color: #DAA520" id="td_goldenrod"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_goldenrod')"
					onmouseover="selectColorMouseOverTDNamed('td_goldenrod')"
					onmouseout="selectColorMouseOutTDNamed('td_goldenrod')">&nbsp;</td>
				<td style="background-color: #FAFAD2" id="td_lightgoldenrodyellow"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightgoldenrodyellow')"
					onmouseover="selectColorMouseOverTDNamed('td_lightgoldenrodyellow')"
					onmouseout="selectColorMouseOutTDNamed('td_lightgoldenrodyellow')">
				&nbsp;</td>
				<td style="background-color: #F5FFFA" id="td_mintcream"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mintcream')"
					onmouseover="selectColorMouseOverTDNamed('td_mintcream')"
					onmouseout="selectColorMouseOutTDNamed('td_mintcream')">&nbsp;</td>
				<td style="background-color: #A9A9A9" id="td_darkgray"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkgray')"
					onmouseover="selectColorMouseOverTDNamed('td_darkgray')"
					onmouseout="selectColorMouseOutTDNamed('td_darkgray')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #9ACD32" id="td_yellowgreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_yellowgreen')"
					onmouseover="selectColorMouseOverTDNamed('td_yellowgreen')"
					onmouseout="selectColorMouseOutTDNamed('td_yellowgreen')">&nbsp;</td>
				<td style="background-color: #2E8B57" id="td_seagreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_seagreen')"
					onmouseover="selectColorMouseOverTDNamed('td_seagreen')"
					onmouseout="selectColorMouseOutTDNamed('td_seagreen')">&nbsp;</td>
				<td style="background-color: #5F9EA0" id="td_cadetblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_cadetblue')"
					onmouseover="selectColorMouseOverTDNamed('td_cadetblue')"
					onmouseout="selectColorMouseOutTDNamed('td_cadetblue')">&nbsp;</td>
				<td style="background-color: #4682B4" id="td_steelblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_steelblue')"
					onmouseover="selectColorMouseOverTDNamed('td_steelblue')"
					onmouseout="selectColorMouseOutTDNamed('td_steelblue')">&nbsp;</td>
				<td style="background-color: #4169E1" id="td_royalblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_royalblue')"
					onmouseover="selectColorMouseOverTDNamed('td_royalblue')"
					onmouseout="selectColorMouseOutTDNamed('td_royalblue')">&nbsp;</td>
				<td style="background-color: #8A2BE2" id="td_blueviolet"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_blueviolet')"
					onmouseover="selectColorMouseOverTDNamed('td_blueviolet')"
					onmouseout="selectColorMouseOutTDNamed('td_blueviolet')">&nbsp;</td>
				<td style="background-color: #9932CC" id="td_darkorchid"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkorchid')"
					onmouseover="selectColorMouseOverTDNamed('td_darkorchid')"
					onmouseout="selectColorMouseOutTDNamed('td_darkorchid')">&nbsp;</td>
				<td style="background-color: #FF1493" id="td_deeppink"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_deeppink')"
					onmouseover="selectColorMouseOverTDNamed('td_deeppink')"
					onmouseout="selectColorMouseOutTDNamed('td_deeppink')">&nbsp;</td>
				<td style="background-color: #BC8F8F" id="td_rosybrown"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_rosybrown')"
					onmouseover="selectColorMouseOverTDNamed('td_rosybrown')"
					onmouseout="selectColorMouseOutTDNamed('td_rosybrown')">&nbsp;</td>
				<td style="background-color: #DC143C" id="td_crimson"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_crimson')"
					onmouseover="selectColorMouseOverTDNamed('td_crimson')"
					onmouseout="selectColorMouseOutTDNamed('td_crimson')">&nbsp;</td>
				<td style="background-color: #FF8C00" id="td_darkorange"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkorange')"
					onmouseover="selectColorMouseOverTDNamed('td_darkorange')"
					onmouseout="selectColorMouseOutTDNamed('td_darkorange')">&nbsp;</td>
				<td style="background-color: #DEB887" id="td_burlywood"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_burlywood')"
					onmouseover="selectColorMouseOverTDNamed('td_burlywood')"
					onmouseout="selectColorMouseOutTDNamed('td_burlywood')">&nbsp;</td>
				<td style="background-color: #BDB76B" id="td_darkkhaki"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkkhaki')"
					onmouseover="selectColorMouseOverTDNamed('td_darkkhaki')"
					onmouseout="selectColorMouseOutTDNamed('td_darkkhaki')">&nbsp;</td>
				<td style="background-color: #FFFFE0" id="td_lightyellow"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightyellow')"
					onmouseover="selectColorMouseOverTDNamed('td_lightyellow')"
					onmouseout="selectColorMouseOutTDNamed('td_lightyellow')">&nbsp;</td>
				<td style="background-color: #F0FFFF" id="td_azure"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_azure')"
					onmouseover="selectColorMouseOverTDNamed('td_azure')"
					onmouseout="selectColorMouseOutTDNamed('td_azure')">&nbsp;</td>
				<td style="background-color: #D3D3D3" id="td_lightgrey"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightgrey')"
					onmouseover="selectColorMouseOverTDNamed('td_lightgrey')"
					onmouseout="selectColorMouseOutTDNamed('td_lightgrey')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #7CFC00" id="td_lawngreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lawngreen')"
					onmouseover="selectColorMouseOverTDNamed('td_lawngreen')"
					onmouseout="selectColorMouseOutTDNamed('td_lawngreen')">&nbsp;</td>
				<td style="background-color: #3CB371" id="td_mediumseagreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumseagreen')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumseagreen')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumseagreen')">
				&nbsp;</td>
				<td style="background-color: #20B2AA" id="td_lightseagreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightseagreen')"
					onmouseover="selectColorMouseOverTDNamed('td_lightseagreen')"
					onmouseout="selectColorMouseOutTDNamed('td_lightseagreen')">&nbsp;</td>
				<td style="background-color: #00BFFF" id="td_deepskyblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_deepskyblue')"
					onmouseover="selectColorMouseOverTDNamed('td_deepskyblue')"
					onmouseout="selectColorMouseOutTDNamed('td_deepskyblue')">&nbsp;</td>
				<td style="background-color: #1E90FF" id="td_dodgerblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_dodgerblue')"
					onmouseover="selectColorMouseOverTDNamed('td_dodgerblue')"
					onmouseout="selectColorMouseOutTDNamed('td_dodgerblue')">&nbsp;</td>
				<td style="background-color: #6A5ACD" id="td_slateblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_slateblue')"
					onmouseover="selectColorMouseOverTDNamed('td_slateblue')"
					onmouseout="selectColorMouseOutTDNamed('td_slateblue')">&nbsp;</td>
				<td style="background-color: #BA55D3" id="td_mediumorchid"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumorchid')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumorchid')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumorchid')">&nbsp;</td>
				<td style="background-color: #D87093" id="td_palevioletred"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_palevioletred')"
					onmouseover="selectColorMouseOverTDNamed('td_palevioletred')"
					onmouseout="selectColorMouseOutTDNamed('td_palevioletred')">&nbsp;</td>
				<td style="background-color: #FA8072" id="td_salmon"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_salmon')"
					onmouseover="selectColorMouseOverTDNamed('td_salmon')"
					onmouseout="selectColorMouseOutTDNamed('td_salmon')">&nbsp;</td>
				<td style="background-color: #FF4500" id="td_orangered"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_orangered')"
					onmouseover="selectColorMouseOverTDNamed('td_orangered')"
					onmouseout="selectColorMouseOutTDNamed('td_orangered')">&nbsp;</td>
				<td style="background-color: #F4A460" id="td_sandybrown"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_sandybrown')"
					onmouseover="selectColorMouseOverTDNamed('td_sandybrown')"
					onmouseout="selectColorMouseOutTDNamed('td_sandybrown')">&nbsp;</td>
				<td style="background-color: #D2B48C" id="td_tan"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_tan')"
					onmouseover="selectColorMouseOverTDNamed('td_tan')"
					onmouseout="selectColorMouseOutTDNamed('td_tan')">&nbsp;</td>
				<td style="background-color: #FFD700" id="td_gold"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_gold')"
					onmouseover="selectColorMouseOverTDNamed('td_gold')"
					onmouseout="selectColorMouseOutTDNamed('td_gold')">&nbsp;</td>
				<td style="background-color: #FFFFF0" id="td_ivory"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_ivory')"
					onmouseover="selectColorMouseOverTDNamed('td_ivory')"
					onmouseout="selectColorMouseOutTDNamed('td_ivory')">&nbsp;</td>
				<td style="background-color: #F8F8FF" id="td_ghostwhite"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_ghostwhite')"
					onmouseover="selectColorMouseOverTDNamed('td_ghostwhite')"
					onmouseout="selectColorMouseOutTDNamed('td_ghostwhite')">&nbsp;</td>
				<td style="background-color: #DCDCDC" id="td_gainsboro"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_gainsboro')"
					onmouseover="selectColorMouseOverTDNamed('td_gainsboro')"
					onmouseout="selectColorMouseOutTDNamed('td_gainsboro')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #7FFF00" id="td_chartreuse"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_chartreuse')"
					onmouseover="selectColorMouseOverTDNamed('td_chartreuse')"
					onmouseout="selectColorMouseOutTDNamed('td_chartreuse')">&nbsp;</td>
				<td style="background-color: #32CD32" id="td_limegreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_limegreen')"
					onmouseover="selectColorMouseOverTDNamed('td_limegreen')"
					onmouseout="selectColorMouseOutTDNamed('td_limegreen')">&nbsp;</td>
				<td style="background-color: #66CDAA" id="td_mediumaquamarine"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumaquamarine')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumaquamarine')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumaquamarine')">
				&nbsp;</td>
				<td style="background-color: #00CED1" id="td_darkturquoise"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkturquoise')"
					onmouseover="selectColorMouseOverTDNamed('td_darkturquoise')"
					onmouseout="selectColorMouseOutTDNamed('td_darkturquoise')">&nbsp;</td>
				<td style="background-color: #6495ED" id="td_cornflowerblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_cornflowerblue')"
					onmouseover="selectColorMouseOverTDNamed('td_cornflowerblue')"
					onmouseout="selectColorMouseOutTDNamed('td_cornflowerblue')">
				&nbsp;</td>
				<td style="background-color: #7B68EE" id="td_mediumslateblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumslateblue')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumslateblue')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumslateblue')">
				&nbsp;</td>
				<td style="background-color: #DA70D6" id="td_orchid"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_orchid')"
					onmouseover="selectColorMouseOverTDNamed('td_orchid')"
					onmouseout="selectColorMouseOutTDNamed('td_orchid')">&nbsp;</td>
				<td style="background-color: #FF69B4" id="td_hotpink"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_hotpink')"
					onmouseover="selectColorMouseOverTDNamed('td_hotpink')"
					onmouseout="selectColorMouseOutTDNamed('td_hotpink')">&nbsp;</td>
				<td style="background-color: #F08080" id="td_lightcoral"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightcoral')"
					onmouseover="selectColorMouseOverTDNamed('td_lightcoral')"
					onmouseout="selectColorMouseOutTDNamed('td_lightcoral')">&nbsp;</td>
				<td style="background-color: #FF6347" id="td_tomato"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_tomato')"
					onmouseover="selectColorMouseOverTDNamed('td_tomato')"
					onmouseout="selectColorMouseOutTDNamed('td_tomato')">&nbsp;</td>
				<td style="background-color: #FFA500" id="td_orange"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_orange')"
					onmouseover="selectColorMouseOverTDNamed('td_orange')"
					onmouseout="selectColorMouseOutTDNamed('td_orange')">&nbsp;</td>
				<td style="background-color: #FFE4C4" id="td_bisque"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_bisque')"
					onmouseover="selectColorMouseOverTDNamed('td_bisque')"
					onmouseout="selectColorMouseOutTDNamed('td_bisque')">&nbsp;</td>
				<td style="background-color: #F0E68C" id="td_khaki"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_khaki')"
					onmouseover="selectColorMouseOverTDNamed('td_khaki')"
					onmouseout="selectColorMouseOutTDNamed('td_khaki')">&nbsp;</td>
				<td style="background-color: #FFF8DC" id="td_cornsilk"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_cornsilk')"
					onmouseover="selectColorMouseOverTDNamed('td_cornsilk')"
					onmouseout="selectColorMouseOutTDNamed('td_cornsilk')">&nbsp;</td>
				<td style="background-color: #FAF0E6" id="td_linen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_linen')"
					onmouseover="selectColorMouseOverTDNamed('td_linen')"
					onmouseout="selectColorMouseOutTDNamed('td_linen')">&nbsp;</td>
				<td style="background-color: #F5F5F5" id="td_whitesmoke"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_whitesmoke')"
					onmouseover="selectColorMouseOverTDNamed('td_whitesmoke')"
					onmouseout="selectColorMouseOutTDNamed('td_whitesmoke')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #ADFF2F" id="td_greenyellow"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_greenyellow')"
					onmouseover="selectColorMouseOverTDNamed('td_greenyellow')"
					onmouseout="selectColorMouseOutTDNamed('td_greenyellow')">&nbsp;</td>
				<td style="background-color: #8FBC8F" id="td_darkseagreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darkseagreen')"
					onmouseover="selectColorMouseOverTDNamed('td_darkseagreen')"
					onmouseout="selectColorMouseOutTDNamed('td_darkseagreen')">&nbsp;</td>
				<td style="background-color: #40E0D0" id="td_turquoise"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_turquoise')"
					onmouseover="selectColorMouseOverTDNamed('td_turquoise')"
					onmouseout="selectColorMouseOutTDNamed('td_turquoise')">&nbsp;</td>
				<td style="background-color: #48D1CC" id="td_mediumturquoise"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumturquoise')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumturquoise')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumturquoise')">
				&nbsp;</td>
				<td style="background-color: #87CEEB" id="td_skyblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_skyblue')"
					onmouseover="selectColorMouseOverTDNamed('td_skyblue')"
					onmouseout="selectColorMouseOutTDNamed('td_skyblue')">&nbsp;</td>
				<td style="background-color: #9370D8" id="td_mediumpurple"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumpurple')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumpurple')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumpurple')">&nbsp;</td>
				<td style="background-color: #EE82EE" id="td_violet"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_violet')"
					onmouseover="selectColorMouseOverTDNamed('td_violet')"
					onmouseout="selectColorMouseOutTDNamed('td_violet')">&nbsp;</td>
				<td style="background-color: #FFB6C1" id="td_lightpink"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightpink')"
					onmouseover="selectColorMouseOverTDNamed('td_lightpink')"
					onmouseout="selectColorMouseOutTDNamed('td_lightpink')">&nbsp;</td>
				<td style="background-color: #E9967A" id="td_darksalmon"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_darksalmon')"
					onmouseover="selectColorMouseOverTDNamed('td_darksalmon')"
					onmouseout="selectColorMouseOutTDNamed('td_darksalmon')">&nbsp;</td>
				<td style="background-color: #FF7F50" id="td_coral"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_coral')"
					onmouseover="selectColorMouseOverTDNamed('td_coral')"
					onmouseout="selectColorMouseOutTDNamed('td_coral')">&nbsp;</td>
				<td style="background-color: #FFDEAD" id="td_navajowhite"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_navajowhite')"
					onmouseover="selectColorMouseOverTDNamed('td_navajowhite')"
					onmouseout="selectColorMouseOutTDNamed('td_navajowhite')">&nbsp;</td>
				<td style="background-color: #FFEBCD" id="td_blanchedalmond"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_blanchedalmond')"
					onmouseover="selectColorMouseOverTDNamed('td_blanchedalmond')"
					onmouseout="selectColorMouseOutTDNamed('td_blanchedalmond')">
				&nbsp;</td>
				<td style="background-color: #EEE8AA" id="td_palegoldenrod"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_palegoldenrod')"
					onmouseover="selectColorMouseOverTDNamed('td_palegoldenrod')"
					onmouseout="selectColorMouseOutTDNamed('td_palegoldenrod')">&nbsp;</td>
				<td style="background-color: #FDF5E6" id="td_oldlace"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_oldlace')"
					onmouseover="selectColorMouseOverTDNamed('td_oldlace')"
					onmouseout="selectColorMouseOutTDNamed('td_oldlace')">&nbsp;</td>
				<td style="background-color: #FFF5EE" id="td_seashell"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_seashell')"
					onmouseover="selectColorMouseOverTDNamed('td_seashell')"
					onmouseout="selectColorMouseOutTDNamed('td_seashell')">&nbsp;</td>
				<td style="background-color: #F8F8FF" id="td_ghostwhite2"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_ghostwhite')"
					onmouseover="selectColorMouseOverTDNamed('td_ghostwhite')"
					onmouseout="selectColorMouseOutTDNamed('td_ghostwhite')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #98FB98" id="td_palegreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_palegreen')"
					onmouseover="selectColorMouseOverTDNamed('td_palegreen')"
					onmouseout="selectColorMouseOutTDNamed('td_palegreen')">&nbsp;</td>
				<td style="background-color: #00FF7F" id="td_springgreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_springgreen')"
					onmouseover="selectColorMouseOverTDNamed('td_springgreen')"
					onmouseout="selectColorMouseOutTDNamed('td_springgreen')">&nbsp;</td>
				<td style="background-color: #7FFFD4" id="td_aquamarine"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_aquamarine')"
					onmouseover="selectColorMouseOverTDNamed('td_aquamarine')"
					onmouseout="selectColorMouseOutTDNamed('td_aquamarine')">&nbsp;</td>
				<td style="background-color: #B0E0E6" id="td_powderblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_powderblue')"
					onmouseover="selectColorMouseOverTDNamed('td_powderblue')"
					onmouseout="selectColorMouseOutTDNamed('td_powderblue')">&nbsp;</td>
				<td style="background-color: #87CEFA" id="td_lightskyblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightskyblue')"
					onmouseover="selectColorMouseOverTDNamed('td_lightskyblue')"
					onmouseout="selectColorMouseOutTDNamed('td_lightskyblue')">&nbsp;</td>
				<td style="background-color: #B0C4DE" id="td_lightsteelblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightsteelblue')"
					onmouseover="selectColorMouseOverTDNamed('td_lightsteelblue')"
					onmouseout="selectColorMouseOutTDNamed('td_lightsteelblue')">
				&nbsp;</td>
				<td style="background-color: #DDA0DD" id="td_plum"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_plum')"
					onmouseover="selectColorMouseOverTDNamed('td_plum')"
					onmouseout="selectColorMouseOutTDNamed('td_plum')">&nbsp;</td>
				<td style="background-color: #FFC0CB" id="td_pink"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_pink')"
					onmouseover="selectColorMouseOverTDNamed('td_pink')"
					onmouseout="selectColorMouseOutTDNamed('td_pink')">&nbsp;</td>
				<td style="background-color: #FFA07A" id="td_lightsalmon"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightsalmon')"
					onmouseover="selectColorMouseOverTDNamed('td_lightsalmon')"
					onmouseout="selectColorMouseOutTDNamed('td_lightsalmon')">&nbsp;</td>
				<td style="background-color: #F5DEB3" id="td_wheat"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_wheat')"
					onmouseover="selectColorMouseOverTDNamed('td_wheat')"
					onmouseout="selectColorMouseOutTDNamed('td_wheat')">&nbsp;</td>
				<td style="background-color: #FFE4B5" id="td_moccasin"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_moccasin')"
					onmouseover="selectColorMouseOverTDNamed('td_moccasin')"
					onmouseout="selectColorMouseOutTDNamed('td_moccasin')">&nbsp;</td>
				<td style="background-color: #FAEBD7" id="td_antiquewhite"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_antiquewhite')"
					onmouseover="selectColorMouseOverTDNamed('td_antiquewhite')"
					onmouseout="selectColorMouseOutTDNamed('td_antiquewhite')">&nbsp;</td>
				<td style="background-color: #FFFACD" id="td_lemonchiffon"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lemonchiffon')"
					onmouseover="selectColorMouseOverTDNamed('td_lemonchiffon')"
					onmouseout="selectColorMouseOutTDNamed('td_lemonchiffon')">&nbsp;</td>
				<td style="background-color: #FFFAF0" id="td_floralwhite"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_floralwhite')"
					onmouseover="selectColorMouseOverTDNamed('td_floralwhite')"
					onmouseout="selectColorMouseOutTDNamed('td_floralwhite')">&nbsp;</td>
				<td style="background-color: #FFFAFA" id="td_snow"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_snow')"
					onmouseover="selectColorMouseOverTDNamed('td_snow')"
					onmouseout="selectColorMouseOutTDNamed('td_snow')">&nbsp;</td>
				<td style="background-color: #F0F8FF" id="td_aliceblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_aliceblue')"
					onmouseover="selectColorMouseOverTDNamed('td_aliceblue')"
					onmouseout="selectColorMouseOutTDNamed('td_aliceblue')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #90EE90" id="td_lightgreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightgreen')"
					onmouseover="selectColorMouseOverTDNamed('td_lightgreen')"
					onmouseout="selectColorMouseOutTDNamed('td_lightgreen')">&nbsp;</td>
				<td style="background-color: #00FA9A" id="td_mediumspringgreen"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mediumspringgreen')"
					onmouseover="selectColorMouseOverTDNamed('td_mediumspringgreen')"
					onmouseout="selectColorMouseOutTDNamed('td_mediumspringgreen')">
				&nbsp;</td>
				<td style="background-color: #AFEEEE" id="td_paleturquoise"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_paleturquoise')"
					onmouseover="selectColorMouseOverTDNamed('td_paleturquoise')"
					onmouseout="selectColorMouseOutTDNamed('td_paleturquoise')">&nbsp;</td>
				<td style="background-color: #E0FFFF" id="td_lightcyan"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightcyan')"
					onmouseover="selectColorMouseOverTDNamed('td_lightcyan')"
					onmouseout="selectColorMouseOutTDNamed('td_lightcyan')">&nbsp;</td>
				<td style="background-color: #ADD8E6" id="td_lightblue"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lightblue')"
					onmouseover="selectColorMouseOverTDNamed('td_lightblue')"
					onmouseout="selectColorMouseOutTDNamed('td_lightblue')">&nbsp;</td>
				<td style="background-color: #E6E6FA" id="td_lavender"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_lavender')"
					onmouseover="selectColorMouseOverTDNamed('td_lavender')"
					onmouseout="selectColorMouseOutTDNamed('td_lavender')">&nbsp;</td>
				<td style="background-color: #D8BFD8" id="td_thistle"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_thistle')"
					onmouseover="selectColorMouseOverTDNamed('td_thistle')"
					onmouseout="selectColorMouseOutTDNamed('td_thistle')">&nbsp;</td>
				<td style="background-color: #FFE4E1" id="td_mistyrose"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_mistyrose')"
					onmouseover="selectColorMouseOverTDNamed('td_mistyrose')"
					onmouseout="selectColorMouseOutTDNamed('td_mistyrose')">&nbsp;</td>
				<td style="background-color: #FFDAB9" id="td_peachpuff"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_peachpuff')"
					onmouseover="selectColorMouseOverTDNamed('td_peachpuff')"
					onmouseout="selectColorMouseOutTDNamed('td_peachpuff')">&nbsp;</td>
				<td style="background-color: #FFEFD5" id="td_papayawhip"
					class="td_selectColor"
					onclick="selectColorFromTDNamed('td_papayawhip')"
					onmouseover="selectColorMouseOverTDNamed('td_papayawhip')"
					onmouseout="selectColorMouseOutTDNamed('td_papayawhip')">&nbsp;</td>
			</tr>
<!--
			<tr>
				<td id="showNamedColors" colspan="16"></td>
			</tr>
-->
		</table>
		<div id="showNamedColors" ></div>
		</div>

		<div id="webPaletteDiv" style="display: none;">
				<div id="changeToNamedColorsButton"
					onclick="showNamedColorAuswahl()"
					onmouseover="buttonOver('changeToNamedColorsButton')"
					onmouseout="buttonOut('changeToNamedColorsButton')"
					class="buttonNormal" style="width: 160px; float: left;">Named&nbsp;Colors</div>
				<div class="buttonPressed" style="width: 160px; float: left;">Web&nbsp;Palette</div>
		<table id="webPaletteTable" class="thinDarkblueBorders" style="clear: both; float: none;">
<!--
			<tr>
				<td id="changeToNamedColorsButton"
					onclick="showNamedColorAuswahl()"
					onmouseover="buttonOver('changeToNamedColorsButton')"
					onmouseout="buttonOut('changeToNamedColorsButton')"
					class="buttonNormal" style="width: 156px;" colspan="6">Named&nbsp;Colors</td>
				<td class="buttonPressed" style="width: 156px;" colspan="6">Web&nbsp;Palette</td>
			</tr>
-->
			<tr>
				<td style="background-color: #FF00FF" id="td_FF00FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF00FF')"
					onmouseover="selectColorMouseOverTDWeb('td_FF00FF')"
					onmouseout="selectColorMouseOutTDWeb('td_FF00FF')">&nbsp;</td>
				<td style="background-color: #FF33FF" id="td_FF33FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF33FF')"
					onmouseover="selectColorMouseOverTDWeb('td_FF33FF')"
					onmouseout="selectColorMouseOutTDWeb('td_FF33FF')">&nbsp;</td>
				<td style="background-color: #FF66FF" id="td_FF66FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF66FF')"
					onmouseover="selectColorMouseOverTDWeb('td_FF66FF')"
					onmouseout="selectColorMouseOutTDWeb('td_FF66FF')">&nbsp;</td>
				<td style="background-color: #FF99FF" id="td_FF99FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF99FF')"
					onmouseover="selectColorMouseOverTDWeb('td_FF99FF')"
					onmouseout="selectColorMouseOutTDWeb('td_FF99FF')">&nbsp;</td>
				<td style="background-color: #FFCCFF" id="td_FFCCFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFCCFF')"
					onmouseover="selectColorMouseOverTDWeb('td_FFCCFF')"
					onmouseout="selectColorMouseOutTDWeb('td_FFCCFF')">&nbsp;</td>
				<td style="background-color: #FFFFFF" id="td_FFFFFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFFFFF')"
					onmouseover="selectColorMouseOverTDWeb('td_FFFFFF')"
					onmouseout="selectColorMouseOutTDWeb('td_FFFFFF')">&nbsp;</td>
				<td style="background-color: #FFFF66" id="td_FFFF66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFFF66')"
					onmouseover="selectColorMouseOverTDWeb('td_FFFF66')"
					onmouseout="selectColorMouseOutTDWeb('td_FFFF66')">&nbsp;</td>
				<td style="background-color: #FFCC66" id="td_FFCC66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFCC66')"
					onmouseover="selectColorMouseOverTDWeb('td_FFCC66')"
					onmouseout="selectColorMouseOutTDWeb('td_FFCC66')">&nbsp;</td>
				<td style="background-color: #FF9966" id="td_FF9966"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF9966')"
					onmouseover="selectColorMouseOverTDWeb('td_FF9966')"
					onmouseout="selectColorMouseOutTDWeb('td_FF9966')">&nbsp;</td>
				<td style="background-color: #FF6666" id="td_FF6666"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF6666')"
					onmouseover="selectColorMouseOverTDWeb('td_FF6666')"
					onmouseout="selectColorMouseOutTDWeb('td_FF6666')">&nbsp;</td>
				<td style="background-color: #FF3366" id="td_FF3366"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF3366')"
					onmouseover="selectColorMouseOverTDWeb('td_FF3366')"
					onmouseout="selectColorMouseOutTDWeb('td_FF3366')">&nbsp;</td>
				<td style="background-color: #FF0066" id="td_FF0066"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF0066')"
					onmouseover="selectColorMouseOverTDWeb('td_FF0066')"
					onmouseout="selectColorMouseOutTDWeb('td_FF0066')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #CC00FF" id="td_CC00FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC00FF')"
					onmouseover="selectColorMouseOverTDWeb('td_CC00FF')"
					onmouseout="selectColorMouseOutTDWeb('td_CC00FF')">&nbsp;</td>
				<td style="background-color: #CC33FF" id="td_CC33FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC33FF')"
					onmouseover="selectColorMouseOverTDWeb('td_CC33FF')"
					onmouseout="selectColorMouseOutTDWeb('td_CC33FF')">&nbsp;</td>
				<td style="background-color: #CC66FF" id="td_CC66FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC66FF')"
					onmouseover="selectColorMouseOverTDWeb('td_CC66FF')"
					onmouseout="selectColorMouseOutTDWeb('td_CC66FF')">&nbsp;</td>
				<td style="background-color: #CC99FF" id="td_CC99FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC99FF')"
					onmouseover="selectColorMouseOverTDWeb('td_CC99FF')"
					onmouseout="selectColorMouseOutTDWeb('td_CC99FF')">&nbsp;</td>
				<td style="background-color: #CCCCFF" id="td_CCCCFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCCCFF')"
					onmouseover="selectColorMouseOverTDWeb('td_CCCCFF')"
					onmouseout="selectColorMouseOutTDWeb('td_CCCCFF')">&nbsp;</td>
				<td style="background-color: #CCFFFF" id="td_CCFFFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCFFFF')"
					onmouseover="selectColorMouseOverTDWeb('td_CCFFFF')"
					onmouseout="selectColorMouseOutTDWeb('td_CCFFFF')">&nbsp;</td>
				<td style="background-color: #CCFF66" id="td_CCFF66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCFF66')"
					onmouseover="selectColorMouseOverTDWeb('td_CCFF66')"
					onmouseout="selectColorMouseOutTDWeb('td_CCFF66')">&nbsp;</td>
				<td style="background-color: #CCCC66" id="td_CCCC66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCCC66')"
					onmouseover="selectColorMouseOverTDWeb('td_CCCC66')"
					onmouseout="selectColorMouseOutTDWeb('td_CCCC66')">&nbsp;</td>
				<td style="background-color: #CC9966" id="td_CC9966"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC9966')"
					onmouseover="selectColorMouseOverTDWeb('td_CC9966')"
					onmouseout="selectColorMouseOutTDWeb('td_CC9966')">&nbsp;</td>
				<td style="background-color: #CC6666" id="td_CC6666"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC6666')"
					onmouseover="selectColorMouseOverTDWeb('td_CC6666')"
					onmouseout="selectColorMouseOutTDWeb('td_CC6666')">&nbsp;</td>
				<td style="background-color: #CC3366" id="td_CC3366"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC3366')"
					onmouseover="selectColorMouseOverTDWeb('td_CC3366')"
					onmouseout="selectColorMouseOutTDWeb('td_CC3366')">&nbsp;</td>
				<td style="background-color: #CC0066" id="td_CC0066"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC0066')"
					onmouseover="selectColorMouseOverTDWeb('td_CC0066')"
					onmouseout="selectColorMouseOutTDWeb('td_CC0066')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #9900FF" id="td_9900FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9900FF')"
					onmouseover="selectColorMouseOverTDWeb('td_9900FF')"
					onmouseout="selectColorMouseOutTDWeb('td_9900FF')">&nbsp;</td>
				<td style="background-color: #9933FF" id="td_9933FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9933FF')"
					onmouseover="selectColorMouseOverTDWeb('td_9933FF')"
					onmouseout="selectColorMouseOutTDWeb('td_9933FF')">&nbsp;</td>
				<td style="background-color: #9966FF" id="td_9966FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9966FF')"
					onmouseover="selectColorMouseOverTDWeb('td_9966FF')"
					onmouseout="selectColorMouseOutTDWeb('td_9966FF')">&nbsp;</td>
				<td style="background-color: #9999FF" id="td_9999FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9999FF')"
					onmouseover="selectColorMouseOverTDWeb('td_9999FF')"
					onmouseout="selectColorMouseOutTDWeb('td_9999FF')">&nbsp;</td>
				<td style="background-color: #99CCFF" id="td_99CCFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99CCFF')"
					onmouseover="selectColorMouseOverTDWeb('td_99CCFF')"
					onmouseout="selectColorMouseOutTDWeb('td_99CCFF')">&nbsp;</td>
				<td style="background-color: #99FFFF" id="td_99FFFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99FFFF')"
					onmouseover="selectColorMouseOverTDWeb('td_99FFFF')"
					onmouseout="selectColorMouseOutTDWeb('td_99FFFF')">&nbsp;</td>
				<td style="background-color: #99FF66" id="td_99FF66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99FF66')"
					onmouseover="selectColorMouseOverTDWeb('td_99FF66')"
					onmouseout="selectColorMouseOutTDWeb('td_99FF66')">&nbsp;</td>
				<td style="background-color: #99CC66" id="td_99CC66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99CC66')"
					onmouseover="selectColorMouseOverTDWeb('td_99CC66')"
					onmouseout="selectColorMouseOutTDWeb('td_99CC66')">&nbsp;</td>
				<td style="background-color: #999966" id="td_999966"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_999966')"
					onmouseover="selectColorMouseOverTDWeb('td_999966')"
					onmouseout="selectColorMouseOutTDWeb('td_999966')">&nbsp;</td>
				<td style="background-color: #996666" id="td_996666"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_996666')"
					onmouseover="selectColorMouseOverTDWeb('td_996666')"
					onmouseout="selectColorMouseOutTDWeb('td_996666')">&nbsp;</td>
				<td style="background-color: #993366" id="td_993366"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_993366')"
					onmouseover="selectColorMouseOverTDWeb('td_993366')"
					onmouseout="selectColorMouseOutTDWeb('td_993366')">&nbsp;</td>
				<td style="background-color: #990066" id="td_990066"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_990066')"
					onmouseover="selectColorMouseOverTDWeb('td_990066')"
					onmouseout="selectColorMouseOutTDWeb('td_990066')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #6600FF" id="td_6600FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6600FF')"
					onmouseover="selectColorMouseOverTDWeb('td_6600FF')"
					onmouseout="selectColorMouseOutTDWeb('td_6600FF')">&nbsp;</td>
				<td style="background-color: #6633FF" id="td_6633FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6633FF')"
					onmouseover="selectColorMouseOverTDWeb('td_6633FF')"
					onmouseout="selectColorMouseOutTDWeb('td_6633FF')">&nbsp;</td>
				<td style="background-color: #6666FF" id="td_6666FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6666FF')"
					onmouseover="selectColorMouseOverTDWeb('td_6666FF')"
					onmouseout="selectColorMouseOutTDWeb('td_6666FF')">&nbsp;</td>
				<td style="background-color: #6699FF" id="td_6699FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6699FF')"
					onmouseover="selectColorMouseOverTDWeb('td_6699FF')"
					onmouseout="selectColorMouseOutTDWeb('td_6699FF')">&nbsp;</td>
				<td style="background-color: #66CCFF" id="td_66CCFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66CCFF')"
					onmouseover="selectColorMouseOverTDWeb('td_66CCFF')"
					onmouseout="selectColorMouseOutTDWeb('td_66CCFF')">&nbsp;</td>
				<td style="background-color: #66FFFF" id="td_66FFFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66FFFF')"
					onmouseover="selectColorMouseOverTDWeb('td_66FFFF')"
					onmouseout="selectColorMouseOutTDWeb('td_66FFFF')">&nbsp;</td>
				<td style="background-color: #66FF66" id="td_66FF66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66FF66')"
					onmouseover="selectColorMouseOverTDWeb('td_66FF66')"
					onmouseout="selectColorMouseOutTDWeb('td_66FF66')">&nbsp;</td>
				<td style="background-color: #66CC66" id="td_66CC66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66CC66')"
					onmouseover="selectColorMouseOverTDWeb('td_66CC66')"
					onmouseout="selectColorMouseOutTDWeb('td_66CC66')">&nbsp;</td>
				<td style="background-color: #669966" id="td_669966"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_669966')"
					onmouseover="selectColorMouseOverTDWeb('td_669966')"
					onmouseout="selectColorMouseOutTDWeb('td_669966')">&nbsp;</td>
				<td style="background-color: #666666" id="td_666666"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_666666')"
					onmouseover="selectColorMouseOverTDWeb('td_666666')"
					onmouseout="selectColorMouseOutTDWeb('td_666666')">&nbsp;</td>
				<td style="background-color: #663366" id="td_663366"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_663366')"
					onmouseover="selectColorMouseOverTDWeb('td_663366')"
					onmouseout="selectColorMouseOutTDWeb('td_663366')">&nbsp;</td>
				<td style="background-color: #660066" id="td_660066"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_660066')"
					onmouseover="selectColorMouseOverTDWeb('td_660066')"
					onmouseout="selectColorMouseOutTDWeb('td_660066')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #3300FF" id="td_3300FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3300FF')"
					onmouseover="selectColorMouseOverTDWeb('td_3300FF')"
					onmouseout="selectColorMouseOutTDWeb('td_3300FF')">&nbsp;</td>
				<td style="background-color: #3333FF" id="td_3333FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3333FF')"
					onmouseover="selectColorMouseOverTDWeb('td_3333FF')"
					onmouseout="selectColorMouseOutTDWeb('td_3333FF')">&nbsp;</td>
				<td style="background-color: #3366FF" id="td_3366FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3366FF')"
					onmouseover="selectColorMouseOverTDWeb('td_3366FF')"
					onmouseout="selectColorMouseOutTDWeb('td_3366FF')">&nbsp;</td>
				<td style="background-color: #3399FF" id="td_3399FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3399FF')"
					onmouseover="selectColorMouseOverTDWeb('td_3399FF')"
					onmouseout="selectColorMouseOutTDWeb('td_3399FF')">&nbsp;</td>
				<td style="background-color: #33CCFF" id="td_33CCFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33CCFF')"
					onmouseover="selectColorMouseOverTDWeb('td_33CCFF')"
					onmouseout="selectColorMouseOutTDWeb('td_33CCFF')">&nbsp;</td>
				<td style="background-color: #33FFFF" id="td_33FFFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33FFFF')"
					onmouseover="selectColorMouseOverTDWeb('td_33FFFF')"
					onmouseout="selectColorMouseOutTDWeb('td_33FFFF')">&nbsp;</td>
				<td style="background-color: #33FF66" id="td_33FF66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33FF66')"
					onmouseover="selectColorMouseOverTDWeb('td_33FF66')"
					onmouseout="selectColorMouseOutTDWeb('td_33FF66')">&nbsp;</td>
				<td style="background-color: #33CC66" id="td_33CC66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33CC66')"
					onmouseover="selectColorMouseOverTDWeb('td_33CC66')"
					onmouseout="selectColorMouseOutTDWeb('td_33CC66')">&nbsp;</td>
				<td style="background-color: #339966" id="td_339966"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_339966')"
					onmouseover="selectColorMouseOverTDWeb('td_339966')"
					onmouseout="selectColorMouseOutTDWeb('td_339966')">&nbsp;</td>
				<td style="background-color: #336666" id="td_336666"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_336666')"
					onmouseover="selectColorMouseOverTDWeb('td_336666')"
					onmouseout="selectColorMouseOutTDWeb('td_336666')">&nbsp;</td>
				<td style="background-color: #333366" id="td_333366"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_333366')"
					onmouseover="selectColorMouseOverTDWeb('td_333366')"
					onmouseout="selectColorMouseOutTDWeb('td_333366')">&nbsp;</td>
				<td style="background-color: #330066" id="td_330066"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_330066')"
					onmouseover="selectColorMouseOverTDWeb('td_330066')"
					onmouseout="selectColorMouseOutTDWeb('td_330066')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #0000FF" id="td_0000FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0000FF')"
					onmouseover="selectColorMouseOverTDWeb('td_0000FF')"
					onmouseout="selectColorMouseOutTDWeb('td_0000FF')">&nbsp;</td>
				<td style="background-color: #0033FF" id="td_0033FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0033FF')"
					onmouseover="selectColorMouseOverTDWeb('td_0033FF')"
					onmouseout="selectColorMouseOutTDWeb('td_0033FF')">&nbsp;</td>
				<td style="background-color: #0066FF" id="td_0066FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0066FF')"
					onmouseover="selectColorMouseOverTDWeb('td_0066FF')"
					onmouseout="selectColorMouseOutTDWeb('td_0066FF')">&nbsp;</td>
				<td style="background-color: #0099FF" id="td_0099FF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0099FF')"
					onmouseover="selectColorMouseOverTDWeb('td_0099FF')"
					onmouseout="selectColorMouseOutTDWeb('td_0099FF')">&nbsp;</td>
				<td style="background-color: #00CCFF" id="td_00CCFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00CCFF')"
					onmouseover="selectColorMouseOverTDWeb('td_00CCFF')"
					onmouseout="selectColorMouseOutTDWeb('td_00CCFF')">&nbsp;</td>
				<td style="background-color: #00FFFF" id="td_00FFFF"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00FFFF')"
					onmouseover="selectColorMouseOverTDWeb('td_00FFFF')"
					onmouseout="selectColorMouseOutTDWeb('td_00FFFF')">&nbsp;</td>
				<td style="background-color: #00FF66" id="td_00FF66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00FF66')"
					onmouseover="selectColorMouseOverTDWeb('td_00FF66')"
					onmouseout="selectColorMouseOutTDWeb('td_00FF66')">&nbsp;</td>
				<td style="background-color: #00CC66" id="td_00CC66"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00CC66')"
					onmouseover="selectColorMouseOverTDWeb('td_00CC66')"
					onmouseout="selectColorMouseOutTDWeb('td_00CC66')">&nbsp;</td>
				<td style="background-color: #009966" id="td_009966"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_009966')"
					onmouseover="selectColorMouseOverTDWeb('td_009966')"
					onmouseout="selectColorMouseOutTDWeb('td_009966')">&nbsp;</td>
				<td style="background-color: #006666" id="td_006666"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_006666')"
					onmouseover="selectColorMouseOverTDWeb('td_006666')"
					onmouseout="selectColorMouseOutTDWeb('td_006666')">&nbsp;</td>
				<td style="background-color: #003366" id="td_003366"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_003366')"
					onmouseover="selectColorMouseOverTDWeb('td_003366')"
					onmouseout="selectColorMouseOutTDWeb('td_003366')">&nbsp;</td>
				<td style="background-color: #000066" id="td_000066"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_000066')"
					onmouseover="selectColorMouseOverTDWeb('td_000066')"
					onmouseout="selectColorMouseOutTDWeb('td_000066')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #0000CC" id="td_0000CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0000CC')"
					onmouseover="selectColorMouseOverTDWeb('td_0000CC')"
					onmouseout="selectColorMouseOutTDWeb('td_0000CC')">&nbsp;</td>
				<td style="background-color: #0033CC" id="td_0033CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0033CC')"
					onmouseover="selectColorMouseOverTDWeb('td_0033CC')"
					onmouseout="selectColorMouseOutTDWeb('td_0033CC')">&nbsp;</td>
				<td style="background-color: #0066CC" id="td_0066CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0066CC')"
					onmouseover="selectColorMouseOverTDWeb('td_0066CC')"
					onmouseout="selectColorMouseOutTDWeb('td_0066CC')">&nbsp;</td>
				<td style="background-color: #0099CC" id="td_0099CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_0099CC')"
					onmouseover="selectColorMouseOverTDWeb('td_0099CC')"
					onmouseout="selectColorMouseOutTDWeb('td_0099CC')">&nbsp;</td>
				<td style="background-color: #00CCCC" id="td_00CCCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00CCCC')"
					onmouseover="selectColorMouseOverTDWeb('td_00CCCC')"
					onmouseout="selectColorMouseOutTDWeb('td_00CCCC')">&nbsp;</td>
				<td style="background-color: #00FFCC" id="td_00FFCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00FFCC')"
					onmouseover="selectColorMouseOverTDWeb('td_00FFCC')"
					onmouseout="selectColorMouseOutTDWeb('td_00FFCC')">&nbsp;</td>
				<td style="background-color: #00FF33" id="td_00FF33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00FF33')"
					onmouseover="selectColorMouseOverTDWeb('td_00FF33')"
					onmouseout="selectColorMouseOutTDWeb('td_00FF33')">&nbsp;</td>
				<td style="background-color: #00CC33" id="td_00CC33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00CC33')"
					onmouseover="selectColorMouseOverTDWeb('td_00CC33')"
					onmouseout="selectColorMouseOutTDWeb('td_00CC33')">&nbsp;</td>
				<td style="background-color: #009933" id="td_009933"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_009933')"
					onmouseover="selectColorMouseOverTDWeb('td_009933')"
					onmouseout="selectColorMouseOutTDWeb('td_009933')">&nbsp;</td>
				<td style="background-color: #006633" id="td_006633"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_006633')"
					onmouseover="selectColorMouseOverTDWeb('td_006633')"
					onmouseout="selectColorMouseOutTDWeb('td_006633')">&nbsp;</td>
				<td style="background-color: #003333" id="td_003333"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_003333')"
					onmouseover="selectColorMouseOverTDWeb('td_003333')"
					onmouseout="selectColorMouseOutTDWeb('td_003333')">&nbsp;</td>
				<td style="background-color: #000033" id="td_000033"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_000033')"
					onmouseover="selectColorMouseOverTDWeb('td_000033')"
					onmouseout="selectColorMouseOutTDWeb('td_000033')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #3300CC" id="td_3300CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3300CC')"
					onmouseover="selectColorMouseOverTDWeb('td_3300CC')"
					onmouseout="selectColorMouseOutTDWeb('td_3300CC')">&nbsp;</td>
				<td style="background-color: #3333CC" id="td_3333CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3333CC')"
					onmouseover="selectColorMouseOverTDWeb('td_3333CC')"
					onmouseout="selectColorMouseOutTDWeb('td_3333CC')">&nbsp;</td>
				<td style="background-color: #3366CC" id="td_3366CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3366CC')"
					onmouseover="selectColorMouseOverTDWeb('td_3366CC')"
					onmouseout="selectColorMouseOutTDWeb('td_3366CC')">&nbsp;</td>
				<td style="background-color: #3399CC" id="td_3399CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_3399CC')"
					onmouseover="selectColorMouseOverTDWeb('td_3399CC')"
					onmouseout="selectColorMouseOutTDWeb('td_3399CC')">&nbsp;</td>
				<td style="background-color: #33CCCC" id="td_33CCCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33CCCC')"
					onmouseover="selectColorMouseOverTDWeb('td_33CCCC')"
					onmouseout="selectColorMouseOutTDWeb('td_33CCCC')">&nbsp;</td>
				<td style="background-color: #33FFCC" id="td_33FFCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33FFCC')"
					onmouseover="selectColorMouseOverTDWeb('td_33FFCC')"
					onmouseout="selectColorMouseOutTDWeb('td_33FFCC')">&nbsp;</td>
				<td style="background-color: #33FF33" id="td_33FF33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33FF33')"
					onmouseover="selectColorMouseOverTDWeb('td_33FF33')"
					onmouseout="selectColorMouseOutTDWeb('td_33FF33')">&nbsp;</td>
				<td style="background-color: #33CC33" id="td_33CC33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33CC33')"
					onmouseover="selectColorMouseOverTDWeb('td_33CC33')"
					onmouseout="selectColorMouseOutTDWeb('td_33CC33')">&nbsp;</td>
				<td style="background-color: #339933" id="td_339933"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_339933')"
					onmouseover="selectColorMouseOverTDWeb('td_339933')"
					onmouseout="selectColorMouseOutTDWeb('td_339933')">&nbsp;</td>
				<td style="background-color: #336633" id="td_336633"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_336633')"
					onmouseover="selectColorMouseOverTDWeb('td_336633')"
					onmouseout="selectColorMouseOutTDWeb('td_336633')">&nbsp;</td>
				<td style="background-color: #333333" id="td_333333"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_333333')"
					onmouseover="selectColorMouseOverTDWeb('td_333333')"
					onmouseout="selectColorMouseOutTDWeb('td_333333')">&nbsp;</td>
				<td style="background-color: #330033" id="td_330033"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_330033')"
					onmouseover="selectColorMouseOverTDWeb('td_330033')"
					onmouseout="selectColorMouseOutTDWeb('td_330033')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #6600CC" id="td_6600CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6600CC')"
					onmouseover="selectColorMouseOverTDWeb('td_6600CC')"
					onmouseout="selectColorMouseOutTDWeb('td_6600CC')">&nbsp;</td>
				<td style="background-color: #6633CC" id="td_6633CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6633CC')"
					onmouseover="selectColorMouseOverTDWeb('td_6633CC')"
					onmouseout="selectColorMouseOutTDWeb('td_6633CC')">&nbsp;</td>
				<td style="background-color: #6666CC" id="td_6666CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6666CC')"
					onmouseover="selectColorMouseOverTDWeb('td_6666CC')"
					onmouseout="selectColorMouseOutTDWeb('td_6666CC')">&nbsp;</td>
				<td style="background-color: #6699CC" id="td_6699CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_6699CC')"
					onmouseover="selectColorMouseOverTDWeb('td_6699CC')"
					onmouseout="selectColorMouseOutTDWeb('td_6699CC')">&nbsp;</td>
				<td style="background-color: #66CCCC" id="td_66CCCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66CCCC')"
					onmouseover="selectColorMouseOverTDWeb('td_66CCCC')"
					onmouseout="selectColorMouseOutTDWeb('td_66CCCC')">&nbsp;</td>
				<td style="background-color: #66FFCC" id="td_66FFCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66FFCC')"
					onmouseover="selectColorMouseOverTDWeb('td_66FFCC')"
					onmouseout="selectColorMouseOutTDWeb('td_66FFCC')">&nbsp;</td>
				<td style="background-color: #66FF33" id="td_66FF33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66FF33')"
					onmouseover="selectColorMouseOverTDWeb('td_66FF33')"
					onmouseout="selectColorMouseOutTDWeb('td_66FF33')">&nbsp;</td>
				<td style="background-color: #66CC33" id="td_66CC33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66CC33')"
					onmouseover="selectColorMouseOverTDWeb('td_66CC33')"
					onmouseout="selectColorMouseOutTDWeb('td_66CC33')">&nbsp;</td>
				<td style="background-color: #669933" id="td_669933"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_669933')"
					onmouseover="selectColorMouseOverTDWeb('td_669933')"
					onmouseout="selectColorMouseOutTDWeb('td_669933')">&nbsp;</td>
				<td style="background-color: #666633" id="td_666633"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_666633')"
					onmouseover="selectColorMouseOverTDWeb('td_666633')"
					onmouseout="selectColorMouseOutTDWeb('td_666633')">&nbsp;</td>
				<td style="background-color: #663333" id="td_663333"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_663333')"
					onmouseover="selectColorMouseOverTDWeb('td_663333')"
					onmouseout="selectColorMouseOutTDWeb('td_663333')">&nbsp;</td>
				<td style="background-color: #660033" id="td_660033"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_660033')"
					onmouseover="selectColorMouseOverTDWeb('td_660033')"
					onmouseout="selectColorMouseOutTDWeb('td_660033')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #9900CC; height: 12px;" id="td_9900CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9900CC')"
					onmouseover="selectColorMouseOverTDWeb('td_9900CC')"
					onmouseout="selectColorMouseOutTDWeb('td_9900CC')">&nbsp;</td>
				<td style="background-color: #9933CC; height: 12px;" id="td_9933CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9933CC')"
					onmouseover="selectColorMouseOverTDWeb('td_9933CC')"
					onmouseout="selectColorMouseOutTDWeb('td_9933CC')">&nbsp;</td>
				<td style="background-color: #9966CC; height: 12px;" id="td_9966CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9966CC')"
					onmouseover="selectColorMouseOverTDWeb('td_9966CC')"
					onmouseout="selectColorMouseOutTDWeb('td_9966CC')">&nbsp;</td>
				<td style="background-color: #9999CC; height: 12px;" id="td_9999CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_9999CC')"
					onmouseover="selectColorMouseOverTDWeb('td_9999CC')"
					onmouseout="selectColorMouseOutTDWeb('td_9999CC')">&nbsp;</td>
				<td style="background-color: #99CCCC; height: 12px;" id="td_99CCCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99CCCC')"
					onmouseover="selectColorMouseOverTDWeb('td_99CCCC')"
					onmouseout="selectColorMouseOutTDWeb('td_99CCCC')">&nbsp;</td>
				<td style="background-color: #99FFCC; height: 12px;" id="td_99FFCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99FFCC')"
					onmouseover="selectColorMouseOverTDWeb('td_99FFCC')"
					onmouseout="selectColorMouseOutTDWeb('td_99FFCC')">&nbsp;</td>
				<td style="background-color: #99FF33; height: 12px;" id="td_99FF33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99FF33')"
					onmouseover="selectColorMouseOverTDWeb('td_99FF33')"
					onmouseout="selectColorMouseOutTDWeb('td_99FF33')">&nbsp;</td>
				<td style="background-color: #99CC33; height: 12px;" id="td_99CC33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99CC33')"
					onmouseover="selectColorMouseOverTDWeb('td_99CC33')"
					onmouseout="selectColorMouseOutTDWeb('td_99CC33')">&nbsp;</td>
				<td style="background-color: #999933; height: 12px;" id="td_999933"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_999933')"
					onmouseover="selectColorMouseOverTDWeb('td_999933')"
					onmouseout="selectColorMouseOutTDWeb('td_999933')">&nbsp;</td>
				<td style="background-color: #996633; height: 12px;" id="td_996633"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_996633')"
					onmouseover="selectColorMouseOverTDWeb('td_996633')"
					onmouseout="selectColorMouseOutTDWeb('td_996633')">&nbsp;</td>
				<td style="background-color: #993333; height: 12px;" id="td_993333"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_993333')"
					onmouseover="selectColorMouseOverTDWeb('td_993333')"
					onmouseout="selectColorMouseOutTDWeb('td_993333')">&nbsp;</td>
				<td style="background-color: #990033; height: 12px;" id="td_990033"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_990033')"
					onmouseover="selectColorMouseOverTDWeb('td_990033')"
					onmouseout="selectColorMouseOutTDWeb('td_990033')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #CC00CC" id="td_CC00CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC00CC')"
					onmouseover="selectColorMouseOverTDWeb('td_CC00CC')"
					onmouseout="selectColorMouseOutTDWeb('td_CC00CC')">&nbsp;</td>
				<td style="background-color: #CC33CC" id="td_CC33CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC33CC')"
					onmouseover="selectColorMouseOverTDWeb('td_CC33CC')"
					onmouseout="selectColorMouseOutTDWeb('td_CC33CC')">&nbsp;</td>
				<td style="background-color: #CC66CC" id="td_CC66CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC66CC')"
					onmouseover="selectColorMouseOverTDWeb('td_CC66CC')"
					onmouseout="selectColorMouseOutTDWeb('td_CC66CC')">&nbsp;</td>
				<td style="background-color: #CC99CC" id="td_CC99CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC99CC')"
					onmouseover="selectColorMouseOverTDWeb('td_CC99CC')"
					onmouseout="selectColorMouseOutTDWeb('td_CC99CC')">&nbsp;</td>
				<td style="background-color: #CCCCCC" id="td_CCCCCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCCCCC')"
					onmouseover="selectColorMouseOverTDWeb('td_CCCCCC')"
					onmouseout="selectColorMouseOutTDWeb('td_CCCCCC')">&nbsp;</td>
				<td style="background-color: #CCFFCC" id="td_CCFFCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCFFCC')"
					onmouseover="selectColorMouseOverTDWeb('td_CCFFCC')"
					onmouseout="selectColorMouseOutTDWeb('td_CCFFCC')">&nbsp;</td>
				<td style="background-color: #CCFF33" id="td_CCFF33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCFF33')"
					onmouseover="selectColorMouseOverTDWeb('td_CCFF33')"
					onmouseout="selectColorMouseOutTDWeb('td_CCFF33')">&nbsp;</td>
				<td style="background-color: #CCCC33" id="td_CCCC33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCCC33')"
					onmouseover="selectColorMouseOverTDWeb('td_CCCC33')"
					onmouseout="selectColorMouseOutTDWeb('td_CCCC33')">&nbsp;</td>
				<td style="background-color: #CC9933" id="td_CC9933"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC9933')"
					onmouseover="selectColorMouseOverTDWeb('td_CC9933')"
					onmouseout="selectColorMouseOutTDWeb('td_CC9933')">&nbsp;</td>
				<td style="background-color: #CC6633" id="td_CC6633"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC6633')"
					onmouseover="selectColorMouseOverTDWeb('td_CC6633')"
					onmouseout="selectColorMouseOutTDWeb('td_CC6633')">&nbsp;</td>
				<td style="background-color: #CC3333" id="td_CC3333"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC3333')"
					onmouseover="selectColorMouseOverTDWeb('td_CC3333')"
					onmouseout="selectColorMouseOutTDWeb('td_CC3333')">&nbsp;</td>
				<td style="background-color: #CC0033" id="td_CC0033"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC0033')"
					onmouseover="selectColorMouseOverTDWeb('td_CC0033')"
					onmouseout="selectColorMouseOutTDWeb('td_CC0033')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #FF00CC" id="td_FF00CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF00CC')"
					onmouseover="selectColorMouseOverTDWeb('td_FF00CC')"
					onmouseout="selectColorMouseOutTDWeb('td_FF00CC')">&nbsp;</td>
				<td style="background-color: #FF33CC" id="td_FF33CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF33CC')"
					onmouseover="selectColorMouseOverTDWeb('td_FF33CC')"
					onmouseout="selectColorMouseOutTDWeb('td_FF33CC')">&nbsp;</td>
				<td style="background-color: #FF66CC" id="td_FF66CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF66CC')"
					onmouseover="selectColorMouseOverTDWeb('td_FF66CC')"
					onmouseout="selectColorMouseOutTDWeb('td_FF66CC')">&nbsp;</td>
				<td style="background-color: #FF99CC" id="td_FF99CC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF99CC')"
					onmouseover="selectColorMouseOverTDWeb('td_FF99CC')"
					onmouseout="selectColorMouseOutTDWeb('td_FF99CC')">&nbsp;</td>
				<td style="background-color: #FFCCCC" id="td_FFCCCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFCCCC')"
					onmouseover="selectColorMouseOverTDWeb('td_FFCCCC')"
					onmouseout="selectColorMouseOutTDWeb('td_FFCCCC')">&nbsp;</td>
				<td style="background-color: #FFFFCC" id="td_FFFFCC"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFFFCC')"
					onmouseover="selectColorMouseOverTDWeb('td_FFFFCC')"
					onmouseout="selectColorMouseOutTDWeb('td_FFFFCC')">&nbsp;</td>
				<td style="background-color: #FFFF33" id="td_FFFF33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFFF33')"
					onmouseover="selectColorMouseOverTDWeb('td_FFFF33')"
					onmouseout="selectColorMouseOutTDWeb('td_FFFF33')">&nbsp;</td>
				<td style="background-color: #FFCC33" id="td_FFCC33"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFCC33')"
					onmouseover="selectColorMouseOverTDWeb('td_FFCC33')"
					onmouseout="selectColorMouseOutTDWeb('td_FFCC33')">&nbsp;</td>
				<td style="background-color: #FF9933" id="td_FF9933"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF9933')"
					onmouseover="selectColorMouseOverTDWeb('td_FF9933')"
					onmouseout="selectColorMouseOutTDWeb('td_FF9933')">&nbsp;</td>
				<td style="background-color: #FF6633" id="td_FF6633"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF6633')"
					onmouseover="selectColorMouseOverTDWeb('td_FF6633')"
					onmouseout="selectColorMouseOutTDWeb('td_FF6633')">&nbsp;</td>
				<td style="background-color: #FF3333" id="td_FF3333"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF3333')"
					onmouseover="selectColorMouseOverTDWeb('td_FF3333')"
					onmouseout="selectColorMouseOutTDWeb('td_FF3333')">&nbsp;</td>
				<td style="background-color: #FF0033" id="td_FF0033"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF0033')"
					onmouseover="selectColorMouseOverTDWeb('td_FF0033')"
					onmouseout="selectColorMouseOutTDWeb('td_FF0033')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #FF0099" id="td_FF0099"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF0099')"
					onmouseover="selectColorMouseOverTDWeb('td_FF0099')"
					onmouseout="selectColorMouseOutTDWeb('td_FF0099')">&nbsp;</td>
				<td style="background-color: #FF3399" id="td_FF3399"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF3399')"
					onmouseover="selectColorMouseOverTDWeb('td_FF3399')"
					onmouseout="selectColorMouseOutTDWeb('td_FF3399')">&nbsp;</td>
				<td style="background-color: #FF6699" id="td_FF6699"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF6699')"
					onmouseover="selectColorMouseOverTDWeb('td_FF6699')"
					onmouseout="selectColorMouseOutTDWeb('td_FF6699')">&nbsp;</td>
				<td style="background-color: #FF9999" id="td_FF9999"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF9999')"
					onmouseover="selectColorMouseOverTDWeb('td_FF9999')"
					onmouseout="selectColorMouseOutTDWeb('td_FF9999')">&nbsp;</td>
				<td style="background-color: #FFCC99" id="td_FFCC99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFCC99')"
					onmouseover="selectColorMouseOverTDWeb('td_FFCC99')"
					onmouseout="selectColorMouseOutTDWeb('td_FFCC99')">&nbsp;</td>
				<td style="background-color: #FFFF99" id="td_FFFF99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFFF99')"
					onmouseover="selectColorMouseOverTDWeb('td_FFFF99')"
					onmouseout="selectColorMouseOutTDWeb('td_FFFF99')">&nbsp;</td>
				<td style="background-color: #FFFF00" id="td_FFFF00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFFF00')"
					onmouseover="selectColorMouseOverTDWeb('td_FFFF00')"
					onmouseout="selectColorMouseOutTDWeb('td_FFFF00')">&nbsp;</td>
				<td style="background-color: #FFCC00" id="td_FFCC00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FFCC00')"
					onmouseover="selectColorMouseOverTDWeb('td_FFCC00')"
					onmouseout="selectColorMouseOutTDWeb('td_FFCC00')">&nbsp;</td>
				<td style="background-color: #FF9900" id="td_FF9900"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF9900')"
					onmouseover="selectColorMouseOverTDWeb('td_FF9900')"
					onmouseout="selectColorMouseOutTDWeb('td_FF9900')">&nbsp;</td>
				<td style="background-color: #FF6600" id="td_FF6600"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF6600')"
					onmouseover="selectColorMouseOverTDWeb('td_FF6600')"
					onmouseout="selectColorMouseOutTDWeb('td_FF6600')">&nbsp;</td>
				<td style="background-color: #FF3300" id="td_FF3300"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF3300')"
					onmouseover="selectColorMouseOverTDWeb('td_FF3300')"
					onmouseout="selectColorMouseOutTDWeb('td_FF3300')">&nbsp;</td>
				<td style="background-color: #FF0000" id="td_FF0000"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_FF0000')"
					onmouseover="selectColorMouseOverTDWeb('td_FF0000')"
					onmouseout="selectColorMouseOutTDWeb('td_FF0000')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #CC0099" id="td_CC0099"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC0099')"
					onmouseover="selectColorMouseOverTDWeb('td_CC0099')"
					onmouseout="selectColorMouseOutTDWeb('td_CC0099')">&nbsp;</td>
				<td style="background-color: #CC3399" id="td_CC3399"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC3399')"
					onmouseover="selectColorMouseOverTDWeb('td_CC3399')"
					onmouseout="selectColorMouseOutTDWeb('td_CC3399')">&nbsp;</td>
				<td style="background-color: #CC6699" id="td_CC6699"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC6699')"
					onmouseover="selectColorMouseOverTDWeb('td_CC6699')"
					onmouseout="selectColorMouseOutTDWeb('td_CC6699')">&nbsp;</td>
				<td style="background-color: #CC9999" id="td_CC9999"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC9999')"
					onmouseover="selectColorMouseOverTDWeb('td_CC9999')"
					onmouseout="selectColorMouseOutTDWeb('td_CC9999')">&nbsp;</td>
				<td style="background-color: #CCCC99" id="td_CCCC99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCCC99')"
					onmouseover="selectColorMouseOverTDWeb('td_CCCC99')"
					onmouseout="selectColorMouseOutTDWeb('td_CCCC99')">&nbsp;</td>
				<td style="background-color: #CCFF99" id="td_CCFF99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCFF99')"
					onmouseover="selectColorMouseOverTDWeb('td_CCFF99')"
					onmouseout="selectColorMouseOutTDWeb('td_CCFF99')">&nbsp;</td>
				<td style="background-color: #CCFF00" id="td_CCFF00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCFF00')"
					onmouseover="selectColorMouseOverTDWeb('td_CCFF00')"
					onmouseout="selectColorMouseOutTDWeb('td_CCFF00')">&nbsp;</td>
				<td style="background-color: #CCCC00" id="td_CCCC00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CCCC00')"
					onmouseover="selectColorMouseOverTDWeb('td_CCCC00')"
					onmouseout="selectColorMouseOutTDWeb('td_CCCC00')">&nbsp;</td>
				<td style="background-color: #CC9900" id="td_CC9900"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC9900')"
					onmouseover="selectColorMouseOverTDWeb('td_CC9900')"
					onmouseout="selectColorMouseOutTDWeb('td_CC9900')">&nbsp;</td>
				<td style="background-color: #CC6600" id="td_CC6600"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC6600')"
					onmouseover="selectColorMouseOverTDWeb('td_CC6600')"
					onmouseout="selectColorMouseOutTDWeb('td_CC6600')">&nbsp;</td>
				<td style="background-color: #CC3300" id="td_CC3300"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC3300')"
					onmouseover="selectColorMouseOverTDWeb('td_CC3300')"
					onmouseout="selectColorMouseOutTDWeb('td_CC3300')">&nbsp;</td>
				<td style="background-color: #CC0000" id="td_CC0000"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_CC0000')"
					onmouseover="selectColorMouseOverTDWeb('td_CC0000')"
					onmouseout="selectColorMouseOutTDWeb('td_CC0000')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #990099" id="td_990099"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_990099')"
					onmouseover="selectColorMouseOverTDWeb('td_990099')"
					onmouseout="selectColorMouseOutTDWeb('td_990099')">&nbsp;</td>
				<td style="background-color: #993399" id="td_993399"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_993399')"
					onmouseover="selectColorMouseOverTDWeb('td_993399')"
					onmouseout="selectColorMouseOutTDWeb('td_993399')">&nbsp;</td>
				<td style="background-color: #996699" id="td_996699"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_996699')"
					onmouseover="selectColorMouseOverTDWeb('td_996699')"
					onmouseout="selectColorMouseOutTDWeb('td_996699')">&nbsp;</td>
				<td style="background-color: #999999" id="td_999999"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_999999')"
					onmouseover="selectColorMouseOverTDWeb('td_999999')"
					onmouseout="selectColorMouseOutTDWeb('td_999999')">&nbsp;</td>
				<td style="background-color: #99CC99" id="td_99CC99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99CC99')"
					onmouseover="selectColorMouseOverTDWeb('td_99CC99')"
					onmouseout="selectColorMouseOutTDWeb('td_99CC99')">&nbsp;</td>
				<td style="background-color: #99FF99" id="td_99FF99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99FF99')"
					onmouseover="selectColorMouseOverTDWeb('td_99FF99')"
					onmouseout="selectColorMouseOutTDWeb('td_99FF99')">&nbsp;</td>
				<td style="background-color: #99FF00" id="td_99FF00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99FF00')"
					onmouseover="selectColorMouseOverTDWeb('td_99FF00')"
					onmouseout="selectColorMouseOutTDWeb('td_99FF00')">&nbsp;</td>
				<td style="background-color: #99CC00" id="td_99CC00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_99CC00')"
					onmouseover="selectColorMouseOverTDWeb('td_99CC00')"
					onmouseout="selectColorMouseOutTDWeb('td_99CC00')">&nbsp;</td>
				<td style="background-color: #999900" id="td_999900"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_999900')"
					onmouseover="selectColorMouseOverTDWeb('td_999900')"
					onmouseout="selectColorMouseOutTDWeb('td_999900')">&nbsp;</td>
				<td style="background-color: #996600" id="td_996600"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_996600')"
					onmouseover="selectColorMouseOverTDWeb('td_996600')"
					onmouseout="selectColorMouseOutTDWeb('td_996600')">&nbsp;</td>
				<td style="background-color: #993300" id="td_993300"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_993300')"
					onmouseover="selectColorMouseOverTDWeb('td_993300')"
					onmouseout="selectColorMouseOutTDWeb('td_993300')">&nbsp;</td>
				<td style="background-color: #990000" id="td_990000"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_990000')"
					onmouseover="selectColorMouseOverTDWeb('td_990000')"
					onmouseout="selectColorMouseOutTDWeb('td_990000')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #660099" id="td_660099"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_660099')"
					onmouseover="selectColorMouseOverTDWeb('td_660099')"
					onmouseout="selectColorMouseOutTDWeb('td_660099')">&nbsp;</td>
				<td style="background-color: #663399" id="td_663399"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_663399')"
					onmouseover="selectColorMouseOverTDWeb('td_663399')"
					onmouseout="selectColorMouseOutTDWeb('td_663399')">&nbsp;</td>
				<td style="background-color: #666699" id="td_666699"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_666699')"
					onmouseover="selectColorMouseOverTDWeb('td_666699')"
					onmouseout="selectColorMouseOutTDWeb('td_666699')">&nbsp;</td>
				<td style="background-color: #669999" id="td_669999"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_669999')"
					onmouseover="selectColorMouseOverTDWeb('td_669999')"
					onmouseout="selectColorMouseOutTDWeb('td_669999')">&nbsp;</td>
				<td style="background-color: #66CC99" id="td_66CC99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66CC99')"
					onmouseover="selectColorMouseOverTDWeb('td_66CC99')"
					onmouseout="selectColorMouseOutTDWeb('td_66CC99')">&nbsp;</td>
				<td style="background-color: #66FF99" id="td_66FF99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66FF99')"
					onmouseover="selectColorMouseOverTDWeb('td_66FF99')"
					onmouseout="selectColorMouseOutTDWeb('td_66FF99')">&nbsp;</td>
				<td style="background-color: #66FF00" id="td_66FF00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66FF00')"
					onmouseover="selectColorMouseOverTDWeb('td_66FF00')"
					onmouseout="selectColorMouseOutTDWeb('td_66FF00')">&nbsp;</td>
				<td style="background-color: #66CC00" id="td_66CC00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_66CC00')"
					onmouseover="selectColorMouseOverTDWeb('td_66CC00')"
					onmouseout="selectColorMouseOutTDWeb('td_66CC00')">&nbsp;</td>
				<td style="background-color: #669900" id="td_669900"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_669900')"
					onmouseover="selectColorMouseOverTDWeb('td_669900')"
					onmouseout="selectColorMouseOutTDWeb('td_669900')">&nbsp;</td>
				<td style="background-color: #666600" id="td_666600"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_666600')"
					onmouseover="selectColorMouseOverTDWeb('td_666600')"
					onmouseout="selectColorMouseOutTDWeb('td_666600')">&nbsp;</td>
				<td style="background-color: #663300" id="td_663300"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_663300')"
					onmouseover="selectColorMouseOverTDWeb('td_663300')"
					onmouseout="selectColorMouseOutTDWeb('td_663300')">&nbsp;</td>
				<td style="background-color: #660000" id="td_660000"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_660000')"
					onmouseover="selectColorMouseOverTDWeb('td_660000')"
					onmouseout="selectColorMouseOutTDWeb('td_660000')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #330099" id="td_330099"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_330099')"
					onmouseover="selectColorMouseOverTDWeb('td_330099')"
					onmouseout="selectColorMouseOutTDWeb('td_330099')">&nbsp;</td>
				<td style="background-color: #333399" id="td_333399"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_333399')"
					onmouseover="selectColorMouseOverTDWeb('td_333399')"
					onmouseout="selectColorMouseOutTDWeb('td_333399')">&nbsp;</td>
				<td style="background-color: #336699" id="td_336699"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_336699')"
					onmouseover="selectColorMouseOverTDWeb('td_336699')"
					onmouseout="selectColorMouseOutTDWeb('td_336699')">&nbsp;</td>
				<td style="background-color: #339999" id="td_339999"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_339999')"
					onmouseover="selectColorMouseOverTDWeb('td_339999')"
					onmouseout="selectColorMouseOutTDWeb('td_339999')">&nbsp;</td>
				<td style="background-color: #33CC99" id="td_33CC99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33CC99')"
					onmouseover="selectColorMouseOverTDWeb('td_33CC99')"
					onmouseout="selectColorMouseOutTDWeb('td_33CC99')">&nbsp;</td>
				<td style="background-color: #33FF99" id="td_33FF99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33FF99')"
					onmouseover="selectColorMouseOverTDWeb('td_33FF99')"
					onmouseout="selectColorMouseOutTDWeb('td_33FF99')">&nbsp;</td>
				<td style="background-color: #33FF00" id="td_33FF00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33FF00')"
					onmouseover="selectColorMouseOverTDWeb('td_33FF00')"
					onmouseout="selectColorMouseOutTDWeb('td_33FF00')">&nbsp;</td>
				<td style="background-color: #33CC00" id="td_33CC00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_33CC00')"
					onmouseover="selectColorMouseOverTDWeb('td_33CC00')"
					onmouseout="selectColorMouseOutTDWeb('td_33CC00')">&nbsp;</td>
				<td style="background-color: #339900" id="td_339900"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_339900')"
					onmouseover="selectColorMouseOverTDWeb('td_339900')"
					onmouseout="selectColorMouseOutTDWeb('td_339900')">&nbsp;</td>
				<td style="background-color: #336600" id="td_336600"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_336600')"
					onmouseover="selectColorMouseOverTDWeb('td_336600')"
					onmouseout="selectColorMouseOutTDWeb('td_336600')">&nbsp;</td>
				<td style="background-color: #333300" id="td_333300"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_333300')"
					onmouseover="selectColorMouseOverTDWeb('td_333300')"
					onmouseout="selectColorMouseOutTDWeb('td_333300')">&nbsp;</td>
				<td style="background-color: #330000" id="td_330000"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_330000')"
					onmouseover="selectColorMouseOverTDWeb('td_330000')"
					onmouseout="selectColorMouseOutTDWeb('td_330000')">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color: #000099" id="td_000099"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_000099')"
					onmouseover="selectColorMouseOverTDWeb('td_000099')"
					onmouseout="selectColorMouseOutTDWeb('td_000099')">&nbsp;</td>
				<td style="background-color: #003399" id="td_003399"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_003399')"
					onmouseover="selectColorMouseOverTDWeb('td_003399')"
					onmouseout="selectColorMouseOutTDWeb('td_003399')">&nbsp;</td>
				<td style="background-color: #006699" id="td_006699"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_006699')"
					onmouseover="selectColorMouseOverTDWeb('td_006699')"
					onmouseout="selectColorMouseOutTDWeb('td_006699')">&nbsp;</td>
				<td style="background-color: #009999" id="td_009999"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_009999')"
					onmouseover="selectColorMouseOverTDWeb('td_009999')"
					onmouseout="selectColorMouseOutTDWeb('td_009999')">&nbsp;</td>
				<td style="background-color: #00CC99" id="td_00CC99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00CC99')"
					onmouseover="selectColorMouseOverTDWeb('td_00CC99')"
					onmouseout="selectColorMouseOutTDWeb('td_00CC99')">&nbsp;</td>
				<td style="background-color: #00FF99" id="td_00FF99"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00FF99')"
					onmouseover="selectColorMouseOverTDWeb('td_00FF99')"
					onmouseout="selectColorMouseOutTDWeb('td_00FF99')">&nbsp;</td>
				<td style="background-color: #00FF00" id="td_00FF00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00FF00')"
					onmouseover="selectColorMouseOverTDWeb('td_00FF00')"
					onmouseout="selectColorMouseOutTDWeb('td_00FF00')">&nbsp;</td>
				<td style="background-color: #00CC00" id="td_00CC00"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_00CC00')"
					onmouseover="selectColorMouseOverTDWeb('td_00CC00')"
					onmouseout="selectColorMouseOutTDWeb('td_00CC00')">&nbsp;</td>
				<td style="background-color: #009900" id="td_009900"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_009900')"
					onmouseover="selectColorMouseOverTDWeb('td_009900')"
					onmouseout="selectColorMouseOutTDWeb('td_009900')">&nbsp;</td>
				<td style="background-color: #006600" id="td_006600"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_006600')"
					onmouseover="selectColorMouseOverTDWeb('td_006600')"
					onmouseout="selectColorMouseOutTDWeb('td_006600')">&nbsp;</td>
				<td style="background-color: #003300" id="td_003300"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_003300')"
					onmouseover="selectColorMouseOverTDWeb('td_003300')"
					onmouseout="selectColorMouseOutTDWeb('td_003300')">&nbsp;</td>
				<td style="background-color: #000000" id="td_000000"
					class="td_selectWebColor"
					onclick="selectColorFromTDWeb('td_000000')"
					onmouseover="selectColorMouseOverTDWeb('td_000000')"
					onmouseout="selectColorMouseOutTDWeb('td_000000')">&nbsp;</td>
			</tr>
<!--
			<tr>
				<td id="showWebPalette" colspan="12"></td>
			</tr>
-->
		</table>
		<div id="showWebPalette"></div>
		</div>

		<table class="thinDarkblueBorders" style="width: 100%; display: none;" 
			id="FontsAuswahl">
			<tr>
				<td style="width: 50%">Special Fonts:<br />
				<select size="10" id="specialFontsMultipleBox"
					ondblclick="chooseSpeFonts()"  onchange="showFontSampleSpe()">
					<option value="Arial, ">Arial</option>
					<option value="Arial Black, ">Arial Black</option>
					<option value="Comic Sans MS, ">Comic Sans MS</option>
					<option value="Courier New, ">Courier New</option>
					<option value="Estrangelo Edessa, ">Estrangelo Edessa</option>
					<option value="Franklin Gothic Medium, ">Franklin Gothic Medium</option>
					<option value="Gautami, ">Gautami</option>
					<option value="Georgia, ">Georgia</option>
					<option value="Georgia Italic Impact, ">Georgia Italic Impact</option>
					<option value="Latha, ">Latha</option>
					<option value="Lucida Console, ">Lucida Console</option>
					<option value="Lucida Sans Unicode, ">Lucida Sans Unicode</option>
					<option value="Microsoft Sans Serif, ">Microsoft Sans Serif</option>
					<option value="Modern MS Sans Serif, ">Modern MS Sans Serif</option>
					<option value="MS Serif, ">MS Serif</option>
					<option value="Mv Boli, ">Mv Boli</option>
					<option value="Palatino Linotype, ">Palatino Linotype</option>
					<option value="Roman, ">Roman</option>
					<option value="Script, ">Script</option>
					<option value="Small Fonts, ">Small Fonts</option>
					<option value="Symbol, ">Symbol</option>
					<option value="Tahoma, ">Tahoma</option>
					<option value="Times New Roman, ">Times New Roman</option>
					<option value="Trebuchet MS, ">Trebuchet MS</option>
					<option value="Tunga, ">Tunga</option>
					<option value="Verdana, ">Verdana</option>
					<option value="Webdings, ">Webdings</option>
					<option value="WingDings, ">WingDings</option>
				</select>
				<div id="specialSampleTextBox" style="height: 24px;">AaBbCc</div>
<!--
				<input class="sameLikeBG width100percent" type="text"
					name="specialSampleTextBox" size="20" value="AaBbCc"
					id="specialSampleTextBox" readonly="readonly" /></td>
				<td style="width: 50%">Generic Fonts:<br />
-->
				</td>
				<td style="width: 50%">Generic Fonts:<br />
				<select size="5" id="genericFontsMultipleBox"
					ondblclick="chooseGenFonts()" onchange="showFontSampleGen()">
					<option value="Serif, ">Serif</option>
					<option value="Sans-Serif, ">Sans-Serif</option>
					<option value="Cursive, ">Cursive</option>
					<option value="Fantasy, ">Fantasy</option>
					<option value="Monospace, ">Monospace</option>
				</select>
				<div id="genericSampleTextBox" style="height: 24px;">AaBbCc</div>
<!--
				<input class="sameLikeBG width100percent" type="text"
					size="20" value="AaBbCc"
					id="genericSampleTextBox" readonly="readonly" /></td>
-->
			</tr>
			<tr>
				<td colspan="2">
				<div class="help"><img src="<?=$cssStyleOn['WEBROOT_IMAGE_PATH']?>/addinfoband.gif"
					style="width: 16px; height: 16px; border: 0" alt="Tip" /> Click on
				a font in one of the list boxes to see a preview underneath the list
				boxes. Doubleclick adds the font to the list in the text box.</div>
				</td>
			</tr>
		</table>
		<table style="display: none; width: 100%"
			class="Hand thinDarkblueBorders" id="BoldTable">
			<tr>
				<td onclick="selectBoldOnClickTD('normal')">normal</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('bold')">bold</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('bolder')">bolder</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('lighter')">lighter</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('100')">100</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('200')">200</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('300')">300</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('400')">400-normal</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('500')">500</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('600')">600</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('700')">700-bold</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('800')">800</td>
			</tr>
			<tr>
				<td onclick="selectBoldOnClickTD('900')">900</td>
			</tr>
		</table>
		<table style="display: none; width: 100%"
			class="Hand thinDarkblueBorders" id="FontSizeTable">
			<tr>
				<td onclick="selectFontSizeOnClickTD('smaller')">Smaller</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('larger')">Larger</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('xx-small')">XX-Small</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('x-small')">X-Small</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('small')">Small</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('medium')">Medium</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('large')">Large</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('x-large')">X-Large</td>
			</tr>
			<tr>
				<td onclick="selectFontSizeOnClickTD('xx-large')">XX-Large</td>
			</tr>
		</table>
		<table style="display: none; width: 100%"
			class="Hand thinDarkblueBorders" id="BGImagePositionHorTable">
			<tr>
				<td onclick="selectBGImagePositionHorOnClickTD('left')">Left</td>
			</tr>
			<tr>
				<td onclick="selectBGImagePositionHorOnClickTD('center')">Center</td>
			</tr>
			<tr>
				<td onclick="selectBGImagePositionHorOnClickTD('right')">Right</td>
			</tr>
		</table>
		<table style="display: none; width: 100%"
			class="Hand thinDarkblueBorders" id="BGImagePositionVerTable">
			<tr>
				<td onclick="selectBGImagePositionVerOnClickTD('top')">Top</td>
			</tr>
			<tr>
				<td onclick="selectBGImagePositionVerOnClickTD('center')">Center</td>
			</tr>
			<tr>
				<td onclick="selectBGImagePositionVerOnClickTD('bottom')">Bottom</td>
			</tr>
		</table>
		<table style="display: none; width: 100%"
			class="Hand thinDarkblueBorders" id="LinesTable">
			<tr>
				<td onclick="selectLinesOnClick('normal')">normal</td>
			</tr>
		</table>
		<table style="display: none; width: 100%"
			class="Hand thinDarkblueBorders" id="LettersTable">
			<tr>
				<td onclick="selectLettersOnClick('normal')">normal</td>
			</tr>
		</table>
		<table style="display: none; width: 100%" class="thinDarkblueBorders"
			id="SampleImagetable">
			<tr>
				<td class="Hand" onclick="selectBulledImage('<?=$cssStyleOn['WEBROOT_SAMPLE_PATH']?>/Sample_1.gif')">
				<img alt="" src="<?=$cssStyleOn['WEBROOT_SAMPLE_PATH']?>/Sample_1.gif"
					style="width: 16px; height: 16px; border: 0" /> Sample 1</td>
			</tr>
			<tr>
				<td class="Hand" onclick="selectBulledImage('<?=$cssStyleOn['WEBROOT_SAMPLE_PATH']?>/Sample_2.gif')">
				<img alt="" src="<?=$cssStyleOn['WEBROOT_SAMPLE_PATH']?>/Sample_2.gif"
					style="width: 16px; height: 16px; border: 0" /> Sample 2</td>
			</tr>
			<tr>
				<td class="Hand" onclick="selectBulledImage('<?=$cssStyleOn['WEBROOT_SAMPLE_PATH']?>/Sample_3.gif')">
				<img alt="" src="<?=$cssStyleOn['WEBROOT_SAMPLE_PATH']?>/Sample_3.gif"
					style="width: 16px; height: 16px; border: 0" /> Sample 3</td>
			</tr>
		</table>
		&nbsp;
		</td>
	</tr>
    <tr>
        <th class="csseditor" style="white-space: nowrap;">Preview<div id="NotInPreviewInfo">&nbsp;(Not all settings are reflected)</div></div></th>
        <th class="csseditor">Code</th>
    </tr>
	<tr>
		<td id="tdpreview" class="csseditor" style="width: 390px;">
<!--
		<table border="1"
			style="border-collapse: collapse; border-color: #3D5FA3; width: 420px;"
			id="table39">
			<tr>
				<td>
-->
				<div class="td_SampleText" id="SampleTextarea"  style="background: none; width: 390px;">Lorem ipsum dolor sit
				amet, consectetuer adipiscing elit. Sed orci dolor, mattis ac,
				molestie a, pulvinar et, mi. Nullam nibh sem, sollicitudin at,
				congue id, malesuada non, lacus.</div>
				<img alt="" id="SampleImageNormal"
					src="<?=$cssStyleOn['WEBROOT_IMAGE_PATH']?>/SampleImageNormal.gif" style="display: none;"
					/>
					<img alt="" id="SampleImageRelativ"
					src="<?=$cssStyleOn['WEBROOT_IMAGE_PATH']?>/SampleImageRelativ.gif" style="display: none;"
					/>
					<img alt="" id="SampleImageAbsolute"
					src="<?=$cssStyleOn['WEBROOT_IMAGE_PATH']?>/SampleImageAbsolute.gif" style="display: none;"
					/>
					<img alt="" id="SampleImagePadding"
					src="<?=$cssStyleOn['WEBROOT_IMAGE_PATH']?>/PaddingAll.gif" style="display: none;" />
					<img
					alt="" id="SampleImageMargin" src="<?=$cssStyleOn['WEBROOT_IMAGE_PATH']?>/MarginAll.gif"
					style="display: none;" />

<!--
                </td>
			</tr>
		</table>
-->
		</td>
		<td id="tdcode" class="csseditor">
<!--
		<table border="1"
			style="border-collapse: collapse; border-color: #3D5FA3; width: 100%"
			id="table37">
			<tr>
				<td>
-->

				<textarea id="CodeTextarea" rows="6" cols="50" onchange="readoutCode()">
x { <?=get_magic_quotes_gpc()?stripslashes($_REQUEST['css_code']):$_REQUEST['css_code']?> }
				</textarea>
<!--
                </td>
			</tr>
		</table>
-->
		<div class="displayNONE" id="Hidden">&nbsp;</div>
		<div id="SampleList" class="displayNONE"></div>
		<div class="displayNONE" id="readoutElement">&nbsp;</div>
		</td>
	</tr>
</table>


















            </TD>
          </TR>
        </TBODY>
      </TABLE>
    </FORM>
  </BODY>
</HTML>
<?
	}

?>