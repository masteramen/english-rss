<?php    

/*

function charCodeAt($str, $index)
{
    $char = mb_substr($str, $index, 1, 'UTF-8');
 
    if (mb_check_encoding($char, 'UTF-8'))
    {
        $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
        return hexdec(bin2hex($ret));
    }
    else
    {
        return null;
    }
}
*/
/*
* rc4加密算法
* $pwd 密钥
* $data 要加密的数据
*/
/*
function RC4($pwd, $data) {
    $cipher      = '';
    $key[]       = "";
    $box[]       = "";
    $pwd_length  = strlen($pwd);
    $data_length = strlen($data);
    for ($i = 0; $i < 256; $i++) {
        $key[$i] = ord($pwd[$i % $pwd_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j       = ($j + $box[$i] + $key[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $data_length; $i++) {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $k       = $box[(($box[$a] + $box[$j]) % 256)];
        $cipher .= chr(ord($data[$i]) ^ $k);
    }


    return $cipher;
}

function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

function unescape($str)
{
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i ++)
    {
        if ($str[$i] == '%' && $str[$i + 1] == 'u')
        {
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f)
                $ret .= chr($val);
            else
                if ($val < 0x800)
                    $ret .= chr(0xc0 | ($val >> 6)) .
                     chr(0x80 | ($val & 0x3f));
                else
                    $ret .= chr(0xe0 | ($val >> 12)) .
                     chr(0x80 | (($val >> 6) & 0x3f)) .
                     chr(0x80 | ($val & 0x3f));
            $i += 5;
        } else
            if ($str[$i] == '%')
            {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else
                $ret .= $str[$i];
    }
    return $ret;
}

function EnLink($type, $video_id){
        //var url = window.location.href || document.URL;
        
       // var regex = /fr\/search\//;         
        
        return (false ? '/fr' : '') 
        . '/download/'
        . base64_encode(
        	RC4("dl-youtube-mp3-download",
	        	unescape(
		        	encodeURIComponent(
		        	rand(0,100000).'|'.$type.'|'.$video_id.'|'.(time()+3600)*1000
		        	)
	        	)
        	)
        );  
    }
*/
    


		$domain="https://check.download-yt-mp3.top";



        if(preg_match_all( '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=|\?v=)([^#&?]*).*/', $_GET['q'],$result)){
        	//print_r($result);
        	 $url = '/video/' . $result[2][0] ;

		}else
		{
			 echo $domain;

			$url = '/keyword/' . urlencode($_GET['q']);
		}
		//echo $domain.$url;
        
        require_once('./curl.php');
		$content = curl_get($domain.$url);
		$obj = json_decode($content);
        //http://www.youtube.com/watch?v=juKd26qkNAw
        return $obj[0]->id->videoId;	