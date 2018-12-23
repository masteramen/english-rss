<?php

	$_GET['link']='http://dict.eudic.net/ting/channel?id=d6bf4ef6-a5a7-4463-99f3-9581e3d3d47b&type=category';
	if(!isset($_GET['link']))die;
	define('RSS_HOME',dirname(__FILE__)."/../");
	require_once(RSS_HOME.'curl.php');
	require_once(RSS_HOME.'init_tables.php');
	$content = curl_get($_GET['link']);

	if(!preg_match('/<div class="contents frap">(.*?)<div class="continue">/s', $content,$m))die;
	$content=$m[1];
	if(!preg_match_all('/<dl title=".*?" id=(.*?)>.*?<span class="date">(.*?)<\/span>/s', $content, $m))die;

	global $wpdb;

	$channel_id=insertMediaChannel(array(
		channel_code=>'english_news',
		channel_title=>'英语新闻',
		channel_type=>'mp4'
	));

	for($i=0;$i<count($m[0]);$i++){
		if($m[2][$i]==date('Y-m-d') || $m[2][$i]==date("Y-m-d",strtotime("-1 day"))){
			$today=$m[2][$i];
			$url = "http://dict.eudic.net/ting/article?id={$m[1][$i]}";
			$content = curl_get($url);
			if(preg_match_all('/<dl title="(.*?)">.*?<a href="(.*?)" .*?>.*?<img src="(.*?)" \/><\/dt>.*?<span class="date">(.*?)<\/span>.*?<\/dl>/s', $content, $m2)){
				for($k=0;$k<count($m2[0]);$k++){
					$detail_url=$m2[2][$k];
					$title=$m2[1][$k];
					$pub_time=$m2[4][$k];
					$conver_url=$m2[3][$k];
					if($detail_url=='javascript:void(0)')
					{
							if(preg_match('/VideoPool\/(.*?)\/index.png/',$m2[4][$k],$m3)){
							$id=$m3[1];
							$detail_url = "/webting/desktopplay?id={$id}&amp;token=QYN+eyJ0b2tlbiI6IiIsInVzZXJpZCI6IiIsInVybHNpZ24iOiI5ZFQyOXJtMExKcERNUjBFeXJoOVMrekFTMmM9IiwidCI6IkFCSU1UVTBOamd3TnpFeU53PT0ifQ%3D%3D";
							}

					}
					if($detail_url=='javascript:void(0)')continue;

					$data=array( 
					link => 'http://dict.eudic.net'.$detail_url,
					title =>  $title,
					pub_time =>date('Y-m-d H:i:s',strtotime($pub_time)),
					create_time =>date('Y-m-d H:i:s',time()),
					channel_id =>$channel_id,
					conver_url => $conver_url
					);
					insertMediaResource($data);

				}
			}else {
				die('no match');
			}


		}
	}




	