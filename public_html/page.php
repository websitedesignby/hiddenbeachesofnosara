<?
require("inc/config.php");
require("inc/config_pages.php");
if ($pages_enable_bannerads == "y")
	require("inc/config_bannerads.php");
if ($pages_enable_adspace == "y")
	require("inc/config_adspace.php");
require("inc/functions.php");
require("inc/page_functions.php");

$page_idname = $_GET['page'];
$draft_id = $_GET['draft'];
$template_id = $_GET['template'];
$error = false;

# is this just a template?
if (is_numeric($template_id)) {
	$rs = mysql_query("SELECT template_html FROM pages_templates WHERE template_id=".$template_id);
	if (mysql_num_rows($rs) > 0) {
		$row = mysql_fetch_array($rs);
		$page_content = str_replace("../../",$site_baseurl,$row[0]); # remove relative image path
		$page_content = str_replace("'uploads/","'".$site_baseurl."uploads/",$page_content); # modify relative image path
		$page_content = str_replace("\"uploads/","\"".$site_baseurl."uploads/",$page_content); # modify relative image path
	}
}
else { # this must be a draft or page

if ($page_idname == "") { # get the hompage if no page is selected 
	$rs = mysql_query("SELECT page_idname, page_id FROM pages WHERE page_home=1");
	if (mysql_num_rows($rs) == 0)
		$error = true;
	else {
		$page_idname = mysql_result($rs,0,0);
		$page_id = mysql_result($rs,0,1);
	}
	mysql_free_result($rs);
}

if ($page_idname <> "" && preg_match("'[^A-Za-z0-9 ]'", $page_idname)) { # only allow alphanumeric characters
	$error = true;
}
elseif ($page_idname <> "") { # get page information
	$sql = "SELECT page_id, page_title, page_parent_id, page_head_title, page_meta_keywords,
	 page_meta_description, page_type, page_custom, page_updated, page_content, page_order, page_home,
	 page_protected FROM pages WHERE LCASE(page_idname)='".strtolower($page_idname)."'";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) == 0)
		$error = true;
	else {
		$rows = mysql_fetch_array($rs);
		$page_id = $rows[0];
		$page_title = $rows[1];
		$page_parent_id = $rows[2];
		$page_head_title = $rows[3];
		$page_meta_keywords = $rows[4];
		$page_meta_description = $rows[5];
		$page_type = $rows[6];
		$page_custom = $rows[7];
		$page_updated = $rows[8];
		$page_content = $rows[9];
		$page_order = $rows[10];
		$page_home = $rows[11];
		$page_protected = $rows[12];
		if ($page_head_title == "") $page_head_title = $page_title;
		if ($page_meta_keywords <> "") $meta_content .= "<meta name=\"Keywords\" content=\"".$page_meta_keywords."\" />\n";
		if ($page_meta_description <> "") $meta_content .= "<meta name=\"Description\" content=\"".$page_meta_description."\" />\n";
		if (substr($page_custom,0,4) <> "http" && $page_type == "link")
			$page_custom = $site_baseurl.$page_custom;
	}
	mysql_free_result($rs);
	
	if ($page_protected == "y") {
		require("page_secure.php");
	}

	if ($page_type == "link") header("location: ".$page_custom); 
	
	if (is_numeric($draft_id)) {
		$rs = mysql_query("SELECT page_id, draft_content FROM pages_drafts WHERE draft_id=".$draft_id);
		if (mysql_num_rows($rs) > 0) {
			$row = mysql_fetch_row($rs);
			$draft_page_id = $row[0];
			$draft_content = $row[1];
			if ($page_id == $draft_page_id) # only display the draft if it's a draft of the selected page
				$page_content = $draft_content;
		}
	}
	
	if ($error == false) {
		$page_content = str_replace("../../",$site_baseurl,$page_content); # modify relative image path
		$page_content = str_replace("\"uploads/","\"".$site_baseurl."uploads/",$page_content); # modify relative image path
		$page_content = str_replace("'uploads/","'".$site_baseurl."uploads/",$page_content); # modify relative image path
		if ($pages_show_update == "y") 
			$page_content .= "<p style=\"text-align: right; padding-top: 10px;\"><em>Updated ".date("F j, Y @ g:i A",strtotime($page_updated))."</em></p>";
		
		/*
		############################################### BEGIN SUBMENU ###############################################
		if ($pages_show_menu == "y") {
			if ($page_parent_id == 0 && $page_order > 0) $page_parent_id = $page_id;
			$rs = mysql_query("SELECT page_title FROM pages WHERE page_id=".$page_parent_id);
			if (mysql_num_rows($rs) > 0) 
				$parent_page_title = mysql_result($rs,0,0);
			$sql = "SELECT page_title, page_idname FROM pages WHERE page_parent_id=".$page_parent_id."
			 AND page_order > 0 ORDER BY page_order";
			$rs = mysql_query($sql);
			if (mysql_num_rows($rs) > 0) { 
				$submenu .= "<ul id=\"hsubnav\">
				 <li id=\"title\">".$parent_page_title."</li>";
				while ($rows = mysql_fetch_array($rs)) {
					$submenu .= "<li><a href=\"".$page_filename."?page=".$rows[1]."\">".$rows[0]."</a></li>";
				}
				$submenu .= "</ul>";
			}
		}
		################################################ END SUBMENU ################################################
		*/
	}

}

} # end template check

