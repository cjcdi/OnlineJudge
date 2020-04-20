<?php
//lyoHMurdSaNyE2X2HyCkr/uGVaw1OGQy
//123456
var_dump(str_replace('//','/',str_replace(DIRECTORY_SEPARATOR,'/',$_SERVER["DOCUMENT_ROOT"])));

echo "<br>";
$tempsource="import java.util.Scanner;
import java.util.Vector;
 
public class Main {
    public static void main(String[] args) {
    	Vector a = new Vector();
		for(int i=1; i<=10; i++)
		{
			a.add(\"第\" + i + \"个孩子\");//赋值
		}
		for(;;)
		{
			if(a.size()==1) break;//剩下最后一个孩子
			//remove返回值为移除的元素,add把元素添加向量的末尾
			for(int k=0; k<2; k++)//先把前面的两个元素放在后面
				_fillProblem_;//填空
			a.remove(0);//再把第三个元素给删除了
		}
		System.out.println(a);
    }
}";

echo strpos($tempsource, "java");
$tempsource = substr_replace($tempsource, "c++", 7, 4);

echo $tempsource."<br>";
$_fillProblem_ = strstr($tempsource, '_fillProblem_');
$_fillProblem_ = substr($_fillProblem_, 0, 13);
echo $_fillProblem_."<br>";
$_fillProblem_=md5($_fillProblem_);
$salt = sha1(rand());
$salt = substr($salt, 0, 4);
$hash = base64_encode( sha1($_fillProblem_ . $salt, true) . $salt ); //base64_encode — 使用 MIME base64 对数据进行编码
echo $hash.strlen($hash)."<br>";
$tempsource = str_replace("_fillProblem_", $hash, $tempsource);
echo $tempsource."<br>";


$saved = "vffTMEOo0m5nRBIAb89PQMiGxKYwZjVh";
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