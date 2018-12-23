<?php


	if(!isset($_GET['link']))die;
	require_once('./curl.php');
	$content = curl_get($_GET['link']);
	$detail = array();
	if(preg_match_all('/<textarea id="lrc_content" name="textfield" cols="70" rows="10" style="display:none;">(.*?)<\/textarea>/si',$content,$ms)){
			$str=trim($ms[1][0]);

			//$str = preg_replace('/[\s]*\n/i',"",$str);
			//$str = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $str);
			$str = preg_replace('/^\s+\r?\n$/', '', $str);
			$tmp = explode("\n", $str);
			//print_r($tmp);
			$str='';
			foreach($tmp as $line){
				if($line[0]=='['){
					$en = preg_replace('/\s([\x{4e00}-\x{9fa5}]+).*/u','',$line, 1);
					$cn = str_replace($en, '', $line);
					$str.= "$en\n$cn\n";

				}
			}
			$detail['content']="[ti:]\n\n".$str;
	}
	
	if(preg_match_all('/mp3:"(.*?)"/i',$content,$ms)){
		$detail['audio']=$ms[1][0];
	}

	if(isset($_GET['debug'])){
		print_r($detail);
	}





	