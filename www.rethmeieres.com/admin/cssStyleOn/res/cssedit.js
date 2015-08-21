	/***************************************************************
	 * cssStyleOn - http://cssStyleOn.c0n.de 2008 by Stefan Baur
	 *--------------------------------------------------------------
	 *  res/cssedit.js
	 *     CSS Editor - based on cssmate (http://cssmate.com)
	 *--------------------------------------------------------------
	 *  TODO: make methods more generic
	 *         (eg. one routine for colors, ...)
	 *        use tab/layout framework
	 **************************************************************/

	// return editing results to caller
function CSSEditReturn()
{
	var destID=document.getElementById('CSSEditTargetID').value;

	var CodeBoxValue = document.getElementById('CodeTextarea').value;

		//strip enclosing braces
	var OpenBracketPos = CodeBoxValue.indexOf('{');
	var CloseBracketPos = CodeBoxValue.indexOf('}');
	CodeBoxValue = CodeBoxValue.substring(OpenBracketPos + 1, CloseBracketPos);

		//return our results
	OnCSSEditorUpdated(destID,CodeBoxValue.replace(/\n/g,'').replace(/;\s*;/g,';'));

	/*
		old method: running in different windows
	var manager = (window.opener && !window.opener.closed)?window.opener:window.parent;

	if (manager)
	{
		var CodeBoxValue = document.getElementById('CodeTextarea').value;
		var OpenBracketPos = CodeBoxValue.indexOf('{');
		var CloseBracketPos = CodeBoxValue.indexOf('}');
		CodeBoxValue = CodeBoxValue.substring(OpenBracketPos + 1, CloseBracketPos);

		manager.OnCSSEditorUpdated(destID,CodeBoxValue.replace(/\n/g,'').replace(/;\s*;/g,';'));

			//are we running in a separate window? -> close it
		if(window.opener && !window.opener.closed)window.close();
	}
	else
	{
		alert("Target not found!");
	}*/
}

	// return without updating
function CSSEditCancel()
{
		//return nothing
	OnCSSEditorUpdated(null,null);
/*
		old method: running in different windows
	var manager = (window.opener && !window.opener.closed)?window.opener:window.parent;
	if (manager)
	{
		manager.OnCSSEditorUpdated(null,null);
			//are we running in a separate window? -> close it
		if(window.opener && !window.opener.closed)window.close();
	}
	else
	{
		alert("Target not found!");
	}*/
}

////////////////////////////////////////////////////////////////////
// below the somewhat fixed and slightly modified code
//  based on cssmate to fit our needs.

function getValueForKeyword(keyword,code)
{
	var searchPos=0;

	while ( (searchPos=code.indexOf(keyword+':',searchPos)) != -1)
	{
		var LastIndexOfKeyword = searchPos + keyword.length + 2;
		var Semikolon = code.indexOf(";",LastIndexOfKeyword);
		if (code.substring(searchPos-1,searchPos) != "-")
		{
			var searchedStyleValue = code.substring(LastIndexOfKeyword,Semikolon);
			return(searchedStyleValue);
		}
		searchPos++;
	}
}


