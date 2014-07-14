<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/autodj_top.php
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

$limit = $setting['display_limit'];
if (!isset($_GET['p'])) {
	$p = 0;
}
else {
	$p = $_GET['p'] * $limit;
}
$l = $p + $limit;
$select = mysql_query("SELECT * FROM servers WHERE owner='".$loginun."' ORDER BY id ASC LIMIT $p,$limit");
if (isset($_GET['id'])) {
	$radioport = mysql_query("SELECT portbase FROM servers WHERE id='".$_GET['id']."' AND owner='".$loginun."'");
	if (mysql_num_rows($radioport)==0) {
		header('Location: content.php?include=music&error=sc_trans_access');
		die ();
	}
	$autopid_check_sql = mysql_query("SELECT autopid FROM servers WHERE id='".$_GET['id']."' AND owner='".$loginun."'");
	if (mysql_num_rows($autopid_check_sql)==0) {
		header('Location: content.php?include=music&error=sc_trans_access');
		die ();
	}
	if (mysql_result($autopid_check_sql,0) != "9999999") {
		if ($_GET['action'] == "start") {
			$connection = @fsockopen($setting['host_add'], mysql_result($radioport,0), &$errno, &$errstr, 1)  or $php_err .= "server doa";
			if ($connection) {
				fputs($connection, "GET /7.html HTTP/1.0\r\nUser-Agent: XML Getter (Mozilla Compatible)\r\n\r\n");
				while(!feof($connection))
					$page .= fgets($connection, 1000);
				fclose($connection);
				$page = ereg_replace(".*<body>", "", $page);
				$page = ereg_replace("</body>.*", ",", $page);
				$numbers = explode(",", $page);
			}
			if (($numbers[1] == "1") || (!isset($_POST['pllist']))) {
				if ($numbers[1] == "1") {
					$errors[] = "<h2>".$messages["331"]."</h2>";
				}
				if (!isset($_POST['pllist'])) {
					$errors[] = "<h2>".$messages["332"]."</h2>";
				}
			}
			else {
				$serverdata = mysql_query("SELECT * FROM servers WHERE id='".$_GET['id']."' AND portbase='".mysql_result($radioport,0)."'");
				$ini_content = "";
				$ini_content .= "playlistfile=".$setting['dir_to_cpanel']."temp/".mysql_result($radioport,0)."/playlist/".strip_tags(ereg_replace("/","", $_POST['pllist']))."\n";
				foreach(mysql_fetch_array($serverdata) as $field => $value) {
					if (!is_numeric($field) && $field != "id" && $field != "owner" && $field!="maxuser" && $field!="portbase" && $field!="adminpassword" && $field!="sitepublic" && $field!="realtime" && $field!="screenlog" && $field!="showlastsongs" && $field!="tchlog" && $field!="weblog" && $field!="w3cenable" && $field!="w3clog" && $field!="srcip" && $field!="destip" && $field!="yport" && $field!="namelookups" && $field!="relayport" && $field!="relayserver" && $field!="autodumpusers" && $field!="autodumpsourcetime" && $field!="contentdir" && $field!="introfile" && $field!="titleformat" && $field!="publicserver" && $field!="allowrelay" && $field!="allowpublicrelay" && $field!="metainterval" && $field!="suspended" && $field!="abuse" && $field!="pid" && $field!="autopid" && $field!="webspace") {
						$ini_content .= $field."=".$value."\n";
					}
				}
				if ($setting['os'] == 'linux') 	{
					$filename = "temp/".mysql_result($radioport,0)."/".mysql_result($radioport,0)."_".time().".conf";
				}
				$handle = fopen($filename, "a");
				chmod($filename,0777);
				if (fwrite($handle, $ini_content) === FALSE) {
					return false;
				}
				fclose($handle);
				if ($setting['os'] == 'linux') {
					$connection = ssh2_connect('localhost', $setting['ssh_port']);
					ssh2_auth_password($connection, ''.base64_decode($setting['ssh_user']).'', ''.base64_decode($setting['ssh_pass']).'');
					$ssh2_exec_com = ssh2_exec($connection, 'sudo -u '.base64_decode($setting['ssh_user']).' '.$setting["dir_to_cpanel"].'files/linux/sc_trans '.$setting["dir_to_cpanel"].$filename.' </dev/null 2>/dev/null >/dev/null & echo $!');
					sleep(4);
					$pid = stream_get_contents($ssh2_exec_com);
					if (!$pid || $pid == "") {
						mysql_query("INSERT INTO notices (username,reason,message,ip) VALUES('".$loginun."','Server failure','The server with id ".$_GET['view']." cannot start on port ".$serverdata['portbase']."','".$_SERVER['REMOTE_ADDR']."')");
						echo "Could not start server, please contact administration using the contact form on your left";
						echo "".$filename."";
					}
				}
				mysql_query("UPDATE servers SET autopid='$pid' WHERE id='".$_GET['id']."'");
				$correc[] = "<h2>".$messages["333"]."</h2>";
				if ($setting["adj_config"]=="0") {
					unlink($filename);
				}
			}
		}
		elseif ($_GET['action'] == "stop") {
			$pid = mysql_query("SELECT autopid FROM servers WHERE id='".$_GET['id']."'");
			if (mysql_result($pid,0)=="") {
				$errors[] = "<h2>".$messages["334"]."</h2>";
			}
			else {
				if ($setting["os"]=="linux") {
					$connection = ssh2_connect('localhost', $setting['ssh_port']);
					ssh2_auth_password($connection, ''.base64_decode($setting['ssh_user']).'', ''.base64_decode($setting['ssh_pass']).'');
					$ssh2_exec_com = ssh2_exec($connection, 'kill '.mysql_result($pid,0));
					sleep(2);
				}
			$notifi[] = "<h2>".$messages["335"]."</h2>";
			}
		}
		elseif ($_GET['action'] == "edit") {
			if (isset($_POST['submit'])) {
				$sqlquery_autodjupdate=mysql_query("UPDATE servers SET streamtitle='".mysql_real_escape_string($_POST['titl'])."',streamurl='".mysql_real_escape_string($_POST['surl'])."',genre='".mysql_real_escape_string($_POST['genr'])."',shuffle='".mysql_real_escape_string($_POST['shuf'])."',quality='".mysql_real_escape_string($_POST['qual'])."',crossfademode='".mysql_real_escape_string($_POST['crom'])."',crossfadelength='".mysql_real_escape_string($_POST['crol'])."',samplerate='".mysql_real_escape_string($_POST['samp'])."',useid3='".mysql_real_escape_string($_POST['uid3'])."',public='".mysql_real_escape_string($_POST['publ'])."',channels='".mysql_real_escape_string($_POST['chan'])."',aim='".mysql_real_escape_string($_POST['maim'])."',icq='".mysql_real_escape_string($_POST['micq'])."',irc='".mysql_real_escape_string($_POST['mirc'])."' WHERE id='".$_GET['id']."' AND portbase='".mysql_result($radioport,0)."'");	
				$correc[] = "<h2>".$messages["336"]." ".mysql_result($radioport,0)." ".$messages["336_pre"]."</h2>";
			}
			$editsql = mysql_query("SELECT * FROM servers WHERE id='".$_GET['id']."' AND portbase='".mysql_result($radioport,0)."'");
			while ($editsqlrow = mysql_fetch_array($editsql)) {
				$formedit_port = $editsqlrow["portbase"];
				$formedit_pass = $editsqlrow["password"];
				$formedit_bitr = $editsqlrow["bitrate"];
				$formedit_titl = $editsqlrow["streamtitle"];
				$formedit_surl = $editsqlrow["streamurl"];
				$formedit_genr = $editsqlrow["genre"];
				$formedit_shuf = $editsqlrow["shuffle"];
				$formedit_qual = $editsqlrow["quality"];
				$formedit_crom = $editsqlrow["crossfademode"];
				$formedit_crol = $editsqlrow["crossfadelength"];
				$formedit_samp = $editsqlrow["samplerate"];
				$formedit_uid3 = $editsqlrow["useid3"];	
				$formedit_publ = $editsqlrow["public"];	
				$formedit_chan = $editsqlrow["channels"];
				$formedit_maim = $editsqlrow["aim"];	
				$formedit_micq = $editsqlrow["icq"];	
				$formedit_mirc = $editsqlrow["irc"];	
			}
		}
	}
	else {
		$errors[] = "<h2>".$messages["337"]."</h2>";
	}
}
?>