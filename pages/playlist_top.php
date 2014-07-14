<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/playlist_top.php
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

if (!isset($_GET['portbase'])) {
	header('Location: content.php?include=music&error=port');
	die ();
}
else {
	$port=$_GET['portbase'];
}
$selectowner = mysql_query("SELECT * FROM servers WHERE portbase='".$port."' AND owner='".$_SESSION['username']."'");
if (mysql_num_rows($selectowner)==1) {
	$port=$port;
}
else {
	header('Location: content.php?include=music&error=access');
	die ();
}
if (($_GET['indiv'] == "0") && (isset($_GET['listname'])) && ($_GET['delete'] == "1")) {
	$deletefiledecoded = strip_tags(ereg_replace("/","", base64_decode($_GET['listname'])));
	if (file_exists("./temp/".$port."/playlist/".$deletefiledecoded."")) {
		unlink("./temp/".$port."/playlist/".$deletefiledecoded."");
		$correc[] = "<h2>".$messages["407"]."</h2>";
	}
	else {
		$errors[] = "<h2>".$messages["408"]."</h2>";
	}
}
if (($_GET['indiv'] == "0") && (isset($_GET['listname'])) && ($_GET['delete'] == "0")) {
	$deletefiledecoded = base64_decode($_GET['listname']);
	if (!file_exists("./temp/".$port."/playlist/".$deletefiledecoded."")) {
		$playlistfilecreate = fopen("./temp/".$port."/playlist/".$deletefiledecoded."", 'w') or $errors[] = "<h2>".$messages["409"]."</h2>";
		fclose($playlistfilecreate);
		chmod("./temp/".$port."/playlist/".$deletefiledecoded."",0777);
	}
	shell_exec('find '.$setting['dir_to_cpanel'].'pages/uploads/'.$port.'/ -type f -name "*.mp3" > '.$setting['dir_to_cpanel'].'temp/'.$port.'/playlist/"'.$deletefiledecoded.'"');	
	$correc[] = "<h2>".$messages["410"]."</h2>";	
}
if ($_GET['delete'] !== "1") {
	if ((isset($_POST['arv'])) && (isset($_GET['listname']))) {
		if ($_GET['new'] == "1") {
			if ($_POST['playlistformname'] !== "bmV3IHBsYXlsaXN0LmxzdA==") {
				$playlistfilecreatename = strip_tags(ereg_replace("/","", $_POST['playlistformname']));
				if (!file_exists("./temp/".$port."/playlist/".$playlistfilecreatename.".lst")) {
					$playlistfilecreate = fopen("./temp/".$port."/playlist/".$playlistfilecreatename.".lst", 'w') or $errors[] = "<h2>".$messages["411"]."</h2>";
					fclose($playlistfilecreate);
					if (file_exists("./temp/".$port."/playlist/")) {
						if (file_exists("./temp/".$port."/playlist/".$playlistfilecreatename.".lst")) {
							$postvar_form = $_POST['arv'];
							$postvar_form = urldecode($postvar_form);
							$postvar_name = "./temp/".$port."/playlist/".$playlistfilecreatename.".lst";
							$filehandle = fopen($postvar_name, "w+");
							$tok = explode(',',$postvar_form);
							foreach ($tok as $playlistentry) {
								fwrite($filehandle, $setting['dir_to_cpanel']."pages/uploads/".$port."".$playlistentry."\n");
							}
							fclose($filehandle);
							chmod($postvar_name,0777);
							$correc[] = "<h2>".$messages["412"]."</h2>";
						}
						else {
							$errors[] = "<h2>".$messages["413"]."</h2>";
						}
					}
					else {
						$errors[] = "<h2>".$messages["414"]."</h2>";
					}				
				}
				else {
					$errors[] = "<h2>".$messages["415"]."</h2>";
				}
			}
			else {
				$errors[] = "<h2>".$messages["416"]."</h2>";
			}
		}
		else {
			if (file_exists("./temp/".$port."/playlist/")) {
				if (file_exists("./temp/".$port."/playlist/".base64_decode($_GET['listname']))) {
					$postvar_form = $_POST['arv'];
					$postvar_form = urldecode($postvar_form);
					$postvar_name = "./temp/".$port."/playlist/".base64_decode($_GET['listname']);
					$filehandle = fopen($postvar_name, "w+");
					$tok = explode(',',$postvar_form);
					foreach ($tok as $playlistentry) {
						fwrite($filehandle, $setting['dir_to_cpanel']."pages/uploads/".$port."".$playlistentry."\n");
					}
					fclose($filehandle);
					chmod($postvar_name,0777);
					$correc[] = "<h2>".$messages["417"]."</h2>";
				}
				else {
					$errors[] = "<h2>".$messages["418"]."</h2>";
				}
			}
			else {
				$errors[] = "<h2>".$messages["419"]."</h2>";
			}
		}
	}
}
else {
	define('entries_per_page',100);
	if (!isset($_GET['filecount']) or !is_numeric($_GET['filecount'])) $offset = 1;
	else $offset = $_GET['filecount'];
	if ($offset == 1) {
		$listing_start = $offset * entries_per_page - entries_per_page;
	}
	else {
		$listing_start = $offset * entries_per_page - entries_per_page + 3;
	}					
	$listing_end = $offset * entries_per_page + 2;
	$dirlisting = @scandir("./temp/".$port."/playlist") or $errors[] = "<h2>".$messages["420"]."</h2>";
	if (!isset($dirlisting[$listing_start]))
		$errors[] = "<h2>".$messages["421"]."</h2>";
}
?>