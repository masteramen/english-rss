<?php

	if(!isset($_GET['link']))die;
	require_once('./curl.php');
	$content = curl_get($_GET['link']);
	$detail = array();
	
	if(preg_match_all('/(<div class="article"><p class="paragraph">.*?<\/div>)/si',$content,$ms) || preg_match_all('/(<div id="article">\r\n<h1><span class="sentence".*?<\/div>)/si',$content,$ms)){

			$str=trim($ms[1][0]);
			//print_r($ms);

			$ret='';
			if(preg_match_all('/\{"order":(.*?),"timestamp":"(.*?)","timestamps":".*?","text":"(.*?)","hashvalue":.*?\}/si',$content,$ms3)){
				//print_r($ms3);

			}

			$k=count($ms3[1])-1;

			if(preg_match_all('/<p class="paragraph"><span .*?data-starttime="(.*?)" data-endtime=".*?">(.*?)<\/p>/si',$str,$ms2)){
				for($i=count($ms2[0])-1;$i>=0;$i--){
					$pstart=$ms2[1][$i];
					$ts='';
					for(;$k>=0;$k--){
						//echo $ms3[2][$k].'>='.$pstart.'='.($ms3[2][$k]>=$pstart)."\n";
						if($ms3[2][$k]>=$pstart){
							$ts=stripslashes($ms3[3][$k]).$ts;
						}else{
							//echo 'brak;';
							break;
						}
					}
					//echo $ts;
					//$ret=$ts.$ret."\n";
					$ret = "[".$pstart."]".trim(strip_tags($ms2[0][$i]))."\n".trim($ts)."\n".$ret;

				}

			}
			
			$detail['content']="[ti:]\n\n".$ret;
			$detail['LRC_OK']='1';
	}
	
	/*
	if(preg_match_all('/src="(.*?mp[34])"/i',$content,$ms)){
		$detail['audio']=$ms[1][0];
	}*/

//"http://static.frhelper.com/MediaPool/ae645a30-6ee2-11e8-b0c5-000c29ffef9b/data/ae645a30-6ee2-11e8-b0c5-000c29ffef9b.mp3?stamp=1528906884970"

	if(preg_match('/Webting_play\.initPlayPage\("(.*?.mp[34]\?stamp=\d+)"/',$content,$ms)){
		$detail['audio'] = $ms[1];
	}else if(preg_match('/<video class="video" .*?src="(.*?)" poster="(.*?)">/',$content,$ms)){
		$detail['audio'] = $ms[1];
		$detail['thumb'] = $ms[2];

	}
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






	