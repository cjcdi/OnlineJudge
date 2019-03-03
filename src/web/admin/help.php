<?php 
  require_once("admin-header.php");
	if(isset($OJ_LANG)){
		require_once("../lang/$OJ_LANG.php");
	}
?>
<html>
  <head>
    <title><?php echo $MSG_ADMIN?></title>
  </head>
<body>
<hr>
<h4>
<ul>
  <li><b><?php echo $MSG_SEEOJ?></b>: <?php echo $MSG_HELP_SEEOJ?><hr>
  <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <li><b><?php echo $MSG_SETMESSAGE?></b>: <?php echo $MSG_HELP_SETMESSAGE?><hr>
  <li><b><?php echo $MSG_NEWS.$MSG_LIST?></b>: <?php echo $MSG_HELP_NEWS_LIST?><hr>
  <li><b><?php echo $MSG_ADD.$MSG_NEWS?></b>: <?php echo $MSG_HELP_ADD_NEWS?><hr>
  <li><b><?php echo $MSG_USER.$MSG_LIST?></b>: <?php echo $MSG_HELP_USER_LIST?><hr>
  <li><b><?php echo $MSG_SET_LOGIN_IP?></b>: <?php echo $MSG_SET_LOGIN_IP?><hr>
  <?php }
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset( $_SESSION[$OJ_NAME.'_'.'password_setter'] )){?>
  <li><b><?php echo $MSG_SETPASSWORD?></b>: <?php echo $MSG_HELP_SETPASSWORD?><hr>
  <?php }
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <li><b><?php echo $MSG_GIVESOURCE?></b>: <?php echo $MSG_HELP_GIVESOURCE?><hr>
  <li><b><?php echo $MSG_PRIVILEGE.$MSG_LIST?></b>: <?php echo $MSG_HELP_PRIVILEGE_LIST?><hr>
  <li><b><?php echo $MSG_ADD.$MSG_PRIVILEGE?></b>: <?php echo $MSG_HELP_ADD_PRIVILEGE?><hr>
  <?php }
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){?>
  <li><b><?php echo $MSG_PROBLEM.$MSG_LIST?></b>: <?php echo $MSG_HELP_PROBLEM_LIST?><hr>
  <?php }
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){?>
  <li><b><?php echo $MSG_ADD.$MSG_PROBLEM?></b>: <?php echo $MSG_HELP_ADD_PROBLEM?><hr>
  <li>
      <b><?php echo $MSG_ADD."代码填空题"?></b>: <?php echo $MSG_HELP_ADD_PROBLEM?><hr>
  </li>
  <li>
      <b><?php echo $MSG_ADD."结果填空题"?></b>: <?php echo $MSG_HELP_ADD_PROBLEM?><hr>
  </li>
  <?php }
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <li><b><?php echo $MSG_IMPORT.$MSG_PROBLEM?></b>: <?php echo $MSG_HELP_IMPORT_PROBLEM?><hr>
  <li><b><?php echo $MSG_EXPORT.$MSG_PROBLEM?></b>: <?php echo $MSG_HELP_EXPORT_PROBLEM?><hr>
  <?php }?>
  <!--li><a class='btn btn-success' href="https://github.com/zhblue/freeproblemset/" target="_blank"><b>FreeProblemSet</b></a-->
  <?php
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])){?>		
  <li><b><?php echo $MSG_CONTEST.$MSG_LIST?></b>: <?php echo $MSG_HELP_CONTEST_LIST?><hr>
  <li><b><?php echo $MSG_ADD.$MSG_CONTEST?></b>: <?php echo $MSG_HELP_ADD_CONTEST?><hr>
  <?php }
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <li><b><?php echo $MSG_TEAMGENERATOR?></b>: <?php echo $MSG_HELP_TEAMGENERATOR?><hr>
  <?php }
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <li><b><?php echo $MSG_REJUDGE?></b>: <?php echo $MSG_HELP_REJUDGE?><hr>
  <?php }?>
  <!--li><a class='btn btn-primary' href="https://github.com/zhblue/hustoj/" target="_blank"><b>HUSTOJ</b></a-->
  <?php
  if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <li><b><?php echo $MSG_UPDATE_DATABASE?></b>: <?php echo $MSG_HELP_UPDATE_DATABASE?><hr>
  <?php
  if (isset($OJ_ONLINE)&&$OJ_ONLINE){?>
  <li><b><?php echo $MSG_ONLINE?></b>: <?php echo $MSG_HELP_ONLINE?><hr>
  <?php } }?>
  <!--li><a class='btn btn-primary' href="http://tk.hustoj.com" target="_blank"><b>自助题库</b></a>
  <li><a class='btn btn-primary' href="http://shang.qq.com/wpa/qunwpa?idkey=d52c3b12ddaffb43420d308d39118fafe5313e271769277a5ac49a6fae63cf7a" target="_blank">手机QQ加官方群23361372</a-->
	
</ul>
<?php /*if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])&&!$OJ_SAE){
?>
	<!--a href="problem_copy.php" target="main" title="Create your own data"><font color="eeeeee">CopyProblem</font></a> <br-->
	<a href="problem_changeid.php" target="main" title="Danger,Use it on your own risk"><font color="eeeeee">ReOrderProblem</font></a>
<?php }*/
?>
<h4>
</body>
</html>
