<?PHP
class modules
{
	var $modules;
	var $selected_module;

	function modules($aModule = '')
	{
		$this->modules = explode(';', "upload.php;products.php;distributions.php;categories.php");//;categories.php;distributions.php

		$include_modules = array();
		if($aModule)
		{
			$this->selected_module = $aModule;
			$include_modules[] = array('class' => $aModule, 'file' => $aModule . '.php');
		}
		else
		{
			reset($this->modules);
			while (list(, $value) = each($this->modules)) 
			{
				$class = substr($value, 0, strrpos($value, '.'));
				$include_modules[] = array('class' => $class, 'file' => $value);
			}
		}
		for($i = 0; $i < count($include_modules); $i++)
		{
			
			include_once(DIR_WS_MODULES . '/' . $include_modules[$i]['file']);

			$GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class']($this);
		}

		if(count($include_modules) == 1)
		{
			$this->selected_module = $include_modules[0]['class'];
		}
	}
	
	function show()
	{
		if(empty($this->selected_module))
		{
			return $this->modules;

		}
		return $GLOBALS[$this->selected_module]->show();
	}

	function install()
	{
		return $GLOBALS[$this->selected_module]->install();
	}

	function add()
	{
		return $GLOBALS[$this->selected_module]->add();
	}
	
	function edit()
	{
		return $GLOBALS[$this->selected_module]->edit();
	}

	function remove()
	{
		return $GLOBALS[$this->selected_module]->remove();
	}

	function contents()
	{
		return $GLOBALS[$this->selected_module]->contents();
	}

	function module_list()
	{
		return $this->modules;
	}

	function module_exists($module_name)
	{
		return in_array($module_name.".php", $this->modules);
	}

	function inner_function($aFunction)
	{
		return $GLOBALS[$this->selected_module]->$aFunction();
	}
	
}
?>