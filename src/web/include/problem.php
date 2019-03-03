<?php
	function addproblem($user_id, $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj,$OJ_DATA) {
		//	$spj=($spj);
		$sql = "INSERT into `problem` (`user_id`,`title`,`time_limit`,`memory_limit`,
		`description`,`input`,`output`,`sample_input`,`sample_output`,`hint`,`source`,`spj`,`in_date`,`defunct`)
		VALUES(?,?,?,?,?,?,?,?,?,?,?,?,NOW(),'Y')";
		//echo $sql;
		$pid =pdo_query( $sql,$user_id,$title,$time_limit,$memory_limit,$description,$input,$output,
				$sample_input,$sample_output,$hint,$source,$spj ) ;
		echo "<br>Add $pid  ";
		if (isset ( $_POST ['contest_id'] ) && $_POST ['contest_id'] != "") {
			$cid =intval($_POST ['contest_id']);
			$sql = "select count(*) FROM `contest_problem` WHERE `contest_id`=?";
			$result = pdo_query( $sql,$cid ) ;
			$row =$result[0];
			$num = $row [0];
			echo "Num=" . $num . ":";
			$sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES(?,?,?)";	
			pdo_query($sql,$pid,$cid,$num);
		}
		/*$basedir = "$OJ_DATA/$pid";
		if(!isset($OJ_SAE)||!$OJ_SAE){
	//			echo "[$title]data in $basedir";
		}*/
		return $pid;
	}

	function mkdata($pid,$filename,$input,$OJ_DATA){
		
		$basedir = "$OJ_DATA/$pid";
		
		$fp = @fopen ( $basedir . "/$filename", "w" ); //"w"打开只写文件，若文件存在则文件长度清为0，即该文件内容会消失。若文件不存在则建立该文件。
		if($fp){
			fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) ); //fwrite() 把 string 的内容写入文件指针 handle 处。
			fclose ( $fp );
		}else{
			echo "Error while opening".$basedir . "/$filename ,try [chgrp -R www-data $OJ_DATA] and [chmod -R 771 $OJ_DATA ] ";
		}
	}

	function addproblem_fill_0($user_id, $title, $time_limit, $memory_limit, $problem_flag, $description, $problem_tempcode, $language, $tempsource, $fillmd5, $sample_input, $sample_output, $hint, $source, $spj, $OJ_DATA) {
		//	$spj=($spj);
		$sql = "INSERT into `problem` (`user_id`,`title`,`time_limit`,`memory_limit`,`description`,`sample_input`,`sample_output`,`hint`,`source`,`spj`,`in_date`,`defunct`) VALUES(?,?,?,?,?,?,?,?,?,?,NOW(),'Y')";
		//echo $sql;
		$pid = pdo_query( $sql,$user_id,$title,$time_limit,$memory_limit,$description,$sample_input,$sample_output,$hint,$source,$spj ) ;
		$sql = "INSERT into `problem_fill` (`problem_id`,`problem_flag`,`problem_tempcode`,`language`,`problem_tempsource`,`fillmd5`) VALUES(?,?,?,?,?,?)";
		//echo $sql;
		$cnt = pdo_query( $sql,$pid, $problem_flag, $problem_tempcode, $language, $tempsource, $fillmd5);
		echo "<br>Add $pid  ";
		if (isset ( $_POST ['contest_id'] ) && $_POST ['contest_id'] != "") {
			$cid =intval($_POST ['contest_id']);
			$sql = "select count(*) FROM `contest_problem` WHERE `contest_id`=?";
			$result = pdo_query( $sql,$cid ) ;
			$row =$result[0];
			$num = $row [0];
			echo "Num=" . $num . ":";
			$sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES(?,?,?)";	
			pdo_query($sql,$pid,$cid,$num);
		}
		/*$basedir = "$OJ_DATA/$pid";
		if(!isset($OJ_SAE)||!$OJ_SAE){
	//			echo "[$title]data in $basedir";
		}*/
		return $pid;
	}

	function addproblem_fill_1($user_id, $title, $time_limit, $memory_limit, $problem_flag, $description, $output, $problem_answer, $hint, $source, $spj) {
		//	$spj=($spj);
		$sql = "INSERT into `problem` (`user_id`,`title`,`time_limit`,`memory_limit`,`description`,`output`,`hint`,`source`,`spj`,`in_date`,`defunct`) VALUES(?,?,?,?,?,?,?,?,?,NOW(),'Y')";
		//echo $sql;
		$pid =pdo_query( $sql,$user_id,$title,$time_limit,$memory_limit,$description,$output,$hint,$source,$spj ) ;
		$sql = "INSERT into `problem_fill` (`problem_id`,`problem_flag`,`problem_answer`) VALUES(?,?,?)";
		//echo $sql;
		$cnt = pdo_query( $sql,$pid,$problem_flag,$problem_answer);
		echo "<br>Add $pid  ";
		if (isset ( $_POST ['contest_id'] ) && $_POST ['contest_id'] != "") {
			$cid =intval($_POST ['contest_id']);
			$sql = "select count(*) FROM `contest_problem` WHERE `contest_id`=?";
			$result = pdo_query( $sql,$cid ) ;
			$row =$result[0];
			$num = $row [0];
			echo "Num=" . $num . ":";
			$sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES(?,?,?)";	
			pdo_query($sql,$pid,$cid,$num);
		}
		/*$basedir = "$OJ_DATA/$pid";
		if(!isset($OJ_SAE)||!$OJ_SAE){
	//			echo "[$title]data in $basedir";
		}*/
		return $pid;
	}
?>
