<?php
//lyoHMurdSaNyE2X2HyCkr/uGVaw1OGQy
//123456
$saved = "lyoHMurdSaNyE2X2HyCkr/uGVaw1OGQy";
$password = "123456";
$svd=base64_decode($saved); // base64_decode — 对使用 MIME base64 编码的数据进行解码
echo $svd."<br>";
	$salt=substr($svd,20); //substr — 返回字符串的子串 
	echo $salt."<br>";
	$password=md5($password);
	echo $password."<br>";
	echo sha1(($password) . $salt, true)."<br>";
	$hash = base64_encode( sha1(($password) . $salt, true) . $salt );
	echo $hash."<br>";
	if (strcmp($hash,$saved)==0) echo "True<br>";
	else echo "False<br>";
?>