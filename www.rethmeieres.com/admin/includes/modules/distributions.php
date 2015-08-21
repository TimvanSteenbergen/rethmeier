<?php
class distributions
{
	var $parent;
	var $db_transcoder;
	
	function distributions($aParent)
	{
		$this->parent = $aParent;
		$this->db_transcoder = mysql_connect(MYRIAD_DATABASE_HOSTNAME, MYRIAD_DATABASE_USERNAME, MYRIAD_DATABASE_PASSWORD);
	}

	function addOutputFormat()
	{
		if (isset($_GET['id']))
		{
			echo "<span class=\"pageHeading\">Wijzigen output formaat</span><BR><BR>";
		}else
		{
			echo "<span class=\"pageHeading\">Invoeren nieuw output formaat</span><BR><BR>";
		}

		$form = new FormHandler;
		$form->PermitEdit();
		$form->TableSettings('100%', 0, 1, 2, 'font-family:Verdana;font-size:10px;border: 1px solid grey');

		//$form->AddHTML($myhtml);

		// set the database data (data from another file or so ? ) 
		$form->UseDatabase(MYRIAD_DATABASE_NAME, "outputformat");

		$form->textfield("Naam", "naam", "" , "50");
		$form->textfield("Breedte", "width", "" , "50");
		$form->textfield("Hoogte", "height", "" , "50");
		$form->textfield("Extensie", "format", "" , "50");
		$form->textfield("Profiel", "profile", "" , "50");
		$form->textfield("Bitrate", "bitrate", "" , "50");

		

		$form->SubmitBtn("Opslaan", false, "Annuleren");

		$form->OnSaved("doRun");

		// flush the form
		$form->FlushForm();
	}

	function editOutputFormat()
	{
		$this->addOutputFormat();
	}

	function removeOutputFormat()
	{

		//$db_transcoder = mysql_connect(MYRIAD_DATABASE_HOSTNAME, MYRIAD_DATABASE_USERNAME, MYRIAD_DATABASE_PASSWORD); 
		mysql_select_db(MYRIAD_DATABASE_NAME, $this->db_transcoder)or die(mysql_error());
		mysql_query("DELETE FROM OUTPUTFORMAT where id = '".$_GET['id']."'");
		echo "<span class=\"pageHeading\">Output Format removed.</span><BR><BR>";

		$this->showOutputFormats();
	}

