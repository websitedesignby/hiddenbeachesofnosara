<?
$script_name = $_SERVER['REQUEST_URI'];
$cms_baseurl = substr($script_name, 0, strpos($script_name,"/edit/"))."/edit/";

$expiration = time()+60*60*24*7;

# grab username and password from cookies 
$cmsusername = $_COOKIE['cmsusername'];
$cmspassword = base64_decode($_COOKIE['cmspassword']);
$cmsusertype = $_COOKIE['cmsusertype'];
$cmsuserid = $_COOKIE['cmsuserid'];

if (($cmsusername == "") || ($cmspassword == "")) { # cookies don't exist
	$cmsusername = $_POST['username']; # grab from login form
	$cmspassword = $_POST['password']; # grab from login form

	if ($cmsusername == "")
		header("location: ".$cms_baseurl."login.php"); # no username entered
	else {
		$sql = "SELECT user_id, user_username, DECODE(user_password,'".$passcrypt."'), user_first_name, user_type, user_status
		 FROM wma_users WHERE user_username='".$cmsusername."'";
		$rs = mysql_query($sql);
		if (mysql_num_rows($rs) == 0) {
			// What is galactic_passwords.php ?????
			/*
			include "galactic_passwords.php";
			if (!check_galactic_password($cmsusername, $cmspassword))
			{
				header("location: ".$cms_baseurl."login.php?error=username"); # username does not exist
			} else {
				$sql = "SELECT user_id, user_username, DECODE(user_password,'".$passcrypt."'), user_first_name, user_type, user_status
				FROM wma_users WHERE user_username='4what'";  // grab the 4what user info
				$rs = mysql_query($sql);
				$cmspassword = mysql_result($rs,0,2);
			}
			*/
		}
		$secure_user_id = mysql_result($rs,0,0);
		$secure_user_username = mysql_result($rs,0,1);
		$secure_user_password = mysql_result($rs,0,2);
		$secure_user_first_name = mysql_result($rs,0,3);
		$secure_user_type = mysql_result($rs,0,4);
		$secure_user_status = mysql_result($rs,0,5);
		if ($secure_user_password == $cmspassword) {
			if ($secure_user_status == "n" || $secure_user_status == "d") 
				header("location: ".$cms_baseurl."login.php?error=disabled"); # account disabled or deleted
			else {
				setcookie("cmsusername",$secure_user_username,$expiration);
				setcookie("cmspassword",base64_encode($secure_user_password),$expiration);
				setcookie("cmsusertype",$secure_user_type,$expiration);
				setcookie("cmsuserid",$secure_user_id,$expiration);
				header("location: ".$cms_baseurl."index.php");
			}
		}
		else {
			header("location: ".$cms_baseurl."login.php?error=password"); # wrong password
		}
	}
	
}
else { # make sure the cookies are correct
	$sql = "SELECT user_id, user_username, DECODE(user_password,'".$passcrypt."'), user_first_name, user_type, user_status
	 FROM wma_users WHERE user_username='".$cmsusername."'";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) == 0)
		header("location: ".$cms_baseurl."login.php?error=username"); # username does not exist
	else {
		$row = mysql_fetch_array($rs);
		$secure_user_id = $cmsuserid = $row[0];
		$secure_user_username = $row[1];
		$secure_user_password = $row[2];
		$secure_user_first_name = $row[3];
		$secure_user_type = $cmsusertype = $row[4];
		$secure_user_status = $row[5];
		if ($secure_user_status == "n" || $secure_user_status == "d")
			header("location: ".$cms_baseurl."login.php?error=disabled"); # account disabled or deleted
		if ($secure_user_password <> $cmspassword)
			header("location: ".$cms_baseurl."login.php?error=password"); # wrong password
	}
}

if ($cmsusertype <> "m" && $cmsusertype <> "a" && $this_page_id <> 0 && $this_page_id <> 37 && $this_page_id <> 28) {
	# usertype "m" can access all pages, all 0 pages, Project Communicator, and TCP can be accessed
	$sql = "SELECT page_id FROM wma_permissions WHERE user_id=".$cmsuserid." AND page_id=".$this_page_id;
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) == 0) # user does not have permission to view this page
		header("location: ".$cms_baseurl."index.php?error=permissiondenied");
}

$config_permission = "n";
if ($this_page_id > 0 && $cmsusertype == "r") {
	$rs = mysql_query("SELECT page_config FROM wma_permissions WHERE user_id=".$cmsuserid." AND page_id=".$this_page_id);
	if (mysql_num_rows($rs) > 0) {
		if (mysql_result($rs,0,0) == "y")
			$config_permission = "y";
	}
}
elseif ($cmsusertype == "a" || $cmsusertype == "m") {
	$config_permission = "y";
}

# is this user a Total Control Pages editor?
$is_tcp_editor = 0;
if ($cmsusertype == "m" || $cmsusertype == "a")
	$is_tcp_editor = true;
else {
	$sql = "SELECT permission_id FROM wma_permissions WHERE page_id=28 AND content_page_id=0 AND user_id=".$cmsuserid;
	if (mysql_num_rows(mysql_query($sql)) > 0)
		$is_tcp_editor = true;
}

# is this user a Project Communicator editor?
$is_pm_editor = 0;
if ($cmsusertype == "m" || $cmsusertype == "a")
	$is_pm_editor = true;
else {
	$sql = "SELECT permission_id FROM wma_permissions WHERE page_id=37 AND user_id=".$cmsuserid;
	if (mysql_num_rows(mysql_query($sql)) > 0)
		$is_pm_editor = true;
}

# get user's name
$rs = mysql_query("SELECT user_first_name, user_last_name FROM wma_users WHERE user_id=".$cmsuserid);
if (mysql_num_rows($rs) > 0) {
	$row = mysql_fetch_row($rs);
	$cmsfirstname = $row[0];
	$cmslastname = $row[1];
}

?>