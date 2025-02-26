<?
require("inc/config.php");
require("inc/config_pages.php");
require("inc/functions.php");

$page_id = 23; # FOR TESTING

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Dynamic Menu Test</title>
<link href="<?= $site_baseurl; ?>inc/styles.css" rel="stylesheet" type="text/css" />
<!-- ################# BEGIN MENU HEADER DATA ################# -->
<link href="<?= $site_baseurl; ?>css/tcp_menu.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?= $site_baseurl; ?>inc/tcp_menu.js"></script>
<? include("inc/tcp_menu_headdata.php"); ?>
<!-- ################## END MENU HEADER DATA ################## -->
</head>

<body>

<? include("inc/tcp_menu_bodydata.php"); ?>
  
<p>PAGE CONTENT GOES HERE</p>
<p>page_id = <?= $page_id; ?></p>

</body>
</html>
