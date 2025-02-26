<?
$this_page_id = 0;
if($_SERVER['HTTP_HOST'] == "dragon"){
	require("../inc/config_local.php");
}else{
	require("../inc/config.php");
}
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
	<h2><a href="index.php">Web Toolbox</a></h2>
</div>

<div id="contentarea">
<div id="content">

  <? if ($_REQUEST['error'] == "permissiondenied") { ?>
  <p class="alert">You do not have permission to view this page.</p>
  <? } else { ?>
  <p><b>Welcome, <?= $cmsfirstname; ?>.</b> Select a tool to edit from the menu above.</p>
  <? } ?>
  <p>&nbsp;</p>
  <p>&nbsp;</p>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
