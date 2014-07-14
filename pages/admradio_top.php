<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/admradio_top.php
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

if ($_GET['new']=="server") {
	$portexist = mysql_query("SELECT * FROM servers WHERE portbase='".$_POST['portbase']."'");
	if (mysql_num_rows($portexist)>0) {
		$nextportq = mysql_query("SELECT portbase FROM servers order by portbase DESC");
		$newport = mysql_result($nextportq,0) + 2;
		$notifi[] = "<h2>Port ".$_POST['portbase']." ".$messages["169"].": ".$newport."</h2>";
		$_POST['portbase'] = $newport;
	}
	if (is_numeric($_POST['portbase'])) {
		if (mysql_query("INSERT INTO servers (owner, maxuser, portbase, bitrate, adminpassword, password, sitepublic, logfile, realtime, screenlog, showlastsongs, tchlog, weblog, w3cenable, w3clog, srcip, destip, yport, namelookups, relayport, relayserver, autodumpusers, autodumpsourcetime, contentdir, introfile, titleformat, publicserver, allowrelay, allowpublicrelay, metainterval, webspace, serverip, serverport, streamtitle, streamurl, shuffle, samplerate, channels, genre, quality, crossfademode, crossfadelength, useid3, public, autopid) VALUES('".$_POST['owner']."', '".$_POST['maxuser']."', '".$_POST['portbase']."', '".$_POST['bitrate']."', '".$_POST['adminpassword']."', '".$_POST['password']."', '".$_POST['sitepublic']."', '".$_POST['logfile']."', '".$_POST['realtime']."', '".$_POST['screenlog']."', '".$_POST['showlastsongs']."', '".$_POST['tchlog']."', '".$_POST['weblog']."', '".$_POST['w3cenable']."', '".$_POST['w3clog']."', '".$_POST['srcip']."', '".$_POST['destip']."', '".$_POST['yport']."', '".$_POST['namelookups']."', '".$_POST['relayport']."', '".$_POST['relayserver']."', '".$_POST['autodumpusers']."', '".$_POST['autodumpsourcetime']."', '".$_POST['contentdir']."', '".$_POST['introfile']."', '".$_POST['titleformat']."', '".$_POST['publicserver']."', '".$_POST['allowrelay']."', '".$_POST['allowpublicrelay']."', '".$_POST['metainterval']."', '".($_POST['webspace']*1024)."', '".$setting['host_add']."', '".$_POST['portbase']."', 'Neuer Shoutcast AutoDJ', 'http://".$setting['host_add']."', '1', '44100', '2', 'Jazz', '1', '1', '8000', '1', 'default', '".$_POST['autopid']."')")) {
			$old = umask(0);
			@mkdir("./pages/uploads/".$_POST['portbase']."", 0777);
			@mkdir("./temp/".$_POST['portbase']."", 0777);
			sleep(1);
			@mkdir("./temp/".$_POST['portbase']."/playlist", 0777);
			umask($old);
			$correc[] = "<h2>".$messages["170"]."</h2>";
		} else {
			$errors[] = "<h2>".$messages["171"]."</h2>";
		}
	}
	else {
		$errors[] = "<h2>".$messages["172"]."</h2>";
	}
}
if ($_GET['new']=="update") {
	$fields = "";
	$values = "";
	foreach($_POST as $key => $value) {
		if ($key == "webspace") { $value = ($value*1024); }
		if ($key != "submit" && $value!="" && $key!="id") {
			$fields .= $key."='".$value."', ";
			$lastfield = $key;
			$lastvalue = $value;
		}
	}
	$fields = explode($lastfield,$fields);
	$fields = $fields['0'].$lastfield."='".$lastvalue."'";
	if (mysql_query("UPDATE servers SET $fields WHERE id='".$_GET['view']."'")) {
		$correc[] = "<h2>".$messages["173"]."</h2>";
	}
	else {
		$errors[] = "<h2>".$messages["174"]."</h2>";
	}
}

