<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/contact_bottom.php
//	

if (!eregi("content.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

?>
<div id="content">
	<div class="box">
		<h2><?php echo $messages["338"];?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["339"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["340"];?></h2>
					<p><?php echo $messages["341"];?></p>
					<p>&nbsp;</p>
				</div>
			</div>
			<form method="post" action="content.php?include=contact" id="contactform">
				<fieldset>
					<legend><?php echo $messages["342"];?></legend>
					<div class="input_field">
						<label for="a"><?php echo $messages["343"];?></label>
						<input class="mediumfield" name="c2" type="text" value="<?php echo $loginun;?>" disabled="disabled" />
						<span class="field_desc"><?php echo $messages["344"];?></span>
					</div>
					<div class="input_field">
						<label for="b"><?php echo $messages["345"];?></label>
						<input type="text" name="email" class="mediumfield" value="<?php if (isset($formerror)) { echo $_POST['email']; }?>"/>
						<?php
						if (isset($formerror)) {
							if ($formerror == "email") {
								echo "<span class=\"validate_error\">".$messages["346"]."</span>";
							}
							else {
								echo "<span class=\"validate_success\">".$messages["347"]."</span>";
							}
						}
						?>
					</div>
					<div class="input_field">
						<label for="c"><?php echo $messages["348"];?></label>
						<input type="text" name="reason" class="mediumfield" value="<?php if (isset($formerror)) { echo $_POST['reason']; }?>" />
						<?php
						if (isset($formerror)) {
							if ($formerror == "reason") {
								echo "<span class=\"validate_error\">".$messages["349"]."</span>";
							}
							else {
								echo "<span class=\"validate_success\">".$messages["350"]."</span>";
							}
						}
						?>
					</div>
					<div class="input_field">
						<textarea cols="90" name="message" rows="6" class="textbox" value=""><?php if (isset($formerror)) { echo $_POST['message']; }?></textarea>
						<?php
						if (isset($formerror)) {
							if ($formerror == "message") {
								echo "<span class=\"validate_error\">".$messages["351"]."</span>";
							}
							else {
								echo "<span class=\"validate_success\">".$messages["352"]."</span>";
							}
						}
						?>
					</div>
					<input class="submit" type="submit" name="submit" value="<?php echo $messages["353"];?>" />
					<input class="submit" type="reset" value="<?php echo $messages["354"];?>" />
				</fieldset>
			</form>
		</div>
	</div>
</div>