<?
require("inc/config.php");
require("inc/config_areamap.php");
require("inc/functions.php");

$sql = "SELECT location_id, location_name, location_x, location_y, type_id, location_url, location_description
 FROM areamap_locations";
$rs = mysql_query($sql);
$locationcount = 0;
while ($row = mysql_fetch_array($rs)) {
	$locationcount++;
	$location_ids[$locationcount] = $row[0];
	$location_names[$locationcount] = $row[1];
	$location_x[$locationcount] = $row[2];
	$location_y[$locationcount] = $row[3];
	$type_id[$locationcount] = $row[4];
	if ($row[5] == "")
		$location_url[$locationcount] = "#";
	else
		$location_url[$locationcount] = $row[5].(($areamap_newwindow == "y")?"\" target=\"_blank":"");

	$location_tag[$locationcount] = "<b>".$row[1]."</b>";
	if ($row[6] <> "") 
		$location_tag[$locationcount] .= "<div style=&quot;width: ".$areamap_max_description_width."px;&quot;>".str_replace('"','&quot;',$row[6])."</div>";

	$rs2 = mysql_query("SELECT type_ext, type_width, type_height FROM areamap_types WHERE type_id=".$row[4]);
	if (mysql_num_rows($rs2) > 0) {
		$type_ext[$locationcount] = mysql_result($rs2,0,0);
		$type_width[$locationcount] = mysql_result($rs2,0,1);
		$type_height[$locationcount] = mysql_result($rs2,0,2);
	}
	else { # use the default image values
		$type_ext[$locationcount] = $areamap_default_ext;
		$type_width[$locationcount] = $areamap_default_width;
		$type_height[$locationcount] = $areamap_default_height;
	}
}
mysql_free_result($rs);
list($map_width, $map_height, $type, $attr) = getimagesize($areamapdir.$areamap_filename);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=  $areamap_web_title; ?></title>
<?= $meta_content; ?>
<link href="<?= $site_baseurl; ?>inc/pages.css" rel="stylesheet" type="text/css" />
<link href="<?= $site_baseurl; ?>css/styles.css" rel="stylesheet" type="text/css" />
<link href="<?= $site_baseurl; ?>css/menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="javascript" src="<?= $site_baseurl; ?>inc/embed.js"></script>
<script language="javascript" src="<?= $site_baseurl; ?>scripts/dropdown.js"></script>
<? if ($pages_show_menu == "y") { ?>
<!-- ################# BEGIN MENU HEADER DATA ################# -->
<link href="<?= $site_baseurl; ?>css/tcp_menu.css.php" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?= $site_baseurl; ?>inc/tcp_menu.js"></script>
<? include("inc/tcp_menu_headdata.php"); ?>
<!-- ################## END MENU HEADER DATA ################## -->
<? } # / show menu ?>
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<?= $site_baseurl; ?>scripts/pngfix.js"></script>
<![endif]-->
<script language="javascript" src="<?= $site_baseurl; ?>inc/scripts.js"></script>
<script language="javascript" src="<?= $site_baseurl; ?>inc/qTip.js" type="text/javascript"></script>
<style type="text/css">
<!--
#map {
	width : <?= $map_width; ?>px; 
	height : <?= $map_height; ?>px;
	padding : 0px;
	margin : 0px;
	position : relative;
}
<? for ($x = 1; $x <= $locationcount; $x++) { ?>
#location<?= $x; ?> { 
	position: absolute;
	padding: 0px;
	left: <?= ($location_x[$x]-($type_width[$x]/2)); ?>px;
	top: <?= ($location_y[$x]-($type_height[$x]/2)); ?>px;
	width: <?= $type_width[$x]; ?>px;
	height: <?= $type_height[$x]; ?>px;
}
<? } ?>

div#qTip {
	padding: 3px;
	border: <?= $areamap_tag_border_size; ?>px solid <?= $areamap_tag_border_color; ?>;
	display: none;
	background: <?= $areamap_tag_bg_color; ?>;
	color: <?= $areamap_tag_font_color; ?>;
	font-size: <?= $areamap_tag_font_size; ?>px;
	font-family: <?= $areamap_tag_font; ?>;
	text-align: <?= $areamap_tag_font_align; ?>;
	position: absolute;
	z-index: 1000;
}
-->
</style>
</head>
<body>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="bg-container">&nbsp;</td>
    <td><div id="container">
      <div id="header">
        <div id="nav">
      <?php include("menu.php"); ?>
        </div>
      </div>
      <!-- End #Nav -->
      <div id="content">
        <div id="subheader"><img src="<?= $site_baseurl; ?>images/logo.png" alt="Playas Escondidas" name="logo" id="logo" /><img src="<?= $site_baseurl; ?>images/subheader-location.jpg" alt="rotating images" /></div>
        <div id="contentMap">
          <p>
            <?
echo "<h1 style=\"padding-bottom:22px\" >".$areamap_web_title."</h1>\n";
echo "<div align=\"center\"><div id=\"map\">";
for ($x = 1; $x <= $locationcount; $x++) {
	echo "<div id=\"location".$x."\"><a href=\"".$location_url[$x]."\" title=\"".$location_tag[$x]."\"><img src=\"".$areamapdir.$type_id[$x].$type_ext[$x]."\" alt=\"".$location_name[$x]."\" width=\"".$type_width[$x]."\" height=\"".$type_height[$x]."\" border=\"0\"></a></div>";
}
echo "<img src=\"".$areamapdir.$areamap_filename."\" height=\"".$map_height."\" width=\"".$map_width."\" alt=\"area map\">
 </div></div>";

# display icon legend
echo "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\">\n<tr>\n";
$sql = "SELECT DISTINCT t.type_id, t.type_name, t.type_ext, t.type_width, t.type_height FROM areamap_types t
 INNER JOIN areamap_locations l ON t.type_id=l.type_id ORDER BY t.type_order";
$rs = mysql_query($sql);
$counter = 0;
while ($rows = mysql_fetch_array($rs)) {
	if ($counter % 3 == 0) {
		echo "</tr>\n<tr>";
		$counter = 0;
	}
	echo "<td width=\"33%\" align=\"left\"><img src=\"".$areamapdir.$rows[0].$rows[2]."\" width=\"".$rows[3]."\" height=\"".$rows[4]."\" alt=\"".$rows[1]."\" align=\"absmiddle\" /> 
	 ".$rows[1]."</td>\n";
	$counter++;
}
echo "</tr>\n</table>\n";
?>
          </p>
        </div>
       </div>
      <!-- end #content -->
      <div id="footer">
      <?php include("footer.php"); ?>
      </div>
    </div></td>
    <td class="bg-containerEmpty">&nbsp;</td>
  </tr>
</table>
<!-- end #container -->
<!-- end #wrapper -->
</body>
</html>