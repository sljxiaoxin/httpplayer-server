<?php
	$folder = $_GET['folder'];   //默认是/读取config预设值文件路径
	$arrBaseFolder = require_once("config.php");
	
	$arrFolder = array();
	$arrPicture = array();
	$arrVideo  = array();
	if($folder == '/'){
		foreach($arrBaseFolder as $k=>$v){
			$arrFolder[] = array(
				'type' => 'folder',
				'name' => $v['name'],
				'docName' => $k,
				'size' => 'N/A',
				'date' => 'N/A',
				'url' => $folder.$k
			);
		}
	}else{
		$dir = trim($folder, "/");
		$arrDir = explode('/', $dir);
		$baseDir = $arrDir[0];
		$cfgInfo = $arrBaseFolder[$baseDir];
		$arrDir[0] = $cfgInfo['pathRoot'];
		$realDir = implode("\\", $arrDir);
		//echo "realDir is : ".$realDir."<br/>";
		///*
		//按路径读取当前路径下的所有文件
		//echo "fff\r\n";
		$realDir = iconv('UTF-8', 'GB2312', $realDir);
		if(is_dir($realDir)){
			//echo "aaa\r\n";
			if($dh = opendir($realDir)){
				//echo "bbb\r\n";
				while (($file = readdir($dh)) !== false) {
					if(!($file == '.' || $file == '..')){
						//$file = iconv('GB2312', 'UTF-8', $file);
						$realFile = $realDir."\\".$file;
						//echo "file path is:".$realFile."<br>";
						if(is_dir($realFile) && $realFile != './.' && $realFile != './..'){
							//echo "folder name is:".$file."<br>";
							$arrFolder[] = array(
								'type' => 'folder',
								'name' => iconv('GB2312', 'UTF-8', $file),
								'docName' => iconv('GB2312', 'UTF-8', $file),
								'size' => 'N/A',
								'date' => 'N/A',
								'url' => $folder.iconv('GB2312', 'UTF-8', $file)
							);
						}else if(is_file($realFile)){
							$arrExt = explode(".", $file);
							$strExt = end($arrExt);
							$strExt = strtolower($strExt);
							if(in_array($strExt, array('jpg','gif','png','bmp','jpeg'))){
								$t = filemtime($realFile);
								$date = date("Y-m-d",$t);
								$arrPicture[] = array(
									'type' => 'image',
									'name' => iconv('GB2312', 'UTF-8', $file),
									'docName' => iconv('GB2312', 'UTF-8', $file),
									'size' => filesize($realFile),
									'date' => $date,
									'url' => $folder.iconv('GB2312', 'UTF-8', $file)
								);
							}elseif(in_array($strExt, array('flv','mkv','mov','avi','mp4','rm','rmvb','wmv'))){
								$t = filemtime($realFile);
								$date = date("Y-m-d",$t);
								$arrVideo[] = array(
									'type' => 'video',
									'name' => iconv('GB2312', 'UTF-8', $file),
									'docName' => iconv('GB2312', 'UTF-8', $file),
									'size' => filesize($realFile),
									'date' => $date,
									'url' => $folder.iconv('GB2312', 'UTF-8', $file)
								);
							}
							//echo "file name is => ".$file."<br>";
						}
					}
				}
			}
		}
		//*/
	}
	$arrBack = array();
	for($i=0;$i<count($arrFolder);$i++){
		$arrBack[] = $arrFolder[$i];
	}
	for($i=0;$i<count($arrPicture);$i++){
		$arrBack[] = $arrPicture[$i];
	}
	for($i=0;$i<count($arrVideo);$i++){
		$arrBack[] = $arrVideo[$i];
	}
	//print_r($arrBack);
	echo json_encode(array(
		'data' => $arrBack
	));
?>