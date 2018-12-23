<?php
	//$link = $_GET['link'];
	//$feedLink = $_GET['feed'];
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
			if( !file_exists($feedFile) || (time() - filemtime($feedFile)> 7*3600*1000)){
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
					
		$encodeLink=urldecode($link);
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

		return "https://www.freefullrss.com/feed.php?url=http://www.jfox.info/rss.php?link=$encodeLink&max=1&links=preserve&exc=&submit=Create+Full+Text+RSS";
	}
	$file="vao-rss.html";

	if(!file_exists($file)||(time() - filemtime($file)> 7*3600)){
		require_once('./curl.php');
		$content = curl_get("http://www.51voa.com/");
		$arr = array();
		if(preg_match_all('/<div id="list">\r\n<ul>(.*?)<\/ul>/si',$content,$ms)){
				//print_r($ms[1][0]);
					if(preg_match_all('/<li>(.*?)<\/li>/si',$ms[1][0],$msli)){
						//print_r($msli);
						foreach($msli[1] as $li){
							if(preg_match_all('/<a .*?href="(.*?)".*?>(.*?)<\/a>/si',$li,$mslia)){
								//print_r($mslia);
								$row = array("link"=>$mslia[1][count($mslia[1])-1],"title"=>$mslia[2][count($mslia[2])-1]);
								if(endWith($mslia[1][1],".lrc")){
									$row['lrc']=$mslia[1][1];
								}
								array_push($arr,$row);
							}
						}

						
					}

		}
		ob_start();

		$i=0;
		foreach($arr as $row){
			if(strlen($row['title'])<40)continue;
			$lrc=isset($row['lrc'])?'http://www.51voa.com/'.$row['lrc']:'';
			echo "<li><a href='http://www.51voa.com/{$row['link']}'>{$row['title']}</a><a href='$lrc'>lrc</a></li>\n";
			$i++;
			if($i>20)break;
		}
		$content = ob_get_contents();
		writeToFile($file,$content);
	}else{
		echo file_get_contents($file);
	}


	