<?php
// 这是连接数据库的主文件
function pdo_query($sql){
    $num_args = func_num_args(); //返回通过该函数（pdo_query）的参数数目，
    $args = func_get_args();       //获得传入的所有参数并组成数组
    $args=array_slice($args,1,--$num_args); //返回根据 offset 和 length 参数所指定的 array 数组中的一段序列。类似于一个一个语句执行
    global $DB_HOST,$DB_NAME,$DB_USER,$DB_PASS,$dbh,$OJ_SAE;
    if(!$dbh){ //假如没有连接数据库
			
		if(isset($OJ_SAE)&&$OJ_SAE)	{
			$OJ_DATA="saestor://data/";
		//  for sae.sina.com.cn
			$DB_NAME=SAE_MYSQL_DB;
			$dbh=new PDO("mysql:host=".SAE_MYSQL_HOST_M.';dbname='.SAE_MYSQL_DB, SAE_MYSQL_USER, SAE_MYSQL_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
		}else{
			$dbh=new PDO("mysql:host=".$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8")); //链接数据库语句，第四个参数解决中文乱码
		}
		
    }
   
    $sth = $dbh->prepare($sql); // Prepares a statement for execution and returns a statement object 
    $sth->execute($args); //  Executes a prepared statement 
    $result=array();
    if(stripos($sql,"select") === 0){ //查找字符串首次出现的位置（不区分大小写）
        $result=$sth->fetchAll(); // Returns an array containing all of the result set rows
    }else if(stripos($sql,"insert") === 0){
	    $result=$dbh->lastInsertId(); // Returns the ID of the last inserted row or sequence value 
    }else{
        $result=$sth->rowCount(); //Returns the number of rows affected by the last SQL statement 
    }
    //print($sql."<br>");
    $sth->closeCursor();
    return $result;
}
?>
