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
	<div id="toolbar_logo"><a href="index.php"><img src="images/logos/4what.gif" width="38" height="38" border="0" alt="Toolbox" /></a></div>
	<h2><a href="webstats.php">Stats</a></h2>
</div>

<div id="contentarea">
<div id="content">

	<h3>Visitor Statistics</h3>
	<p>Click the button to view web site visitor tracking reports. Enter the username and password listed below to log into your reports.</p>
	<form action="<?= $webstats_url; ?>" method="post" target="_blank">
	<div><input type="submit" value="View Reports" class="button" /></div>
	</form>
	<p><strong>Username:</strong> <?= $webstats_username; ?></p>
	<p><strong>Password:</strong> <?= $webstats_password; ?></p>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