function CSSEditReadoutCode()
{

	var CodeBoxValue = document.getElementById('CodeTextarea').value;
	var OpenBracketPos = CodeBoxValue.indexOf('{');
	var CloseBracketPos = CodeBoxValue.indexOf('}');
	CodeBoxValue = CodeBoxValue.substring(OpenBracketPos + 1, CloseBracketPos);
	document.getElementById('readoutElement').style.cssText = CodeBoxValue;
	var NewCodeBoxValue = document.getElementById('readoutElement').style.cssText.toLowerCase()+";";

///////////////////////////////////////
//***********************************//
//*		Funktionsteil fuer Font		*//
//***********************************//
///////////////////////////////////////

	document.getElementById('usedFonts').value='';
	var searchedString = getValueForKeyword("font-family",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('usedFonts').value = searchedString + ", ";
	}



	document.getElementById('CBox').value='';
	var searchedString = getValueForKeyword("color",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('CBox').value = searchedString;
	}



	document.getElementById('FontSizeTextBox').value='';
	document.getElementById('dropDownListFontSize').value='';
	var searchedString = getValueForKeyword("font-size",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('FontSizeTextBox').value = searchedString.substring(0,searchedString.length-2);;
			document.getElementById('dropDownListFontSize').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (NewCodeBoxValue.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('FontSizeTextBox').value = searchedString.substring(0,searchedString.length-1);;
			document.getElementById('dropDownListFontSize').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
		else
		{
			document.getElementById('FontSizeTextBox').value = searchedString;
		}
	}


	document.getElementById('BoldTextBox').value='';
	var searchedString = getValueForKeyword("font-weight",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('BoldTextBox').value = searchedString;
	}



	document.getElementById('radioButtonItalicClear').checked = true;
	document.getElementById('radioButtonItalic').checked = false;
	document.getElementById('radioButtonItalicNormal').checked = false;
	var searchedString = getValueForKeyword("font-style",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('radioButtonItalic').checked = (searchedString == "italic" );
		document.getElementById('radioButtonItalicNormal').checked = (searchedString == "normal" );
		document.getElementById('radioButtonItalicClear').checked = (searchedString != "italic" && searchedString != "normal");
	}

	document.getElementById('radioButtonSmallCapsClear').checked = true;
	document.getElementById('radioButtonSmallCaps').checked = false;
	document.getElementById('radioButtonSmallCapsNormal').checked = false;
	var searchedString = getValueForKeyword("font-variant",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('radioButtonSmallCaps').checked = (searchedString == "small-caps" );
		document.getElementById('radioButtonSmallCapsNormal').checked = (searchedString == "normal" );
		document.getElementById('radioButtonSmallCapsClear').checked = (searchedString != "small-caps" && searchedString != "normal");
	}


	document.getElementById('dropDownListCapitalization').options[0].selected = true;
	var searchedString = getValueForKeyword("text-transform",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('dropDownListCapitalization').options[1].selected = true;
		}
		else if (searchedString == "capitalize")
		{
			document.getElementById('dropDownListCapitalization').options[2].selected = true;
		}
		else if (searchedString == "lowercase")
		{
			document.getElementById('dropDownListCapitalization').options[3].selected = true;
		}
		else if (searchedString == "uppercase")
		{
			document.getElementById('dropDownListCapitalization').options[4].selected = true;
		}
	}


	document.getElementById('CheckBoxUnderline').checked = false;
	document.getElementById('CheckBoxOverline').checked = false;
	document.getElementById('CheckBoxStrikethrough').checked = false;
	document.getElementById('CheckBoxNone').checked = false;
	var searchedString = getValueForKeyword("text-decoration",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('CheckBoxUnderline').checked = (searchedString.indexOf('underline') > -1);
		document.getElementById('CheckBoxOverline').checked = (searchedString.indexOf('overline') > -1);
		document.getElementById('CheckBoxStrikethrough').checked = (searchedString.indexOf('line-through') > -1);
		document.getElementById('CheckBoxNone').checked = (searchedString.length == 0);
	}


/*	var searchedString = getValueForKeyword("filter",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('FilterTextBox').value = searchedString;
	}
*/

///////////////////////////////////////////////
//*******************************************//
//*		Funktionsteil fuer Background		*//
//*******************************************//
///////////////////////////////////////////////


	document.getElementById('transparentCheckBox').checked = false;
	document.getElementById('bgColorTextBox').value = '';
	var searchedString = getValueForKeyword("background-color",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "transparent")
		{
			document.getElementById('transparentCheckBox').checked = true;
		}
		else
		{
			document.getElementById('bgColorTextBox').value = searchedString;
		}
	}

	document.getElementById('imageInputTextBox').value = '';
	var searchedString = getValueForKeyword("background-image",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('imageInputTextBox').value = searchedString;
	}



	document.getElementById('bgTilingDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("background-repeat",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "repeat-x")
		{
			document.getElementById('bgTilingDropDownList').options[1].selected = true;
		}
		else if (searchedString == "repeat-y")
		{
			document.getElementById('bgTilingDropDownList').options[2].selected = true;
		}
		else if (searchedString == "repeat")
		{
			document.getElementById('bgTilingDropDownList').options[3].selected = true;
		}
		else if (searchedString == "no-repeat")
		{
			document.getElementById('bgTilingDropDownList').options[4].selected = true;
		}
	}


	document.getElementById('bgScrollingDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("background-attachment",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "scroll")
		{
			document.getElementById('bgScrollingDropDownList').options[1].selected = true;
		}
		else if (searchedString == "fixed")
		{
			document.getElementById('bgScrollingDropDownList').options[2].selected = true;
		}
	}

	document.getElementById('HorizontaleBGPositionTextBox').value = '';
	document.getElementById('HorizontalBGPositionUnitDropDownList').value = '';
	document.getElementById('VerticalBGPositionTextBox').value = '';
	document.getElementById('VerticalBGPositionUnitDropDownList').value = '';
	var searchedString = getValueForKeyword("background-position-x",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('HorizontaleBGPositionTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('HorizontalBGPositionUnitDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('HorizontaleBGPositionTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('HorizontalBGPositionUnitDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
		else
		{
			document.getElementById('HorizontaleBGPositionTextBox').value = searchedString;
		}
	}



	var searchedString = getValueForKeyword("background-position",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		for (serchBlank = 0;serchBlank < searchedString.length;serchBlank++)
		{
			if(searchedString.charAt(serchBlank) == " ")
			{
				var BGPosX = searchedString.substring(0,serchBlank);
				var BGPosY = searchedString.substring(serchBlank+1);

				if (BGPosX.substring(BGPosX.length-2,BGPosX.length) == "px"||BGPosX.substring(BGPosX.length-2,BGPosX.length) == "pt"||BGPosX.substring(BGPosX.length-2,BGPosX.length) == "pc"||BGPosX.substring(BGPosX.length-2,BGPosX.length) == "mm"||BGPosX.substring(BGPosX.length-2,BGPosX.length) == "cm"||BGPosX.substring(BGPosX.length-2,BGPosX.length) == "in"||BGPosX.substring(BGPosX.length-2,BGPosX.length) == "em"||BGPosX.substring(BGPosX.length-2,BGPosX.length) == "ex")
				{
					document.getElementById('HorizontaleBGPositionTextBox').value = BGPosX.substring(0,BGPosX.length-2);
					document.getElementById('HorizontalBGPositionUnitDropDownList').value = BGPosX.substring(BGPosX.length-2,BGPosX.length);
				}
				else if (BGPosX.substring(BGPosX.length-1,BGPosX.length) == "%")
				{
					document.getElementById('HorizontaleBGPositionTextBox').value = BGPosX.substring(0,BGPosX.length-1);
					document.getElementById('HorizontalBGPositionUnitDropDownList').value = BGPosX.substring(BGPosX.length-1,BGPosX.length);
				}
				else
				{
					document.getElementById('HorizontaleBGPositionTextBox').value = BGPosX;
				}


				if (BGPosY.substring(BGPosY.length-2,BGPosY.length) == "px"||BGPosY.substring(BGPosY.length-2,BGPosY.length) == "pt"||BGPosY.substring(BGPosY.length-2,BGPosY.length) == "pc"||BGPosY.substring(BGPosY.length-2,BGPosY.length) == "mm"||BGPosY.substring(BGPosY.length-2,BGPosY.length) == "cm"||BGPosY.substring(BGPosY.length-2,BGPosY.length) == "in"||BGPosY.substring(BGPosY.length-2,BGPosY.length) == "em"||BGPosY.substring(BGPosY.length-2,BGPosY.length) == "ex")
				{
					document.getElementById('VerticalBGPositionTextBox').value = BGPosY.substring(0,BGPosY.length-2);
					document.getElementById('VerticalBGPositionUnitDropDownList').value = BGPosY.substring(BGPosY.length-2,BGPosY.length);
				}
				else if (BGPosY.substring(BGPosY.length-1,BGPosY.length) == "%")
				{
					document.getElementById('VerticalBGPositionTextBox').value = BGPosY.substring(0,BGPosY.length-1);
					document.getElementById('VerticalBGPositionUnitDropDownList').value = BGPosY.substring(BGPosY.length-1,BGPosY.length);
				}
				else
				{
					document.getElementById('VerticalBGPositionTextBox').value = BGPosY;
				}
			}
		}
	}


	var searchedString = getValueForKeyword("background-position-y",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('VerticalBGPositionTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('VerticalBGPositionUnitDropDownList').value = searchedString.substring(searchedString.length-2);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('VerticalBGPositionTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('VerticalBGPositionUnitDropDownList').value = searchedString.substring(searchedString.length-1);
		}
		else
		{
			document.getElementById('VerticalBGPositionTextBox').value = searchedString;
		}
	}




	document.getElementById('cursorDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("cursor",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "auto")
		{
			document.getElementById('cursorDropDownList').options[1].selected = true;
		}
		else if (searchedString  == "default")
		{
			document.getElementById('cursorDropDownList').options[2].selected = true;
		}
		else if (searchedString == "crosshair")
		{
			document.getElementById('cursorDropDownList').options[3].selected = true;
		}
		else if (searchedString == "hand")
		{
			document.getElementById('cursorDropDownList').options[4].selected = true;
		}
		else if (searchedString == "move")
		{
			document.getElementById('cursorDropDownList').options[5].selected = true;
		}
		else if (searchedString == "n-resize")
		{
			document.getElementById('cursorDropDownList').options[6].selected = true;
		}
		else if (searchedString == "s-resize")
		{
			document.getElementById('cursorDropDownList').options[7].selected = true;
		}
		else if (searchedString == "w-resize")
		{
			document.getElementById('cursorDropDownList').options[8].selected = true;
		}
		else if (searchedString == "e-resize")
		{
			document.getElementById('cursorDropDownList').options[9].selected = true;
		}
		else if (searchedString == "nw-resize")
		{
			document.getElementById('cursorDropDownList').options[10].selected = true;
		}
		else if (searchedString == "sw-resize")
		{
			document.getElementById('cursorDropDownList').options[11].selected = true;
		}
		else if (searchedString == "ne-resize")
		{
			document.getElementById('cursorDropDownList').options[12].selected = true;
		}
		else if (searchedString == "se-resize")
		{
			document.getElementById('cursorDropDownList').options[13].selected = true;
		}
		else if (searchedString == "text")
		{
			document.getElementById('cursorDropDownList').options[14].selected = true;
		}
		else if (searchedString == "wait")
		{
			document.getElementById('cursorDropDownList').options[15].selected = true;
		}
		else if (searchedString == "help")
		{
			document.getElementById('cursorDropDownList').options[16].selected = true;
		}
	}





///////////////////////////////////////
//***********************************//
//*		Funktionsteil fuer Text		*//
//***********************************//
///////////////////////////////////////

	document.getElementById('horizontalAlignmentDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("text-align",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "left")
		{
			document.getElementById('horizontalAlignmentDropDownList').options[1].selected = true;
		}
		else if (searchedString == "center")
		{
			document.getElementById('horizontalAlignmentDropDownList').options[2].selected = true;
		}
		else if (searchedString == "right")
		{
			document.getElementById('horizontalAlignmentDropDownList').options[3].selected = true;
		}
		else if (searchedString == "justify")
		{
			document.getElementById('horizontalAlignmentDropDownList').options[4].selected = true;
		}
	}


	document.getElementById('justificationHorAlignmentDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("text-justify",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "auto")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[1].selected = true;
		}
		else if (searchedString == "inter-word")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[2].selected = true;
		}
		else if (searchedString == "newspaper")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[3].selected = true;
		}
		else if (searchedString == "distribute")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[4].selected = true;
		}
		else if (searchedString == "distribute-all-lines")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[5].selected = true;
		}
		else if (searchedString == "inter-cluster")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[6].selected = true;
		}
		else if (searchedString == "inter-ideograph")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[7].selected = true;
		}
		else if (searchedString == "inter-word")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[8].selected = true;
		}
		else if (searchedString == "kashida")
		{
			document.getElementById('justificationHorAlignmentDropDownList').options[9].selected = true;
		}
	}


	document.getElementById('spacingBetweenLettersTextBox').value = '';
	document.getElementById('spacingBetweenLettersUnitsDropDownList').value = '';
	var searchedString = getValueForKeyword("letter-spacing",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('spacingBetweenLettersTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('spacingBetweenLettersUnitsDropDownList').value = searchedString.substring(searchedString.length-2);
		}
		else
		{
			document.getElementById('spacingBetweenLettersTextBox').value = searchedString;
		}
	}


	document.getElementById('verticalAlignmentDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("vertical-align",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "baseline")
		{
			document.getElementById('verticalAlignmentDropDownList').options[1].selected = true;
		}
		else if (searchedString == "sub")
		{
			document.getElementById('verticalAlignmentDropDownList').options[2].selected = true;
		}
		else if (searchedString == "super")
		{
			document.getElementById('verticalAlignmentDropDownList').options[3].selected = true;
		}
		else if (searchedString == "top")
		{
			document.getElementById('verticalAlignmentDropDownList').options[4].selected = true;
		}
		else if (searchedString == "text-top")
		{
			document.getElementById('verticalAlignmentDropDownList').options[5].selected = true;
		}
		else if (searchedString == "middle")
		{
			document.getElementById('verticalAlignmentDropDownList').options[6].selected = true;
		}
		else if (searchedString == "bottom")
		{
			document.getElementById('verticalAlignmentDropDownList').options[7].selected = true;
		}
		else if (searchedString == "text-bottom")
		{
			document.getElementById('verticalAlignmentDropDownList').options[8].selected = true;
		}
	}



	document.getElementById('spacingBetweenLinesTextBox').value = '';
	document.getElementById('spacingBetweenLinesUnitsDropDownList').value = '';
	var searchedString = getValueForKeyword("line-height",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('spacingBetweenLinesTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('spacingBetweenLinesUnitsDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('spacingBetweenLinesTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('spacingBetweenLinesUnitsDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
		else
		{
			document.getElementById('spacingBetweenLinesTextBox').value = searchedString;
		}
	}


	document.getElementById('textFlowIdentationTextBox').value = '';
	document.getElementById('textFlowIdentationDropDownList').value = '';
	var searchedString = getValueForKeyword("text-indent",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('textFlowIdentationTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('textFlowIdentationDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('textFlowIdentationTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('textFlowIdentationDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}

	document.getElementById('textFlowTextDirectionDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("direction",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "ltr")
		{
			document.getElementById('textFlowTextDirectionDropDownList').options[1].selected = true;
		}
		else if (searchedString == "rtl")
		{
			document.getElementById('textFlowTextDirectionDropDownList').options[2].selected = true;
		}
	}






///////////////////////////////////////////
//***************************************//
//*		Funktionsteil fuer Position		*//
//***************************************//
///////////////////////////////////////////


	document.getElementById('positionModeDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("position",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "static")
		{
			document.getElementById('positionModeDropDownList').options[1].selected = true;
		}
		else if (searchedString == "relative")
		{
			document.getElementById('positionModeDropDownList').options[2].selected = true;
		}
		else if (searchedString == "absolute")
		{
			document.getElementById('positionModeDropDownList').options[3].selected = true;
		}
	}




	document.getElementById('positionTopTextBox').value = '';
	document.getElementById('positionTopDropDownList').value = '';
	var searchedString = getValueForKeyword("top",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('positionTopTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('positionTopDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('positionTopTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('positionTopDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}


	document.getElementById('positionHeightTextBox').value = '';
	document.getElementById('positionHeightDropDownList').value = '';
	var searchedString = getValueForKeyword("height",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('positionHeightTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('positionHeightDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('positionHeightTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('positionHeightDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}


	document.getElementById('positionLeftTextBox').value = '';
	document.getElementById('positionLeftDropDownList').value = '';
	var searchedString = getValueForKeyword("left",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('positionLeftTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('positionLeftDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('positionLeftTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('positionLeftDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}


	document.getElementById('positionWitdthTextBox').value = '';
	document.getElementById('positionWidthDropDownList').value = '';
	var searchedString = getValueForKeyword("width",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('positionWitdthTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('positionWidthDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('positionWitdthTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('positionWidthDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}

	document.getElementById('positionZIndexTextBox').value = '';
	var searchedString = getValueForKeyword("z-index",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('positionZIndexTextBox').value = searchedString;
	}



	document.getElementById('flowControlVisibilityDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("visibility",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "hidden")
		{
			document.getElementById('flowControlVisibilityDropDownList').options[1].selected = true;
		}
		else if (searchedString == "visible")
		{
			document.getElementById('flowControlVisibilityDropDownList').options[2].selected = true;
		}
	}



	document.getElementById('flowControlDisplayDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("display",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('flowControlDisplayDropDownList').options[1].selected = true;
		}
		else if (searchedString == "block")
		{
			document.getElementById('flowControlDisplayDropDownList').options[2].selected = true;
		}
		else if (searchedString == "inline")
		{
			document.getElementById('flowControlDisplayDropDownList').options[3].selected = true;
		}
	}


	document.getElementById('flowControlAllowTextToFlowDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("float",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('flowControlAllowTextToFlowDropDownList').options[1].selected = true;
		}
		else if (searchedString == "right")
		{
			document.getElementById('flowControlAllowTextToFlowDropDownList').options[2].selected = true;
		}
		else if (searchedString == "left")
		{
			document.getElementById('flowControlAllowTextToFlowDropDownList').options[3].selected = true;
		}
	}


	document.getElementById('flowControlAllowFloatingObjectsDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("clear",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('flowControlAllowFloatingObjectsDropDownList').options[1].selected = true;
		}
		else if (searchedString == "left")
		{
			document.getElementById('flowControlAllowFloatingObjectsDropDownList').options[2].selected = true;
		}
		else if (searchedString == "right")
		{
			document.getElementById('flowControlAllowFloatingObjectsDropDownList').options[3].selected = true;
		}
		else if (searchedString == "both")
		{
			document.getElementById('flowControlAllowFloatingObjectsDropDownList').options[4].selected = true;
		}
	}

///////////////////////////////////////////
//***************************************//
//*		Funktionsteil fuer Layout		*//
//***************************************//
///////////////////////////////////////////


	document.getElementById('contentOverflowDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("overflow",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "auto")
		{
			document.getElementById('contentOverflowDropDownList').options[1].selected = true;
		}
		else if (searchedString == "scroll")
		{
			document.getElementById('contentOverflowDropDownList').options[2].selected = true;
		}
		else if (searchedString == "visible")
		{
			document.getElementById('contentOverflowDropDownList').options[3].selected = true;
		}
		else if (searchedString == "hidden")
		{
			document.getElementById('contentOverflowDropDownList').options[4].selected = true;
		}
	}


	document.getElementById('clippinTopTextBox').value = '';
	document.getElementById('clippinTopUnitsDropDownList').value = '';
	document.getElementById('clippinRightTextBox').value = '';
	document.getElementById('clippinRightUnitsDropDownList').value = '';
	document.getElementById('clippinBottomTextBox').value = '';
	document.getElementById('clippinBottomUnitsDropDownList').value = '';
	document.getElementById('clippinLeftTextBox').value = '';
	document.getElementById('clippinLeftUnitsDropDownList').value = '';
	var searchedString = getValueForKeyword("clip",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		// transform rect(top, right, bottom, left) to 'top right bottom left'
		searchedString=searchedString.replace(/\,/g,"");
		searchedString=searchedString.replace(/rect\((\w+) (\w+) (\w+) (\w+)\)/gi,'$1 $2 $3 $4');
		var topValue = searchedString.substring(0,searchedString.indexOf(" "));
		searchedString = searchedString.substring(topValue.length+1);
		var rightValue = searchedString.substring(0,searchedString.indexOf(" "));
		searchedString = searchedString.substring(rightValue.length+1);
		var bottomValue = searchedString.substring(0,searchedString.indexOf(" "));
		searchedString = searchedString.substring(bottomValue.length+1);
		var leftValue = searchedString;

		if (topValue != "auto")
		{
			if (topValue.substring(topValue.length-2,topValue.length) == "px"||topValue.substring(topValue.length-2,topValue.length) == "pt"||topValue.substring(topValue.length-2,topValue.length) == "pc"||topValue.substring(topValue.length-2,topValue.length) == "mm"||topValue.substring(topValue.length-2,topValue.length) == "cm"||topValue.substring(topValue.length-2,topValue.length) == "in"||topValue.substring(topValue.length-2,topValue.length) == "em"||topValue.substring(topValue.length-2,topValue.length) == "ex")
			{
				document.getElementById('clippinTopTextBox').value = topValue.substring(0,topValue.length-2);
				document.getElementById('clippinTopUnitsDropDownList').value = topValue.substring(topValue.length-2,topValue.length);
			}
			else if (topValue.substring(topValue.length-1,topValue.length) == "%")
			{
				document.getElementById('clippinTopTextBox').value = topValue.substring(0,topValue.length-1) ;
				document.getElementById('clippinTopUnitsDropDownList').value = topValue.substring(topValue.length-1,topValue.length);
			}
		}
		if (rightValue != "auto")
		{
			if (rightValue.substring(rightValue.length-2,rightValue.length) == "px"||rightValue.substring(rightValue.length-2,rightValue.length) == "pt"||rightValue.substring(rightValue.length-2,rightValue.length) == "pc"||rightValue.substring(rightValue.length-2,rightValue.length) == "mm"||rightValue.substring(rightValue.length-2,rightValue.length) == "cm"||rightValue.substring(rightValue.length-2,rightValue.length) == "in"||rightValue.substring(rightValue.length-2,rightValue.length) == "em"||rightValue.substring(rightValue.length-2,rightValue.length) == "ex")
			{
				document.getElementById('clippinRightTextBox').value = rightValue.substring(0,rightValue.length-2);
				document.getElementById('clippinRightUnitsDropDownList').value = rightValue.substring(rightValue.length-2,rightValue.length);
			}
			else if (rightValue.substring(rightValue.length-1,rightValue.length) == "%")
			{
				document.getElementById('clippinRightTextBox').value = rightValue.substring(0,rightValue.length-1) ;
				document.getElementById('clippinRightUnitsDropDownList').value = rightValue.substring(rightValue.length-1,rightValue.length);
			}
		}
		if (bottomValue != "auto")
		{
			if (bottomValue.substring(bottomValue.length-2,bottomValue.length) == "px"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "pt"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "pc"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "mm"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "cm"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "in"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "em"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "ex")
			{
				document.getElementById('clippinBottomTextBox').value = bottomValue.substring(0,bottomValue.length-2);
				document.getElementById('clippinBottomUnitsDropDownList').value = bottomValue.substring(bottomValue.length-2,bottomValue.length);
			}
			else if (bottomValue.substring(bottomValue.length-1,bottomValue.length) == "%")
			{
				document.getElementById('clippinBottomTextBox').value = bottomValue.substring(0,bottomValue.length-1) ;
				document.getElementById('clippinBottomUnitsDropDownList').value = bottomValue.substring(bottomValue.length-1,bottomValue.length);
			}
		}
		if (leftValue != "auto")
		{
			if (leftValue.substring(leftValue.length-2,leftValue.length) == "px"||leftValue.substring(leftValue.length-2,leftValue.length) == "pt"||leftValue.substring(leftValue.length-2,leftValue.length) == "pc"||leftValue.substring(leftValue.length-2,leftValue.length) == "mm"||leftValue.substring(leftValue.length-2,leftValue.length) == "cm"||leftValue.substring(leftValue.length-2,leftValue.length) == "in"||leftValue.substring(leftValue.length-2,leftValue.length) == "em"||leftValue.substring(leftValue.length-2,leftValue.length) == "ex")
			{
				document.getElementById('clippinLeftTextBox').value = leftValue.substring(0,leftValue.length-2);
				document.getElementById('clippinLeftUnitsDropDownList').value = leftValue.substring(leftValue.length-2,leftValue.length);
			}
			else if (leftValue.substring(leftValue.length-1,leftValue.length) == "%")
			{
				document.getElementById('clippinLeftTextBox').value = leftValue.substring(0,leftValue.length-1) ;
				document.getElementById('clippinLeftUnitsDropDownList').value = leftValue.substring(leftValue.length-1,leftValue.length);
			}
		}
	}

	document.getElementById('pageBreakBefore').options[0].selected = true;
	var searchedString = getValueForKeyword("page-break-before",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "auto")
		{
			document.getElementById('pageBreakBefore').options[1].selected = true;
		}
		else if (searchedString == "always")
		{
			document.getElementById('pageBreakBefore').options[2].selected = true;
		}
	}

	document.getElementById('pageBreakAfter').options[0].selected = true;
	var searchedString = getValueForKeyword("page-break-after",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "auto")
		{
			document.getElementById('pageBreakAfter').options[1].selected = true;
		}
		else if (searchedString == "always")
		{
			document.getElementById('pageBreakAfter').options[2].selected = true;
		}
	}

	document.getElementById('tablesLayoutDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("table-layout",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "auto")
		{
			document.getElementById('tablesLayoutDropDownList').options[1].selected = true;
		}
		else if (searchedString == "fixed")
		{
			document.getElementById('tablesLayoutDropDownList').options[2].selected = true;
		}
	}




///////////////////////////////////////////
//***************************************//
//*		Funktionsteil fuer Borders		*//
//***************************************//
///////////////////////////////////////////

	document.getElementById('borderMarginTopTextBox').value = '';
	document.getElementById('borderMarginTopDropDownList').value = '';
	var searchedString = getValueForKeyword("margin-top",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderMarginTopTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderMarginTopDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderMarginTopTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderMarginTopDropDownList').value = searchedString.substring(searchedString.length-1);
		}
	}


	document.getElementById('borderMarginBottomTextBox').value = '';
	document.getElementById('borderMarginBottomDropDownList').value = '';
	var searchedString = getValueForKeyword("margin-bottom",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderMarginBottomTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderMarginBottomDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderMarginBottomTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderMarginBottomDropDownList').value = searchedString.substring(searchedString.length-1);
		}
	}


	document.getElementById('borderMarginLeftTextBox').value = '';
	document.getElementById('borderMarginLeftDropDownList').value = '';
	var searchedString = getValueForKeyword("margin-left",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderMarginLeftTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderMarginLeftDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderMarginLeftTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderMarginLeftDropDownList').value = searchedString.substring(searchedString.length-1);
		}
	}


	document.getElementById('borderMarginRightTextBox').value = '';
	document.getElementById('borderMarginRightDropDownList').value = '';
	var searchedString = getValueForKeyword("margin-right",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderMarginRightTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderMarginRightDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderMarginRightTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderMarginRightDropDownList').value = searchedString.substring(searchedString.length-1);
		}
	}



	document.getElementById('borderMarginAllTextBox').value = '';
	document.getElementById('borderMarginAllDropDownList').value = '';
	var searchedString = getValueForKeyword("margin",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.indexOf(" ") == -1)
		{
			if (searchedString.substring(searchedString.length-2) == "px"||searchedString.substring(searchedString.length-2) == "pt"||searchedString.substring(searchedString.length-2) == "pc"||searchedString.substring(searchedString.length-2) == "mm"||searchedString.substring(searchedString.length-2) == "cm"||searchedString.substring(searchedString.length-2) == "in"||searchedString.substring(searchedString.length-2) == "em"||searchedString.substring(searchedString.length-2) == "ex")
			{
				document.getElementById('borderMarginAllTextBox').value = searchedString.substring(0,searchedString.length-2);
				document.getElementById('borderMarginAllDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
			}
			else if (searchedString.substring(searchedString.length-1) == "%")
			{
				document.getElementById('borderMarginAllTextBox').value = searchedString.substring(0,searchedString.length-1);
				document.getElementById('borderMarginAllDropDownList').value = searchedString.substring(searchedString.length-1);
			}
		}
		else
		{
			var topValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(topValue.length+1);
			var rightValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(rightValue.length+1);
			var bottomValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(bottomValue.length+1);
			var leftValue = searchedString;
			if (topValue != "auto")
			{
				if (topValue.substring(topValue.length-2,topValue.length) == "px"||topValue.substring(topValue.length-2,topValue.length) == "pt"||topValue.substring(topValue.length-2,topValue.length) == "pc"||topValue.substring(topValue.length-2,topValue.length) == "mm"||topValue.substring(topValue.length-2,topValue.length) == "cm"||topValue.substring(topValue.length-2,topValue.length) == "in"||topValue.substring(topValue.length-2,topValue.length) == "em"||topValue.substring(topValue.length-2,topValue.length) == "ex")
				{
					document.getElementById('borderMarginTopTextBox').value = topValue.substring(0,topValue.length-2);
					document.getElementById('borderMarginTopDropDownList').value = topValue.substring(topValue.length-2,topValue.length);
				}
				else if (topValue.substring(topValue.length-1,topValue.length) == "%")
				{
					document.getElementById('borderMarginTopTextBox').value = topValue.substring(0,topValue.length-1) ;
					document.getElementById('borderMarginTopDropDownList').value = topValue.substring(topValue.length-1,topValue.length);
				}
			}
			if (rightValue != "auto")
			{
				if (rightValue.substring(rightValue.length-2,rightValue.length) == "px"||rightValue.substring(rightValue.length-2,rightValue.length) == "pt"||rightValue.substring(rightValue.length-2,rightValue.length) == "pc"||rightValue.substring(rightValue.length-2,rightValue.length) == "mm"||rightValue.substring(rightValue.length-2,rightValue.length) == "cm"||rightValue.substring(rightValue.length-2,rightValue.length) == "in"||rightValue.substring(rightValue.length-2,rightValue.length) == "em"||rightValue.substring(rightValue.length-2,rightValue.length) == "ex")
				{
					document.getElementById('borderMarginRightTextBox').value = rightValue.substring(0,rightValue.length-2);
					document.getElementById('borderMarginRightDropDownList').value = rightValue.substring(rightValue.length-2,rightValue.length);
				}
				else if (rightValue.substring(rightValue.length-1,rightValue.length) == "%")
				{
					document.getElementById('borderMarginRightTextBox').value = rightValue.substring(0,rightValue.length-1) ;
					document.getElementById('borderMarginRightDropDownList').value = rightValue.substring(rightValue.length-1,rightValue.length);
				}
			}
			if (bottomValue != "auto")
			{
				if (bottomValue.substring(bottomValue.length-2,bottomValue.length) == "px"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "pt"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "pc"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "mm"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "cm"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "in"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "em"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "ex")
				{
					document.getElementById('borderMarginBottomTextBox').value = bottomValue.substring(0,bottomValue.length-2);
					document.getElementById('borderMarginBottomDropDownList').value = bottomValue.substring(bottomValue.length-2,bottomValue.length);
				}
				else if (bottomValue.substring(bottomValue.length-1,bottomValue.length) == "%")
				{
					document.getElementById('borderMarginBottomTextBox').value = bottomValue.substring(0,bottomValue.length-1) ;
					document.getElementById('borderMarginBottomDropDownList').value = bottomValue.substring(bottomValue.length-1,bottomValue.length);
				}
			}
			if (leftValue != "auto")
			{
				if (leftValue.substring(leftValue.length-2,leftValue.length) == "px"||leftValue.substring(leftValue.length-2,leftValue.length) == "pt"||leftValue.substring(leftValue.length-2,leftValue.length) == "pc"||leftValue.substring(leftValue.length-2,leftValue.length) == "mm"||leftValue.substring(leftValue.length-2,leftValue.length) == "cm"||leftValue.substring(leftValue.length-2,leftValue.length) == "in"||leftValue.substring(leftValue.length-2,leftValue.length) == "em"||leftValue.substring(leftValue.length-2,leftValue.length) == "ex")
				{
					document.getElementById('borderMarginLeftTextBox').value = leftValue.substring(0,leftValue.length-2);
					document.getElementById('borderMarginLeftDropDownList').value = leftValue.substring(leftValue.length-2,leftValue.length);
				}
				else if (leftValue.substring(leftValue.length-1,leftValue.length) == "%")
				{
					document.getElementById('borderMarginLeftTextBox').value = leftValue.substring(0,leftValue.length-1) ;
					document.getElementById('borderMarginLeftDropDownList').value = leftValue.substring(leftValue.length-1,leftValue.length);
				}
			}
		}
	}



	document.getElementById('borderPaddingTopTextBox').value = '';
	document.getElementById('borderPaddingTopDropDownList').value = '';
	var searchedString = getValueForKeyword("padding-top",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderPaddingTopTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderPaddingTopDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderPaddingTopTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderPaddingTopDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}

	document.getElementById('borderPaddingBottomTextBox').value = '';
	document.getElementById('borderPaddingBottomDropDownList').value = '';
	var searchedString = getValueForKeyword("padding-bottom",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderPaddingBottomTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderPaddingBottomDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderPaddingBottomTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderPaddingBottomDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}

	document.getElementById('borderPaddingLeftTextBox').value = '';
	document.getElementById('borderPaddingLeftDropDownList').value = '';
	var searchedString = getValueForKeyword("padding-left",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderPaddingLeftTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderPaddingLeftDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderPaddingLeftTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderPaddingLeftDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}

	document.getElementById('borderPaddingRightTextBox').value = '';
	document.getElementById('borderPaddingRightDropDownList').value = '';
	var searchedString = getValueForKeyword("padding-right",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.substring(searchedString.length-2,searchedString.length) == "px"||searchedString.substring(searchedString.length-2,searchedString.length) == "pt"||searchedString.substring(searchedString.length-2,searchedString.length) == "pc"||searchedString.substring(searchedString.length-2,searchedString.length) == "mm"||searchedString.substring(searchedString.length-2,searchedString.length) == "cm"||searchedString.substring(searchedString.length-2,searchedString.length) == "in"||searchedString.substring(searchedString.length-2,searchedString.length) == "em"||searchedString.substring(searchedString.length-2,searchedString.length) == "ex")
		{
			document.getElementById('borderPaddingRightTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderPaddingRightDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
		else if (searchedString.substring(searchedString.length-1,searchedString.length) == "%")
		{
			document.getElementById('borderPaddingRightTextBox').value = searchedString.substring(0,searchedString.length-1);
			document.getElementById('borderPaddingRightDropDownList').value = searchedString.substring(searchedString.length-1,searchedString.length);
		}
	}


	document.getElementById('borderPaddingAllTextBox').value = '';
	document.getElementById('borderPaddingAllDropDownList').value = '';
	var searchedString = getValueForKeyword("padding",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.indexOf(" ") == -1)
		{
			if (searchedString.substring(searchedString.length-2) == "px"||searchedString.substring(searchedString.length-2) == "pt"||searchedString.substring(searchedString.length-2) == "pc"||searchedString.substring(searchedString.length-2) == "mm"||searchedString.substring(searchedString.length-2) == "cm"||searchedString.substring(searchedString.length-2) == "in"||searchedString.substring(searchedString.length-2) == "em"||searchedString.substring(searchedString.length-2) == "ex")
			{
				document.getElementById('borderPaddingAllTextBox').value = searchedString.substring(0,searchedString.length-2);
				document.getElementById('borderPaddingAllDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
			}
			else if (searchedString.substring(searchedString.length-1) == "%")
			{
				document.getElementById('borderPaddingAllTextBox').value = searchedString.substring(0,searchedString.length-1);
				document.getElementById('borderPaddingAllDropDownList').value = searchedString.substring(searchedString.length-1);
			}
		}
		else
		{
			var topValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(topValue.length+1);
			var rightValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(rightValue.length+1);
			var bottomValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(bottomValue.length+1);
			var leftValue = searchedString;
			if (topValue != "auto")
			{
				if (topValue.substring(topValue.length-2,topValue.length) == "px"||topValue.substring(topValue.length-2,topValue.length) == "pt"||topValue.substring(topValue.length-2,topValue.length) == "pc"||topValue.substring(topValue.length-2,topValue.length) == "mm"||topValue.substring(topValue.length-2,topValue.length) == "cm"||topValue.substring(topValue.length-2,topValue.length) == "in"||topValue.substring(topValue.length-2,topValue.length) == "em"||topValue.substring(topValue.length-2,topValue.length) == "ex")
				{
					document.getElementById('borderPaddingTopTextBox').value = topValue.substring(0,topValue.length-2);
					document.getElementById('borderPaddingTopDropDownList').value = topValue.substring(topValue.length-2,topValue.length);
				}
				else if (topValue.substring(topValue.length-1,topValue.length) == "%")
				{
					document.getElementById('borderPaddingTopTextBox').value = topValue.substring(0,topValue.length-1) ;
					document.getElementById('borderPaddingTopDropDownList').value = topValue.substring(topValue.length-1,topValue.length);
				}
			}
			if (rightValue != "auto")
			{
				if (rightValue.substring(rightValue.length-2,rightValue.length) == "px"||rightValue.substring(rightValue.length-2,rightValue.length) == "pt"||rightValue.substring(rightValue.length-2,rightValue.length) == "pc"||rightValue.substring(rightValue.length-2,rightValue.length) == "mm"||rightValue.substring(rightValue.length-2,rightValue.length) == "cm"||rightValue.substring(rightValue.length-2,rightValue.length) == "in"||rightValue.substring(rightValue.length-2,rightValue.length) == "em"||rightValue.substring(rightValue.length-2,rightValue.length) == "ex")
				{
					document.getElementById('borderPaddingRightTextBox').value = rightValue.substring(0,rightValue.length-2);
					document.getElementById('borderPaddingRightDropDownList').value = rightValue.substring(rightValue.length-2,rightValue.length);
				}
				else if (rightValue.substring(rightValue.length-1,rightValue.length) == "%")
				{
					document.getElementById('borderPaddingRightTextBox').value = rightValue.substring(0,rightValue.length-1) ;
					document.getElementById('borderPaddingRightDropDownList').value = rightValue.substring(rightValue.length-1,rightValue.length);
				}
			}
			if (bottomValue != "auto")
			{
				if (bottomValue.substring(bottomValue.length-2,bottomValue.length) == "px"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "pt"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "pc"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "mm"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "cm"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "in"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "em"||bottomValue.substring(bottomValue.length-2,bottomValue.length) == "ex")
				{
					document.getElementById('borderPaddingBottomTextBox').value = bottomValue.substring(0,bottomValue.length-2);
					document.getElementById('borderPaddingBottomDropDownList').value = bottomValue.substring(bottomValue.length-2,bottomValue.length);
				}
				else if (bottomValue.substring(bottomValue.length-1,bottomValue.length) == "%")
				{
					document.getElementById('borderPaddingBottomTextBox').value = bottomValue.substring(0,bottomValue.length-1) ;
					document.getElementById('borderPaddingBottomDropDownList').value = bottomValue.substring(bottomValue.length-1,bottomValue.length);
				}
			}
			if (leftValue != "auto")
			{
				if (leftValue.substring(leftValue.length-2,leftValue.length) == "px"||leftValue.substring(leftValue.length-2,leftValue.length) == "pt"||leftValue.substring(leftValue.length-2,leftValue.length) == "pc"||leftValue.substring(leftValue.length-2,leftValue.length) == "mm"||leftValue.substring(leftValue.length-2,leftValue.length) == "cm"||leftValue.substring(leftValue.length-2,leftValue.length) == "in"||leftValue.substring(leftValue.length-2,leftValue.length) == "em"||leftValue.substring(leftValue.length-2,leftValue.length) == "ex")
				{
					document.getElementById('borderPaddingLeftTextBox').value = leftValue.substring(0,leftValue.length-2);
					document.getElementById('borderPaddingLeftDropDownList').value = leftValue.substring(leftValue.length-2,leftValue.length);
				}
				else if (leftValue.substring(leftValue.length-1,leftValue.length) == "%")
				{
					document.getElementById('borderPaddingLeftTextBox').value = leftValue.substring(0,leftValue.length-1) ;
					document.getElementById('borderPaddingLeftDropDownList').value = leftValue.substring(leftValue.length-1,leftValue.length);
				}
			}
		}
	}


	document.getElementById('tablesBordersDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-collapse",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "separate")
		{
			document.getElementById('tablesBordersDropDownList').options[1].selected = true;
		}
		else if (searchedString == "collapse")
		{
			document.getElementById('tablesBordersDropDownList').options[2].selected = true;
		}
	}


	document.getElementById('borderColorTopTextBox').value = '';
	var searchedString = getValueForKeyword("border-top-color",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('borderColorTopTextBox').value = searchedString;
	}

	document.getElementById('borderWidthTopTextBox').value = '';
	document.getElementById('borderWidthTopDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-top-width",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "thin")
		{
			document.getElementById('borderWidthTopDropDownList').options[1].selected = true;
		}
		else if (searchedString == "medium")
		{
			//already handeld
		}
		else if (searchedString == "thick")
		{
			document.getElementById('borderWidthTopDropDownList').options[3].selected = true;
		}
		else
		{
			document.getElementById('borderWidthTopTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderWidthTopDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
	}

	document.getElementById('borderStyleTopDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-top-style",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('borderStyleTopDropDownList').options[1].selected = true;
		}
		else if (searchedString == "dotted")
		{
			document.getElementById('borderStyleTopDropDownList').options[2].selected = true;
		}
		else if (searchedString == "dashed")
		{
			document.getElementById('borderStyleTopDropDownList').options[3].selected = true;
		}
		else if (searchedString == "solid")
		{
			document.getElementById('borderStyleTopDropDownList').options[4].selected = true;
		}
		else if (searchedString == "double")
		{
			document.getElementById('borderStyleTopDropDownList').options[5].selected = true;
		}
		else if (searchedString == "groove")
		{
			document.getElementById('borderStyleTopDropDownList').options[6].selected = true;
		}
		else if (searchedString == "ridge")
		{
			document.getElementById('borderStyleTopDropDownList').options[7].selected = true;
		}
		else if (searchedString == "inset")
		{
			document.getElementById('borderStyleTopDropDownList').options[8].selected = true;
		}
		else if (searchedString == "outset")
		{
			document.getElementById('borderStyleTopDropDownList').options[9].selected = true;
		}
	}


	document.getElementById('borderColorBottomTextBox').value = '';
	var searchedString = getValueForKeyword("border-bottom-color",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('borderColorBottomTextBox').value = searchedString;
	}

	document.getElementById('borderWidthBottomTextBox').value = '';
	document.getElementById('borderWidthBottomDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-bottom-width",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "thin")
		{
			document.getElementById('borderWidthBottomDropDownList').options[1].selected = true;
		}
		else if (searchedString == "medium")
		{
			//already handeld
		}
		else if (searchedString == "thick")
		{
			document.getElementById('borderWidthBottomDropDownList').options[3].selected = true;
		}
		else
		{
			document.getElementById('borderWidthBottomTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderWidthBottomDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
	}

	document.getElementById('borderStyleBottomDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-bottom-style",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('borderStyleBottomDropDownList').options[1].selected = true;
		}
		else if (searchedString == "dotted")
		{
			document.getElementById('borderStyleBottomDropDownList').options[2].selected = true;
		}
		else if (searchedString == "dashed")
		{
			document.getElementById('borderStyleBottomDropDownList').options[3].selected = true;
		}
		else if (searchedString == "solid")
		{
			document.getElementById('borderStyleBottomDropDownList').options[4].selected = true;
		}
		else if (searchedString == "double")
		{
			document.getElementById('borderStyleBottomDropDownList').options[5].selected = true;
		}
		else if (searchedString == "groove")
		{
			document.getElementById('borderStyleBottomDropDownList').options[6].selected = true;
		}
		else if (searchedString == "ridge")
		{
			document.getElementById('borderStyleBottomDropDownList').options[7].selected = true;
		}
		else if (searchedString == "inset")
		{
			document.getElementById('borderStyleBottomDropDownList').options[8].selected = true;
		}
		else if (searchedString == "outset")
		{
			document.getElementById('borderStyleBottomDropDownList').options[9].selected = true;
		}
	}


	document.getElementById('borderColorLeftTextBox').value = '';
	var searchedString = getValueForKeyword("border-left-color",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('borderColorLeftTextBox').value = searchedString;
	}

	document.getElementById('borderWidthLeftTextBox').value = '';
	document.getElementById('borderWidthLeftDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-left-width",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "thin")
		{
			document.getElementById('borderWidthLeftDropDownList').options[1].selected = true;
		}
		else if (searchedString == "medium")
		{
			//already handeld
		}
		else if (searchedString == "thick")
		{
			document.getElementById('borderWidthLeftDropDownList').options[3].selected = true;
		}
		else
		{
			document.getElementById('borderWidthLeftTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderWidthLeftDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
	}

	document.getElementById('borderStyleLeftDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-left-style",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('borderStyleLeftDropDownList').options[1].selected = true;
		}
		else if (searchedString == "dotted")
		{
			document.getElementById('borderStyleLeftDropDownList').options[2].selected = true;
		}
		else if (searchedString == "dashed")
		{
			document.getElementById('borderStyleLeftDropDownList').options[3].selected = true;
		}
		else if (searchedString == "solid")
		{
			document.getElementById('borderStyleLeftDropDownList').options[4].selected = true;
		}
		else if (searchedString == "double")
		{
			document.getElementById('borderStyleLeftDropDownList').options[5].selected = true;
		}
		else if (searchedString == "groove")
		{
			document.getElementById('borderStyleLeftDropDownList').options[6].selected = true;
		}
		else if (searchedString == "ridge")
		{
			document.getElementById('borderStyleLeftDropDownList').options[7].selected = true;
		}
		else if (searchedString == "inset")
		{
			document.getElementById('borderStyleLeftDropDownList').options[8].selected = true;
		}
		else if (searchedString == "outset")
		{
			document.getElementById('borderStyleLeftDropDownList').options[9].selected = true;
		}
	}


	document.getElementById('borderColorRightTextBox').value = '';
	var searchedString = getValueForKeyword("border-right-color",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		document.getElementById('borderColorRightTextBox').value = searchedString;
	}

	document.getElementById('borderWidthRightTextBox').value = '';
	document.getElementById('borderWidthRightDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-right-width",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "thin")
		{
			document.getElementById('borderWidthRightDropDownList').options[1].selected = true;
		}
		else if (searchedString == "medium")
		{
			//already handeld
		}
		else if (searchedString == "thick")
		{
			document.getElementById('borderWidthRightDropDownList').options[3].selected = true;
		}
		else
		{
			document.getElementById('borderWidthRightTextBox').value = searchedString.substring(0,searchedString.length-2);
			document.getElementById('borderWidthRightDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
		}
	}

	document.getElementById('borderStyleRightDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-right-style",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('borderStyleRightDropDownList').options[1].selected = true;
		}
		else if (searchedString == "dotted")
		{
			document.getElementById('borderStyleRightDropDownList').options[2].selected = true;
		}
		else if (searchedString == "dashed")
		{
			document.getElementById('borderStyleRightDropDownList').options[3].selected = true;
		}
		else if (searchedString == "solid")
		{
			document.getElementById('borderStyleRightDropDownList').options[4].selected = true;
		}
		else if (searchedString == "double")
		{
			document.getElementById('borderStyleRightDropDownList').options[5].selected = true;
		}
		else if (searchedString == "groove")
		{
			document.getElementById('borderStyleRightDropDownList').options[6].selected = true;
		}
		else if (searchedString == "ridge")
		{
			document.getElementById('borderStyleRightDropDownList').options[7].selected = true;
		}
		else if (searchedString == "inset")
		{
			document.getElementById('borderStyleRightDropDownList').options[8].selected = true;
		}
		else if (searchedString == "outset")
		{
			document.getElementById('borderStyleRightDropDownList').options[9].selected = true;
		}
	}




	var searchedString = getValueForKeyword("border-right",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		var firstBlankINsearchedString = searchedString.indexOf(" ");
		var StringAfterFirstBlank = searchedString.substring(firstBlankINsearchedString+1,searchedString.length);
		var secondBlankINsearchedString = StringAfterFirstBlank.indexOf(" ");
		if(secondBlankINsearchedString != -1)
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = StringAfterFirstBlank.substring(0,secondBlankINsearchedString);
			var colorString = StringAfterFirstBlank.substring(secondBlankINsearchedString+1,StringAfterFirstBlank.length);

			document.getElementById('borderColorRightTextBox').value = colorString;

			if (widthString == "thin")
			{
				document.getElementById('borderWidthRightDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthRightDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthRightTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthRightDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleRightDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleRightDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleRightDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleRightDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleRightDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleRightDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleRightDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleRightDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleRightDropDownList').options[9].selected = true;
			}
		}
		else
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = searchedString.substring(firstBlankINsearchedString+1,searchedString.lenght);
			if (widthString == "thin")
			{
				document.getElementById('borderWidthRightDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthRightDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthRightTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthRightDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleRightDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleRightDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleRightDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleRightDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleRightDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleRightDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleRightDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleRightDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleRightDropDownList').options[9].selected = true;
			}
		}
	}




	var searchedString = getValueForKeyword("border-left",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		var firstBlankINsearchedString = searchedString.indexOf(" ");
		var StringAfterFirstBlank = searchedString.substring(firstBlankINsearchedString+1,searchedString.length);
		var secondBlankINsearchedString = StringAfterFirstBlank.indexOf(" ");
		if(secondBlankINsearchedString != -1)
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = StringAfterFirstBlank.substring(0,secondBlankINsearchedString);
			var colorString = StringAfterFirstBlank.substring(secondBlankINsearchedString+1,StringAfterFirstBlank.length);

			document.getElementById('borderColorLeftTextBox').value = colorString;

			if (widthString == "thin")
			{
				document.getElementById('borderWidthLeftDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthLeftDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthLeftTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthLeftDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleLeftDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleLeftDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleLeftDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleLeftDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleLeftDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleLeftDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleLeftDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleLeftDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleLeftDropDownList').options[9].selected = true;
			}
		}
		else
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = searchedString.substring(firstBlankINsearchedString+1,searchedString.lenght);
			if (widthString == "thin")
			{
				document.getElementById('borderWidthLeftDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthLeftDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthLeftTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthLeftDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleLeftDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleLeftDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleLeftDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleLeftDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleLeftDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleLeftDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleLeftDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleLeftDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleLeftDropDownList').options[9].selected = true;
			}
		}
	}



	var searchedString = getValueForKeyword("border-bottom",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		var firstBlankINsearchedString = searchedString.indexOf(" ");
		var StringAfterFirstBlank = searchedString.substring(firstBlankINsearchedString+1,searchedString.length);
		var secondBlankINsearchedString = StringAfterFirstBlank.indexOf(" ");
		if(secondBlankINsearchedString != -1)
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = StringAfterFirstBlank.substring(0,secondBlankINsearchedString);
			var colorString = StringAfterFirstBlank.substring(secondBlankINsearchedString+1,StringAfterFirstBlank.length);

			document.getElementById('borderColorBottomTextBox').value = colorString;

			if (widthString == "thin")
			{
				document.getElementById('borderWidthBottomDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthBottomDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthBottomTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthBottomDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleBottomDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleBottomDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleBottomDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleBottomDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleBottomDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleBottomDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleBottomDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleBottomDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleBottomDropDownList').options[9].selected = true;
			}
		}
		else
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = searchedString.substring(firstBlankINsearchedString+1,searchedString.lenght);
			if (widthString == "thin")
			{
				document.getElementById('borderWidthBottomDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthBottomDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthBottomTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthBottomDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleBottomDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleBottomDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleBottomDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleBottomDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleBottomDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleBottomDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleBottomDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleBottomDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleBottomDropDownList').options[9].selected = true;
			}
		}
	}



	var searchedString = getValueForKeyword("border-top",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		var firstBlankINsearchedString = searchedString.indexOf(" ");
		var StringAfterFirstBlank = searchedString.substring(firstBlankINsearchedString+1,searchedString.length);
		var secondBlankINsearchedString = StringAfterFirstBlank.indexOf(" ");
		if(secondBlankINsearchedString != -1)
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = StringAfterFirstBlank.substring(0,secondBlankINsearchedString);
			var colorString = StringAfterFirstBlank.substring(secondBlankINsearchedString+1,StringAfterFirstBlank.length);

			document.getElementById('borderColorTopTextBox').value = colorString;

			if (widthString == "thin")
			{
				document.getElementById('borderWidthTopDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthTopDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthTopTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthTopDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleTopDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleTopDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleTopDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleTopDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleTopDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleTopDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleTopDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleTopDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleTopDropDownList').options[9].selected = true;
			}
		}
		else
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = searchedString.substring(firstBlankINsearchedString+1,searchedString.lenght);
			if (widthString == "thin")
			{
				document.getElementById('borderWidthTopDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthTopDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthTopTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthTopDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleTopDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleTopDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleTopDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleTopDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleTopDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleTopDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleTopDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleTopDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleTopDropDownList').options[9].selected = true;
			}
		}
	}


	document.getElementById('borderStyleAllDropDownList').options[0].selected = true;
	var searchedString = getValueForKeyword("border-style",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.indexOf( " " ) != -1)
		{
			var topValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(topValue.length+1);
			var bottomValue=topValue;
			var rightValue;
			var leftValue;

			if(searchedString.indexOf( " " ) != -1)
			{
				rightValue = searchedString.substring(0,searchedString.indexOf(" "));
				searchedString = searchedString.substring(rightValue.length+1);

				leftValue=rightValue;

				if(searchedString.indexOf( " " ) != -1)
				{
					bottomValue = searchedString.substring(0,searchedString.indexOf(" "));
					searchedString = searchedString.substring(bottomValue.length+1);
					leftValue = searchedString;
				}
				else
				{
					bottomValue = searchedString;
				}
			}
			else
			{
				rightValue = searchedString;
				leftValue = searchedString;
			}

			switch(topValue)
			{
				case "none": document.getElementById('borderStyleTopDropDownList').options[1].selected = true; break;
				case "dotted": document.getElementById('borderStyleTopDropDownList').options[2].selected = true; break;
				case "dashed": document.getElementById('borderStyleTopDropDownList').options[3].selected = true; break;
				case "solid": document.getElementById('borderStyleTopDropDownList').options[4].selected = true; break;
				case "double": document.getElementById('borderStyleTopDropDownList').options[5].selected = true; break;
				case "groove": document.getElementById('borderStyleTopDropDownList').options[6].selected = true; break;
				case "ridge": document.getElementById('borderStyleTopDropDownList').options[7].selected = true; break;
				case "inset": document.getElementById('borderStyleTopDropDownList').options[8].selected = true; break;
				case "outset": document.getElementById('borderStyleTopDropDownList').options[9].selected = true; break;
			}
			switch(rightValue)
			{
				case "none": document.getElementById('borderStyleRightDropDownList').options[1].selected = true; break;
				case "dotted": document.getElementById('borderStyleRightDropDownList').options[2].selected = true; break;
				case "dashed": document.getElementById('borderStyleRightDropDownList').options[3].selected = true; break;
				case "solid": document.getElementById('borderStyleRightDropDownList').options[4].selected = true; break;
				case "double": document.getElementById('borderStyleRightDropDownList').options[5].selected = true; break;
				case "groove": document.getElementById('borderStyleRightDropDownList').options[6].selected = true; break;
				case "ridge": document.getElementById('borderStyleRightDropDownList').options[7].selected = true; break;
				case "inset": document.getElementById('borderStyleRightDropDownList').options[8].selected = true; break;
				case "outset": document.getElementById('borderStyleRightDropDownList').options[9].selected = true; break;
			}
			switch(bottomValue)
			{
				case "none": document.getElementById('borderStyleBottomDropDownList').options[1].selected = true; break;
				case "dotted": document.getElementById('borderStyleBottomDropDownList').options[2].selected = true; break;
				case "dashed": document.getElementById('borderStyleBottomDropDownList').options[3].selected = true; break;
				case "solid": document.getElementById('borderStyleBottomDropDownList').options[4].selected = true; break;
				case "double": document.getElementById('borderStyleBottomDropDownList').options[5].selected = true; break;
				case "groove": document.getElementById('borderStyleBottomDropDownList').options[6].selected = true; break;
				case "ridge": document.getElementById('borderStyleBottomDropDownList').options[7].selected = true; break;
				case "inset": document.getElementById('borderStyleBottomDropDownList').options[8].selected = true; break;
				case "outset": document.getElementById('borderStyleBottomDropDownList').options[9].selected = true; break;
			}
			switch(leftValue)
			{
				case "none": document.getElementById('borderStyleLeftDropDownList').options[1].selected = true; break;
				case "dotted": document.getElementById('borderStyleLeftDropDownList').options[2].selected = true; break;
				case "dashed": document.getElementById('borderStyleLeftDropDownList').options[3].selected = true; break;
				case "solid": document.getElementById('borderStyleLeftDropDownList').options[4].selected = true; break;
				case "double": document.getElementById('borderStyleLeftDropDownList').options[5].selected = true; break;
				case "groove": document.getElementById('borderStyleLeftDropDownList').options[6].selected = true; break;
				case "ridge": document.getElementById('borderStyleLeftDropDownList').options[7].selected = true; break;
				case "inset": document.getElementById('borderStyleLeftDropDownList').options[8].selected = true; break;
				case "outset": document.getElementById('borderStyleLeftDropDownList').options[9].selected = true; break;
			}
		}
		else
		{
			if (searchedString == "none")
			{
				document.getElementById('borderStyleAllDropDownList').options[1].selected = true;
			}
			else if (searchedString == "dotted")
			{
				document.getElementById('borderStyleAllDropDownList').options[2].selected = true;
			}
			else if (searchedString == "dashed")
			{
				document.getElementById('borderStyleAllDropDownList').options[3].selected = true;
			}
			else if (searchedString == "solid")
			{
				document.getElementById('borderStyleAllDropDownList').options[4].selected = true;
			}
			else if (searchedString == "double")
			{
				document.getElementById('borderStyleAllDropDownList').options[5].selected = true;
			}
			else if (searchedString == "groove")
			{
				document.getElementById('borderStyleAllDropDownList').options[6].selected = true;
			}
			else if (searchedString == "ridge")
			{
				document.getElementById('borderStyleAllDropDownList').options[7].selected = true;
			}
			else if (searchedString == "inset")
			{
				document.getElementById('borderStyleAllDropDownList').options[8].selected = true;
			}
			else if (searchedString == "outset")
			{
				document.getElementById('borderStyleAllDropDownList').options[9].selected = true;
			}
		}
	}


	document.getElementById('borderColorAllTextBox').value = '';
	var searchedString = getValueForKeyword("border-color",NewCodeBoxValue);
	if (searchedString != undefined)
	{
			//FIXME: checking for spaces does not work if colors are specified like so:
			//		rgb(255, 255, 255) rgb(255, 255, 255) ...

		if (searchedString.indexOf( " " ) != -1)
		{
			var topValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(topValue.length+1);
			var bottomValue=topValue;
			var rightValue;
			var leftValue;

			if(searchedString.indexOf( " " ) != -1)
			{
				rightValue = searchedString.substring(0,searchedString.indexOf(" "));
				searchedString = searchedString.substring(rightValue.length+1);

				leftValue=rightValue;

				if(searchedString.indexOf( " " ) != -1)
				{
					bottomValue = searchedString.substring(0,searchedString.indexOf(" "));
					searchedString = searchedString.substring(bottomValue.length+1);
					leftValue = searchedString;
				}
				else
				{
					bottomValue = searchedString;
				}
			}
			else
			{
				rightValue = searchedString;
				leftValue = searchedString;
			}

			document.getElementById('borderColorTopTextBox').value = topValue;
			document.getElementById('borderColorRightTextBox').value = rightValue;
			document.getElementById('borderColorBottomTextBox').value = bottomValue;
			document.getElementById('borderColorLeftTextBox').value = leftValue;
		}
		else
		{
			document.getElementById('borderColorAllTextBox').value = searchedString;
		}
	}

	document.getElementById('borderWidthAllTextBox').value = '';
	document.getElementById('borderWidthAllDropDownList').value = '';
	var searchedString = getValueForKeyword("border-width",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString.indexOf( " " ) != -1)
		{
			var topValue = searchedString.substring(0,searchedString.indexOf(" "));
			searchedString = searchedString.substring(topValue.length+1);
			var bottomValue=topValue;
			var rightValue;
			var leftValue;

			if(searchedString.indexOf( " " ) != -1)
			{
				rightValue = searchedString.substring(0,searchedString.indexOf(" "));
				searchedString = searchedString.substring(rightValue.length+1);

				leftValue=rightValue;

				if(searchedString.indexOf( " " ) != -1)
				{
					bottomValue = searchedString.substring(0,searchedString.indexOf(" "));
					searchedString = searchedString.substring(bottomValue.length+1);
					leftValue = searchedString;
				}
				else
				{
					bottomValue = searchedString;
				}
			}
			else
			{
				rightValue = searchedString;
				leftValue = searchedString;
			}

			switch(topValue)
			{
				case "thin": document.getElementById('borderWidthTopDropDownList').options[1].selected = true; break;
				case "medium": break; //already handeld
				case "thick": document.getElementById('borderWidthTopDropDownList').options[3].selected = true; break;
				default:
					document.getElementById('borderWidthTopTextBox').value = topValue.substring(0,topValue.length-2);
					document.getElementById('borderWidthTopDropDownList').value = topValue.substring(topValue.length-2,topValue.length);
			}
			switch(rightValue)
			{
				case "thin": document.getElementById('borderWidthRightDropDownList').options[1].selected = true; break;
				case "medium": break; //already handeld
				case "thick": document.getElementById('borderWidthRightDropDownList').options[3].selected = true; break;
				default:
					document.getElementById('borderWidthRightTextBox').value = rightValue.substring(0,rightValue.length-2);
					document.getElementById('borderWidthRightDropDownList').value = rightValue.substring(rightValue.length-2,rightValue.length);
			}
			switch(bottomValue)
			{
				case "thin": document.getElementById('borderWidthBottomDropDownList').options[1].selected = true; break;
				case "medium": break; //already handeld
				case "thick": document.getElementById('borderWidthBottomDropDownList').options[3].selected = true; break;
				default:
					document.getElementById('borderWidthBottomTextBox').value = bottomValue.substring(0,bottomValue.length-2);
					document.getElementById('borderWidthBottomDropDownList').value = bottomValue.substring(bottomValue.length-2,bottomValue.length);
			}
			switch(leftValue)
			{
				case "thin": document.getElementById('borderWidthLeftDropDownList').options[1].selected = true; break;
				case "medium": break; //already handeld
				case "thick": document.getElementById('borderWidthLeftDropDownList').options[3].selected = true; break;
				default:
					document.getElementById('borderWidthLeftTextBox').value = leftValue.substring(0,leftValue.length-2);
					document.getElementById('borderWidthLeftDropDownList').value = leftValue.substring(leftValue.length-2,leftValue.length);
			}

		}
		else
		{
			if (searchedString == "thin")
			{
				document.getElementById('borderWidthAllDropDownList').options[1].selected = true;
			}
			else if (searchedString == "medium")
			{
				//already handeld
			}
			else if (searchedString == "thick")
			{
				document.getElementById('borderWidthAllDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthAllTextBox').value = searchedString.substring(0,searchedString.length-2);
				document.getElementById('borderWidthAllDropDownList').value = searchedString.substring(searchedString.length-2,searchedString.length);
			}
		}
	}

	var searchedString = getValueForKeyword("border",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		var firstBlankINsearchedString = searchedString.indexOf(" ");
		var StringAfterFirstBlank = searchedString.substring(firstBlankINsearchedString+1,searchedString.length);
		var secondBlankINsearchedString = StringAfterFirstBlank.indexOf(" ");
		if(secondBlankINsearchedString != -1)
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = StringAfterFirstBlank.substring(0,secondBlankINsearchedString);
			var colorString = StringAfterFirstBlank.substring(secondBlankINsearchedString+1,StringAfterFirstBlank.length);

			document.getElementById('borderColorAllTextBox').value = colorString;

			if (widthString == "thin")
			{
				document.getElementById('borderWidthAllDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthAllDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthAllTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthAllDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleAllDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleAllDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleAllDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleAllDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleAllDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleAllDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleAllDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleAllDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleAllDropDownList').options[9].selected = true;
			}
		}
		else
		{
			var widthString = searchedString.substring(0,firstBlankINsearchedString);
			var styleString = searchedString.substring(firstBlankINsearchedString+1,searchedString.lenght);
			if (widthString == "thin")
			{
				document.getElementById('borderWidthAllDropDownList').options[1].selected = true;
			}
			else if (widthString == "medium")
			{
				//already handeld
			}
			else if (widthString == "thick")
			{
				document.getElementById('borderWidthAllDropDownList').options[3].selected = true;
			}
			else
			{
				document.getElementById('borderWidthAllTextBox').value = widthString.substring(0,widthString.length-2);
				document.getElementById('borderWidthAllDropDownList').value = widthString.substring(widthString.length-2,widthString.length);
			}

			if (styleString == "none")
			{
				document.getElementById('borderStyleAllDropDownList').options[1].selected = true;
			}
			else if (styleString == "dotted")
			{
				document.getElementById('borderStyleAllDropDownList').options[2].selected = true;
			}
			else if (styleString == "dashed")
			{
				document.getElementById('borderStyleAllDropDownList').options[3].selected = true;
			}
			else if (styleString == "solid")
			{
				document.getElementById('borderStyleAllDropDownList').options[4].selected = true;
			}
			else if (styleString == "double")
			{
				document.getElementById('borderStyleAllDropDownList').options[5].selected = true;
			}
			else if (styleString == "groove")
			{
				document.getElementById('borderStyleAllDropDownList').options[6].selected = true;
			}
			else if (styleString == "ridge")
			{
				document.getElementById('borderStyleAllDropDownList').options[7].selected = true;
			}
			else if (styleString == "inset")
			{
				document.getElementById('borderStyleAllDropDownList').options[8].selected = true;
			}
			else if (styleString == "outset")
			{
				document.getElementById('borderStyleAllDropDownList').options[9].selected = true;
			}
		}
	}


