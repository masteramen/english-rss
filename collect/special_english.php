<?php
	
	$_GET['link']='http://www.51voa.com/';
	if(!isset($_GET['link']))die;
	define('RSS_HOME',dirname(__FILE__)."/../");
	require_once(RSS_HOME.'curl.php');
	require_once(RSS_HOME.'init_tables.php');
	$content = curl_get($_GET['link']);




	global $wpdb;

	$channel_id=insertMediaChannel(array(
		channel_code=>'special_english',
		channel_title=>'慢速英语',
		channel_type=>'mp3'
	));

	$arr = array();
	if(preg_match_all('/<div id="list">\r\n<ul>(.*?)<\/ul>/si',$content,$ms)){
				if(preg_match_all('/<li>(.*?)<\/li>/si',$ms[1][0],$msli)){
					foreach($msli[1] as $li){
						if(preg_match_all('/<a .*?href="(.*?)".*?>(.*?)<\/a>/si',$li,$mslia)){
							if( in_array($mslia[1][0],
								array('/as_it_is_1.html'
									,'/Health_Report_1.html'
									,'/VOA_Standard_1.html'
									,'/Words_And_Their_Stories_1.html'
									,'/Education_Report_1.html'
									,'/Trending_Today_1.html'
									,'/Science_in_the_News_1.html'
									,'/American_Mosaic_1.html'
									,'/American_Stories_1.html'
									,'/Technology_Report_1.html'
								))
						){

							$title = $mslia[2][count($mslia[2])-1];
							 if(preg_match('/(.*?)\((\d{4}\-\d{1,2}\-\d{1,2})\)/',$title,$rm)){
							 	$data=array( 
								link => 'http://www.51voa.com'.$mslia[1][count($mslia[1])-1],
								title =>  $rm[1],
								media_type =>  3,
								pub_time =>date('Y-m-d H:i:s',strtotime($rm[2])),
								create_time =>date('Y-m-d H:i:s',time()),
								channel_id =>$channel_id
								);
								//print_r($mslia);	
								//print_r($rm);
								//print_r($data);
								//die;		
								insertMediaResource($data);				 
							}

						

							}

						}
					}

					
				}

	}


	