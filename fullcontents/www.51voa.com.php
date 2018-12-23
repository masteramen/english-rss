<?php

	if(!isset($_GET['link']))die;
	require_once('./curl.php');
	$content = curl_get($_GET['link']);
	$detail = array();
	if(preg_match_all('/<div id="content">(.*?)<\/div>[\r\n]+<div id="Bottom_VOA">/si',$content,$ms)){
			$str=$ms[1][0];
			$str = preg_replace('/<span style="display:none".*?<\/span>/si',"",$str);
			$str = preg_replace('/<script>.*?<\/script>/si',"",$str);
			$str = preg_replace('/[\x{4e00}-\x{9fa5}，。]/iu ',"",$str);
			$str = preg_replace('/51VOA.COM/i ',"",$str);
			$str = preg_replace('/<a .*?>/i ',"",$str);
			$str = preg_replace('/<\/a>/i ',"",$str);
			$str = preg_replace('/<span.*?class=[\'"]?datetime[\'"]?>.*?<\/span>/i',"",$str);
			$str = preg_replace('/<span.*?class=[\'"]?byline[\'"]?>.*?<\/span>/i',"",$str);
			$str = preg_replace('/<img.*?>/i ',"",$str);
			$str = preg_replace('/___________________________.*/is',"",$str);
			
			$str = preg_replace('/<em.*?>.*?<\/em>/i ',"",$str);

			$detail['content']=$str;
	}
	
	if(preg_match_all("/href=\"(.*?.mp3)\"/i",$content,$ms)){
		$detail['audio']=$ms[1][0];
	}
	if(isset($_GET['debug'])){
		print_r($detail);
	}





	