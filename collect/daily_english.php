<?php

	$_GET['link']='http://dict.eudic.net/home/HomePageList';
	if(!isset($_GET['link']))die;
	define('RSS_HOME',dirname(__FILE__)."/../");
	require_once(RSS_HOME.'curl.php');
	require_once(RSS_HOME.'init_tables.php');
	$content = curl_get($_GET['link']);
	$detail = array();

	$obj=json_decode($content);	
	//print_r($obj->ting);

	global $wpdb;

	$channel_id=insertMediaChannel(array(
		channel_code=>'daily_english',
		channel_title=>'每日英语',
		channel_type=>'mp4'
	));
	foreach ($obj->ting as $key => $row) {
			$data=array( 
			link => $row->url,
			title =>  $row->title,
			media_type =>  $row->item_type,
			pub_time =>date('Y-m-d H:i:s',strtotime($row->update_time)),
			create_time =>date('Y-m-d H:i:s',time()),
			channel_id =>$channel_id,
			conver_url => $row->image
			);
		insertMediaResource($data);
	}


//print_r($wpdb);





	