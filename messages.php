<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./messages.php
//	

// If database.php is not created and filled move to install
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
// Get variable for messagerequest
if (!isset($_GET['id'])) {	
	$id = "error";
}
else {	
	$id = $_GET['id'];	
}
// Thanks to CWH Underground, should be good now
// That is the fix for http://www.milw0rm.com/exploits/5813
$id=ereg_replace("/","", $id);
$id=strip_tags($id);
if ($id == "error") {
	echo "<h2>".$messages["50"]."</h2>";
}
else {
	$noticeq = mysql_query("SELECT * FROM notices WHERE id='".$id."'");
	$notice = mysql_fetch_array($noticeq);
	echo "<h2>".$messages["51"]." ".$notice['username']."<hr /></h2>
		<h3>".$messages["52"].": <i>".$notice['reason']."</i></h3>
		<h3>".$messages["53"].": ".nl2br($notice['message'])."</h3>
		<p>[".$notice['ip']."]</p>";
}
?>
