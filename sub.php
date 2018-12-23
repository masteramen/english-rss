<?php
    ini_set("error_reporting", E_ALL);
     error_reporting(E_ALL);

include_once('yt-base.php');




    
    $url = $_GET['url'];

    //$url = "http://www.youtube.com/watch?v=WZLKw_5d_oQ";
     if(!getYoutubeId($url)){
        print_log(1,false,'valid url');
     }else print_log(1,true,'valid url');

    require_once('./curl.php');


    $next = 'http://downsub.com/?url='.urlencode( $url );
    $content = curl_get($next,false);
    $rand='';


      if(preg_match('/\(auto\-generated\).*?Or translate from <b>English<\/b> to:/', $content,$m)){


        if(preg_match_all('/<b><a href="(.*?)">>>Download<<<\/a> <\/b>&nbsp;&nbsp;(.*?)<br>/',$m[0],$mm)){

            for($i=0;$i<count($mm[1]);$i++){
                print_r($mm[1][0]);
                print_r("\n");
                if($mm[2][0]=='English'){
                    die('http://downsub.com/'.$mm[1][0]);
                }
            }

        }else{
            print_r('no match');
        }

      }
      die;

     if(preg_match('/nbsp;&nbsp;Dutch<br><b><a href="(.*?)">>>Download<<<\/a> <\/b>&nbsp;&nbsp;English/s', $content, $m)){
        $suburl = 'http://downsub.com/'.$m[1];

        $content = curl_get($suburl,false);
        print_r($content);


    }else{

    }


  