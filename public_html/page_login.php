<?
require("inc/config.php");
require("inc/functions.php");
require("inc/config_pages.php");

$expiration = time()-1; # cookies expired one second ago
setcookie("pageusername","",$expiration,"/");
setcookie("pagepassword","",$expiration,"/");

$action = $_REQUEST['action'];
$page_idname = $_REQUEST['page'];
$page_id = 0; # default
$page_head_title = $website_name; # default
$error = false;
if ($page_idname <> "" && preg_match("'[^A-Za-z0-9 ]'", $page_idname)) { # only allow alphanumeric characters
	$error = true;
}
elseif ($page_idname <> "") { # get page information
	$sql = "SELECT page_id, page_title, page_head_title, page_meta_keywords, page_meta_description
	 FROM pages WHERE LCASE(page_idname)='".strtolower($page_idname)."'";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) == 0)
		$error = true;
	else {
		$rows = mysql_fetch_array($rs);
		$page_id = $rows[0];
		$page_title = $rows[1];
		$page_head_title = $rows[2];
		$page_meta_keywords = $rows[3];
		$page_meta_description = $rows[4];
		if ($page_head_title == "") $page_head_title = $page_title;
		if ($page_meta_keywords <> "") $meta_content .= "<meta name=\"Keywords\" content=\"".$page_meta_keywords."\" />\n";
		if ($page_meta_description <> "") $meta_content .= "<meta name=\"Description\" content=\"".$page_meta_description."\" />\n";
	}
}

if ($action == "login") {
	$pageusername = $_REQUEST['username']; # grab from login form
	$pagepassword = $_REQUEST['password']; # grab from login form
	$login_baseurl = $site_baseurl.(($enable_urlrewriting == "y")?$page_idname."/login/":"page_login.php?page=".$page_idname);
	if ($pageusername == "") {
		header("location: ".$login_baseurl); # no username entered
		exit;
	}
	else {
		$sql = "SELECT user_id, user_username, DECODE(user_password,'".$passcrypt."')
		 FROM wma_users WHERE user_username='".$pageusername."' AND user_type='f'";
		$rs = mysql_query($sql);
		if (mysql_num_rows($rs) == 0)
			header("location: ".$login_baseurl."?error=username"); # username does not exist
		else {
			$row = mysql_fetch_array($rs);
			#$secure_user_id = $row[0];
			$secure_username = $row[1];
			$secure_password = $row[2];
			if ($secure_password <> $pagepassword)
				header("location: ".$login_baseurl."?error=password"); # wrong password
			else {
				#mysql_query("INSERT INTO private_user_accesses VALUES (NULL, ".$secure_user_id.", '".date("Y-m-d H:i:s")."')");
				$expiration = time()+(60*60*24); # 1 days
				setcookie("pageusername",$secure_username,$expiration,"/");
				setcookie("pagepassword",base64_encode($secure_password),$expiration,"/");
				header("location: ".$site_baseurl.(($enable_urlrewriting == "y")?$page_idname."/":"page.php?page=".$page_idname));
				exit;
			}
		}
	}
}
elseif ($action == "submit") {
	$user_type = "f";
	$user_status = "y";
	$user_first_name = $_REQUEST['user_first_name'];
	$user_last_name = $_REQUEST['user_last_name'];
	$user_password = $_REQUEST['user_password'];
	$user_password_again = $_REQUEST['user_password_again'];
	$user_email = $_REQUEST['user_email'];
	$user_username = $user_email;
	# make sure username is not already in use
	$rs = mysql_query("SELECT user_id FROM wma_users WHERE user_username='".$user_username."' AND user_status <> 'd'");
	if (mysql_num_rows($rs) > 0) 
		$errmsg = "The e-mail address you entered is already registered. Please enter another.";
	elseif ($user_password <> $user_password_again)
		$errmsg = "The passwords must match.";
	else {
		$sql = "INSERT INTO wma_users (user_first_name, user_last_name, user_username, user_password, user_type,
		 user_status, user_email) VALUES ('".$user_first_name."','".$user_last_name."', '".$user_username."',
		 ENCODE('".$user_password."','".$passcrypt."'),'".$user_type."','".$user_status."','".$user_email."')";
		mysql_query($sql);
		$expiration = time()+(60*60*24); # 1 day
		setcookie("pageusername",$user_username,$expiration,"/");
		setcookie("pagepassword",base64_encode($user_password),$expiration,"/");
		header("location: ".$site_baseurl.(($enable_urlrewriting == "y")?$page_idname."/":"page.php?page=".$page_idname));
		exit;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $page_head_title; ?></title>
<?= $meta_content; ?>
<link href="<?= $site_baseurl; ?>css/styles.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?= $site_baseurl; ?>inc/scripts.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<? if ($pages_show_menu == "y") { ?>
<!-- ################# BEGIN MENU HEADER DATA ################# -->
<link href="<?= $site_baseurl; ?>css/tcp_menu.css.php" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?= $site_baseurl; ?>inc/tcp_menu.js"></script>
<script language="javascript" src="<?= $site_baseurl; ?>inc/embed.js"></script>
<? include("inc/tcp_menu_headdata.php"); ?>
<!-- ################## END MENU HEADER DATA ################## -->
<? } # / show menu ?>
<script language="JavaScript">
<!--
function isReady() {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.registrationform.user_email.value)) {
		// correct format
	}
	else {
		alert("Please enter a valid e-mail address.");
		return false;
	}
	if (document.registrationform.user_first_name.value <= 1) {
	  alert("Please enter your first name.");
	  return false;
	}
	else if (document.registrationform.user_last_name.value <= 1) {
	  alert("Please enter your last name.");
	  return false;
	}
	else if (document.registrationform.user_password.value <= 1) {
	  alert("Please enter a password.");
	  return false;
	}
	else if (document.registrationform.user_password_again.value <= 1) {
	  alert("Please enter a password.");
	  return false;
	}
	else if (document.registrationform.user_password.value != document.registrationform.user_password_again.value) {
	  alert("Passwords must match.");
	  return false;
	}
	else
	  return true;
}
// -->
</script>
</head>
<body>
<div align="center"><div id="pagearea">

