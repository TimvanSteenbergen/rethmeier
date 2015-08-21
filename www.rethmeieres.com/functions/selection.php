<?
function selection($name, $default="false")
{
	if(!$_GET['p'] && $default == "true")
	{
		return "selected_";
	}
	elseif($_GET['p'] == $name)
	{
		return "selected_";
	}
}
?>