///////////////////////////////////////
//***********************************//
//*		Funktionsteil fuer Lists		*//
//***********************************//
///////////////////////////////////////


	document.getElementById('ListsDropDownList').options[0].selected = true;
	document.getElementById('bulletStyleDropDownList').options[0].selected = true;
	document.getElementById('bulletPositionDropDownList').options[0].selected = true;
	document.getElementById('customBulletRadioButton_1').checked = true;
	document.getElementById('customBulletRadioButton_2').checked = false;
	document.getElementById('customBulletImageTextBox').value = '';
	var searchedString = getValueForKeyword("list-style-type",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('ListsDropDownList').options[2].selected = true;
		}
		else if (searchedString == "circle")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[1].selected = true;
		}
		else if (searchedString == "disc")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[2].selected = true;
		}
		else if (searchedString == "square")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[3].selected = true;
		}
		else if (searchedString == "decimal")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[4].selected = true;
		}
		else if (searchedString == "lower-roman")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[5].selected = true;
		}
		else if (searchedString == "upper-roman")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[6].selected = true;
		}
		else if (searchedString == "lower-alpha")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[7].selected = true;
		}
		else if (searchedString == "upper-alpha")
		{
			document.getElementById('ListsDropDownList').options[1].selected = true;
			document.getElementById('bulletStyleDropDownList').options[8].selected = true;
		}
	}



	var searchedString = getValueForKeyword("list-style-position",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "outside")
		{
			document.getElementById('bulletPositionDropDownList').options[1].selected = true;
		}
		else if (searchedString == "inside")
		{
			document.getElementById('bulletPositionDropDownList').options[2].selected = true;
		}
	}



	var searchedString = getValueForKeyword("list-style-image",NewCodeBoxValue);
	if (searchedString != undefined)
	{
		if (searchedString == "none")
		{
			document.getElementById('customBulletRadioButton_1').checked = false;
			document.getElementById('customBulletRadioButton_2').checked = true;
		}
		else
		{
			document.getElementById('customBulletRadioButton_1').checked = true;
			document.getElementById('customBulletRadioButton_2').checked = false;
			document.getElementById('customBulletImageTextBox').value = searchedString.substring(4,searchedString.length-1);
		}
	}



