<?
$this_page_id = 0;
require("../inc/config.php");
require("../inc/page_functions.php");
require("../inc/functions.php");
require("secure.php");

$errmsg = "";
$error = false;
$user_id = $_REQUEST['user_id'];
$type = $_REQUEST['type'];

# is the Total Control Pages tool active?
$tcp_active = "n";
$rs = mysql_query("SELECT page_status FROM wma_pages WHERE page_id=28");
if (mysql_num_rows($rs) > 0) {
	$row = mysql_fetch_row($rs);
	$tcp_active = $row[0];
}

if ($_POST['action'] == "save") {

	$var_array = array();
	$varcounter = 0;
	foreach ($_POST as $key=>$value) {
		${$key} = $value; # grab all variables from form
		$var_array[$varcounter] = $key;
		$varcounter++;
	}
	if ($user_type == "") {
		$user_type = (($type == "f")?"f":"r");
	}
	
	# make sure username is not already in use
	$rs = mysql_query("SELECT user_id FROM wma_users WHERE user_username='".$user_username."' AND user_id <> ".$user_id);
	if (mysql_num_rows($rs) > 0) 
		$errmsg = "The username you selected is already in use. Please enter another.";
	else {
		$sql = "UPDATE wma_users SET user_first_name='".$user_first_name."', user_last_name='".$user_last_name."',
		 user_username='".$user_username."', user_password=ENCODE('".$user_password."','".$passcrypt."'), 
		 user_type='".$user_type."', user_status='".$user_status."', user_email='".$user_email."' 
		 WHERE user_id=".$user_id;
		mysql_query($sql);
		if ($user_type <> "m") { # insert permissions if not Manager
			mysql_query("DELETE FROM wma_permissions WHERE content_page_id=0 AND user_id=".$user_id);
			$rs = mysql_query("SELECT page_id FROM wma_pages WHERE page_status='y'");
			while ($rows = mysql_fetch_array($rs)) {
				$page_id_content = ${'page_id'.$rows[0]};
				$page_id_config = ${'page_id_config'.$rows[0]};
				if ($page_id_content == "y" || $page_id_config == "y") {
					$config = (($page_id_config == "y")?"y":"n");
					$sql = "INSERT INTO wma_permissions (user_id, page_id, content_page_id, page_config)
					 VALUES ('".$user_id."','".$rows[0]."',0,'".$config."')";
					mysql_query($sql);
				}
			}
			# Total Control Pages
			if ($tcp_active == "y") {
				mysql_query("DELETE FROM wma_permissions WHERE page_id=28 AND content_page_id > 0 AND user_id=".$user_id);
				$rs = mysql_query("SELECT page_id FROM pages");
				while ($rows = mysql_fetch_array($rs)) {
					$tcp_page_id = ${'tcp_page_id'.$rows[0]};
					if ($tcp_page_id == "y") {
						$sql = "INSERT INTO wma_permissions (user_id, page_id, content_page_id, page_config)
						 VALUES ('".$user_id."',28,".$rows[0].",'n')";
						mysql_query($sql);
					}
				}
			}

		}
		header("location: users.php?type=".$type);	
	}

}

if (is_numeric($user_id)) {
	$sql = "SELECT user_first_name, user_last_name, user_username, DECODE(user_password,'".$passcrypt."'),
	 user_type, user_status, user_email FROM wma_users WHERE user_id=".$user_id;
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) > 0) {
		$user_first_name = htmlentities(mysql_result($rs,0,0));
		$user_last_name = htmlentities(mysql_result($rs,0,1));
		$user_username = htmlentities(mysql_result($rs,0,2));
		$user_password = htmlentities(mysql_result($rs,0,3));
		$user_type = htmlentities(mysql_result($rs,0,4));
		$user_status = htmlentities(mysql_result($rs,0,5));
		$user_email = htmlentities(mysql_result($rs,0,6));
	}
}
else {
	$error = true;
	$errmsg = "The user you selected could not be found.";
}

if ($user_type == "a") { # the administrator account can't be edited
	$error = true;
	$errmsg = "The user you selected could not be found.";	# This is a lie. We just don't want the client
															# to know there's the possibility of a hidden user
}

if ($errmsg <> "") {
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
function togglefield(theform, enabled) {
	for (var i = 0; i < theform.elements.length; i++) {
		if (theform.elements[i].id.indexOf('_page') != -1) {
			theform.elements[i].disabled = (enabled);
			if (enabled == true) {
				document.getElementById("pages").style.color = '#869DB5';
			}
			else {
				document.getElementById("pages").style.color = '#264A6E';
			}
		}
	}
}
// -->
</script>
<? if ($user_type == "m") { ?>
<style>
#pages {
	color: #869DB5;
}
</style>
<? } ?>
</head>

<body>
<? require("inc/header.php"); ?>
<div id="toolbar">
	<div id="toolbar_logo"><a href="index.php"><img src="images/logos/4what.gif" width="38" height="38" border="0" alt="Toolbox" /></a></div>
	<h2><a href="users.php">Users</a></h2>
