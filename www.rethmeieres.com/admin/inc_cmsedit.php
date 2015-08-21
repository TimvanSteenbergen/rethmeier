<?php



if ($_POST['save'] == '1') {
	
	$content_title_nl = addslashes($_POST['content_title_nl']);
	$content_title_en = addslashes($_POST['content_title_en']);

	$content_nl = addslashes($_POST['content_nl']);
	$content_en = addslashes($_POST['content_en']);

	//$content_dui = addslashes($_POST['content_dui']);

	$content_name = addslashes($_POST['content_name']);

	//$content_name_dui = addslashes($_POST['content_name_dui']);

	$pic_left = addslashes($_POST['pic_left']);
	$pic_center = addslashes($_POST['pic_center']);
	$pic_right = addslashes($_POST['pic_right']);

	$parent = $_POST['parent'];

	if ($_POST['id']) {

		// updaten
		

		$sql = "UPDATE tbl_contents 

			   SET pic_left = '".$pic_left."',pic_center = '".$pic_center."',pic_right = '".$pic_right."', content_title_nl = '". $content_title_nl."', content_title_en = '". $content_title_en."', content_nl = '". $content_nl."', content_en = '". $content_en."', content_name =  '". $content_name."' ";



		$sql .= " WHERE CONTENT_ID = ".$_POST['id'];

		$result = mysql_query($sql)or die(mysql_error()); 

	 	$to = 'index.php?page=cmsartikelen&id='.$_POST['id'];

	} else {

	// toevoegen



		$sql = "INSERT INTO tbl_contents

					(pic_left,pic_center,pic_right,content_title_nl,content_title_en,content_nl,content_en,content_name,content_parentid,content_lang,content_creation_date,content_archive,content_visible) VALUES

					('".$pic_left."','".$pic_center."','".$pic_right."','".$content_title_nl."','".$content_title_en."','".$content_nl."','".$content_en."','".$content_name."','".$parent."','1','".date("Y-m-d H:i:s")."','F','T')";

		$result = @mysql_query($sql) or die(mysql_error()); 

	 	$to = 'index.php?page=cmsartikelen&id='.mysql_insert_id();

	}


	echo '<META HTTP-EQUIV="refresh" content="0;URL='.$to.'">';

}

if ($_GET['act']) {

	// zet artikel actief of niet actief

	$act = 'T';

	if ($_GET['act'] == "2") {

		$act = 'F';

	}

	$query = "UPDATE tbl_contents SET content_visible = '".$act."' WHERE CONTENT_ID = ".$_GET['id'];

	mysql_query($query);

	//Header("Location:index.php?page=);

	echo '<META HTTP-EQUIV="refresh" content="0;URL=cmsartikelen&id='.$_GET['id'].'">';
}

//	$IMAGES_BASE_URL = '../images/';

