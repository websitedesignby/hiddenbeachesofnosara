<?
require("inc/config.php");
require("inc/functions.php");
require("inc/config_forms.php");

$action = $_REQUEST['action'];
#$form_id = $_REQUEST['f'];
$form_idname = $_REQUEST['f'];

# prepage page name for searching the database
$form_idname = cleanUpFormNameForURL($form_idname); # strip spaces
if ($form_idname == "") # no form was selected
	$form_name = $form_web_title;
elseif ($form_idname <> "" && preg_match("'[^A-Za-z0-9 ]'", $form_idname)) # only allow alphanumeric characters
	$form_name = $form_web_title;
else {
	$sql = "SELECT form_id, form_name, form_custom, form_submit_label, form_reset_label, form_message, form_intro, form_side
	 FROM forms WHERE LCASE(REPLACE(REPLACE(REPLACE(form_name,'-',''),'\'',''),' ',''))='".$form_idname."'";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) > 0) {
		$row = mysql_fetch_array($rs);
		$form_id = $row[0];
		$form_name = htmlentities($row[1]);
		$form_custom = $row[2];
		$form_submit_label = htmlentities($row[3]);
		$form_reset_label = htmlentities($row[4]);
		$form_message = $row[5];
		$form_intro = $row[6];
		$form_side = $row[7];
	}
}