</div>

<div id="contentarea">
<div id="content">

	<? if ($tcp_active == "y") { ?>
	<div class="maintabs">
		<ul>
			<li<?= (($type <> "f")?" class=\"selected\"":""); ?>><a href="users.php">Toolbox Users</a></li>
			<li<?= (($type == "f")?" class=\"selected\"":""); ?>><a href="users.php?type=f">Web Site Users</a></li>
		</ul>
	</div>
	<? } ?>

	<h3>Edit A User</h3>
	
	<?= $errmsg; ?>
	
	<? if ($error == false) { ?>
	<form action="users_edit.php" method="post" name="form1" onSubmit="return isReady(form1);">
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="user_id" value="<?= $user_id; ?>" />
	<input type="hidden" name="type" value="<?= $type; ?>" />
	<fieldset>
		<legend>User Info</legend>
		<dl>
		  <dt><label for="user_status">Status: </label></dt>
		  <dd><input name="user_status" type="radio" value="y" <? if ($user_status == "y") echo "checked=\"checked\""; ?> /> 
			  Enabled &nbsp;
			<input name="user_status" type="radio" value="n" <? if ($user_status == "n") echo "checked=\"checked\""; ?> /> 
			Disabled</dd>
		  <dt><label for="user_first_name">First Name: </label></dt>
		  <dd><input name="user_first_name" type="text" id="user_first_name" size="30" class="formfield" maxlength="50" value="<?= $user_first_name; ?>" /></dd>
		  <dt><label for="user_last_name">Last Name: </label></dt>
		  <dd><input name="user_last_name" type="text" id="user_last_name" size="30" class="formfield" maxlength="50" value="<?= $user_last_name; ?>" /></dd>
		  <dt><label for="user_username">Username: </label></dt>
		  <dd><input name="user_username" type="text" id="user_username" size="20" class="formfield" maxlength="20" value="<?= $user_username; ?>" /></dd>
		  <dt><label for="user_password">Password: </label></dt>
		  <dd><input name="user_password" type="text" id="user_password" size="20" class="formfield" maxlength="20" value="<?= $user_password; ?>" /></dd>
		  <dt><label for="user_email">Email Address: </label></dt>
		  <dd><input name="user_email" type="text" id="user_email" size="30" class="formfield" maxlength="255" value="<?= $user_email; ?>" /></dd>
		</dl>
	</fieldset>
	<? if ($type <> "f") { ?>
	<fieldset>
		<legend>Tool Access</legend>
		<dl>
		  <dt>&nbsp;</dt>
		  <dd><input type="checkbox" name="user_type" id="user_type" value="m" onclick="togglefield(this.form, this.checked)" <? if ($user_type == "m") echo "checked=\"checked\""; ?> /> 
			<strong>Manager (all tools, configuration, and user management)</strong></dd>
		  <dt>&nbsp;</dt>
		  <dd><img src="images/contentconfig.gif" width="124" height="75" hspace="3" alt="Content Only | Content and Config" /></dd>
		  <dt>Individual Tools:</dt>
		  <dd><div id="pages"><?
			$rs = mysql_query("SELECT page_id, page_name FROM wma_pages WHERE page_status='y' ORDER BY page_name");
			while ($rows = mysql_fetch_array($rs)) {
				$content_checked = $config_checked = "";
				$sql = "SELECT page_id, page_config FROM wma_permissions
				 WHERE user_id=".$user_id." AND page_id=".$rows[0]." AND content_page_id=0";
				$rs2 = mysql_query($sql);
				if (mysql_num_rows($rs2) > 0) {
					if (mysql_result($rs2,0,1) == "y")
						$config_checked = " checked=\"checked\"";
					else
						$content_checked = " checked=\"checked\"";
				}
				echo "<input name=\"page_id".$rows[0]."\" type=\"checkbox\" id=\"select_page\" value=\"y\" ".$content_checked." ".(($user_type == "m")?"disabled=\"disabled\"":"")." /> 
				 <input name=\"page_id_config".$rows[0]."\" type=\"checkbox\" id=\"select_page\" value=\"y\" ".$config_checked." ".(($user_type == "m")?"disabled=\"disabled\"":"")." /> ".
				 $rows[1]."<br />";
				mysql_free_result($rs2);
			}
			mysql_free_result($rs);
		  ?>
		  </div></dd>
		  <? if ($tcp_active == "y") { ?>
			  <dt>Total Control Pages:</dt>
			  <dd><em>To give this user permission to edit only certain Total Control Pages, do NOT check the Total Control Pages tool above and check only the necessary pages below:</em></dd>
			  <dt>&nbsp;</dt>
			  <dd><? displayCheckboxTree(0,0); ?></dd>
		  <? } ?>
		</dl>
	</fieldset>
	<? } ?>
	<div class="buttonarea"><input type="submit" value="Save" class="button" /></div>
	</form>
	<? } ?>
	
	<p class="notes">&nbsp;</p>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
