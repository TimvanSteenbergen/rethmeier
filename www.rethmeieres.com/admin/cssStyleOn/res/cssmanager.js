	/***************************************************************
	 * cssStyleOn - http://cssStyleOn.c0n.de 2008 by Stefan Baur
	 *--------------------------------------------------------------
	 *  res/cssmanager.js
	 *     CSS Manager
	 *--------------------------------------------------------------
	 *  TODO: make some input fields active when switching tabs?
	 **************************************************************/

//////////////////////////////////////////////////////////////////////////////
// Init
//////////////////////////////////////////////////////////////////////////////

function CSSManagerLoad()
	{
		//on load open paragraph tab
	layoutButtonClick('cssmanager','paragraph');

	//buttonClick('FontButton'); //show font tab in csseditor - this is now done always when csseditor is shown

		//fill styles with values given from server
	loadStyles();
	}


//////////////////////////////////////////////////////////////////////////////
// CSS Editor integration
//////////////////////////////////////////////////////////////////////////////

function OnCSSEditorUpdated(editfieldID,cssCode)
	{
	if( editfieldID!=null && editfieldID!='' )document.getElementById(editfieldID).value=cssCode;
	hideCSSEditor();
	}

function hideCSSEditor()
	{
	document.getElementById('cssEditor').style.display = "none";
	document.getElementById('cssManager').style.display = "";
	}
function showCSSEditor()
	{
	document.getElementById('cssEditor').style.display = "";
	document.getElementById('cssManager').style.display = "none";
	buttonClick('FontButton');
	}

function openCSSEditor(cssTag,cssClass)
	{
	var editfieldID="css_"+cssTag+"_"+cssClass;
	document.getElementById('CSSEditTargetID').value=editfieldID;

		//read out the settings (before showing the editor)
	document.getElementById('CodeTextarea').value=" x { "+document.getElementById(editfieldID).value+"; }";
	CSSEditReadoutCode();

	showCSSEditor(); //show the editor

		//read out the settings again (in some browsers the fields only get updated when they are visible :/
		// (eg. opera do not update dropdown lists correctly if not shown, so we read it out again here...)
		// (konqueror does not insert content in input fields)
	document.getElementById('CodeTextarea').value=" x { "+document.getElementById(editfieldID).value+"; }";
	CSSEditReadoutCode();
	}


//////////////////////////////////////////////////////////////////////////////
// CSS entry management
//////////////////////////////////////////////////////////////////////////////

function __setupCSSEntry(myElement,cssTag,cssClass,cssStyle)
	{
	if(myElement!=null)
		{
		myElement.id = cssTag+"Entry"+cssClass;
		myElement.style.display = "";
		myElement.childNodes[0].textContent = cssClass; //label
		myElement.childNodes[0].htmlFor = "css_"+cssTag+"_"+cssClass;
		myElement.childNodes[2].name = "css["+cssTag+"."+cssClass+"]"; //input
		myElement.childNodes[2].id = "css_"+cssTag+"_"+cssClass; //input
		myElement.childNodes[2].value = cssStyle; //input
		myElement.childNodes[4].href = "javascript: openCSSEditor('"+cssTag+"','"+cssClass+"');"; //a edit
		myElement.childNodes[6].href = "javascript: deleteCSSEntry('"+cssTag+"','"+cssClass+"');"; //a del
		}
	return myElement;
	}

function appendCSSEntry(cssTag,cssClass,cssStyle)
	{
	var existentElement = document.getElementById("css_"+cssTag+"_"+cssClass);

	if( existentElement != null )
		alert('duplicate Element '+cssTag+"."+cssClass);
	else
		{
		var myElement = document.getElementById(cssTag+"EntryTemplate").cloneNode(true);

		myElement=__setupCSSEntry(myElement,cssTag,cssClass,cssStyle);
		
		document.getElementById(cssTag+"TagHolder").appendChild(myElement);
		}
	}

function renameCSSEntry(cssTag,cssClass)
	{
	var myElement=document.getElementById("css_"+cssTag+"_"+cssClass).parentNode;

	var cssClass=promptForClassName();

	if(cssClass!=null && myElement!=null)
		__setupCSSEntry(myElement,cssTag,cssClass,cssStyle);
	}

function deleteCSSEntry(cssTag,cssClass)
	{
	var myElement=document.getElementById("css_"+cssTag+"_"+cssClass).parentNode;

	if( myElement != null && confirm("Really delete this class ("+cssTag+"."+cssClass+")?"))
		{
		myElement.parentNode.removeChild(myElement);
		}
	}

function appendNewCSSEntry(cssTag)
	{
	var cssClass=promptForClassName();
	if(cssClass!=null)appendCSSEntry(cssTag,cssClass,'');
	}

function promptForClassName()
	{
	var cssClass=prompt('New class name:','');

	if(cssClass!=null)
		{
			//either allow '#' or chars/digits only
		if(cssClass!='#')
			{
			cssClass=cssClass.replace(/[^0-9^a-z^A-Z]+/g,' '); //strip all non chars/digits to spaces

				// uppercaseFirstCharAfterSpace
			var exploded=cssClass.split(' ');
			for ( var i in exploded )
				{
					if(i>0 && exploded[i].length>0)exploded[i] = exploded[i].charAt(0).toUpperCase() + exploded[i].substring(1);
				}
			cssClass=exploded.join('');
			}
		}

	if(cssClass != null && cssClass!='')return cssClass;
	return null;
	}


//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////
