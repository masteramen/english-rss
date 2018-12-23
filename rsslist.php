<?php 
$efile='data/rsslist.xml';
$xml='rsslist.xml';
if(!file_exists($efile) || filemtime($efile)<filemtime($xml)){
	require_once('./crypto.fn.php');
	$content = encrypt(file_get_contents($xml));
	$fp = fopen($efile, 'w');
	fwrite($fp,$content);
	fclose($fp);
}
echo file_get_contents('data/rsslist.xml');