	function showOutputFormats()
	{
		if (!isset($_GET['order']) || $order == "DESC"){
			$order = "ASC";
		}else{
			$order = "DESC";
		}
		if (!isset($_GET['orderby']) || $_GET['orderby'] == ""){
			$orderby = "NAAM";
		}else{
			$orderby = $_GET['orderby'];
		}


		//$db_transcoder = mysql_connect(MYRIAD_DATABASE_HOSTNAME, MYRIAD_DATABASE_USERNAME, MYRIAD_DATABASE_PASSWORD); 
		mysql_select_db(MYRIAD_DATABASE_NAME, $this->db_transcoder)or die(mysql_error());

		$result = mysql_query("select * from customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' ") or die(mysql_error());
		$row = mysql_fetch_array($result);

		if (isset($_GET['search']) && $_GET['search'] != ""){
			if (ereg(" ", $_GET['search'])){
				$search = explode(" ", $_GET['search']);
				$titel = $search[0];
				$query = "SELECT * FROM OUTPUTFORMAT WHERE   (NAAM LIKE '%".$titel."%' ) ORDER BY ".$orderby." ".$order;
			}
			else{
			$query = "SELECT * FROM OUTPUTFORMAT WHERE  (NAAM LIKE '%".$_GET['search']."%' OR SURNAME LIKE '%".$_GET['search']."%') ORDER BY ".$orderby." ".$order;
			}
		}else{
			$query = "SELECT * FROM OUTPUTFORMAT  ORDER BY NAAM";
		}
		$exec = mysql_query($query)or die(mysql_error());
		$totaalAantal = mysql_num_rows($exec);

		echo "<span class=\"pageHeading\">Output Formaten (".$totaalAantal.")</span><BR><BR>";

		echo "<a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=show".">Terug naar distributies</a><BR><BR>";

		echo "<a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&mf=addOutputFormat"."><img src=".DIR_WS_IMAGES."/cms/add.gif border='0' alt='toevoegen' title='toevoegen'></a><BR><BR>";

		echo "<form method=get>";
		echo "<input type=text name=search>&nbsp<input type=submit value=zoek>";
		echo "</form><BR><BR>";

		if ($_GET['pagina']!= "")
		{
			$begin = ($_GET['pagina']-1) * $_GET['recs'];
			$aantal = $_GET['recs'];
		}else
		{
			$begin = 0;
			$aantal = 50;
		}
			if (isset($_GET['search']) && $_GET['search'] != "")
			{
				if (ereg(" ", $_GET['search'])){
					$search = explode(" ", $_GET['search']);
					$titel = $search[0];
					$query = "SELECT * FROM OUTPUTFORMAT WHERE NAAM LIKE '%".$titel."%' ORDER BY ".$orderby." ".$order;
				}
				else{
				$query = "SELECT * FROM OUTPUTFORMAT WHERE NAAM LIKE '%".$_GET['search']."%' ORDER BY ".$orderby." ".$order;
				}
			}else{
				$query = "SELECT * FROM OUTPUTFORMAT ORDER BY ".$orderby." ".$order." LIMIT ".$begin.",".$aantal;
			}
			$exec = mysql_query($query);
			echo "<TABLE cellpadding=2 cellspacing=0 style=\"font-family:Verdana\" width=100%>";
			echo "<TR class=\"dataTableHeadingRow\"><TD class=\"dataTableHeadingContent\">Naam</TD><TD class=\"dataTableHeadingContent\">Width</TD><TD class=\"dataTableHeadingContent\">height</TD><TD class=\"dataTableHeadingContent\">Extension</TD><TD class=\"dataTableHeadingContent\">Bitrate</TD><TD class=\"dataTableHeadingContent\">&nbsp</TD></TR>";
			if (mysql_num_rows($exec) > 0){
				while ($row = mysql_fetch_array($exec)){
					extract($row);
						echo "<TR onMouseOver=\"this.bgColor='#C0C0C0';this.style.cursor='hand';this.style.color='white';\" onMouseOut=\"this.bgColor='white';this.style.color='black';\" style=\"font-size:10\"><TD><a href=new_output_formats.php?id=".$id.">".$naam."</a></TD><TD>".$width."</TD><TD>".$height."</TD><TD>".$format."</TD><TD>".$bitrate."</TD><TD align=right>[ <a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&mf=editOutputFormat&id=".$id."><img src=".DIR_WS_IMAGES."/cms/format_edit.gif border='0' alt='Formaat wijzigen' title='Formaat wijzigen'></a> &nbsp; <a href=# onClick=\"confirm_del('index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&mf=removeOutputFormat&id=".$id."','".addslashes($naam)."'); return false\"><img src=".DIR_WS_IMAGES."/cms/format_remove.gif border='0' alt='Format verwijderen' title='Formaat verwijderen'></a> ]</TD></TR>";
				}
			}else{

				echo "<TR style=\"font-size:10\"><TD colspan=7 align=center>Nog geen formaten ingevoerd!</TD></TR>";

			}
			echo "</TABLE>";
		?>
		<BR>
		<form name="myform" method="get" action="">
		<input type=hidden name=page value="<? echo $_GET['page']; ?>">
		<input type=hidden name=m value="<? echo $_GET['m']; ?>">
		<input type=hidden name=mf value="<? echo $_GET['mf']; ?>">
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

	function add()
	{
		if (isset($_GET['id']))
		{
			echo "<span class=\"pageHeading\">Wijzigen distributie</span><BR><BR>";
		}else
		{
			echo "<span class=\"pageHeading\">Invoeren nieuwe distributie</span><BR><BR>";
		}

		$form = new FormHandler;
		$form->PermitEdit();
		$form->TableSettings('100%', 0, 1, 2, 'font-family:Verdana;font-size:10px;border: 1px solid grey');

		//$form->AddHTML($myhtml);

		// set the database data (data from another file or so ? ) 
		$form->UseDatabase(MYRIAD_DATABASE_NAME, "customers");

		$form->textfield("Naam", "name", "" , "50");

		$form->HiddenField("srcHost", TRANSCODER_HOSTNAME);
		$form->HiddenField("srcUsername", TRANSCODER_USERNAME);
		$form->HiddenField("srcPassword", TRANSCODER_PASSWORD);

		$form->HiddenField("srcFolder", TRANSCODER_SRC_FOLDER);

		$form->HiddenField("destHost", MEDIASERVER_HOSTNAME);
		$form->HiddenField("destUsername", MEDIASERVER_USERNAME);
		$form->HiddenField("destPassword", MEDIASERVER_PASSWORD);

		$form->textfield("Doelmap", "destFolder", "", "50");

		$query = "SELECT * FROM OUTPUTFORMAT";
		$exec = mysql_query($query);
		$teller = 0;
		while ($row = mysql_fetch_array($exec))
		{
			$valueArray[$row['id']] = $row['naam'];	
		}

		$form->SelectField("Output Formaat", "outputFormatId", $valueArray, true);


		$form->HiddenField("conversionProgramId", "0");
		$form->HiddenField("pause", "10");
		$form->textfield("Aantal Screenshots", "numberOfStills", "" , "50");



		$form->CheckBox("DRM Beveiliging", "drmProtection");

		$form->textfield("URL Gelukt", "successURL", "" , "50");
		$form->textfield("URL Mislukt", "failureURL", "" , "50");

		if($this->parent->module_exists("brandmerk"))
		{
//			$brandmerks = $GLOBALS["brandmerk"]->contents();

			$conn_id = ftp_connect(TRANSCODER_HOSTNAME);

			// login with username and password
			$login_result = ftp_login($conn_id, TRANSCODER_USERNAME, TRANSCODER_PASSWORD);

			$valueArray = array();
			$valueArray[] = "Geen brandmerk";
			$img_array = ftp_nlist ( $conn_id, TRANS_IMAGES_DIR );
			for($x = 0; $x < count($img_array); $x++)
			{
				$valueArray[$img_array[$x]] = $img_array[$x];
			}
			ftp_close($conn_id);
			$form->SelectField("Brandmerk", "companyLogo", $valueArray, true);
		}
	
		if($this->parent->module_exists("betalingen"))
		{
			$form->SelectField("Betaal categorie", "paymentCategoryId", $paymentarray, true);
		}

		$valueArray = array();

		$valueArray[1] = "Ja";
		$valueArray[0] = "Nee";

		$form->SelectField("Bron opschonen", "clean", $valueArray, true);

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
		mysql_select_db(MYRIAD_DATABASE_NAME, $this->db_transcoder)or die(mysql_error());
		mysql_query("DELETE FROM customers where id = '".$_GET['id']."'");
		echo "<span class=\"pageHeading\">Distribution removed.</span><BR><BR>";

		$this->show();
	}

	function setActief()
	{
		//$db_transcoder = mysql_connect(MYRIAD_DATABASE_HOSTNAME, MYRIAD_DATABASE_USERNAME, MYRIAD_DATABASE_PASSWORD); 
		mysql_select_db(MYRIAD_DATABASE_NAME, $this->db_transcoder)or die(mysql_error());

		if ($_GET["act"] == 2) 
		{
			$sql = "UPDATE customers SET active = '0' WHERE id = " . $_GET["id"];
		}else
		{
			$sql = "UPDATE customers SET active = '1' WHERE id = " . $_GET["id"];
		}
		$result = mysql_query($sql); 

		$this->show();
	}

	function contents()
	{
		$distributions = Array();
		//$db_transcoder = mysql_connect(MYRIAD_DATABASE_HOSTNAME, MYRIAD_DATABASE_USERNAME, MYRIAD_DATABASE_PASSWORD); 
		mysql_select_db(MYRIAD_DATABASE_NAME, $this->db_transcoder)or die(mysql_error());
		$dist_result = mysql_query("SELECT * FROM customers where active = '1' AND srcFolder = '".TRANSCODER_SRC_FOLDER."'");
		while($dist_row = mysql_fetch_array($dist_result))
		{
			$distributions[] = array($dist_row['id'], $dist_row['name']);
		}
		mysql_select_db(DB_DATABASE);
		//print_r($distributions);
		return $distributions;
		
	}

	function install()
	{
		$result = mysql_query("SELECT * FROM configuration_group where configuration_group_title = 'Distributions module'");
		if(mysql_num_rows($result) < 1)
		{
			$sql = "REPLACE INTO configuration_group (configuration_group_title, configuration_group_description, visible) VALUES ('Distributions module', 'Distributions configuration', '1')";
			mysql_query($sql);

			$insert_id = mysql_insert_id();
			$sql = array();


			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'MYRIAD_DATABASE_NAME', 'Database name', '', 'The database name for the transcoder.')";
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'MYRIAD_DATABASE_HOSTNAME', 'Database hostname', '', 'The database hostname for the transcoder.')";
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'MYRIAD_DATABASE_USERNAME', 'Database username', '', 'The database username for the transcoder.')";
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'MYRIAD_DATABASE_PASSWORD', 'Database password', '', 'The database password for the transcoder.')";

			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'TRANSCODER_HOSTNAME', 'Transcoder hostname', '', 'The ftp hostname for the transcoder.')";
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'TRANSCODER_USERNAME', 'Transcoder username', '', 'The ftp username for the transcoder.')";
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'TRANSCODER_PASSWORD', 'Transcoder password', '', 'The ftp password for the transcoder.')";
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'TRANSCODER_SRC_FOLDER', 'Transcoder source folder', '', 'The source folder for the transcoder.')";

			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'MEDIASERVER_HOSTNAME', 'Mediaserver url', '', 'The ftp address for the mediaserver.')";	
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'MEDIASERVER_USERNAME', 'Mediaserver username', '', 'The ftp username for the mediaserver.')";
			$sql[] = "REPLACE INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'MEDIASERVER_PASSWORD', 'Mediaserver password', '', 'The ftp password for the mediaserver.')";
			for($x = 0; $x < count($sql); $x++)
				mysql_query($sql[$x])or die(mysql_error().$sql[$x]);
		
			echo "<span class=\"pageHeading\">Module installed</span><BR><BR>";
		}
		else
		{
			echo "<span class=\"pageHeading\">Module already installed</span><BR><BR>";
		}
	}

	function show()
	{
		if (!isset($_GET['order']) || $order == "DESC"){
			$order = "ASC";
		}else{
			$order = "DESC";
		}
		if (!isset($_GET['orderby']) || $_GET['orderby'] == ""){
			$orderby = "NAME";
		}else{
			$orderby = $_GET['orderby'];
		}


		//$db_transcoder = mysql_connect(MYRIAD_DATABASE_HOSTNAME, MYRIAD_DATABASE_USERNAME, MYRIAD_DATABASE_PASSWORD); 
		mysql_select_db(MYRIAD_DATABASE_NAME, $this->db_transcoder)or die(mysql_error());

		$result = mysql_query("select * from customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' ") or die(mysql_error());
		$row = mysql_fetch_array($result);

		if (isset($_GET['search']) && $_GET['search'] != ""){
			if (ereg(" ", $_GET['search'])){
				$search = explode(" ", $_GET['search']);
				$titel = $search[0];
				$query = "SELECT * FROM customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' AND NAME LIKE '%".$titel."%' ORDER BY ".$orderby." ".$order;
			}
			else{
			$query = "SELECT * FROM customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' AND (NAME LIKE '%".$_GET['search']."%' ) ORDER BY ".$orderby." ".$order;
			}
		}else{
			$query = "SELECT * FROM customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' ORDER BY NAME";
		}
		$exec = mysql_query($query)or die(mysql_error());
		$totaalAantal = mysql_num_rows($exec);

		echo "<span class=\"pageHeading\">Distributies (".$totaalAantal.")</span><BR><BR>";

		echo "<a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&mf=showOutputFormats".">Toon Output Formaten</a><BR><BR>";

		echo "<a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=add"."><img src=".DIR_WS_IMAGES."/cms/add.gif border='0' alt='toevoegen' title='toevoegen'></a><BR><BR>";

		echo "<form method=get>";
		echo "<input type=text name=search>&nbsp<input type=submit value=zoek>";
		echo "</form><BR><BR>";

		if ($_GET['pagina']!= "")
		{
			$begin = ($_GET['pagina']-1) * $_GET['recs'];
			$aantal = $_GET['recs'];
		}else
		{
			$begin = 0;
			$aantal = 50;
		}
		if (isset($_GET['search']) && $_GET['search'] != ""){
			if (ereg(" ", $_GET['search'])){
				$search = explode(" ", $_GET['search']);
				$titel = $search[0];
				$query = "SELECT * FROM customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' AND NAME LIKE '%".$titel."%' ORDER BY ".$orderby." ".$order;
			}
			else{
			$query = "SELECT * FROM customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' AND NAME LIKE '%".$_GET['search']."%' ORDER BY ".$orderby." ".$order;
			}
		}else{
			$query = "SELECT * FROM customers WHERE srcFolder = '".TRANSCODER_SRC_FOLDER."' ORDER BY ".$orderby." ".$order." LIMIT ".$begin.",".$aantal;
		}
		$exec = mysql_query($query) or die($query);
		echo "<TABLE cellpadding=2 cellspacing=0 style=\"font-family:Verdana\" width=100%>";
		echo "<TR class=\"dataTableHeadingRow\"><TD class=\"dataTableHeadingContent\"><a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=show&orderby=NAME&order=".$order."&search=".$_GET['search']."&page=".$_GET['page']."&recs=".$_GET['recs']." style=\"font-weight:bold;color:white\">Naam</a></TD><TD class=\"dataTableHeadingContent\">output</TD><TD class=\"dataTableHeadingContent\">bitrate</TD><TD class=\"dataTableHeadingContent\">Kosten</TD><TD class=\"dataTableHeadingContent\">DRM</TD><TD class=\"dataTableHeadingContent\">actief</TD><TD class=\"dataTableHeadingContent\">&nbsp</TD></TR>";
		if (mysql_num_rows($exec) > 0){
			while ($row = mysql_fetch_array($exec))
			{
				extract($row);

				$formatquery = "SELECT * FROM OUTPUTFORMAT WHERE ID = '".$outputFormatId."'";
				$formats = mysql_query($formatquery);
				$format = mysql_fetch_array($formats);

			//	$paymentquery = "SELECT * FROM SHOP_PAYMENT_CATEGORIES WHERE PAYMENT_CATEGORY_ID = '".$paymentCategoryId."'";
			//	$payments = mysql_query($paymentquery)or die(mysql_error());
			//	$payment = mysql_fetch_array($payments);

				if ($active == '1') {
					$tabgreen = "<img src=".DIR_WS_IMAGES."/cms/green_active.gif border=0>";
				}else{	
					$tabgreen = "<a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&mf=setActief&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&id=" . $id . "&act=1"."\"><img src=".DIR_WS_IMAGES."/cms/green_non_active.gif border=0></a>";
				}
				if ($active == '0') {
					$tabred = "<img src=".DIR_WS_IMAGES."/cms/red_active.gif border=0>";
				}else{	
					$tabred = "<a href=\"index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&mf=setActief&pagina=".$_GET['pagina']."&recs=".$_GET['recs']."&search=".$_GET['search']."&orderby=".$_GET['orderby']."&id=" . $id . "&act=2"."\"><img src=".DIR_WS_IMAGES."/cms/red_non_active.gif border=0></a>";
				}

				if($drmProtection == 'on')
				{
					$tabdrm = "<img src=".DIR_WS_IMAGES."/cms/drm_lock.gif border='0' alt='DRM beveiliging actief' title='DRM beveiliging actief'>";
				}
				else if($drmProtection == 'off')
				{
					$tabdrm = "<img src=".DIR_WS_IMAGES."/cms/drm_unlock.gif border='0' alt='DRM beveiliging niet actief' title='DRM beveiliging niet actief'>";
				}

				//print_r($row);

				echo "<TR onMouseOver=\"this.bgColor='#C0C0C0';this.style.cursor='hand';this.style.color='white';\" onMouseOut=\"this.bgColor='white';this.style.color='black';\" style=\"font-size:10\"><TD><a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&id=".$id.">".$name."</a></TD><TD>".$format['naam']."</TD><TD>".$format['bitrate']."</TD><TD>". $paymentarray[$paymentCategoryId]."</TD><TD>".$tabdrm."</TD><TD>".$tabgreen.$tabred."</TD><TD align=right>[ <a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&id=".$id."><img src=".DIR_WS_IMAGES."/cms/distribution_edit.gif border='0' alt='Distributie wijzigen' title='Distributie wijzigen'></a> &nbsp; <a href=# onClick=\"confirm_del('index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=remove&id=".$id."','".addslashes($name)."'); return false\"><img src=".DIR_WS_IMAGES."/cms/distribution_remove.gif border='0' alt='Distributie verwijderen' title='Distributie verwijderen'></a> ]</TD></TR>";
			}
		}else{

			echo "<TR style=\"font-size:10\"><TD colspan=7 align=center>Nog geen distributies ingevoerd!</TD></TR>";

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