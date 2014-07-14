<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./pages/update.php
//	

if (!eregi("content.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$version = "3.1";
$url = "http://update.shoutcastadmin.info/index.html";
$file = @fopen ($url,"r");
$startstring = '<!-- <update ver="';
$endstring = '"> -->'; 
if (trim($file) == "") {
	} else {
	$i=0;
	while (!feof($file)) {
		$zeile[$i] = fgets($file,2000);
		$i++;
	}
	fclose($file);
}
for ($j=0;$j<$i;$j++) {
	if ($resa = strstr($zeile[$j],$startstring)) {
		$resb = str_replace($startstring, "", $resa);
		$endstueck = strstr($resb, $endstring);
		$resultat .= str_replace($endstueck,"",$resb);
	}
}
if ($version<$resultat) {
		$notifi[] = "<h2><a href=\"http://update.shoutcastadmin.info\" target=\"_blank\">".$messages["524"]."</a></h2>";
}
?>