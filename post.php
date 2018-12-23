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
		
		
	if(isset($_POST['_e'])){
		require_once('crypto.fn.php');
		$_POST=json_decode(decrypt($_POST['_e']),true);
		$_POST['_e']=true;
	}
	if(!isset($_POST['link'])||!isset($_POST['link'])||!isset($_POST['audio'])||!isset($_POST['pubDate']))die('error');
	print_r($_POST);
	 $link = $_POST['link'];
	 $content = $_POST['content'];
	 $audio = $_POST['audio'];

	$cacheLinkfile =  'xml/'.urlToFileName($link).'.xml';

	require_once('./rss.class.php');
	$feed = new RSS();

	$feed->title       = "RSS Feed 标题";
	$feed->link        = "http://";
	$feed->description = "RSS 订阅列表描述。";

	$item = new RSSItem();
	if(isset($_POST['title']))$item->title = $_POST['title'];

	$item->description = $content;
	$item->link=$link.'?__='.urlencode($audio).'|'.date("Y-m-d ", $_POST['pubDate']/1000);
	$item->setPubDate($_POST['pubDate']/1000);
	$feed->addItem($item);
	$xml = $feed->out();

	if(isset($_POST['_e'])){

		$xml=fixEncoding($xml);
		$xml = encrypt($xml);
	}
	writeToFile($cacheLinkfile,$xml);
	die('ok');
	