<?php
set_time_limit(0);
$arrBaseFolder = require_once("config.php");
$folder = $_GET['folder'];
//$folder = urlencode($folder);
$dir = trim($folder, "/");
$arrDir = explode('/', $dir);
$baseDir = $arrDir[0];
$cfgInfo = $arrBaseFolder[$baseDir];
$arrDir[0] = $cfgInfo['pathRoot'];
$folder = implode("\\", $arrDir);
$folder = iconv('UTF-8', 'GB18030//IGNORE', $folder);
/*
$fp1 = fopen($folder, 'rb');
if($fp1){
	echo "file open ok";
}else{
	echo "file open fail";
}
$fp = fopen("log.txt", "a+");
fwrite($fp, iconv('UTF-8', 'GB18030//IGNORE',$_GET['folder'])."\n");
fwrite($fp, $folder."\n");
fclose($fp);
*/
//var_dump($fp);
//echo $folder;die();
/*
$fp = fopen("log.txt", "a+");
fwrite($fp, "php int max:".PHP_INT_MAX);
fclose($fp);
*/
PutMovie($folder);
function PutMovie($file) {
	header("Content-type: video/mp4");
	header("Accept-Ranges: bytes");
	
	$size = sprintf("%u",filesize($file));
	$size = (intval($size) == PHP_INT_MAX) ? PHP_INT_MAX : filesize($file);
	//echo "filesize is:".$size."<br>";die();
	if(isset($_SERVER['HTTP_RANGE'])){
		header("HTTP/1.1 206 Partial Content");
		list($name, $range) = explode("=", $_SERVER['HTTP_RANGE']);
		list($begin, $end) =explode("-", $range);
		if($end == 0) $end = $size - 1;
	}
	else {
		$begin = 0; $end = $size - 1;
	}
	
	header("Content-Length: " . ($end - $begin + 1));
	header("Content-Disposition: filename=".basename($file));
	header("Content-Range: bytes ".$begin."-".$end."/".$size);
	$fp = fopen($file, 'rb');
	fseek($fp, $begin);
	while(!feof($fp)) {
		$p = min(1024, $end - $begin + 1);
		/*
		$fps = fopen("log.txt", "a+");
		fwrite($fps, "begin value is :".$begin."\n");
		fwrite($fps, "end value is :".$end."\n");
		fwrite($fps, "size value is :".$size."\n");
		fclose($fps);
		*/
		if($p <= 0){
			break;
		}
		$begin += $p;
		$data =  fread($fp, $p);
		/*
		$fps = fopen("log.txt", "a+");
		fwrite($fps, "\r\n[data] value is :".$data."\r\n");
		fclose($fps);
		*/
		echo $data;
	}
	fclose($fp);
}

?>