setFontStyle();
setBGStyle();
setTextStyle();
setPositionStyle();
setLayoutStyle();
setEdgesStyle()
setListsStyle()
}



function setJustify()
{
	if (document.getElementById('justificationHorAlignmentDropDownList').value != "")
	{
		document.getElementById('horizontalAlignmentDropDownList').value = "justify";
	}
}


/*
function visibleTables()
{
	document.getElementById('BGTable').style.visibility = "";
	document.getElementById('TextTable').style.visibility = "";
	document.getElementById('PositionTable').style.visibility = "";
	document.getElementById('LayoutTable').style.visibility = "";
	document.getElementById('EdgesTable').style.visibility = "";
	document.getElementById('ListsTable').style.visibility = "";
	document.getElementById('FilterTable').style.visibility = "";
	document.getElementById('SampleImagetable').style.visibility = "";
	document.getElementById('LettersTable').style.visibility = "";
	document.getElementById('LinesTable').style.visibility = "";
	document.getElementById('BGImagePositionVerTable').style.visibility = "";
	document.getElementById('BGImagePositionHorTable').style.visibility = "";
	document.getElementById('FontSizeTable').style.visibility = "";
	document.getElementById('BoldTable').style.visibility = "";
	document.getElementById('webPaletteTable').style.visibility = "";
	document.getElementById('namedColorsTable').style.visibility = "";
}
*/

/*
function setFocusOnLoad()
{
	document.getElementById('usedFonts').focus();
}
*/

