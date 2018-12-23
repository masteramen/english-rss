<?php 
/*
donwload image file
*/

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
		
		$voaUrl = 'http://gandalf.ddo.jp/';



		$file = 'rss-voa.xml';
		if(!file_exists($file) || (time() - filemtime($file)> 10800) ){
			$content = curl_get_content($voaUrl);
			if(preg_match_all('/https:\/\/gandalf.ddo.jp\/html\/(\d+).html/i', $content, $ms)){
				require_once('./rss.class.php');
				$feed = new RSS();

				$feed->title       = "VOA News";
				$feed->link        = "https://gandalf.ddo.jp/";
				$feed->description = "VOA News";
					foreach ($ms[1] as $value) {
					$item = new RSSItem();
					$item->title = 'VOA News';
					$item->link  = "https://gandalf.ddo.jp/html/${value}.html";
					$item->setPubDate(time()); 
					$item->description = "<![CDATA[]]>";
					$feed->addItem($item);
				}

				
				echo $feed->serve();
			}
		}


//  echo $content;
//}
?>
