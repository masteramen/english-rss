<?php
    ini_set("error_reporting", E_ALL);
     error_reporting(E_ALL);

include_once('yt-base.php');

try{


    
    $url = $_GET['url'];

    //$url = "http://www.youtube.com/watch?v=WZLKw_5d_oQ";
     if(!getYoutubeId($url)){
        print_log(1,false,'valid url');
     }else print_log(1,true,'valid url');

    require_once('./curl.php');


    $next = 'http://convert2mp3.net/en/index.php?url='.urlencode( $url );
    $content = curl_get($next);
    $rand='';


     if(preg_match('/var _0x2879 = (\d+);.*?var _0xa3bd = (\d+)/s', $content, $m)){

        $rand =$m[1]+$m[2];
        print_log(2,true,'get rand');
    }else{
        print_log(2,false,'get rand');

    }


    $content = curl_get('http://convert2mp3.net/en/index.php?p=convert',true,array('url'=>$url,'format'=>'mp3','quality'=>1,'9di2n3'=>$rand));
    //print_log($content);
    //$obj = json_decode($content);

    //videoID, key, cs, format
    //convert("youtube_WZLKw_5d_oQ", "LSZyHy9c0enb", "9", "mp3");
    $converurl='';

/*
<iframe id="convertFrame" src="http://c-api4.convert2mp3.net/conversion.php?id=youtube_vr7cZ9sIk8w&key=LZN3d12v0AEU&hash=400d646657d697b4b3f9e4d6966e7493&time=1525597130&lang=en&client_id=Chu639med56yixo58r" style="width: 100%; height: 200px; border: 0px; text-align: center;"></iframe>
*/
   if(preg_match('/<iframe id="convertFrame" src="(.*?)".*?>/', $content, $m)){


        print_log(3,true,'get conversion url');

        $converurl = $m[1];
        
        //print_log($m);
        //print_log($converurl);

        $conver_content = curl_get($converurl,false);
        //print_log($conver_content);

           if(preg_match_all('/window.parent.location.href = "(.*?)";/', $conver_content, $m)){
                print_log(4,true,'get detail url');
                $detail_url = $m[1][0];
                //print_log($detail_url);

            }else{
                print_log(4,false,'get detail url');
                print_r($conver_content);

            }

         


   }else{
   // print_r($content);
    print_log(3,false,'get conversion url');
   }

    

    if(preg_match_all('/convert\("(.*?)", "(.*?)", "(.*?)", "(.*?)"\);/', $content, $m)){
        //print_log($m);
        print_log(5,true,'get id,key');

        $videoID=$m[1][0];
        $key =$m[2][0];
        $cs =$m[3][0];
        $format =$m[4][0];
        $t=time()*1000;
        $trycount=0;
        do{
            sleep(3);

            $o = "";
            foreach (array('id'=>$videoID,'key'=>$key,'cs'=>$cs,'time'=> $t) as $k => $v ) 
            { 
                $o.= "$k=" . urlencode( $v ). "&" ;
            }
            $t=$t+1000;

            $url = 'http://convert2mp3.net/en/status.php?'.substr($o,0,-1);
           // echo "\n$url\n";
            $content = curl_get($url,false);
            // 1 => preparing video download, 2 => downloading video , 3 => converting video, please wait
            print_log(6,true,'get status');

            if($content[0]=='3'||$content[0]=='4'){

                if($content[0]=='3'){
                        $detail_content = curl_get($detail_url,false);
                        if(preg_match_all('/<audio src="(.*?)" preload="none"><\/audio>/', $detail_content, $dm)){

                            print_log(7,true,'get audio url');
                            $audio = $dm[1][0];


                           // ok_url($dm[1][0]);

                        }else{
                            print_log(7,false,'get audio url');
                        }


                }
                //
                break;
            }
            if(++$trycount> 5)break;

        }while(true);
 
    

        //http://convert2mp3.net/en/index.php?p=tags&id=youtube_WZLKw_5d_oQ&key=peHYPEv4JUXa

    }else{
        print_log(5,false,'get id,key');
    }

}catch(Exception $e){


}
return output_result(true);

/*

Please wait, while we convert your video.
http://c-api9.convert2mp3.net/conversion.php?id=youtube_WZLKw_5d_oQ&key=ooGojK1Sfsen&hash=62d1940d0bd64bc03323e267c3bc8db7&time=1525584178&lang=en&client_id=Chu639med56yixo58r

convert("youtube_WZLKw_5d_oQ", "ooGojK1Sfsen", "9", "mp3");



                        var _0x2879 = 42;
                         var _0xa3bd = 33598328 + _0x2879;
                        var _0xcf59=["\x31\x32\x33\x34\x35","\x3C\x69\x6E\x70\x75\x74\x20\x74\x79\x70\x65\x3D\x22\x68\x69\x64\x64\x65\x6E\x22\x20\x6E\x61\x6D\x65\x3D\x22\x39\x64\x69\x32\x6E\x33\x22\x20\x76\x61\x6C\x75\x65\x3D\x22","\x22\x3E","\x61\x70\x70\x65\x6E\x64","\x23\x63\x6F\x6E\x76\x65\x72\x74\x46\x6F\x72\x6D"];$(_0xcf59[4])[_0xcf59[3]](_0xcf59[1]+ _0xa3bd+ _0xcf59[2]);
                     
http://convert2mp3.net/en/index.php?p=tags&id=youtube_WZLKw_5d_oQ&key=ooGojK1Sfsen
http://convert2mp3.net/en/index.php?p=complete&id=youtube_WZLKw_5d_oQ&key=ooGojK1Sfsen
function getStatus(videoID, key, cs, format) {
    if (ready === true) {
        ready = false;
        var timestamp = (new Date).getTime();
        $.ajax({
            type: "GET",
            url: "status.php",
            data: {
                id: videoID,
                key: key,
                cs: cs,
                time: timestamp
            },
            success: function(response) {
                var result = response.split(":");
                var status = "";
                if (result[0] == "0") {
                    status = lng_status1
                } else if (result[0] == "1") {
                    status = lng_status2;
                    if (result.length > 0) status += " (" + result[1] + ")"
                } else if (result[0] == "2") {
                    setProgress(100);
                    document.title = "converting - convert2mp3.net - Online Video Converter";
                    status = lng_status3
                } else if (result[0] == "3") {
                    completeVideo(videoID, key, format)
                } else if (result[0] == "4") {
                    if (response.length > 0) status = result[1];
                    error(videoID, status)
                }
                if (result[1] != undefined) {
                    var str = result[1];
                    var progress = str.replace("%", "");
                    progressint = parseInt(progress);
                    setProgress(progressint)
                }
                $("#status").text(status);
                ready = true
            }
        })
    }*/