if (isset($_GET['view'])) {
	$serverq = mysql_query("SELECT * FROM servers WHERE id='".$_GET['view']."'");
	$serverdata = mysql_fetch_array($serverq);
	if (isset($_GET['action']) && $_GET['action']=="restart") {
		$radioport = mysql_query("SELECT portbase FROM servers WHERE id='".$_GET['view']."'");
		if (mysql_num_rows($radioport)==0) {
			$errors[] = "<h2>".$messages["175"]."</h2>";
		}
		else {
			$connection = @fsockopen($setting['host_add'], mysql_result($radioport,0), $errno, $errstr, 1);
			if (!$connection) {
			}
			else {
				$pid = mysql_query("SELECT pid FROM servers WHERE id='".$_GET['view']."'");
				if (mysql_result($pid,0)=="") {
					$errors[] = "<h2>".$messages["176"]."</h2>";
				}
				else {
					if ($setting["os"]=="windows") {
						$WshShell = new COM("WScript.Shell");
						$oExec = $WshShell->Run("taskkill /pid ".mysql_result($pid,0)." /f", 3, false);
					}
					if ($setting["os"]=="linux") {
						$connection = ssh2_connect('localhost', $setting['ssh_port']);
						ssh2_auth_password($connection, ''.base64_decode($setting['ssh_user']).'', ''.base64_decode($setting['ssh_pass']).'');
						$ssh2_exec_com = ssh2_exec($connection, 'kill '.mysql_result($pid,0));
						sleep(1);
					}
				}
			}
			$connection = @fsockopen($setting['host_add'], mysql_result($radioport,0), &$errno, &$errstr, 1)  or $php_err .= "server doa";
			if ($connection) {
				$notifi[] = "<h2>".$messages["177"]."</h2>";
			}
			else {
				$serverdata = mysql_query("SELECT * FROM servers WHERE id='".$_GET['view']."' AND portbase='".mysql_result($radioport,0)."'");
				$ini_content = "";
				foreach(mysql_fetch_array($serverdata) as $field => $value) {
					if (!is_numeric($field) && $value !="" && $field != "id" && $field != "owner" && $field != "sitepublic" && $field != "suspended" && $field != "abuse" && $field != "pid" && $field != "autopid" && $field != "webspace" && $field != "serverip" && $field != "serverport" && $field != "streamtitle" && $field != "streamurl" && $field != "shuffle" && $field != "samplerate" && $field != "channels" && $field != "genre" && $field != "quality" && $field != "crossfademode" && $field != "crossfadelength" && $field != "useid3" && $field != "public" && $field != "aim" && $field != "icq" && $field != "irc") {
						$ini_content .= $field."=".$value."\n";
					}
				}
				if ($setting['os'] == 'windows') {
					$filename = $setting['dir_to_cpanel']."temp\\".mysql_result($radioport,0)."_".time().".ini";
				}
				if ($setting['os'] == 'linux') {
					$filename = "temp/".mysql_result($radioport,0)."_".time().".conf";
				}
				if (!$handle = fopen($filename, 'a')) {
					$errors[] = "<h2>".$messages["178"]."</h2>";
					fclose($handle);
				}
				elseif (fwrite($handle, $ini_content) === FALSE) {
					$errors[] = "<h2>".$messages["179"]."</h2>";
					fclose($handle);
				}
				else {
					if ($setting['os']=='windows') {
						$WshShell = new COM("WScript.Shell");
						$oExec = $WshShell->Run($setting['dir_to_cpanel']."files/windows/sc_serv.exe $filename", 3, false);
						$output = array();
						exec('tasklist /fi "Imagename eq  sc_serv.exe" /NH', $output);
						foreach($output as $a => $b)
						$pid = $b;
						if (strstr($pid,"INFO:") || !$pid || mysql_num_rows(mysql_query("SELECT * FROM servers WHERE pid='$pid'"))==1) {
							mysql_query("INSERT INTO notices (username,reason,message,ip) VALUES('".$loginun."','Server failure','The server with id".$_GET['view']." cannot start on port ".$serverdata['portbase']."','".$_SERVER['REMOTE_ADDR']."')");
							$errors[] = "<h2>".$messages["187"]."</h2>";
						}
						$pid = explode(" ",$pid);
						$i=0;
						foreach($pid as $a) {
							if (is_numeric($a) && !isset($set)) {
								$pid = trim($a);
								$set = 1;
							}
						}
					}
					if ($setting['os'] == 'linux') {
						$connection = ssh2_connect('localhost', $setting['ssh_port']);
						ssh2_auth_password($connection, ''.base64_decode($setting['ssh_user']).'', ''.base64_decode($setting['ssh_pass']).'');
						$ssh2_exec_com = ssh2_exec($connection, 'sudo -u '.base64_decode($setting['ssh_user']).' '.$setting["dir_to_cpanel"].'files/linux/sc_serv '.$setting["dir_to_cpanel"].$filename.' </dev/null 2>/dev/null >/dev/null & echo $!');
							sleep(3);
						$pid = stream_get_contents($ssh2_exec_com);
						if (!$pid || $pid == "") {
							mysql_query("INSERT INTO notices (username,reason,message,ip) VALUES('".$loginun."','Server failure','The server with id ".$_GET['view']." cannot start on port ".$serverdata['portbase']."','".$_SERVER['REMOTE_ADDR']."')");
							$errors[] = "<h2>".$messages["180"]."</h2>";
						}
					}
					mysql_query("UPDATE servers SET pid='$pid' WHERE id='".$_GET['view']."'");
					$correc[] = "<h2>".$messages["181"]."</h2>";	
					if ($setting["scs_config"]=="0") {
						unlink($filename);
					}
				}
			}
		}
	}
	if (isset($_GET['action']) && $_GET['action']=="delete") {
		$unlink_port_sql = mysql_query("SELECT portbase FROM servers WHERE id='".$_GET['view']."'");
		$pid = mysql_query("SELECT pid FROM servers WHERE id='".$_GET['view']."'");
		if (mysql_result($pid,0)=="") {
			$errors[] = "<h2>".$messages["182"]."</h2>";
		}
		else {
			if ($setting["os"]=="windows") {
				$WshShell = new COM("WScript.Shell");
				$oExec = $WshShell->Run("taskkill /pid ".mysql_result($pid,0)." /f", 3, false);
			}
			if ($setting["os"]=="linux") {
				$connection = ssh2_connect('localhost', $setting['ssh_port']);
				ssh2_auth_password($connection, ''.base64_decode($setting['ssh_user']).'', ''.base64_decode($setting['ssh_pass']).'');
				$ssh2_exec_com = ssh2_exec($connection, 'kill '.mysql_result($pid,0));
				sleep(1);
			}
		}
		if (mysql_num_rows($unlink_port_sql)==0) {
			$errors[] = "<h2>".$messages["183"]."</h2>";
		}
		else {
			while($t_data = mysql_fetch_array($unlink_port_sql)) {
				$unlink_port_sql_port = $t_data['portbase'];
			}
			function delete_directory($dirname) {
				if (is_dir($dirname))
					$dir_handle = opendir($dirname);
				if (!$dir_handle)
				return false;
				while($file = readdir($dir_handle)) {
					if ($file != "." && $file != "..") {
						if (!is_dir($dirname."/".$file))
							@unlink($dirname."/".$file);
						else
							delete_directory($dirname.'/'.$file); 
					}
				}
				@closedir($dir_handle);
				@rmdir($dirname);
			}
			delete_directory("./pages/uploads/".$unlink_port_sql_port."/");
			delete_directory("./temp/".$unlink_port_sql_port."/playlist/");
			delete_directory("./temp/".$unlink_port_sql_port."/");
			if (mysql_query("DELETE FROM servers WHERE id='".$_GET['view']."'")) {
				$correc[] = "<h2>".$messages["184"]."</h2>";
			}
			else {
				$errors[] = "<h2>".$messages["185"]."</h2>";
			}	
		}
	}
}
$limit = $setting['display_limit'];
if (!isset($_GET['p'])) {
	$p = 0;
}
else {
	$p = $_GET['p'] * $limit;
}
$l = $p + $limit;
$listq = mysql_query("SELECT * FROM servers ORDER BY id ASC LIMIT $p,$limit");
if ($_GET['action'] == "update") {
	$updateget_data = mysql_query("SELECT * FROM servers WHERE id='".$_GET['view']."'");
	if (mysql_num_rows($updateget_data)==0) {
		$errors[] = "<h2>".$messages["186"]."</h2>";
		header('Location: edit_serv.php');
		die();
	}
}
?>