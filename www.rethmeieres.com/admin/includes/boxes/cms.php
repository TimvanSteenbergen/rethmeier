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

  $heading[] = array('text'  => '<img src='.DIR_WS_IMAGES.'/cms/content_management.gif border="0" alt="Content Management" title="Content Management">&nbsp;'.'Content Management',
                     'link'  => tep_href_link('index.php', 'page=cmsartikelen&selected_box=CMS'));

  if ($_SESSION['selected_box'] == 'CMS') {
    $contents[] = array('text'  => '<a href="'.tep_href_link('index.php', 'page=cmsartikelen&selected_box=CMS').'">Artikelen</a><br>');
    $contents[] = array('text'  => '<a href="'.tep_href_link('index.php', 'page=cmsupload&selected_box=CMS').'">Upload</a><br>');
	$contents[] = array('text'  => '<a href="'.tep_href_link('index.php', 'page=teksten&selected_box=CMS').'">Tekst animaties</a><br>');	

  }
 
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
