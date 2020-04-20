<?php
	require_once("../include/db_info.inc.php");
	function problem_exist($pid,$cid){
		if ($pid=='') $pid=0;
		if ($cid!='') //""、0、"0"、NULL、FALSE、array()、var $var; 以及没有任何属性的对象都将被认为是空的
			$cid=intval($cid);
		else
			$cid='NULL';

		if($pid!=0)
			if($cid!='NULL')
				$sql="SELECT 1 FROM `contest_problem` WHERE `contest_id` = $cid AND `problem_id` = '".intval($pid)."'";
			else
				$sql="SELECT 1 FROM `problem` WHERE `problem_id` = ".intval($pid).""; //判断数据库里面是否有该题目
		else if($cid!='NULL')
			$sql="SELECT 1 FROM `contest` WHERE `contest_id` = $cid";
		else
			return false; //pid=0,cid=null 数据库里面没有问题和竞赛就不给在讨论版讨论

		$sql.=" LIMIT 1";
		//echo $sql;
		$result=pdo_query($sql) or print "db error";
		return count($result)>0; //大于0返回真，小于0返回假
	}

	function err_msg($msg){
	        $view_errors= "$msg";
	        require("template/".$OJ_TEMPLATE."/error.php");
	        exit(0);
	}
?>
