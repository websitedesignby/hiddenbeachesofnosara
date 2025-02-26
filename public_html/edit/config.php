<?
$this_page_id = 0;
require("../inc/config.php");
require("../inc/functions.php");
require("secure.php");

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

	$database_name_live = $_REQUEST['database_name_live'];
	$database_name_local = $_REQUEST['database_name_local'];
	$database_host_live = $_REQUEST['database_host_live'];
	$database_host_local = $_REQUEST['database_host_local'];
	$database_username_live = $_REQUEST['database_username_live'];
	$database_username_local = $_REQUEST['database_username_local'];
	$database_password_live = $_REQUEST['database_password_live'];
	$database_password_local  = $_REQUEST['database_password_local'];
	$basedir_live = $_REQUEST['basedir_live'];
	$basedir_local = $_REQUEST['basedir_local'];
	$smtpserver_live = $_REQUEST['smtpserver_live'];
	$smtpserver_local = $_REQUEST['smtpserver_local'];

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
	
	$intMaxUploadFileSize = round($_REQUEST['intMaxUploadFileSize']);

	# update config file
	$file_text = "<?
\$website_name = \"".$website_name."\";
\$website_url = \"".$website_url."\";

\$basedir_local = \"".$basedir_local."\";
\$smtpserver_local = \"".$smtpserver_local."\";
\$database_name_local = \"".$database_name_local."\";
\$database_host_local = \"".$database_host_local."\";
\$database_username_local = \"".$database_username_local."\";
\$database_password_local = \"".$database_password_local."\";

\$basedir_live = \"".$basedir_live."\";
\$smtpserver_live = \"".$smtpserver_live."\";
\$database_name_live = \"".$database_name_live."\";
\$database_host_live = \"".$database_host_live."\";
\$database_username_live = \"".$database_username_live."\";
\$database_password_live = \"".$database_password_live."\";

\$server = \$_SERVER['SERVER_NAME'];

