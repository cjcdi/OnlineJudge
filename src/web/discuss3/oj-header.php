<?php 
	require_once('../include/db_info.inc.php');
	require_once('../include/setlang.php');
	require_once('../include/const.inc.php'); //静态常量数组文件
	if(isset($_GET['tid'])&&!isset($_GET['cid'])){//设置了帖子id却没找到竞赛id
		$tid=intval($_GET['tid']);
		$cid=pdo_query("select cid from topic where tid=?",$tid)[0][0];
		if($cid>0) $_GET['cid']=$cid;
	}
    ob_start(); //打开输出控制缓冲缓存所有输出的数据
?>
