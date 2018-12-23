<?php

    ini_set("error_reporting", E_ALL);
     error_reporting(E_ALL);

function curl_get($url,$ispost=false, $data = array()) {
	
	$refer=$url;
	for($i=0;$i<1;$i++)
	{
		//echo "\n$url\n";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		@curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11' );
		$headers = array(
				'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0',
				'Referer: $refer',
				'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
		);
		@curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
		

        if($ispost){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

		$result = curl_exec($ch);
		if(curl_exec($ch) === false){
    		echo 'Curl error: ' . curl_error($ch)."\n";
		}

		curl_close($ch);

		if($result)return $result;
		
		if(!empty($_POST['url']))
		{
			$refer=$_POST['url'];
		}
	}

	return $result;
}

function curl_download($source,$destination){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $source);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_SSLVERSION,3);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 
	//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$headers = array(
			'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0',
			'Referer: $refer',
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
	);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

	$file = fopen($destination, "w");

	curl_setopt($ch, CURLOPT_FILE, $file);

	$ok=curl_exec ($ch);
	$error = curl_error($ch); 
	print_r($error);
	//fclose($file);
	echo $ok;
  $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  if($http_status != 200) {
  	@unlink($destination);
  }
  curl_close($ch);

  return $ok;
}


$url= 'http://www.baidu.com/';
$url= 'https://s2.download-yt-mp3.top/download/HrH4RBJWooSaNpD1X6NFL7cISxa367DfpOmt5JO4VgozvMqcWk6In+AAECdoPzQI0a748SR1O9ctO0hDmVFd';

//$url= 'http://www.baidu.com';
echo $url;
curl_download($url,'test.txt');
//echo file_get_contents('test.txt');
die;
