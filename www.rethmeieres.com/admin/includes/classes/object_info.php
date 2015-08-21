<?php
/*
  $Id: object_info.php,v 1.1 2008/04/17 09:06:57 sem_heethaar Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class objectInfo {

// class constructor
    function objectInfo($object_array) {
      reset($object_array);
      while (list($key, $value) = each($object_array)) {
        $this->$key = ($value);
      }
    }
  }
?>
