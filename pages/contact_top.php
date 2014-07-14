<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/contact_top.php
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
	if (isset($_POST['email'])) {
		if (!strstr($_POST['email'],"@")) {
			$formerror = "email";
		}
		else {
			if (empty($_POST['reason'])) {
				$formerror = "reason";
			}
			else {
				if (empty($_POST['message'])) {
					$formerror = "message";
				}
				else {
					if (function_exists('htmlspecialchars_decode'))
						$messagesql = htmlspecialchars_decode($_POST['message'], ENT_QUOTES);
					if (function_exists('htmlspecialchars_decode'))
						$reasonsql = htmlspecialchars_decode($_POST['reason'], ENT_QUOTES);
					if (mysql_query("INSERT INTO notices (username,reason,message,ip) VALUES('".$loginun."','".$reasonsql."','".$messagesql."','".$_SERVER['REMOTE_ADDR']."')")) {
						$correc[] = "<h2>".$messages["355"]."</h2>";
					}
					else {
						$errors[] = "<h2>".$messages["356"]."</h2>";
					}
				}
			}
		}
	}
	else {
		$errors[] = "<h2>".$messages["357"]."</h2>";
		$formerror = "email";
	}
}