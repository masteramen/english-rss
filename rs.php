<?php 
/*
donwload image file
*/
function urlsafe_b64encode($string) {
   $data = base64_encode($string);
   $data = str_replace(array('+','/','='),array('-','_',''),$data);
   return $data;
}
function urlsafe_b64decode($string) {
   $data = str_replace(array('-','_'),array('+','/'),$string);
   $mod4 = strlen($data) % 4;
   if ($mod4) {
       $data .= substr('====', $mod4);
   }
   return base64_decode($data);
}
		function curl_get_content($url) {
			
			$refer=$url;
			for($i=0;$i<2;$i++)
			{
				$ch = curl_init();
				//curl_setopt($ch, CURLOPT_REFERER, $refer);
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
				@curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
				//curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				
				
				$result = curl_exec($ch);
				curl_error($ch);
				curl_close($ch);
				
				if($result)return $result;
				
				if(!empty($_POST['url']))
				{
					$refer=$_POST['url'];
				}
			}

			return $result;
		}
		
		
					$fp = fopen('test.txt', 'a');
				   	fwrite($fp, time()."\n");

				   foreach ($_SERVER as $name => $value) 
				   { 
					   //if (substr($name, 0, 5) == 'HTTP_') 
					   { 
						   fwrite($fp, $value."\n");

					   } 
				   } 
					fclose($fp);
					
			// echo urlsafe_b64encode('http://feed43.com/8753402584161724.xml');
			//die;
			$url = urlsafe_b64decode($_GET['u']);

			//header('location:http://www.kekenet.com/broadcast/201803/545317.shtml');
								die;

			//header("location:$url");
		$feeUrl = $url;
		//http://localhost/rs.php?u=aHR0cDovL3d3dy5rZWtlbmV0LmNvbS9icm9hZGNhc3QvMjAxODAzLzU0NTMxNy5zaHRtbA
		//$feeUrl='http://www.kekenet.com/broadcast/201803/545317.shtml';
		//http://localhost/rs.php?u=aHR0cDovL2ZlZWQ0My5jb20vODc1MzQwMjU4NDE2MTcyNC54bWw
		if(!empty($feeUrl)){
			$file =  str_replace(array('+','/','=','.',':'),array('_','_','','',''),$feeUrl).'.txt';
			//if(!file_exists($file) || (time() - filemtime($file)> 10800000) ){
				$content = curl_get_content($feeUrl);
				if(preg_match_all('/<link>(.*?)<\/link/i', $content, $ms)){
					$content = $ms[1][1];
					$content = curl_get_content($content);

					$fp = fopen($file, 'w');
					fwrite($fp, $content);
					fclose($fp);
				}
			//}
			//header('location:'.file_get_contents($file));
			echo file_get_contents($file);
			die();
		}



//  echo $content;
//}
?>
