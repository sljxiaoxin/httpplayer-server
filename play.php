<?php
$folder = $_GET['folder'];
$folder = iconv('UTF-8', 'GB18030//IGNORE', $folder);
//$folder = urlencode($folder);
//$fp = fopen($folder, 'rb');
//var_dump($fp);
//echo $folder;die();
PutMovie($folder);
function PutMovie($file) {
	header("Content-type: video/mp4");
	header("Accept-Ranges: bytes");
	
	$size = filesize($file);
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
		$begin += $p;
		echo fread($fp, $p);
	}
	fclose($fp);
}

?>