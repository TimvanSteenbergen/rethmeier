<?php
/*
  $Id: cms.php,v 1.1 2008/07/14 12:46:20 sem_heethaar Exp $

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

  $heading[] = array('text'  => 'CMS',
                     'link'  => tep_href_link('cmsviv.php', 'selected_box=CMS'));

  if ($selected_box == 'CMS') {
    $contents[] = array('text'  => '<a href=./cmsviv.php?selected_box=CMS>Gebruikers</a><br>' .
                                   '<a href=./cmsstructuur.php>Structuur</a><br>' .
                                   '<a href=./cmsartikelen.php>Artikelen</a><br>' .
//                                 '<a href=./cmspagina.php>Trailers</a><br>' .
//                                   '<a href=./cmsoverig.php>Overig</a><br>' .
                                   '<a href=./cmsupload.php>Bestanden</a><br>' .
                                   '<a href=./cmsstatistieken.php>Statistieken</a><br>' .
                                   '<a href=./cmsarchief.php>Archief</a><br>' .
                                   '<a href=./cmsleden.php>Leden</a>');

  }
 
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