function SetSameStyleInHiddenDiv()
{
	if (document.getElementById('Hidden').style.cssText != "")
	{
		if (document.getElementById('flowControlDisplayDropDownList').value == "none")
		{
			document.getElementById('NotInPreviewInfo').style.display = "";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + "; display: none" + ";\n}";
		}
		else if (document.getElementById('flowControlDisplayDropDownList').value == "block")
		{
			document.getElementById('NotInPreviewInfo').style.display = "";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + "; display: block" + ";\n}";
		}
		else if (document.getElementById('flowControlDisplayDropDownList').value == "inline")
		{
			document.getElementById('NotInPreviewInfo').style.display = "";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + "; display: inline" + ";\n}";
		}
		else
		{
			document.getElementById('NotInPreviewInfo').style.display = "none";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + ";\n}";
		}
	}
	else
	{
		if (document.getElementById('flowControlDisplayDropDownList').value == "none")
		{
			document.getElementById('NotInPreviewInfo').style.display = "";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + "display: none" + ";\n}";
		}
		else if (document.getElementById('flowControlDisplayDropDownList').value == "block")
		{
			document.getElementById('NotInPreviewInfo').style.display = "";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + "display: block" + ";\n}";
		}
		else if (document.getElementById('flowControlDisplayDropDownList').value == "inline")
		{
			document.getElementById('NotInPreviewInfo').style.display = "";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + "display: inline" + ";\n}";
		}
		else
		{
			document.getElementById('NotInPreviewInfo').style.display = "none";
			document.getElementById('CodeTextarea').value = "x\n{\n" + document.getElementById('Hidden').style.cssText.toLowerCase() + ";\n}";
		}
	}

	if (document.getElementById('usedFonts').value == "")
	{
			var CSSText = document.getElementById('CodeTextarea').value;
			CSSText = CSSText.replace(/font-family: ; /, "");
			CSSText = CSSText.replace(/font-family: ;/, "");
			CSSText = CSSText.replace(/font-family: /, "");
			document.getElementById('CodeTextarea').value = CSSText;
	}

	//alert("Saved:\n" + document.getElementById('CodeTextarea').value);
	//SaveCookie("CodeTextarea", escape(document.getElementById('CodeTextarea').value), 7);

/*	if (document.getElementById('FilterTextBox').value == "")
	{
			var CSSText = document.getElementById('CodeTextarea').value;
			CSSText = CSSText.replace(/filter: ; /, "");
			CSSText = CSSText.replace(/filter: /, "");
			document.getElementById('CodeTextarea').value = CSSText;
	}
*/
}


function changeSampleText()
{
	if (document.getElementById('ListsTable').style.display != "none")
	{
		document.getElementById('SampleTextarea').innerHTML = "<ul id='SampleList'><li>Lorem ipsum dolor sit amet, <br>consectetuer adipiscing elit.</li><li>Cras sodales aliquam mi.</li><li>Curabitur consequat libero id orci, <br>Vestibulum sed augue in sapien.</li></ul>";
		setListsStyle();
	}
	else
	{
		document.getElementById('SampleTextarea').innerHTML = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed orci dolor, mattis ac, molestie a, pulvinar et, mi. Nullam nibh sem, sollicitudin at, congue id, malesuada non, lacus.";
	}
}



var CurrentButtonID = "FontButton";
function buttonClick(id)
{
	CurrentButtonID = id;

	// buttonOut
	document.getElementById("FontButton").className = "buttonNormal";
	document.getElementById("BackgroundButton").className = "buttonNormal";
	document.getElementById("TextButton").className = "buttonNormal";
	document.getElementById("PositionButton").className = "buttonNormal";
	document.getElementById("LayoutButton").className = "buttonNormal";
	document.getElementById("EdgesButton").className = "buttonNormal";
	document.getElementById("ListsButton").className = "buttonNormal";

	document.getElementById(id).className = "buttonPressed";

	hideSelector();
	showRightTable(id);
	changeSampleText();
}


function buttonOver(id)
{
	var button = document.getElementById(id);
	if (id == CurrentButtonID)
	{
		button.className = "buttonPressedHover";
	}
	else
	{
		button.className = "buttonHover";
	}
}


function buttonOut(id)
{
	var button = document.getElementById(id);
	if (id == CurrentButtonID)
	{
		button.className = "buttonPressed";
	}
	else
	{
		button.className = "buttonNormal";
	}
}


/*
function hideTablesAtStart()
{
	document.getElementById("FontTable").style.display = "";
	document.getElementById("BGTable").style.display = "none";
	document.getElementById("TextTable").style.display = "none";
	document.getElementById("PositionTable").style.display = "none";
	document.getElementById("LayoutTable").style.display = "none";
	document.getElementById("EdgesTable").style.display = "none";
	document.getElementById("ListsTable").style.display = "none";
}
*/

function showRightTable(InPutName)
{
	if (InPutName == "FontButton")
	{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "";
		document.getElementById("FontTable").style.display = "";
		document.getElementById("BGTable").style.display = "none";
		document.getElementById("TextTable").style.display = "none";
		document.getElementById("PositionTable").style.display = "none";
		document.getElementById("LayoutTable").style.display = "none";
		document.getElementById("EdgesTable").style.display = "none";
		document.getElementById("ListsTable").style.display = "none";
			document.getElementById('usedFonts').focus();
	}
	else if (InPutName == "BackgroundButton")
	{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "";
		document.getElementById("FontTable").style.display = "none";
		document.getElementById("BGTable").style.display = "";
		document.getElementById("TextTable").style.display = "none";
		document.getElementById("PositionTable").style.display = "none";
		document.getElementById("LayoutTable").style.display = "none";
		document.getElementById("EdgesTable").style.display = "none";
		document.getElementById("ListsTable").style.display = "none";
			document.getElementById('bgColorTextBox').focus();
	}
	else if (InPutName == "TextButton")
	{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "";
		document.getElementById("BGTable").style.display = "none";
		document.getElementById("FontTable").style.display = "none";
		document.getElementById("TextTable").style.display = "";
		document.getElementById("PositionTable").style.display = "none";
		document.getElementById("LayoutTable").style.display = "none";
		document.getElementById("EdgesTable").style.display = "none";
		document.getElementById("ListsTable").style.display = "none";
			document.getElementById('horizontalAlignmentDropDownList').focus();
	}
	else if (InPutName == "PositionButton")
	{
		document.getElementById("BGTable").style.display = "none";
		document.getElementById("FontTable").style.display = "none";
		document.getElementById("TextTable").style.display = "none";
		document.getElementById("PositionTable").style.display = "";
		document.getElementById("LayoutTable").style.display = "none";
		document.getElementById("EdgesTable").style.display = "none";
		document.getElementById("ListsTable").style.display = "none";
			document.getElementById('positionModeDropDownList').focus();
	}
	else if (InPutName == "LayoutButton")
	{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "";
		document.getElementById("BGTable").style.display = "none";
		document.getElementById("FontTable").style.display = "none";
		document.getElementById("TextTable").style.display = "none";
		document.getElementById("PositionTable").style.display = "none";
		document.getElementById("LayoutTable").style.display = "";
		document.getElementById("EdgesTable").style.display = "none";
		document.getElementById("ListsTable").style.display = "none";
			document.getElementById('contentOverflowDropDownList').focus();
	}
	else if (InPutName == "EdgesButton")
	{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "";
		document.getElementById("BGTable").style.display = "none";
		document.getElementById("FontTable").style.display = "none";
		document.getElementById("TextTable").style.display = "none";
		document.getElementById("PositionTable").style.display = "none";
		document.getElementById("LayoutTable").style.display = "none";
		document.getElementById("EdgesTable").style.display = "";
		document.getElementById("ListsTable").style.display = "none";
			document.getElementById('borderMarginAllTextBox').focus();
	}
	else if (InPutName == "ListsButton")
	{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "";
		document.getElementById("BGTable").style.display = "none";
		document.getElementById("FontTable").style.display = "none";
		document.getElementById("TextTable").style.display = "none";
		document.getElementById("PositionTable").style.display = "none";
		document.getElementById("LayoutTable").style.display = "none";
		document.getElementById("EdgesTable").style.display = "none";
		document.getElementById("ListsTable").style.display = "";
			document.getElementById('ListsDropDownList').focus();
	}
}







function manualFontInput()
{
	document.getElementById('SampleTextarea').style.fontFamily = document.getElementById('usedFonts').value;
	document.getElementById('Hidden').style.fontFamily = document.getElementById('usedFonts').value;
setFontStyle();
}


/*
function hideDisplaysAtStart()
{
	document.getElementById('FontsAuswahl').style.display = "none";
	document.getElementById('namedColorsTable').style.display = "none";
	document.getElementById('webPaletteTable').style.display = "none";
	document.getElementById('BoldTable').style.display = "none";
	document.getElementById('FontSizeTable').style.display = "none";
	document.getElementById('BGImagePositionHorTable').style.display = "none";
	document.getElementById('BGImagePositionVerTable').style.display = "none";
	document.getElementById('LettersTable').style.display = "none";
	document.getElementById('LinesTable').style.display = "none";
	document.getElementById('SampleImageAbsolute').style.display = "none";
	document.getElementById('SampleImageRelativ').style.display = "none";
	document.getElementById('SampleImageNormal').style.display = "none";
	document.getElementById('NotInPreviewInfo').style.display = "none";
	document.getElementById('SampleImagetable').style.display = "none";
	document.getElementById('SampleImageMargin').style.display = "none";
	document.getElementById('SampleImagePadding').style.display = "none";
	document.getElementById('FilterTable').style.display = "none";
}
*/

function HideSelectors()
{
	document.getElementById('FontsAuswahl').style.display = "none";
	document.getElementById('namedColorsDiv').style.display = "none";
	document.getElementById('webPaletteDiv').style.display = "none";
	document.getElementById('BoldTable').style.display = "none";
	document.getElementById('FontSizeTable').style.display = "none";
	document.getElementById('BGImagePositionHorTable').style.display = "none";
	document.getElementById('BGImagePositionVerTable').style.display = "none";
	document.getElementById('LettersTable').style.display = "none";
	document.getElementById('LinesTable').style.display = "none";
	document.getElementById('SampleImageAbsolute').style.display = "none";
	document.getElementById('SampleImageRelativ').style.display = "none";
	document.getElementById('SampleImageNormal').style.display = "none";
	document.getElementById('NotInPreviewInfo').style.display = "none";
	document.getElementById('SampleImagetable').style.display = "none";
	document.getElementById('SampleImageMargin').style.display = "none";
	document.getElementById('SampleImagePadding').style.display = "none";
/*
	document.getElementById('FilterTable').style.display = "none";
*/
}

function changeColorAuswahl()
{
	if(document.getElementById('namedColorsDiv').style.display == "none")
	{
		document.getElementById('webPaletteDiv').style.display == ""
	}
	else if(document.getElementById('webPaletteDiv').style.display == "none")
	{
		document.getElementById('namedColorsDiv').style.display == ""
	}
}




var SelectorTargetControl = null;

function showSelector(TargetControl, SelectorType)
{
	SelectorTargetControl = TargetControl;

	/*hideDisplaysAtStart();*/
	HideSelectors();

	if (SelectorType == "color")
	{
		showNamedColorAuswahl();
	}
	else if (SelectorType == "font")
	{
		showFontAuswahl();
	}
	else if(SelectorType == "bgcolor")
	{
		showNamedColorAuswahl();
	}
	else if(SelectorType == "borderColor")
	{
		showNamedColorAuswahl();
	}
	else if(SelectorType == "bold")
	{
		showBoldAuswahl();
	}
	else if(SelectorType == "fontSize")
	{
		showFontSizeAuswahl();
	}
	else if(SelectorType == "BGImgPosHor")
	{
		showHorizontaleBGPosition();
	}
	else if(SelectorType == "BGImgPosVer")
	{
		showVerticalBGPosition();
	}
	else if(SelectorType == "Letters")
	{
		showLettersTD();
	}
	else if(SelectorType == "Lines")
	{
		showLinesTD();
	}
	else if(SelectorType == "Image")
	{
		showBulletImageTD();
	}
	else if(SelectorType == "Padding")
	{
		showPaddingImage();
	}
	else if(SelectorType == "Margin")
	{
		showMarginImage();
	}
/*
	else if(SelectorType == "Filter")
	{
		showFilterAuswahl();
	}
*/
}
function hideSelector()
{
	document.getElementById('SampleTextarea').style.display = "";
	showSelector(null, '');
}

/*
function showFilterAuswahl()
{
	if (document.getElementById('FilterTable').style.display == "")
	{
		document.getElementById('FilterTable').style.display = "none";
	}
	else
	{
		document.getElementById('FilterTable').style.display = "";
	}

}
*/

function showPaddingImage()
{
	document.getElementById('SampleTextarea').style.display = "none";
	if (document.getElementById('SampleImagePadding').style.display == "")
	{
		document.getElementById('SampleImagePadding').style.display = "none";
	}
	else
	{
		document.getElementById('SampleImagePadding').style.display = "";
	}
}



function showMarginImage()
{
	document.getElementById('SampleTextarea').style.display = "none";
	if (document.getElementById('SampleImageMargin').style.display == "")
	{
		document.getElementById('SampleImageMargin').style.display = "none";
	}
	else
	{
		document.getElementById('SampleImageMargin').style.display = "";
	}
}



function showBulletImageTD()
{
	if (document.getElementById('SampleImagetable').style.display == "")
	{
		document.getElementById('SampleImagetable').style.display = "none";
	}
	else
	{
		document.getElementById('SampleImagetable').style.display = "";
	}
}

function showLettersTD()
{
	if (document.getElementById('LettersTable').style.display == "")
	{
		document.getElementById('LettersTable').style.display = "none";
	}
	else
	{
		document.getElementById('LettersTable').style.display = "";
	}
}


function showLinesTD()
{
	if (document.getElementById('LinesTable').style.display == "")
	{
		document.getElementById('LinesTable').style.display = "none";
	}
	else
	{
		document.getElementById('LinesTable').style.display = "";
	}
}



function showHorizontaleBGPosition()
{
	if (document.getElementById('BGImagePositionHorTable').style.display == "")
	{
		document.getElementById('BGImagePositionHorTable').style.display = "none";
	}
	else
	{
		document.getElementById('BGImagePositionHorTable').style.display = "";
	}
}



function showVerticalBGPosition()
{
	if (document.getElementById('BGImagePositionVerTable').style.display == "")
	{
		document.getElementById('BGImagePositionVerTable').style.display = "none";
	}
	else
	{
		document.getElementById('BGImagePositionVerTable').style.display = "";
	}
}

function showWebPaletteAuswahl()
{
	document.getElementById('webPaletteDiv').style.display = "";
	document.getElementById('namedColorsDiv').style.display = "none";
}


function showNamedColorAuswahl()
{
	document.getElementById('namedColorsDiv').style.display = "";
	document.getElementById('webPaletteDiv').style.display = "none";
}


function showBoldAuswahl()
{
	if (document.getElementById('BoldTable').style.display == "")
	{
		document.getElementById('BoldTable').style.display = "none";
	}
	else
	{
		document.getElementById('BoldTable').style.display = "";
	}
}

function showFontSizeAuswahl()
{
	if (document.getElementById('FontSizeTable').style.display == "")
	{
		document.getElementById('FontSizeTable').style.display = "none";
	}
	else
	{
		document.getElementById('FontSizeTable').style.display = "";
	}
}


function showFontAuswahl()
{
	if (document.getElementById('FontsAuswahl').style.display == "")
	{
		document.getElementById('FontsAuswahl').style.display = "none";
	}
	else
	{
		document.getElementById('FontsAuswahl').style.display = "";
	}
}


function showFontSampleGen()
{
	var Sammler = "";
	var i;
	var vBox = document.getElementById('genericFontsMultipleBox');
	for (i = 0; i < 5; i++)
	{
		if (vBox.options[i].selected)
		{
			Sammler += vBox.options[i].value;
		}
	}
	document.getElementById('genericSampleTextBox').style.fontFamily = Sammler;
}



function showFontSampleSpe()
{
	var Sammler = "";
	var i;
	var vBox = document.getElementById('specialFontsMultipleBox');
	for (i = 0; i < 28; i++)
	{
		if (vBox.options[i].selected)
		{
			Sammler += vBox.options[i].value;
		}
	}
	document.getElementById('specialSampleTextBox').style.fontFamily = Sammler;
}




function chooseGenFonts()
{
	var Sammler = "";
	var i;
	var vBox = document.getElementById('genericFontsMultipleBox');
	for (i = 0; i < 5; i++)
	{
	    if (vBox.options[i].selected)
	    {
		    Sammler += vBox.options[i].value;
	    }
	}
	document.getElementById('usedFonts').value = document.getElementById('usedFonts').value + Sammler;
    setFontStyle();
}




function chooseSpeFonts()
{
	var Sammler = "";
	var i;
	var vBox = document.getElementById('specialFontsMultipleBox');
	for (i = 0; i < 28; i++)
	{
	    if (vBox.options[i].selected)
	    {
		    Sammler += vBox.options[i].value;
	    }
	}
	document.getElementById('usedFonts').value = document.getElementById('usedFonts').value + Sammler;
    setFontStyle();
}





function selectColorFromTDNamed(InputString)
{
	var colorName = InputString.substring(3,InputString.length);

    aColorOrTransparent('x');
	if (document.getElementById("BGTable").style.display != "none")
	{
		if (document.getElementById('imageInputTextBox').value == "")
		{
			document.getElementById(SelectorTargetControl).value = colorName;
			setBGStyle();
		}
	}

	else if (document.getElementById("FontTable").style.display != "none")
	{
		document.getElementById(SelectorTargetControl).value = colorName;
		setFontStyle();
	}

	else if (document.getElementById("EdgesTable").style.display != "none")
	{
		document.getElementById(SelectorTargetControl).value = colorName;
		setEdgesStyle();
	}
}

function selectColorFromTDWeb(InputString)
{
	var colorName = InputString.substring(3,InputString.length);

    aColorOrTransparent('x');
	if (document.getElementById("BGTable").style.display != "none")
	{
		if (document.getElementById('imageInputTextBox').value == "")
		{
			document.getElementById('bgColorTextBox').value = "#"+colorName;
			setBGStyle();
		}
	}

	else if (document.getElementById("FontTable").style.display != "none")
	{
		document.getElementById('CBox').value = "#"+colorName;
		setFontStyle();
	}

	else if (document.getElementById("EdgesTable").style.display != "none")
	{
		document.getElementById(SelectorTargetControl).value = "#"+colorName;
		setEdgesStyle();
	}
}

/*
function selectFilterOnClickTD(InputString)
{
	document.getElementById('FilterTextBox').value = InputString;
	setFontStyle();
}
*/


