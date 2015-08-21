<?php
// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// Include application configuration parameters
  require('includes/configure.php');

// set php_self in the local scope
  $PHP_SELF = (isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME']);
// customization for the design layout
  define('BOX_WIDTH', 150); // how wide the boxes should be in pixels (default: 125)


// make a connection to the database... now

  mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die('Unable to connect to database server!');
  mysql_select_db(DB_DATABASE);

// set application wide parameters
  $configuration_query = mysql_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . 'configuration');
  while ($configuration = mysql_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

  session_name('AdminID');
  //tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
  session_set_cookie_params(0, DIR_WS_ADMIN);
  
// lets start our session
  session_start();

// define our general functions used application-wide
	require(DIR_WS_FUNCTIONS . 'general.php');
	require(DIR_WS_FUNCTIONS . 'html_output.php');

	require(DIR_WS_CLASSES . "object_info.php");
	require(DIR_WS_CLASSES . "class.FormHandler.php");
	require(DIR_WS_CLASSES . 'table_block.php');
	require(DIR_WS_CLASSES . 'box.php');
	include(DIR_WS_CLASSES . 'modules.php');	

// default open navigation box
	if (!session_is_registered('selected_box')) 
	{
		$_SESSION['selected_box'] = 'configuration';
	}

	if (isset($_GET['selected_box'])) 
	{
		$_SESSION['selected_box'] = $_GET['selected_box'];
	}


if( !$_COOKIE['adminuserid'] )
{
	header("Location: index2.php");
}
?>
