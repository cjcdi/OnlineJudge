<?php
  require_once ("admin-header.php");
  require_once("../include/check_post_key.php");//出问题
  if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
  require_once ("../include/db_info.inc.php");
  require_once ("../include/const.inc.php");
  require_once ("../include/my_func.inc.php");
  require_once ("../include/problem.php");
  // contest_id
  $user_id=$_SESSION[$OJ_NAME.'_'.'user_id'];
  $title = $_POST['title'];
  $title = str_replace(",", "&#44;", $title);
  $time_limit = $_POST['time_limit'];
  $memory_limit = $_POST['memory_limit'];
  $problem_flag = $_POST['problem_flag'];
  $description = $_POST['description'];
  $description = str_replace("<p>", "", $description); 
  $description = str_replace("</p>", "<br />", $description);
  $description = str_replace(",", "&#44;", $description); 
  $hint = $_POST['hint'];
  $hint = str_replace("<p>", "", $hint); 
  $hint = str_replace("</p>", "<br />", $hint); 
  $hint = str_replace(",", "&#44;", $hint);
  $source = $_POST['source'];
  $spj = $_POST['spj'];
  if(get_magic_quotes_gpc()){
    $user_id = stripcslashes($user_id);
    $title = stripslashes($title);
    $time_limit = stripslashes($time_limit);
    $memory_limit = stripslashes($memory_limit);
    $problem_flag = stripslashes($problem_flag);
    $description = stripslashes($description);
    // $input = stripslashes($input);
    $hint = stripslashes($hint);
    $source = stripslashes($source);
    $spj = stripslashes($spj);
  }
  $title = RemoveXSS($title);
  $description = RemoveXSS($description);
  //$input = RemoveXSS($input);
  $hint = RemoveXSS($hint);
  /*
  $input = $_POST['input'];
  $input = str_replace("<p>", "", $input); 
  $input = str_replace("</p>", "<br />", $input); 
  $input = str_replace(",", "&#44;", $input);
  */
  if("1" == $problem_flag){//结果填空
    $title.="（结果填空）";
    $problem_answer = $_POST['problem_answer'];
    $output = $_POST['output'];
    $output = str_replace("<p>", "", $output); 
    $output = str_replace("</p>", "<br />", $output);
    $output = str_replace(",", "&#44;", $output); 
    if(get_magic_quotes_gpc()){
      $output = stripslashes($output);
      $problem_answer = stripslashes($problem_answer);
    }
    $output = RemoveXSS($output);
    $pid = addproblem_fill_1($user_id, $title, $time_limit, $memory_limit, $problem_flag, $description, $output, $problem_answer, $hint, $source, $spj);
  }
  if(!strcmp("0",$problem_flag)){//代码填空
    $title.="（代码填空）";
    $problem_tempcode = $_POST['problem_tempcode'];
    $problem_tempcode = str_replace("<p>", "", $problem_tempcode); 
    $problem_tempcode = str_replace("</p>", "<br />", $problem_tempcode);
    $problem_tempcode = str_replace(",", "&#44;", $problem_tempcode);
    $sample_input = $_POST['sample_input'];
    $sample_output = $_POST['sample_output'];
    $test_input = $_POST['test_input'];
    $test_output = $_POST['test_output'];
    $language=intval($_POST['language']);
    if ($language>count($language_name) || $language<0) $language=0;
    $language=strval($language); //strval — 获取变量的字符串值
    setcookie('lastlang',$language,time()+360000); //设置cookie
    $tempsource=$_POST['tempsource'];
    if(get_magic_quotes_gpc()){
      $problem_tempcode = stripslashes($problem_tempcode);
      $sample_input = stripslashes($sample_input);
      $sample_output = stripslashes($sample_output);
      $test_input = stripslashes($test_input);
      $test_output = stripslashes($test_output);
      $tempsource=stripslashes($tempsource);
    }
    $problem_tempcode = RemoveXSS($problem_tempcode);
    if($language==6) $tempsource="# coding=utf-8\n".$tempsource;
    $_fillProblem_=md5("_fillProblem_");
    $salt = sha1(rand());
    $salt = substr($salt, 0, 4);
    $fillmd5 = base64_encode( sha1($_fillProblem_ . $salt, true) . $salt );
    $tempsource = str_replace("_fillProblem_", $fillmd5, $tempsource);
    if((~$OJ_LANGMASK)&(1<<$language)){
      $pid = addproblem_fill_0($user_id, $title, $time_limit, $memory_limit, $problem_flag, $description, $problem_tempcode, $language, $tempsource, $fillmd5, $sample_input, $sample_output, $hint, $source, $spj, $OJ_DATA);
      $basedir = "$OJ_DATA/$pid";
      mkdir($basedir);
      if(strlen($sample_output) && !strlen($sample_input)) $sample_input = "0";
      if(strlen($sample_input)) mkdata($pid, "sample.in", $sample_input, $OJ_DATA);
      if(strlen($sample_output)) mkdata($pid, "sample.out", $sample_output, $OJ_DATA);
      if(strlen($test_output) && !strlen($test_input)) $test_input = "0";
      if(strlen($test_input)) mkdata($pid,"test.in", $test_input, $OJ_DATA);
      if(strlen($test_output)) mkdata($pid,"test.out", $test_output, $OJ_DATA);
    }
  }
  $sql = "insert into `privilege` (`user_id`,`rightstr`) values(?,?)";
  pdo_query($sql, $_SESSION[$OJ_NAME.'_'.'user_id'], "p$pid");
  $_SESSION[$OJ_NAME.'_'."p$pid"] = true;
  echo "<a href='javascript:phpfm($pid);'>Add more TestData now!</a>";
?>

<script src='../template/bs3/jquery.min.js' ></script>
<script>
  function phpfm(pid){
    //alert(pid);
    $.post("phpfm.php",{'frame':3,'pid':pid,'pass':''},function(data,status){
      if(status=="success"){
        document.location.href="phpfm.php?frame=3&pid="+pid;
      }
    });
  }
</script>
