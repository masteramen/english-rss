<?php 

function urlsafe_b64encode($string) {
   $data = base64_encode($string);
   $data = str_replace(array('+','/','='),array('-','_',''),$data);
   return $data;
}
function urlsafe_b64decode($string) {
   $data = str_replace(array('-','_'),array('+','/'),$string);
   $mod4 = strlen($data) % 4;
   if ($mod4) {
       $data .= substr('====', $mod4);
   }
   return base64_decode($data);
}
function urlToFileName($url){
	return str_replace(array('+','/','=','.',':','?','&'),array('_','_','','','','',''),$url);
}
function writeToFile($file,$content,$flag){
	$fp = fopen($file, $flag);
	fwrite($fp,$content);
	fclose($fp);
}
	
// echo urlsafe_b64encode('http://feed43.com/8753402584161724.xml');
if(!isset($_GET['d']))die;
$detailFeedId = $_GET['d'];
$feeUrl = "http://feed43.com/$detailFeedId.xml";
if(isset($_GET['t'])&&isset($_GET['l'])){
	$link = $_GET['t'];
	$listFeedId = $_GET['l'];
	$listFeedUrl = "http://feed43.com/$listFeedId.xml";
	$feedFile = 'map/'.urlToFileName($feeUrl).'.txt';
	$linkFile = 'map/'.urlToFileName($link).'.txt';
	$listFeedFile =  "feeds_".urlToFileName($listFeedUrl).'.txt';


	if(strpos(file_get_contents($listFeedFile), $listFeedUrl)==false){
		writeToFile($listFeedFile,"\n$listFeedUrl",'a');
	}

	writeToFile($feedFile,$link,'w');
	writeToFile($linkFile,$listFeedUrl,'w');
}


if(!empty($feeUrl)){
	
//header('location:'.file_get_contents($file));
	$feedFile = 'map/'.urlToFileName($feeUrl).'.txt';
	
	if(file_exists($feedFile)){
		$link = file_get_contents($feedFile);
		if(!empty($link)){
			$linkFile = 'map/'.urlToFileName($link).'.txt';
			if(file_exists($linkFile)){
				touch($linkFile);
			}
			touch($feedFile);

			header('location:'.$link);
		}
	}
}

//}
?>
