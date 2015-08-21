<?php

class categories
{

	function add()
	{
		if (isset($_GET['id']))
		{
			echo "<span class=\"pageHeading\">Wijzigen categorie</span><BR><BR>";
		}else
		{
			echo "<span class=\"pageHeading\">Invoeren nieuwe categorie</span><BR><BR>";
		}

		$form = new FormHandler;
		$form->PermitEdit();
		$form->TableSettings('100%', 0, 1, 2, 'font-family:Verdana;font-size:10px;border: 1px solid grey');

		//$form->AddHTML($myhtml);

		// set the database data (data from another file or so ? ) 
		$form->UseDatabase(DB_DATABASE, "categories");

		$form->textfield("Naam", "categories_name", "", "50");

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
		echo "<span class=\"pageHeading\">Categorie verwijderd</span><BR>";
		mysql_query("DELETE FROM categories WHERE CATEGORIES_ID = '".$_GET['id']."'");
		$this->show();
	}

	function contents()
	{
		$query = "SELECT * FROM categories ORDER BY categories_name";
		$exec = mysql_query($query);
		$categories = Array();
		if (mysql_num_rows($exec)>0)
		{
			while ($row = mysql_fetch_array($exec))
			{
				$categories[$row['categories_id']] = $row['categories_name'];
			}
		}
		return $categories;
	}

	function show()
	{
		if ($_GET['order'] == "" || $order == "ASC"){
			$order = "DESC";
		}else{
			$order = "ASC";
		}
		if (!isset($_GET['orderby']) || $_GET['orderby'] == ""){
			$orderby = "categories_name";
		}else{
			$orderby = $_GET['orderby'];
		}


		if (isset($_GET['search']) && $_GET['search'] != ""){
			$query = "SELECT * FROM categories WHERE categories_name LIKE '%".$_GET['search']."%' ";
		}else{
			$query = "SELECT * FROM categories ";
		}
		$exec = mysql_query($query);
		$totaalAantal = mysql_num_rows($exec);

		echo "<span class=\"pageHeading\">Categorie&euml;n (".$totaalAantal.")</span><BR>";

		echo "<TABLE cellpadding='0' cellspacing='1' width='100%' style='font-family:verdana;font-size:10'>";
		echo "<TR><TD>[ <a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=add&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."&catid=".$_GET['catid']."><img src=".DIR_WS_IMAGES."/cms/add.gif border='0' alt='Categorie toevoegen' title='Categorie toevoegen'></a> ";

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
			$query = "SELECT * FROM categories WHERE categories_name LIKE '%".$_GET['search']."%'  ORDER BY ".$orderby." ".$order." LIMIT ".(int)$begin.",".(int)$aantal;
		}else{
			$query = "SELECT * FROM categories  ".$catwhere." ORDER BY ".$orderby." ".$order." LIMIT ".(int)$begin.",".(int)$aantal;
		}

		$exec = mysql_query($query) or die($query);
		echo "<TABLE cellpadding=2 cellspacing=0 style=\"font-family:Verdana\" width=100%>";
		echo "<TR class=\"dataTableHeadingRow\"><TD class=\"dataTableHeadingContent\"><a href=\"producten.php?page=".$_GET['page']."&recs=".$_GET['recs']."&orderby=categories_name&order=".$order."&search=".$_GET['search']."&catid=".$_GET['catid']."\" style=\"font-weight:bold;color:white\">Naam</a></TD><TD class=\"dataTableHeadingContent\">&nbsp</TD></TR>";
		if (mysql_num_rows($exec)>0)
		{
			while ($row = mysql_fetch_array($exec))
			{
				extract($row);
				

				echo "<TR onMouseOver=\"this.bgColor='#C0C0C0';this.style.cursor='hand';this.style.color='white';\" onMouseOut=\"this.bgColor='white';this.style.color='black';\" style=\"font-size:10\">";
				
				echo "<TD><a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&id=".$categories_id."&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."\">".$categories_name."</a></TD>";
				echo "<TD align=right><NOBR>[ <a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&id=".$categories_id."&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&order=".$_GET['order']."\"><img src=".DIR_WS_IMAGES."/cms/edit.gif border='0' alt='bewerken' title='bewerken'></a>&nbsp;<a href=\"#\" onClick=\"confirm_del('index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=remove&id=".$categories_id."','".str_replace("\"", "",str_replace("'","",$categories_name))."'); return false\"><img src=".DIR_WS_IMAGES."/cms/remove.gif border='0' alt='verwijder' title='verwijder'></a> ]</NOBR></TD>";
				echo "</TR>";
			}
		}else{
			echo "<TR style=\"font-size:10\"><TD colspan=7 align=center>Nog geen categorieen toegevoegd!</TD></TR>";
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