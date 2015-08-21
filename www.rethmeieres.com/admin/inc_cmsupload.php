<?
include("inc/db_conn.php");

	if (isset($_GET['verwijder'])){
		$image = "../images/".$_GET['verwijder'];
		unlink($image);
		$to = "index.php?page=cmsupload";
		echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$to.'">';
	}

	set_time_limit(0); 
 
 	$destpath = "../images/";
	//$destpath = $_SERVER['DOCUMENT_ROOT'].str_replace("upload.php","",$_SERVER['REQUEST_URI'])."upload/";
	if (isset($_FILES['file'])){
 		//if (strtoupper($HTTP_POST_FILES['file']['name']) == "DEBITEUREN") {
			if (strlen($_FILES['file']['name']) > 0){
				if (move_uploaded_file($_FILES['file']['tmp_name'],$destpath.$_FILES['file']['name'])) 
				{
					$melding = "Het bestand of document is ge-upload!";

					// Set a maximum height and width
					$width = 120;
					$height = 120;
					
					$thumbsize = 0;
					
					// Content type
					//header('Content-type: image/jpeg');
					
					// Get new dimensions
					list($width_orig, $height_orig, $image_type) = getimagesize($destpath.$_FILES['file']['name']);
					
					/*
					$ratio_orig = $width_orig/$height_orig;
					
					if ($width/$height > $ratio_orig) {
					   $width = $height*$ratio_orig;
					} else {
					   $height = $width/$ratio_orig;
					}
					*/
					
					
					// Resample
					$image_p = imagecreatetruecolor($width, $height);
					
    				$functions = array(
						IMAGETYPE_GIF => 'imagecreatefromgif',
						IMAGETYPE_JPEG => 'imagecreatefromjpeg',
						IMAGETYPE_PNG => 'imagecreatefrompng',
						IMAGETYPE_WBMP => 'imagecreatefromwbmp',
						IMAGETYPE_XBM => 'imagecreatefromwxbm',
						IMAGETYPE_BMP => 'imagecreatefrombmp'
					);
					$image = $functions[$image_type]($destpath.$_FILES['file']['name']);
		
					//$image = imagecreatefromjpeg($destpath.$_FILES['file']['name']);
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
					
					// Output
					imagejpeg($image_p, $destpath."thumb_".$_FILES['file']['name'], 100);
					unlink($destpath.$_FILES['file']['name']);
					
					rename($destpath."thumb_".$_FILES['file']['name'], substr($destpath.$_FILES['file']['name'], 0, strrpos($destpath.$_FILES['file']['name'], ".")).".jpg");
					
				} else {
					$melding = "Bestand kon niet worden ge-upload!";
				}
				//echo ("<BR><a href=fileread.php?file=" . $HTTP_POST_FILES['file']['name'] . ">Klik hier om de gegevens te verwerken in de database.</a>");
			}
		//}
		//else
		//{
		//	echo "Dit bestand moet een andere naam hebben. Neem contact op met Vivens Nederland voor hulp!";
		//}
		$to = "index.php?page=cmsupload&melding=".$melding;
		echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$to.'">';
	}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<script language="javascript">
	function confirm_del(url){
		var return_value = confirm('Weet u zeker dat u dit bestand wilt verwijderen?') ;
		if ( return_value == true ){
			window.location = url;
		}
	}
</script>
</head>
<span class="pageHeading">Bestanden</span> <font color=red><?=$_GET['melding']?></font><br><br>
<form action="" method="post" enctype="multipart/form-data" name="form1">
   <input type="file" name="file" style="width:300">
   <input type="submit" name="Submit" value="Upload!">
</form>

 
<?
	include("bog.php");
?>
</body>
</html> 