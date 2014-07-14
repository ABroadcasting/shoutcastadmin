<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/autodj_bottom.php
//	

if (!eregi("content.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

if ($_GET['action'] !== "edit") {?>
<div id="content">
	<div class="box">
		<h2><?php echo $messages["277"];?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["278"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["279"];?></h2>
					<p><?php echo $messages["280"];?></p>
				</div>
			</div>
			<table cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th><?php echo $messages["281"];?></th>
						<th><?php echo $messages["282"];?></th>
						<th><?php echo $messages["283"];?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (mysql_num_rows($select)==0) {
						echo "<tr>
							<td colspan=\"5\">".$messages["284"]."</td>
							</tr>";
					}
					else {
						while($data = mysql_fetch_array($select)) {
							if ($data['autopid'] != "9999999") {
								echo '<form action="content.php?include=autodj&id='.$data['id'].'&action=start" method="post" name="sc_transform'.$data['portbase'].'">
									<tr>
									<td><a href="http://'.$setting['host_add'].':'.$data['portbase'].'/" target="_blank">'.$setting['host_add'].'</a></td>
									<td><a href="http://'.$setting['host_add'].':'.$data['portbase'].'/" target="_blank">'.$data['portbase'].'</a></td>
									<td><select name="pllist" class="playlistselect">';
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
								$dirlisting = @scandir("./temp/".$data['portbase']."/playlist/") or $errors[] = "";
								if (!isset($dirlisting[$listing_start])) $errors[] = "";
								for($i=$listing_start;$i<=$listing_end;$i++) {
									if (($dirlisting[$i]!=".") and ($dirlisting[$i]!="..") and ($dirlisting[$i]!="")) {
										echo "<option class=\"playlistselectdrop\" value=\"".$dirlisting[$i]."\">".$dirlisting[$i]."</option>";
									}
								}
								echo '</select>
									</td>
									<td><a class="selector" href="javascript:document.sc_transform'.$data['portbase'].'.submit()">'.$messages["285"].'</a></td>
									<td><a class="delete" href="content.php?include=autodj&id='.$data['id'].'&action=stop">'.$messages["286"].'</a><a class="edit" href="content.php?include=autodj&id='.$data['id'].'&action=edit">'.$messages["287"].'</a></td>
									</tr>
									</form>';
							}
						}
					}
					?>
				</tbody>
			</table>
			<ul class="paginator">
				<?php
				if (mysql_num_rows($select)==0) { }
				else {
					$page = mysql_num_rows(mysql_query("SELECT * FROM servers WHERE owner='".$loginun."'"));
					$i = 0;
					$page = mysql_num_rows(mysql_query("SELECT * FROM servers"));
					while($page > "0") {
						echo "<li><a href=\"content.php?include=autodj&page=servers&p=";
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
} else {
?>
<div id="content">
	<div class="box">
		<h2><?php echo $messages["288"];?> <?php echo $formedit_port;?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["289"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["290"];?></h2>
					<p><?php echo $messages["291"];?></p>
				</div>
			</div>
			<form method="post" action="content.php?include=autodj&id=<?php echo $_GET['id'];?>&action=edit">
				<fieldset>
					<legend><?php echo $messages["292"];?></legend>
					<div class="input_field">
						<label for="a"><?php echo $messages["293"];?></label>
						<input class="mediumfield" name="ip" type="text" value="<?php echo $setting['host_add'];?>" disabled="disabled" />
						<span class="field_desc"><?php echo $messages["294"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["295"];?></label>
						<input class="mediumfield" name="port" type="text" value="<?php echo $formedit_port;?>" disabled="disabled"  />
						<span class="field_desc"><?php echo $messages["296"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["297"];?></label>
						<input class="mediumfield" name="pass" type="text" value="<?php echo $formedit_pass;?>" disabled="disabled" />
						<span class="field_desc"><?php echo $messages["298"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["299"];?></label>
						<input class="mediumfield" name="bitr" type="text" value="<?php echo $formedit_bitr;?>" disabled="disabled"/>
						<span class="field_desc"><?php echo $messages["300"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["301"];?></label>
						<input class="mediumfield" name="titl" type="text" value="<?php echo $formedit_titl;?>" />
						<span class="field_desc"><?php echo $messages["302"];?></span>
					</div>
					<div class="input_field">
						<label for="a6"><?php echo $messages["303"];?></label>
						<input class="mediumfield" name="surl" type="text" value="<?php echo $formedit_surl;?>" />
						<span class="field_desc"><?php echo $messages["304"];?></span>
					</div>
					<div class="input_field">
						<label for="a7"><?php echo $messages["305"];?></label>
						<input class="mediumfield" name="genr" type="text" value="<?php echo $formedit_genr;?>" />
						<span class="field_desc"><?php echo $messages["306"];?></span>
					</div>
					<div class="input_field">
						<label for="a8"><?php echo $messages["307"];?></label>
						<input class="shortfield" name="shuf" type="text" value="<?php echo $formedit_shuf;?>" maxlength="1" />
						<span class="field_desc"><?php echo $messages["308"];?></span>
					</div>
					<div class="input_field">
						<label for="a10"><?php echo $messages["309"];?></label>
						<input class="shortfield" name="qual" type="text" value="<?php echo $formedit_qual;?>" maxlength="1" />
						<span class="field_desc"><?php echo $messages["310"];?></span>
					</div>
					<div class="input_field">
						<label for="a11"><?php echo $messages["311"];?></label>
						<input class="shortfield" name="crom" type="text" value="<?php echo $formedit_crom;?>" maxlength="1" />
						<span class="field_desc"><?php echo $messages["312"];?></span>
					</div>
					<div class="input_field">
						<label for="a12"><?php echo $messages["313"];?></label>
						<input class="shortfield" name="crol" type="text" value="<?php echo $formedit_crol;?>" />
						<span class="field_desc"><?php echo $messages["314"];?></span>
					</div>
					<div class="input_field">
						<label for="a13"><?php echo $messages["315"];?></label>
						<input class="shortfield" name="samp" type="text" value="<?php echo $formedit_samp;?>" />
						<span class="field_desc"><?php echo $messages["316"];?></span>
					</div>
					<div class="input_field">
						<label for="a14"><?php echo $messages["317"];?></label>
						<input class="shortfield" name="uid3" type="text" value="<?php echo $formedit_uid3;?>" maxlength="1" />
						<span class="field_desc"><?php echo $messages["318"];?></span>
					</div>
					<div class="input_field">
						<label for="a15"><?php echo $messages["319"];?></label>
						<input class="shortfield" name="publ" type="text" value="<?php echo $formedit_publ;?>" maxlength="1" />
						<span class="field_desc"><?php echo $messages["320"];?></span>
					</div>
					<div class="input_field">
						<label for="a5"><?php echo $messages["321"];?></label>
						<input class="shortfield" name="chan" type="text" value="<?php echo $formedit_chan;?>" maxlength="1"/>
						<span class="field_desc"><?php echo $messages["322"];?></span>
					</div>
					<div class="input_field">
						<label for="a16"><?php echo $messages["323"];?></label>
						<input class="mediumfield" name="maim" type="text" value="<?php echo $formedit_maim;?>" />
						<span class="field_desc"><?php echo $messages["324"];?></span>
					</div>
					<div class="input_field">
						<label for="a17"><?php echo $messages["325"];?></label>
						<input class="mediumfield" name="micq" type="text" value="<?php echo $formedit_micq;?>" />
						<span class="field_desc"><?php echo $messages["326"];?></span>
					</div>
					<div class="input_field">
						<label for="a18"><?php echo $messages["327"];?></label>
						<input class="mediumfield" name="mirc" type="text" value="<?php echo $formedit_mirc;?>" />
						<span class="field_desc"><?php echo $messages["328"];?></span>
					</div>
					<input class="submit" type="submit" name="submit" value="<?php echo $messages["329"];?>" />
					<input class="submit" type="reset" value="<?php echo $messages["330"];?>" onclick="document.location='content.php?include=autodj';" />
				</fieldset>
			</form>
		</div>
	</div>
</div>
<?php } ?>