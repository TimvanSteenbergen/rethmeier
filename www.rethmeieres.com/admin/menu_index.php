<? include("inc/functions.php"); ?>
<?
if ($_GET['id'] != "") {
	function getParent($parent) {
		global $parents;
		$query = "SELECT content_parentid FROM tbl_contents WHERE CONTENT_ID = ".$parent;
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			if ($row['content_parentid'] != "") {
				$parents .= ",".$row['content_parentid'];
				getParent($row['content_parentid']);
			}
		}	
	}	
	
	// er is een edit of een insert geweest, id tot laatste parent in array zetten

	$parents = $_GET['id'];
	$query = "SELECT content_parentid,content_archive FROM tbl_contents WHERE CONTENT_ID = ".$_GET['id'];
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		if ($row['content_parentid'] != "") {
			//if ($row['content_archive'] != "T") {
				$parents .= ",".$row['content_parentid'];
			//}
			getParent($row['content_parentid']);
		}
	}
	
	$parent_div = split(",",$parents);
	
}
?>
<?
$taal = "Nederlands";
$lan = 1;

$aantal_cell = 0;
$teller = 0;

$query = "SELECT tbl_contents.*, Count(tbl_visits.page) AS TOTALCOUNT FROM tbl_contents LEFT JOIN tbl_visits ON tbl_contents.CONTENT_ID = tbl_visits.page WHERE content_archive = 'F' AND content_parentid IS NULL AND content_lang = '".$lan."' GROUP BY tbl_contents.CONTENT_ID order by content_seq";
$result = mysql_query($query);
$rij_teller = 0;
while ($row = mysql_fetch_array($result)) {
		if ($row["content_visible"] == 'T') {
			$tab = "<td width=\"50\" align=\"center\"><img align=absmiddle src=images/green_on.bmp border=0></a>&nbsp;<a title=\"Zet op niet actief\" href=index.php?page=cmsedit&id=".$row["CONTENT_ID"]."&act=2><img src=images/red_off.bmp border=0 align=absmiddle></a></td>";
		}else{	
			$tab = "<td width=\"50\" align=\"center\"><a title=\"Zet op actief\" href=index.php?page=cmsedit&id=".$row["CONTENT_ID"]."&act=1><img align=absmiddle src=images/green_off.bmp border=0></a>&nbsp;<img src=images/red_on.bmp border=0 align=absmiddle></td>";
		}
	if (strlen($row['content_name'])>20) {
		$content_name = substr($row['content_name'],0,17)."...";
	} else {
		$content_name = $row['content_name'];
	}
	$rij_teller++;
	$query = "SELECT * FROM tbl_contents WHERE content_archive = 'F' AND content_parentid = ".$row['CONTENT_ID'];
	$result2 = mysql_query($query);
	if (mysql_num_rows($result2) > 0) {
		// parent heeft children
		$teller++;
?>
<table border="0" cellpadding="0" cellspacing="0">
  <tr> 
<?
		$min = "";
		for ($j=0;$j<count($parent_div);$j++) {
			if ($parent_div[$j]==$row['CONTENT_ID']) {
				$min = "true";
			}
		}
		if ($rij_teller != mysql_num_rows($result)) {  
			$nodes = "true";
			if ($min!="") {
?>
    <td width="21"><a href="javascript:void(0);" onClick="javascript:showitem(<? echo($row['CONTENT_ID']); ?>,'mid');"><img src="images/ftv2mnode.gif" id="plus<? echo($row['CONTENT_ID']); ?>" width="16" height="22" border="0"></a></td>
<?
			} else {
?>
    <td width="21"><a href="javascript:void(0);" onClick="javascript:showitem(<? echo($row['CONTENT_ID']); ?>,'mid');"><img src="images/ftv2pnode.gif" id="plus<? echo($row['CONTENT_ID']); ?>" width="16" height="22" border="0"></a></td>
<?		
			}
		} else {
			$nodes = "false";
			if ($min!="") {
?>
    <td width="21"><a href="javascript:void(0);" onClick="javascript:showitem(<? echo($row['CONTENT_ID']); ?>,'end');"><img src="images/ftv2mlastnode.gif" id="plus<? echo($row['CONTENT_ID']); ?>" width="16" height="22" border="0"></a></td>
<?
			} else {
?>
		<td width="21"><a href="javascript:void(0);" onClick="javascript:showitem(<? echo($row['CONTENT_ID']); ?>,'end');"><img src="images/ftv2plastnode.gif" id="plus<? echo($row['CONTENT_ID']); ?>" width="16" height="22" border="0"></a></td>
<?
			}
		}
?>      <td valign="top">
    	<table width="100%" border="0" cellspacing="2" cellpadding="0">
        <tr> 
          <td width="140"><a href="index.php?page=cmsedit&lan=<?=$lan?>&id=<? echo($row['CONTENT_ID']); ?>"><? echo($content_name); ?></a></td><?=$tab?>
          <td valign="top"><a href="index.php?page=cmsedit&lan=<?=$lan?>&id=<? echo($row['CONTENT_ID']); ?>">[ wijzig ]</a> 
          <a href="index.php?page=cmsedit&lan=<?=$lan?>&parent=<? echo($row['CONTENT_ID']); ?>">[ nieuw ]</a>   
          <a href="index.php?page=cmsstructuur&lan=<?=$lan?>&id=<? echo($row['CONTENT_ID']); ?>">[ volgorde ]</a>
          <? if (STATISTIEKEN=="1") { ?><a href="stats.php?id=<? echo($row['CONTENT_ID']); ?>">[ statistieken ]</a> <? } ?>
          <? if (ARCHIVEER=="1") { ?><a href="archiveer.php?lan=<?=$lan?>&id=<? echo($row['CONTENT_ID']); ?>">[ archiveer ]</a> <? } ?>
          <? if (VISITS=="1") { ?>(<?=$row['TOTALCOUNT']?>)<? } ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?

	// hier de children doorlopen, bij elk child functie aanroepen voor wegschrijven table...
	schrijfTabel($row['CONTENT_ID'],$nodes,$id);
	
	} else {
		// parent heeft geen children
?>
<table border="0" cellpadding="0" cellspacing="0">
  <tr> 
<?
		if ($rij_teller != mysql_num_rows($result)) {  
?> 
    <td width="21"><img src="images/ftv2node.gif" width="16" height="22"></td>
<?
		} else {
?>
		<td width="21"><img src="images/ftv2lastnode.gif" width="16" height="22"></td>
<?
		}
?>    <td valign="top">
    	<table width="100%" border="0" cellspacing="2" cellpadding="0">
        <tr> 
          <td width="140"><a href="index.php?page=cmsedit&lan=<?=$lan?>&id=<? echo($row['CONTENT_ID']); ?>"><? echo($content_name); ?></a></td><?=$tab?>
          <td valign="top"><a href="index.php?page=cmsedit&lan=<?=$lan?>&id=<? echo($row['CONTENT_ID']); ?>">[ wijzig ]</a> 
          <a href="edit.php?lan=<?=$lan?>&parent=<? echo($row['CONTENT_ID']); ?>">[ nieuw ]</a>    
          <? if (STATISTIEKEN=="1") { ?><a href="stats.php?id=<? echo($row['CONTENT_ID']); ?>">[ statistieken ]</a> <? } ?>
          <? if (ARCHIVEER=="1") { ?><a href="archiveer.php?lan=<?=$lan?>&id=<? echo($row['CONTENT_ID']); ?>">[ archiveer ]</a> <? } ?>
          <? if (VISITS=="1") { ?>(<?=$row['TOTALCOUNT']?>)<? } ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?
	}
}
?>
<script>
<!--
var item = null;
var item = Array();
<?
//$item_teller = $teller;
if ($ids!="") {
	$item_teller = split(",",$ids);
	for($i=0;$i<count($item_teller);$i++) {
		$value = "false";
		for($j=0;$j<count($parent_div);$j++) {
			if ($parent_div[$j] == $item_teller[$i]) {
				$value = "true";
			}
		}
	?>
	item[<? echo $item_teller[$i]; ?>] = "<?=$value?>";
	<?
	}
	for($j=0;$j<count($parent_div);$j++) {
		if ($parent_div[$j]!="") {
	?>
	document.getElementById('<?=$parent_div[$j]?>').style.display='inline';
	<?
		}
	}
	
}
?>
-->
</script>