//	$IMAGES_BASE_DIR = getenv("DOCUMENT_ROOT").$IMAGES_BASE_URL;





	$IMAGES_BASE_URL = 'images/';

	$IMAGES_BASE_DIR = DIR_FS_CATALOG . $IMAGES_BASE_URL;

	

	$html_iTBL_lst = '';



	function walk_dir($path) {

		if ($dir = opendir($path)) {

			while (false !== ($file = readdir($dir))){

				if ($file[0]==".") continue;

				if (is_dir($path."/".$file)){

					//$retval = array_merge($retval,walk_dir($path."/".$file));

				}elseif (is_file($path."/".$file)){

					$retval[]=$path."/".$file;

				}

			}

			closedir($dir);

		}

		return $retval;

	}

	

	function CheckImgExt($filename) {

		$iTBL_exts = array("jpg", "gif", "png", "JPG", "GIF");

		foreach($iTBL_exts as $this_ext) {

			if (preg_match("/\.$this_ext$/", $filename)) {

				return TRUE;

			}

		}

		return FALSE;

	}

	

	function CheckPdfExt($filename) {

		$iTBL_exts = array("pdf");

		foreach($iTBL_exts as $this_ext) {

			if (preg_match("/\.$this_ext$/", $filename)) {

				return TRUE;

			}

		}

		return FALSE;

	}



	function CheckSearch($filename){

		if (strpos($filename, $_GET['search'])){

			return TRUE;

		}

		return FALSE;

	}

	
	foreach (walk_dir($IMAGES_BASE_DIR) as $file) {

		//echo $file;

		$file = preg_replace("#//+#", '/', $file);

		$IMAGES_BASE_DIR = preg_replace("#//+#", '/', $IMAGES_BASE_DIR);

		$file = preg_replace("#$IMAGES_BASE_DIR#", '', $file);

		if (CheckImgExt($file)) {

			//if ($_GET['search'] == ""){

				$files[] = $file;	//adding filenames to array

			//}else{

			//	if (CheckSearch($file)){

			//		$files[] = $file;

			//	}

			//}

		}

	}

	

	foreach (walk_dir($IMAGES_BASE_DIR) as $file) {

		//echo $file;

		$file = preg_replace("#//+#", '/', $file);

		$IMAGES_BASE_DIR = preg_replace("#//+#", '/', $IMAGES_BASE_DIR);

		$file = preg_replace("#$IMAGES_BASE_DIR#", '', $file);

		if (CheckPdfExt($file)) {

			//if ($_GET['search'] == ""){

				$pdf[] = $file;	//adding filenames to array

			//}else{

			//	if (CheckSearch($file)){

			//		$files[] = $file;

			//	}

			//}

		}

	}



function isProduct($id = 0,$parent = 0) {

	$return = false;

	if ($id > 0) {

		// kijken of parent van parent "9" is	

		$query = "SELECT b.content_parentid FROM tbl_contents a INNER JOIN tbl_contents b ON b.CONTENT_ID = a.content_parentid WHERE a.CONTENT_ID = ".$id." AND b.content_parentid = 9";

		$res = mysql_query($query);

		if (mysql_num_rows($res)!=0) {

			$return = true;

		}

	} else {

		// kijken of parent "9" is

		$query = "SELECT content_parentid FROM tbl_contents WHERE CONTENT_ID = '".$parent."' AND content_parentid = 9";

		$res = mysql_query($query);

		if (mysql_num_rows($res)!=0) {

			$return = true;

		}

	}

	return $return;

}

	

function addsingleslash($string){

	$string = str_replace("\'","'",$string);

	return $string;

}

	

//include("include.php");

include("fckeditor/fckeditor.php") ;







$sql = "SELECT *, DATE_FORMAT(content_archive_date, '%d-%m-%Y') AS content_archive_date_conv 

	   FROM tbl_contents 

	   WHERE CONTENT_ID = ".$_GET['id'];

$result = mysql_query($sql); 

 

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');



$error = false;

$processed = false;

?>

<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">

  <tr>


<!-- body_text //-->



    <td width="100%" valign="top">

    

    <table border="0" width="100%" cellspacing="0" cellpadding="2">

    <span class="pageHeading">Content management editor</span> <a href="index.php?page=cmsartikelen<? if ($_GET['id']) { echo "&id=".$_GET['id']; } ?>">terug naar artikelen</a><BR><BR> 

		<form method="POST" action="<?=$_PHP_SELF;?>">

		<? 

		if ($_GET['id']) {

			$row = mysql_fetch_array($result);

		}

		?>

			<input type="hidden" name="id" value="<? echo $_GET["id"]; ?>">

			<input type=hidden name=type value="<? echo $_GET["type"]; ?>">

			<input type=hidden name=parent value="<? echo $_GET["parent"]; ?>">

			<input type=hidden name=titel value="<? echo $row['content_name']; ?>">

			

			<input type=hidden name=taal value="1">

			<input type="hidden" name="save" value="1">

			<TR>

				<TD width="176"><div class="menuBoxContent">Pagina</div></TD>

				<TD><div class="menuBoxContent"><input type="text" name="content_name" size="60" value="<? echo $row['content_name']; ?>"></div></td>

			</tr>

			<tr>

				<TD><div class="menuBoxContent">Afbeelding links</div></TD>

				<TD><div class="menuBoxContent">

					<select name="pic_left">

						<option value=""></option>

