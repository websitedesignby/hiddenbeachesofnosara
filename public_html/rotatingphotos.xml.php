<?
include("inc/config.php");
include("inc/config_rotatingphotos.php");
require("inc/functions.php");

$rs = mysql_query("SELECT rotating_timer, rotating_fadetime, rotating_order, rotating_looping FROM rotatingspecs");
if (mysql_num_rows($rs) == 0) { # default values
	$rotating_timer = 5;
	$rotating_fadetime = 2;
	$rotating_order = "sequential";
	$rotating_looping = "yes";
}
else {
	$row = mysql_fetch_array($rs);
	$rotating_timer = $row[0];
	$rotating_fadetime = $row[1];
	$rotating_order = $row[2];
	$rotating_looping = $row[3];
}

$xml_text = "<gallery timer=\"".$rotating_timer."\" order=\"".$rotating_order."\" fadetime=\"".$rotating_fadetime."\" looping=\"".$rotating_looping."\" xpos=\"0\" ypos=\"0\">\n";
$xml_text .= "<text><![CDATA[]]></text>\n"; // this node can be used for text to appear over the gallery
$xml_text .= "<images>\n";
$rs = mysql_query("SELECT photo_id, photo_link FROM rotatingphotos ORDER BY photo_order");
while ($rows = mysql_fetch_array($rs)) {
	$xml_text .= "<image path=\"".$rotatingphotosdir.$rows[0].".jpg\" url=\"".$rows[1]."\" />\n";
}
$xml_text .= "</images>\n";
$xml_text .= "</gallery>";
echo $xml_text;

?>