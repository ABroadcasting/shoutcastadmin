<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./index.php
//	

session_start();
// save random captcha value in browsersession
$_SESSION['captcha_shoutcastadmin']=rand(10000,99999);
//	include database
if (!include("database.php")) die("database.php could not be loaded!");
if ($db_host == "" || !isset($db_host)) die("please reinstall this panel");
//MySQL Verbindung wird getestet
$connection = mysql_connect($db_host, $db_username, $db_password) or die ("database could not be connected");
$db = mysql_select_db($database) or die ("database could not be selected");
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
//	if an error occured by logging in, check which login and then echo error
if ($_GET['login']=="data") {
	$error[] = "<h2>".$messages["1"]."</h2>";
}
if ($_GET['login']=="captcha") {
	$error[] = "<h2>".$messages["2"]."</h2>";
}
if ($_GET['login']=="logout") {
	$correct[] = "<h2>".$messages["3"]."</h2>";
}
// if user is already logged in, than redirect to content
$loggedin = FALSE;
if (isset($_SESSION['username']) && isset($_SESSION['user_password'])) {
	$loginun = $_SESSION['username'];
	$loginpw = $_SESSION['user_password'];
}
$hash = md5($loginun.$loginpw);
$selectuser = mysql_query("SELECT * FROM users WHERE md5_hash='$hash'");
if (mysql_num_rows($selectuser)==1) {
	$_SESSION['username'] = $loginun;
	$_SESSION['user_password'] = $loginpw;
	$userdata = mysql_fetch_array($selectuser);
	$loginun = $userdata['username'];
	$user_level = $userdata['user_level'];
	$user_id = $userdata['id'];
	$loggedin = TRUE;
}
if (isset($loggedin) && $loggedin==TRUE) {
	header('Location: content.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title>Shoutcast Admin Panel 3 - <?php echo $messages["14"]; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./css/framework.css" />
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<!--[if IE]>
		<style type="text/css">
			input.loginfield {
				padding: 9px 6px 0px 6px !important;
				height: 24px !important;
				width: 161px !important;
			}
			</style>
	<![endif]-->
</head>
<body>

<div id="container">
	<div id="header_top">
		<div class="header logo_login">
			<a href="#" title=""><img src="images/logo.png" alt="" /></a>
		</div>
	</div>
	<div id="primary_login">
		<?PHP
		if(count($error) > 0) {
			foreach($error as $error_cont) $error_list.="<div class=\"error_log\">".$error_cont."</div>";
			echo ($error_list);
		}
		if(count($correct) > 0) {
			foreach($correct as $correct_cont) $correct_list.="<div class=\"correct_log\">".$correct_cont."</div>";
			echo ($correct_list);
		}
		?>
		<div id="content">
			<div class="box">
				<h2><?php echo $messages["4"]; ?></h2>
				<p><?php echo $messages["5"]; ?></p>
				<form method="post" action="content.php<?php if (isset($_GET['redir'])) { echo "?include=".$_GET['redir']; }?>">
					<fieldset>
						<legend><?php echo $messages["6"]; ?></legend>
						<div class="input_field">
							<label for="a"><?php echo $messages["7"]; ?></label>
							<input class="loginfield" name="username" type="text" />
							<span class="field_desc"><?php echo $messages["8"]; ?></span>
						</div>
						<div class="input_field">
							<label for="b"><?php echo $messages["9"]; ?></label>
							<input class="loginfield" name="user_password" type="password" />
							<span class="field_desc"><?php echo $messages["10"]; ?></span>
						</div>
                        <?php
						$captcha_sql = mysql_query("SELECT login_captcha FROM settings WHERE id='0'");
						if (mysql_result($captcha_sql,0)== "1") {
						?>
						<div class="input_field">
							<label for="a"><?php echo $messages["11"]; ?></label>
							<input class="loginfield" name="captcha_field" type="text" maxlength="5" />
							<span class="field_desc"><span class="captchaspan"><img class="field_desc" src="captcha/picture.php"></span></span>
						</div>
                        <?php }?>
						<center>
							<input type="hidden" name="login_submit" />
							<input class="loginsubmit" type="submit" value="<?php echo $messages["12"]; ?>" />
							<input class="loginsubmit" type="reset" value="<?php echo $messages["13"]; ?>" />
						</center>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div id="footer">
		<p>Shoutcast Admin Panel | djcrackhome | <a href="http://www.shoutcastadmin.info">http://www.shoutcastadmin.info</a> | <a href="http://www.nagualmedia.de/">Design by Zephon</a> | <?php echo $messages["564"];?></p>
	</div>
</div>
</body>
</html>