if(\$server == \"fourwhat.com\" || \$server == \"dragon\"){ // development server
	
	\$basedir = \"".$basedir_local."\";
	\$smtpserver = \"".$smtpserver_local."\";
	\$database_name = \"".$database_name_local."\";
	\$database_host = \"".$database_host_local."\";
	\$database_username = \"".$database_username_local."\";
	\$database_password = \"".$database_password_local."\";
	
}else{

	\$basedir = \"".$basedir_live."\";
	\$smtpserver = \"".$smtpserver_live."\";
	\$database_name = \"".$database_name_live."\";
	\$database_host = \"".$database_host_live."\";
	\$database_username = \"".$database_username_live."\";
	\$database_password = \"".$database_password_live."\";
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

\$intMaxUploadFileSize = ".$intMaxUploadFileSize."; # in megabytes
\$intMaxUploadFileSizeBytes = ".$intMaxUploadFileSize."000000; # in bytes

\$pagesfilesdir = \"uploads/pagesfiles/\"; # MUST STAY HERE BECAUSE WYSIWYG EDITOR IS USED ON MULTIPLE TOOLS

/* Functions, Definitions, and Declarations */
\$montharray = array(\"\",\"January\",\"February\",\"March\",\"April\",\"May\",\"June\",\"July\",\"August\",\"September\",\"October\",\"November\",\"December\");
\$timearray = array(\"00:00:00\",\"00:30:00\",\"01:00:00\",\"01:30:00\",\"02:00:00\",\"02:30:00\",\"03:00:00\",\"03:30:00\",\"04:00:00\",\"04:30:00\",\"05:00:00\",\"05:30:00\",\"06:00:00\",\"06:30:00\",\"07:00:00\",\"07:30:00\",\"08:00:00\",\"08:30:00\",\"09:00:00\",\"09:30:00\",\"10:00:00\",\"10:30:00\",\"11:00:00\",\"11:30:00\",\"12:00:00\",\"12:30:00\",\"13:00:00\",\"13:30:00\",\"14:00:00\",\"14:30:00\",\"15:00:00\",\"15:30:00\",\"16:00:00\",\"16:30:00\",\"17:00:00\",\"17:30:00\",\"18:00:00\",\"18:30:00\",\"19:00:00\",\"19:30:00\",\"20:00:00\",\"20:30:00\",\"21:00:00\",\"21:30:00\",\"22:00:00\",\"22:30:00\",\"23:00:00\",\"23:30:00\");
\$colorarray = array(\"#FF0000\",\"#FFFF00\",\"#3333FF\",\"#FF9900\",\"#33CC33\",\"#CC0099\",\"#33FFFF\",\"#FF0000\",\"#FFFF00\",\"#3333FF\",\"#FF9900\",\"#33CC33\",\"#CC0099\",\"#33FFFF\",\"#FF0000\",\"#FFFF00\",\"#3333FF\",\"#FF9900\",\"#33CC33\",\"#CC0099\",\"#33FFFF\");

\$fontarray = array(\"Arial, Helvetica\",\"Times New Roman, Times\",\"Courier New, Courier\",\"Georgia, Times New Roman\",\"Verdana, Arial, Helvetica\",\"Geneva, Arial, Helvetica\",\"'Lucida Sans', Arial, sans-serif\",\"'Lucida Grande', Verdana, sans-serif\",\"'Trebuchet MS', 'Lucida Sans', sans-serif\");
#\$fontsizearray = array(\"9\",\"10\",\"11\",\"12\",\"14\",\"16\",\"18\",\"20\",\"24\",\"28\",\"32\",\"36\");
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
	
	# log the user out so they must log back in with these new settings
	$expiration = time()-1; # cookies expired one second ago
	setcookie("cmsusername","",$expiration);
	setcookie("cmspassword","",$expiration);
	setcookie("cmsusertype","",$expiration);
	setcookie("cmsfirstname","",$expiration);
	setcookie("cmsuserid","",$expiration);

	header("location: config.php");
}

# parse through the text file to find variables and values
$fp = fopen($filename, "r") or die("Could not open file");
while (!feof($fp)) {
	$line = fgets($fp,1024);
	if (substr($line, 0, 1) == "$") { # if the line starts with "$", it's a variable assignment
		$variable_name = substr($line, 1, strpos($line,"=")-2); # grab text after "$" and before "="
		$variable_value = substr($line, strpos($line,"=")+1, (strpos($line,";")-strpos($line,"=")-1)); # grab text between "=" and ";"
		if (substr($variable_value,1,1) == '"') { # the variable value is encapsulated in quotes
			$variable_value = substr($variable_value,2,strlen($variable_value)-3); # remove the quotes
		}
		${$variable_name} = trim($variable_value);
	}
}

if ($site_baseurl == "")
	$site_baseurl = "/";

if ($errmsg <> "")
	$errmsg = "<p class=\"alert\">".$errmsg."</p>";
else
	$errmsg = "<p class=\"alert\">Warning! Do not update anything on this page unless you have been instructed to do so by your web developer. Making changes to this page may produce errors in the Toolbox and on the web site.</p>";
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
	<div id="toolbar_logo"><a href="admin.php"><img src="images/logos/4what.gif" width="38" height="38" border="0" alt="Toolbox" /></a></div>
	<h2><a href="admin.php">Administration</a></h2>
</div>

<div id="contentarea">
<div id="content">

	<div class="tabs">
		<ul>
			<li><a href="admin.php">Tools</a></li>
			<li class="selected"><a href="config.php">Toolbox Configuration</a></li>
			<? if ($cmsusertype == "a") { # only 4What should see this link ?>
			<li><a href="admin_select.php">Tool Selector</a></li>
			<? } ?>
			<li><a href="styles.php">WYSIWYG Styles</a></li>
			<li><a href="phpinfo.php">PHP Info</a></li>
		</ul>
	</div>
	
	<?= $errmsg; ?>
	
  <form action="config.php" method="post">
  <input type="hidden" name="action" value="save" />
	<fieldset>
		<legend>Web Site Information</legend>
		<dl>
			<dt>Web Site Name:</dt>
			<dd><input name="website_name" type="text" class="formfield" id="website_name" size="40" value="<?= $website_name; ?>" /></dd>
			<dt>Web Site URL (live):</dt>
			<dd><input name="website_url" type="text" class="formfield" id="website_url" size="40" value="<?= $website_url; ?>" /></dd>
			<dt>Web Site Base Directory:</dt>
			<dd>http://www.domainname.com<input name="site_baseurl" type="text" class="formfield" id="site_baseurl" size="40" value="<?= $site_baseurl; ?>" /> <em>always end with a forward slash: /</em></dd>
			<dt>URL Rewriting:</dt>
			<dd><input type="checkbox" name="enable_urlrewriting" value="y" <? if ($enable_urlrewriting == "y") echo "checked=\"checked\""; ?> /> Enable URL Rewriting site-wide</dd>
			<dt>&nbsp;</dt>
			<dd>&nbsp;</dd>
			<dt>Server Base Directory (local):</dt>
			<dd><input name="basedir_local" type="text" class="formfield" id="basedir_local" size="50" value="<?= $basedir_local; ?>" /></dd>
			<dt>SMTP Server (local):</dt>
			<dd><input name="smtpserver_local" type="text" class="formfield" id="smtpserver_local" size="20" value="<?= $smtpserver_local; ?>" /></dd>
			<dt>&nbsp;</dt>
			<dd>&nbsp;</dd>
			<dt>Server Base Directory (live):</dt>
			<dd><input name="basedir_live" type="text" class="formfield" id="basedir_live" size="50" value="<?= $basedir_live; ?>" /></dd>
			<dt>SMTP Server (live):</dt>
			<dd><input name="smtpserver_live" type="text" class="formfield" id="smtpserver_live" size="20" value="<?= $smtpserver_live; ?>" /></dd>
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
			<dt>&nbsp;</dt>
			<dd>&nbsp;</dd>
			<dt>Database Name (live):</dt>
			<dd><input name="database_name_live" type="text" class="formfield" id="database_name_live" size="40" value="<?= $database_name_live; ?>" /></dd>
			<dt>Host Name:</dt>
			<dd><input name="database_host_live" type="text" class="formfield" id="database_host_live" size="40" value="<?= $database_host_live; ?>" /></dd>
			<dt>Username:</dt>
			<dd><input name="database_username_live" type="text" class="formfield" id="database_username_live" size="20" value="<?= $database_username_live; ?>" /></dd>
			<dt>Password:</dt>
			<dd><input name="database_password_live" type="text" class="formfield" id="database_password_live" size="20" value="<?= $database_password_live; ?>" /></dd>
		</dl>
	</fieldset>
	<fieldset>
		<legend>Toolbox</legend>
		<dl>
			<dt>Webmail URL:</dt>
			<dd><input name="webmail_url" type="text" class="formfield" id="webmail_url" size="40" value="<?= $webmail_url; ?>" /> <em>Leave URL blank if webmail is not enabled</em></dd>
			<dt>Toolbox Title:</dt>
			<dd><input name="cms_title" type="text" class="formfield" id="cms_title" size="40" value="<?= $cms_title; ?>" /></dd>
			<dt>*Max Upload File Size:</dt>
			<dd><input name="intMaxUploadFileSize" type="text" class="formfield" id="intMaxUploadFileSize" size="3" value="<?= $intMaxUploadFileSize; ?>" /> MB <em>(integers only)</em></dd>
		</dl>
	</fieldset>
	<fieldset>
		<legend>Web Site Tracking Statistics (optional)</legend>
		<dl>
			<dt>URL:</dt>
		  <dd><input name="webstats_url" type="text" class="formfield" id="webstats_url" size="40" value="<?= $webstats_url; ?>" /> <em>Leave URL blank if  stats aren't tracked.</em></dd>
			<dt>Username:</dt>
			<dd><input name="webstats_username" type="text" class="formfield" id="webstats_username" size="20" value="<?= $webstats_username; ?>" /></dd>
			<dt>Password:</dt>
			<dd><input name="webstats_password" type="text" class="formfield" id="webstats_password" size="20" value="<?= $webstats_password; ?>" /></dd>
		</dl>
	</fieldset>
	<div class="buttonarea"><input type="submit" value="Save" class="button" /></div>
  </form>

	<p class="notes">*Note - The Maximum Upload File Size must be within the limits set by the php.ini file.</p>
	
</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
