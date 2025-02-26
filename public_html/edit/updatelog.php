<?
require("../inc/config.php");
require("../inc/functions.php");
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
	<h2><a href="updatelog.php">Update Log</a></h2>
</div>

<div id="contentarea">
<div id="content">

<table border="0" cellspacing="0" cellpadding="0" class="record_table">
  <tr>
    <th>Date</th>
    <th>Version</th>
    <th>Updates</th>
    </tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
    <td>18-Feb-2008</td>
    <td>1.45</td>
    <td>Added user_profile.php page to allow all users to update their usernames and passwords </td>
  </tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
    <td>21-Sep-2007</td>
    <td>1.44</td>
    <td>Added extra security to secure.php </td>
  </tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
    <td>18-Apr-2007</td>
    <td>1.43</td>
    <td>Added URL Rewriting to Configuration<br />
      <br />
      Added Web Site Base Directory to Configuration </td>
  </tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
    <td>19-Feb-2007</td>
    <td>1.42</td>
    <td>Removed cms_baseurl variable from the configuration file. This is now calculated in inc/header.php </td>
  </tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
  	<td>30-Nov-2006</td>
  	<td>1.41</td>
  	<td>Added ownership to files uploaded to the File Manager. Files can now only be edited and deleted by their owner and Toolbox Managers.</td>
  	</tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
  	<td>22-Jun-2006</td>
  	<td>1.4</td>
  	<td>Added web_title to all tools to implement tool name changes in the Toolbox menu </td>
  	</tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
  	<td>13-Apr-2006</td>
  	<td>1.3</td>
  	<td>Made the Image Manager and File Manager global </td>
  	</tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
  	<td>6-Apr-2006</td>
  	<td>1.2</td>
  	<td>Added icons to every tool page </td>
  	</tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
  	<td>9-Jan-2005</td>
  	<td>1.1</td>
  	<td>Updated the configuration page to include Web Site Name and Web Site URL </td>
  	</tr>
  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
    <td>18-Oct-2005</td>
    <td>1.0</td>
    <td>Released</td>
    </tr>
</table>
	
	<p class="notes">&nbsp;</p>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
