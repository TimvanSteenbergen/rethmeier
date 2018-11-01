<?php

$module = new modules($_GET['m']);


if($_GET['mf'])
{
	$module->inner_function($_GET['mf']);
}
else
{
	switch($_GET['f'])
	{
		case 'install':
			$module->install();
		break;
		case 'add':
			$module->add();
		break;
		case 'edit':
			$module->edit();
		break;
		case 'remove':
			$module->remove();
		break;
		default:		
			$module->show();
		break;
	}
}

function doRun($id, $data) 
{

	if($_GET['m'] == 'products')
	{

		
		if($GLOBALS["products"]->parent->module_exists("distributions"))
		{
			//add distribution data
			$distribution_ids = $GLOBALS["distributions"]->contents();

//		while (list($title_key, $title_val) = each($_POST['DistTitel'])) 
	//	{
//			list($synopsis_key, $synopsis_val) = each($_POST['DistSynopsis']);
			
//			$description = $_POST['DistSynopsis'][$title_key];
			for($x = 0; $x < count($distribution_ids); $x++)
			{
				$result = mysqli_query("SELECT * FROM product_description where product_id = '".$_GET['id']."' AND distribution_id = '".$distribution_ids[$x][0]."'");
				if(mysqli_num_rows($result) == 0 )
				{
					 mysqli_query("INSERT INTO product_description (product_id, distribution_id) VALUES ('".$_GET['id']."', '".$distribution_ids[$x][0]."')");
				}
			}
			
			//while ($row = mysqli_query)
			//$query = "update product_description set product_title = '".$title_val."' , product_synopsis = '".$description."' WHERE product_id = '".$_GET['id']."' AND distribution_id = '".$title_key."'";
			//mysqli_query($query) or die(mysqli_error());
		}

		if($_GET['m'] == 'distributions')
		{
			$distribution_ids = $GLOBALS["distributions"]->contents();

			for($x = 0; $x < count($distribution_ids); $x++)
			{
				$result = mysqli_query("SELECT * FROM product_description where product_id = '".$_GET['id']."' AND distribution_id = '".$distribution_ids[$x][0]."'");
				if(mysqli_num_rows($result) == 0 )
				{
					 mysqli_query("INSERT INTO product_description (product_id, distribution_id) VALUES ('".$_GET['id']."', '".$distribution_ids[$x][0]."')");
				}
			}
		}
//		mysqli_query("REPLACE INTO PRODUCTS_TO_CATEGORIES (PRODUCTS_ID, CATEGORIES_ID) VALUES ('".$_GET['id']."', '".$_POST['CATEGORY_ID']."' ) WHERE PRODUCTS_ID = '".$_GET['id']."'");

	}

   
	return "<META HTTP-EQUIV=\"Refresh\" Content= \"0; URL=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=show"."\">";

}
?>