function selectLettersOnClick(InputString)
{
	document.getElementById('spacingBetweenLettersTextBox').value = InputString;
	setTextStyle();
}

function selectLinesOnClick(InputString)
{
	document.getElementById('spacingBetweenLinesTextBox').value = InputString;
	setTextStyle();
}


function selectBoldOnClickTD(InputString)
{
	document.getElementById('BoldTextBox').value = InputString;
	setFontStyle();
}


function selectFontSizeOnClickTD(InputString)
{
	document.getElementById('FontSizeTextBox').value = InputString;
	setFontStyle();
}

function selectBGImagePositionVerOnClickTD(InputString)
{
	document.getElementById('VerticalBGPositionTextBox').value = InputString;
	setBGStyle();
}

function selectBGImagePositionHorOnClickTD(InputString)
{
	document.getElementById('HorizontaleBGPositionTextBox').value = InputString;
	setBGStyle();
}

function selectBulledImage(InputString)
{
	if (document.getElementById('ListsTable').style.display == "")
	{
		document.getElementById('customBulletImageTextBox').value = InputString;
		setListsStyle();
	}
	else if (document.getElementById('BGTable').style.display == "")
	{
		if (document.getElementById('bgColorTextBox').value == "" && document.getElementById('transparentCheckBox').checked == false)
		{
			document.getElementById('imageInputTextBox').value = InputString;
			setBGStyle();
		}
	}
}


function selectColorMouseOverTDWeb(InputString)
{
	var colorName = InputString.substring(3,InputString.length);
	document.getElementById('showWebPalette').innerHTML = colorName;
}

function selectColorMouseOutTDWeb(InputString)
{
	document.getElementById('showWebPalette').innerHTML = "";
}


function selectColorMouseOverTDNamed(InputString)
{
	var colorName = InputString.substring(3,InputString.length);
	document.getElementById('showNamedColors').innerHTML = colorName;
}

function selectColorMouseOutTDNamed(InputString)
{
	document.getElementById('showNamedColors').innerHTML = "&nbsp;";
}








function setFontStyle()
{


	document.getElementById('SampleTextarea').style.fontFamily = document.getElementById('usedFonts').value.substring(0,document.getElementById('usedFonts').value.length-2);

	document.getElementById('SampleTextarea').style.color = document.getElementById('CBox').value;
	document.getElementById('SampleTextarea').style.textTransform = document.getElementById('dropDownListCapitalization').value;

	document.getElementById('SampleTextarea').style.fontWeight = document.getElementById('BoldTextBox').value;

/*
	if (document.getElementById('FilterTextBox').value != "")
	{
		document.getElementById('SampleTextarea').style.filter = document.getElementById('FilterTextBox').value;
	}
	else
	{
		document.getElementById('SampleTextarea').style.filter = "";
	}
*/


	if (document.getElementById('radioButtonSmallCaps').checked == true)
	{
		document.getElementById('SampleTextarea').style.fontVariant = "small-caps";
	}
	else if (document.getElementById('radioButtonSmallCapsNormal').checked == true)
	{
		document.getElementById('SampleTextarea').style.fontVariant = "normal";
	}
	else
	{
		document.getElementById('SampleTextarea').style.fontVariant = "";
	}



	if (document.getElementById('radioButtonItalic').checked == true)
	{
		document.getElementById('SampleTextarea').style.fontStyle = "italic";
	}
	else if (document.getElementById('radioButtonItalicNormal').checked == true)
	{
		document.getElementById('SampleTextarea').style.fontStyle = "normal";
	}
	else
	{
		document.getElementById('SampleTextarea').style.fontStyle = "";
	}




	if (document.getElementById('FontSizeTextBox').value == "xx-small" || document.getElementById('FontSizeTextBox').value == "x-small" ||
		document.getElementById('FontSizeTextBox').value == "small" || document.getElementById('FontSizeTextBox').value == "medium" ||
		document.getElementById('FontSizeTextBox').value == "large" || document.getElementById('FontSizeTextBox').value == "x-large" ||
		document.getElementById('FontSizeTextBox').value == "xx-large" || document.getElementById('FontSizeTextBox').value == "Smaller" ||
		document.getElementById('FontSizeTextBox').value == "Larger")
	{
		document.getElementById('SampleTextarea').style.fontSize = document.getElementById('FontSizeTextBox').value;
	}
	else if (document.getElementById('FontSizeTextBox').value == "")
	{
		document.getElementById('SampleTextarea').style.fontSize = "";
	}
	else
	{
		if (document.getElementById('FontSizeTextBox').value != "")
		{
			document.getElementById('SampleTextarea').style.fontSize = document.getElementById('FontSizeTextBox').value + document.getElementById('dropDownListFontSize').value;
		}
	}




	var Decoration = "";
	if (document.getElementById('CheckBoxUnderline').checked)
	{
		Decoration += 'underline ';
	}
	if (document.getElementById('CheckBoxStrikethrough').checked)
	{
		Decoration += 'line-through ';
	}
	if (document.getElementById('CheckBoxOverline').checked)
	{
		Decoration += 'overline ';
	}

	document.getElementById('SampleTextarea').style.textDecoration = Decoration;



	document.getElementById('Hidden').style.fontFamily = document.getElementById('usedFonts').value.substring(0,document.getElementById('usedFonts').value.length-2);

	document.getElementById('Hidden').style.color = document.getElementById('CBox').value;
	document.getElementById('Hidden').style.textTransform = document.getElementById('dropDownListCapitalization').value;

	document.getElementById('Hidden').style.fontWeight = document.getElementById('BoldTextBox').value;

/*
	if (document.getElementById('FilterTextBox').value != "")
	{
		document.getElementById('Hidden').style.filter = document.getElementById('FilterTextBox').value;
	}
	else
	{
		document.getElementById('Hidden').style.filter = "";
	}
*/

	if (document.getElementById('radioButtonSmallCaps').checked == true)
	{
		document.getElementById('Hidden').style.fontVariant = "small-caps";
	}
	else if (document.getElementById('radioButtonSmallCapsNormal').checked == true)
	{
		document.getElementById('Hidden').style.fontVariant = "normal";
	}
	else
	{
		document.getElementById('Hidden').style.fontVariant = "";
	}



	if (document.getElementById('radioButtonItalic').checked == true)
	{
		document.getElementById('Hidden').style.fontStyle = "italic";
	}
	else if (document.getElementById('radioButtonItalicNormal').checked == true)
	{
		document.getElementById('Hidden').style.fontStyle = "normal";
	}
	else
	{
		document.getElementById('Hidden').style.fontStyle = "";
	}




	if (document.getElementById('FontSizeTextBox').value == "xx-small" || document.getElementById('FontSizeTextBox').value == "x-small" ||
		document.getElementById('FontSizeTextBox').value == "small" || document.getElementById('FontSizeTextBox').value == "medium" ||
		document.getElementById('FontSizeTextBox').value == "large" || document.getElementById('FontSizeTextBox').value == "x-large" ||
		document.getElementById('FontSizeTextBox').value == "xx-large" || document.getElementById('FontSizeTextBox').value == "Smaller" ||
		document.getElementById('FontSizeTextBox').value == "Larger")
	{
		document.getElementById('Hidden').style.fontSize = document.getElementById('FontSizeTextBox').value;
	}
	else if (document.getElementById('FontSizeTextBox').value == "")
	{
		document.getElementById('Hidden').style.fontSize = "";
	}
	else
	{
		if (document.getElementById('FontSizeTextBox').value != "")
		{
			document.getElementById('Hidden').style.fontSize = document.getElementById('FontSizeTextBox').value + document.getElementById('dropDownListFontSize').value;
		}
	}




	var Decoration = "";
	if (document.getElementById('CheckBoxUnderline').checked)
	{
		Decoration += 'underline ';
	}
	if (document.getElementById('CheckBoxStrikethrough').checked)
	{
		Decoration += 'line-through ';
	}
	if (document.getElementById('CheckBoxOverline').checked)
	{
		Decoration += 'overline ';
	}


	document.getElementById('Hidden').style.textDecoration = Decoration;

document.getElementById('SampleTextarea').style.color = document.getElementById('CBox').value;
document.getElementById('Hidden').style.color = document.getElementById('CBox').value;
SetSameStyleInHiddenDiv();
}




function uncheckCheckboxesIfNone()
{
	if (document.getElementById('CheckBoxNone').checked)
	{
		document.getElementById('CheckBoxUnderline').checked = false;
		document.getElementById('CheckBoxStrikethrough').checked = false;
		document.getElementById('CheckBoxOverline').checked = false;
	}
	setFontStyle()
}


function uncheckCheckboxesElse()
{
	if (document.getElementById('CheckBoxStrikethrough').checked == true || document.getElementById('CheckBoxOverline').checked == true || document.getElementById('CheckBoxUnderline').checked == true)
	{
		document.getElementById('CheckBoxNone').checked = false;
	}
	setFontStyle()
}



function changeRadioButtonSmallCaps(Index)
{
	if (Index == 1)
	{
		document.getElementById('radioButtonSmallCaps').checked = true;
		document.getElementById('radioButtonSmallCapsNormal').checked = false;
		document.getElementById('radioButtonSmallCapsClear').checked = false;
	}
	else if (Index == 2)
	{
		document.getElementById('radioButtonSmallCaps').checked = false;
		document.getElementById('radioButtonSmallCapsNormal').checked = true;
		document.getElementById('radioButtonSmallCapsClear').checked = false;
	}
	else
	{
		document.getElementById('radioButtonSmallCaps').checked = false;
		document.getElementById('radioButtonSmallCapsNormal').checked = false;
		document.getElementById('radioButtonSmallCapsClear').checked = true;
	}
	setFontStyle()
}



function changeRadioButtonItalic(Index)
{
	if (Index == 1)
	{
		document.getElementById('radioButtonItalic').checked = true;
		document.getElementById('radioButtonItalicNormal').checked = false;
		document.getElementById('radioButtonItalicClear').checked = false;
	}
	else if (Index == 2)
	{
		document.getElementById('radioButtonItalic').checked = false;
		document.getElementById('radioButtonItalicNormal').checked = true;
		document.getElementById('radioButtonItalicClear').checked = false;
	}
	else
	{
		document.getElementById('radioButtonItalic').checked = false;
		document.getElementById('radioButtonItalicNormal').checked = false;
		document.getElementById('radioButtonItalicClear').checked = true;
	}
	setFontStyle()
}





//			*********************************************************
//			* Ab hier kommen die Funktionen fuer den Background Teil *
//			*********************************************************


function setBGStyle()
{




	if (document.getElementById('imageInputTextBox').value == "")
	{
		if (document.getElementById('transparentCheckBox').checked)
		{
			document.getElementById('SampleTextarea').style.backgroundColor = 'transparent';
			document.getElementById('Hidden').style.backgroundColor = 'transparent';
		}
		else
		{
			document.getElementById('SampleTextarea').style.backgroundColor = "";
			document.getElementById('Hidden').style.backgroundColor = "";
		}
		if (document.getElementById('bgColorTextBox').value != "")
		{
			document.getElementById('SampleTextarea').style.backgroundColor = document.getElementById('bgColorTextBox').value;
			document.getElementById('Hidden').style.backgroundColor = document.getElementById('bgColorTextBox').value;
		}
	}



	if (document.getElementById('bgColorTextBox').value == "" && document.getElementById('transparentCheckBox').checked == false)
	{
		if (document.getElementById('imageInputTextBox').value != "")
		{
			document.getElementById('SampleTextarea').style.backgroundImage = "url('" + document.getElementById('imageInputTextBox').value + "')";
			document.getElementById('Hidden').style.backgroundImage = "url('" + document.getElementById('imageInputTextBox').value + "')";
		}
		else
		{
			document.getElementById('SampleTextarea').style.backgroundImage = "";
			document.getElementById('Hidden').style.backgroundImage = "";
		}
	}


	document.getElementById('SampleTextarea').style.cursor = document.getElementById('cursorDropDownList').value;

	document.getElementById('SampleTextarea').style.backgroundAttachment = document.getElementById('bgScrollingDropDownList').value;

	document.getElementById('SampleTextarea').style.backgroundRepeat = document.getElementById('bgTilingDropDownList').value;

	var horPos;
	if (document.getElementById('HorizontaleBGPositionTextBox').value != 'left' || document.getElementById('HorizontaleBGPositionTextBox').value != 'center' || document.getElementById('HorizontaleBGPositionTextBox').value != 'right')
	{
		if (document.getElementById('HorizontaleBGPositionTextBox').value != "")
		{
			horPos = document.getElementById('HorizontaleBGPositionTextBox').value + document.getElementById('HorizontalBGPositionUnitDropDownList').value;
		}
		else
		{
			horPos = "";
		}
	}
	else
	{
		horPos = document.getElementById('HorizontaleBGPositionTextBox').value;
	}

	var verPos;
	if (document.getElementById('VerticalBGPositionTextBox').value != 'top' || document.getElementById('VerticalBGPositionTextBox').value != 'center' || document.getElementById('VerticalBGPositionTextBox').value != 'bottom')
	{
		if (document.getElementById('VerticalBGPositionTextBox').value != "")
		{
			verPos = document.getElementById('VerticalBGPositionTextBox').value + document.getElementById('VerticalBGPositionUnitDropDownList').value;
		}
		else
		{
			verPos = "";
		}
	}
	else
	{
		verPos = document.getElementById('VerticalBGPositionTextBox').value;
	}


	document.getElementById('SampleTextarea').style.backgroundPosition = "";
	var CSSText = document.getElementById('SampleTextarea').style.cssText;
	CSSText = CSSText.replace(/BACKGROUND-POSITION: 0% 0%;/, "");
	CSSText = CSSText.replace(/BACKGROUND-POSITION: 0% 0%/, "");
	document.getElementById('SampleTextarea').style.cssText = CSSText;
	if ((verPos + horPos) == "")
	{
		// already handled
	}
	else if (verPos == "" && horPos != "")
	{
		document.getElementById('SampleTextarea').style.backgroundPositionX = horPos;
	}
	else if (verPos != "" && horPos == "")
	{
		document.getElementById('SampleTextarea').style.backgroundPositionY = verPos;
	}
	else
	{
		document.getElementById('SampleTextarea').style.backgroundPosition = horPos + " " + verPos;
	}








	document.getElementById('Hidden').style.cursor = document.getElementById('cursorDropDownList').value;



	document.getElementById('Hidden').style.backgroundAttachment = document.getElementById('bgScrollingDropDownList').value;


	document.getElementById('Hidden').style.backgroundRepeat = document.getElementById('bgTilingDropDownList').value;



	var horPos;
	if (document.getElementById('HorizontaleBGPositionTextBox').value != 'left' || document.getElementById('HorizontaleBGPositionTextBox').value != 'center' || document.getElementById('HorizontaleBGPositionTextBox').value != 'right')
	{
		if (document.getElementById('HorizontaleBGPositionTextBox').value != "")
		{
			horPos = document.getElementById('HorizontaleBGPositionTextBox').value + document.getElementById('HorizontalBGPositionUnitDropDownList').value;
		}
		else
		{
			horPos = "";
		}
	}
	else
	{
		horPos = document.getElementById('HorizontaleBGPositionTextBox').value;
	}

	var verPos;
	if (document.getElementById('VerticalBGPositionTextBox').value != 'top' || document.getElementById('VerticalBGPositionTextBox').value != 'center' || document.getElementById('VerticalBGPositionTextBox').value != 'bottom')
	{
		if (document.getElementById('VerticalBGPositionTextBox').value != "")
		{
			verPos = document.getElementById('VerticalBGPositionTextBox').value + document.getElementById('VerticalBGPositionUnitDropDownList').value;
		}
		else
		{
			verPos = "";
		}
	}
	else
	{
		verPos = document.getElementById('VerticalBGPositionTextBox').value;
	}


	document.getElementById('Hidden').style.backgroundPosition = "";
	var CSSText = document.getElementById('Hidden').style.cssText;
	CSSText = CSSText.replace(/BACKGROUND-POSITION: 0% 0%;/, "");
	CSSText = CSSText.replace(/BACKGROUND-POSITION: 0% 0%/, "");
	document.getElementById('Hidden').style.cssText = CSSText;
	if ((verPos + horPos) == "")
	{
		// already handled
	}
	else if (verPos == "" && horPos != "")
	{
		document.getElementById('Hidden').style.backgroundPositionX = horPos;
	}
	else if (verPos != "" && horPos == "")
	{
		document.getElementById('Hidden').style.backgroundPositionY = verPos;
	}
	else //if (verPos != "" && horPos != "")
	{
		document.getElementById('Hidden').style.backgroundPosition = horPos + " " + verPos;
	}
SetSameStyleInHiddenDiv();
}

function aColorOrTransparent(inputString)
{
	if(inputString == "trans")
	{
		document.getElementById('bgColorTextBox').value = "";
	}
	else
	{
		document.getElementById('transparentCheckBox').checked = false;
	}
	setBGStyle();
}



//				**********************************************
//				* Ab hier beginnt der Code fuer den Text Teil *
//				**********************************************

