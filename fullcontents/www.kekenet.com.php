<?php

	if(!isset($_GET['link']))die;
	require_once('./curl.php');
	$content = curl_get($_GET['link']);
	$detail = array();
	if(preg_match_all('/<div id="article">(.*?)<\/div>/si',$content,$ms)){
			$str=$ms[1][0];
			$str = preg_replace('/<span style="display:none".*?<\/span>/si',"",$str);
			$str = preg_replace('/<script>.*?<\/script>/si',"",$str);
			$str = preg_replace('/[\x{4e00}-\x{9fa5}，。]/iu ',"",$str);
			
			$detail['content']=$str;
	}
	
	if(preg_match_all("/var domain= '(.*?)';/i",$content,$ms)){
			if(preg_match_all('/var thunder_url ="(.*?)";/i',$content,$ms2)){
					$detail['audio']=$ms[1][0].$ms2[1][0];
			}
	}





	