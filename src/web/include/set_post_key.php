<?php 
	$_SESSION[$OJ_NAME.'_'.'postkey']=strtoupper(substr(MD5($_SESSION[$OJ_NAME.'_'.'user_id'].rand(0,9999999)),0,10)); 
	//strtoupper — 将字符串转化为大写
?>
<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
