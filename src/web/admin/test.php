<?php
$doc_root = str_replace('//','/',str_replace(DIRECTORY_SEPARATOR,'/',$_SERVER["DOCUMENT_ROOT"]));
$fm_self = $doc_root.$_SERVER["PHP_SELF"];
var_dump($path_info = pathinfo($fm_self));

	require_once ("../include/my_func.inc.php");
	$language_name=Array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Scheme","Clang","Clang++","Lua","JavaScript","Go","Other Language");
echo $_SESSION[$OJ_NAME.'_'.'postkey']."1<br>2".$_POST['postkey'];
	$language_ext=Array( "c", "cc", "pas", "java", "rb", "sh", "py", "php","pl", "cs","m","bas","scm","c","cc","lua","js","go" );

	$title = $_POST['title'];
	$title = str_replace(",", "&#44;", $title);
	$problem_tempcode = $_POST['problem_tempcode'];
	$description = $_POST['description'];
	$description = str_replace("<p>", "", $description); 
	$description = str_replace("</p>", "<br />", $description);
	$description = str_replace(",", "&#44;", $description);
	$problem_tempcode = str_replace("<p>", "", $problem_tempcode); 
	$problem_tempcode = str_replace("</p>", "<br />", $problem_tempcode);
	$problem_tempcode = str_replace(",", "&#44;", $problem_tempcode);
	$input = $_POST['input'];
	$input = str_replace("<p>", "", $input); 
	$input = str_replace("</p>", "<br />", $input); 
	$input = str_replace(",", "&#44;", $input);
	$output = $_POST['output'];
	$output = str_replace("<p>", "", $output); 
	$output = str_replace("</p>", "<br />", $output);
	$output = str_replace(",", "&#44;", $output); 
	$sample_input = $_POST['sample_input'];
	$sample_output = $_POST['sample_output'];
	$test_input = $_POST['test_input'];
	$test_output = $_POST['test_output'];
	$problem_answer = $_POST['problem_answer'];
	$problem_answer = str_replace("<p>", "", $problem_answer); 
	$problem_answer = str_replace("</p>", "<br />", $problem_answer); 
	$problem_answer = str_replace(",", "&#44;", $problem_answer);
	$hint = $_POST['hint'];
	$hint = str_replace("<p>", "", $hint); 
	$hint = str_replace("</p>", "<br />", $hint); 
	$hint = str_replace(",", "&#44;", $hint);
	$tempsource = $_POST['tempsource'];
	$source = $_POST['source'];
	$spj = $_POST['spj'];
	$time_limit = $_POST['time_limit'];
	$memory_limit = $_POST['memory_limit'];
	$problem_flag = $_POST['problem_flag'];
	if(get_magic_quotes_gpc()){
		$title = stripslashes($title);
		$time_limit = stripslashes($time_limit);
		$memory_limit = stripslashes($memory_limit);
		$description = stripslashes($description);
		$problem_tempcode = stripslashes($problem_tempcode);
		$tempsource=stripslashes($tempsource);
		$input = stripslashes($input);
		$output = stripslashes($output);
		$sample_input = stripslashes($sample_input);
		$sample_output = stripslashes($sample_output);
		$test_input = stripslashes($test_input);
		$test_output = stripslashes($test_output);
		$hint = stripslashes($hint);
		$spj = stripslashes($spj);
	}
	$title = RemoveXSS($title);
	$description = RemoveXSS($description);
	$problem_tempcode = RemoveXSS($problem_tempcode);
	$input = RemoveXSS($input);
	$output = RemoveXSS($output);
	$hint = RemoveXSS($hint);

	echo $_POST['problem_id']."<br>";
	echo $title."<br>";
	echo $time_limit."<br>";
	echo $memory_limit."<br>";
	echo $description."<br>";
	echo $output."<br>";
	echo $problem_tempcode."<br>";
	echo $_POST['language']."<br>";
	//echo $tempsource."<br>";
	echo $sample_input."<br>";
	echo $sample_output."<br>";
	echo $test_input."<br>";
	echo $test_output."<br>";
	echo $hint."<br>";
	echo $spj."<br>";
	echo $source."<br>";
	echo $problem_flag."<br>";
	echo $problem_answer."<br>";
	echo $_POST['contest_id']."<br>";

echo "<pre>".htmlentities(str_replace("\n\r","\n",$tempsource),ENT_QUOTES,"utf-8")."\n"."\tProblem: ".$_POST['problem_id']."\n"."\tLanguage: ".$language_name[$_POST['language']]."\n"."</pre>";
?>
