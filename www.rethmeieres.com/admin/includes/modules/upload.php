<?php

class upload
{
	function upload()
	{

	}

	function add()
	{
		// make a new $form object
		$form = new FormHandler;

		// permit editing
		$form->PermitEdit();
		echo "<span class=\"pageHeading\">Add files</span><BR><BR>";
		$form->TableSettings('100%', 0, 1, 2, 'font-family:Verdana;font-size:10px;border: 1px solid grey');

		// upload config
		$config = array(
		  'path'   => '../files/',
		  'type'   => 'jpg jpeg png gif',
		  'exists' => 'rename',
		  'size' => '1000000',
		); 

		// uploadfield
		$form->uploadField('Image', 'image', $config);

		// save the resized image as...
		// MAKE SURE THAT THIS DIR EXISTS!
		$saveAs = UPLOAD_DIR.'/thumbs';

		// resize the image
		//echo $saveAs;
		$form->imageResize( 'image', $saveAs, UPLOAD_RESIZE_WIDTH ); 

		$form->SubmitBtn("Opslaan", false, "Annuleren");

		// what to do after saving ?
		$form->OnCorrect("doRun");

		// flush the form
		$form->FlushForm();

		// 'commit after form' function 
	}

	function install()
	{
		$result = mysqli_query("SELECT * FROM configuration_group where configuration_group_title = 'Upload module'");
		if(mysqli_num_rows($result) < 1)
		{
			$sql = "INSERT INTO configuration_group (configuration_group_title, configuration_group_description, visible) VALUES ('Upload module', 'Upload configuration', '1')";
			mysqli_query($sql);

			$insert_id = mysqli_insert_id();
			$sql = array();
			$sql[] = "INSERT INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'UPLOAD_DIR', 'Upload folder', '/upload', 'The folder uploaded files are placed.')";
			$sql[] = "INSERT INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'UPLOAD_RESIZE_WIDTH', 'Upload image resize width', '200', 'The width of the generated thumbnails.')";
			$sql[] = "INSERT INTO configuration (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description) VALUES ('".$insert_id."', 'UPLOAD_RESIZE_HEIGHT', 'Upload image resize height', '', 'The height of the generated thumbnails.')";
			for($x = 0; $x < count($sql); $x++)
				mysqli_query($sql[$x])or die(mysqli_error().$sql[$x]);
		
			echo "<span class=\"pageHeading\">Module installed</span><BR><BR>";
		}
		else
		{
			echo "<span class=\"pageHeading\">Module already installed</span><BR><BR>";
		}
	}

	function disable()
	{
		$sql = "UPDATE FROM configuration_group SET visible = '0' WHERE configuration_group_title = 'Upload module'";
	}

	function edit()
	{
		echo "<span class=\"pageHeading\">".$_GET['filename']."</span><BR><BR>";
		echo "<img src=".UPLOAD_DIR."/thumbs/thumb_".$_GET['filename']."><BR><BR>";
		$this->show();
	}
	
	function remove()
	{
		if(unlink(UPLOAD_DIR.'/'.$_GET['filename']))
		{
			unlink(UPLOAD_DIR.'/thumbs/thumb_'.$_GET['filename']);
			echo "<span class=\"pageHeading\">".$_GET['filename']." removed.</span><BR><BR>";
		}
		else
		{
			echo "Could not delete file ".$_GET['filename'];
		}
		$this->show();
	}

	function show()
	{
		echo "<span class=\"pageHeading\">Uploaded files</span><BR><BR>";

		echo "<a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=add"."><img src=".DIR_WS_IMAGES."/cms/add.gif border='0' alt='toevoegen' title='toevoegen'></a><BR><BR>";

		echo "<TABLE cellpadding=2 cellspacing=0 style=\"font-family:Verdana\" width=100%>";
		echo "<TR class=\"dataTableHeadingRow\">";
		echo "<TD class=\"dataTableHeadingContent\">Bestandsnaam</TD>";
		echo "<TD class=\"dataTableHeadingContent\">Delete</TD>";
		echo "</TR>";
		@mkdir(UPLOAD_DIR.'/');
		if ($handle = opendir(UPLOAD_DIR.'/')) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && !is_dir(UPLOAD_DIR.'/'.$file)) 
				{
					echo "<TR onMouseOver=\"this.bgColor='#C0C0C0';this.style.cursor='hand';this.style.color='white';\" onMouseOut=\"this.bgColor='white';this.style.color='black';\" style=\"font-size:10\">";
					echo "<TD><a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=edit&filename=".$file.">$file</a></TD>";
					echo "<TD><a href=index.php?page=".$_REQUEST['page']."&m=".$_REQUEST['m']."&f=remove&filename=".$file."><img src=".DIR_WS_IMAGES."/cms/remove.gif border='0' alt='verwijder' title='verwijder'></a></TD>";
					echo "</TR>";
				}
			}
			closedir($handle);
		}
		echo "</TABLE>";
	}

	function contents()
	{
		$files = Array();
		if ($handle = opendir(UPLOAD_DIR.'/')) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && !is_dir(UPLOAD_DIR.'/'.$file)) 
				{
					$files[] = $file;
				}
			}
			closedir($handle);
		}
		return $files;
	}

}



?>