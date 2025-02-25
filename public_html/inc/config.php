<?
$website_name = "Hidden Beaches of Nosara";
$website_url = "http://www.hiddenbeachesofnosara.com/";
$website_server = "live";
$enable_urlrewriting = "y";
$passcrypt = "aa";
$textcrypt = "zipadeedooda";

$basedir_local = "/srv/www/htdocs/dev/playas/";
$smtpserver_local = "127.0.0.1";
$database_name_local = "playaescondidasnosara_com_-_1";
$database_host_local = "localhost";
$database_username_local = "4what";
$database_password_local = "4what.123";
$site_baseurl_local = "/dev/playas/";

$basedir_live = "/home/nosara/public_html/";
$smtpserver_live = "localhost";
$database_name_live = "playasescondidasnosara_com_1";
$database_host_live = "localhost";
$database_username_live = "nosara";
$database_password_live = "c0sta";
$site_baseurl_live = "/";

$server = $_SERVER['SERVER_NAME'];

if ($server == "fourwhat.com" || $server == "dragon") { // development server
	$basedir = $basedir_local;
	$smtpserver = $smtpserver_local;
	$database_name = $database_name_local;
	$database_host = $database_host_local;
	$database_username = $database_username_local;
	$database_password = $database_password_local;
	$site_baseurl = $site_baseurl_local;
} else {
	$basedir = $basedir_live;
	$smtpserver = $smtpserver_live;
	$database_name = $database_name_live;
	$database_host = $database_host_live;
	$database_username = $database_username_live;
	$database_password = $database_password_live;
	$site_baseurl = $site_baseurl_live;
}
$link = mysql_connect($database_host, $database_username, $database_password) or die("aaaaarg<br>" . mysql_error());
mysql_select_db($database_name) or die("AAAAARG<br>" . mysql_error());

/* Toolbox Variables */
$trOverColor = "#FFFFFF";
$trOffColor = "#e5edf4";
$cms_title = "Playa Escondidas Nosara Toolbox";
$webmail_url = "http://webmail.4what.net/";
$webstats_url = "http://www.google.com/analytics/";
$webstats_username = "something";
$webstats_password = "something123";
$arrAllowedPhotoFiletypes = array(".jpg");$arrAllowedNoresizePhotoFiletypes = array(".jpg",".gif");
$strAllowedPhotoFiletypes = "<strong>.jpg</strong>";
$strAllowedNoresizePhotoFiletypes = "<strong>.jpg</strong>, <strong>.gif</strong>";

$intMaxUploadFileSize = 10; # in megabytes
$intMaxUploadFileSizeBytes = 10000000; # in bytes

$pagesfilesdir = "uploads/pagesfiles/"; # MUST STAY HERE BECAUSE WYSIWYG EDITOR IS USED ON MULTIPLE TOOLS

/* Functions, Definitions, and Declarations */
$montharray = array("","January","February","March","April","May","June","July","August","September","October","November","December");
$timearray = array("00:00:00","00:30:00","01:00:00","01:30:00","02:00:00","02:30:00","03:00:00","03:30:00","04:00:00","04:30:00","05:00:00","05:30:00","06:00:00","06:30:00","07:00:00","07:30:00","08:00:00","08:30:00","09:00:00","09:30:00","10:00:00","10:30:00","11:00:00","11:30:00","12:00:00","12:30:00","13:00:00","13:30:00","14:00:00","14:30:00","15:00:00","15:30:00","16:00:00","16:30:00","17:00:00","17:30:00","18:00:00","18:30:00","19:00:00","19:30:00","20:00:00","20:30:00","21:00:00","21:30:00","22:00:00","22:30:00","23:00:00","23:30:00");
$colorarray = array("#FF0000","#FFFF00","#3333FF","#FF9900","#33CC33","#CC0099","#33FFFF","#FF0000","#FFFF00","#3333FF","#FF9900","#33CC33","#CC0099","#33FFFF","#FF0000","#FFFF00","#3333FF","#FF9900","#33CC33","#CC0099","#33FFFF");

$fontarray = array("Arial, Helvetica","Times New Roman, Times","Courier New, Courier","Georgia, Times New Roman","Verdana, Arial, Helvetica","Geneva, Arial, Helvetica","'Lucida Sans', Arial, sans-serif","'Lucida Grande', Verdana, sans-serif","'Trebuchet MS', 'Lucida Sans', sans-serif");
#$fontsizearray = array("9","10","11","12","14","16","18","20","24","28","32","36");
$fontsizearray = array(".80",".85",".90",".95","1","1.1","1.2","1.3","1.4","1.5","1.6","1.7","1.8","1.9","2");
$fontstylearray = array("normal","bold","italic","bold, italic");

$fontcolorarray = array(
	array("#FFFFFF","White"),
	array("#CCCCCC","Gray"),
	array("#666666","Dark Gray"),
	array("#000000","Black"),
	array("#006600","Dark Green"),
	array("#009966","Medium Green"),
	array("#66CC99","Light Green"),
	array("#003366","Dark Blue"),
	array("#0099CC","Medium Blue"),
	array("#00CCFF","Light Blue"),
	array("#663300","Dark Brown"),
	array("#996633","Light Brown"),
	array("#999966","Tan"),
	array("#990000","Maroon"),
	array("#FF0000","Red"),
	array("#CC3366","Pink"),
	array("#FF99CC","Light Pink"),
	array("#993399","Purple"),
	array("#9999CC","Violet"),
	array("#FF6600","Orange"),
	array("#FFCC66","Light Orange"),
	array("#FFFF00","Yellow"),
	array("#FFFFCC","Light Yellow")
);
$fontcolorarray_count = count($fontcolorarray);

?>
