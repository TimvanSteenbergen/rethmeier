<?php


  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
        $configuration_value = ($HTTP_POST_VARS['configuration_value']);
        $cID = ($HTTP_GET_VARS['cID']);

        mysqli_query("update configuration set configuration_value = '" . $configuration_value . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");

        break;
    }
  }

  $gID = (isset($HTTP_GET_VARS['gID'])) ? $HTTP_GET_VARS['gID'] : 1;
  $selectGroups = ($gID == 1 ? "configuration_group_id = '1' or configuration_group_id = '16' " : "configuration_group_id = '" . (int)$gID . "'");
	
  $cfg_group_query = mysqli_query("select configuration_group_title from configuration_group where ".$selectGroups);
  $cfg_group = mysqli_fetch_array($cfg_group_query);
?>



<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $cfg_group['configuration_group_title']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo "Naam"; ?></td>
                <td class="dataTableHeadingContent"><?php echo "Waarde"; ?></td>
              </tr>
<?php
  $configuration_query = mysqli_query("select configuration_id, configuration_title, configuration_value, use_function from configuration  where ".$selectGroups." order by configuration_title, sort_order");
  while ($configuration = mysqli_fetch_array($configuration_query)) {
    if (tep_not_null($configuration['use_function'])) {
      $use_function = $configuration['use_function'];
      if (ereg('->', $use_function)) {
        $class_method = explode('->', $use_function);
        if (!is_object(${$class_method[0]})) {
          include(DIR_WS_CLASSES . $class_method[0] . '.php');
          ${$class_method[0]} = new $class_method[0]();
        }
        $cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
      } else {
        $cfgValue = tep_call_function($use_function, $configuration['configuration_value']);
      }
    } else {
      $cfgValue = $configuration['configuration_value'];
    }

    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cfg_extra_query = mysqli_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from configuration where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
      $cfg_extra = mysqli_fetch_array($cfg_extra_query);

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }

    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) 
	{
      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . 'index.php?page=configuration'. '&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit' . '\'">' . "\n";
    } else 
	{
      echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . 'index.php?page=configuration'. '&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['configuration_id'] . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $configuration['configuration_title']; ?></td>
                <td class="dataTableContent"><?php echo htmlspecialchars($cfgValue); ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) 
  {
    case 'edit':
      $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

      if ($cInfo->set_function) {
        eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
      } else {
        	$value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
      }

      $contents = array('form' => '<form method="post" name="configuration" action="index.php?page=configuration&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save">');
      $contents[] = array('text' => 'Bewerken');
      $contents[] = array('text' => '<br><b>' . $cInfo->configuration_title . '</b><br>' . $cInfo->configuration_description . '<br>' . $value_field);
      $contents[] = array('align' => 'center', 'text' => '<br>' . '<input type=image src="images/cms/save.gif">'. '&nbsp;<a href="' . 'index.php?page=configuration&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '">' . '<input type=image src="images/cms/undo.gif">' . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');

        $contents[] = array('align' => 'left', 'text' => '<a href="' . 'index.php?page=configuration&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit' . '">' . '<input type=image src="images/cms/edit.gif">' . '</a>');
        $contents[] = array('text' => '<br>' . $cInfo->configuration_description);
        $contents[] = array('text' => '<br>' . "Datum toegevoegd" . ' ' . ($cInfo->date_added));
        if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => "Laatste wijziging" . ' ' . ($cInfo->last_modified));
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) 
  {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->


