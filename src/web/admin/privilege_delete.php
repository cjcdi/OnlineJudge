<?php require_once("admin-header.php");?>
<?php require_once("../include/check_get_key.php");
if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
if(isset($_GET['uid'])){
	$user_id=$_GET['uid'];
	$rightstr =$_GET['rightstr'];
	$sql="SELECT 1 from `privilege` where user_id=? and rightstr=?";
	$row=pdo_query($sql,$user_id,$rightstr);
	$sql="DELETE from `privilege` where user_id=? and rightstr=?";
	$rows=pdo_query($sql,$user_id,$rightstr);
	if(count($row) > 1){
		$sql="INSERT INTO privilege(user_id,rightstr,defunct) VALUES(?,?,'N')";
		$rows=pdo_query($sql,$user_id,$rightstr);
	}
	//echo "$user_id $rightstr deleted!";
}
echo "<script language='javascript'>\n";
echo "alert('".(count($row)-1)." pieces of $user_id $rightstr deleted!');\n";
echo "history.go(-1);\n";
echo "</script>";
?>

<!--script language=javascript>
	alert("");
	window.setTimeOut(100,"history.go(-1)");
</script-->
