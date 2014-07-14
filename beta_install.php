<?php
if (isset($_POST['sql_dns'])) {
	if(!$connection = mysql_connect($_POST['sql_dns'], $_POST['sql_user'], $_POST['sql_pass'])) {
    	$errors[] = "<h2>No connection!</h2>";
	}
	else {
    	if(!$db = mysql_select_db($_POST['sql_daba'])){
			$errors[] = "<h2>No connection!</h2>";
    	}
        else {
			if(!mysql_query("CREATE TABLE `headlines` ( `id` int(11) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `title` varchar(100) NOT NULL default '', `text` text NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ")){ $errors[] = "<h2>MySQL: headlines could not be created!</h2>";}
						
			if(!mysql_query("CREATE TABLE `notices` ( `id` int(11) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `reason` varchar(100) NOT NULL default '', `message` varchar(10240) NOT NULL, `ip` varchar(100) NOT NULL default '', `time` varchar(100) NOT NULL default '', PRIMARY KEY  (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ")
			){ $errors[] = "<h2>MySQL: notices could not be created!</h2>";}

			if(!mysql_query("CREATE TABLE `servers` ( `id` int(11) NOT NULL auto_increment, `owner` varchar(100) NOT NULL default '', `maxuser` varchar(100) NOT NULL default '', `portbase` int(11) NOT NULL default '0', `bitrate` varchar(100) NOT NULL default '',  `adminpassword` varchar(100) NOT NULL default '', `password` varchar(100) NOT NULL default '',  `sitepublic` varchar(100) NOT NULL default '1', `logfile` varchar(100) NOT NULL default '../logs/sc_{port}.log',  `realtime` varchar(100) NOT NULL default '1',   `screenlog` varchar(100) NOT NULL default '0', `showlastsongs` varchar(100) NOT NULL default '10', `tchlog` varchar(100) NOT NULL default 'Yes', `weblog` varchar(100) NOT NULL default 'no', `w3cenable` varchar(100) NOT NULL default 'Yes', `w3clog` varchar(100) NOT NULL default 'sc_w3c.log', `srcip` varchar(100) NOT NULL default 'ANY', `destip` varchar(100) NOT NULL default 'ANY', `yport` varchar(100) NOT NULL default '80', `namelookups` varchar(100) NOT NULL default '0', `relayport` varchar(100) NOT NULL default '0', `relayserver` varchar(100) NOT NULL default 'empty', `autodumpusers` varchar(100) NOT NULL default '0', `autodumpsourcetime` varchar(100) NOT NULL default '30', `contentdir` varchar(100) NOT NULL default '', `introfile` varchar(100) NOT NULL default '', `titleformat` varchar(100) NOT NULL default '', `publicserver` varchar(100) NOT NULL default 'default', `allowrelay` varchar(100) NOT NULL default 'Yes', `allowpublicrelay` varchar(100) NOT NULL default 'Yes', `metainterval` varchar(100) NOT NULL default '32768', `suspended` varchar(100) NOT NULL default '', `abuse` int(11) NOT NULL default '0', `pid` varchar(100) NOT NULL default '', `autopid` varchar(100) NOT NULL, `webspace` varchar(100) NOT NULL, `serverip` varchar(100) NOT NULL, `serverport` varchar(100) NOT NULL, `streamtitle` varchar(100) NOT NULL, `streamurl` varchar(100) NOT NULL, `shuffle` int(1) NOT NULL default '1', `samplerate` varchar(100) NOT NULL, `channels` int(1) NOT NULL default '2', `genre` varchar(100) NOT NULL, `quality` int(1) NOT NULL default '1', `crossfademode` varchar(100) NOT NULL, `crossfadelength` varchar(100) NOT NULL, `useid3` int(1) NOT NULL default '1', `public` int(1) NOT NULL default '1', `aim` varchar(100) NOT NULL, `icq` varchar(100) NOT NULL, `irc` varchar(100) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ")
			){$errors[] = "<h2>MySQL: servers could not be created!</h2>";}

			if(!mysql_query("CREATE TABLE `settings` ( `id` int(11) NOT NULL default '0', `title` varchar(50) NOT NULL, `slogan` varchar(50) NOT NULL default '', `display_limit` int(11) NOT NULL default '10', `host_add` varchar(100) NOT NULL default '192.168.0.1', `os` varchar(100) NOT NULL default '', `dir_to_cpanel` varchar(100) NOT NULL default '', `scs_config` varchar(1) NOT NULL, `adj_config` varchar(1) NOT NULL, `php_mp3` varchar(50) NOT NULL default '10', `php_exe` varchar(50) NOT NULL default '250', `update_check` varchar(1) NOT NULL default '1', `login_captcha` varchar(1) NOT NULL default '1', `ssh_user` varchar(256) NOT NULL, `ssh_pass` varchar(256) NOT NULL, `ssh_port` varchar(11) NOT NULL default '22', `language` varchar(256) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 ")
			){$errors[] = "<h2>MySQL: settings could not be created!</h2>";}
			
			if(!mysql_query("CREATE TABLE `users` ( `id` int(11) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `user_password` varchar(50) NOT NULL default '', `md5_hash` varchar(100) NOT NULL default '', `user_level` varchar(100) NOT NULL default '', `user_email` varchar(200) NOT NULL default '', `contact_number` varchar(15) NOT NULL, `mobile_number` varchar(15) NOT NULL, `account_notes` text NOT NULL, `name` varchar(50) NOT NULL default '', `surname` varchar(50) NOT NULL default '', `age` varchar(3) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ")
			){$errors[] = "<h2>MySQL: users could not be created!</h2>";}
			
			if (!mysql_query("INSERT INTO `notices` (`id`, `username`, `reason`, `message`, `ip`, `time`) VALUES (78, 'Shoutcast Admin', 'Welcome - German Welcome Test Message', 'Herzlich Willkommen zu der Public Beta 1 des Shoutcast Admin Panel\r\n\r\nWir freuen uns sehr dass Sie sich für unser Programm interessieren. Bitte vergewissern Sie sich das dies eine Beta Version des Programms ist, und das Programm noch nicht fertig programmiert wurde. Wir befinden uns in der Endphase der Entwicklung dieses Programms. Die finale Version wird nochmals viele Änderungen beinhalten, und empfehlen Ihnen sobald dieses verfügbar ist, dieses sich zu installieren.\r\n\r\nBei Fragen und Anregungen bitten wir Sie uns über http://support.shoutcastadmin.info zu schreiben. Ihnen werden dort auch viele weitere hilfsbereite Mitglieder helfen können.\r\n\r\nWir wünschen Ihnen auf diesem Wege viel Spaß mit dem Shoutcast Admin Panel 3 - Public Beta 1\r\n\r\nIhr Shoutcast Admin Panel Team', '127.0.0.1', '')"))
			{ $errors[] = "<h2>MySQL: notices entry could not be created!</h2>"; }
			if ($_POST['server_os'] == "linux") {
				$dir_new = $_POST['server_dir']."/";
			}
			elseif ($_POST['server_os'] == "windows") {
				$dir_new = $_POST['server_dir'].'\\';
			}
			if (!mysql_query("INSERT INTO `settings` (`id`, `title`, `slogan`, `display_limit`, `host_add`, `os`, `dir_to_cpanel`, `scs_config`, `adj_config`, `php_mp3`, `php_exe`, `update_check`, `login_captcha`, `ssh_user`, `ssh_pass`, `ssh_port`, `language`) VALUES (0, '".$_POST['server_title']."', 'Public Beta', 10, '".$_POST['server_dns']."', '".$_POST['server_os']."', '".$dir_new."', '0', '0', '20', '230', '0', '1', '".base64_encode($_POST['server_sshuser'])."', '".base64_encode($_POST['server_sshpass'])."', '".$_POST['server_sshport']."', '".$_POST['server_lang']."') ")){ $errors[] = "<h2>MySQL: settings entry could not be created!</h2>";}
			
			if (!mysql_query("INSERT INTO `users` (`id`, `username`, `user_password`, `md5_hash`, `user_level`, `user_email`, `contact_number`, `mobile_number`, `account_notes`, `name`, `surname`, `age`) VALUES (1, '".$_POST['user']."', '".$_POST['pass']."', '".md5($_POST['user'].$_POST['pass'])."', 'Super Administrator', 'admin@domain.com', 'none', '0', 'Default Administrator', 'Max', 'Mustermann', 'non') "))  { $errors[] = "<h2>MySQL: user entry could not be created!</h2>";}
		}
	}
}
$cwd = str_replace("\\", "/", getcwd());
if((count($errors) > 0) && (isset($_POST['sql_dns']))) {
	foreach($errors as $errors_cont)
				$errors_list.="<div>".$errors_cont."</div>";
	echo ($errors_list);
}
else {
	if (isset($_POST['sql_dns'])) {
		$correc[] = "<h2><a href=\"index.php\">Installation successfully! Click here to continue to the Panel and login</a></h2>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title>Shoutcast Admin Panel - Public Beta 1 Install</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./css/install.css" />
</head>
<body>
<div id="container">
	<div id="header_top">
		<div class="header logo">
			<a href="#" title=""><img src="images/logo.png" alt="" /></a>
		</div>
		<div class="header top_nav">
			<span class="session">Installation of S-A Panel 3.1-public.beta.1 <a href="http://www.shoutcastadmin.info" title="Sign out">cancel</a></span>
		</div>
	</div>
	<div id="sidebar">
		<div id="navigation">
			<div class="sidenav">
				<div class="nav_info">
					<span>Installation des Shoutcast Admin Panel 3</span><br/>
					<span class="nav_info_messages">This is not the final install, it's just beta!</span>
				</div>
				<div class="navhead_blank">
					<span>INSTALLATION</span>
					<span>3.1-public.beta.1</span>
				</div>
				<div class="subnav_child">
					<ul class="submenu">
						<li><b>Welcome to the installation</b></li>
						<li>(FTP configuration)</li>
						<li><b>MySQL configuration</b></li>
						<li>(Upload of corefiles)</li>
                        <li><b>Panel settings</b></li>
                        <li><b>Account settings</b></li>
                        <li>(Update configuration)</li>
                        <li>(Upload of config-files)</li>
                        <li><b>Other settings</b></li>
						<li>Redirect to panel</li>
					</ul>
				</div>
				<div class="navhead">
					<span>informationen</span>
					<span>ip und versionsinfo</span>
				</div>
				<div class="subnav">
					<table cellspacing="0" cellpadding="0" class="ip_table">
						<tbody>
							<tr>
								<td class="ip_table">User IP</td>
								<td class="ip_table_under"><?PHP echo ($_SERVER['REMOTE_ADDR']);?></td>
							</tr>
							<tr>
								<td class="ip_table">Server IP</td>
								<td class="ip_table_under"><?PHP echo ($_SERVER['SERVER_ADDR']);?></td>
							</tr>
							<tr>
								<td class="ip_table">Panel version</td>
								<td class="ip_table_under">3.1-public.beta.1</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div id="primary">
		<?PHP
		if(count($errors) > 0) {
			foreach($errors as $errors_cont)
				$errors_list.="<div class=\"error\">".$errors_cont."</div>";
			echo ($errors_list);
		}
		if(count($notifi) > 0) {
			foreach($notifi as $notifi_cont)
				$notifi_list.="<div class=\"notifi\">".$notifi_cont."</div>";
			echo ($notifi_list);
		}
		if(count($correc) > 0) {
			foreach($correc as $correc_cont)
				$correc_list.="<div class=\"correct\">".$correc_cont."</div>";
			echo ($correc_list);
		}
		?>
		<div id="content">
			<div class="box">
				<h2>Welcome to the installation of Shoutcast Admin Panel 3 - Public Beta 1</h2>
				<div class="tool_top_menu">
					<div class="main_shorttool">
						<p>Before you continue this installation please check this following settings on your server:</p>
						<ul>
							<li>PHP Version 4 or 5 with the setting (safe_mode=&quot;off&quot;)</li>
							<li>SSH2 as a PHP extension</li>
							<li>PHP on an Apache Server</li>
							<li>Windows or Linux Server (recommended)</li>
							<li>Linux: &quot;GNU C Library&quot; (glibc) has to be installed on a 32 Bit environment with a Version 6 or newer</li>
							<li>Linux: Sudo has to be installed</li>
							<li>MySQL for Databases</li>
						</ul>
					</div>
					<div class="main_righttool">
						<h2>Notices</h2>
						<p>In this Box you will always find any important informations about the actual page</p>
						<p>&nbsp;</p>
					</div>
				</div>
				<form action="beta_install.php" method="post">
					<fieldset>
						<legend>MySQL Configuration</legend>
						<div class="input_field">
							<label for="a">MySql DNS/IP</label>
							<input type="text" name="sql_dns" class="mediumfield" value="localhost" />
							<span class="field_desc">Please enter the DNS/IP of MySQL</span>
						</div>
						<div class="input_field">
							<label for="a">MySQL Username</label>
							<input name="sql_user" type="text" class="mediumfield" />
							<span class="field_desc">Username of MySQL</span>
						</div>
						<div class="input_field">
							<label for="a">MySQL Password</label>
							<input name="sql_pass" type="text" class="mediumfield" />
							<span class="field_desc">Password of MySQL</span>
						</div>
						<div class="input_field">
							<label for="a">Database Name</label>
							<input name="sql_daba" type="text" class="mediumfield" />
							<span class="field_desc">Database Name for MySQL</span>
						</div>
					</fieldset>
					<fieldset>
						<legend>Panel Administrator Account</legend>
						<div class="input_field">
							<label for="a">Admin Username</label>
							<input name="user" type="text" class="mediumfield" />
							<span class="field_desc">Enter Admin Username for Panel</span>
						</div>
						<div class="input_field">
							<label for="a">Admin Password</label>
							<input name="pass" type="text" class="mediumfield" />
							<span class="field_desc">Enter Admin Username for Panel</span>
						</div>        
				</fieldset>
				<fieldset>
					<legend>Server Settings</legend>
						<div class="input_field">
							<label for="a">Panel Directory</label>
							<input type="text" name="server_dir" class="mediumfield" value="<?php echo $cwd;?>" />
							<span class="field_desc">The full path for the Panel</span>
						</div>        
						<div class="input_field">
							<label for="a">IP/DNS of Server</label>
							<input type="text" name="server_dns" class="mediumfield" value="<?php echo $_SERVER["HTTP_HOST"];?>" />
							<span class="field_desc">The IP/DNS of this Server</span>
						</div>   
						<div class="input_field">
							<label for="a">Server Title</label>
							<input type="text" name="server_title" class="mediumfield" value="My Radio" />
							<span class="field_desc">Title of this Server</span>
						</div>                 
					</fieldset>
					<fieldset>
						<legend>SSH connection settings</legend>
						<div class="input_field">
							<label for="a">SSH Username</label>
							<input type="text" name="server_sshuser" class="mediumfield" />
							<span class="field_desc">Title of this Server</span>
						</div>            
						<div class="input_field">
							<label for="a">SSH Password</label>
							<input type="text" name="server_sshpass" class="mediumfield" />
							<span class="field_desc">Title of this Server</span>
						</div>  
						<div class="input_field">
							<label for="a">SSH Port</label>
							<input type="text" name="server_sshport" class="smallfield" value="22" />
							<span class="field_desc">Title of this Server</span>
						</div> 
					</fieldset>
					<fieldset>
						<legend>Other settings</legend>     
						<div class="input_field">
							<label for="a">Operating system</label>
							<?php echo "<select name='server_os'><option value='windows'>Windows</option><option value='linux'"; 
				  			if (!stristr(php_os, 'WIN')) {echo " selected";}  echo ">Linux</option></select>";?>
							<span class="field_desc">Please choose server's OS</span>
						</div> 
						<div class="input_field">
							<label for="a">Panel language</label>
							<select name="server_lang" class="playlistselect">
								<option class="playlistselectdrop" value="dutch">Dutch (da) - Extreemhost</option>
								<option class="playlistselectdrop" value="english">English (en) - Official Language*</option>
								<option class="playlistselectdrop" value="german" selected="selected">German (de) - Official Language*</option>
								<option class="playlistselectdrop" value="" disabled="disabled">README_FIRST.txt !!</option>
							</select>
							<span class="field_desc">Languagse which the panel will run with</span>
						</div>        
					</fieldset> 
					<br />
					<input class="submit" type="submit" value="Install" />
				</form>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div id="footer">
		<p>Shoutcast Admin Panel | djcrackhome | <a href="http://www.shoutcastadmin.info">http://www.shoutcastadmin.info</a> | <a href="http://www.nagualmedia.de/">Design	by Zephon</a> | CSS3-Integrated</p>
	</div>
</div>
</body>
</html>