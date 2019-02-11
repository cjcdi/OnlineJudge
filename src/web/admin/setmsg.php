<?php
  require_once("admin-header.php");
  if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
  echo "<hr>";
  echo "<center><h3>$MSG_SETMESSAGE</h3></center>";

  if(isset($_POST['do'])){
    require_once("../include/check_post_key.php"); // 检查下面设置了post_key

    $fp = fopen($OJ_SAE?"saestor://web/msg.txt":"msg.txt","w");
    $msg = $_POST['msg'];

    $msg = str_replace("<p>", "", $msg); //str_replace — 子字符串替换参数1被参数2代替
    $msg = str_replace("</p>", "<br />", $msg);
    $msg = str_replace(",", "&#44;", $msg);

    if(get_magic_quotes_gpc()){
      $title = stripslashes($title); //stripslashes — 反引用一个引用字符串
    }

    $msg = RemoveXSS($msg);
    fputs($fp,$msg);//fwrite — 写入文件（可安全用于二进制文件）fwrite() 把 string 的内容写入文件指针 handle 处。 
    fclose($fp);
    echo "Update At ".date('Y-m-d h:i:s');
  }

  $msg = file_get_contents($OJ_SAE?"saestor://web/msg.txt":"msg.txt"); //file_get_contents — 将整个文件读入一个字符串

  include("kindeditor.php");
?>

<div class="container">
  <form action='setmsg.php' method='post'>
    <textarea name='msg' rows=25 class="kindeditor" ><?php echo $msg?></textarea><br>
    <input type='hidden' name='do' value='do'>
    <center><input type='submit' value='Save'></center>
      if this does not work, try run "sudo chown -R www-data /home/judge/src/web " in terminal.
      <?php require_once("../include/set_post_key.php");?>
  </form>
</div>
<?php require_once('../oj-footer.php'); ?>