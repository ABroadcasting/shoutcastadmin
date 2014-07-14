<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./content.php
//	

// If database.php does not exist then print error
if (!include("database.php")) die("database.php could not be loaded!");
if ($db_host == "" || !isset($db_host)) die("please reinstall this panel");
//MySQL Verbindung wird getestet
$connection = mysql_connect($db_host, $db_username, $db_password) or die ("database could not be connected");
$db = mysql_select_db($database) or die ("database could not be selected");
// Logincheck
session_start();
// Language File check on DB
$captcha_sql = mysql_query("SELECT language FROM settings WHERE id='0'");
$language_setting = mysql_result($captcha_sql,0);
// Check if Language-file exists and include, else load English
if (file_exists("./pages/messages/".$language_setting.".php")) {
	$language_setting = $language_setting;
}
else {
	$errors[] = "<h2>The language file could not be found, English is the default language!</h2>";
	$language_setting = "english";
}	
include "./pages/messages/".$language_setting.".php";
// Get variable for include
if (!isset($_GET['include'])) {	
	$include_php = "main";
}
else {	
	$include_php = $_GET['include'];	
}
// Thanks to CWH Underground, should be good now
// That is the fix for http://www.milw0rm.com/exploits/5813
$include_php=ereg_replace("/","", $include_php);
$include_php=strip_tags($include_php);
// Logout of Panel
if ($_GET['login'] == "logout") {
	$loggedin = FALSE;
	session_destroy();
	header('Location: index.php?login=logout');
}	
$loggedin = FALSE;
if (isset($_SESSION['username']) && isset($_SESSION['user_password']) || isset($_POST['username']) && isset($_POST['user_password'])) {
	if (isset($_POST['login_submit']))
	{
		$loginun = $_POST['username'];
		$loginpw = $_POST['user_password'];
	}else{
		$loginun = $_SESSION['username'];
		$loginpw = $_SESSION['user_password'];
	}
	if (isset($_POST['login_submit'])) {
		$captcha_sql = mysql_query("SELECT login_captcha FROM settings WHERE id='0'");
		if (mysql_result($captcha_sql,0)== "1") {
			if ($_POST['captcha_field']!=$_SESSION['captcha_shoutcastadmin']) {
				if ($include_php !== "main" || $include_php !== "") {
					header('Location: index.php?login=captcha&redir='.$include_php.'');
					die();
				}
				else {
					header('Location: index.php?login=captcha');
					die();
				}
			}
		}
	}
	$hash = md5($loginun.$loginpw);
	$selectuser = mysql_query("SELECT * FROM users WHERE md5_hash='$hash'");
	if (mysql_num_rows($selectuser)==1)
	{
		$_SESSION['username'] = $loginun;
		$_SESSION['user_password'] = $loginpw;
		$userdata = mysql_fetch_array($selectuser);
		$loginun = $userdata['username'];
		$user_level = $userdata['user_level'];
		$user_id = $userdata['id'];
		$loggedin = TRUE;
		if (isset($_POST['login_submit'])) {
			$correc[] = "<h2>".$messages["15"]."</h2>";
		}
	}else{
		session_destroy();
		$loggedin = FALSE;
	}
}
if (isset($loggedin) && $loggedin==TRUE) {
}
else {
	if ($include_php !== "main" || $include_php !== "") {
		header('Location: index.php?login=data&redir='.$include_php.'');
	}
	else {
		header('Location: index.php?login=data');
	}
}
// additional message insert by $_GET
if (!isset($_GET['message_ext']) or !isset($_GET['message_lang'])) { }
else {
	if ($_GET['message_ext']=="1") {
		$errors[] = $messages[$_GET['message_lang']];
	}
	if ($_GET['message_ext']=="2") {
		$notifi[] = $messages[$_GET['message_lang']];
	}
	if ($_GET['message_ext']=="3") {
		$correc[] = $messages[$_GET['message_lang']];
	}
}	
// MySQL connection
$connection = mysql_connect($db_host, $db_username, $db_password) or die ($messages["g1"]);
$db = mysql_select_db($database) or die ($messages["g2"]);
// ?install_cancel=1 deactivates installcheck
if (file_exists("./install/install.php")) {
	$errors[] = "<h2>".$messages["16"]."</h2>";
}
// if including file doesn't exist then load main page
if (file_exists("./pages/".$include_php."_bottom.php")) {
	$include_php = $include_php;
}
else {
	if (file_exists("./pages/main_bottom.php")) {
		$errors[] = $messages["g3"];
		$include_php = "main";
	}
	else {
		$errors[] = $messages["g3"];
		$include_php = "_no";
	}
}
if (($include_php == "admserver") || ($include_php == "admradio") || ($include_php == "admuser")) {
	if ($user_level!="Super Administrator") {
		$include_php = "main";
		$errors[] = "<h2>".$messages["17"]."</h2>";
	}
}
// check messages on headlines
$newsq = mysql_query("SELECT * FROM headlines order by id DESC LIMIT 20") or die($messages["g4"]);
$newsq_quant = mysql_num_rows($newsq);
if ($user_level=="Super Administrator" && $_GET['action'] == "remove" && isset($_GET['delmessid'])) {
	if (mysql_query(" DELETE FROM notices WHERE id='".$_GET['delmessid']."' ")) {
		$correc[] = "<h2>".$messages["18"]."</h2>";
	}
	else {
		$errors[] = "<h2>".$messages["19"]."</h2>";
	}
}
// Playlist in XML for output
if ($_GET['playlist'] == "left") {
	if (isset($_GET['portbase'])) {
		$port=$_GET['portbase'];
		$selectowner = mysql_query("SELECT * FROM servers WHERE portbase='".$port."' AND owner='".$loginun."'");
		if (mysql_num_rows($selectowner)==1) {
			header("Content-type:text/xml"); print("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?>");
			$listing_start = 1;
			$listing_end = 10000;
			$dirlisting = @scandir("".dirname(__FILE__)."/pages/uploads/".$port."") or die ();
			$dirlistingsearch  = array('&', '<', '>', '"', "'");
			$dirlistingreplace = array('&amp;', '&lt;', '&gt;', '&quot;', '&apos;');
			if(!isset($dirlisting[$listing_start])) die();
				echo "<tree id=\"0\">";
			for($i=$listing_start;$i<=$listing_end;$i++) {
				if (($dirlisting[$i]!=".") and ($dirlisting[$i]!="..") and ($dirlisting[$i]!="")) {
					echo "<item id=\"".utf8_decode("".dirname(__FILE__)."/pages/uploads/".$port."/".str_replace($dirlistingsearch, $dirlistingreplace, $dirlisting[$i])."")."\" text=\"".utf8_decode("".str_replace($dirlistingsearch, $dirlistingreplace, $dirlisting[$i])."")."\" />";
				}
			}
			echo "</tree>";
			die ();
		}
	}
}
elseif (($_GET['playlist'] == "right") && (isset($_GET['listname']))) {
	if (isset($_GET['portbase'])) {
		$port=$_GET['portbase'];
		$selectowner = mysql_query("SELECT * FROM servers WHERE portbase='".$port."' AND owner='".$loginun."'");
		if (mysql_num_rows($selectowner)==1) {
			header("Content-type:text/xml"); print("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?>");
			if (base64_decode($_GET['listname']) !== "new playlist.lst") {
				$filehandle = fopen("".dirname(__FILE__)."/temp/".$port."/playlist/".base64_decode($_GET['listname'])."", "r");
				$contents = fread($filehandle, filesize("".dirname(__FILE__)."/temp/".$port."/playlist/".base64_decode($_GET['listname']).""));
				$entrys = explode("\n",$contents);
				$dirlistingsearch  = array('&', '<', '>', '"', "'");
				$dirlistingreplace = array('&amp;', '&lt;', '&gt;', '&quot;', '&apos;');
			}
			echo ("<tree id='0'>");
			if (base64_decode($_GET['listname']) !== "new playlist.lst") {
				$inta = 0;
				foreach($entrys as $entry) {
					$inta ++;
					$entry1 = str_replace("".dirname(__FILE__)."/pages/uploads/".$port."/", "", $entry);
					if($entry1 != "")
						echo ("<item child='0' id='".utf8_decode("".str_replace($dirlistingsearch, $dirlistingreplace, $entry1)."")."' text='".utf8_decode("".str_replace($dirlistingsearch, $dirlistingreplace, $entry1)."")."'></item>");
					}
				fclose($filehandle);
			}
			if (base64_decode($_GET['listname']) == "new playlist.lst") {
				echo ("<item child='0' id='demo' text='Delete Me First!'></item>");
			}
			echo("</tree>");
			die ();
		}
	}
}
// include functions of php file
if ((file_exists("./pages/".$include_php."_top.php")) && ($include_php!="_no")) {
	@include("./pages/".$include_php."_top.php");
}
// get all settings of db
$settingsq = mysql_query("SELECT * FROM settings WHERE id='0'") or die($messages["g5"]);
foreach(mysql_fetch_array($settingsq) as $key => $pref) {
	if (!is_numeric($key)) {
		$setting[$key] = stripslashes($pref);
	}
}
// update check
if ($setting['update_check']=="1"){
	include "./pages/update.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>

	<title><?PHP echo $setting['title']." - ". $setting['slogan'];?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./css/framework.css" />
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<script type="text/javascript" src="./js/jquery.min.js"></script>
	<script type="text/javascript" src="./js/jquery.nyroModal-1.6.2.pack.js"></script>
	<?php
	if ($include_php == "main") {
	?>
	<script type="text/javascript">
	$(function() {		
		function preloadImg(image) {
			var img = new Image();
			img.src = image;
		}
		preloadImg('images/modalwin/ajaxLoader.gif');
	});	
	</script>
	<?php }?>
	<?php
	if ($include_php == "upload") {
	?>
	<script language="javascript">
	function setVisibility(id, visibility) {
		document.getElementById(id).style.display = visibility;
	}
	</script>
	<?php }?>
	<?php
	if ($include_php == "playlist") {
	?>
	<script language="javascript">
	function clearPlaylist()
	{
		var itemId=this.tree2.rootId;
      	var temp=this.tree2._globalIdStorageFind(itemId);
      	this.tree2.deleteChildItems(itemId);
	}
	function setValue()
	{
		var i = 0;
		var j = 0;
		var n = 0;
		arvArray = new Array();	
		arvArray = getChilds(this.tree2.htmlNode, arvArray, "<?php echo $soundfiles."/";?>")
		var arv = arvArray.toString();
		document.treeform.arv.value = escape(arv);
	}
	function getChilds(Childs, arr, label) {
		var i = 0;
		for(i = 0; i < Childs.childsCount; i++) {
			if(Childs.childNodes[i].childsCount == 0) {
				if(Childs.childNodes[i].label[0] != "/") {
					arr.push(label+Childs.childNodes[i].label);
				}
				else arr.push(Childs.childNodes[i].label);
			}
			else {
				arr = getChilds(Childs.childNodes[i], arr, label+Childs.childNodes[i].label+"/")
			}
		}
		return arr;
	}
    </script>
    <?php
	}
	?>

</head>
<body>
<div id="container">
	<div id="header_top">
		<div class="header logo">
			<a href="content.php" title=""><img src="./images/logo.png" alt="" /></a>
		</div>
		<div class="header top_nav">
			<span class="session"><?php echo $messages["20"];?> <?PHP echo ($loginun);?> (<a href="content.php?login=logout" title="Sign out"><?php echo $messages["21"];?></a>)</span>
		</div>
	</div>
	<div id="sidebar">
		<div id="navigation">
			<div class="sidenav">
				<div class="nav_info">
					<span><?php echo $messages["22"];?> <span class="nav_info_username"><?PHP echo ($loginun);?></span>,</span><br/>
					<?php
					if ($user_level=="Super Administrator")	{
						$noticesq = mysql_query("SELECT * FROM notices");
						if (mysql_num_rows($noticesq)==0) {
							echo "<span class=\"nav_info_messages\">".$messages["23"]."</span>";
						}
						else {
							$noticesqquant = mysql_num_rows($noticesq);
							if ($noticesqquant == 1) {
								echo "<span class=\"nav_info_messages\">".$messages["24"]." <b>".$noticesqquant."</b> ".$messages["25"]."</span>";
							}
							else {
								echo "<span class=\"nav_info_messages\">".$messages["26"]." <b>".$noticesqquant."</b> ".$messages["27"]." </span>";
							}
						}
					}
					else {
						echo "<span class=\"nav_info_messages\">Shoutcast Admin Panel 3 - ".$messages["28"]."</span>";
					}
					?>
				</div>
				<div class="navhead_blank">
					<span><?php echo $messages["29"];?></span>
					<span><?php echo $messages["30"];?></span>
				</div>
				<div class="subnav_child">
					<ul class="submenu">
						<li><a href="content.php?include=contact" title=""><?php echo $messages["31"];?></a></li>
						<li><a href="content.php?include=public" title=""><?php echo $messages["32"];?></a></li>
						<li><a href="content.php?include=account" title=""><?php echo $messages["33"];?></a></li>
						<li><a href="content.php?include=server" title=""><?php echo $messages["34"];?></a></li>
					</ul>
				</div>
				<?php if ($setting['os']=="linux") { ?>
				<div class="navhead">
					<span><?php echo $messages["35"];?></span>
					<span><?php echo $messages["36"];?></span>
				</div>
				<div class="subnav">
					<ul class="submenu">
						<li><a href="content.php?include=music" title=""><?php echo $messages["37"];?></a></li>
						<li><a href="content.php?include=autodj" title=""><?php echo $messages["38"];?></a></li>
					</ul>
				</div>
				<?php } ?>
				<?php if ($user_level=="Super Administrator")	{ ?>
				<div class="navhead">
					<span><?php echo $messages["39"];?></span>
					<span><?php echo $messages["40"];?></span>
				</div>
				<div class="subnav">
					<ul class="submenu">
						<li><a href="content.php?include=admserver" title=""><?php echo $messages["41"];?></a></li>
						<li><a href="content.php?include=admradio" title=""><?php echo $messages["42"];?></a></li>
						<li><a href="content.php?include=admuser" title=""><?php echo $messages["43"];?></a></li>
					</ul>
				</div>
				<?php } ?>
				<div class="navhead">
					<span><?php echo $messages["44"];?></span>
					<span><?php echo $messages["45"];?></span>
				</div>
				<div class="subnav">
					<table cellspacing="0" cellpadding="0" class="ip_table">
						<tbody>
							<tr>
								<td class="ip_table"><?php echo $messages["46"];?></td>
								<td class="ip_table_under"><?PHP echo ($_SERVER['REMOTE_ADDR']);?></td>
							</tr>
							<tr>
								<td class="ip_table"><?php echo $messages["47"];?></td>
								<td class="ip_table_under"><?PHP echo ($_SERVER['SERVER_ADDR']);?></td>
							</tr>
							<tr>
								<td class="ip_table"><?php echo $messages["48"];?></td>
								<td class="ip_table_under">3.1-public.beta.1</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div id="primary">
		<?PHP
		if(count($errors) > 0) {
			foreach($errors as $errors_cont)
				$errors_list.="<div class=\"error\">".$errors_cont."</div>";
			echo ($errors_list);
		}
		if(count($notifi) > 0) {
			foreach($notifi as $notifi_cont)
				$notifi_list.="<div class=\"notifi\">".$notifi_cont."</div>";
			echo ($notifi_list);
		}
		if(count($correc) > 0) {
			foreach($correc as $correc_cont)
				$correc_list.="<div class=\"correct\">".$correc_cont."</div>";
			echo ($correc_list);
		}
		include("./pages/".$include_php."_bottom.php");
		?>
	</div>
	<div class="clear"></div>
	<div id="footer">
		<p>Shoutcast Admin Panel | djcrackhome | <a href="http://www.shoutcastadmin.info">http://www.shoutcastadmin.info</a> | <a href="http://www.nagualmedia.de/">Design by Zephon</a> | <?php echo $messages["564"];?></p>
	</div>
</div>
</body>
</html>