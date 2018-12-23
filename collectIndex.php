<?php
	
	if(isset($_GET['id'])){
		$id = 'collect/'.str_replace('/', '', $_GET['id']);
		if(file_exists("$id.php")){
			$cachefile = "$id.html";
			if(file_exists("$id.html") &&  (time() - filemtime($cachefile)) < 7*3600){
				die(file_get_contents($cachefile));
			}
			include("$id.php");
			global $wpdb;
		
			$sql = $wpdb->prepare("select * from {$wpdb->prefix}media_resource r,{$wpdb->prefix}media_channel  c where  r.channel_id=c.channel_id and c.channel_code = %s order by pub_time desc,r.create_time desc limit 120", $_GET['id']);
 			$rows=$wpdb->get_results($sql);
			ob_start();
			foreach($rows as $row){
				//echo "{$row->pub_time}";
				$pub_time=date('Y-m-d',strtotime($row->pub_time));
				echo "<li><span>{$row->channel_title}</span><a href='{$row->link}'>{$row->title}</a><span>{$pub_time}</span><img src='{$row->conver_url}'/></li>\n";
			}

			$content = ob_get_contents();

			$fp = fopen($cachefile, 'w');
			fwrite($fp,$content);
			fclose($fp);
		}

	};

	