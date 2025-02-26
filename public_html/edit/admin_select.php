<?
$this_page_id = 0;
require("../inc/config.php");
require("../inc/functions.php");
require("secure.php");

# The user may be accessing this page directly from the Toolbox setup page. If so, the setup page needs to be deleted now.
if (file_exists("setup.php"))
	unlink("setup.php");

if ($_REQUEST['action'] == "save") {

	$rs = mysql_query("SELECT page_id FROM wma_pages");
	while ($rows = mysql_fetch_array($rs)) {
		$page_id = $rows[0];
		$page_version = $_REQUEST['page_version'.$page_id];
		$page_status = $_REQUEST['page_status'.$page_id];
		if ($page_status <> "y") $page_status = "n";
		mysql_query("UPDATE wma_pages SET page_status='".$page_status."', page_version='".$page_version."' WHERE page_id=".$page_id);
	}
	header("location: admin_select.php");
}
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
			<li><a href="admin.php">Tools</a></li>
			<li><a href="config_admin.php">Toolbox Configuration</a></li>
			<? if ($cmsusertype == "a") { # only 4What should see this link ?>
			<li class="selected"><a href="admin_select.php">Tool Selector</a></li>
			<? } ?>
			<li><a href="styles.php">WYSIWYG Styles</a></li>
			<li><a href="phpinfo.php">PHP Info</a></li>
		</ul>
	</div>
	
	<form action="admin_select.php" method="post">
	<input type="hidden" name="action" value="save" />
	<table border="0" cellspacing="0" cellpadding="0" class="record_table">
	  <tr>
	    <th>Actions</th>
	    <th>Status</th>
	    <th>Tool</th>
	    <th>Version</th>
	  </tr>
		<?
		$rs = mysql_query("SELECT page_id, page_status, page_name, page_version, page_filename FROM wma_pages ORDER BY page_name");
		while ($rows = mysql_fetch_array($rs)) {
			if ($rows[1] == "y")
				$page_name = "<b>".$rows[2]."</b>";
			else
				$page_name = $rows[2];
		?>
		  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
			<td class="actions"><a href="<?= $rows[4]; ?>updatelog.php">Update Log</a><!--<a href="wma_pages_edit.php?page_id=<?= $rows[0]; ?>" onmouseover="menuroll('pageedit<?= $rows[0]; ?>', 'images/icon_edit_over.gif');" onmouseout="menuroll('pageedit<?= $rows[0]; ?>', 'images/icon_edit.gif');"><img src="images/icon_edit.gif" width="21" height="20" class="actionicon" alt="edit" border="0" name="pageedit<?= $rows[0]; ?>" /></a>--></td>
			<td><input type="checkbox" name="page_status<?= $rows[0]; ?>" value="y" <? if ($rows[1] == "y") echo " checked=\"checked\""; ?> /></td>
			<td><?= $page_name.(($rows[0] > 50)?" &nbsp;<i>custom widget</i>":""); ?></td>
			<td><input name="page_version<?= $rows[0]; ?>" type="text" class="formfield" id="page_version<?= $rows[0]; ?>" size="5" maxlength="10" value="<?= $rows[3]; ?>" /></td>
		  </tr>
		<? } ?>
	</table>
	<div class="buttonarea"><input type="submit" name="Submit" value="Save" class="button" /></div>
	</form>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
