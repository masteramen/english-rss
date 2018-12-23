<?php
    $msg = '';
    $audio = '';

     function print_log($step,$ok_fail=true,$content=""){
        global $msg,$audio;
        $msg.=print_r("step $step ". ($ok_fail?'ok':'fail')."\n",true);
        $msg.=print_r($content,true);
        $msg.="\n";
        if(!$ok_fail){
            throw new Exception( $msg);
        }
     }
     function output_result($show=false){

        global $msg,$audio;
        if(!empty($audio))$msg='';
        $result = array('msg'=>$msg,'audio'=>$audio);
        if($show){
            echo json_encode($result);
        }
        return $result;

     }

     function getYoutubeId($url){
    $rx = '~
      ^(?:https?://)?                           # Optional protocol
       (?:www[.])?                              # Optional sub-domain
       (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
       ([^&]{11})                               # Video id of 11 characters as capture group 1
        ~x';

    $has_match = preg_match($rx, $url, $matches);

    return $has_match?$matches[1]:false;

     }

     function no_match($content){
        header("HTTP/1.0 404 Not Found");
        print_r($content);
        die;
     }