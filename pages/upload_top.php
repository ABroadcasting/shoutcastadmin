<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/upload_top.php
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

if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {

	if (!isset($_GET['portbase'])) {
		header('Location: content.php?include=music&error=port');
		die ();
	}
	else {
		$port = $_GET['portbase'];
	}
	$port = strip_tags(ereg_replace('/','', $port));
	$selectowner = mysql_query("SELECT * FROM servers WHERE portbase='".$port."' AND owner='".$_SESSION['username']."'");
	if (mysql_num_rows($selectowner)==1) {
		$port=$port;
	}
	else {
		header('Location: content.php?include=music&error=access');
		die ();
	}
	$database_space = mysql_query("SELECT * FROM servers WHERE portbase='".$port."'") or die ();
	$data = mysql_fetch_array($database_space); 
	if (file_exists("./pages/uploads/".$port."/")) {
		$port_use = $port;
	}
	else {
		$old = umask(0);
		mkdir("./pages/uploads/".$port."", 0777);
		umask($old);
		$port_use = $port;
		if ($old != umask()) {
			header('Location: content.php?include=music&error=dir');
			die ();
		}
	}
	if (file_exists("./pages/uploads/".$port."/")) {
		$dir = "./pages/uploads/".$port."/";
		$filesize = 0;
		if(is_dir($dir)) {
			if($dp = opendir($dir)) {
				while( $filename = readdir($dp) ) {
					if(($filename == '.') || ($filename == '..')) 
					continue;
					$filedata = stat($dir."/".$filename);
					$filesize += $filedata[7];
				}
				$actual_dir_size = $filesize/1024;
			}
			else {
				$errors[] = "<h2>".$messages["547"]."</h2>";
			}
		}
		else {
			$errors[] = "<h2>".$messages["548"]."</h2>";
		}
	}
	$filename = basename($_FILES['uploaded_file']['name']);
	$ext = substr($filename, strrpos($filename, '.') + 1);
	$file_size_m = str_replace("M","",ini_get("upload_max_filesize"));
	if ($_FILES["uploaded_file"]["size"] >= (($data['webspace']*1024)-$actual_dir_size*1024)) {
		$errors[] = "<h2>".$messages["549"]."</h2>";
	}
	else {
	if (
    	($ext == "mp3") && 
    	($_FILES["uploaded_file"]["size"] < ($file_size_m*1024)*1024) &&
    	(
        	($_FILES["uploaded_file"]["type"] == "audio/mpeg") || 
        	($_FILES["uploaded_file"]["type"] == "audio/mpeg3") || 
			($_FILES["uploaded_file"]["type"] == "audio/ext") || 
        	($_FILES["uploaded_file"]["type"] == "audio/x-mpeg-3") ||
			($_FILES["uploaded_file"]["type"] == "application/octet-stream") ||
			($_FILES["uploaded_file"]["type"] == "application/force-download") ||
			($_FILES["uploaded_file"]["type"] == "application/octetstream") ||
			($_FILES["uploaded_file"]["type"] == "application/x-download")
    	)
	) 
	{
		$newname = "./pages/uploads/".$port_use."/$filename";
		if (!file_exists($newname)) {
			if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname))) {
				$correc[] = "<h2>".$messages["550"]."</h2>";
				$playlistupdate = "1";
			}
			else {
				$errors[] = "<h2>".$messages["551"]."</h2>";
			}
		} 
		else {
			$errors[] = "<h2>".$messages["552"]."</h2>";
		}
	}
	else {
		$errors[] = "<h2>".$messages["553"]."</h2>";
	}
	}
	if (file_exists("./pages/uploads/".$port."/")) {
		$dir = "./pages/uploads/".$port."/";
		$filesize = 0;
		if(is_dir($dir)) {
			if($dp = opendir($dir)) {
				while( $filename = readdir($dp) ) {
					if(($filename == '.') || ($filename == '..')) 
					continue;
					$filedata = stat($dir."/".$filename);
					$filesize += $filedata[7];
				}
				$actual_dir_size = $filesize/1024;
			}
			else {
				$errors[] = "<h2>".$messages["554"]."</h2>";
			}
		}
		else {
			$errors[] = "<h2>".$messages["555"]."</h2>";
		}
	}
} 
else {
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
	if (isset($_GET['delete'])) {
		$deletefiledecoded = base64_decode($_GET['delete']);
		if (file_exists("./pages/uploads/".$port."/".$deletefiledecoded."")) {
			unlink("./pages/uploads/".$port."/".$deletefiledecoded."");
			$correc[] = "<h2>".$messages["556"]."</h2>";
			$playlistupdate = "2";
		}
		else {
			$errors[] = "<h2>".$messages["557"]."</h2>";
		}
	}
	if (isset($_GET['download'])) {
		$downloadiddecode=base64_decode($_GET['download']);
		if (file_exists("./pages/uploads/".$port."/".$downloadiddecode."")) {
			$filename = "./pages/uploads/".$port."/".$downloadiddecode."";
			if(ini_get("zlib.output_compression")) ini_set("zlib.output_compression", "Off");
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header("Content-Type: audio/mpeg");
			header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($filename));
			readfile("$filename");
			exit();
		}
		else {
			$errors[] = "<h2>".$messages["558"]."</h2>";
		}
	}
	$database_space = mysql_query("SELECT * FROM servers WHERE portbase='".$port."'") or die ();
	$data = mysql_fetch_array($database_space); 
	$port = strip_tags(ereg_replace('/','', $port));
	if (file_exists("./pages/uploads/".$port."/")) {
		$dir = "./pages/uploads/".$port."/";
		$filesize = 0;
		if(is_dir($dir)) {
			if($dp = opendir($dir)) {
				while( $filename = readdir($dp) ) {
					if(($filename == '.') || ($filename == '..')) 
					continue;
					$filedata = stat($dir."/".$filename);
					$filesize += $filedata[7];
				}
				$actual_dir_size = $filesize/1024;
			}
			else {
				$errors[] = "<h2>".$messages["559"]."</h2>";
			}
		}
		else {
			$errors[] = "<h2>".$messages["560"]."</h2>";
		}
	}
}
$dirlistdir = @opendir("./pages/uploads/".$port."/") or $errors[] = "<h2>".$messages["561"]."</h2>";
define('entries_per_page',7);
if (!isset($_GET['filecount']) or !is_numeric($_GET['filecount'])) $offset = 1;
else $offset = $_GET['filecount'];
if ($offset == 1) {
	$listing_start = $offset * entries_per_page - entries_per_page;
}
else {
	$listing_start = $offset * entries_per_page - entries_per_page + 3;
}					
$listing_end = $offset * entries_per_page + 2;
$dirlisting = @scandir("./pages/uploads/".$port) or $errors[] = "<h2>".$messages["562"]."</h2>";
if (!isset($dirlisting[$listing_start])) $errors[] = "<h2>".$messages["563"]."</h2>";
if (isset($_GET['playlist']) or is_numeric($_GET['playlist'])) {
	if (!file_exists("./temp/".$port.".lst")) {
		$handle = fopen("./temp/".$port.".lst",'w+'); 
    	fclose($handle);
   		chmod("./temp/".$port.".lst",0777);
	}
	shell_exec('find '.dirname(__FILE__).'/pages/uploads/'.$port.'/ -type f -name "*.mp3" > ./temp/'.$port.'.lst');	
	header('Location: content.php?include=music&error=playlist');
	die ();
}
?>