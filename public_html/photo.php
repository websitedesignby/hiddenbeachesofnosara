<?
require("inc/config.php");
require("inc/functions.php");
require("inc/config_photogallery.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $photogallery_web_title; ?></title>
<? require("inc/photogallery.css.php"); ?>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<?
$photo_id = $_REQUEST['photo'];

if (is_numeric($photo_id)) {
	$rs = mysql_query("SELECT photo_extension, photo_title, gallery_id, category_id FROM photos WHERE photo_id=".$photo_id);
	if (mysql_num_rows($rs) > 0) {
		$photo_extension = mysql_result($rs,0,0);
		$photo_title = mysql_result($rs,0,1);
		$gallery_id = mysql_result($rs,0,2);
		$category_id = mysql_result($rs,0,3);
		echo "<div class=\"fullsizephoto\"><img src=\"".$photosdir.$photo_id.".jpg\" alt=\"\" border=\"0\" /></div>\n
		 <p id=\"desc\">".$photo_title."</p>\n";
	}
	
	# get photo ID's for previous and next photos
	if ($gallery_prevnextlinks == "y" || $gallery_numberlinks == "y") {
		$rs = mysql_query("SELECT photo_id FROM photos WHERE gallery_id=".$gallery_id." AND category_id=".$category_id." ORDER BY photo_order");
		if (mysql_num_rows($rs) > 0) {
			$prev_photo_id = 0;
			$next_photo_id = 0;
			$get_next_photo_id = false;
			$counter = 0;
			while (($rows = mysql_fetch_array($rs)) && ($break == false)) {
				$counter++;
	
				if ($get_next_photo_id == true) {
					$next_photo_id = $rows[0];
					$get_next_photo_id = false;
				}
	
				if ($rows[0] == $photo_id) {
					$photolinks .= " <b>".$counter."</b> ";
					$prev_photo_id = $current_photo_id;
					$get_next_photo_id = true;
				}
				else
					$photolinks .= " <a href=\"photo.php?photo=".$rows[0]."\">".$counter."</a> ";
				
				$current_photo_id = $rows[0];
				
			}
			# print navigation links
			$prev_link = $next_link = "&nbsp;";
			if ($prev_photo_id > 0 && $gallery_prevnextlinks == "y")
				$prev_link = "<p><b><a href=\"photo.php?photo=".$prev_photo_id."\">Previous</a></b></p>";
			if ($next_photo_id > 0 && $gallery_prevnextlinks == "y")
				$next_link = "<p align=\"right\"><b><a href=\"photo.php?photo=".$next_photo_id."\">Next</a></b></p>";
			if ($gallery_numberlinks == "n")
				$photolinks = "&nbsp;";
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>
			 <td width=\"9%\">".$prev_link."</td><td width=\"82%\"><p align=\"center\">".$photolinks."</p></td><td width=\"9%\">".$next_link."</td>
			 </tr></table>";
		}
	}
}

mysql_close($link);
?>
</body>
</html>