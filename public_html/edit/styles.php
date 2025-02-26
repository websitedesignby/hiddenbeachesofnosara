<?
$this_page_id = 0;
require("../inc/config.php");
require("../inc/functions.php");
require("secure.php");

$errmsg = $_REQUEST['errmsg'];
$css_filename = "inc/wysiwyg.css";

if ($_REQUEST['action'] == "save") {

	$errmsg = "";

	$css_file_text = $_REQUEST['css_file_text'];

	# update css file
	$cssfp = fopen($css_filename, "w") or die("Could not open file ".$css_filename);
	fwrite($cssfp, $css_file_text);
	fclose($cssfp);

	header("location: styles.php");
}

# parse through the text file to find variables and values
$css_file_text = "";
$fp = fopen($css_filename, "r") or die("Could not open file");
while (!feof($fp)) {
	$line = fgets($fp,1024);
	$css_file_text .= $line;
}

if ($errmsg <> "")
	$errmsg = "<p class=\"alert\">".$errmsg."</p>";
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
			<li><a href="config_admin.php">Toolbox Configuration</a></li>
			<? if ($cmsusertype == "a") { # only 4What should see this link ?>
			<li><a href="admin_select.php">Tool Selector</a></li>
			<? } ?>
			<li class="selected"><a href="styles.php">WYSIWYG Styles</a></li>
			<li><a href="phpinfo.php">PHP Info</a></li>
		</ul>
	</div>
	
	<?= $errmsg; ?>
	
	<p class="notes">This page updates the style sheet used to style content in the WYSIWYG editors throughout the Toolbox. This style sheet is independent of the web site's style sheets so selectors listed here must match the web site's style sheet.</p>
	
	<form action="styles.php" method="post">
	<input type="hidden" name="action" value="save" />
	<fieldset>
		<legend>Update WYSIWYG Editor CSS file</legend>
		<p><textarea name="css_file_text" style="width: 90%; height: 300px;" class="formfield"><?= $css_file_text; ?></textarea></p>
	</fieldset>
	<div class="buttonarea"><input type="submit" value="Save" class="button" /></div>
	</form>

	<fieldset>
		<legend>WYSIWYG.css Template</legend>
		<p class="notes">The file should contain the following selectors. Add properties as needed and be sure to include the properties indicated.</p>
		<p>
		body {<br />
		font-size: 76%; <span class="notes">/* acceptable values are 60%, 69%, or 76% */</span><br />
		}
		</p><p>
		p, table, td, th, li {<br />
			font-size: 1em; <span class="notes">/* do not delete! */</span><br />
		}
		</p><p>
		a, a:hover, a:visited, a:active, a:link {<br />
		}
		</p><p>
		h1 {<br />
		}
		</p><p>
		h2 {<br />
		}
		</p><p>
		h3 {<br />
		}
		</p><p>
		h4 {<br />
		}
		</p><p>
		h5 {<br />
		}
		</p><p>
		h6 {<br />
		}
		</p><p>
		hr {<br />
		}
		</p>
		<p class="notes">/* Add any class selectors here. Classes will be displayed in the WYSIWYG editor's <strong>Styles</strong> drop down menu. */</p>
	</fieldset>

	<p class="notes">&nbsp;</p>
	
</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
