<?php
/*
  $Id: column_left.php,v 1.1 2008/07/14 12:46:22 sem_heethaar Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

$query = "SELECT * FROM admin_user_rights LEFT JOIN admin_items ON admin_user_rights.ITEM_ID = admin_items.ITEM_ID WHERE USER_ID = ".$_COOKIE['adminuserid']." ORDER BY admin_items.ITEM_ID";
$exec = mysql_query($query);
while ($row = mysql_fetch_array($exec)){
	$filenameArr = explode("?", $row['ITEM_INCLUDE']);
	$filename = $filenameArr[0];
	
	/*require(DIR_WS_BOXES. $filename);
  require(DIR_WS_BOXES . 'catalog.php');
  require(DIR_WS_BOXES . 'customers.php');
  require(DIR_WS_BOXES . 'reports.php');
  require(DIR_WS_BOXES . 'tools.php');
  
  */

}
//begin Supportticketsystem
//require(DIR_WS_BOXES . 'support.php');
//end Supportticketsystem
require(DIR_WS_BOXES . 'cms.php');
require(DIR_WS_BOXES . 'configuration.php');
require(DIR_WS_BOXES. 'logout.php');
?>
