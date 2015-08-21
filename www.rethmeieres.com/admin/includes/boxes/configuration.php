<!-- configuration //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => '<img src='.DIR_WS_IMAGES.'/cms/config.gif border="0" alt="Configuratie" title="Configuratie">&nbsp;'."Configuratie",
                     'link'  => tep_href_link('index.php', 'page=adminusers&selected_box=configuration'));

  if ($_SESSION['selected_box'] == 'configuration') {
    $cfg_groups = '';
    $cfg_groups .= '<a href="index.php?page=adminusers&selected_box=configuration" class="menuBoxContentLink">Administratie gebruikers</a><br>';

    $configuration_groups_query = mysql_query("select configuration_group_id as cgID, configuration_group_title as cgTitle from configuration_group where visible = '1' order by configuration_group_title");
    $eerste = true;
/*	while ($configuration_groups = mysql_fetch_array($configuration_groups_query)) 
	{			
		$cfg_groups .= '<a href="index.php?page=configuration&gID='.$configuration_groups['cgID'].'&selected_box=configuration" class="menuBoxContentLink">' . $configuration_groups['cgTitle'] . '</a><br>';
    }
*/
    $contents[] = array('text'  => $cfg_groups);
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- configuration_eof //-->