if ($form_custom <> "") { # redirect to custom form
	header("location: ".$form_custom);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?= $form_name; ?></title>
<?= $meta_content; ?>
<link href="<?= $site_baseurl; ?>inc/pages.css" rel="stylesheet" type="text/css" />
<link href="<?= $site_baseurl; ?>css/styles.css" rel="stylesheet" type="text/css" />
<link href="<?= $site_baseurl; ?>css/menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="javascript" src="<?= $site_baseurl; ?>inc/embed.js"></script>
<script language="javascript" src="<?= $site_baseurl; ?>scripts/dropdown.js"></script>
<? if ($pages_show_menu == "y") { ?>
<!-- ################# BEGIN MENU HEADER DATA ################# -->
<link href="<?= $site_baseurl; ?>css/tcp_menu.css.php" rel="stylesheet" type="text/css" />
<script language="javascript" src="<?= $site_baseurl; ?>inc/tcp_menu.js"></script>
<? include("inc/tcp_menu_headdata.php"); ?>
<!-- ################## END MENU HEADER DATA ################## -->
<? } # / show menu ?>
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<?= $site_baseurl; ?>scripts/pngfix.js"></script>
<![endif]-->
<script language="javascript">
<!--//

<?
if (is_numeric($form_id)) {

echo "function isReady() {\n";

$sql = "SELECT field_id, field_type, field_label, field_validation, field_values
 FROM form_fields WHERE form_id=".$form_id." AND field_required='y' ORDER BY field_order";
$rs = mysql_query($sql);
if (mysql_num_rows($rs) > 0) {
	$counter = 0;
	while ($rows = mysql_fetch_array($rs)) {
		$counter++;
		$field_id = $rows[0];
		$field_type = $rows[1];
		$field_label = $rows[2];
		$field_validation = $rows[3];
		######## TEXT FIELD ########
		if ($field_type == "t") {
			if ($field_validation == "e") { # must be valid e-mail address
				echo (($counter > 1)?"else ":"")."if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.form".$form_id.".field".$field_id.".value))) {
					alert(\"Please fill in a valid e-mail address.\");
					return false;
				}\n";
			}
			else if ($field_validation == "7" || $field_validation == "8") { # answer to math equation for spam prevention
				echo (($counter > 1)?"else ":"")."if (document.form".$form_id.".field".$field_id.".value != \"".$field_validation."\") {
				  alert(\"Please complete ".$field_label.".\");
				  return false;
				}\n";
			}
			else { # field_validation == "o" - no validation
				echo (($counter > 1)?"else ":"")."if (document.form".$form_id.".field".$field_id.".value <= 1) {
				  alert(\"Please complete ".$field_label.".\");
				  return false;
				}\n";
			}
		}
		######## TEXT AREA ########
		if ($field_type == "a") {
			echo (($counter > 1)?"else ":"")."if (document.form".$form_id.".field".$field_id.".value <= 1) {
			  alert(\"Please complete ".$field_label.".\");
			  return false;
			}\n";
		}
		######## DROP DOWN ########
		if ($field_type == "d") {
			echo (($counter > 1)?"else ":"")."if (document.form".$form_id.".field".$field_id.".options[document.form".$form_id.".field".$field_id.".selectedIndex].value == \"\") {
			  alert(\"Please complete ".$field_label.".\");
			  return false;
			}\n";
		}
		######## CHECKBOXES ########
		if ($field_type == "c") { # this validation does not work in Firefox
			$checkbox_values .= "";
			$value_array = split("\n",$rows[4]);
			$checkbox_counter = 0;
			foreach ($value_array as $myvalue) {
				#$myvalue = trim((substr($myvalue,0,1) == "*")?substr($myvalue,1,strlen($myvalue)-1):$myvalue);
				$checkbox_values .= "document.form".$form_id."[\"field".$field_id."[]\"][".$checkbox_counter."].checked == false && ";
				$checkbox_counter++;
			}
			if ($checkbox_values <> "") # remove final " && "
				$checkbox_values = substr($checkbox_values,0,strlen($checkbox_values)-4);
			echo (($counter > 1)?"else ":"")."if (".$checkbox_values.") {
			  alert(\"Please complete ".$field_label.". \");
			  return false;
			}\n";
		}
		######## RADIO BUTTONS ########
		if ($field_type == "r") {
			$radio_values .= "";
			$value_array = split("\n",$rows[4]);
			$radio_counter = 0;
			foreach ($value_array as $myvalue) {
				$radio_values .= "document.form".$form_id."[\"field".$field_id."\"][".$radio_counter."].checked == false && ";
				$radio_counter++;
			}
			if ($radio_values <> "") # remove final " && "
				$radio_values = substr($radio_values,0,strlen($radio_values)-4);
			echo (($counter > 1)?"else ":"")."if (".$radio_values.") {
			  alert(\"Please complete ".$field_label.".\");
			  return false;
			}\n";
		}
	}
	echo "else\n";
}
echo "	return true;\n"; # default, return true
echo "}\n"; # end the function

}
?>
//-->
</script>
</head>
<body>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="bg-container">&nbsp;</td>
    <td><div id="container">
      <div id="header">
        <div id="nav">
      <?php include("menu.php"); ?>
        </div>
      </div>
      <!-- End #Nav -->
      <div id="content">
        <div id="subheader"><img src="<?= $site_baseurl; ?>images/logo.png" alt="Playas Escondidas" name="logo" id="logo" /><img src="<?= $site_baseurl; ?>images/subheader-contact.jpg" alt="rotating images" /></div>
        <div id="contentMain">
          <p>
            <?
echo "<h1>".$form_name."</h1>\n";

if (!is_numeric($form_id)) { # no form selected so list all forms
	$rs = mysql_query("SELECT form_id, form_name, form_custom FROM forms ORDER BY form_name");
	while ($rows = mysql_fetch_array($rs)) {
		if ($rows[2] <> "")
			$form_link = $rows[2];
		elseif ($enable_urlrewriting == "y")
			$form_link = $site_baseurl."forms/".cleanUpFormNameForURL($rows[1])."/";
		else
			$form_link = $site_baseurl."form.php?f=".cleanUpFormNameForURL($rows[1]);
		echo "<p><a href=\"".$form_link."\">".$rows[1]."</a></p>\n";
	}
}
else {

if ($action == "submit") {

	$var_array = array();
	$varcounter = 0;
	foreach ($_POST as $key=>$value) {
		${$key} = $value; # grab all variables from form
		$var_array[$varcounter] = $key;
		$varcounter++;
	}
	$time_submitted = date("Y-m-d H:i:s");
	
	mysql_query("INSERT INTO form_responses (form_id, time_submitted) VALUES ('".$form_id."','".$time_submitted."')");
	$response_id = mysql_insert_id();

	$form_contents = "";
	$send_message = true; # we'll change this to false if the form includes spam checking that fails
	$sql = "SELECT field_id, field_type, field_label, field_required, field_validation
	 FROM form_fields WHERE form_id=".$form_id." ORDER BY field_order";
	$rs = mysql_query($sql);
	while ($rows = mysql_fetch_array($rs)) {
		$field_id = $rows[0];
		$field_required = $rows[3];
		$field_validation = $rows[4];
		$field_value = "";
		if ($rows[1] == "c") {
			if ($_REQUEST['field'.$field_id] <> "") 
				$field_value = implode(", ",$_REQUEST['field'.$field_id]); 
		}
		else
			$field_value = $_REQUEST['field'.$field_id];
		if ($field_required == "y" && ($field_validation == "7" || $field_validation == "8") && $field_value <> $field_validation) {
			$send_message = false; 
		}
		else {
			$sql = "INSERT INTO form_response_fields (response_id, field_id, responsefield_value)
			 VALUES (".$response_id.", ".$field_id.", '".$field_value."')";
			mysql_query($sql);
			$form_contents .= $rows[2]." ".$field_value."\n";
		}
	}
	
	if ($field1a2 <> "") { # this is a hidden field so a human shouldn't type anything in the text box
		$send_message = false;
	}
	
	if ($send_message == false) { # spam checking failed, so delete this response from the database
		mysql_query("DELETE FROM form_responses WHERE response_id=".$response_id);
		mysql_query("DELETE FROM form_response_fields WHERE response_id=".$response_id);
	}
	else {
	
	# compose message
	$email_subject = $website_name." Form Submission - ".$form_name;
	$email_body = "The following information was submitted from the web site form:

".$form_contents."

Submitted: ".date("F j, Y @ g:i A",strtotime($time_submitted))."
";

	$rs = mysql_query("SELECT recipient_email FROM form_recipients WHERE form_id=".$form_id);
	if (mysql_num_rows($rs) > 0) {
		require_once("class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->IsSMTP(); # telling the class to use SMTP
		$mail->Host = "$smtpserver"; # SMTP server
		$num_to_send = 0;
		while ($rows = mysql_fetch_array($rs)) {
			if (checkEmail($rows[0])) {
				$mail->AddAddress($rows[0]);
				$num_to_send++;
			}
			if ($num_to_send == 1) {
				$mail->FromName = $website_name;
				$mail->From = $rows[0];
			}
		}
		$mail->Subject = "$email_subject";
		$mail->Body = "$email_body";
		$mail->WordWrap = 50;
#		$mail->IsHTML(true);
		if ($num_to_send > 0) {
			if (!$mail->Send()) 
				echo "Message was not sent<br/>Mailer Error: ".$mail->ErrorInfo."<br>";
		}
	}
	} # / $send_message == false

	echo str_replace("../../",$site_baseurl,$form_message);

}
else {
	
	echo (($form_side <> "")?"<div style=\"float: right;\">".str_replace("../../",$site_baseurl,$form_side)."</div>":"").str_replace("../../",$site_baseurl,$form_intro)."
		<form action=\"".$site_baseurl.(($enable_urlrewriting == "y")?"forms/":"form.php")."\" method=\"post\" name=\"form".$form_id."\" onSubmit=\"return isReady(form".$form_id.");\">
		<input type=\"hidden\" name=\"action\" value=\"submit\" />
		<input type=\"hidden\" name=\"f\" value=\"".$form_idname."\" />
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"2\">\n";
	# get form fields
	$sql = "SELECT field_id, field_type, field_label, field_required, field_width, field_height, field_default_value,
	 field_description, field_values, field_orientation FROM form_fields WHERE form_id=".$form_id." ORDER BY field_order";
	$rs = mysql_query($sql);
	while ($rows = mysql_fetch_array($rs)) {
		switch ($rows[1]) {
			case "a" :
				$displayvalue = "<textarea name=\"field".$rows[0]."\" class=\"formfield\" style=\"width: ".$rows[4]."px; height: ".$rows[5]."px;\" />".$rows[6]."</textarea>";
				break;
			case "t" :
				$displayvalue = "<input name=\"field".$rows[0]."\" type=\"text\" class=\"formfield\" value=\"".$rows[6]."\" style=\"width: ".$rows[4]."px;\" />";
				break;
			case "d" :
				$displayvalue = "<select name=\"field".$rows[0]."\" size=\"1\" class=\"formfield\">
				 <option value=\"\"></option>\n";
				$value_array = split("\n",$rows[8]);
				foreach ($value_array as $myvalue) {
					$selected = ((substr($myvalue,0,1) == "*")?true:false);
					$myvalue = trim((substr($myvalue,0,1) == "*")?substr($myvalue,1,strlen($myvalue)-1):$myvalue);
					$displayvalue .= "<option value=\"".$myvalue."\"".(($selected == true)?" selected=\"selected\"":"").">".$myvalue."</option>\n";
				}
				$displayvalue .= "</select>\n";
				break;
			case "c" : # check boxes
				$displayvalue = "";
				$value_array = split("\n",$rows[8]);
				$field_orientation = $rows[9];
				$items_per_column = ceil(count($value_array)/$field_orientation);
				if ($field_orientation > 1)
					$displayvalue .= "<div style=\"float: left; padding-right: 20px;\">";
				$ccount = 0;
				foreach ($value_array as $myvalue) {
					if ($ccount % $items_per_column == 0 && $ccount > 0 && $field_orientation > 1)
						$displayvalue .= "</div><div style=\"float: left; padding-right: 20px;\">";
					$selected = ((substr($myvalue,0,1) == "*")?true:false);
					$myvalue = trim((substr($myvalue,0,1) == "*")?substr($myvalue,1,strlen($myvalue)-1):$myvalue);
					$displayvalue .= "<input type=\"checkbox\" name=\"field".$rows[0]."[]\" value=\"".$myvalue."\"".(($selected == true)?" checked=\"checked\"":"")."> ".$myvalue."<br />\n";
					$ccount++;
				}
				if ($field_orientation > 1)
					$displayvalue .= "</div>";
				break;
			case "r" : # radio buttons
				$displayvalue = "";
				$value_array = split("\n",$rows[8]);
				$field_orientation = $rows[9];
				$items_per_column = ceil(count($value_array)/$field_orientation);
				if ($field_orientation > 1)
					$displayvalue .= "<div style=\"float: left; padding-right: 20px;\">";
				$ccount = 0;
				foreach ($value_array as $myvalue) {
					if ($ccount % $items_per_column == 0 && $ccount > 0 && $field_orientation > 1)
						$displayvalue .= "</div><div style=\"float: left; padding-right: 20px;\">";
					$selected = ((substr($myvalue,0,1) == "*")?true:false);
					$myvalue = trim((substr($myvalue,0,1) == "*")?substr($myvalue,1,strlen($myvalue)-1):$myvalue);
					$displayvalue .= "<input type=\"radio\" name=\"field".$rows[0]."\" value=\"".$myvalue."\"".(($selected == true)?" checked=\"checked\"":"")."> ".$myvalue."<br />\n";
					$ccount++;
				}
				if ($field_orientation > 1)
					$displayvalue .= "</div>";
				break;
			case "h" : # text heading
				$rows[2] = "<h2>".$rows[2]."</h2>";
				$displayvalue = "" ; #$rows[2];
				break;
			case "p" : # text label
				$displayvalue = $rows[7];
				$rows[7] = "";
				break;
		}
		echo "<tr>
		 <td".(($rows[1] == "h")?" colspan=\"2\"":"").">".(($rows[3] == "y")?"*":"").$rows[2]."</td>
		 ".(($rows[1] <> "h")?"<td>".$displayvalue.(($rows[7] <> "")?" <em>".$rows[7]."</em>":"")."</td>":"")."
		 </tr>\n";
	}
	# add hidden field for spam protection
	echo "<tr style=\"display: none;\">
	 <td>*Fax:</td>
	 <td><input name=\"field1a2\" type=\"text\" class=\"formfield\" value=\"\" style=\"width: 50px;\" /></td>
	 </tr>\n";
	# submit/reset button
	echo "<tr>\n<td>&nbsp;</td>
	 <td><input type=\"submit\" value=\"".$form_submit_label."\" class=\"button\" />".(($form_reset_label <> "")?" <input type=\"reset\" value=\"".$form_reset_label."\" class=\"button\" />":"")."</td>
	 </tr>\n</table>\n</form>\n";

}

} # end is_numeric(form_id)
?>
          </p>
        </div>
        <div id="divider"></div>
        <div id="contentSub">
		<?php include("contentsub.php"); ?>
        </div>
      </div>
      <!-- end #content -->
      <div id="footer">
      <?php include("footer.php"); ?>
      </div>
    </div></td>
    <td class="bg-containerEmpty">&nbsp;</td>
  </tr>
</table>
<!-- end #container -->
<!-- end #wrapper -->
</body>
</html>