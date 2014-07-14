<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title>SAP3_CSS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./css/framework.css" />
</head>
<body>

<div id="container">
  <div id="header_top">
		<div class="header logo">
			<a href="#" title=""><img src="images/logo.png" alt="" /></a>
		</div>
		<div class="header top_nav">
			<span class="session">Sie sind angemeldet als <a href="#" title="">Administrator</a> (<a href="#" title="Sign out">Abmelden</a>)</span>
		</div>
	</div>
	<div id="sidebar">
	  <div id="navigation">
	    <div class="sidenav">
	      <div class="nav_info"> <span>Herzlich Willkommen <span class="nav_info_username"><?PHP echo ($loginun);?></span>,</span><br/>
	        <?php
					if ($user_level=="Super Administrator")	{
						$noticesq = mysql_query("SELECT * FROM notices");
						if (mysql_num_rows($noticesq)==0) {
							echo "<span class=\"nav_info_messages\">Sie haben keine neue Nachrichten</span>";
						}
						else {
							$noticesqquant = mysql_num_rows($noticesq);
							if ($noticesqquant == 1) {
								echo "<span class=\"nav_info_messages\">Sie haben <b>".$noticesqquant."</b> neue Nachricht </span>";
							}
							else {
								echo "<span class=\"nav_info_messages\">Sie haben <b>".$noticesqquant."</b> neue Nachrichten </span>";
							}
						}
					}
					?>
          </div>
	      <div class="navhead_blank"> <span>HAUPTMENU</span> <span>server, seite und stream</span></div>
	      <div class="subnav_child">
	        <ul class="submenu">
	          <li><a href="contact.php" title="">Nachrichtencenter</a></li>
	          <li><a href="public.php" title="">Öffentliche Server</a></li>
	          <li><a href="account.php" title="">Meine Kontoeinstellungen</a></li>
	          <li><a href="servers.php" title="">Eigene Radioserver</a></li>
            </ul>
          </div>
	      <div class="navhead"> <span>AutoDJ</span> <span>autodj, playlist und mp3s</span></div>
	      <div class="subnav">
	        <ul class="submenu">
	          <li><a href="server_mp3.php" title="">MP3 Einstellungen</a></li>
	          <li><a href="sc_trans.php" title="">AutoDJ Einstellungen</a></li>
            </ul>
          </div>
	      <div class="navhead"> <span>Administration</span> <span>server und zugriff</span></div>
	      <div class="subnav">
	        <ul class="submenu">
	          <li><a href="#" title="">Menu Entry 3</a></li>
	          <li><a href="#" title="">Menu Entry 3</a></li>
	          <li><a href="#" title="">Menu Entry 3</a></li>
	          <li><a href="#" title="">Menu Entry 3</a></li>
            </ul>
          </div>
	      <div class="navhead"> <span>informationen</span> <span>ip und versionsinfo</span></div>
	      <div class="subnav">
	        <table cellspacing="0" cellpadding="0" class="ip_table">
	          <tbody>
	            <tr>
	              <td class="ip_table">Benutzer IP</td>
	              <td class="ip_table_under"><?PHP echo ($_SERVER['REMOTE_ADDR']);?></td>
                </tr>
	            <tr>
	              <td class="ip_table">Server IP</td>
	              <td class="ip_table_under"><?PHP echo ($_SERVER['SERVER_ADDR']);?></td>
                </tr>
	            <tr>
	              <td class="ip_table">Version</td>
	              <td class="ip_table_under"><?PHP echo ($PANEL_VER);?></td>
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
		foreach($errors as $errors_cont) $errors_list.="<div class=\"error\">".$errors_cont."</div>";
		echo ($errors_list);
	}
	if(count($notifi) > 0) {

		foreach($notifi as $notifi_cont) $notifi_list.="<div class=\"notifi\">".$notifi_cont."</div>";
		echo ($notifi_list);
	}
	if(count($correc) > 0) {
		foreach($correc as $correc_cont) $correc_list.="<div class=\"correct\">".$correc_cont."</div>";
		echo ($correc_list);
	}
/*	if ($page=="_no") {
		die;
	}
	else {
		include("pages/".$page.".php");
	}*/
	?>

	  <div id="content">
			<div class="box">
				<h2>Eigenschaften des Servers 192.168.178.21:8000</h2>

				<div class="contact_top_menu">
				  <div class="tool_top_menu">
				    <div class="main_shorttool">Hier können Sie den AutoDJ für Ihre Shoutcast Server konfigurieren. Bitte wählen Sie zwischen den Wiedergabelisten die Sie über die MP3 Einstellungen erstellt haben und klicken sie dann auf Starten. Bei Mausklick auf den gelben Button können Sie die Einstellungen für den AutoDJ ändern welche den Titel und die Stream-Qualität beinhalten. Natürlich können Sie diesen auch hiermit beenden.</div>
				    <div class="main_righttool">
				      <h2>AutoDJ Config</h2>
				      <p>Bitte über das Auswahlmenü die Wiedergabeliste auswählen mit der Sie den AutoDJ starten möchten.</p>
				      <p>&nbsp;</p>
			        </div>
			      </div>
				  <form method="post" action="#"><!-- Form -->
			  <fieldset>
