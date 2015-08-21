<?php
/*
  $Id: cms.php,v 1.1 2008/07/14 12:46:23 sem_heethaar Exp $

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

  $heading[] = array('text'  => '<img src='.DIR_WS_IMAGES.'/cms/website.gif border="0" alt="Front site" title="Front site">&nbsp;'.'Front site',
                     'link'  => tep_href_link('index.php', 'page=cssedit&selected_box=frontsite'));

  if ($_SESSION['selected_box'] == 'frontsite') {
    $contents[] = array('text'  => '<a href="'.tep_href_link('index.php', 'page=cssedit&selected_box=frontsite').'">Stylesheet</a><br>');
    $contents[] = array('text'  => '<a href="'.tep_href_link('index.php', 'page=layout&selected_box=frontsite').'">Layout</a><br>');					
    $contents[] = array('text'  => '<a href="'.tep_href_link('index.php', 'page=preview&selected_box=frontsite').'">Preview</a><br>');		

  }
 
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
