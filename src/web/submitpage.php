<?php
    require_once('./include/db_info.inc.php');
    require_once('./include/const.inc.php');
    require_once('./include/memcache.php');
	require_once('./include/setlang.php');
	$view_title=$MSG_SUBMIT;
	if (!isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
		$view_errors= "<a href=loginpage.php>$MSG_Login</a>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	//	$_SESSION[$OJ_NAME.'_'.'user_id']="Guest";
	}
	//查找问题的输入参照和输出参照以及问题号
	$problem_id=1000;
	if (isset($_GET['id'])){
		$id=intval($_GET['id']);
	    $sample_sql="SELECT sample_input,sample_output,problem_id from problem where problem_id=?";
	}else if (isset($_GET['cid']) && isset($_GET['pid'])){
		$cid=intval($_GET['cid']);
		$pid=intval($_GET['pid']);
		$psql="SELECT problem_id from contest_problem where contest_id=? and num=?";
		$problem_id=pdo_query($psql,$cid,$pid)[0][0];
		$sample_sql="SELECT p.sample_input, p.sample_output, p.problem_id FROM problem p where problem_id = ? ";
	}else{
		$view_errors="<h2>No Such Problem!</h2>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	}
	if(isset($id)) $problem_id=$id;
	$view_sample_input="1 2";
	$view_sample_output="3";
	if(isset($sample_sql)){
		//echo $sample_sql;
		if (isset($_GET['id'])){
			$result=pdo_query($sample_sql,$id);
		}else{
			$result=pdo_query($sample_sql,$problem_id);
		}
		if($result == false)
		{
			$view_errors=  "<h2>No Such Problem!</h2>";
			require("template/".$OJ_TEMPLATE."/error.php");
			exit(0);
		}
		$row=$result[0];
		$view_sample_input=$row[0];
		$view_sample_output=$row[1];
		$problem_id=$row[2];
	}
	//echo $problem_id."<br>";
	$sql="SELECT `problem_flag` FROM `problem_fill` WHERE `problem_id`=?";
	$result=pdo_query($sql,$problem_id);
	$row=$result[0];
	if($row) $problem_flag = $row['problem_flag'];

	//假如从编辑代码跳转过来就把之前的代码自动填充上去
	$view_src="";
	if(isset($_GET['sid'])){ 
		$sid=intval($_GET['sid']);
		$sql="SELECT * FROM `solution` WHERE `solution_id`=?";
		$result=pdo_query($sql,$sid);
		$row=$result[0];
		if ($row && $row['user_id'] == $_SESSION[$OJ_NAME.'_'.'user_id']) $ok=true;
		if (isset($_SESSION[$OJ_NAME.'_'.'source_browser'])) {
			$ok=true;
		}else{
			if(isset($OJ_EXAM_CONTEST_ID)){
				if($cid<$OJ_EXAM_CONTEST_ID&&!isset($_SESSION[$OJ_NAME.'_'.'source_browser'])){
					header("Content-type: text/html; charset=utf-8");
					echo $MSG_SOURCE_NOT_ALLOWED_FOR_EXAM;
					exit();
				}
			}
		}
		if ($ok==true){
			$sql="SELECT * FROM `solution_fill` WHERE `solution_id`=?";
			$result=pdo_query($sql,$sid);
			$row=$result[0];
			if($row){
				//$problem_flag = $row['problem_flag'];
				$view_src = $row['solution_answer'];
			}else{
				$sql="SELECT `source` FROM `source_code_user` WHERE `solution_id`=?";
				$result=pdo_query($sql,$sid);
				$row=$result[0];
				if($row) $view_src=$row['source'];
			}
		}
	}

	//假如有临时文件,则存入代码
	if(!$view_src){
		if(isset($_COOKIE['lastlang'])) 
			$lastlang=intval($_COOKIE['lastlang']);
		else 
			$lastlang=0;
		$template_file="$OJ_DATA/$problem_id/template.".$language_ext[$lastlang];
		if(file_exists($template_file)){
			$view_src=file_get_contents($template_file);
		}
	}

	//等待判的题目和正在判的题目总数大于十就需要输入验证码，防止恶意提交
	$sql="SELECT count(1) FROM `solution` WHERE result<4";
	$result=mysql_query_cache($sql);
	$row=$result[0];
	if($row[0]>10) {
		$OJ_VCODE=true;
	//	$OJ_TEST_RUN=false;
	//	echo "$row[0]";
	}

	/////////////////////////Template
	if(isset($problem_flag) && ($problem_flag=="0" || $problem_flag=="1")){
		require("template/".$OJ_TEMPLATE."/submitpage_fill.php");
	}else{
		require("template/".$OJ_TEMPLATE."/submitpage.php");
	}
	/////////////////////////Common foot
?>

