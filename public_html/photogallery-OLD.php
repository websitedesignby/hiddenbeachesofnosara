<?
require("inc/config.php");
require("inc/functions.php");
require("inc/config_photogallery.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $photogallery_web_title; ?></title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<? require("inc/photogallery.css.php"); ?>
<script language="javascript" src="inc/photogallery.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<div align="center"><div id="pagearea">

<?
echo "<h1>".$photogallery_web_title."</h1>\n";

$gallery_id = $_REQUEST['gallery'];
$category_id = $_REQUEST['category'];
$photo_id = $_REQUEST['photo'];
if ($photogallery_enable_galleries == "n") $gallery_id = 0;

if ($photogallery_enable_galleries == "y") {
	# GET GALLERY INFO
	if (is_numeric($gallery_id)) {
		$rs = mysql_query("SELECT gallery_name, gallery_intro FROM photo_galleries WHERE gallery_id=".$gallery_id);
		if (mysql_num_rows($rs) > 0) {
			$gallery_name = mysql_result($rs,0,0);
			$gallery_intro = mysql_result($rs,0,1);
			if ($gallery_intro <> "")
				$gallery_intro = "<p>".nl2br($gallery_intro)."</p>\n";
		}
	}
	$rs = mysql_query("SELECT gallery_id, gallery_name FROM photo_galleries ORDER BY gallery_order");
	$num_galleries = mysql_num_rows($rs);
	if ($num_galleries == 1) {
		$gallery_id = mysql_result($rs,0,0);
		$gallery_name = mysql_result($rs,0,1);
	}
	elseif ($num_galleries > 1) {
		$gallery_dropdown = "<p><form><select onchange=\"JumpMenu(this)\" size=\"1\" class=\"formfield\">
		 <option value=\"photogallery.php\">Select A Gallery Here</option>\n";
		while ($rows = mysql_fetch_array($rs)) {
			$gallery_dropdown .= "<option value=\"photogallery.php?gallery=".$rows[0]."\"";
			if ($rows[0] == $gallery_id) {
				$gallery_dropdown .= " selected=\"selected\"";
				$gallery_links .= "<b>".$rows[1]."</b> | ";
			}
			else
				$gallery_links .= "<a href=\"photogallery.php?gallery=".$rows[0]."\">".$rows[1]."</a> | ";
			$gallery_dropdown .= ">".$rows[1]."</option>\n";
		}
		$gallery_dropdown .= "</select></form></p>\n";
		# erase the gallery if the config doesn't specify
		if ($display_gallery_dropdown == "n") 
			$gallery_dropdown = "";
		$gallery_links = "<p>".substr($gallery_links, 0, strlen($gallery_links)-3)."</p>\n";
	}
} # end enable galleries check

# GET CATEGORY INFO
if (is_numeric($gallery_id)) {
	$sql = "SELECT c.category_id, c.category_name, c.photo_id, p.photo_extension FROM photo_categories c
	 INNER JOIN photos p ON c.photo_id=p.photo_id WHERE c.gallery_id=".$gallery_id." ORDER BY c.category_order";
	$rs = mysql_query($sql);
	$num_categories = mysql_num_rows($rs);
	if ($num_categories > 0) {
		$counter = 0;
		$tdwidth = round(100/$category_num_thumbnails);
		$category_thumbnails = "<table cellpadding=\"0\" cellspacing=\"5\" width=\"100%\" border=\"0\"><tr>";
		$category_links = "<ul class=\"categoryUL\">\n";
		# if there are uncategorized photos, display that link first
		$rs2 = mysql_query("SELECT photo_id FROM photos WHERE gallery_id=".$gallery_id." AND category_id=0");
		if (mysql_num_rows($rs2) == 0)
			$num_uncategorized = 0;
		else
			$num_uncategorized = mysql_result($rs2,0,0);
		if ($num_uncategorized > 0)
			$category_links .= "<a href=\"photogallery.php?gallery=".$gallery_id."&category=0\">".$gallery_name."</a><br>\n";
		while ($rows = mysql_fetch_array($rs)) {
			if (($counter % $category_num_thumbnails == 0) && ($counter > 0)) {
				$category_thumbnails .= "</tr><tr><td colspan=\"".$category_num_thumbnails."\">&nbsp;</td></tr><tr>";
				$counter = 0;
			}
			$category_thumbnails .= "<td width=\"".$tdwidth."%\" class=\"categorythumbnail\"><a href=\"photogallery.php?gallery=".$gallery_id."&category=".$rows[0]."\">".$rows[1]."</a><br>
			 <a href=\"photogallery.php?gallery=".$gallery_id."&category=".$rows[0]."\"><img src=\"".$photosdir."t-".$rows[2].$rows[3]."\" border=\"0\" alt=\"".$rows[1]."\"></a><br>
			 </td>\n";
			$counter++;
			$category_links .= "<li><a href=\"photogallery.php?gallery=".$gallery_id."&category=".$rows[0]."\">".$rows[1]."</a></li>\n";
		}
		for ($x = $counter; $x < $category_num_thumbnails; $x++)
			$category_thumbnails .= "<td width=\"".$tdwidth."%\">&nbsp;</td>";
		$category_thumbnails .= "</tr></table>\n";
		$category_links .= "</ul>\n";
		# don't display category thumbnails if this is a photo viewing page
		if (is_numeric($photo_id))
			$category_thumbnails = "";
	}
}

# GET PHOTOS
if (is_numeric($gallery_id)) { # display galleries

	if (is_numeric($photo_id)) 
		$sql = "SELECT photo_id, photo_title FROM photos WHERE photo_id=".$photo_id;
	else 
		$sql = "SELECT photo_id, photo_title FROM photos WHERE gallery_id=".$gallery_id." AND category_id=".
		 ((is_numeric($category_id))?$category_id:"0")." ORDER BY photo_order LIMIT 1";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) > 0) {
		$initial_photo_id = mysql_result($rs,0,0);
		$initial_caption = mysql_result($rs,0,1);
		# photos without captions mess up the javascript, so put an nbsp in to correct this error
		if ($initial_caption == "") $initial_caption = "&nbsp;";
	}
	
	$sql = "SELECT photo_id, photo_title, photo_name FROM photos WHERE gallery_id=".$gallery_id." AND category_id=".
	 ((is_numeric($category_id))?$category_id:"0")." ORDER BY photo_order";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) > 0) {
		if ($gallery_thumbnail_position == "left")
			$thumbnailstyle = "thumbnailsleft";
		elseif ($gallery_thumbnail_position == "right")
			$thumbnailstyle = "thumbnailsright";
		else
			$thumbnailstyle = "thumbnails";

		if (is_numeric($gallery_thumbnail_rows))
			$thumbnails = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"".$thumbnailstyle."\"><tr valign=\"top\">\n";
		elseif ($gallery_thumbnail_rows == "scroll" && ($gallery_thumbnail_position == "above" || $gallery_thumbnail_position == "below" || $gallery_thumbnail_position == "")) 
			$thumbnails = "<div class=\"".$thumbnailstyle." thumbnailoverflowhorizontal\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>";
		elseif ($gallery_thumbnail_rows == "scroll" && ($gallery_thumbnail_position == "left" || $gallery_thumbnail_position == "right")) 
			$thumbnails = "<div class=\"".$thumbnailstyle." thumbnailoverflowvertical\">\n";
		$counter = 0;
		if (is_numeric($gallery_thumbnail_rows)) 
			$tdwidth = round(100/$gallery_thumbnail_rows)."%";
		while ($rows = mysql_fetch_array($rs)) {
			$photo_title = $rows[1];
			# photos without captions mess up the javascript, so put an nbsp in to correct this error
			if ($photo_title == "") $photo_title = "&nbsp;";
			if ($gallery_full_position == "pop")
				$photo_link = "href=\"photo.php?photo=".$rows[0]."\" onClick=\"NewWindow(this.href,'pop','750','550','yes');return false;\"";
			else
				$photo_link = "onclick=\"return showPic(this)\" href=\"".$photosdir.$rows[0].".jpg\" title=\"".$photo_title."\"";
			# determine what text goes above and below the image
			$text_above_photo = $text_below_photo = "";
			if ($photo_above <> "") {
				$text_above_photo = "<div class=\"abovephoto\"><a ".$photo_link.">";
				if ($photo_above == "name")
					$text_above_photo .= $rows[2];
				elseif ($photo_above == "view")
					$text_above_photo .= "View &gt;";
				elseif ($photo_above == "full")
					$text_above_photo .= "View Full Size";
				$text_above_photo .= "</a></div>";
			}
			if ($photo_below <> "") {
				$text_below_photo = "<div class=\"belowphoto\"><a ".$photo_link.">";
				if ($photo_below == "name")
					$text_below_photo .= $rows[2];
				elseif ($photo_below == "view")
					$text_below_photo .= "View &gt;";
				elseif ($photo_below == "full")
					$text_below_photo .= "View Full Size";
				$text_below_photo .= "</a></div>";
			}
			# display the thumbnails
			if (is_numeric($gallery_thumbnail_rows)) {
				if ($counter % $gallery_thumbnail_rows == 0 && $counter > 0)
					$thumbnails .= "</tr>\n<tr valign=\"top\">\n";
				$thumbnails .= "<td width=\"".$tdwidth."\"><div class=\"photothumbnail\">".$text_above_photo."
				 <a ".$photo_link."><img src=\"".$photosdir."t-".$rows[0].".jpg\" alt=\"".$rows[1]."\" border=\"0\" vspace=\"5\" hspace=\"5\" /></a>
				 ".$text_below_photo."</div></td>\n";
			}
			elseif ($gallery_thumbnail_rows == "scroll" && ($gallery_thumbnail_position == "left" || $gallery_thumbnail_position == "right")) {
				$thumbnails .= "<div class=\"photothumbnail\">".$text_above_photo."
				 <a ".$photo_link."><img src=\"".$photosdir."t-".$rows[0].".jpg\" alt=\"".$rows[1]."\" border=\"0\" /></a>
				 ".$text_below_photo."</div>";
			}
			elseif ($gallery_thumbnail_rows == "scroll" && ($gallery_thumbnail_position == "above" || $gallery_thumbnail_position == "below" || $gallery_thumbnail_position == "")) {
				$thumbnails .= "<td width=\"".$tdwidth."\"><div class=\"photothumbnail\">".$text_above_photo."
				 <a ".$photo_link."><img src=\"".$photosdir."t-".$rows[0].".jpg\" alt=\"".$rows[1]."\" border=\"0\" vspace=\"5\" hspace=\"5\" /></a>
				 ".$text_below_photo."</div></td>";
			}
			$counter++;
			$arrPhotoIDs[$counter] = $rows[0];
		}
		if (is_numeric($gallery_thumbnail_rows))
			$thumbnails .= "</tr></table>\n";
		elseif ($gallery_thumbnail_rows == "scroll" && ($gallery_thumbnail_position == "above" || $gallery_thumbnail_position == "below" || $gallery_thumbnail_position == "")) 
			$thumbnails .= "</tr></table></div>\n";
		elseif ($gallery_thumbnail_rows == "scroll" && ($gallery_thumbnail_position == "left" || $gallery_thumbnail_position == "right")) 
			$thumbnails .= "</div>\n";
	}
	
	# get photo ID's for previous and next photos
	if (is_numeric($gallery_id)) {
		$sql = "SELECT photo_id FROM photos WHERE gallery_id=".$gallery_id." AND category_id=".((is_numeric($category_id))?$category_id:"0")." ORDER BY photo_order";
		$rs = mysql_query($sql);
		if (mysql_num_rows($rs) > 1) { # there's no need to navigate with just one photo
			$prev_photo_id = 0;
			$next_photo_id = 0;
			$get_next_photo_id = false;
			$counter = 0;
			while ($rows = mysql_fetch_array($rs)) {
				$counter++;
	
				if ($get_next_photo_id == true) {
					$next_photo_id = $rows[0];
					$get_next_photo_id = false;
				}
	
				if ($rows[0] == $initial_photo_id) {
					$photolinks .= " <b>".$counter."</b> ";
					$prev_photo_id = $current_photo_id;
					$get_next_photo_id = true;
				}
				else
					$photolinks .= " <a href=\"photogallery.php?gallery=".$gallery_id."&category=".$category_id."&photo=".$rows[0]."\">".$counter."</a> ";
				
				$current_photo_id = $rows[0];
			}
			# print navigation links
			if ($gallery_full_position == "page") {
				$prev_link = $next_link = "&nbsp;";
				if ($prev_photo_id > 0 && $gallery_prevnextlinks == "y") 
					$prev_link = "<p><b><a href=\"photogallery.php?gallery=".$gallery_id."&category=".$category_id."&photo=".$prev_photo_id."\">Previous</a></b></p>";
				if ($next_photo_id > 0 && $gallery_prevnextlinks == "y") 
					$next_link = "<p align=\"right\"><b><a href=\"photogallery.php?gallery=".$gallery_id."&category=".$category_id."&photo=".$next_photo_id."\">Next</a></b></p>";
				if ($gallery_numberlinks == "n")
					$photolinks = "&nbsp;";
				$photo_navigation = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>
				 <td width=\"9%\">".$prev_link."</td>
				 <td width=\"82%\"><p align=\"center\">".$photolinks."</p></td>
				 <td width=\"9%\">".$next_link."</td>
				 </tr></table>";
			}
		}
	}
	
	$display_photos = "";
	if ($gallery_thumbnail_position <> "below")
		$display_photos .= $thumbnails;
	if ($gallery_full_position == "page")
		$display_photos .= "<div class=\"fullsizephoto\"><img id=\"placeholder\" src=\"".$photosdir.$initial_photo_id.".jpg\" alt=\"\" border=\"0\" /></div>\n
		 <p id=\"desc\">".$initial_caption."</p>\n";
	$display_photos .= $photo_navigation;
	if ($gallery_thumbnail_position == "below")
		$display_photos .= $thumbnails;
	$display_photos .= "<div style=\"clear: both;\"></div>";
	
#	if ($num_galleries > 1)
#		$display_photos .= "<p align=\"center\"><b><a href=\"photogallery.php\">Return to the Photo Gallery</a></b></p>";
}

############################################### PRINT STUFF ###############################################
echo $gallery_dropdown;
echo $gallery_links;

if ($category_links <> "")
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
	 <tr valign=\"top\"><td width=\"25%\">".$category_links."</td><td width=\"75%\">";
#if ($num_galleries > 1)
#	echo "<h2>".$gallery_name."</h2>\n";
if ($display_category_thumbnails == "y" && !is_numeric($category_id))
	echo $gallery_intro.$category_thumbnails;
if ($num_categories == 0 || ($num_categories > 0 && is_numeric($category_id)))
	echo $display_photos;
if ($category_links <> "")
	echo "</td>\n</tr>\n</table>\n";

?>

<p>&nbsp;</p>

</div></div>
</body>
</html>