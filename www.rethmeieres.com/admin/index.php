<?php
require('includes/application_top.php');
require(DIR_WS_INC 2394LUDES . 'header.php'); 
//require('style.php'); 
?>
<!-- header_eof //-->
<?php
//echo mysqli_get_host_info();
?>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript">
	function confirm_del($url, $product){
		var return_value = confirm('Weet u zeker dat u '+$product+' wilt verwijderen?') ;
		if ( return_value == true ){
			window.location = $url;
		}
	}
	function afdrukken(){
		window.printFrame.focus();
		window.printFrame.print();
	}	
</script>

<script language="javascript">
function expand(assortiment)
{

	var assort = "assort_" + assortiment;
	var td = "expand_" + assortiment;

	var dir_images = "<?php echo DIR_WS_IMAGES ?>";

	document.getElementById(assort).style.display = "block";

	document.getElementById(td).innerHTML = "<a onclick='collapse(" + assortiment + ");'><img src=" + dir_images + "cms/collapse.gif border=0 alt=Inklappen title=Inklappen>";
}
function collapse(assortiment)
{

	var assort = "assort_" + assortiment;
	var td = "expand_" + assortiment;

	var dir_images = "<?php echo DIR_WS_IMAGES ?>";

	document.getElementById(assort).style.display = "none";

	document.getElementById(td).innerHTML = "<a onclick='expand(" + assortiment + ");'><img src=" + dir_images + "cms/expand.gif border=0 alt=Uitklappen title=Uitklappen>";
}
</script>


<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" >
<div id="spiffycalendar" class="text"></div>

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<img src="images/top_left.jpg" border="0" />
		</td>
		<td background="images/top_center.jpg" width="100%">
		</td>
		<td>
			<img src="images/top_right.jpg" border="0" />
		</td>
	</tr>
</table>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
	<td width="250" valign="top"><table border="0" width="250" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>

<!-- body_text //-->
    <td width="100%" valign="top">
	<?php
		if(empty($_GET['page'])) $_GET['page'] = 'cmsartikelen';
		include("inc_".$_GET['page'].".php"); 
	?>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php // require(DIR_WS_INCLUDES . 'footer.php'); 
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php //require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>