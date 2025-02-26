<?
# ALWAYS KEEP THE setup_KEEP_THIS_COPY.php FILE SINCE setup.php IS DELETED FROM THE SERVER ONCE IT'S USED.
# WE DON'T WANT TO OVERWRITE THE LOCAL COPY BY ACCIDENT

$this_page_id = 0;
require("../inc/functions.php");

$errmsg = $_REQUEST['errmsg'];
$filename = "../inc/config.php";

if ($_REQUEST['action'] == "save") {

	$errmsg = "";

	$passcrypt = "aa";
	$textcrypt = "zipadeedooda";
	$website_name = stripslashes($_REQUEST['website_name']);
	$website_url = $_REQUEST['website_url'];
	$webstats_url = $_REQUEST['webstats_url'];
	$webstats_username = $_REQUEST['webstats_username'];
	$webstats_password = $_REQUEST['webstats_password'];
	$database_name = $_REQUEST['database_name'];
	$database_name_local = $_REQUEST['database_name_local'];
	$database_host = $_REQUEST['database_host'];
	$database_host_local = $_REQUEST['database_host_local'];
	$database_username_local = $_REQUEST['database_username_local'];
	$database_password_local  = $_REQUEST['database_password_local'];
	$database_username = $_REQUEST['database_username'];
	$database_password = $_REQUEST['database_password'];
	$basedir_local = $_REQUEST['basedir_local'];
	$basedir_live = $_REQUEST['basedir_live'];
	$smtpserver_local = $_REQUEST['smtpserver_local'];
	$smtpserver_live = $_REQUEST['smtpserver_live'];
	$webmail_url = $_REQUEST['webmail_url'];
	$cms_title = stripslashes($_REQUEST['cms_title']);
	$user_first_name = $_REQUEST['user_first_name'];
	$user_last_name = $_REQUEST['user_last_name'];
	$user_username = $_REQUEST['user_username'];
	$user_password = $_REQUEST['user_password'];
	$user_type = "a";
	$enable_urlrewriting = $_REQUEST['enable_urlrewriting'];
	if ($enable_urlrewriting <> "y")
		$enable_urlrewriting = "n";
	$site_baseurl = $_REQUEST['site_baseurl'];
	if ($site_baseurl == "")
		$site_baseurl = "/";
	
	# make sure the database connection works
	$link = mysql_connect($database_host_local, $database_username_local, $database_password_local)
		or die("<p style=\"color: red;\"><strong>Could not connect to database. Please go back and re-enter the database information.</strong></p><p><strong><a href=\"javascript:history.back();\">Back</a></strong></p>");
	echo $database_name_local;
	mysql_select_db($database_name_local);
	
	# add the dump file to the database
	$mysqldumpfile = "toolbox.sql";
	# shell_exec('mysql -u'.$database_username.' -p'.$database_password.' '.$database_name.' < '.$mysqldumpfile);
	# THE SHELL_EXEC USED TO WORK BUT NOT ON WW1, USE THE LOOP BELOW INSTEAD

	##### BEGIN SHELL_EXEC REPLACEMENT #####
	# FROM http://www.daniel15.com/blog/2006/12/09/restore-mysql-dump-using-php/
	$templine = ''; # Temporary variable, used to store current query
	$lines = file($mysqldumpfile);
	foreach ($lines as $line_num => $line) {
		if (substr($line, 0, 2) != '--' && $line != '') { # Only continue if it's not a comment
			$templine .= $line; # Add this line to the current segment
			if (substr(trim($line), -1, 1) == ';') { # If it has a semicolon at the end, it's the end of the query
				mysql_query($templine) or print('Error performing query \'<b>'.$templine.'</b>\': '.mysql_error().'<br /><br />');
				$templine = '';
			}
		}
	}
	##### END SHELL_EXEC REPLACEMENT #####
		
	# add admin user account
	$sql = "INSERT INTO wma_users (user_first_name, user_last_name, user_username, user_password, user_type, user_status)
	 VALUES ('".$user_first_name."','".$user_last_name."','".$user_username."',ENCODE('".$user_password."','".$passcrypt."'),
	 '".$user_type."','y')";
	mysql_query($sql);
	$user_id = mysql_insert_id();

	# update config file
	$file_text = "<?
\$website_name = \"".$website_name."\";
\$website_url = \"".$website_url."\";

\$server = \$_SERVER['SERVER_NAME'];

if(\$server == \"fourwhat.com\" || \$server == \"dragon\"){ // development server
	
	\$basedir = \"".$basedir_local."\";
	\$smtpserver = \"".$smtpserver_local."\";
	\$database_name = \"".$database_name_local."\";
	\$database_host = \"".$database_host_local."\";
	\$database_username = \"".$database_username_local."\";
	\$database_password = \"".$database_password_local."\";
	
}else{

	\$basedir = \"".$basedir."\";
	\$smtpserver = \"".$smtpserver."\";
	\$database_name = \"".$database_name."\";
	\$database_host = \"".$database_host."\";
	\$database_username = \"".$database_username."\";
	\$database_password = \"".$database_password."\";
}


\$site_baseurl = \"".$site_baseurl."\";
\$enable_urlrewriting = \"".$enable_urlrewriting."\";
\$passcrypt = \"".$passcrypt."\";
\$textcrypt = \"".$textcrypt."\";

\$link = mysql_connect(\$database_host, \$database_username, \$database_password);
mysql_select_db(\$database_name);

/* Content Management System (CMS) Variables */
\$trOverColor = \"#FFFFFF\";
\$trOffColor = \"#e5edf4\";
\$cms_title = \"".$cms_title."\";
\$webmail_url = \"".$webmail_url."\";
\$webstats_url = \"".$webstats_url."\";
\$webstats_username = \"".$webstats_username."\";
\$webstats_password = \"".$webstats_password."\";
";

$arrAllowedPhotoFiletypes = array(".jpg"); 
$file_text .= "\$arrAllowedPhotoFiletypes = array(\".jpg\");";
$arrAllowedNoresizePhotoFiletypes = array(".jpg",".gif"); 
$file_text .= "\$arrAllowedNoresizePhotoFiletypes = array(\".jpg\",\".gif\");";

$strAllowedPhotoFiletypes = "";
foreach ($arrAllowedPhotoFiletypes as $f)
	$strAllowedPhotoFiletypes .= "<b>".$f."</b>, ";
$strAllowedPhotoFiletypes = substr($strAllowedPhotoFiletypes,0,strlen($strAllowedPhotoFiletypes)-2); # remove last space and comma

$strAllowedNoresizePhotoFiletypes = "";
foreach ($arrAllowedNoresizePhotoFiletypes as $f)
	$strAllowedNoresizePhotoFiletypes .= "<b>".$f."</b>, ";
$strAllowedNoresizePhotoFiletypes = substr($strAllowedNoresizePhotoFiletypes,0,strlen($strAllowedNoresizePhotoFiletypes)-2); # remove last space and comma

	$file_text .= "
\$strAllowedPhotoFiletypes = \"".$strAllowedPhotoFiletypes."\";
\$strAllowedNoresizePhotoFiletypes = \"".$strAllowedNoresizePhotoFiletypes."\";

\$intMaxUploadFileSize = 2; # in megabytes
\$intMaxUploadFileSizeBytes = 2000000; # in bytes

\$pagesfilesdir = \"uploads/pagesfiles/\"; # MUST STAY HERE BECAUSE WYSIWYG EDITOR IS USED ON MULTIPLE TOOLS

/* Functions, Definitions, and Declarations */
\$montharray = array(\"\",\"January\",\"February\",\"March\",\"April\",\"May\",\"June\",\"July\",\"August\",\"September\",\"October\",\"November\",\"December\");
\$timearray = array(\"00:00:00\",\"06:00:00\",\"06:30:00\",\"07:00:00\",\"07:30:00\",\"08:00:00\",\"08:30:00\",\"09:00:00\",\"09:30:00\",\"10:00:00\",\"10:30:00\",\"11:00:00\",\"11:30:00\",\"12:00:00\",\"12:30:00\",\"13:00:00\",\"13:30:00\",\"14:00:00\",\"14:30:00\",\"15:00:00\",\"15:30:00\",\"16:00:00\",\"16:30:00\",\"17:00:00\",\"17:30:00\",\"18:00:00\",\"18:30:00\",\"19:00:00\",\"19:30:00\",\"20:00:00\",\"20:30:00\",\"21:00:00\",\"21:30:00\",\"22:00:00\",\"22:30:00\",\"23:00:00\",\"23:30:00\");
\$colorarray = array(\"#FF0000\",\"#FFFF00\",\"#3333FF\",\"#FF9900\",\"#33CC33\",\"#CC0099\",\"#33FFFF\",\"#FF0000\",\"#FFFF00\",\"#3333FF\",\"#FF9900\",\"#33CC33\",\"#CC0099\",\"#33FFFF\",\"#FF0000\",\"#FFFF00\",\"#3333FF\",\"#FF9900\",\"#33CC33\",\"#CC0099\",\"#33FFFF\");

\$fontarray = array(\"Arial, Helvetica\",\"Times New Roman, Times\",\"Courier New, Courier\",\"Georgia, Times New Roman\",\"Verdana, Arial, Helvetica\",\"Geneva, Arial, Helvetica\",\"'Lucida Sans', Arial, sans-serif\",\"'Lucida Grande', Verdana, sans-serif\",\"'Trebuchet MS', 'Lucida Sans', sans-serif\");
\$fontsizearray = array(\".80\",\".85\",\".90\",\".95\",\"1\",\"1.1\",\"1.2\",\"1.3\",\"1.4\",\"1.5\",\"1.6\",\"1.7\",\"1.8\",\"1.9\",\"2\");
\$fontstylearray = array(\"normal\",\"bold\",\"italic\",\"bold, italic\");

\$fontcolorarray = array(
	array(\"#FFFFFF\",\"White\"),
	array(\"#CCCCCC\",\"Gray\"),
	array(\"#666666\",\"Dark Gray\"),
	array(\"#000000\",\"Black\"),
	array(\"#006600\",\"Dark Green\"),
	array(\"#009966\",\"Medium Green\"),
	array(\"#66CC99\",\"Light Green\"),
	array(\"#003366\",\"Dark Blue\"),
	array(\"#0099CC\",\"Medium Blue\"),
	array(\"#00CCFF\",\"Light Blue\"),
	array(\"#663300\",\"Dark Brown\"),
	array(\"#996633\",\"Light Brown\"),
	array(\"#999966\",\"Tan\"),
	array(\"#990000\",\"Maroon\"),
	array(\"#FF0000\",\"Red\"),
	array(\"#CC3366\",\"Pink\"),
	array(\"#FF99CC\",\"Light Pink\"),
	array(\"#993399\",\"Purple\"),
	array(\"#9999CC\",\"Violet\"),
	array(\"#FF6600\",\"Orange\"),
	array(\"#FFCC66\",\"Light Orange\"),
	array(\"#FFFF00\",\"Yellow\"),
	array(\"#FFFFCC\",\"Light Yellow\")
);
\$fontcolorarray_count = count(\$fontcolorarray);

?>";

	$fp = fopen($filename, "w") or die("Could not open file ".$filename);
	fwrite($fp, $file_text);
	fclose($fp);

	# log the user into the admin account
	$expiration = time()+60*60*24*7;
	setcookie("cmsusername",$user_username,$expiration);
	setcookie("cmspassword",base64_encode($user_password),$expiration);
	setcookie("cmsfirstname",$user_first_name,$expiration);
	setcookie("cmsusertype",$user_type,$expiration);
	setcookie("cmsuserid",$user_id,$expiration);
	# refresh to the toolbox admin page so tools can be selected
	header("location: admin_select.php");
	exit;
}

# default values
if ($website_url == "") 
	$website_url = "http://";
if ($basedir_live == "") 
	$basedir_live = "/home/SITENAME/public_html/";
if ($basedir_local == "") 
	$basedir_local = "/srv/www/htdocs/dev/SITENAME/";
if ($smtpserver_live == "") 
	$smtpserver_live = "util01.4what.net";
if ($smtpserver_local == "") 
	$smtpserver_local = "127.0.0.1";
if ($database_host == "") 
	$database_host = "util01.4what.net";
if ($database_host_local == "") 
	$database_host_local = "localhost";
if ($database_username_local == "") 
	$database_username_local = "4what";
if ($database_password_local == "") 
	$database_password_local = "4what.123";
if ($webmail_url == "") 
	$webmail_url = "http://webmail.4what.net";
if ($webstats_url == "") 
	$webstats_url = "http://util01.4what.net:8080/";
if ($user_first_name == "") 
	$user_first_name = "4What";
if ($user_last_name == "") 
	$user_last_name = "Interactive";
if ($user_username == "") 
	$user_username = "4what";
if ($user_password == "") 
	$user_password = "";
if ($site_baseurl == "")
	$site_baseurl = "/";
if ($cms_title == "")
	$cms_title = "Toolbox";

if ($errmsg <> "")
	$errmsg = "<p class=\"alert\">".$errmsg."</p>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Web Toolbox Setup</title>
<link href="inc/master.css" rel="stylesheet" type="text/css" />
</head>

<body>
<? # require("inc/header.php"); ?>
<div id="toolbar">
	<div id="toolbar_logo"><img src="images/logos/4what.gif" width="38" height="38" border="0" alt="Toolbox" /></div>
	<h2>Toolbox Set Up</h2>
</div>

<div id="contentarea">
<div id="content">

	<?= $errmsg; ?>

  <form action="setup.php" method="post">
  <input type="hidden" name="action" value="save" />	

	<fieldset>
		<legend>Web Site Information</legend>
		<dl>
			<dt>Web Site Name:</dt>
			<dd><input name="website_name" type="text" class="formfield" id="website_name" size="40" value="<?= $website_name; ?>" /></dd>
			<dt>Web Site URL:</dt>
			<dd><input name="website_url" type="text" class="formfield" id="website_url" size="40" value="<?= $website_url; ?>" /></dd>
			<dt>Server Base Directory (local):</dt>
			<dd><input name="basedir_local" type="text" class="formfield" id="basedir" size="40" value="<?= $basedir_local; ?>" /></dd>
			<dt>Server Base Directory (live):</dt>
			<dd><input name="basedir_live" type="text" class="formfield" id="basedir_live" size="40" value="<?= $basedir_live; ?>" /></dd>
			<dt>Web Site Base Directory:</dt>
			<dd>http://www.domainname.com<input name="site_baseurl" type="text" class="formfield" id="site_baseurl" size="40" value="<?= $site_baseurl; ?>" /> <em>always end with a forward slash: /</em></dd>
			<dt>SMTP Server (development):</dt>
			<dd><input name="smtpserver_local" type="text" class="formfield" id="smtpserver_local" size="20" value="<?= $smtpserver_local; ?>" /></dd>
			<dt>SMTP Server:</dt>
			<dd><input name="smtpserver_live" type="text" class="formfield" id="smtpserver_live" size="20" value="<?= $smtpserver_live; ?>" /></dd>
			<dt>URL Rewriting:</dt>
			<dd><input type="checkbox" name="enable_urlrewriting" value="y" <? if ($enable_urlrewriting == "y") echo "checked=\"checked\""; ?> /> Enable URL Rewriting site-wide</dd>
		</dl>
	</fieldset>
	<fieldset>
		<legend>Database</legend>
		<dl>
			<dt>Database Name (local):</dt>
			<dd><input name="database_name_local" type="text" class="formfield" id="database_name_local" size="40" value="<?= $database_name_local; ?>" /></dd>
			<dt>Host Name:</dt>
			<dd><input name="database_host_local" type="text" class="formfield" id="database_host_local" size="40" value="<?= $database_host_local; ?>" /></dd>
			<dt>Username:</dt>
			<dd><input name="database_username_local" type="text" class="formfield" id="database_username_local" size="20" value="<?= $database_username_local; ?>" /></dd>
			<dt>Password:</dt>
			<dd><input name="database_password_local" type="text" class="formfield" id="database_password_local" size="20" value="<?= $database_password_local; ?>" /></dd>
			<p>
			<dt>Database Name (live):</dt>
			<dd><input name="database_name" type="text" class="formfield" id="database_name" size="40" value="<?= $database_name; ?>" /></dd>
			<dt>Host Name:</dt>
			<dd><input name="database_host" type="text" class="formfield" id="database_host" size="40" value="<?= $database_host; ?>" /></dd>
			<dt>Username:</dt>
			<dd><input name="database_username" type="text" class="formfield" id="database_username" size="20" value="<?= $database_username; ?>" /></dd>
			<dt>Password:</dt>
			<dd><input name="database_password" type="text" class="formfield" id="database_password" size="20" value="<?= $database_password; ?>" /></dd>
			</p>
		</dl>
	</fieldset>
	<fieldset>
		<legend>Toolbox</legend>
		<dl>
			<dt>Webmail URL:</dt>
			<dd><input name="webmail_url" type="text" class="formfield" id="webmail_url" size="40" value="<?= $webmail_url; ?>" /></dd>
			<dt>Toolbox Title:</dt>
			<dd><input name="cms_title" type="text" class="formfield" id="cms_title" size="40" value="<?= $cms_title; ?>" /></dd>
		</dl>
	</fieldset>
	<fieldset>
		<legend>Administrator Account</legend>
		<dl>
			<dt>Username:</dt>
			<dd><input name="user_username" type="text" class="formfield" id="user_username" size="20" value="<?= $user_username; ?>" /></dd>
			<dt>Password:</dt>
			<dd><input name="user_password" type="text" class="formfield" id="user_password" size="20" value="<?= $user_password; ?>" /></dd>
			<dt>First Name:</dt>
			<dd><input name="user_first_name" type="text" class="formfield" id="user_first_name" size="40" value="<?= $user_first_name; ?>" /></dd>
			<dt>Last Name:</dt>
			<dd><input name="user_last_name" type="text" class="formfield" id="user_last_name" size="40" value="<?= $user_last_name; ?>" /></dd>
		</dl>
	</fieldset>
	<fieldset>
		<legend>Web Site Tracking Statistics (optional)</legend>
		<dl>
			<dt>URL:</dt>
			<dd><input name="webstats_url" type="text" class="formfield" id="webstats_url" size="40" value="<?= $webstats_url; ?>" /> <em>Leave URL blank if user stats won't be tracked </em></dd>
			<dt>Username:</dt>
			<dd><input name="webstats_username" type="text" class="formfield" id="webstats_username" size="20" value="<?= $webstats_username; ?>" /></dd>
			<dt>Password:</dt>
			<dd><input name="webstats_password" type="text" class="formfield" id="webstats_password" size="20" value="<?= $webstats_password; ?>" /></dd>
		</dl>
	</fieldset>
	<div class="buttonarea"><input type="submit" value="Save" class="button" /></div>
  </form>
	
</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
