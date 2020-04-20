<?php
////////////////////////////Common head
$cache_time = 30; //缓存时间内是否存在你缓存文件
$OJ_CACHE_SHARE = true; //判断用户是否登陆
require_once( './include/cache_start.php' ); //设置缓存文件，每个ip下的每个用户一个缓存文件，没有登陆就是每个ip一个缓存文件
require_once( './include/db_info.inc.php' ); //设置大量全局变量，即一个配置文件
require_once( './include/memcache.php' ); //进行缓存
require_once( './include/setlang.php' ); //设置语言，国际化
$view_title = "Welcome To Online Judge";
$result = false; //存放数据库查询结果
if ( isset( $OJ_ON_SITE_CONTEST_ID ) ) {
	header( "location:contest.php?cid=" . $OJ_ON_SITE_CONTEST_ID );
	exit();
}

/*-----------------MAIN----------------------------------------------------*/
$view_news = ""; //存放数据库中新闻的结果
//搜索最新的50条没有被屏蔽的新闻
$sql = "select * "
. "FROM `news` "
. "WHERE `defunct`!='Y'" //新闻没有被屏蔽
. "ORDER BY `importance` ASC,`time` DESC " //按照关键字升序和更新时间降序排序
. "LIMIT 50";
$result = mysql_query_cache( $sql ); //mysql_escape_string($sql)); //查询结果放入result
//设置首页的新闻模块
if ( !$result ) {
	$view_news = "<h3>No News Now!</h3>";
} else {
	$view_news .= "<div class='panel panel-default' style='width:80%;margin:0 auto;'>"; //class 使用的是bootstrap的面板
	$view_news .= "<div class='panel-heading'><h3>" . $MSG_NEWS . "<h3></div>"; //$MSG_NEWS = "News";
	$view_news .= "<div class='panel-body'>";
/*遍历二维数组通用代码
foreach ($result as $k => $v) {
    echo $k . '<br>';
    print_r($v);
    echo '<br>';
    foreach ($result[$k] as $index => $value) {
        echo $index . '<br>';
        echo $value . '<br><br>';
    }
}
*/
	foreach ( $result as $row ) {
/*一维数组遍历键值对
foreach($row as $n=>$v)
{
    echo $n . "=" . $v . "<br>";
}
*/
		$view_news .= "<div class='panel panel-default'>";
		$view_news .= "<div class='panel-heading'><big>" . $row[ 'title' ] . "</big>-<small>" . $row[ 'user_id' ] . "</small></div>";
		$view_news .= "<div class='panel-body'>" . $row[ 'content' ] . "</div>";
		$view_news .= "</div>";
	}
	$view_news .= "</div>";
	//$view_news .= "<div class='panel-footer'>This <a href=http://cm.baylor.edu/welcome.icpc>ACM/ICPC</a> OnlineJudge is a GPL product from <a href=https://github.com/zhblue/hustoj>hustoj</a></div>";
	$view_news .= "</div>";
}

/*-----------------首页中间的图表数据---------------------------*/
$view_apc_info = "";
// 寻找有效的提交数
$sql = "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM (select * from solution order by solution_id desc limit 8000) solution  where result<13 group by md order by md desc limit 200";
$result = mysql_query_cache( $sql ); //mysql_escape_string($sql));
$chart_data_all = array(); //新建一个数组 
//存入变量chart_data_all
foreach ( $result as $row ) {
	array_push( $chart_data_all, array( $row[ 'md' ], $row[ 'c' ] ) ); //将一个或多个单元压入数组的末尾（入栈） 
}

//寻找有效的AC数
$sql = "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM  (select * from solution order by solution_id desc limit 8000) solution where result=4 group by md order by md desc limit 200"; 
$result = mysql_query_cache( $sql ); //mysql_escape_string($sql));
$chart_data_ac = array();
//存入变量chart_data_ac
foreach ( $result as $row ) {
	array_push( $chart_data_ac, array( $row[ 'md' ], $row[ 'c' ] ) );
}

//假如已经登陆
if ( isset( $_SESSION[ $OJ_NAME . '_' . 'administrator' ] ) ) {
	$sql = "select avg(sp) sp from (select  avg(1) sp,judgetime from solution where result>3 and judgetime>date_sub(now(),interval 1 hour)  group by (judgetime DIV 60 * 60) order by sp) tt;";
	$result = mysql_query_cache( $sql );
	$speed = ( $result[ 0 ][ 0 ] ? $result[ 0 ][ 0 ] : 0 ) . '/min';
} else {
	$speed = ( $chart_data_all[ 0 ][ 1 ] ? $chart_data_all[ 0 ][ 1 ] : 0 ) . '/day';
}

/////////////////////////Template
require( "template/" . $OJ_TEMPLATE . "/index.php" );
/////////////////////////Common foot
if ( file_exists( './include/cache_end.php' ) ) //是否使用缓存文件缓存网页
	require_once( './include/cache_end.php' );
?>