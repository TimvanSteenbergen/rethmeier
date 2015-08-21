<?php

class products
{
	var $parent;
	
	function products($aParent)
	{
		$this->parent = $aParent;
	}

	function add()
	{



	include(DIR_WS_FUNCTIONS.'/browser_detection.php');
	$a_browser_data = browser_detection('full');

//	print_r($a_browser_data);

	if ( $a_browser_data[0] == 'moz' )
	{
		echo "<script language=\"JavaScript\" type=\"text/JavaScript\" src=\"../js/media_ff.js\"></script>";
	}
	else// if it is msie, that is
	{
		echo "<script language=\"JavaScript\" type=\"text/JavaScript\" src=\"../js/media.js\"></script>";
	}
?>
	<script type="text/javascript" src="../js/embed-object.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="../js/swfobject.js"></script>
	<script language="JavaScript">
	function setImage(image){
	//	document.getElementById('PICTURE').value = image.substr(2,image.length);
	}
	function setMovie(movie, id){
		if (id == 1){
			document.getElementById('SOURCE_FILE').value = movie;
		}
		if (id == 2){
		//	document.getElementById('SOURCE_FILE2').value = movie;		
		}
		if (id == 3){
		//	document.getElementById('SOURCE_FILE3').value = movie;		
		//	document.getElementById('SOURCE_FILE2').value = movie.replace("600","2000");
			document.getElementById('SOURCE_FILE').value = movie.replace("600","1000");
		}
	}
	function setTrailer(trailer){
	//	document.getElementById('TRAILER').value = trailer.substr(2,trailer.length);
	}
	</script>
<?php

	if (isset($_GET['id']))
	{
		echo "<span class=\"pageHeading\">Wijzigen product</span><BR><BR>";
	}else
	{
		echo "<span class=\"pageHeading\">Invoeren nieuw product</span><BR><BR>";
	}
	$form = new FormHandler;
	$form->PermitEdit();
	$form->TableSettings('100%', 0, 1, 2, 'font-family:Verdana;font-size:10px;border: 1px solid grey');

	//$form->AddHTML($myhtml);

	// set the database data (data from another file or so ? ) 
	$form->UseDatabase(DB_DATABASE, "products");

	$form->textfield("Titel", "TITLE", "", "50");

	$form->TextArea("Synopsis algemeen", "SHORT_SYNOPSIS", "", 47);

	//$form->textfield("<a href=# onclick=window.open(\"./mftp/index.php?film=1&currentDir=voetbal_supporter\",\"film\",\"width=560,height=350,left=250,top=200\")><U>Film locatie - 1Mbps</U></a>:", "SOURCE_FILE", "", "50");
	//$form->textfield("ContentID 1Mbps", "CONTENTID", "", "5");

	if($this->parent->module_exists("categories"))
	{
		$categories = $GLOBALS["categories"]->contents();
		$form->SelectField("Categorie", "CATEGORY_ID", $categories, true);
	}
	//reset
	if (isset($_GET['id']))
	{
		$query = "SELECT source_file,  TITLE FROM products WHERE products.product_id = '".$_GET['id']."'";
		$exec2 = mysql_query($query);

		$row2 = mysql_fetch_array($exec2);
		mysql_data_seek($exec2, 0);
	

		$popupHtml = "";
		$image_url = "../images/screens/";
		$image_name = $row2['source_file'];
		$image_name = substr($image_name, 0, strlen($image_name)-4);

		$popupHtml .= "<tr><td colspan=4><table cellspacing=\"2\" cellpadding=\"0\"><tr>";
		for ($x = 0; $x < 5; $x++)
		{
			$popupHtml .= "<td height=\"100\" align=\"center\" valign=\"middle\">";

			$image = $image_name."_".($x + 1).".jpg";

			$popupHtml .= "<img src=\"".$image_url . $image. "\" width=\"100\" border=\"0\">";
			$popupHtml .= "</td>";


		}
		$popupHtml .= "</tr></table></td></tr>";

		$sourcefile = "http://217.114.103.140/content/bbaltv/".$row2['source_file'];
		$flash = str_replace(".wmv", ".flv", $row2['source_file']);
		$flash_sourcefile = "http://217.114.103.140/content/bbaltv/".$flash; 
		$image = "http://www.bbal.tv/images/screens/".$_GET['id']."_2.jpg";

		if(file_exists($flash_sourcefile))
		{
			
			$popupHtml .= "<tr>";

			$popupHtml .= "<td colspan=3 width=\"390\" align=\"left\"><p id=\"flvplayer\"><a href=\"http://www.macromedia.com/go/getflashplayer\">Get the Flash Player</a> to see this player.</p>";

			$popupHtml .= "<script type=\"text/javascript\">";
				$popupHtml .= "var so = new SWFObject('../flash/flvplayer.swf','player','390','290','7');";
				$popupHtml .= "so.addParam(\"allowfullscreen\",\"true\");";
				$popupHtml .= "so.addVariable(\"file\",\"".$flash_sourcefile."\");";
				$popupHtml .= "so.addVariable(\"image\", \"".$image."\");";
				$popupHtml .= "so.addVariable(\"displayheight\",\"290\");";
				$popupHtml .= "so.addVariable(\"lightcolor\",\"0x557722\");";
				$popupHtml .= "so.addVariable(\"backcolor\",\"0x363795\");";
				$popupHtml .= "so.addVariable(\"frontcolor\",\"0xCCCCCC\");";
				$popupHtml .= "so.write('flvplayer');";
			$popupHtml .= "</script>";

			$popupHtml .= "</td></tr>";

		}
		$form->AddHTML($popupHtml);
	}

	if($this->parent->module_exists("upload"))
	{
		
		$files = $GLOBALS["upload"]->contents();
		array_unshift($files, "");
		$form->SelectField("Uploaded file", "UPLOAD_FILE", $files, true);
		//print_r($files);
//		$form->AddHTML($uploadHtml);
	}


	if (strpos(" ".$_SERVER['QUERY_STRING'], "opmerking=1"))
	{
		$form->hiddenField("VISIBLE", "T");
	}
	$form->AddHTML("<tr><td colspan=\"3\">&nbsp;</td></tr>");

	$form->SubmitBtn("Opslaan", false, "Annuleren");

	$form->OnSaved("doRun");

	// flush the form
	$form->FlushForm();

	}

