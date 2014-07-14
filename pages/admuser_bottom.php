<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/admuser_bottom.php
//	

if (!eregi("content.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

?>
<div id="content">
	<div class="box">
		<h2><?php echo $messages["228"];?> <?php echo $port;?></h2>
		<div class="tool_top_menu">
			<div class="main_shorttool"><?php echo $messages["229"];?></div>
			<div class="main_righttool">
				<h2><?php echo $messages["230"];?></h2>
				<p><?php echo $messages["231"];?></p>
				<p>&nbsp;</p>
			</div>
		</div>
		<table cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th><?php echo $messages["232"];?></th>
					<th><?php echo $messages["233"];?></th>
					<th><a class="selector" href="content.php?include=admuser&action=newuser"><?php echo $messages["234"];?></a></th>
				</tr>
			</thead>
			<tbody>
					<?php
					$get_users = mysql_query("SELECT * FROM users order by id ASC limit $p,$limit");
					while($data = mysql_fetch_array($get_users)) {
						$get_servers = mysql_query("SELECT * FROM servers WHERE owner='".$data['username']."'");
						echo "<tr>
							<td>".$data['username']."</td>
							<td>".mysql_num_rows($get_servers)." ".$messages["237"]."</td>
							<td><a class=\"delete\" href=\"content.php?include=admuser&action=edit&id=".$data['id']."&function=delete\">".$messages["235"]."</a><a class=\"edit\" href=\"content.php?include=admuser&action=edit&id=".$data['id']."\">".$messages["236"]."</a></td>
							</tr>";
					}
					?>
			</tbody>
		</table>
		<ul class="paginator">
			<?php
			$i = 0;
			$page = mysql_num_rows(mysql_query("SELECT * FROM users"));
			while($page > "0") {
				echo "<li><a href=\"content.php?include=admuser&?p=";
				if (($p / $limit) == $i){
					echo "";
				}
				echo "$i\">$i</a></li>";
				$i++;
				$page -= $limit;
			}
			?>
		</ul>
        <?php 
		if (($_GET['action'] == "edit" && $user_check!=="1") || ($_GET['action'] == "newuser")) {
			if ($_GET['action'] !== "newuser") {
				$userq = mysql_query("SELECT * FROM users WHERE id='".$_GET['id']."'");
				foreach(mysql_fetch_array($userq) as $key => $pref) {
					if (!is_numeric($key)) {
						if ($pref != "") {
							$userdata[$key] = $pref;
						}
						else {
							$userdata[$key] = "none";
						}
					}
				}
			}
		?>
		<br />
		<h2><?php if ($_GET['action'] == "newuser") { echo $messages["238"]; } else { echo $messages["239"]; }?></h2>
		<form method="post" action="content.php?include=admuser&action=<?php if ($_GET['action'] == "newuser") { echo "newuser"; } else { echo "edit"; }?>&function=update<?php if ($_GET['action'] == "edit") { echo "&id=".$_GET['id']; }?>">
			<fieldset>
				<legend><?php echo $messages["240"];?></legend>
				<div class="input_field">
					<label for="a"><?php echo $messages["241"];?></label>
					<input class="mediumfield" name="eusername" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['username']."\" disabled=\"disabled\""; }?>" />
					<span class="field_desc"><?php echo $messages["242"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["243"];?></label>
					<input class="mediumfield" name="euser_password" type="password" value="<?php if ($_GET['action'] == "edit") { echo $userdata['user_password']; }?>" />
					<span class="field_desc"><?php echo $messages["244"];?></span>
				</div>
				<?php if ($_GET['action'] == "newuser") {?>
				<div class="input_field">
					<label for="a"><?php echo $messages["245"];?></label>
					<input class="mediumfield" name="ceuser_password" type="password" value="" />
					<span class="field_desc"><?php echo $messages["246"];?></span>
				</div>
				<?php } ?>
				<div class="input_field">
					<label for="a"><?php echo $messages["265"];?></label>
					<?php
					if ($_GET['action'] == "newuser") {
						echo '<select name="euser_level"><option value="Super Administrator">'.$messages["247"].'</option><option selected="selected" value="User">'.$messages["248"].'</option></select>';
					}
					else {
						echo '<select name="euser_level"><option ';
						if($userdata['user_level']=="Super Administrator") { 
							echo "selected ";
						}
						echo " value=\"Super Administrator\">".$messages["247"]."</option><option ";
						if($userdata['user_level']=="User") {
							echo "selected ";
						}
						echo " value=\"User\">".$messages["248"]."</option></select>";
					}
					?>                    
					<span class="field_desc"><?php echo $messages["249"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["250"];?></label>
					<input class="smallfield" name="econtact_number" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['contact_number']; }?>" />
					<span class="field_desc"><?php echo $messages["251"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["252"];?></label>
					<input class="smallfield" name="emobile_number" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['mobile_number']; }?>" />
					<span class="field_desc"><?php echo $messages["253"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["254"];?></label>
					<input class="mediumfield" name="euser_email" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['user_email']; }?>" />
					<span class="field_desc"><?php echo $messages["255"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["256"];?></label>
					<input class="mediumfield" name="ename" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['name']; }?>" />
					<span class="field_desc"><?php echo $messages["257"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["258"];?></label>
					<input class="mediumfield" name="esurname" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['surname']; }?>" />
					<span class="field_desc"><?php echo $messages["259"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["260"];?></label>
					<input class="smallfield" name="eage" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['age']; }?>" />
					<span class="field_desc"><?php echo $messages["261"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["262"];?></label>
					<input class="smallfield" name="eaccount_notes" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['account_notes']; }?>" />
					<span class="field_desc"><?php echo $messages["263"];?></span>
				</div>
				<input class="submit" type="submit" value="<?php echo $messages["264"];?>" />
			</fieldset>
		</form>
		<?php }?>
	</div> 
</div>