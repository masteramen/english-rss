<?php
	 function endWith($haystack, $needle) {   

		  $length = strlen($needle);  
		  if($length == 0)
		  {    
			  return true;  
		  }  
		  return (substr($haystack, -$length) === $needle);
	 }
	function fetch_list_feed($listfeed,$file){
		require_once('./curl.php');

		$content = curl_get($listfeed);

		if(preg_match_all('/<link>(.*?)<\/link/i', $content, $ms)){
			$links = $ms[1];
			array_shift($links);
			$fp = fopen($file, 'w');
			fwrite($fp,serialize($links));
			fclose($fp);
		}
	}
	function writeToFile($file,$content){
		$fp = fopen($file, 'w');
		fwrite($fp,$content);
		fclose($fp);
	}

	function urlToFileName($url){
		return str_replace(array('+','/','=','.',':','?','&'),array('_','_','','','','',''),$url);
	}
	function getOldestFeed($feedsFile){
		$content = file_get_contents($feedsFile);
		$feeds =explode('\n',$content);
		foreach($feeds as $feed){
			$feedFile='map/'.urlToFileName($feed).'.txt';
			if( !file_exists($feedFile) || (time() - filemtime($feedFile)> 7*3600)){
				return $feed;
			}
		}
		return '';
	}
	function getFullRssLink($listfeed,$link){
		
		$linkfile =  'map/'.urlToFileName($link).'.txt';
		if(file_exists($linkfile)){	
			$feed=file_get_contents($linkfile);
			$feedFile = 'map/'.urlToFileName($feed).'.txt';
			if(file_exists($feedFile)&&file_get_contents($feedFile)==$link){
				@touch($linkfile);
				@touch(file_get_contents($linkfile));
				return file_get_contents($linkfile);
			}else{
				@unlink($linkfile);
			}
		}
					
		$file =  urlToFileName($listfeed).'.txt';
		$feedsFile = "feeds_".$file;
		if(file_exists($feedsFile)){
			$oldFeed = getOldestFeed($feedsFile);
			if(!empty($oldFeed))
			{
				$feedFile = 'map/'.urlToFileName($oldFeed).'.txt';
				writeToFile($feedFile,$link);
				writeToFile($linkfile,$oldFeed);
				return $oldFeed;
			}
		}

	}
		function fixEncoding($in_str)

		{
		//return iconv(mb_detect_encoding($in_str, mb_detect_order(), true), "UTF-8", $in_str);
		//if(true)return $in_str;
		$cur_encoding = mb_detect_encoding($in_str) ;

		if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))

		return $in_str;

		else

		return utf8_encode($in_str);

		}
		
		
	if(isset($_GET['_e'])){
		require_once('crypto.fn.php');
		$_GET=json_decode(decrypt($_GET['_e']),true);
		$_GET['_e']=true;
	}
	$link = $_GET['link'];
	 
	$cacheLinkfile =  'xml/'.urlToFileName($link).'.srt';
	if(file_exists($cacheLinkfile) && !isset($_GET['nocache']) ){

		header('Content-Type: application/rss+xml; charset=utf-8'); 
		echo file_get_contents($cacheLinkfile);
		die;
	}
	$arr = parse_url($link);
	$host = $arr['host'];
	if(isset($_GET['subtitle']) && $_GET['subtitle'] == 'srt'){
		$host.='.srt';
	};


	if(file_exists("$host.php")){

		include("$host.php");
        $detail['content'];
	
        $xml = $detail['content'];
        /*
        die($xml);
		if(isset($_GET['_e'])){
 
			$xml=fixEncoding($xml);
			$xml = encrypt($xml);
		}*/

		writeToFile($cacheLinkfile,$xml);
		header('Content-Type: application/srt; charset=utf-8'); 
		echo $xml;
		die;
	}


	die;

	//http://localhost/rss/?feed=http://feed43.com/8753402584161724.xml&link=http://www.kekenet.com/broadcast/201803/545317.shtml

//http://feed43.com/8753402584161724.xml
	