function setTextStyle()
{

	document.getElementById('SampleTextarea').style.textAlign = document.getElementById('horizontalAlignmentDropDownList').value;


	if (document.getElementById('horizontalAlignmentDropDownList').value == "")
	{
		//already handeld
	}
	else if (document.getElementById('justificationHorAlignmentDropDownList').value != "" && document.getElementById('horizontalAlignmentDropDownList').value != "right" && document.getElementById('horizontalAlignmentDropDownList').value != "center" && document.getElementById('horizontalAlignmentDropDownList').value != "left")
	{
		document.getElementById('horizontalAlignmentDropDownList').value = "justify";
	}
	else if (document.getElementById('horizontalAlignmentDropDownList').value != "justify")
	{
		document.getElementById('justificationHorAlignmentDropDownList').value = "";
		document.getElementById('SampleTextarea').style.textAlign = document.getElementById('horizontalAlignmentDropDownList').value;
	}
	else
	{
		document.getElementById('horizontalAlignmentDropDownList').value = "justify";
	}


	document.getElementById('SampleTextarea').style.verticalAlign = document.getElementById('verticalAlignmentDropDownList').value;

	document.getElementById('SampleTextarea').style.textJustify = document.getElementById('justificationHorAlignmentDropDownList').value;




	if (document.getElementById('spacingBetweenLettersTextBox').value == 'normal')
	{
		document.getElementById('SampleTextarea').style.letterSpacing = 'normal'
	}
	else if (document.getElementById('spacingBetweenLettersTextBox').value == "")
	{
		document.getElementById('SampleTextarea').style.letterSpacing = "";
	}
	else
	{
		if (document.getElementById('spacingBetweenLettersTextBox').value != "")
		{
			document.getElementById('SampleTextarea').style.letterSpacing = document.getElementById('spacingBetweenLettersTextBox').value + document.getElementById('spacingBetweenLettersUnitsDropDownList').value;
		}
	}



	if (document.getElementById('spacingBetweenLinesTextBox').value == 'normal')
	{
		document.getElementById('SampleTextarea').style.lineHeight = 'normal'
	}
	else if (document.getElementById('spacingBetweenLinesTextBox').value == "")
	{
		document.getElementById('SampleTextarea').style.lineHeight = "";
	}
	else
	{
		if (document.getElementById('spacingBetweenLinesTextBox').value != "")
		{
			document.getElementById('SampleTextarea').style.lineHeight = document.getElementById('spacingBetweenLinesTextBox').value + document.getElementById('spacingBetweenLinesUnitsDropDownList').value;
		}
	}



	if (document.getElementById('textFlowIdentationTextBox').value != "")
	{
		document.getElementById('SampleTextarea').style.textIndent = document.getElementById('textFlowIdentationTextBox').value + document.getElementById('textFlowIdentationDropDownList').value;
	}
	else
	{
		document.getElementById('SampleTextarea').style.textIndent = "";
	}

	document.getElementById('SampleTextarea').style.direction = document.getElementById('textFlowTextDirectionDropDownList').value;







	document.getElementById('Hidden').style.textAlign = document.getElementById('horizontalAlignmentDropDownList').value;


	document.getElementById('Hidden').style.verticalAlign = document.getElementById('verticalAlignmentDropDownList').value;

	document.getElementById('Hidden').style.textJustify = document.getElementById('justificationHorAlignmentDropDownList').value;



	if (document.getElementById('spacingBetweenLettersTextBox').value == 'normal')
	{
		document.getElementById('Hidden').style.letterSpacing = 'normal'
	}
	else if (document.getElementById('spacingBetweenLettersTextBox').value == "")
	{
		document.getElementById('Hidden').style.letterSpacing = "";
	}
	else
	{
		if (document.getElementById('spacingBetweenLettersTextBox').value != "")
		{
			document.getElementById('Hidden').style.letterSpacing = document.getElementById('spacingBetweenLettersTextBox').value + document.getElementById('spacingBetweenLettersUnitsDropDownList').value;
		}
	}



	if (document.getElementById('spacingBetweenLinesTextBox').value == 'normal')
	{
		document.getElementById('Hidden').style.lineHeight = 'normal'
	}
	else if (document.getElementById('spacingBetweenLinesTextBox').value == "")
	{
		document.getElementById('Hidden').style.lineHeight = "";
	}
	else
	{
		if (document.getElementById('spacingBetweenLinesTextBox').value != "")
		{
			document.getElementById('Hidden').style.lineHeight = document.getElementById('spacingBetweenLinesTextBox').value + document.getElementById('spacingBetweenLinesUnitsDropDownList').value;
		}
	}



	if (document.getElementById('textFlowIdentationTextBox').value != "")
	{
		document.getElementById('Hidden').style.textIndent = document.getElementById('textFlowIdentationTextBox').value + document.getElementById('textFlowIdentationDropDownList').value;
	}
	else
	{
		document.getElementById('Hidden').style.textIndent = "";
	}

	document.getElementById('Hidden').style.direction = document.getElementById('textFlowTextDirectionDropDownList').value;


SetSameStyleInHiddenDiv();
}


//				****************************************
//				* Ab hier beginnt der Code fuer Positon *
//				****************************************



function setPositionStyle()
{
	if(typeof(document.getElementById('SampleTextarea').style.styleFloat)!= 'undefined' )
		document.getElementById('SampleTextarea').style.styleFloat = document.getElementById('flowControlAllowTextToFlowDropDownList').value;
	else
		document.getElementById('SampleTextarea').style.cssFloat = document.getElementById('flowControlAllowTextToFlowDropDownList').value;
	document.getElementById('SampleTextarea').style.clear = document.getElementById('flowControlAllowFloatingObjectsDropDownList').value;

	document.getElementById('Hidden').style.visibility = document.getElementById('flowControlVisibilityDropDownList').value;
	if(typeof(document.getElementById('Hidden').style.styleFloat)!= 'undefined' )
		document.getElementById('Hidden').style.styleFloat = document.getElementById('flowControlAllowTextToFlowDropDownList').value;
	else
		document.getElementById('Hidden').style.cssFloat = document.getElementById('flowControlAllowTextToFlowDropDownList').value;
	document.getElementById('Hidden').style.clear = document.getElementById('flowControlAllowFloatingObjectsDropDownList').value;


	document.getElementById('Hidden').style.top = "";
	document.getElementById('Hidden').style.left = "";

	document.getElementById('Hidden').style.position = document.getElementById('positionModeDropDownList').value;

	if (document.getElementById('positionTopTextBox').value != "")
	{
		document.getElementById('Hidden').style.top = document.getElementById('positionTopTextBox').value + document.getElementById('positionTopDropDownList').value;
	}
	else
	{
		document.getElementById('Hidden').style.top = "";
	}

	if (document.getElementById('positionHeightTextBox').value != "")
	{
		document.getElementById('Hidden').style.height = document.getElementById('positionHeightTextBox').value + document.getElementById('positionHeightDropDownList').value;
	}
	else
	{
		document.getElementById('Hidden').style.height = "";
	}

	if (document.getElementById('positionLeftTextBox').value != "")
	{
		document.getElementById('Hidden').style.left = document.getElementById('positionLeftTextBox').value + document.getElementById('positionLeftDropDownList').value;
	}
	else
	{
		document.getElementById('Hidden').style.left = "";
	}

	if (document.getElementById('positionWitdthTextBox').value != "")
	{
		document.getElementById('Hidden').style.width = document.getElementById('positionWitdthTextBox').value + document.getElementById('positionWidthDropDownList').value;
	}
	else
	{
		document.getElementById('Hidden').style.width = "";
	}

	if (document.getElementById('positionZIndexTextBox').value != "")
	{
		document.getElementById('Hidden').style.zIndex = document.getElementById('positionZIndexTextBox').value;
	}
	else
	{
		document.getElementById('Hidden').style.zIndex = "";
	}

SetSameStyleInHiddenDiv();
}



function changePositionSample()
{
	if (document.getElementById("PositionTable").style.display != "none")
	{
		if (document.getElementById('positionModeDropDownList').value == "static")
		{
			document.getElementById('SampleImageNormal').style.display = "";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "none";
		}
		else if (document.getElementById('positionModeDropDownList').value == "relative")
		{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "none";
		}
		else if (document.getElementById('positionModeDropDownList').value == "absolute")
		{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "";
			document.getElementById('SampleTextarea').style.display = "none";
		}
		else
		{
			document.getElementById('SampleImageNormal').style.display = "none";
			document.getElementById('SampleImageRelativ').style.display = "none";
			document.getElementById('SampleImageAbsolute').style.display = "none";
			document.getElementById('SampleTextarea').style.display = "";
		}
	}
}



//				**********************************************
//				* Ab hier kommt der Code fuer den Layout teil *
//				**********************************************


function setLayoutStyle()
{


	document.getElementById('SampleTextarea').style.tableLayout = document.getElementById('tablesLayoutDropDownList').value;



	document.getElementById('SampleTextarea').style.pageBreakBefore = document.getElementById('pageBreakBefore').value;
	document.getElementById('SampleTextarea').style.pageBreakAfter = document.getElementById('pageBreakAfter').value;

	document.getElementById('SampleTextarea').style.overflow = document.getElementById('contentOverflowDropDownList').value;

	var clipTop = "";
	var clipRight = "";
	var clipBottom = "";
	var clipLeft = "";

	if (document.getElementById('clippinTopTextBox').value != "")
	{
		clipTop = document.getElementById('clippinTopTextBox').value + document.getElementById('clippinTopUnitsDropDownList').value;
	}
	else
	{
		clipTop = "auto";
	}


	if (document.getElementById('clippinRightTextBox').value != "")
	{
		clipRight = document.getElementById('clippinRightTextBox').value + document.getElementById('clippinRightUnitsDropDownList').value;
	}
	else
	{
		clipRight = "auto";
	}


	if (document.getElementById('clippinBottomTextBox').value != "")
	{
		clipBottom = document.getElementById('clippinBottomTextBox').value + document.getElementById('clippinBottomUnitsDropDownList').value;
	}
	else
	{
		clipBottom = "auto";
	}


	if (document.getElementById('clippinLeftTextBox').value != "")
	{
		clipLeft = document.getElementById('clippinLeftTextBox').value + document.getElementById('clippinLeftUnitsDropDownList').value;
	}
	else
	{
		clipLeft = "auto";
	}

	if (document.getElementById('clippinLeftTextBox').value != "" || document.getElementById('clippinBottomTextBox').value != "" || document.getElementById('clippinRightTextBox').value != "" || document.getElementById('clippinTopTextBox').value != "")
	{
		document.getElementById('SampleTextarea').style.clip = "rect(" + clipTop + " " + clipRight + " " + clipBottom + " " + clipLeft + ")";
	}

	if (document.getElementById('clippinLeftTextBox').value == "" && document.getElementById('clippinBottomTextBox').value == "" && document.getElementById('clippinRightTextBox').value == "" && document.getElementById('clippinTopTextBox').value == "")
	{
		document.getElementById('SampleTextarea').style.clip = "rect(auto auto auto auto)";
	}


	var CSSTextx = document.getElementById('SampleTextarea').style.cssText;
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto auto auto auto\);/gi, "");
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto, auto, auto, auto\);/gi, "");
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto auto auto auto\)/gi, "");
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto, auto, auto, auto\)/gi, "");
	document.getElementById('SampleTextarea').style.cssText = CSSTextx;


	document.getElementById('Hidden').style.tableLayout = document.getElementById('tablesLayoutDropDownList').value;




	document.getElementById('Hidden').style.pageBreakBefore = document.getElementById('pageBreakBefore').value;
	document.getElementById('Hidden').style.pageBreakAfter = document.getElementById('pageBreakAfter').value;

	document.getElementById('Hidden').style.overflow = document.getElementById('contentOverflowDropDownList').value;

	var clipTop = "";
	var clipRight = "";
	var clipBottom = "";
	var clipLeft = "";

	if (document.getElementById('clippinTopTextBox').value != "")
	{
		clipTop = document.getElementById('clippinTopTextBox').value + document.getElementById('clippinTopUnitsDropDownList').value;
	}
	else
	{
		clipTop = "auto";
	}


	if (document.getElementById('clippinRightTextBox').value != "")
	{
		clipRight = document.getElementById('clippinRightTextBox').value + document.getElementById('clippinRightUnitsDropDownList').value;
	}
	else
	{
		clipRight = "auto";
	}


	if (document.getElementById('clippinBottomTextBox').value != "")
	{
		clipBottom = document.getElementById('clippinBottomTextBox').value + document.getElementById('clippinBottomUnitsDropDownList').value;
	}
	else
	{
		clipBottom = "auto";
	}


	if (document.getElementById('clippinLeftTextBox').value != "")
	{
		clipLeft = document.getElementById('clippinLeftTextBox').value + document.getElementById('clippinLeftUnitsDropDownList').value;
	}
	else
	{
		clipLeft = "auto";
	}

	if (document.getElementById('clippinLeftTextBox').value != "" || document.getElementById('clippinBottomTextBox').value != "" || document.getElementById('clippinRightTextBox').value != "" || document.getElementById('clippinTopTextBox').value != "")
	{
		document.getElementById('Hidden').style.clip = "rect(" + clipTop + " " + clipRight + " " + clipBottom + " " + clipLeft + ")";
	}

	if (document.getElementById('clippinLeftTextBox').value == "" && document.getElementById('clippinBottomTextBox').value == "" && document.getElementById('clippinRightTextBox').value == "" && document.getElementById('clippinTopTextBox').value == "")
	{
		document.getElementById('Hidden').style.clip = "rect(auto auto auto auto)";
	}


	var CSSTextx = document.getElementById('Hidden').style.cssText;
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto auto auto auto\);/gi, "");
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto, auto, auto, auto\);/gi, "");
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto auto auto auto\)/gi, "");
	CSSTextx = CSSTextx.replace(/CLIP: rect\(auto, auto, auto, auto\)/gi, "");
	document.getElementById('Hidden').style.cssText = CSSTextx;


SetSameStyleInHiddenDiv();
}






//				***********************************************
//				* Ab hier beginnt der Code fuer den Edges Teil *
//				***********************************************