<?
if ($pages_show_menu == "y") {
	include("inc/tcp_menu_bodydata.php");
}
?>

<?
if ($error == true) {
	echo "<h1>Error</h1>\n<p>The page could not be found.</p>
	 <p><a href=\"javascript:history.back();\">Please Try Again</a></p>";
}
else {
?>

<h1><?= $page_title; ?></h1>

<?
if ($_GET['error'] == "username")
	echo "<p class=\"alert\">The username is incorrect. Please try again.</p>";
elseif ($_GET['error'] == "password")
	echo "<p class=\"alert\">The password is incorrect. Please try again.</p>";
else
	echo "<p><em>You must log in to view this page. Cookies must be enabled.</em></p>";
?>

<h2>Log In</h2>

<form action="<?= $site_baseurl; ?>page_login.php" method="post">
<input type="hidden" name="action" value="login" />
<input type="hidden" name="page" value="<?= $page_idname; ?>" />
<table border="0" cellpadding="0" cellspacing="3">
  <tr>
    <td class="formlabel">Username:</td>
    <td><input name="username" type="text" id="username" size="20" class="formfield" /> </td>
  </tr>
  <tr>
    <td class="formlabel">Password:</td>
    <td><input name="password" type="password" id="password" size="20" class="formfield" /> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="Submit" type="submit" class="button" value="Log In" /> &nbsp; <a href="<?= $site_baseurl; ?>page_forgot.php">forgot password?</a> </td>
  </tr>
</table>
</form>

<h2>Register</h2>

<p>Don't have an account? Register below to view the page: (<em>all fields required</em>)</p>

<?= (($errmsg <> "")?"<p style=\"font-weight: bold; color: #c00;\">".$errmsg."</p>":""); ?>

<form action="<?= $site_baseurl.(($enable_urlrewriting == "y")?$page_idname."/login/":"page_login.php?page=".$page_idname); ?>" method="post" name="registrationform" onSubmit="return isReady(registrationform);">
<input type="hidden" name="action" value="submit" />
<table border="0" cellpadding="0" cellspacing="3">
  <tr>
    <td class="formlabel">First Name:&nbsp;</td>
    <td><input name="user_first_name" type="text" id="user_first_name" size="20" maxlength="50" class="formfield" /> </td>
  </tr>
  <tr>
    <td class="formlabel">Last Name:&nbsp;</td>
    <td><input name="user_last_name" type="text" id="user_last_name" size="20" maxlength="50" class="formfield" /> </td>
  </tr>
  <tr>
    <td class="formlabel">E-mail Address:&nbsp;</td>
    <td><input name="user_email" type="text" id="user_email" size="20" maxlength="255" class="formfield" /> </td>
  </tr>
  <tr>
    <td class="formlabel">Password:&nbsp;</td>
    <td><input name="user_password" type="password" id="user_password" size="20" maxlength="20" class="formfield" /> </td>
  </tr>
  <tr>
    <td class="formlabel">Password Again:&nbsp;</td>
    <td><input name="user_password_again" type="password" id="user_password_again" size="20" maxlength="20" class="formfield" /> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="Submit" type="submit" class="button" value="Sign Up" /> </td>
  </tr>
</table>
</form>

<? } # / error == true ?>

</div></div>
</body>
</html>