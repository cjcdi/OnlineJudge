<html>
  <head>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Problem Add</title>
  </head>
  <hr>
  <?php 
    require_once("../include/db_info.inc.php");
    require_once('../include/const.inc.php');
    require_once("admin-header.php");
    if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))){
      echo "<a href='../loginpage.php'>Please Login First!</a>";
      exit(1);
    }
    echo "<center><h3>$MSG_ADD"."结果填空题</h3></center>";
    include_once("kindeditor.php") ;
    $path_fix = "../";
  ?>
  <!-- 新 Bootstrap 核心 CSS 文件 -->
  <link rel="stylesheet" href="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>bootstrap.min.css">
  <body leftmargin="30" >
    <div class="container">
      <form id="form2" name="form2" method="POST" action="problem_fill_add.php">
        <input type=hidden name=problem_id value="New Problem">
        <input type=hidden name=problem_flag value="1">
        <p align=left>
          <?php echo "<h3>".$MSG_TITLE."</h3>"?>
          <input class="input input-xxlarge" style="width:100%;" type=text name=title><br><br>
        </p>
        <p align=left>
          <?php echo $MSG_Time_Limit?><br>
          <input class="input input-mini" type=text name=time_limit size=20 value=1> Sec<br><br>
          <?php echo $MSG_Memory_Limit?><br>
          <input class="input input-mini" type=text name=memory_limit size=20 value=128> MB<br><br>
        </p>
        <p align=left>
          <?php echo "<h4>".$MSG_Description."</h4>"?>
          <textarea class="kindeditor" rows=20 name=description cols=80></textarea><br>
        </p>
        <!--p align=left>
          <?php echo "<h4>".$MSG_Input."</h4>"?>
          <textarea class="kindeditor" rows=13 name=input cols=80></textarea><br>
        </p-->
        <p align=left>
          <?php echo "<h4>".$MSG_Output."</h4>"?>
          <textarea  class="kindeditor" rows=13 name=output cols=80></textarea><br>
        </p>
        <!--p align=left>
          <?php echo "<h4>".$MSG_Sample_Input."</h4>"?>
          <textarea  class="input input-large" style="width:100%;" rows=13 name=sample_input></textarea><br><br>
        </p>
        <p align=left>
          <?php echo "<h4>".$MSG_Sample_Output."</h4>"?>
          <textarea  class="input input-large" style="width:100%;" rows=13 name=sample_output></textarea><br><br>
        </p>
        <p align=left>
          <?php echo "<h4>".$MSG_Test_Input."</h4>"?>
          <?php echo "(".$MSG_HELP_MORE_TESTDATA_LATER.")"?><br>
          <textarea class="input input-large" style="width:100%;" rows=13 name=test_input></textarea><br><br>
        </p>
        <p align=left>
          <?php echo "<h4>".$MSG_Test_Output."</h4>"?>
          <?php echo "(".$MSG_HELP_MORE_TESTDATA_LATER.")"?><br>
          <textarea class="input input-large" style="width:100%;" rows=13 name=test_output></textarea><br><br>
        </p-->
        <p align=left>
          <?php echo "<h4>"."正确答案"." [当有多个答案时，每个答案占一行--不要输出其他任何多余的字符，包括换行和空格]</h4>"?>
          <textarea  class="input input-large" style="width:100%;" rows=13 name=problem_answer></textarea>
        </p>
        <p align=left>
          <?php echo "<h4>".$MSG_HINT."</h4>"?>
          <textarea class="kindeditor" rows=13 name=hint cols=80></textarea><br>
        </p>
        <p>
          <?php echo "<h4>".$MSG_SPJ."</h4>"?>
          <?php echo "(".$MSG_HELP_SPJ.")"?><br>
          <?php echo "No "?><input type=radio name=spj value='0' checked><?php echo "/ Yes "?><input type=radio name=spj value='1'><br><br>
        </p>
        <p align=left>
          <?php echo "<h4>".$MSG_SOURCE."</h4>"?>
          <textarea id="source" name="source" style="width:100%;" rows=1></textarea><br><br>
        </p>
        <p align=left><?php echo "<h4>".$MSG_CONTEST."</h4>"?>
          <select name=contest_id>
            <?php
            $sql="SELECT `contest_id`,`title` FROM `contest` WHERE `end_time`>NOW() order by `contest_id`";
            $result=pdo_query($sql);
            echo "<option value=''>none</option>";
            if (count($result)==0){
            }else{
              foreach($result as $row){
                echo "<option value='{$row['contest_id']}'>{$row['contest_id']} {$row['title']}</option>";
              }
            }?>
          </select>
        </p>
        <div align=center>
          <?php require_once("../include/set_post_key.php");?>
          <input class="btn btn-info" type=submit value=Submit name=submit">
        </div>
      </form>
    </div>
  </body>
</html>
