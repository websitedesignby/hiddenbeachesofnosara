<?
require("inc/config.php");
require("inc/functions.php");
require("inc/config_pages.php");

$page_id = 0;

$email = $_REQUEST['email'];

if ($email <> "") {
	$error = "";
	$rs = mysql_query("SELECT user_username, DECODE(user_password,'".$passcrypt."') FROM wma_users WHERE user_email='".$email."'");
	if (mysql_num_rows($rs) == 0) 
		$error = "true";
	else { 
		$user_username = mysql_result($rs,0,0);
		$user_password = mysql_result($rs,0,1);
		$email_subject = "Your ".$website_name." Account";
		$email_body = "Your login information for ".$website_name." has been requested. Use the following username and password to log into ".$website_url.":
	
	username: ".$user_username."
	password: ".$user_password."
";
		$email_body = stripslashes($email_body);

		require_once("class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->IsSMTP(); # telling the class to use SMTP
		$mail->Host = "$smtpserver"; # SMTP server
		$mail->AddAddress($email);
		$mail->FromName = $website_name;
		$mail->From = $email;
		$mail->Subject = "$email_subject";
		$mail->Body = "$email_body";
		$mail->WordWrap = 50;
		if (!$mail->Send()) {
			$error = true;
			#echo "Message was not sent<br />Mailer Error: ".$mail->ErrorInfo."<br />";
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= $website_name; ?></title>
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
</head>
<body>
<div align="center"><div id="pagearea">

<?
if ($pages_show_menu == "y") {
	include("inc/tcp_menu_bodydata.php");
}
?>

	<h1>Forgot Password</h1>
	
	<?
	if ($email <> "") {
		if ($error == "") 
			echo "<p align=\"center\">Your password has been sent to <strong>".$email."</strong>.</p>";
		else 
			echo "<p align=\"center\"><strong>Error:</strong> The e-mail address you entered could not be found.</p>
			  <p align=\"center\"><strong><a href=\"page_forgot.php\">Please Try Again</a></strong>.</p>";
	} else {
	?>
	<p>Enter the e-mail address for your account below and your password will be e-mailed to you.</p>
	<form action="page_forgot.php" method="post">
	<table border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td class="formlabel">E-mail Address:&nbsp;</td>
        <td><input name="email" type="text" class="formfield" id="email" size="30"></td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
		<td><input type="submit" value="Request Password" class="button" /></td>
      </tr>
    </table>
	</form>
	<? } ?>

</div></div>
</body>
</html>