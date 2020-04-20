<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');

$now = strftime("%Y-%m-%d %H:%M", time());
if (isset($_GET['cid']))
    $ucid = "&cid=" . intval($_GET['cid']);
else
    $ucid = "";

require_once("./include/db_info.inc.php");
if (isset($OJ_LANG)) {
    require_once("./lang/$OJ_LANG.php");
}

$pr_flag = false;
$co_flag = false;
if (isset($_GET['id'])) {  // 只获取问题id
    $id = intval($_GET['id']);
    // require("oj-header.php");
    if (!isset($_SESSION[$OJ_NAME . '_' . 'administrator']) && $id != 1000 && !isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))
        $sql = "SELECT * FROM `problem` WHERE `problem_id`=? AND `defunct`='N' AND `problem_id` NOT IN(
              SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
                SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' or `private`='1'))";
    else $sql = "SELECT * FROM `problem` WHERE `problem_id`=?";
    $result = pdo_query($sql, $id);
    if (count($result) == 1) {
        $row = ($result[0]);
        $id = $row['problem_id'];
        $sql = "SELECT * FROM `problem_fill` WHERE `problem_id`=?";
        $result_fill = pdo_query($sql, $id);
        if (count($result_fill) == 1) {
            $row_fill = ($result_fill[0]);
        }
    }
    $pr_flag = true;
} else if (isset($_GET['cid']) && isset($_GET['pid'])) {
    $cid = intval($_GET['cid']);
    $pid = intval($_GET['pid']);
    if (!isset($_SESSION[$OJ_NAME . '_' . 'administrator']))
        $sql = "SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=? AND `start_time`<='$now'";
    else
        $sql = "SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=?";
    $result = pdo_query($sql, $cid);
    $rows_cnt = count($result);
    $row = ($result[0]);
    $contest_ok = true;

    if ($row[1] && !isset($_SESSION[$OJ_NAME . '_' . 'c' . $cid]))  // 私有竞赛且用户没有该竞赛权限则不允许查看竞赛
        $contest_ok = false;
    if ($row[2] == 'Y')  // 竞赛被屏蔽不允许查看竞赛
        $contest_ok = false;
    if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']))  // 管理员不受限制
        $contest_ok = true;

    $ok_cnt = $rows_cnt == 1;
    $langmask = $row[0];
    if ($ok_cnt != 1) {  // 没有查询到该竞赛id的比赛
        $view_errors =  "No such Contest!";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    } else {  // 查到了该竞赛，则找到该竞赛的某一个题目所有数据
        $sql = "SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
              SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=? AND `num`=?)";
        $result = pdo_query($sql, $cid, $pid);
        if (count($result) == 1) {
            $row = ($result[0]);
            $id = $row['problem_id'];
            $sql = "SELECT * FROM `problem_fill` WHERE `problem_id`=?";
            $result_fill = pdo_query($sql, $id);
            if (count($result_fill) == 1) {
                $row_fill = ($result_fill[0]);
            }
        }
    }
    $sql_1 = "SELECT `c_submit`,`c_accepted` FROM `contest_problem` WHERE `contest_id`=? AND `num`=?";
    $r = pdo_query($sql_1, $cid, $pid);
    $total = intval($r[0][0]);
    $ac = intval($r[0][1]);

    if (!$contest_ok) {
        $view_errors = "Not Invited!";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    }
    $co_flag = true;
} else {
    $view_errors = "<title>$MSG_NO_SUCH_PROBLEM</title><h2>$MSG_NO_SUCH_PROBLEM</h2>";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

if (count($result) != 1) {  // 在该竞赛下面没有设置该题目 id 的题目，或者题目表里面没有该 id 的数据
    $view_errors = "";
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT contest.`contest_id`, contest.`title`,contest_problem.num FROM `contest_problem`, `contest` 
            WHERE contest.contest_id=contest_problem.contest_id and `problem_id`=? and defunct='N' ORDER BY `num`";
        $result = pdo_query($sql, $id);

        if ($i = count($result)) {  // 查询到有数据
            $view_errors .= "This problem is in Contest(s) below:<br>";
            foreach ($result as $row) {
                $view_errors .= "<a href=problem.php?cid=$row[0]&pid=$row[2]>Contest $row[0]:" . htmlentities($row[1], ENT_QUOTES, "utf-8") . "</a><br>"; //重新跳转到该问题下面
            }
        } else {
            $view_title = "<title>$MSG_NO_SUCH_PROBLEM!</title>";
            $view_errors .= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
        }
    } else {
        $view_title = "<title>$MSG_NO_SUCH_PROBLEM!</title>";
        $view_errors .= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
    }
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
} else {
    $row = $result[0];
    $view_title = $row['title'];
}

if (isset($row_fill) && ($row_fill['problem_flag'] == "0" || $row_fill['problem_flag'] == "1")) {
    require("template/" . $OJ_TEMPLATE . "/problem_fill.php");
} else {
    require("template/" . $OJ_TEMPLATE . "/problem.php");
}

if (file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
