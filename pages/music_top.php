<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/music_top.php
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

if ($_GET['error']=="port") {
	$errors[] = "<h2>".$messages["385"]."</h2>";
}
if ($_GET['error']=="access") {
	$errors[] = "<h2>".$messages["386"]."</h2>";
}
if ($_GET['error']=="dir") {
	$errors[] = "<h2>".$messages["387"]."</h2>";
}
if ($_GET['error']=="space") {
	$errors[] = "<h2>".$messages["388"]."</h2>";
}
if ($_GET['error']=="dirlisting") {
	$errors[] = "<h2>".$messages["389"]."</h2>";
}
if ($_GET['error']=="sc_trans_access") {
	$correc[] = "<h2>".$messages["390"]."</h2>";
}
?>