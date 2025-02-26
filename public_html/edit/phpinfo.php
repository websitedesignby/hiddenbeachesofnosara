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
			<li><a href="admin.php">Tools</a></li>
			<li><a href="config_admin.php">Toolbox Configuration</a></li>
			<? if ($cmsusertype == "a") { # only 4What should see this link ?>
			<li><a href="admin_select.php">Tool Selector</a></li>
			<? } ?>
			<li><a href="styles.php">WYSIWYG Styles</a></li>
			<li class="selected"><a href="phpinfo.php">PHP Info</a></li>
		</ul>
	</div>
	
	<? phpinfo(); ?>
	
	<p>&nbsp;</p>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
