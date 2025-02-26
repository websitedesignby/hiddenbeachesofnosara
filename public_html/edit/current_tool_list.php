<?
# ALL OTHER TOOLBOXES LINK TO THIS PAGE - DO NOT DELETE THIS FILE!
require("../inc/config.php");
$rs = mysql_query("SELECT page_id, page_version, page_name, page_filename FROM wma_pages ORDER BY page_name");
while ($rows = mysql_fetch_array($rs)) {
	echo "\$page_version".$rows[0]." = \"".$rows[1]."\";\n";
	echo "\$page_name".$rows[0]." = \"".$rows[2]."\";\n";
	echo "\$page_filename".$rows[0]." = \"".$rows[3]."\";\n";
}
?>
