<?
require("inc/config.php");
require("inc/config_pages.php");
if ($pages_enable_bannerads == "y")
	require("inc/config_bannerads.php");
if ($pages_enable_adspace == "y")
	require("inc/config_adspace.php");
require("inc/functions.php");
require("inc/page_functions.php");
$page_id = 2;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?= $page_head_title; ?></title>
<?= $meta_content; ?>
<link href="<?= $site_baseurl; ?>inc/pages.css" rel="stylesheet" type="text/css" />
<link href="<?= $site_baseurl; ?>css/styles.css" rel="stylesheet" type="text/css" />
<link href="<?= $site_baseurl; ?>css/menu.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<?= $site_baseurl; ?>scripts/pngfix.js"></script>
<![endif]-->
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
<script src="<?= $site_baseurl; ?>scripts/AC_RunActiveContent.js" type="text/javascript"></script>
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
        <div id="flash"><img id="logo" src="<?= $site_baseurl; ?>images/logo.png" alt="Playas Escondidas" />
          <script type="text/javascript">
		AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0','width','836','height','324','src','<?= $site_baseurl; ?>rotatingphotos?site_baseurl=<?= $site_baseurl; ?>','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','wmode','transparent','movie','<?= $site_baseurl; ?>rotatingphotos?site_baseurl=<?= $site_baseurl; ?>' ); //end AC code
		</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="836" height="324">
			<param name="movie" value="<?= $site_baseurl; ?>rotatingphotos.swf?site_baseurl=<?= $site_baseurl; ?>" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent" />
			<embed src="<?= $site_baseurl; ?>rotatingphotos.swf?site_baseurl=<?= $site_baseurl; ?>" width="836" height="324" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>
		  </object>
		</noscript></div>
        <div id="contentMain">
          <p><img src="images/heading-home.jpg" alt="Costa Rica es Pura Vida" width="301" height="34" class="heading" /></p>
          <p class="lineheight">The Hidden Beaches of Nosara is located immediately north of the Nosara river delta in an area locally referred to as Bocas de Nosara. </p>
          <p class="lineheight">This upscale development has the Nosara River to the south, the Montana River dividing the two costal farms of 81.7 hectares and 48.5 hectares.  These properties front on the Estero Escondio and the Ostional Widlife Refuge, the entire length of the property.  This property provides access to Playa Nosara, a 2 mile long beach, via system of boardwalks. Previously the only access to this beach was gained by walking north from the mouth of the Nosara River or south from Ostional.</p>
          <p> Take a closer look at Playas Escondidas with our interactive map.</p>
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