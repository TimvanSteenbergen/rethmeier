<!-- modules //-->
<tr>
<td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => '<img src='.DIR_WS_IMAGES.'/cms/modules.gif border="0" alt="Modules" title="Modules">&nbsp;'.'Modules',
                     'link'  => tep_href_link('index.php', 'page=modules&selected_box=Modules'));

if ($_SESSION['selected_box'] == 'Modules') 
{
	$modules = new modules();
	for($x = 0; $x < count($modules->modules); $x++)
	{
		$module_class = substr($modules->modules[$x], 0, strrpos($modules->modules[$x], '.'));
		$contents[] = array('text'  => '<a href="'.tep_href_link('index.php', 'page=modules&selected_box=Modules&m='.$module_class).'">'.$module_class .'</a><br>');
	}	
}
 
$box = new box;
echo $box->menuBox($heading, $contents);

//unset($modules);
?>
</td>
</tr>
<!-- modules_eof //-->
