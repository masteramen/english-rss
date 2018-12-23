<?php

	if(!isset($_GET['link']))die;
	require_once('./curl.php');
	$content = curl_get($_GET['link']);
	$detail = array();



	if(preg_match_all('/<div class="article">(.*?)<\/div>/si',$content,$ms)){
			$str=trim($ms[1][0]);

			//$str = preg_replace('/[\s]*\n/i',"",$str);
			//$str = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $str);
			//$str = preg_replace('/^\s+\r?\n$/', '', $str);
			//print_r($tmp);
			$ret='';
			if(preg_match_all('/\{"order":(.*?),"timestamp":"(.*?)","timestamps":".*?","text":"(.*?)","hashvalue":.*?\}/si',$content,$ms3)){
				//print_r($ms3);

			}

			//$k=count($ms3[1])-1;
			if(preg_match_all('/<span class="sentence J_.*?" id="J_.*?" data-starttime="(.*?)" data-endtime="(.*?)">(.*?)<\/span>/si',$str,$ms2)){
				
				//print_r($ms2);
				for($i=0;$i<count($ms2[1]);$i++){
					$start=$ms2[1][$i];
					$end =$ms2[2][$i];
					$text = trim(strip_tags($ms2[0][$i]));
					$index=$i+1;
					$ts =stripslashes($ms3[3][$i]);
					$ret .= "{$index}\n00:{$start} --> 00:{$end}\n{$text}\n$ts\n\n";
				}
				/*for($i=count($ms2[0])-1;$i>=0;$i--){
					$pstart=$ms2[1][$i];
					$ts='';
					for(;$k>=0;$k--){
						//echo $ms3[2][$k].'>='.$pstart.'='.($ms3[2][$k]>=$pstart)."\n";
						if($ms3[2][$k]>=$pstart){
							$ts=$ms3[3][$k].$ts;
						}else{
							//echo 'brak;';
							break;
						}
					}
					//echo $ts;
					//$ret=$ts.$ret."\n";
					$ret = "[".$pstart."]".trim(strip_tags($ms2[0][$i]))."\n".trim($ts)."\n".$ret;

				}*/

			}
			
			$detail['content']=$ret;
	}
	
	/*
	if(preg_match_all('/src="(.*?mp[34])"/i',$content,$ms)){
		$detail['audio']=$ms[1][0];
	}*/

	if(preg_match('/<h1>(.*?)<\/h1>/',$content,$ms)){

		$str=trim($ms[1]);
		//$detail['audio']= 'http://www.jfox.info/rss/conver.php?q='.urlencode($str).'&type=.mp3';
	}else {
		die('error');
	}
	if(isset($_GET['debug'])){
		//print_r($content);
		print_r($detail);
	}





	