function setEdgesStyle()
{
	var vSampleTextarea = document.getElementById('SampleTextarea');
	var vHidden = document.getElementById('Hidden');

	vSampleTextarea.style.borderCollapse = document.getElementById('tablesBordersDropDownList').value;


	var allColor = "";
	var allWidth = "";
	var allStyle = "";

	allColor = document.getElementById('borderColorAllTextBox').value;

	if (document.getElementById('borderWidthAllDropDownList').value == "thick" || document.getElementById('borderWidthAllDropDownList').value == "thin")
	{
		allWidth = document.getElementById('borderWidthAllDropDownList').value;
	}
	else if(document.getElementById('borderWidthAllTextBox').value != "" && document.getElementById('borderWidthAllDropDownList').value != "")
	{
		allWidth = document.getElementById('borderWidthAllTextBox').value + document.getElementById('borderWidthAllDropDownList').value;
	}

	allStyle = document.getElementById('borderStyleAllDropDownList').value;




//******************************************************************************************************************************************************************


	var leftColor = "";
	var leftWidth = "";
	var leftStyle = "";

	leftColor = document.getElementById('borderColorLeftTextBox').value;

	if (document.getElementById('borderWidthLeftDropDownList').value == "thick" || document.getElementById('borderWidthLeftDropDownList').value == "thin")
	{
		leftWidth = document.getElementById('borderWidthLeftDropDownList').value;
	}
	else if(document.getElementById('borderWidthLeftTextBox').value != "" && document.getElementById('borderWidthLeftDropDownList').value != "")
	{
		leftWidth = document.getElementById('borderWidthLeftTextBox').value + document.getElementById('borderWidthLeftDropDownList').value;
	}

	leftStyle = document.getElementById('borderStyleLeftDropDownList').value;



//*******************************************************************************************************************************************

	var rightColor = "";
	var rightWidth = "";
	var rightStyle = "";

	rightColor = document.getElementById('borderColorRightTextBox').value;

	if (document.getElementById('borderWidthRightDropDownList').value == "thick" || document.getElementById('borderWidthRightDropDownList').value == "thin")
	{
		rightWidth = document.getElementById('borderWidthRightDropDownList').value;
	}
	else if(document.getElementById('borderWidthRightTextBox').value != "" && document.getElementById('borderWidthRightDropDownList').value != "")
	{
		rightWidth = document.getElementById('borderWidthRightTextBox').value + document.getElementById('borderWidthRightDropDownList').value;
	}

	rightStyle = document.getElementById('borderStyleRightDropDownList').value;


//*******************************************************************************************************************************************

	var bottomColor = "";
	var bottomWidth = "";
	var bottomStyle = "";

	bottomColor = document.getElementById('borderColorBottomTextBox').value;

	if (document.getElementById('borderWidthBottomDropDownList').value == "thick" || document.getElementById('borderWidthBottomDropDownList').value == "thin")
	{
		bottomWidth = document.getElementById('borderWidthBottomDropDownList').value;
	}
	else if(document.getElementById('borderWidthBottomTextBox').value != "" && document.getElementById('borderWidthBottomDropDownList').value != "")
	{
		bottomWidth = document.getElementById('borderWidthBottomTextBox').value + document.getElementById('borderWidthBottomDropDownList').value;
	}

	bottomStyle = document.getElementById('borderStyleBottomDropDownList').value;


//*****************************************************************************************************************************************


	var topColor = "";
	var topWidth = "";
	var topStyle = "";

	topColor = document.getElementById('borderColorTopTextBox').value;

	if (document.getElementById('borderWidthTopDropDownList').value == "thick" || document.getElementById('borderWidthTopDropDownList').value == "thin")
	{
		topWidth = document.getElementById('borderWidthTopDropDownList').value;
	}
	else if(document.getElementById('borderWidthTopTextBox').value != "" && document.getElementById('borderWidthTopDropDownList').value != "")
	{
		topWidth = document.getElementById('borderWidthTopTextBox').value + document.getElementById('borderWidthTopDropDownList').value;
	}

	topStyle = document.getElementById('borderStyleTopDropDownList').value;

//***************************************************************************************************************************************
//***************************************************************************************************************************************


	vSampleTextarea.style.borderStyle = "";
	vSampleTextarea.style.borderColor = "";
	vSampleTextarea.style.borderWidth = "";
	vSampleTextarea.style.borderLeft = "";
	vSampleTextarea.style.borderLeftWidth = "";
	vSampleTextarea.style.borderLeftColor = "";
	vSampleTextarea.style.borderLeftStyle = "";
	vSampleTextarea.style.borderBottom = "";
	vSampleTextarea.style.borderBottomWidth = "";
	vSampleTextarea.style.borderBottomColor = "";
	vSampleTextarea.style.borderBottomStyle = "";
	vSampleTextarea.style.borderTop = "";
	vSampleTextarea.style.borderTopWidth = "";
	vSampleTextarea.style.borderTopColor = "";
	vSampleTextarea.style.borderTopStyle = "";
	vSampleTextarea.style.borderRight = "";
	vSampleTextarea.style.borderRightWidth = "";
	vSampleTextarea.style.borderRightColor = "";
	vSampleTextarea.style.borderRightStyle = "";


	if (allColor != "" && allWidth != "" && allStyle != "")
	{
		vSampleTextarea.style.border = allColor + " " + allWidth + " " + allStyle;
	}
	else if (allColor != "" && allWidth == "" && allStyle != "")
	{
		vSampleTextarea.style.borderStyle = allStyle;
		vSampleTextarea.style.borderColor = allColor;
	}
	else if (allColor == "" && allWidth != "" && allStyle != "")
	{
		vSampleTextarea.style.borderStyle = allStyle;
		vSampleTextarea.style.borderWidth = allWidth;
	}
	else if (allColor == "" && allWidth == "" && allStyle != "")
	{
		vSampleTextarea.style.borderStyle = allStyle;
	}
//	else if (allColor == "" && allWidth == "" && allStyle == "")


	if (allStyle != "" && leftStyle == "")
	{
		leftStyle = allStyle;
	}
	if (allStyle != "" && rightStyle == "")
	{
		rightStyle = allStyle;
	}
	if (allStyle != "" && topStyle == "")
	{
		topStyle = allStyle;
	}
	if (allStyle != "" && bottomStyle == "")
	{
		bottomStyle = allStyle;
	}


	if (leftColor != "" && leftWidth != "" && leftStyle != "")
	{
		vSampleTextarea.style.borderLeft = leftColor + " " + leftWidth + " " + leftStyle;
	}
	else if (leftColor != "" && leftWidth == "" && leftStyle != "")
	{
		vSampleTextarea.style.borderLeftStyle = leftStyle;
		vSampleTextarea.style.borderLeftColor = leftColor;
	}
	else if (leftColor == "" && leftWidth != "" && leftStyle != "")
	{
		vSampleTextarea.style.borderLeftStyle = leftStyle;
		vSampleTextarea.style.borderLeftWidth = leftWidth;
	}
	else if (leftColor == "" && leftWidth == "" && leftStyle != "")
	{
		vSampleTextarea.style.borderLeftStyle = leftStyle;
	}


	if (rightColor != "" && rightWidth != "" && rightStyle != "")
	{
		vSampleTextarea.style.borderRight = rightColor + " " + rightWidth + " " + rightStyle;
	}
	else if (rightColor != "" && rightWidth == "" && rightStyle != "")
	{
		vSampleTextarea.style.borderRightStyle = rightStyle;
		vSampleTextarea.style.borderRightColor = rightColor;
	}
	else if (rightColor == "" && rightWidth != "" && rightStyle != "")
	{
		vSampleTextarea.style.borderRightStyle = rightStyle;
		vSampleTextarea.style.borderRightWidth = rightWidth;
	}
	else if (rightColor == "" && rightWidth == "" && rightStyle != "")
	{
		vSampleTextarea.style.borderRightStyle = rightStyle;
	}



	if (topColor != "" && topWidth != "" && topStyle != "")
	{
		vSampleTextarea.style.borderTop = topColor + " " + topWidth + " " + topStyle;
	}
	else if (topColor != "" && topWidth == "" && topStyle != "")
	{
		vSampleTextarea.style.borderTopStyle = topStyle;
		vSampleTextarea.style.borderTopColor = topColor;
	}
	else if (topColor == "" && topWidth != "" && topStyle != "")
	{
		vSampleTextarea.style.borderTopStyle = topStyle;
		vSampleTextarea.style.borderTopWidth = topWidth;
	}
	else if (topColor == "" && topWidth == "" && topStyle != "")
	{
		vSampleTextarea.style.borderTopStyle = topStyle;
	}


	if (bottomColor != "" && bottomWidth != "" && bottomStyle != "")
	{
		vSampleTextarea.style.borderBottom = bottomColor + " " + bottomWidth + " " + bottomStyle;
	}
	else if (bottomColor != "" && bottomWidth == "" && bottomStyle != "")
	{
		vSampleTextarea.style.borderBottomStyle = bottomStyle;
		vSampleTextarea.style.borderBottomColor = bottomColor;
	}
	else if (bottomColor == "" && bottomWidth != "" && bottomStyle != "")
	{
		vSampleTextarea.style.borderBottomStyle = bottomStyle;
		vSampleTextarea.style.borderBottomWidth = bottomWidth;
	}
	else if (bottomColor == "" && bottomWidth == "" && bottomStyle != "")
	{
		vSampleTextarea.style.borderBottomStyle = bottomStyle;
	}


	vHidden.style.borderCollapse = document.getElementById('tablesBordersDropDownList').value;


	if (document.getElementById('borderPaddingAllTextBox').value != "")
	{
		vHidden.style.padding = document.getElementById('borderPaddingAllTextBox').value + document.getElementById('borderPaddingAllDropDownList').value;
	}
	else if (document.getElementById('borderPaddingAllTextBox').value == "")
	{
		vHidden.style.padding = "";
	}
	if (document.getElementById('borderPaddingTopTextBox').value != "")
	{
		vHidden.style.paddingTop = document.getElementById('borderPaddingTopTextBox').value + document.getElementById('borderPaddingTopDropDownList').value;
	}
	else if (document.getElementById('borderPaddingTopTextBox').value == "" && document.getElementById('borderPaddingAllTextBox').value == "")
	{
		vHidden.style.paddingTop = "";
	}
	if (document.getElementById('borderPaddingBottomTextBox').value != "")
	{
		vHidden.style.paddingBottom = document.getElementById('borderPaddingBottomTextBox').value + document.getElementById('borderPaddingBottomDropDownList').value;
	}
	else if (document.getElementById('borderPaddingBottomTextBox').value == "" && document.getElementById('borderPaddingAllTextBox').value == "")
	{
		vHidden.style.paddingBottom = "";
	}
	if (document.getElementById('borderPaddingLeftTextBox').value != "")
	{
		vHidden.style.paddingLeft = document.getElementById('borderPaddingLeftTextBox').value + document.getElementById('borderPaddingLeftDropDownList').value;
	}
	else if (document.getElementById('borderPaddingLeftTextBox').value == "" && document.getElementById('borderPaddingAllTextBox').value == "")
	{
		vHidden.style.paddingLeft = "";
	}
	if (document.getElementById('borderPaddingRightTextBox').value != "")
	{
		vHidden.style.paddingRight = document.getElementById('borderPaddingRightTextBox').value + document.getElementById('borderPaddingRightDropDownList').value;
	}
	else if (document.getElementById('borderPaddingRightTextBox').value == "" && document.getElementById('borderPaddingAllTextBox').value == "")
	{
		vHidden.style.paddingRight = "";
	}



	if (document.getElementById('borderMarginAllTextBox').value != "")
	{
		vHidden.style.margin = document.getElementById('borderMarginAllTextBox').value + document.getElementById('borderMarginAllDropDownList').value;
	}
	else if (document.getElementById('borderMarginAllTextBox').value == "")
	{
		vHidden.style.margin = "";
	}
	if (document.getElementById('borderMarginTopTextBox').value != "")
	{
		vHidden.style.marginTop = document.getElementById('borderMarginTopTextBox').value + document.getElementById('borderMarginTopDropDownList').value;
	}
	else if (document.getElementById('borderMarginTopTextBox').value == "" && document.getElementById('borderMarginAllTextBox').value == "")
	{
		vHidden.style.marginTop = "";
	}
	if (document.getElementById('borderMarginBottomTextBox').value != "")
	{
		vHidden.style.marginBottom = document.getElementById('borderMarginBottomTextBox').value + document.getElementById('borderMarginBottomDropDownList').value;
	}
	else if (document.getElementById('borderMarginBottomTextBox').value == "" && document.getElementById('borderMarginAllTextBox').value == "")
	{
		vHidden.style.marginBottom = "";
	}
	if (document.getElementById('borderMarginLeftTextBox').value != "")
	{
		vHidden.style.marginLeft = document.getElementById('borderMarginLeftTextBox').value + document.getElementById('borderMarginLeftDropDownList').value;
	}
	else if (document.getElementById('borderMarginLeftTextBox').value == "" && document.getElementById('borderMarginAllTextBox').value == "")
	{
		vHidden.style.marginLeft = "";
	}
	if (document.getElementById('borderMarginRightTextBox').value != "")
	{
		vHidden.style.marginRight = document.getElementById('borderMarginRightTextBox').value + document.getElementById('borderMarginRightDropDownList').value;
	}
	else if (document.getElementById('borderMarginRightTextBox').value == "" && document.getElementById('borderMarginAllTextBox').value == "")
	{
		vHidden.style.marginRight = "";
	}






//**********************************************************************************************************************************************
//**********************************************************************************************************************************************


	var allColor = "";
	var allWidth = "";
	var allStyle = "";

	allColor = document.getElementById('borderColorAllTextBox').value;

	if (document.getElementById('borderWidthAllDropDownList').value == "thick" || document.getElementById('borderWidthAllDropDownList').value == "thin")
	{
		allWidth = document.getElementById('borderWidthAllDropDownList').value;
	}
	else if(document.getElementById('borderWidthAllTextBox').value != "" && document.getElementById('borderWidthAllDropDownList').value != "")
	{
		allWidth = document.getElementById('borderWidthAllTextBox').value + document.getElementById('borderWidthAllDropDownList').value;
	}

	allStyle = document.getElementById('borderStyleAllDropDownList').value;




//******************************************************************************************************************************************************************


	var leftColor = "";
	var leftWidth = "";
	var leftStyle = "";

	leftColor = document.getElementById('borderColorLeftTextBox').value;

	if (document.getElementById('borderWidthLeftDropDownList').value == "thick" || document.getElementById('borderWidthLeftDropDownList').value == "thin")
	{
		leftWidth = document.getElementById('borderWidthLeftDropDownList').value;
	}
	else if(document.getElementById('borderWidthLeftTextBox').value != "" && document.getElementById('borderWidthLeftDropDownList').value != "")
	{
		leftWidth = document.getElementById('borderWidthLeftTextBox').value + document.getElementById('borderWidthLeftDropDownList').value;
	}

	leftStyle = document.getElementById('borderStyleLeftDropDownList').value;



//*******************************************************************************************************************************************

	var rightColor = "";
	var rightWidth = "";
	var rightStyle = "";

	rightColor = document.getElementById('borderColorRightTextBox').value;

	if (document.getElementById('borderWidthRightDropDownList').value == "thick" || document.getElementById('borderWidthRightDropDownList').value == "thin")
	{
		rightWidth = document.getElementById('borderWidthRightDropDownList').value;
	}
	else if(document.getElementById('borderWidthRightTextBox').value != "" && document.getElementById('borderWidthRightDropDownList').value != "")
	{
		rightWidth = document.getElementById('borderWidthRightTextBox').value + document.getElementById('borderWidthRightDropDownList').value;
	}

	rightStyle = document.getElementById('borderStyleRightDropDownList').value;


//*******************************************************************************************************************************************

	var bottomColor = "";
	var bottomWidth = "";
	var bottomStyle = "";

	bottomColor = document.getElementById('borderColorBottomTextBox').value;

	if (document.getElementById('borderWidthBottomDropDownList').value == "thick" || document.getElementById('borderWidthBottomDropDownList').value == "thin")
	{
		bottomWidth = document.getElementById('borderWidthBottomDropDownList').value;
	}
	else if(document.getElementById('borderWidthBottomTextBox').value != "" && document.getElementById('borderWidthBottomDropDownList').value != "")
	{
		bottomWidth = document.getElementById('borderWidthBottomTextBox').value + document.getElementById('borderWidthBottomDropDownList').value;
	}

	bottomStyle = document.getElementById('borderStyleBottomDropDownList').value;


//*****************************************************************************************************************************************


	var topColor = "";
	var topWidth = "";
	var topStyle = "";

	topColor = document.getElementById('borderColorTopTextBox').value;

	if (document.getElementById('borderWidthTopDropDownList').value == "thick" || document.getElementById('borderWidthTopDropDownList').value == "thin")
	{
		topWidth = document.getElementById('borderWidthTopDropDownList').value;
	}
	else if(document.getElementById('borderWidthTopTextBox').value != "" && document.getElementById('borderWidthTopDropDownList').value != "")
	{
		topWidth = document.getElementById('borderWidthTopTextBox').value + document.getElementById('borderWidthTopDropDownList').value;
	}

	topStyle = document.getElementById('borderStyleTopDropDownList').value;

//***************************************************************************************************************************************
//***************************************************************************************************************************************


	vHidden.style.borderStyle = "";
	vHidden.style.borderColor = "";
	vHidden.style.borderWidth = "";
	vHidden.style.borderLeft = "";
	vHidden.style.borderLeftWidth = "";
	vHidden.style.borderLeftColor = "";
	vHidden.style.borderLeftStyle = "";
	vHidden.style.borderBottom = "";
	vHidden.style.borderBottomWidth = "";
	vHidden.style.borderBottomColor = "";
	vHidden.style.borderBottomStyle = "";
	vHidden.style.borderTop = "";
	vHidden.style.borderTopWidth = "";
	vHidden.style.borderTopColor = "";
	vHidden.style.borderTopStyle = "";
	vHidden.style.borderRight = "";
	vHidden.style.borderRightWidth = "";
	vHidden.style.borderRightColor = "";
	vHidden.style.borderRightStyle = "";


	if (allColor != "" && allWidth != "" && allStyle != "")
	{
		vHidden.style.border = allColor + " " + allWidth + " " + allStyle;
	}
	else if (allColor != "" && allWidth == "" && allStyle != "")
	{
		vHidden.style.borderStyle = allStyle;
		vHidden.style.borderColor = allColor;
	}
	else if (allColor == "" && allWidth != "" && allStyle != "")
	{
		vHidden.style.borderStyle = allStyle;
		vHidden.style.borderWidth = allWidth;
	}
	else if (allColor == "" && allWidth == "" && allStyle != "")
	{
		vHidden.style.borderStyle = allStyle;
	}
//	else if (allColor == "" && allWidth == "" && allStyle == "")


	if (allStyle != "" && leftStyle == "")
	{
		leftStyle = allStyle;
	}
	if (allStyle != "" && rightStyle == "")
	{
		rightStyle = allStyle;
	}
	if (allStyle != "" && topStyle == "")
	{
		topStyle = allStyle;
	}
	if (allStyle != "" && bottomStyle == "")
	{
		bottomStyle = allStyle;
	}


	if (leftColor != "" && leftWidth != "" && leftStyle != "")
	{
		vHidden.style.borderLeft = leftColor + " " + leftWidth + " " + leftStyle;
	}
	else if (leftColor != "" && leftWidth == "" && leftStyle != "")
	{
		vHidden.style.borderLeftStyle = leftStyle;
		vHidden.style.borderLeftColor = leftColor;
	}
	else if (leftColor == "" && leftWidth != "" && leftStyle != "")
	{
		vHidden.style.borderLeftStyle = leftStyle;
		vHidden.style.borderLeftWidth = leftWidth;
	}
	else if (leftColor == "" && leftWidth == "" && leftStyle != "")
	{
		vHidden.style.borderLeftStyle = leftStyle;
	}


	if (rightColor != "" && rightWidth != "" && rightStyle != "")
	{
		vHidden.style.borderRight = rightColor + " " + rightWidth + " " + rightStyle;
	}
	else if (rightColor != "" && rightWidth == "" && rightStyle != "")
	{
		vHidden.style.borderRightStyle = rightStyle;
		vHidden.style.borderRightColor = rightColor;
	}
	else if (rightColor == "" && rightWidth != "" && rightStyle != "")
	{
		vHidden.style.borderRightStyle = rightStyle;
		vHidden.style.borderRightWidth = rightWidth;
	}
	else if (rightColor == "" && rightWidth == "" && rightStyle != "")
	{
		vHidden.style.borderRightStyle = rightStyle;
	}



	if (topColor != "" && topWidth != "" && topStyle != "" )
	{
		vHidden.style.borderTop = topColor + " " + topWidth + " " + topStyle;
	}
	else if (topColor != "" && topWidth == "" && topStyle != "")
	{
		vHidden.style.borderTopStyle = topStyle;
		vHidden.style.borderTopColor = topColor;
	}
	else if (topColor == "" && topWidth != "" && topStyle != "")
	{
		vHidden.style.borderTopStyle = topStyle;
		vHidden.style.borderTopWidth = topWidth;
	}
	else if (topColor == "" && topWidth == "" && topStyle != "")
	{
		vHidden.style.borderTopStyle = topStyle;
	}


	if (bottomColor != "" && bottomWidth != "" && bottomStyle != "")
	{
		vHidden.style.borderBottom = bottomColor + " " + bottomWidth + " " + bottomStyle;
	}
	else if (bottomColor != "" && bottomWidth == "" && bottomStyle != "")
	{
		vHidden.style.borderBottomStyle = bottomStyle;
		vHidden.style.borderBottomColor = bottomColor;
	}
	else if (bottomColor == "" && bottomWidth != "" && bottomStyle != "")
	{
		vHidden.style.borderBottomStyle = bottomStyle;
		vHidden.style.borderBottomWidth = bottomWidth;
	}
	else if (bottomColor == "" && bottomWidth == "" && bottomStyle != "")
	{
		vHidden.style.borderBottomStyle = bottomStyle;
	}



SetSameStyleInHiddenDiv();
}


//				***********************************************
//				* Ab hier beginnt der Code fuer den Lists Teil *
//				***********************************************


function setListsStyle()
{


	if (document.getElementById('customBulletImageTextBox').value != "")
	{
		document.getElementById('SampleList').style.listStyleImage = "url(" + document.getElementById('customBulletImageTextBox').value + ")";
	}
	else if (document.getElementById('customBulletRadioButton_1').checked)
	{
		document.getElementById('SampleList').style.listStyleImage = "";
	}

	if (document.getElementById('customBulletRadioButton_2').checked)
	{
		document.getElementById('SampleList').style.listStyleImage = 'none';
	}

	document.getElementById('SampleList').style.listStyleType = document.getElementById('bulletStyleDropDownList').value;

	document.getElementById('SampleList').style.listStylePosition = document.getElementById('bulletPositionDropDownList').value;

	if (document.getElementById('ListsDropDownList').value == "Unbulleted")
	{
		document.getElementById('SampleList').style.listStyleType = 'none';
	}


	var vHidden = document.getElementById('Hidden');

	if (document.getElementById('customBulletImageTextBox').value != "")
	{
		vHidden.style.listStyleImage = "url(" + document.getElementById('customBulletImageTextBox').value + ")";
	}
	else if (document.getElementById('customBulletRadioButton_1').checked)
	{
		vHidden.style.listStyleImage = "";
	}

	if (document.getElementById('customBulletRadioButton_2').checked)
	{
		vHidden.style.listStyleImage = 'none';
	}

	vHidden.style.listStyleType = document.getElementById('bulletStyleDropDownList').value;

	vHidden.style.listStylePosition = document.getElementById('bulletPositionDropDownList').value;

	if (document.getElementById('ListsDropDownList').value == "Unbulleted")
	{
		vHidden.style.listStyleType = 'none';
	}


	SetSameStyleInHiddenDiv();
}

function changeRadioButtonLists(Index)
{
	if (Index == 1)
	{
		document.getElementById('customBulletRadioButton_1').checked = true;
		document.getElementById('customBulletRadioButton_2').checked = false;
	}
	else if (Index == 2)
	{
		document.getElementById('customBulletRadioButton_1').checked = false;
		document.getElementById('customBulletRadioButton_2').checked = true;
	}
}


