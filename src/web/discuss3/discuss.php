<?php
	require_once("oj-header.php");
	require_once("discuss_func.inc.php"); //判断数据库里是否有问题和竞赛，有才给讨论，没有不给讨论
	
	$parm="";
	if(isset($_GET['pid'])){
		$pid=intval($_GET['pid']);
		$parm="pid=".$pid;
	}else{
		$pid=0;
	}
	if(isset($_GET['cid'])){
		$cid=intval($_GET['cid']);
	}else{
		$cid=0;
	}
	$parm.="&cid=".$cid;
    $prob_exist = problem_exist($pid, $cid);
	require_once("oj-header.php");
?>
<center>
	<div style="width:90%">
		<?php $prob_exist = true;?>
		<?php if ($prob_exist){?>
			<div style="text-align:left;font-size:80%">
				[ <a href="newpost.php<?php if ($pid!=0 && $cid!=null) echo "?pid=".$pid."&cid=".$cid;
										else if ($pid!=0) echo "?pid=".$pid;
										else if ($cid!=0) echo "?cid=".$cid; // 假如处于某个问题的讨论中，新建的topic则默认填写问题id?>">
				New Thread</a> ]
			</div>
			<div style="float:left;text-align:left;font-size:80%">
				Location :
				<?php if ($cid!=null) echo "<a href=\"discuss.php?cid=".$cid."\">Contest ".$cid."</a>"; 
					else echo "<a href=\"discuss.php\">MainBoard</a>"; //判断处于哪个讨论板块中，是主面板还是某个竞赛的讨论版，实际上根本没做竞赛讨论版模块
				if ($pid!=null && $pid!=0){ //判断是否确定哪个题目，假如确定，则在分板块后面再分题目
					$query="?pid=$pid";
					if($cid!=0) {
						$query.="&cid=$cid";
						$PAL=pdo_query("select num from contest_problem where contest_id=? and problem_id=?",$cid,$pid)[0][0];
						echo $PAL."<br>";
						echo " >> <a href=\"discuss.php".$query."\">Problem ".$PID[$PAL]."</a>";
					}else{
						echo " >> <a href=\"discuss.php".$query."\">Problem ".$pid."</a>"; //暂时只可能这种情况
					}
				}
			?>
			</div>

			<div style="float:right;font-size:80%;color:red;font-weight:bold">
				<?php if ($pid!=null && $pid!=0 && ($cid=='' || $cid==null)){?>
					<a href="../problem.php?id=<?php echo $pid; //跳转到该问题描述页面?>">See the problem</a> 
				<?php }?>
			</div>
		<?php }
			//后台数据库查询语句
			$sql = "SELECT `tid`, `title`, `top_level`, `t`.`status`, `cid`, `pid`, CONVERT(MIN(`r`.`time`),DATE) `posttime`,
							MAX(`r`.`time`) `lastupdate`, `t`.`author_id`, COUNT(`rid`) `count`
					FROM `topic` t left join `reply` r on t.tid=r.topic_id
					WHERE `t`.`status`!=2  ";
			if(isset($_REQUEST['cid'])){
				$cid=intval($_REQUEST['cid']);
				$sql = "SELECT `tid`, t.`title`, `top_level`, `t`.`status`, `cid`, `pid`, CONVERT(MIN(`r`.`time`),DATE) `posttime`,
					MAX(`r`.`time`) `lastupdate`, `t`.`author_id`, COUNT(`rid`) `count`,cp.num
					FROM `topic` t left join `reply` r on t.tid=r.topic_id left join contest_problem cp on t.pid=cp.problem_id and cp.contest_id=$cid 
					WHERE `t`.`status`!=2  ";
			}
			if (array_key_exists("cid",$_REQUEST)&&$_REQUEST['cid']!='') 
				$sql.= " AND ( `cid` = '".intval($_REQUEST['cid'])."'";
			else 
				$sql.=" AND (`cid`=0 ";
			$sql.=" OR `top_level` = 3 )";
			if (array_key_exists("pid",$_REQUEST)&&$_REQUEST['pid']!=''){
			  $sql.=" AND ( `pid` = '".intval($_REQUEST['pid'])."' OR `top_level` >= 2 )";
			  $level="";
			}else{
			  $level=" - ( `top_level` = 1 )";
			}
			$sql.=" GROUP BY t.tid ORDER BY `top_level`$level DESC, MAX(`r`.`time`) DESC"; //`top_level`只有三种等级，0和1是一样的，所以这么写
			$sql.=" LIMIT 30";
			//echo $sql;
			$result = pdo_query($sql);
			$rows_cnt = count($result);
			$cnt=0;
			$isadmin = isset($_SESSION[$OJ_NAME.'_'.'administrator']); //是否管理员登陆登陆
		?>

		<!--显示讨论版内容-->
		<table style="clear:both; width:100%">
			<tr align=center class='toprow'>
		        <td width="2%"><?php if ($isadmin) echo "<input type=checkbox>"; ?></td>
		        <td width="3%"></td>
		        <td width="4%">Prob</td>
		        <td width="12%">Author</td>
		        <td width="46%">Title</td>
		        <td width="8%">Post Date</td>
		        <td width="16%">Last Reply</td>
		        <td width="3%">Re</td> <!--显示帖子回复数-->
			</tr>
			<?php 
				if ($rows_cnt==0) echo("<tr class=\"evenrow\"><td colspan=4></td><td style=\"text-align:center\">No thread here.</td></tr>");
				$i=0;
				foreach ( $result as $row){
				    if ($cnt) echo "<tr align=center class='oddrow'>";
				    else echo "<tr align=center class='evenrow'>";
			        $cnt=1-$cnt;
				        if ($isadmin) echo "<td><input type=checkbox></td>"; //已登陆的话显示复选框
				        else echo("<td></td>");

				        echo "<td>";
			                if ($row['top_level']!=0){
			                    if ($row['top_level']!=1||$row['pid']==($pid==''?0:$pid))
			                        echo"<b class=\"Top{$row['top_level']}\">Top</b>";
			                }
			                else if ($row['status']==1) echo"<b class=\"Lock\">Lock</b>";
			                else if ($row['count']>20) echo"<b class=\"Hot\">Hot</b>"; //回复数大于 20
				        echo "</td>";

				        echo "<td>";
					        if ($row['pid']!=0) {
								if($row['cid']){	
									echo "<a href=\"discuss.php?pid={$row['pid']}"."&cid={$row['cid']}\">{$PID[$row['num']]}</a>";
								}else{
									echo "<a href=\"discuss.php?pid={$row['pid']}\">{$row['pid']}</a>";
								}
					        }
						echo "</td>";

					    echo "<td><a href=\"../userinfo.php?user={$row['author_id']}\">{$row['author_id']}</a></td>";

				        if($row['cid']) echo "<td><a href=\"thread.php?tid={$row['tid']}&cid={$row['cid']}\">".htmlentities($row['title'],ENT_QUOTES,"UTF-8")."</a></td>";
				        else echo "<td><a href=\"thread.php?tid={$row['tid']}\">".htmlentities($row['title'],ENT_QUOTES,"UTF-8")."</a></td>";

					    echo "<td>{$row['posttime']}</td>";

					    echo "<td>{$row['lastupdate']}</td>";

					    echo "<td>".($row['count']-1)."</td>";
				    echo "</tr>";
					$i++;
				}
			?>
		</table>
	</div>
</center>
<?php require_once("../template/$OJ_TEMPLATE/discuss.php")?>