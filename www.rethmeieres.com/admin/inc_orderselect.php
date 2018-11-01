<?php
  $error = false;
  
if (isset($_POST['save'])){
	$array = explode("|",$_POST['destItems']);
	for ($x=1;$x<count($array)-1;$x++){
		$query = "UPDATE TBL_CONTENTS SET content_seq = ".$x." WHERE CONTENT_ID = ".$array[$x];
		$save = mysqli_query($query);		
	}
	$to = "index.php?page=cmsartikelen";
	?><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?php echo $to; ?>"><?php
	return 0;
}
?>
<h1>Bepaal volgorde</h1> 
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
<style>
	A:visited { color: #000000; font-size:10; font-family: Verdana, Arial, Helvetica, sans-serif; text-decoration: none; }
	A:hover {color: #FF6600; font-size:10; font-family: Verdana, Arial, Helvetica, sans-serif; text-decoration: underline;}
	A:active {color: #FF6600; font-size:10; font-family: Verdana, Arial, Helvetica, sans-serif; text-decoration: none;}
	inputCheckBox {font-family: Verdana;color: #000000;font-size: 10px;}
	input {font-family: Verdana;color: #000000;font-size: 10px;border-width: 1;border-color: #000000;border-style: solid;background-color: #EEEEEE;}
	select {font-family: Verdana;color: #000000;font-size: 10px;border-width: 1;border-color: #000000;border-style: solid;background-color: #EEEEEE;}
	textarea {font-family: Verdana;color: #000000;font-size: 10px;border-width: 1;border-color: #000000;border-style: solid;background-color: #EEEEEE;}
</style>
</head> 
<body>
<form name="marquepick" method="POST"> 
<input type="hidden" value="1" name="save">
<input type="hidden" value="<?php echo $_GET['ID']; ?>" name="ID">
<table cellspacing="1" cellpadding="2" bgcolor="#EEEEEE" style="font-family:Verdana;font-size:10">
<tr bgcolor="#FFFFFF" class="dataTableHeadingRow" style="font-weight:bold;color:white"> 
<td><B>Volgorde</td> 
<td width=200><B>Wijzig</td> 
</tr><tr bgcolor="#FFFFFF">
<td> <select name="dest" size="20" style="width:230px;" class="select">
<?php
$query = "SELECT * FROM TBL_CONTENTS WHERE content_type = ".$_GET['ID']." AND content_archive = 'F' AND content_parentid IS NULL ORDER BY content_seq";
$exec = mysqli_query($query);
while ($row = mysqli_fetch_array($exec)){
	echo "<option value=\"".$row['CONTENT_ID']."\">".$row['content_name']."</option>";
}
?>
</select> </td>
<td align="center"> <input type="button" value="omhoog" onClick="moveSelected(dest,false)" /><br /><br /> <input type="button" value="omlaag" onClick="moveSelected(dest,true)" /><br /><br /> </td>
</tr><tr bgcolor="#FFFFFF">
<td align="middle" colspan=2> <input type="hidden" name="destItems" /> <input type="submit" value="Opslaan" onClick="return setHidden(this.form)" /> </td>
</tr></table> 
</form> 
<iframe name="saveFrame" id="saveFrame" width="0" height="0"></iframe>
</body> 
</html>