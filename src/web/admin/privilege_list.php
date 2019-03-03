<?php
  require("admin-header.php");
  require_once("../include/set_get_key.php");

  if(!isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }

  if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
  }
?>

<title>Privilege List</title>
<hr>
<center><h3><?php echo $MSG_PRIVILEGE.$MSG_LIST?></h3></center>

<div class='container'>
  <div class="jumbotron">
    <h4>注：</h4>
    <ul>
      <ol>administrator：管理员权限，以下所有权限的综合，并具有其他管理功能</ol>
      <ol>problem_editor：问题权限，拥有该权限才能进行问题的编辑操作</ol>
      <ol>contest_creator：竞赛权限，拥有该权限才能进行竞赛的编辑操作</ol>
      <ol>m[]：某竞赛的管理者，拥有该权限才能真正的对某竞赛进行管理，其中[]为竞赛id</ol>
      <ol>c[]：某竞赛的使用权限，当竞赛设置为私有时必须拥有该权限才能进行竞赛，其中[]为竞赛id</ol>
      <ol>p[]：某问题的管理者，拥有该权限才能真正的对某问题进行管理，其中[]为竞赛id</ol>
    </ul>
  </div>
  <?php
  //$sql = "SELECT COUNT(*) AS ids FROM privilege WHERE rightstr IN ('administrator','source_browser','contest_creator','http_judge','problem_editor','password_setter','printer','balloon') ORDER BY user_id, rightstr";
  $sql = "SELECT COUNT(*) AS ids FROM privilege ORDER BY user_id, rightstr";
  $result = pdo_query($sql);
  $row = $result[0];

  $ids = intval($row['ids']);

  $idsperpage = 25;
  $pages = intval(ceil($ids/$idsperpage));

  if(isset($_GET['page'])){ $page = intval($_GET['page']);}
  else{ $page = 1;}

  $pagesperframe = 5;
  $frame = intval(ceil($page/$pagesperframe));

  $spage = ($frame-1)*$pagesperframe+1;
  $epage = min($spage+$pagesperframe-1, $pages);

  $sid = ($page-1)*$idsperpage;

  $sql = "";
  if(isset($_GET['keyword']) && $_GET['keyword']!=""){
    $keyword = $_GET['keyword'];
    $keyword = "%$keyword%";
    $sql = "SELECT * FROM privilege WHERE (user_id LIKE ?) OR (rightstr LIKE ?) ORDER BY user_id, rightstr";
    $result = pdo_query($sql,$keyword,$keyword);
  }else{
    $sql = "SELECT * FROM privilege ORDER BY user_id, rightstr LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
  }
?>

<form action=privilege_list.php class="center">
  <input name=keyword><input type=submit value="<?php echo $MSG_SEARCH?>">
</form>

<center>
  <table width=100% border=1 style="text-align:center;">
    <tr>
      <td>ID</td>
      <td>PRIVILEGE</td>
      <td>REMOVE</td>
    </tr>
    <?php
    foreach($result as $row){
      echo "<tr>";
        echo "<td>".$row['user_id']."</td>";
        echo "<td>".$row['rightstr']."</td>";
        echo "<td><a href=privilege_delete.php?uid={$row['user_id']}&rightstr={$row['rightstr']}&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">Delete</a></td>";
      echo "</tr>";
    }
    ?>
  </table>
</center>
<h3>权限不要随意修改，会造成无法想象的后果</h3>
<?php
  if(!(isset($_GET['keyword']) && $_GET['keyword']!=""))
  {
    echo "<div style='display:inline;'>";
    echo "<nav class='center'>";
    echo "<ul class='pagination pagination-sm'>";
    echo "<li class='page-item'><a href='privilege_list.php?page=".(strval(1))."'>&lt;&lt;</a></li>";
    echo "<li class='page-item'><a href='privilege_list.php?page=".($page==1?strval(1):strval($page-1))."'>&lt;</a></li>";
    for($i=$spage; $i<=$epage; $i++){
      echo "<li class='".($page==$i?"active ":"")."page-item'><a title='go to page' href='privilege_list.php?page=".$i.(isset($_GET['my'])?"&my":"")."'>".$i."</a></li>";
    }
    echo "<li class='page-item'><a href='privilege_list.php?page=".($page==$pages?strval($page):strval($page+1))."'>&gt;</a></li>";
    echo "<li class='page-item'><a href='privilege_list.php?page=".(strval($pages))."'>&gt;&gt;</a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "</div>";
  }
?>

</div>
