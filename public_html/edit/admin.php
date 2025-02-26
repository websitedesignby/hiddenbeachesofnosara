<?
$this_page_id = 0;
require("../inc/config.php");
require("../inc/functions.php");
require("secure.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?= $cms_title; ?></title>
<link href="inc/master.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? require("inc/header.php"); ?>
<div id="toolbar">
	<div id="toolbar_logo"><a href="admin.php"><img src="images/logos/4what.gif" width="38" height="38" border="0" alt="Toolbox" /></a></div>
	<h2><a href="admin.php">Administration</a></h2>
</div>

<div id="contentarea">
<div id="content">

	<div class="tabs">
		<ul>
			<li class="selected"><a href="admin.php">Tools</a></li>
			<li><a href="config_admin.php">Toolbox Configuration</a></li>
			<? if ($cmsusertype == "a") { # only 4What should see this link ?>
			<li><a href="admin_select.php">Tool Selector</a></li>
			<? } ?>
			<li><a href="styles.php">WYSIWYG Styles</a></li>
			<li><a href="phpinfo.php">PHP Info</a></li>
		</ul>
	</div>
	
	<p>The following tools are available are available for your Web Toolbox. Tools in <strong>bold text</strong> are currently in use on your site.</p>
	<p>These tools are always being upgraded to make maintenance of web sites easier. If an upgrade notice is posted beside one of the tools, please contact  4What Interactive to find out how the upgrades can be incorporated into your Toolbox.</p>
	
	<?
	$displaynewheading = false;
	$file = fopen ("http://www.4whatbsg.com/toolbox/edit/current_tool_list.php", "r");
	while (!feof ($file)) {
		$line = fgets($file,1024);
		if (substr($line, 0, 1) == "$") { # if the line starts with "$", it's a variable assignment
			$variable_name = substr($line, 1, strpos($line,"=")-2); # grab text after "$" and before "="
			$variable_value = substr($line, strpos($line,"=")+1, (strpos($line,";")-strpos($line,"=")-1)); # grab text between "=" and ";"
			if (substr($variable_value,1,1) == '"') { # the variable value is encapsulated in quotes
				$variable_value = substr($variable_value,2,strlen($variable_value)-3); # remove the quotes
			}
			${$variable_name} = trim($variable_value);
			if (strstr($variable_name,"page_filename")) {
				# is this a new tool that the client doesn't have?
				$page_id = substr($variable_name,13,strlen($variable_name)-13);
				$rs = mysql_query("SELECT page_id FROM wma_pages WHERE page_id=".$page_id);
				if (mysql_num_rows($rs) == 0) {
					if ($displaynewheading == false) {
						echo "<h2>New Tools!</h2>
						 <p>Visit <a href=\"http://www.4whatbsg.com/toolbox/\" target=\"_blank\">www.4whatbsg.com/toolbox</a> to see these new tools in action and contact 4What to add these to your web site!</p>";
						$displaynewheading = true;
					}
					echo "<div style=\"width: 170px; border: 1px solid #B1C4D6; background-color: #FFFFFF; float: left; text-align: left; margin-right: 10px; margin-bottom: 10px; padding: 5px;\">";
					 $logo_file = ${'page_filename'.$page_id};
					 $logo_file = substr($logo_file,0,strlen($logo_file)-1); # remove last forward slash
					 $logo_filename = "http://www.4whatbsg.com/toolbox/edit/images/logos/".$logo_file.".gif";
					 $notfound_filename = "http://www.4whatbsg.com/toolbox/edit/images/logos/4what.gif";
					 # don't display rotating photos multi (43) or basic gallery (37) or custom
					 if ($rows[0] == 43 || $rows[0] == 37)
						 echo "<img src=\"".$notfound_filename."\" height=\"38\" width=\"38\" alt=\"".$rows[2]."\" align=\"absmiddle\" />";
					 else
						 echo "<img src=\"".$logo_filename."\" height=\"38\" width=\"38\" alt=\"".$rows[2]."\" align=\"absmiddle\" />";
					echo "<strong>".${'page_name'.$page_id}."</strong></div>";
				}
			} # end page_version check
		}
	}
	fclose($file);
	?>
	
	<table border="0" cellspacing="0" cellpadding="0" class="record_table" style="clear: both;">
		<tr>
			<th>&nbsp;</th>
			<th><div style="padding-left: 20px;">Tool</div></th>
			<th colspan="2">Version</th>
		</tr>
	<?
	$rs = mysql_query("SELECT page_id, page_status, page_name, page_version, page_filename FROM wma_pages ORDER BY page_name");
	while ($rows = mysql_fetch_array($rs)) {
		if ($rows[0] <> 43) { #don't display rotating photos multi (43)
		?>
	  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
		<td class="actions" style="background-color: #FFFFFF; padding: 5px; text-align: center;"><?
		 $logo_file = substr($rows[4],0,strlen($rows[4])-1); # remove last forward slash
		 if ($logo_file == "homes")
			$logo_file = "homesforsale";
		 $logo_filename = "http://www.4whatbsg.com/toolbox/edit/images/logos/".$logo_file.".gif";
		 $notfound_filename = "http://www.4whatbsg.com/toolbox/edit/images/logos/4what.gif";
		 # custom tools don't have icons
		 if (strstr(strtolower($rows[3]),"c"))
			 echo "<img src=\"".$notfound_filename."\" height=\"38\" width=\"38\" alt=\"".$rows[2]."\" />";
		 else
			 echo "<img src=\"".$logo_filename."\" height=\"38\" width=\"38\" alt=\"".$rows[2]."\" />";
		?></td>
		<td><div style="padding-left: 20px;"><?= (($rows[1] == "y")?"<strong>":"").$rows[2].(($rows[1] == "y")?"</strong>":""); ?></div></td>
		<td><?= (($rows[1] == "y")?"<strong>":"").$rows[3].(($rows[1] == "y")?"</strong>":""); ?></td>
		<td><div class="alert" align="right">&nbsp;<?
		 if (($rows[3] <> ${'page_version'.$rows[0]}) && ($rows[1] == "y") && !strstr(strtolower($rows[3]),"c")) {
			echo "upgraded tool ".${'page_version'.$rows[0]}." available";
		 }
		 ?></div></td>
	  </tr>
		<?
		}
	}
	?>
	</table>
	
	<p>&nbsp;</p>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
