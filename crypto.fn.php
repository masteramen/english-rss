<?php

$privateKey="@12345678912345!";
$iv="@12345678912345!";
function encrypt($data){
	global $privateKey,$iv;

	//加密
	$encrypted=@mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$privateKey,$data,MCRYPT_MODE_CBC,$iv);
	return base64_encode($encrypted);	
}
function decrypt($data){
	//解密
global $privateKey,$iv;
$encryptedData=base64_decode($data);
$decrypted=@mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$privateKey,$encryptedData,MCRYPT_MODE_CBC,$iv);
$decrypted=rtrim($decrypted,"\0");//注意！解密出来的数据后面会出现六个红点；这句代码可以处理掉，从而不影响进一步的数据操作
return $decrypted;
}

//echo encrypt(file_get_contents('xml/http__wwwkekenetcom_broadcast_201803_545389shtml.xml'));
//$str =<<<EOF
//EOF
//echo decrypt($str);


