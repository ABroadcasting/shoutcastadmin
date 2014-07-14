<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/server_bottom.php
//	

if (!eregi("content.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$limit = $setting['display_limit'];
if (!isset($_GET['p'])) {
	$p = 0;
}
else {
	$p = $_GET['p'] * $limit;
}
$l = $p + $limit;
$listq = mysql_query("SELECT * FROM servers WHERE owner='".$loginun."' ORDER BY id ASC LIMIT $p,$limit");
if (!isset($_GET['manage'])) {
?>
<div id="content">
	<div class="box">
		<h2><?php echo $messages["433"];?></h2>
			<div class="contact_top_menu">
				<div class="tool_top_menu">
					<div class="main_shorttool"><?php echo $messages["434"];?></div>
					<div class="main_righttool">
						<h2><?php echo $messages["435"];?></h2>
						<p><?php echo $messages["436"];?></p>
						<p>&nbsp;</p>
					</div>
				</div>
				<table cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th><?php echo $messages["437"];?></th>
							<th><?php echo $messages["438"];?></th>
							<th><?php echo $messages["439"];?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (mysql_num_rows($listq)==0) {
							echo "<tr>
								<td colspan=\"5\">".$messages["440"]."</td>
								</tr>";
						}
						else {
							while($data = mysql_fetch_array($listq)) {
								echo '<tr>
									<td><a href="http://'.$setting['host_add'].':'.$data['portbase'].'/" target="_blank">'.$setting['host_add'].'</a></td>
									<td><a href="http://'.$setting['host_add'].':'.$data['portbase'].'/" target="_blank">'.$data['portbase'].'</a></td>
									<td><div class="space_show" style="background-position:';
								if (file_exists("./pages/uploads/".$data['portbase']."/")) {
									$dir = "./pages/uploads/".$data['portbase']."/";
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
									}
								}
								$negative_background_pos = ($actual_dir_size/$data['webspace'])*120;
								echo '-'.$negative_background_pos.'px 0px;"></div></td>
									<td><a class="delete" href="content.php?include=server&view='.$data["id"].'&action=stop">'.$messages["441"].'</a><a class="selector" href="content.php?include=server&view='.$data["id"].'&action=start">'.$messages["442"].'</a><a class="edit" href="content.php?include=server&manage='.$data["id"].'">'.$messages["443"].'</a></td>
									</tr>';
							}
						}
						?>
					</tbody>
				</table>
				<ul class="paginator">
					<?php
					if (mysql_num_rows($listq)==0) { }
					else {
					$page = mysql_num_rows(mysql_query("SELECT * FROM servers WHERE owner='".$loginun."'"));
					$i = 0;
					$page = mysql_num_rows(mysql_query("SELECT * FROM servers"));
					while($page > "0") {
						echo "<li><a href=\"content.php?include=server&p=";
						if (($p / $limit) == $i){
							echo "";
						}
						echo "$i\">$i</a></li>";
						$i++;
						$page -= $limit;
						}
					}
					?>
				</ul>
			</div>
		</div> 
	</div>

<?php 
}
else {
	?>
<div id="content">
	<div class="box">
		<h2><?php echo $messages["444"];?> <?php if ($user_level=="Super Administrator")	{ $portquery = mysql_fetch_object(mysql_query("SELECT portbase FROM servers WHERE id='".$_GET['manage']."'"));	echo $portquery->portbase; } else { $portquery = mysql_fetch_object(mysql_query("SELECT portbase FROM servers WHERE owner='".$loginun."' AND id='".$_GET['manage']."'")); echo $portquery->portbase; }?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["445"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["446"];?></h2>
					<p><?php echo $messages["447"];?></p>
				</div>
			</div>
		<form method="post" action="content.php?include=server&manage=<?php echo $_GET['manage'];?>&action=edit">
		  <fieldset>
			<legend><?php echo $messages["448"];?></legend>
				<?php
					if (isset($_GET['manage'])) {
						$server= mysql_query("SELECT * FROM servers WHERE id='".$_GET['manage']."'");
						foreach (mysql_fetch_array($server) as $field => $value) {
							if (!is_numeric($field) && $field != "id" && $field != "suspended" && $field != "abuse" && $field != "pid" && $field != "autopid" && $field != "webspace" && $field != "serverip" && $field != "serverport" && $field != "streamtitle" && $field != "streamurl" && $field != "shuffle" && $field != "samplerate" && $field != "channels" && $field != "genre" && $field != "quality" && $field != "crossfademode" && $field != "crossfadelength" && $field != "useid3" && $field != "public" && $field != "aim" && $field != "icq" && $field != "irc") {
								echo "<div class=\"input_field\">
									<label for=\"a\">";
								if ($field == "owner") echo $messages["451"];
								if ($field == "maxuser") echo $messages["452"];
								if ($field == "portbase") echo $messages["453"];
								if ($field == "bitrate") echo $messages["454"];
								if ($field == "adminpassword") echo $messages["455"];
								if ($field == "password") echo $messages["456"];
								if ($field == "sitepublic") echo $messages["457"];
								if ($field == "logfile") echo $messages["458"];
								if ($field == "realtime") echo $messages["459"];
								if ($field == "screenlog") echo $messages["460"];
								if ($field == "showlastsongs") echo $messages["461"];
								if ($field == "tchlog") echo $messages["462"];
								if ($field == "weblog") echo $messages["463"];
								if ($field == "w3cenable") echo $messages["464"];
								if ($field == "w3clog") echo $messages["465"];
								if ($field == "srcip") echo $messages["466"];
								if ($field == "destip") echo $messages["467"];
								if ($field == "yport") echo $messages["468"];
								if ($field == "namelookups") echo $messages["469"];
								if ($field == "relayport") echo $messages["470"];
								if ($field == "relayserver") echo $messages["471"];
								if ($field == "autodumpusers") echo $messages["472"];
								if ($field == "autodumpsourcetime") echo $messages["473"];
								if ($field == "contentdir") echo $messages["474"];
								if ($field == "introfile") echo $messages["475"];
								if ($field == "titleformat") echo $messages["476"];
								if ($field == "publicserver") echo $messages["477"];
								if ($field == "allowrelay") echo $messages["478"];
								if ($field == "allowpublicrelay") echo $messages["479"];
								if ($field == "metainterval") echo $messages["480"];
								echo "</label>";
								if (($field == "owner") || ($field == "portbase") || ($field == "w3clog") || ($field == "w3cenable") || ($field == "logfile") || ($field == "maxuser") || ($field == "bitrate")) {
									echo "<input class=\"";
									if (($field == "bitrate") ||
										($field == "maxuser") ||
										($field == "portbase") ||
										($field == "logfile") ||
										($field == "w3clog") ||
										($field == "w3cenable")) {
											echo "small";
									}
									else {
										echo "medium";
									}
									echo "field\" name=\"".$field."\" type=\"text\" disabled=\"disabled\" value=\"".$value."\" />";
								}
								else {
									echo "<input class=\"";
									if (($field == "sitepublic") ||
										($field == "realtime") ||
										($field == "screenlog") ||
										($field == "showlastsongs") ||
										($field == "tchlog") ||
										($field == "weblog") ||
										($field == "yport") ||
										($field == "namelookups") ||
										($field == "relayport") ||
										($field == "autodumpusers") ||
										($field == "autodumpsourcetime") ||
										($field == "allowrelay") ||
										($field == "allowpublicrelay") ||
										($field == "metainterval")) {
											echo "small";
									}
									else {
										echo "medium";
									}
									echo "field\" name=\"".$field."\" type=\"text\" value=\"".$value."\" />";
								}
								echo "<span class=\"field_desc\">";
								if ($field == "owner") echo $messages["481"];
								if ($field == "maxuser") echo $messages["482"];
								if ($field == "portbase") echo $messages["483"];
								if ($field == "bitrate") echo $messages["484"];
								if ($field == "adminpassword") echo $messages["485"];
								if ($field == "password") echo $messages["486"];
								if ($field == "sitepublic") echo $messages["487"];
								if ($field == "logfile") echo $messages["488"];
								if ($field == "realtime") echo $messages["489"];
								if ($field == "screenlog") echo $messages["490"];
								if ($field == "showlastsongs") echo $messages["491"];
								if ($field == "tchlog") echo $messages["492"];
								if ($field == "weblog") echo $messages["493"];
								if ($field == "w3cenable") echo $messages["494"];
								if ($field == "w3clog") echo $messages["495"];
								if ($field == "srcip") echo $messages["496"];
								if ($field == "destip") echo $messages["497"];
								if ($field == "yport") echo $messages["498"];
								if ($field == "namelookups") echo $messages["499"];
								if ($field == "relayport") echo $messages["500"];
								if ($field == "relayserver") echo $messages["501"];
								if ($field == "autodumpusers") echo $messages["502"];
								if ($field == "autodumpsourcetime") echo $messages["503"];
								if ($field == "contentdir") echo $messages["504"];
								if ($field == "introfile") echo $messages["505"];
								if ($field == "titleformat") echo $messages["506"];
								if ($field == "publicserver") echo $messages["507"];
								if ($field == "allowrelay") echo $messages["508"];
								if ($field == "allowpublicrelay") echo $messages["509"];
								if ($field == "metainterval") echo $messages["510"];
								echo "</span>
									</div>";
							}
						}
					}
					?>
					<input class="submit" type="submit" name="submit" value="<?php echo $messages["449"];?>" />
					<input class="submit" type="reset" value="<?php echo $messages["450"];?>" onclick="document.location='content.php?include=server';" />
				</fieldset>
			</form>
		</div>
	</div>
</div>
<?php 
}?>