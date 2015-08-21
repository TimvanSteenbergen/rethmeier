<?php
/*
  $Id: logout.php,v 1.1 2008/07/14 12:46:23 sem_heethaar Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td></b>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => '<img src='.DIR_WS_IMAGES.'/cms/logout.gif border="0" alt="Logout" title="Logout">&nbsp;'.'Logout',
                     //'link'  => tep_href_link('cmsviv.php', 'selected_box=CMS'));
                     'link'  => tep_href_link('index2.php', 'logout=1'));

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
