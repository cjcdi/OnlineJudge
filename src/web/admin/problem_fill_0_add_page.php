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
    echo "<center><h3>$MSG_ADD"."代码填空题</h3></center>";
    include_once("kindeditor.php") ;
    $path_fix = "../";
  ?>
  <!-- 新 Bootstrap 核心 CSS 文件 -->
  <link rel="stylesheet" href="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>bootstrap.min.css">
  <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
  <script src="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>jquery.min.js"></script>
  <body leftmargin="30" >
    <div class="container">
      <form method="POST" action="problem_fill_add.php">
        <input type=hidden name=problem_id value="New Problem">
        <input type=hidden name=problem_flag value="0">
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
          <textarea class="kindeditor" rows=15 name=description cols=80></textarea><br>
        </p>
        <p align=left>
          <?php echo "<h4>"."缺空代码（注：此代码用于显示在问题页面。）"."</h4>"?>
          <textarea class="kindeditor" rows=13 name=problem_tempcode cols=80></textarea><br>
        </p>
        <p align=left>
          <?php echo "<h4>"."判题使用代码（注：缺空地方必须使用\"<b>_fillProblem_</b>\"代替！此代码用于编译判题使用。）"."</h4>"?>
          Language:
          <select id="language" name="language" onChange="reloadtemplate($(this).val());" >
            <?php
              $lang_count=count($language_ext);
              if(isset($_GET['langmask'])) $langmask=$_GET['langmask'];
              else $langmask=$OJ_LANGMASK;
              $lang=(~((int)$langmask))&((1<<($lang_count))-1);
              if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
              else $lastlang=0;
              for($i=0;$i<$lang_count;$i++)
                if($lang&(1<<$i)) echo"<option value=$i ".( $lastlang==$i?"selected":"").">".$language_name[$i]."</option>";
            ?>
          </select>
          <?php if($OJ_ACE_EDITOR){ ?>
            <pre style="width:80%;height:600" cols=180 rows=20 id="tempsource"><?php echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></pre><br><!--编辑题目时会导进代码-->
            <input type=hidden id="hide_tempsource" name="tempsource" value=""/>
          <?php }else{ ?>
            <textarea style="width:80%;height:600" cols=180 rows=20 id="tempsource" name="tempsource"><?php echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></textarea><br>
          <?php }?>
        </p>
        <p align=left>
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
          <input class="btn btn-info" type=submit value=Submit name=submit onclick="do_submit();">
        </div>
      </form>
    </div>
  </body>
  <script>
    function do_submit(){
      if(typeof(editor) != "undefined"){ 
        $("#hide_tempsource").val(editor.getValue());
      }
    }
    function switchLang(lang){
       var langnames=new Array("c_cpp","c_cpp","pascal","java","ruby","sh","python","php","perl","csharp","objectivec","vbscript","scheme","c_cpp","c_cpp","lua","javascript","golang");
       editor.getSession().setMode("../ace/mode/"+langnames[lang]);
    }
    function reloadtemplate(lang){
      console.log("lang="+lang);
      document.cookie="lastlang="+lang.value;
      /*/alert(document.cookie);
      var url=window.location.href;
      var i=url.indexOf("sid=");
      if(i!=-1) url=url.substring(0,i-1);*/
      /* if(confirm("<?php echo $MSG_LOAD_TEMPLATE_CONFIRM?>")) document.location.href=url;*/
      switchLang(lang);
    }
  </script>
  <?php if($OJ_ACE_EDITOR){ ?>
    <script src="../ace/ace.js"></script>
    <script src="../ace/ext-language_tools.js"></script>
    <script>
        ace.require("../ace/ext/language_tools");
        var editor = ace.edit("tempsource");
        editor.setTheme("../ace/theme/chrome");
        switchLang(<?php echo $lastlang ?>);
        editor.setOptions({
          enableBasicAutocompletion: true,
          enableSnippets: true,
          enableLiveAutocompletion: true
        });
        reloadtemplate($("#language").val()); 
    </script>
  <?php }?>
</html>
