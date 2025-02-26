<?
function getSubmenu($parent_page_id, $site_baseurl) {
	$submenu = "";
	$sql = "SELECT page_title, page_idname FROM pages WHERE page_parent_id=".$parent_page_id." AND page_order > 0
	 ORDER BY page_order";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) > 0) {
		$submenu .= "<ul>";
		while ($rows = mysql_fetch_array($rs)) {
			$submenu .= "<li><a href=\"".$site_baseurl.$rows[1]."/\">".$rows[0]."</a></li>";
		}
		$submenu .= "</ul>";
	}
	return $submenu;
}
?>
<body>
<ul id="dropDownMenu">
<li><?= getSubmenu(2, $site_baseurl); ?>
  <a href="<?= $site_baseurl; ?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('home','','<?= $site_baseurl; ?>images/nav-home-over.jpg',1)"><img src="<?= $site_baseurl; ?>images/nav-home.jpg" alt="Home" name="home" border="0" id="home" /></a></li>
<li><?= getSubmenu(3, $site_baseurl); ?>
  <a href="<?= $site_baseurl; ?>playas/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('playas','','<?= $site_baseurl; ?>images/nav-playas-over.jpg',1)"><img src="<?= $site_baseurl; ?>images/nav-playas.jpg" alt="Playas Escondidas" name="playas" border="0" id="playas" /></a></li>
<li><?= getSubmenu(7, $site_baseurl); ?>
<a href="<?= $site_baseurl; ?>areamap.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('locations','','<?= $site_baseurl; ?>images/nav-location-over.jpg',1)"><img src="<?= $site_baseurl; ?>images/nav-location.jpg" alt="Location" name="locations" border="0" id="locations" /></a></li>
<li><?= getSubmenu(5, $site_baseurl); ?>
  <a href="<?= $site_baseurl; ?>sales/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('sales','','<?= $site_baseurl; ?>images/nav-sales-over.jpg',1)"><img src="<?= $site_baseurl; ?>images/nav-sales.jpg" alt="Sales" name="sales" border="0" id="sales" /></a></li>
<li><?= getSubmenu(8, $site_baseurl); ?>
  <a href="<?= $site_baseurl; ?>brochure/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('brochure','','<?= $site_baseurl; ?>images/nav-brochure-over.jpg',1)"><img src="<?= $site_baseurl; ?>images/nav-brochure.jpg" alt="Brochure" name="brochure" border="0" id="brochure" /></a></li>
<li><?= getSubmenu(6, $site_baseurl); ?>
  <a href="<?= $site_baseurl; ?>forms/contactus/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('contact','','<?= $site_baseurl; ?>images/nav-contact-over.jpg',1)"><img src="<?= $site_baseurl; ?>images/nav-contact.jpg" alt="Contact Us" name="contact" border="0" id="contact" /></a></li>
</ul>