<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/admserver_top.php
//	

if (!eregi("content.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$settingsq = mysql_query("SELECT * FROM settings WHERE id='0'") or die($messages["g5"]);
foreach(mysql_fetch_array($settingsq) as $key => $pref) {
	if (!is_numeric($key)) {
		$setting[$key] = stripslashes($pref);
	}
}

if ($user_level == "Super Administrator") {
	if ($_GET['action'] == "savesettings") {
		foreach($_POST as $key => $pref) {
			$_POST[$key] = addslashes($pref);
		}
		if (mysql_query("UPDATE settings SET host_add='".$_POST['host_add']."',dir_to_cpanel='".addslashes($_POST['dir_to_cpanel'])."',title='".$_POST['title']."', slogan='".$_POST['slogan']."', scs_config='".$_POST['scs_config']."', adj_config='".$_POST['adj_config']."', php_mp3='".$_POST['php_mp3']."', php_exe='".$_POST['php_exe']."', display_limit='".$_POST['display_limit']."', update_check='".$_POST['update_check']."', ssh_user='".base64_encode($_POST['ssh_user'])."', language='".$_POST['language']."', ssh_pass='".base64_encode($_POST['ssh_pass'])."', ssh_port='".$_POST['ssh_port']."', login_captcha='".$_POST['login_captcha']."'")) {
			$htaccess_cont = @fopen(".htaccess","w+");
			@fputs($htaccess_cont,"php_value upload_max_filesize ".$_POST['php_mp3']."M\r\nphp_value post_max_size ".$_POST['php_mp3']."M\r\nphp_value max_execution_time ".$_POST['php_exe']."\r\nphp_value max_input_time ".$_POST['php_exe']."");
			@fclose($htaccess_cont);
			$correc[] = "<h2>".$messages["226"]."</h2>";
		}
		else {
			$errors[] = "<h2>".$messages["227"]."</h2>";
		}
	}
}
?>