

<script type="text/javascript" language="javascript"> 

	var destMAX = 8; //maximum number of items in dest list 

	var blnDone = true;

	function inDest(dest, text, value) { 

		var opt, o = 0; 

		while (opt = dest[o++]) if (opt.value == value && opt.text == text) return true; return o > destMAX; 

	} 

	

		

	function moveSelected (select, down) { 

		if (select.selectedIndex != -1) { 

			if (down) { 

				if (select.selectedIndex != select.options.length - 1) var x = select.selectedIndex + 1; else return; 

			}else { 

				if (select.selectedIndex != 0) var x = select.selectedIndex - 1; else return; 

			} 

			var swapOption = new Object(); 

			swapOption.text = select.options[select.selectedIndex].text; 

			swapOption.value = select.options[select.selectedIndex].value; 

			swapOption.selected = select.options[select.selectedIndex].selected; 

			swapOption.defaultSelected = select.options[select.selectedIndex].defaultSelected; 

			for (var property in swapOption) select.options[select.selectedIndex][property] = select.options[x][property]; 

			for (var property in swapOption) select.options[x][property] = swapOption[property]; 

		} 

	} 



	function setHidden(f) { 

		var destVals = new Array(), opt = 0, separator = '|', d = f.dest; 

		while (d[opt]) destVals[opt] = d[opt++].value;

		f.destItems.value = separator + destVals.join(separator) + separator; 

		document.getElementById('marquepick').submit;

	} 

	

</script> 

<span class="pageHeading">Volgorde</span> <a href="index.php?page=cmsartikelen<? if ($_GET['id']) { echo "&id=".$_GET['id']; } ?>">terug naar artikelen</a><BR><BR> 

<form name="marquepick" method="POST"> 

<input type="hidden" value="1" name="save">

<input type="hidden" value="<? echo $_GET['id']; ?>" name="id">

<table cellspacing="0" cellpadding="0" style="font-family:Verdana;font-size:12">

<tr> 

<td style="padding-bottom:4px;"><B>Volgorde</td> 

<td style="padding-bottom:4px;padding-left:4px;" width=200><B>Wijzig</td> 

</tr><tr bgcolor=#FFFFFF>

<td> <select name="dest" size="8" style="width:200px;border:1px solid #FFA107;background-color:#FFFFFF;" class="select">

<? 

if ($_GET['id'] != "") {

	$query = "SELECT * FROM tbl_contents WHERE content_archive = 'F' AND content_parentid = ".$_GET['id']." ORDER BY content_seq";

} else {

	$query = "SELECT * FROM tbl_contents WHERE content_archive = 'F' AND content_parentid IS NULL ORDER BY content_seq";

}

$exec = mysql_query($query);

echo $query;

while ($row = mysql_fetch_array($exec)){

	echo "<option value=\"".$row['CONTENT_ID']."\">".$row['content_name']."</option>";

}

?>

</select> </td>

<td align="left" style="padding-left:4px;"> <input type="button" style="width:100px;" value="Omhoog" onclick="moveSelected(dest,false)" /><br /><br /> <input type="button" value="Omlaag" style="width:100px;" onclick="moveSelected(dest,true)" /><br /><br /><br /> <input type="hidden" name="destItems" /> <input type="submit" style="width:100px;" value="Opslaan" onclick="return setHidden(this.form)" /> </td>

</tr></table> 

</form> 

<iframe name="saveFrame" id="saveFrame" width="0" height="0"></iframe>

