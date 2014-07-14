<?PHP
//	Shoutcast Admin Panel 3.1 - Public Beta 1
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.shoutcastadmin.info
///////////////////////////////////////////////
//	./captcha/picture.php
//	

session_start();	
$img=imagecreatefromjpeg('captcha_bg.jpg');	
$text=imagettftext($img,15,rand(-10,10),rand(5,80),rand(20,21),imagecolorallocate($img,255-rand(100,255),255-rand(100,255),255-rand(100,255)),"../css/type/delicious-roman-webfont.ttf",empty($_SESSION['captcha_shoutcastadmin']) ? 'error' : $_SESSION['captcha_shoutcastadmin']);
header("Content-type:image/jpeg");
header("Content-Disposition:inline ; filename=secure.jpg");	
imagejpeg($img);
?>