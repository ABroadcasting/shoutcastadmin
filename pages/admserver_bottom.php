<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/admserver_bottom.php
//	

if (!eregi("content.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

?>
<div id="content">
	<div class="box">
		<h2><?php echo $messages["188"];?> <?PHP echo ($_SERVER['SERVER_ADDR']);?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["189"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["190"];?></h2>
					<p><?php echo $messages["191"];?></p>
				</div>
			</div>
			<form method="post" action="content.php?include=admserver&action=savesettings">	
				<fieldset>
					<legend><?php echo $messages["192"];?></legend>
					<div class="input_field">
						<label for="a"><?php echo $messages["193"];?></label>
						<input class="mediumfield" name="host_add" type="text" value="<?php echo $setting['host_add'];?>" />
						<span class="field_desc"><?php echo $messages["194"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["195"];?></label>
						<input class="mediumfield" name="os" type="text" value="<?php echo $setting['os'];?>" disabled="disabled" />
						<span class="field_desc"><?php echo $messages["196"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["197"];?></label>
						<input class="mediumfield" name="ssh_user" type="text" value="<?php echo base64_decode($setting['ssh_user']);?>" />
						<span class="field_desc"><?php echo $messages["198"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["199"];?></label>
						<input class="mediumfield" name="ssh_pass" type="text" value="<?php echo base64_decode($setting['ssh_pass']);?>" />
						<span class="field_desc"><?php echo $messages["200"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["201"];?></label>
						<input class="smallfield" name="ssh_port" type="text" value="<?php echo $setting['ssh_port'];?>" />
						<span class="field_desc"><?php echo $messages["202"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["203"];?></label>
						<input class="mediumfield" name="dir_to_cpanel" type="text" value="<?php echo $setting['dir_to_cpanel'];?>" />
						<span class="field_desc"><?php echo $messages["204"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["205"];?></label>
						<input class="mediumfield" name="title" type="text" value="<?php echo $setting['title'];?>" />
						<span class="field_desc"><?php echo $messages["206"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["207"];?></label>
						<input class="mediumfield" name="slogan" type="text" value="<?php echo $setting['slogan'];?>" />
						<span class="field_desc"><?php echo $messages["208"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["209"];?></label>
						<input class="smallfield" name="php_mp3" type="text" value="<?php echo $setting['php_mp3'];?>" />
						<span class="field_desc"><?php echo $messages["210"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["211"];?></label>
						<?php
						echo '<select name="language">';
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
						$dirlisting = @scandir("./pages/messages/") or $errors[] = "";
						if (!isset($dirlisting[$listing_start])) $errors[] = "";
						for($i=$listing_start;$i<=$listing_end;$i++) {
							if (($dirlisting[$i]!=".") and ($dirlisting[$i]!="..") and ($dirlisting[$i]!="")) {
								echo "<option";
								if (substr($dirlisting[$i], 0, -4) == $setting['language']) {
									echo " selected=\"selected\"";
								}
								echo " class=\"playlistselectdrop\" value=\"".substr($dirlisting[$i], 0, -4)."\">".ucfirst(substr($dirlisting[$i], 0, -4))."</option>";
							}
						}
						echo '</select>';?>
						<span class="field_desc"><?php echo $messages["212"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["213"];?></label>
						<input class="smallfield" name="php_exe" type="text" value="<?php echo $setting['php_exe'];?>" />
						<span class="field_desc"><?php echo $messages["214"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["215"];?></label>
						<input class="smallfield" name="update_check" type="text" value="<?php echo $setting['update_check'];?>" />
						<span class="field_desc"><?php echo $messages["216"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["217"];?></label>
						<input class="smallfield" name="adj_config" type="text" value="<?php echo $setting['adj_config'];?>" />
						<span class="field_desc"><?php echo $messages["218"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["219"];?></label>
						<input class="smallfield" name="scs_config" type="text" value="<?php echo $setting['scs_config'];?>" />
						<span class="field_desc"><?php echo $messages["220"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["221"];?></label>
						<input class="smallfield" name="login_captcha" type="text" value="<?php echo $setting['login_captcha'];?>" />
						<span class="field_desc"><?php echo $messages["222"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["223"];?></label>
						<input class="smallfield" name="display_limit" type="text" value="<?php echo $setting['display_limit'];?>" />
						<span class="field_desc"><?php echo $messages["224"];?></span>
					</div>
					<input class="submit" type="submit" value="<?php echo $messages["225"];?>" />
				</fieldset>
			</form>
		</div>
	</div> 
</div>