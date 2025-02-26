<?
$expiration = time()+(60*60*24); # 1 day

# grab username and password from cookies 
# $page_id, $page_idname FROM page.php
$pageusername = $_COOKIE['pageusername'];
$pagepassword = base64_decode($_COOKIE['pagepassword']);

$login_baseurl = $site_baseurl.(($enable_urlrewriting == "y")?$page_idname."/login/":"page_login.php?page=".$page_idname);

if ($pageusername == "" || $pagepassword == "") { # cookies don't exist
	$pageusername = $_REQUEST['username']; # grab from login form
	$pagepassword = $_REQUEST['password']; # grab from login form
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
				setcookie("pageusername",$secure_username,$expiration,"/");
				setcookie("pagepassword",base64_encode($secure_password),$expiration,"/");
				header("location: ".$site_baseurl.(($enable_urlrewriting == "y")?$page_idname."/":"page.php?page=".$page_idname));
			}
		}
	}
}
else { # make sure the cookies are correct
	$sql = "SELECT user_id, DECODE(user_password,'".$passcrypt."'), user_status
	 FROM wma_users WHERE user_username='".$pageusername."' AND user_type='f'";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) == 0)
		header("location: ".$login_baseurl."?error=username"); # username does not exist
	else {
		$row = mysql_fetch_array($rs);
		#$secure_user_id = $row[0];
		$secure_user_password = $row[1];
		$secure_user_status = $row[2];
		if ($secure_user_status == "n" || $secure_user_status == "d")
			header("location: ".$login_baseurl."?error=disabled"); # account disabled or deleted
		if ($secure_user_password <> $pagepassword)
			header("location: ".$login_baseurl."?error=password"); # wrong password
	}
}
?>