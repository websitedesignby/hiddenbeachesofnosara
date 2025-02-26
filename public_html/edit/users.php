<?
$this_page_id = 0;
require("../inc/config.php");
require("../inc/functions.php");
require("secure.php");
$pageurl = "users.php";

$type = $_REQUEST['type'];

# is the Total Control Pages tool active?
$tcp_active = "n";
$rs = mysql_query("SELECT page_status FROM wma_pages WHERE page_id=28");
if (mysql_num_rows($rs) > 0) {
	$row = mysql_fetch_row($rs);
	$tcp_active = $row[0];
}

if ($_REQUEST['action'] == "delete") {
	$user_id = $_REQUEST['user_id'];
	if (is_numeric($user_id)) {
		# if the user has added files to the File Manager, they can't be deleted
		mysql_query("UPDATE wma_users SET user_status='d' WHERE user_id=".$user_id);
		mysql_query("DELETE FROM wma_permissions WHERE user_id=".$user_id);
	}
	header("location: users.php?type=".$type);
}
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

	<p class="addbutton"><a href="users_add.php?type=<?= $type; ?>">Add A User</a></p>
	<h3>List Users</h3>
	<p class="notes"><?= (($type == "f")?"The web site user accounts are used to log into password-protected Total Control Pages.":"These user accounts are used to log into this Toolbox."); ?></p>
	
	<?
	$sql = "SELECT user_id, user_first_name, user_last_name, user_username, user_type, user_status
	 FROM wma_users WHERE ".(($type == "f")?"user_type='f'":"(user_type='m' OR user_type='r')")." 
	 AND user_status <> 'd' ORDER BY user_last_name"; # do not display administrator account for editing
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) == 0)
		echo "<p>There are no users in the database.</p>\n";
	else {
		?>
	<table border="0" cellspacing="0" cellpadding="0" class="record_table">
      <tr>
        <th>&nbsp;</th>
        <th>Name</th>
        <th>Username</th>
        <th>User Type</th>
      </tr>
	  <? while ($rows = mysql_fetch_array($rs)) { ?>
	  <tr onMouseOver="this.style.background='<?= $trOverColor; ?>';" onMouseOut="this.style.background='<?= $trOffColor; ?>';">
		<td class="actions"><a href="users_edit.php?user_id=<?= $rows[0]; ?>&type=<?= $type; ?>">edit</a> | <a href="<?= $pageurl; ?>?action=delete&user_id=<?= $rows[0]; ?>&type=<?= $type; ?>" onclick="return confirm('You are about to permanently delete this user. Are you sure you want to delete?')">delete</a> </td>
		<td><?= $rows[2].(($rows[1] <> "" && $rows[2] <> "")?",":"")." ".$rows[1]; ?>&nbsp;</td>
		<td><?= $rows[3]; ?>&nbsp;</td>
		<td><?= (($rows[4] == "m")?"manager":"&nbsp;"); ?></td>
	  </tr>
	  <? } ?>
    </table>
	<p class="notes">Click the headings to sort records</p>
	<? } ?>

</div>
</div>

<? require("inc/footer.php"); ?>
</body>
</html>
