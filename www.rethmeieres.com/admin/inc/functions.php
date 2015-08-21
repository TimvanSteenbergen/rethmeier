<?
function schrijfTabel($id,$nodes,$home_nodes) {
	global $teller;
	global $ids;
	global $f_nodes;
	global $parent_div;
	$display="none";
	$f_rij_teller = 0;
	global $aantal_cell;
	$aantal_cell++;
	$f_nodes = $nodes;
	$f_query = "SELECT tbl_contents.*, Count(tbl_visits.page) AS TOTALCOUNT FROM tbl_contents LEFT JOIN tbl_visits ON tbl_contents.CONTENT_ID = tbl_visits.page WHERE content_archive = 'F' AND content_parentid = ".$id." GROUP BY tbl_contents.CONTENT_ID order by content_seq";
	$f_result = mysql_query($f_query);
	for ($i=0;$i<count($parent_div);$i++) {
		if ($parent_div[$i]==$id) {
			$display="inline";
		}
	}
	echo("<div id=\"".$id."\" style=\"display:".$display.";\">\n");
	if ($ids == "") {
		$ids = $id;
	} else {
		$ids .= ",".$id;
	}
	while($f_row = mysql_fetch_array($f_result)) {
		$lan = $f_row['content_lang'];
			if ($f_row["content_visible"] == 'T') {
				$tab = "<td width=\"50\" align=\"center\"><img src=images/green_on.bmp border=0 align=absmiddle></a>&nbsp;<a title=\"Zet op niet actief\" href=index.php?page=cmsedit&id=".$f_row["CONTENT_ID"]."&act=2><img src=images/red_off.bmp border=0 align=absmiddle></a></td>";
			}else{	
				$tab = "<td width=\"50\" align=\"center\"><a title=\"Zet op actief\" href=index.php?page=cmsedit&id=".$f_row["CONTENT_ID"]."&act=1><img src=images/green_off.bmp border=0 align=absmiddle></a>&nbsp;<img src=images/red_on.bmp border=0 align=absmiddle></td>";
			}
		if (strlen($f_row['content_name'])>20) {
			$content_name = substr($f_row['content_name'],0,17)."...";
		} else {
			$content_name = $f_row['content_name'];
		}
		$content_name = stripslashes($content_name);
		$f_rij_teller++;
		$f_query2 = "SELECT * FROM tbl_contents WHERE content_archive = 'F' AND content_parentid = ".$f_row['CONTENT_ID'];
		$f_result2 = mysql_query($f_query2);
		if (mysql_num_rows($f_result2) > 0) {
			// parent heeft children
			// while lus door children, functie weer aanroepen
			echo("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n");
	  		echo("\t<tr>\n");
			/*
			for ($i=0;$i<$aantal_cell;$i++) {
				// cellen met normale node
				if ($i == 0 && $aantal_cell > 1 && $home_nodes == "false") {
					echo("\t\t<td width=\"21\"><img src=\"images/ftv2blank.gif\" width=\"16\" height=\"22\"></td>\n");
				} else {
					if ($i == $aantal_cell-1 && $nodes == "false") {
						echo("\t\t<td width=\"21\"><img src=\"images/ftv2blank.gif\" width=\"16\" height=\"22\"></td>\n");
					} else {
						echo("\t\t<td width=\"21\"><img src=\"images/ftv2vertline.gif\" width=\"16\" height=\"22\"></td>\n");
					}
				}
			}
			*/
			
			$nodes_array = explode(",",$nodes);
			//echo $home_nodes;
			if ($home_nodes!="") {
				$f_query2 = "SELECT tbl_contents.*, Count(tbl_visits.page) AS TOTALCOUNT FROM tbl_contents LEFT JOIN tbl_visits ON tbl_contents.CONTENT_ID = tbl_visits.page WHERE content_archive = 'F' AND content_parentid = ".$home_nodes." GROUP BY tbl_contents.CONTENT_ID order by content_seq";
				$f_result2 = mysql_query($f_query2);
				if (mysql_num_rows($f_result2)<2 || mysql_num_rows($f_result2) == $f_rij_teller) {
					//$nodes_array[$aantal_cell-1] = "false";
				}
			}

			if ($f_rij_teller == mysql_num_rows($f_result)) {
				//$nodes_array[$aantal_cell-1] = "false";
			}
			for ($i=0;$i<$aantal_cell;$i++) {
				if ($nodes_array[$i] == "false") {
					echo("\t\t<td width=\"21\"><img src=\"images/ftv2blank.gif\" width=\"16\" height=\"22\"></td>\n");
				} else {
					echo("\t\t<td width=\"21\"><img src=\"images/ftv2vertline.gif\" width=\"16\" height=\"22\"></td>\n");
				}
			}
			$min = "";
			for ($j=0;$j<count($parent_div);$j++) {
				//echo $parent_div[$j];
				if ($parent_div[$j]==$f_row['CONTENT_ID']) {
					$min = "true";
				}
			}
			//echo $f_nodes;
			if ($f_rij_teller != mysql_num_rows($f_result)) {
				$f_nodes .= ",true";
				if ($min!="") {
					echo("\t\t<td width=\"21\"><a href=\"javascript:void(0);\" onClick=\"javascript:item(".$f_row['CONTENT_ID'].",'mid');\"><img src=\"images/ftv2mnode.gif\" id=\"plus".($f_row['CONTENT_ID'])."\" width=\"16\" height=\"22\" border=\"0\"></a></td>\n");
				} else {  
	    		echo("\t\t<td width=\"21\"><a href=\"javascript:void(0);\" onClick=\"javascript:item(".$f_row['CONTENT_ID'].",'mid');\"><img src=\"images/ftv2pnode.gif\" id=\"plus".($f_row['CONTENT_ID'])."\" width=\"16\" height=\"22\" border=\"0\"></a></td>\n");
				}
			} else {
				$f_nodes .= ",false";
				$temp_array = explode(",",$f_nodes);
				$temp_array[$aantal_cell] = "false";
				for ($i=0;$i<count($temp_array);$i++) {
					if ($i==0) {
						$f_nodes = $temp_array[$i];
					} else {
						$f_nodes .= ",".$temp_array[$i];
					}
				}
				if ($min!="") {
					echo("\t\t<td width=\"21\"><a href=\"javascript:void(0);\" onClick=\"javascript:item(".$f_row['CONTENT_ID'].",'end');\"><img src=\"images/ftv2mlastnode.gif\" id=\"plus".($f_row['CONTENT_ID'])."\" width=\"16\" height=\"22\" border=\"0\"></a></td>\n");
				} else {
					echo("\t\t<td width=\"21\"><a href=\"javascript:void(0);\" onClick=\"javascript:item(".$f_row['CONTENT_ID'].",'end');\"><img src=\"images/ftv2plastnode.gif\" id=\"plus".($f_row['CONTENT_ID'])."\" width=\"16\" height=\"22\" border=\"0\"></a></td>\n");
				}
			}
					echo("\t\t<td valign=\"top\">\n");
			    	echo("\t\t\t<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">\n");
			        echo("\t\t\t\t<tr>\n"); 
			          echo("\t\t\t\t\t<td width=\"140\"><a href=\"index.php?page=cmsedit&lan=".$lan."&id=".$f_row['CONTENT_ID']."\">".$content_name."</a></td>".$tab."\n");
			          echo("\t\t\t\t\t<td valign=\"top\"><a href=\"index.php?page=cmsedit&lan=".$lan."&id=".$f_row['CONTENT_ID']."\">[ wijzig ]</a> ");

			          	echo("<a href=\"index.php?page=cmsedit&lan=".$lan."&parent=".$f_row['CONTENT_ID']."\">[ nieuw ]</a> ");


			          	echo("<a href=\"cmsstructuur.php?lan=".$lan."&id=".$f_row['CONTENT_ID']."\">[ volgorde ]</a> ");

			          if (SUBSTATISTIEKEN=="1") {
			          	echo("<a href=\"stats.php?id=".$f_row['CONTENT_ID']."\">[ statistieken ]</a> ");
			          }
			          if (SUBARCHIVEER=="1") {
			          	echo("<a href=\"archiveer.php?lan=".$lan."&id=".$f_row['CONTENT_ID']."\">[ archiveer ]</a> ");
			          }
			          if (SUBVISITS=="1") {
			          	echo("(".$f_row['TOTALCOUNT'].")");
			          }
			          echo("</td>\n");
			        echo("\t\t\t\t</tr>\n");
			      echo("\t\t\t</table>\n");
			    echo("\t\t</td>\n");
				echo("\t</tr>\n");
			echo("</table>\n");
			$teller++;
			
			schrijfTabel($f_row['CONTENT_ID'],$f_nodes,$id);
			
		} else {
			// parent heeft geen children
			// gewone tabel schrijven
			echo("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n");
	  		echo("\t<tr>\n");
	  	/*
	  	for ($i=0;$i<$aantal_cell;$i++) {
				// cellen met normale node
				if ($i == 0 && $aantal_cell > 1 && $home_nodes == "false") {
					echo("\t\t<td width=\"21\"><img src=\"images/ftv2blank.gif\" width=\"16\" height=\"22\"></td>\n");
				} else {
					if ($i == $aantal_cell-1 && $nodes == "false") {
						echo("\t\t<td width=\"21\"><img src=\"images/ftv2blank.gif\" width=\"16\" height=\"22\"></td>\n");
					} else {
						echo("\t\t<td width=\"21\"><img src=\"images/ftv2vertline.gif\" width=\"16\" height=\"22\"></td>\n");
					}
				}
			}
			*/
			$nodes_array = explode(",",$nodes);
			//echo "rij_teller=".$f_rij_teller."<br>";
			if ($home_nodes!="") {
				$f_query2 = "SELECT tbl_contents.*, Count(tbl_visits.page) AS TOTALCOUNT FROM tbl_contents LEFT JOIN tbl_visits ON tbl_contents.CONTENT_ID = tbl_visits.page WHERE content_archive = 'F' AND content_parentid = ".$home_nodes." GROUP BY tbl_contents.CONTENT_ID order by content_seq";
				$f_result2 = mysql_query($f_query2);
				if (mysql_num_rows($f_result2)<2 || mysql_num_rows($f_result2) == $f_rij_teller) {
					//$nodes_array[$aantal_cell-1] = "false";
				}
			}
			
			if ($f_rij_teller == mysql_num_rows($f_result)) {
				//$nodes_array[$aantal_cell-1] = "false";
			}
			for ($i=0;$i<$aantal_cell;$i++) {
				//echo $nodes_array[$i].",";
				if ($nodes_array[$i] == "false") {
					echo("\t\t<td width=\"21\"><img src=\"images/ftv2blank.gif\" width=\"16\" height=\"22\"></td>\n");
				} else {
					echo("\t\t<td width=\"21\"><img src=\"images/ftv2vertline.gif\" width=\"16\" height=\"22\"></td>\n");
				}
			}
			//echo "<br>";
			if ($f_rij_teller != mysql_num_rows($f_result)) {
	    		echo("\t\t<td width=\"21\"><img src=\"images/ftv2node.gif\" width=\"16\" height=\"22\"></td>\n");
			} else {
					echo("\t\t<td width=\"21\"><img src=\"images/ftv2lastnode.gif\" width=\"16\" height=\"22\"></td>\n");
					$temp_array = split(",",$f_nodes);
					for ($i=0;$i<count($temp_array)-1;$i++) {
						if ($i==0) {
							$f_nodes = $temp_array[$i];
						} else {
							$f_nodes .= ",".$temp_array[$i];
						}
					}
			}
					echo("\t\t<td valign=\"top\">\n");
			    	echo("\t\t\t<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">\n");
			        echo("\t\t\t\t<tr>\n"); 
			          echo("\t\t\t\t\t<td width=\"140\"><a href=\"index.php?page=cmsedit&lan=".$lan."&id=".$f_row['CONTENT_ID']."\">".$content_name."</a></td>".$tab."\n");
			          echo("\t\t\t\t\t<td valign=\"top\"><a href=\"index.php?page=cmsedit&lan=".$lan."&id=".$f_row['CONTENT_ID']."\">[ wijzig ]</a> ");

			          	echo("<a href=\"index.php?page=cmsedit&lan=".$lan."&parent=".$f_row['CONTENT_ID']."\">[ nieuw ]</a> ");

			          /*
			          geen children, dus geen volgorde
			          if (SUBVOLGORDE=="1") {
			          	echo("<a href=\"volgorde.php?lan=".$lan."&id=".$f_row['CONTENT_ID']."\">[ volgorde ]</a> ");
			          }
			          */
			          if (SUBSTATISTIEKEN=="1") {
			          	echo("<a href=\"stats.php?id=".$f_row['CONTENT_ID']."\">[ statistieken ]</a> ");
			          }
			          if (SUBARCHIVEER=="1") {
			          	echo("<a href=\"archiveer.php?lan=".$lan."&id=".$f_row['CONTENT_ID']."\">[ archiveer ]</a> ");
			          }
			          if (SUBVISITS=="1") {
			          	echo("(".$f_row['TOTALCOUNT'].")");
			          }
			          echo("</td>\n");
			        echo("\t\t\t\t</tr>\n");
			      echo("\t\t\t</table>\n");
			    echo("\t\t</td>\n");
				echo("\t</tr>\n");
			echo("</table>\n");
		}
	}
	echo("</div>\n");
	$aantal_cell--;
}
?>