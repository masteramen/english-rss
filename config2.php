<?php 

$source='config2.json';
$efile='data/'.$source;
if(!@file_exists($efile) || @filemtime($efile)<@filemtime($source)){
	require_once('./crypto.fn.php');
	$content = encrypt(file_get_contents($source));
	$obj = array();
	$obj['_e']=1;
	$obj['type']='json';
	$obj['content']= $content;
	$fp = fopen($efile, 'w');
	fwrite($fp,json_encode($obj));
	fclose($fp);
}
if(isset($_GET['callback'])){
	$callback = $_GET['callback'];
	echo $callback.'('.file_get_contents($efile).')';
}