	function edit()
	{
		$this->add();
	}


	function remove()
	{
		echo "<span class=\"pageHeading\">Product verwijderd</span><BR>";
		mysql_query("UPDATE products set ARCHIVE = 'T' WHERE PRODUCT_ID = '".$_GET['id']."'");
		$this->show();
	}

	function setActief()
	{
		if ($_GET["act"] == 2) 
		{
			$sql = "UPDATE products SET VISIBLE = 'F' WHERE PRODUCT_ID = " . $_GET["id"];
		}else
		{
			$sql = "UPDATE products SET VISIBLE = 'T' WHERE PRODUCT_ID = " . $_GET["id"];
		}
		$result = mysql_query($sql); 

		$this->show();
	}

	function show()
	{
		if ($_GET['order'] == "" || $order == "ASC"){
			$order = "DESC";
		}else{
			$order = "ASC";
		}
		if (!isset($_GET['orderby']) || $_GET['orderby'] == ""){
			$orderby = "TIMESTAMP";
		}else{
			$orderby = $_GET['orderby'];
		}
		if ($_GET['catid'] != ""){
			$catwhere = "AND CATEGORY_ID = ".$_GET['catid'];
		}else{
			$catwhere = "";
		}

		if (isset($_GET['search']) && $_GET['search'] != ""){
			$query = "SELECT *, DATE_FORMAT(AVAILABLE_FROM, '%d-%m-%Y') AS FROM_DATE, DATE_FORMAT(AVAILABLE_UNTIL, '%d-%m-%Y') AS UNTIL_DATE FROM products LEFT JOIN categories ON products.CATEGORY_ID = categories.categories_id WHERE products.ARCHIVE = 'F' AND (TITLE LIKE '%".$_GET['search']."%' OR categories_name LIKE '%".$_GET['search']."%' )";
		}else{
			$query = "SELECT * FROM products LEFT JOIN categories ON products.CATEGORY_ID = categories.categories_id WHERE products.ARCHIVE = 'F' ".$catwhere;
		}
		$exec = mysql_query($query);
		$totaalAantal = mysql_num_rows($exec);

		echo "<span class=\"pageHeading\">Producten (".$totaalAantal.")</span><BR><BR>";

		echo "<TABLE cellpadding='0' cellspacing='1' width='100%' style='font-family:verdana;font-size:10'>";
		echo "<TR><TD>[ <a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=add&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."&catid=".$_GET['catid']."><img src=".DIR_WS_IMAGES."/cms/add.gif border='0' alt='Product toevoegen' title='Product toevoegen'></a> ";

		//echo "&nbsp; <a href=productorder.php?category=".$_GET['catid']."><img src=".DIR_WS_IMAGES."/cms/order.gif border='0' alt='Product volgorde' title='Product volgorde'></a> ]</TD>";

		echo "<td align=middle>";
		echo "<img src=".DIR_WS_IMAGES."/cms/search.gif border='0' alt='Zoek' title='Zoek'>&nbsp;";
		echo "<form method=get>";
		echo "<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">";
		echo "<input type=\"hidden\" name=\"m\" value=\"".$_GET['m']."\">";
		echo "<input type=\"hidden\" name=\"order\" value=\"".$_GET['order']."\">";
		echo "<input type=\"hidden\" name=\"orderby\" value=\"".$_GET['orderby']."\">";
		echo "<input type=\"hidden\" name=\"recs\" value=\"".$_GET['recs']."\">";
		echo "<input type=text name=search>&nbsp<input type=submit value=zoek>";
		echo "</form>";
		echo "</td>";
		echo "<TD align=right>";
		//echo "[ <a href=\"#\" onClick=\"printFrame.location='printproducts.php'\"><img src=".DIR_WS_IMAGES."/cms/print.gif border='0' alt='afdrukken' title='afrukken'></a> ";
		//echo "&nbsp; <a href=\"exceloutputproducts.php?order=".$order."&orderby=".$orderby."&catid=".$catid."&search=".$_GET['search']."\"><img src=".DIR_WS_IMAGES."/cms/save.gif border='0' alt='opslaan' title='opslaan'></a> ]";
		echo "</TD>";
		echo "</TR></TABLE>";
		echo "<BR>";

		//echo "<BR><BR>";
		if ($_GET['pagina']!= ""){
			$begin = ($_GET['pagina']-1) * $_GET['recs'];
			$aantal = $_GET['recs'];
		}else{
			$begin = 0;
			$aantal = 50;
		}
		if (isset($_GET['search']) && $_GET['search'] != ""){
			$query = "SELECT *,  DATE_FORMAT(AVAILABLE_FROM, '%d-%m-%Y') AS FROM_DATE, DATE_FORMAT(AVAILABLE_UNTIL, '%d-%m-%Y') AS UNTIL_DATE FROM products  LEFT JOIN categories ON products.CATEGORY_ID = categories.categories_id WHERE products.ARCHIVE = 'F' AND (TITLE LIKE '%".$_GET['search']."%' OR categories_name LIKE '%".$_GET['search']."%' ) group by products.PRODUCT_ID ORDER BY ".$orderby." ".$order." LIMIT ".(int)$begin.",".(int)$aantal;
		}else{
			$query = "SELECT *,  DATE_FORMAT(AVAILABLE_FROM, '%d-%m-%Y') AS FROM_DATE, DATE_FORMAT(AVAILABLE_UNTIL, '%d-%m-%Y') AS UNTIL_DATE FROM products  LEFT JOIN categories ON products.CATEGORY_ID = categories.categories_id WHERE products.ARCHIVE = 'F' ".$catwhere." group by products.PRODUCT_ID ORDER BY ".$orderby." ".$order." LIMIT ".(int)$begin.",".(int)$aantal;
		}

		$exec = mysql_query($query) or die($query);
		echo "<TABLE cellpadding=2 cellspacing=0 style=\"font-family:Verdana\" width=100%>";
		echo "<TR class=\"dataTableHeadingRow\">";
		echo "<TD class=\"dataTableHeadingContent\"><a href=\"producten.php?page=".$_GET['page']."&recs=".$_GET['recs']."&orderby=TITLE&order=".$order."&search=".$_GET['search']."&catid=".$_GET['catid']."\" style=\"font-weight:bold;color:white\">Titel</a></TD>";
		echo "<TD class=\"dataTableHeadingContent\"><a href=\"producten.php?page=".$_GET['page']."&recs=".$_GET['recs']."&orderby=PRODUCT_ID&order=".$order."&search=".$_GET['search']."&catid=".$_GET['catid']."\" style=\"font-weight:bold;color:white\">Product ID</A></TD>";
		if($this->parent->module_exists("categories"))
		{
			echo  "<TD class=\"dataTableHeadingContent\"><a href=\"producten.php?page=".$_GET['page']."&recs=".$_GET['recs']."&orderby=categories_name&order=".$order."&search=".$_GET['search']."&catid=".$_GET['catid']."\" style=\"font-weight:bold;color:white\">Categorie</a></TD>";
		}
		echo "<TD class=\"dataTableHeadingContent\"><a href=\"producten.php?page=".$_GET['page']."&recs=".$_GET['recs']."&orderby=TIMESTAMP&order=".$order."&search=".$_GET['search']."&catid=".$_GET['catid']."\" style=\"font-weight:bold;color:white\">Beschikbaar</A></TD>";
		echo "<TD class=\"dataTableHeadingContent\"><a href=\"producten.php?page=".$_GET['page']."&recs=".$_GET['recs']."&orderby=VISIBLE&order=".$order."&search=".$_GET['search']."&catid=".$_GET['catid']."\" style=\"font-weight:bold;color:white\">Actief</A></TD>";
		if($this->parent->module_exists("distributions"))
		{
			$distribution_ids = $GLOBALS["distributions"]->contents();

			for($x = 0; $x < count($distribution_ids); $x++)
			{
				echo "<TD class=\"dataTableHeadingContent\">".$distribution_ids[$x][1]."</TD>";
			}
		}
		echo "<TD class=\"dataTableHeadingContent\">&nbsp</TD></TR>";
		if (mysql_num_rows($exec)>0)
		{
			while ($row = mysql_fetch_array($exec))
			{
				extract($row);
				
				if ($VISIBLE == 'T') {
					$tabgreen = "<img src=".DIR_WS_IMAGES."/cms/green_active.gif border=0>";
				}else{	
					$tabgreen = "<a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&id=".$PRODUCT_ID."&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."&catid=".$_GET['catid']."&mf=setActief&act=1\"><img src=".DIR_WS_IMAGES."/cms/green_non_active.gif border=0></a>";
				}
				if ($VISIBLE == 'F') {
					$tabred = "<img src=".DIR_WS_IMAGES."/cms/red_active.gif border=0>";
				}else{	
					$tabred = "<a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&id=".$PRODUCT_ID."&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."&catid=".$_GET['catid']."&mf=setActief&act=2\"><img src=".DIR_WS_IMAGES."/cms/red_non_active.gif border=0></a>";
				}
				$aantal = 6 - strlen($PRODUCT_ID);
				$prodcode = $PRODUCT_ID;
				for ($x=1;$x<=$aantal;$x++){
					$prodcode = "0".$prodcode;
				}
				echo "<TR onMouseOver=\"this.bgColor='#C0C0C0';this.style.cursor='hand';this.style.color='white';\" onMouseOut=\"this.bgColor='white';this.style.color='black';\" style=\"font-size:10\">";
				
				echo "<TD><a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&id=".$PRODUCT_ID."&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."&catid=".$_GET['catid']."\">".$TITLE."</a></TD>";
				echo "<TD><a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&id=".$PRODUCT_ID."&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."&catid=".$_GET['catid']."\">".$prodcode."</a></TD>";
				if($this->parent->module_exists("categories"))
				{
					echo "<TD><a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=show&catid=".$CATEGORY_ID."\">".$categories_name."</a></TD>";
				}
				if($this->parent->module_exists("upload"))
				{
				}
				echo "<TD>".date('Y-m-d', $TIMESTAMP)."</TD>";
				echo "<TD>".$tabgreen."&nbsp;".$tabred."</TD>";
				if($this->parent->module_exists("distributions"))
				{
					//add distribution data
					$distribution_cells = "";

					$distribution_ids = $GLOBALS["distributions"]->contents();

					for($x = 0; $x < count($distribution_ids); $x++)
					{
						$statusresult = mysql_query("SELECT * FROM product_description WHERE PRODUCT_ID = '".$PRODUCT_ID."' AND DISTRIBUTION_ID = '".$distribution_ids[$x][0]."'")or die(mysql_error());
						//echo mysql_num_rows($statusresult);
						if($statusrow = mysql_fetch_array($statusresult))
						{
							switch($statusrow['product_status'])
							{
								
								case 0://unknown
									$distribution_cells .= "<TD><img src=".DIR_WS_IMAGES."/cms/status_orange.gif  alt='onbekend' title='onbekend'></TD>";
								break;
								case 1://success
									$distribution_cells .= "<TD><img src=".DIR_WS_IMAGES."/cms/status_blue.gif  alt='succes' title='succes'></TD>";
								break;
								case 2://failure
									$distribution_cells .= "<TD><img src=".DIR_WS_IMAGES."/cms/status_red.gif  alt='mislukt' title='mislukt'></TD>";
								break;	
							}
						}
					}					
					echo $distribution_cells;
				}				
				echo "<TD align=right><NOBR>[ <a href=\"#\" onClick=\"confirm_encode('encode.php?id=".$PRODUCT_ID."','".str_replace("\"", "",str_replace("'","",$TITLE))."'); return false\"><img src=".DIR_WS_IMAGES."/cms/re-encode.gif border='0' alt='Encodeer opnieuw' title='Encodeer opnieuw'></a>&nbsp;<a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&id=".$PRODUCT_ID."&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."&catid=".$_GET['catid']."\"><img src=".DIR_WS_IMAGES."/cms/edit.gif border='0' alt='bewerken' title='bewerken'></a>&nbsp;<a href=\"#\" onClick=\"confirm_del('index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=remove&id=".$PRODUCT_ID."&catid=".$_GET['catid']."','".str_replace("\"", "",str_replace("'","",$TITLE))."'); return false\"><img src=".DIR_WS_IMAGES."/cms/remove.gif border='0' alt='verwijder' title='verwijder'></a> ]</NOBR></TD>";
				echo "</TR>";
			}
		}else{
			echo "<TR style=\"font-size:10\"><TD colspan=7 align=center>Nog geen producten toegevoegd!</TD></TR>";
		}
		echo "</TABLE>";
		?>
		<BR>
		<form name="myform" method="get" action="">
		<input type=hidden name=page value="<? echo $_GET['page']; ?>">
		<input type=hidden name=m value="<? echo $_GET['m']; ?>">
		<input type=hidden name=search value="<? echo $_GET['search']; ?>">
		<input type=hidden name=orderby value="<? echo $_GET['orderby']; ?>">
		<input type=hidden name=catid value="<? echo $_GET['catid']; ?>">
		<? 
		if (isset($_GET['order']))
		{
		?>
			<input type=hidden name=order value="<? echo $_GET['order']; ?>">
		<? 
		}
		else
		{ 
		?>
			<input type=hidden name=order value="DESC">
		<? 
		} 
		?>

		<font face=verdana size=1>Toon <select class="fields" name=recs OnChange="updateBoxes(myform)">
			<option value="5" <? if ($_GET['recs'] == 5) echo "selected"?>> 5</option>
			<option value="10" <? if ($_GET['recs'] == 10) echo "selected"?>> 10</option>
			<option value="20" <? if ($_GET['recs'] == 20) echo "selected"?>> 20</option>
			<option value="50" <? if ($_GET['recs'] == 50) echo "selected"?>> 50</option>
			<option value="100" <? if ($_GET['recs'] == 100) echo "selected"?>> 100</option>
		</select> resultaten per pagina. &nbsp;
	
	
		<font face=verdana size=1>Toon pagina <select class="fields" name="pagina"><option></option><option></option><option></option><option></option><option></option><option></option><option></option><option></option></select>
		<input class="buttons" type=submit value="Toon">
		</form>
		<script language="JavaScript">
		NUM_RECORDS = <?=$totaalAantal ?>;
		function updateBoxes(theFormObj)
			{
				var selectedRecs = theFormObj.recs.options[theFormObj.recs.selectedIndex].value;
				var numpages = Math.ceil(NUM_RECORDS / selectedRecs);
				var numOptions = theFormObj.pagina.length;
				for(var i=0 ; i<numOptions ; i++)  {
					theFormObj.pagina.options[0] = null; 
				}
				for(var j=0 ; j<numpages ; j++)   {
					theFormObj.pagina.options[j] = new Option(j+1,j+1);
				}
				<? if (!isset($_GET['page'])){ ?>
					theFormObj.pagina.selectedIndex = 0;
				<? }else{ ?>
					theFormObj.pagina.selectedIndex = <? echo $_GET['pagina'] - 1; ?>;
				<? } ?>
			}
		</script>
		<script> 
		  updateBoxes(document.myform);
		</script>
<?
	}

}
?>