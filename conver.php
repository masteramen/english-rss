<?php
    ini_set("error_reporting", E_ALL);
     error_reporting(E_ALL);

if(!isset($_GET['url']) && isset($_GET['q'])){
   ob_start();
  $videoId = include('videoId.php');
  ob_end_clean();
  if(isset($videoId) && !empty($videoId)){
      $_GET['url']='http://www.youtube.com/watch?v='.$videoId;
  }
}
include_once('yt-base.php');
$videoId = getYoutubeId($_GET['url']);
if(!$videoId){
        header("HTTP/1.0 404 Not Found");
        die;
}
$vfile = 'videoIds/'.$videoId;

if(file_exists($vfile) && (time()-filemtime($vfile)<=7200)){

  header("Location:".file_get_contents($vfile));
  die;
}
for($i=1;$i<10;$i++){
    $file = "./conver$i.php";
    if(file_exists($file)){
        ob_start();
       $result = include($file);
       ob_end_clean();
       
       if(isset($result) && !empty($result['audio'])){
            //echo json_encode($result);
            //echo $vfile;
            $fp = fopen($vfile, 'w');
            fwrite($fp,$result['audio']);
            fclose($fp);
           // print_r($result);
            //die($result['audio']);

            header("Location:".$result['audio']);
            die;
       }
    }else {
        header("HTTP/1.0 404 Not Found");
        echo "no found!";
        die;
    }

}
