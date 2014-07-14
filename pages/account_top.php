<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/account_top.php
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

if (isset($_POST['submit'])) {
	if ($_POST['u_cuser_password'] !== "") {
		if (strtolower($_POST['u_user_password']) == strtolower($_POST['u_cuser_password'])) {
			$update_ne = "1";
			$loginpw = $_POST['u_user_password'];
			$u_md5_hash = md5(strtolower($loginun.$loginpw));
			if (mysql_query("UPDATE users SET user_password='".$loginpw."',md5_hash='".md5(strtolower($loginun.$loginpw))."',  WHERE id='".$userdata['id']."' ") )
				$_SESSION['user_password'] = $_POST['u_user_password'];
		}
		else {
			$notifi[] = "<h2>".$messages["82"]."</h2>";
		}
	}
	if ($_SESSION['user_password'] !== $_POST['u_user_password']) {
		$notifi[] = "<h2>".$messages["83"]."</h2>";
	}
	$fields = "";
	$values = "";
	if (mysql_query("UPDATE users SET user_email='".$_POST["u_user_email"]."',contact_number='".$_POST["u_contact_number"]."',mobile_number='".$_POST["u_mobile_number"]."',name='".$_POST["u_name"]."',surname='".$_POST["u_surname"]."',age='".$_POST["u_age"]."' WHERE id='".$userdata['id']."' ") ) {
		$correc[] = "<h2>".$messages["84"]."</h2>";
	}
	else{
		$errors[] = "<h2>".$messages["85"]."</h2>";
	}
}
?>