<?
$this_page_id = 0;
require("../inc/config.php");
require("../inc/functions.php");
require("../inc/page_functions.php");
require("secure.php");

$errmsg = "";
$error = false;

if ($_POST['action'] == "save") {

	$user_username = $_REQUEST['user_username'];
	$user_password = $_REQUEST['user_password'];
	$user_password_again = $_REQUEST['user_password_again'];
	$user_email = $_REQUEST['user_email'];
	
	# make sure username is not already in use
	$rs = mysql_query("SELECT user_id FROM wma_users WHERE user_username='".$user_username."' AND user_id <> ".$cmsuserid);
	if (mysql_num_rows($rs) > 0) 
		$errmsg = "The username you selected is already in use. Please enter another.";
	elseif ($user_username == "")
		$errmsg = "You must enter a username.";
	elseif ($user_password <> $user_password_again)
		$errmsg = "Passwords do not match.";
	else {
		$sql = "UPDATE wma_users SET user_username='".$user_username."', user_email='".$user_email."' 
		 WHERE user_id=".$cmsuserid;
		mysql_query($sql);
		
		if ($user_password <> "" && $user_password_again <> "") {
			$sql = "UPDATE wma_users SET user_password=ENCODE('".$user_password."','".$passcrypt."')
			 WHERE user_id=".$cmsuserid;
			mysql_query($sql);
		}

		header("location: user_profile.php");	
	}

}

if (is_numeric($cmsuserid)) {
	$sql = "SELECT user_id, user_first_name, user_last_name, user_username, DECODE(user_password,'".$passcrypt."'),
	 user_email FROM wma_users WHERE user_id=".$cmsuserid;
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) > 0) {
		$user_id = mysql_result($rs,0,0);
		$user_first_name = htmlentities(mysql_result($rs,0,1));
		$user_last_name = htmlentities(mysql_result($rs,0,2));
		$user_username = htmlentities(mysql_result($rs,0,3));
		$user_password = htmlentities(mysql_result($rs,0,4));
		$user_email = htmlentities(mysql_result($rs,0,5));
	}
}
else {
	$error = true;
	$errmsg = "The user you selected could not be found.";
}

if ($user_id <> $cmsuserid) {
	$error = true;
	$errmsg = "You may only edit your user profile.";
}

if ($errmsg == "")
	$errmsg = "&nbsp;";
else {
	$errmsg = "<p class=\"alert\">".$errmsg."</p>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?= $cms_title; ?></title>
<link href="inc/master.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="all" href="inc/tree/tree.css" />
<script type="text/javascript" src="inc/tree/tree.js"></script>
<script language="JavaScript">
<!--
function isReady() {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.form1.user_email.value)) {
		// correct format
	}
	else {
		alert("Please fill in a valid e-mail address.");
		return false;
	}
}
// -->
</script>
</head>

<body>
<? require("inc/header.php"); ?>
<div id="toolbar">
	<div id="toolbar_logo"><img src="images/logos/4what.gif" width="38" height="38" border="0" alt="Toolbox" /></div>
	<h2>Update User Profile</h2>
</div>

<div id="contentarea">
<div id="content">

	<?= $errmsg; ?>
	
	<? if ($error == false) { ?>
	<form action="user_profile.php" method="post" name="form1" onSubmit="return isReady(form1);">
	<input type="hidden" name="action" value="save" />
	<fieldset>
		<legend><?= $user_first_name." ".$user_last_name; ?></legend>
		<dl>
		  <dt><label for="user_username">Username: </label></dt>
		  <dd><input name="user_username" type="text" id="user_username" style="width: 50%;" class="formfield" maxlength="20" value="<?= $user_username; ?>" /></dd>
		  <dt><label for="user_password">Change Password: </label></dt>
		  <dd><input name="user_password" type="password" id="user_password" style="width: 50%;" class="formfield" maxlength="20" /></dd>
		  <dt><label for="user_password_again">Password Again: </label></dt>
		  <dd><input name="user_password_again" type="password" id="user_password_again" style="width: 50%;" class="formfield" maxlength="20" /></dd>
		  <dt><label for="user_email">Email Address: </label></dt>
		  <dd><input name="user_email" type="text" id="user_email" style="width: 70%;" class="formfield" maxlength="255" value="<?= $user_email; ?>" /></dd>
		</dl>
	</fieldset>
	<p class="notes">If you enter a new username and/or password, you will be automatically logged out after saving and will need to log in with your new information.</p>
	<div class="buttonarea"><input type="submit" value="Save" class="button" /></div>
	</form>
	<? } ?>
	
	<p class="notes">&nbsp;</p>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
