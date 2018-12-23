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
	$file="en8848.html";


	if(!file_exists($file)||(time() - filemtime($file)> 100*3600)){
		require_once('./curl.php');
		$content = curl_get("http://www.en8848.com.cn/bec/waimao/wmky900/");
		$arr = array();

		if(preg_match_all('/<div class="ch_lii_left"><a href="(.*?)" title="(.*?)" >(.*?)<\/a>/si',$content,$ms)){
				//print_r($ms);
				for($i=0;$i<count($ms[1]);$i++)
				{
					$title = $ms[2][$i];
					$title = preg_replace('/900句Unit \d/','',$title);
					$title = preg_replace('/mp3/i','',$title);
					$link = $ms[1][$i]."?__=".date("Ymd");
					$row = array("link"=>$link,"title"=>$title);
					array_push($arr,$row);
				}

		}
		ob_start();

		$i=0;
		foreach($arr as $row){
			if(strlen($row['title'])<40)continue;
			echo "<li><a href='{$row['link']}'>{$row['title']}</a></li>\n";
			$i++;
			if($i>20)break;
		}
		$content = ob_get_contents();
		writeToFile($file,$content);
	}else{
		echo file_get_contents($file);
	}


	