<?

foreach ($files as $file) {

	$selected = "";

	if ($row['pic_left']==$file) {

		$selected = " SELECTED";

	}

	echo "<option value=\"".$file."\"".$selected.">".$file."</option>";

}

?>

					</select>	

				</div></TD>

			</tr>

			<tr>

				<TD><div class="menuBoxContent">Afbeelding midden</div></TD>

				<TD><div class="menuBoxContent">

					<select name="pic_center">

						<option value=""></option>

<?

foreach ($files as $file) {

	$selected = "";

	if ($row['pic_center']==$file) {

		$selected = " SELECTED";

	}

	echo "<option value=\"".$file."\"".$selected.">".$file."</option>";

}

?>

					</select>	

				</div></TD>

			</tr>

			<tr>

				<TD><div class="menuBoxContent">Afbeelding rechts</div></TD>

				<TD><div class="menuBoxContent">

					<select name="pic_right">

						<option value=""></option>

<?

foreach ($files as $file) {

	$selected = "";

	if ($row['pic_right']==$file) {

		$selected = " SELECTED";

	}

	echo "<option value=\"".$file."\"".$selected.">".$file."</option>";

}

?>

					</select>	

				</div></TD>

			</tr>

			<TR>

				<TD width="176"><div class="menuBoxContent">Titel NL</div></TD>

				<TD><div class="menuBoxContent"><input type="text" name="content_title_nl" size="60" value="<? echo $row['content_title_nl']; ?>"></div></td>

			</tr>

			

			<TR>

				<TD><div class="menuBoxContent">Content NL</div></TD>

				<TD><div class="menuBoxContent">

			<?

				$oFCKeditor1 = new FCKeditor('content_nl') ;

				$oFCKeditor1->Value = stripslashes($row['content_nl']);
				$oFCKeditor1->ToolbarSet = 'Basic';
				//$oFCKeditor1->Config['MaxLength'] = '850';
				$oFCKeditor1->Create();
				
				
				//include_once "ckeditor/ckeditor.php";
				// The initial value to be displayed in the editor.
				//$initialValue = '<p>This is some <strong>sample text</strong>.</p>';
				// Create class instance.
				//$CKEditor = new CKEditor();
				// Path to CKEditor directory, ideally instead of relative dir, use an absolute path:
				//   $CKEditor->basePath = '/ckeditor/'
				// If not set, CKEditor will try to detect the correct path.
				//$CKEditor->basePath = 'ckeditor/';
				// Create textarea element and attach CKEditor to it.
				//$CKEditor->editor("editor1", $initialValue);

			?>

				</div></td>

			</tr>
			
			<TR>

				<TD width="176"><div class="menuBoxContent">Titel EN</div></TD>

				<TD><div class="menuBoxContent"><input type="text" name="content_title_en" size="60" value="<? echo $row['content_title_en']; ?>"></div></td>

			</tr>
			
			<TR>

				<TD><div class="menuBoxContent">Content EN</div></TD>

				<TD><div class="menuBoxContent">

			<?

				$oFCKeditor2 = new FCKeditor('content_en') ;

				$oFCKeditor2->Value = stripslashes($row['content_en']);
				$oFCKeditor2->ToolbarSet = 'Basic';
				//$oFCKeditor2->Config['MaxLength'] = '850';
				$oFCKeditor2->Create();

			?>

				</div></td>

			</tr>

			<TR>

				<TD colspan="2"><INPUT type="submit" value="Opslaan"></TD>

			</TR>

		</form>

		</table>

		

		</td>

	</tr>

</table>