# add ad space and banner ads
if ($pages_enable_adspace == "y")
	$page_content = displayAdspace($page_id).$page_content; 
if ($pages_enable_bannerads == "y")
	$page_content = displayBannerAds($page_id).$page_content; 

if ($error == true) {
	$page_title = "Error";
	$page_content = "<h1>Error</h1>\n<p>The page could not be found.</p>
	 <p><a href=\"javascript:history.back();\">Please Try Again</a></p>";
}
else {
	if ($pages_show_menu == "y")	
		$page_content = $submenu.$page_content;
}

#if ($pages_show_menu == "y")
#	$page_content = $page_nav.$page_content;
if ($pages_show_footer_menu == "y")
	$page_content = $page_content.$footer_nav;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?= $page_head_title; ?></title>
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
<script src="scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body onload="MM_preloadImages('<?= $site_baseurl; ?>images/nav-home-over.jpg','<?= $site_baseurl; ?>images/nav-playas-over.jpg','<?= $site_baseurl; ?>images/nav-location-over.jpg','<?= $site_baseurl; ?>images/nav-sales-over.jpg','<?= $site_baseurl; ?>images/nav-brochure-over.jpg','<?= $site_baseurl; ?>images/nav-contact-over.jpg')">
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
        <div id="subheader"><img src="<?= $site_baseurl; ?>images/logo.png" alt="Playas Escondidas" name="logo" id="logo" />
          <? if ($page_id == 3) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-playas.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 9) {  ?>
          <img src="<?= $site_baseurl; ?>images/subheader-hidden.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 10) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-tropical.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 11) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-greencomm.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 12) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-ecology.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 14) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-video.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 26) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-gallery.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 20) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-video.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 27) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-gallery.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 34) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-video.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 28) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-gallery.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 29) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-video.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 30) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-gallery.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 31) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-video.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 33) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-gallery.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 32) { ?>          
          <img src="<?= $site_baseurl; ?>images/subheader-gallery.jpg" alt="Playas Escondidas" />
          <? } elseif ($page_id == 5) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-sales.jpg" alt="Playas Escondidas" />
		  <? } elseif ($page_id == 8) { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-brochure.jpg" alt="Playas Escondidas" />
          <? } else { ?>
          <img src="<?= $site_baseurl; ?>images/subheader-playas.jpg" alt="Playas Escondidas" />
          <? } ?>
        </div>
        <div id="contentMain">
          <p>
            <?= $page_content; ?>
        </p>
        </div>
        <div id="divider"></div>
        <div id="contentSub">
		<?php include("contentsub.php"); ?>
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