<legend>AutoDJ Konfiguration</legend><div class="input_field">
						<label for="a">Server IP</label>
				      <input class="mediumfield" name="b" type="text" value="192.168.178.201" disabled="disabled" />
				      <span class="field_desc">Die Serverip des Shoutcast Server</span>
				  </div>
				  <div class="input_field">
					<label for="a">Server Port</label>
					  <input class="mediumfield" name="b2" type="text" value="8000" disabled="disabled"  />
					  <span class="field_desc">Der Serverport des Shoutcast Server</span>
				</div>
                    <div class="input_field">
						<label for="a">Server Password</label>
						<input class="mediumfield" name="b2" type="text" value="pass1223" disabled="disabled" />
						<span class="field_desc">Das Passwort zum Verbinden</span>
					</div>
                    <div class="input_field">
						<label for="a">Bitrate</label>
						<input class="mediumfield" name="b2" type="text" value="128000" disabled="disabled"/>
						<span class="field_desc">Die Übertragungsrate des Streams</span>
					</div>
                    <div class="input_field">
						<label for="a">Stream Titel</label>
						<input class="mediumfield" name="b2" type="text" value="sc_trans" />
						<span class="field_desc">Der Titel dieses Streams</span>
					</div>
                <div class="input_field">
                      <label for="a6">Stream URL</label>
                      <input class="mediumfield" name="b3" type="text" value="http://www.ShoutcastAdmin.info" />
                      <span class="field_desc">Die Webadresse Ihres Radios/etc.</span></div>
                <div class="input_field">
                  <label for="a7">Stream Genre</label>
                  <input class="mediumfield" name="b7" type="text" value="Hip-Hop, Top40, Ambient, Trance, Pop" />
                  <span class="field_desc">Das Musikgenre dieses Senders </span></div>
                <div class="input_field">
                  <label for="a8">Shuffle</label>
                  <input class="shortfield" name="b4" type="text" value="1" maxlength="1" />
                  <span class="field_desc">Zufällige Wiedergabe [1 = An | 0 = Aus]</span></div>
                <div class="input_field">
                  <label for="a10">Qualität</label>
                  <input class="shortfield" name="b8" type="text" value="1" maxlength="1" />
                  <span class="field_desc">Qualität [1 = Beste | 10 = Schnellste]</span></div>
                <div class="input_field">
                  <label for="a11">Crossfade</label>
                  <input class="shortfield" name="b9" type="text" value="1" maxlength="1" />
                  <span class="field_desc">Übergang der Lieder [1 = An | 0 = Aus]</span></div>
                <div class="input_field">
                  <label for="a12">Crossfade Länge</label>
                  <input class="shortfield" name="b10" type="text" value="8000" maxlength="1" />
                  <span class="field_desc">Die Länge des Musikübergangs [in Millisekunde]</span></div>
                <div class="input_field">
                  <label for="a13"> Bandbreite</label>
                  <input class="shortfield" name="b5" type="text" value="44100" />
                  <span class="field_desc">Musikbandbreite in Hz</span></div>
                <div class="input_field">
                  <label for="a14"> ID3 Benutzung</label>
                  <input class="shortfield" name="b11" type="text" value="1" maxlength="1" />
                  <span class="field_desc">ID3 Benutzung der MP3 Dateien</span></div>
                <div class="input_field">
                  <label for="a15"> Öffentlich zeigen</label>
                  <input class="shortfield" name="b12" type="text" value="1" maxlength="1" />
                  <span class="field_desc">Stream öffentlich gezeigt werden  [1 = An | 0 = Aus]</span></div>
                <div class="input_field">
                  <label for="a5">Channel</label>
                  <input class="shortfield" name="b6" type="text" value="2" maxlength="1"/>
                  <span class="field_desc">Musikchannel [1 = Mono | 2 = Stereo]</span> </div>
                <div class="input_field">
                  <label for="a16">AIM</label>
                  <input class="mediumfield" name="b13" type="text" />
                  <span class="field_desc">Benutzer Verbindung für AOL</span></div>
                <div class="input_field">
                  <label for="a17">ICQ</label>
                  <input class="mediumfield" name="b14" type="text" />
                  <span class="field_desc">Benutzer Verbindung für ICQ</span></div>
                <div class="input_field">
                  <label for="a18">IRC</label>
                  <input class="mediumfield" name="b15" type="text" />
                  <span class="field_desc">Benutzer Verbindung für IRC</span></div>
<input class="submit" type="submit" value="Submit" />
					<input class="submit" type="reset" value="Zurücksetzen" />
				</fieldset>
			</form>
				</div>
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