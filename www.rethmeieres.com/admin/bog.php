<?php 
$folder = "../images/";
$extension = "jpg";
$extension2 = "gif";
$extension3 = "LOG";
$extension4 = "swf";
$extension5 = "php";
$extension6 = "menu2_02";
echo ("<TABLE cellpadding=0 cellspacing=1 style=\"font-family:verdana;font-size:10\"><TR><TD><font face=verdana size=1>Beschikbare bestanden in: images/</TD></TR>");
//foreach ($_SERVER AS $name => $value) {
//	echo $name.": ".$value."<br>";
//}
$extsize = strlen($extension); 
if ($handle = opendir($folder)) { 
  while (false !== ($file = readdir($handle))) { 
    //if ((ereg(".$extension$",$file)) or (ereg(".$extension2$",$file))  or (ereg(".$extension3$",$file))  or (ereg(".$extension4$",$file))  or (ereg(".$extension5$",$file)) or (ereg(".$extension6$",$file))) { 
   
   //   }
//	else
//	{
	$filename = (substr($file,0,strlen($file))); 
        //$filename = ereg_replace("_", " ", $filename); 
	if (strlen($filename) > 2 && $filename != "thumbs") {
	
	      echo "<TR><TD><FONT face=verdana size=1><a href=\"../images/$filename\">$filename</a> </TD><TD><a href=\"javascript:void(0)\" onclick=\"confirm_del('index.php?page=cmsupload&verwijder=".$filename."');return false;\">[ verwijder ]</a></TD></TR> "; 
 
//          }
     
    }
  } 
  closedir($handle);  
} 
?> 
</TABLE>