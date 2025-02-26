<?
if($_SERVER['HTTP_HOST'] == "dragon"){
	require("../inc/config_local.php");
}else{
	require("../inc/config.php");
}
require("../inc/functions.php");

$expiration = time()-1; # cookies expired one second ago
setcookie("cmsusername","",$expiration);
setcookie("cmspassword","",$expiration);
setcookie("cmsusertype","",$expiration);
setcookie("cmsfirstname","",$expiration);
setcookie("cmsuserid","",$expiration);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?= $cms_title; ?></title>
<link href="inc/master.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="header">
	<div id="general_links">&nbsp;</div>
	<h1><?= $website_name; ?></h1>
</div>
<div id="toolbar">
	<div id="toolbar_logo"><img src="images/logos/4what.gif" width="38" height="38" border="0" alt="Toolbox" /></div>
	<h2>Web Toolbox</h2>
</div>

<div id="contentarea">
<div id="content">

	<h3>Log In</h3>

	<?
	$error = $_GET['error'];
	if ($error == "username")
		echo "<p class=\"alert\">The username is incorrect. Please try again.</p>";
	elseif ($error == "password")
		echo "<p class=\"alert\">The password is incorrect. Please try again.</p>";
	elseif ($error == "disabled")
		echo "<p class=\"alert\">Your user account has been disabled.</p>";
	else
		echo "<p><em>You must have cookies enabled to use the Toolbox.</em></p>";
	?>

	<form action="index.php" method="post">
	<fieldset>
		<!--<legend>Log In</legend>-->
		<dl>
		  <dt><label for="username">Username: </label></dt>
		  <dd><input name="username" type="text" id="username" size="20" class="formfield" /></dd>
		  <dt><label for="password">Password: </label></dt>
		  <dd><input name="password" type="password" id="password" size="20" class="formfield" /></dd>
		</dl>
	</fieldset>
	<div class="buttonarea"><input type="submit" value="Log In" class="button" /></div>
	</form>
	
</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
