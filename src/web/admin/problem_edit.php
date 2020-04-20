<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Edit Problem</title>
</head>
<hr>

<?php
require_once("../include/db_info.inc.php");
require_once("admin-header.php");
require_once("../include/const.inc.php");
require_once("../include/my_func.inc.php");
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}
echo "<center><h3>Edit-" . "$MSG_PROBLEM</h3></center>";
include_once("kindeditor.php");
?>

<body leftmargin="30">
    <div class="container">
        <?php
        if (isset($_GET['id'])) {
            // require_once("../include/check_get_key.php"); 
            ?>
            <form method=POST action=problem_edit.php>
                <?php
                    $sql = "SELECT * FROM `problem` WHERE `problem_id`=?";
                    $result = pdo_query($sql, intval($_GET['id']));
                    $row = $result[0];
                    $is_ok = false;
                    $sql = "SELECT * FROM `problem_fill` WHERE `problem_id`=?";
                    $result_fill = pdo_query($sql, intval($_GET['id']));
                    if ($result_fill) {
                        $row_fill = $result_fill[0];
                        $is_ok = true;
                    }
                    ?>
                <input type=hidden name=problem_id value='<?php echo $row['problem_id'] ?>'>
                <?php if ($is_ok) { ?>
                    <input type=hidden name=problem_flag value='<?php echo $row_fill['problem_flag'] ?>'>
                <?php } ?>
                <p align=left>
                    <center>
                        <h3>
                            <?php echo $row['problem_id'] ?>: <input class="input input-xxlarge" style='width:90%' type=text name=title value='<?php echo htmlentities($row['title'], ENT_QUOTES, "UTF-8") ?>'>
                        </h3>
                    </center>
                </p>
                <p align=left>
                    <?php echo $MSG_Time_Limit ?><br>
                    <input class="input input-mini" type=text name=time_limit size=20 value='<?php echo htmlentities($row['time_limit'], ENT_QUOTES, "UTF-8") ?>'> Sec<br><br>
                    <?php echo $MSG_Memory_Limit ?><br>
                    <input class="input input-mini" type=text name=memory_limit size=20 value='<?php echo htmlentities($row['memory_limit'], ENT_QUOTES, "UTF-8") ?>'> MB<br><br>
                </p>
                <p align=left>
                    <?php echo "<h4>" . $MSG_Description . "</h4>" ?>
                    <textarea class="kindeditor" rows=13 name=description cols=80><?php echo htmlentities($row['description'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                </p>
                <?php if (!$is_ok) { ?>
                    <p align=left>
                        <?php echo "<h4>" . $MSG_Input . "</h4>" ?>
                        <textarea class="kindeditor" rows=13 name=input cols=80><?php echo htmlentities($row['input'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                    </p>
                <?php } ?>
                <?php if ($is_ok && $row_fill['problem_flag'] == "1") { ?>
                    <p align=left>
                        <?php echo "<h4>" . $MSG_Output . "</h4>" ?>
                        <textarea class="kindeditor" rows=13 name=output cols=80><?php echo htmlentities($row['output'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                    </p>
                    <p align=left>
                        <?php echo "<h4>" . "正确答案" . " [当有多个答案时，每个答案占一行--不要输出其他任何多余的字符，包括换行和空格]</h4>" ?>
                        <textarea class="input input-large" style="width:100%;" rows=13 name=problem_answer><?php echo htmlentities($row_fill['problem_answer'], ENT_QUOTES, "UTF-8") ?></textarea>
                    </p>
                <?php } ?>
                <?php if ($is_ok && $row_fill['problem_flag'] == "0") { ?>
                    <p align=left>
                        <?php echo "<h4>" . "缺空代码（注：此代码用于显示在问题页面。）" . "</h4>" ?>
                        <textarea class="kindeditor" rows=13 name=problem_tempcode cols=80><?php echo htmlentities($row_fill['problem_tempcode'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                    </p>
                    <p align=left>
                        <?php echo "<h4>" . "判题使用代码（注：缺空地方必须使用\"<b>_fillProblem_</b>\"代替！此代码用于编译判题使用。）" . "</h4>" ?>
                        Language:
                        <select id="language" name="language" onChange="reloadtemplate($(this).val());">
                            <?php
                                    $lang_count = count($language_ext);
                                    if (isset($_GET['langmask'])) $langmask = $_GET['langmask'];
                                    else $langmask = $OJ_LANGMASK;
                                    $lang = (~((int) $langmask)) & ((1 << ($lang_count)) - 1);
                                    //if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
                                    //else $lastlang=0;
                                    $lastlang = $row_fill['language'];
                                    for ($i = 0; $i < $lang_count; $i++)
                                        if ($lang & (1 << $i)) echo "<option value=$i " . ($lastlang == $i ? "selected" : "") . ">" . $language_name[$i] . "</option>";
                                    ?>
                        </select>
                        <?php
                                $tempsource = str_replace($row_fill['fillmd5'], "_fillProblem_", $row_fill['problem_tempsource']);
                                ?>
                        <?php if ($OJ_ACE_EDITOR) { ?>
                            <pre style="width:80%;height:600" cols=180 rows=20 id="tempsource"><?php echo htmlentities($tempsource, ENT_QUOTES, "UTF-8") ?></pre><br>
                            <!-- 编辑题目时会导进代码 -->
                            <input type=hidden id="hide_tempsource" name="tempsource" value="" />
                        <?php } else { ?>
                            <textarea style="width:80%;height:600" cols=180 rows=20 id="tempsource" name="tempsource"><?php echo htmlentities($tempsource, ENT_QUOTES, "UTF-8") ?></textarea><br>
                        <?php } ?>
                    </p>
                <?php } ?>
                <?php if ($is_ok && $row_fill['problem_flag'] == "1") { } else { ?>
                    <p align=left>
                        <?php echo "<h4>" . $MSG_Sample_Input . "</h4>" ?>
                        <textarea class="input input-large" style="width:100%;" rows=13 name=sample_input><?php echo htmlentities($row['sample_input'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
                    </p>
                    <p align=left>
                        <?php echo "<h4>" . $MSG_Sample_Output . "</h4>" ?>
                        <textarea class="input input-large" style="width:100%;" rows=13 name=sample_output><?php echo htmlentities($row['sample_output'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
                    </p>
                <?php } ?>
                <p align=left>
                    <?php echo "<h4>" . $MSG_HINT . "</h4>" ?>
                    <textarea class="kindeditor" rows=13 name=hint cols=80><?php echo htmlentities($row['hint'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                </p>
                <p>
                    <?php echo "<h4>" . $MSG_SPJ . "</h4>" ?>
                    <?php echo "(" . $MSG_HELP_SPJ . ")" ?><br>
                    <?php echo "No " ?><input type=radio name=spj value='0' <?php echo $row['spj'] == "0" ? "checked" : "" ?>>
                    <?php echo "/ Yes " ?><input type=radio name=spj value='1' <?php echo $row['spj'] == "1" ? "checked" : "" ?>><br><br>
                </p>
                <p align=left>
                    <?php echo "<h4>" . $MSG_SOURCE . "</h4>" ?>
                    <textarea name=source style="width:100%;" rows=1><?php echo htmlentities($row['source'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
                </p>
                <div align=center>
                    <?php require_once("../include/set_post_key.php"); ?>
                    <input type=submit value=Submit name=submit <?php echo (($is_ok) && ($row_fill['problem_flag'] == "0")) ? "onclick=\"do_submit();\"" : "" ?>>
                </div>
            </form>
        <?php
        } else {
            require_once("../include/check_post_key.php");
            $id = intval($_POST['problem_id']);
            if (!(isset($_SESSION[$OJ_NAME . '_' . "p$id"]) || isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) exit();

            $title = $_POST['title'];
            $title = str_replace(",", "&#44;", $title);
            $time_limit = $_POST['time_limit'];
            $memory_limit = $_POST['memory_limit'];
            $problem_flag = $_POST['problem_flag'];

            $description = $_POST['description'];
            $description = str_replace("<p>", "", $description);
            $description = str_replace("</p>", "<br />", $description);
            $description = str_replace(",", "&#44;", $description);
            if (!isset($problem_flag)) {
                $input = $_POST['input'];
                $input = str_replace("<p>", "", $input);
                $input = str_replace("</p>", "<br />", $input);
                $input = str_replace(",", "&#44;", $input);
            }
            $hint = $_POST['hint'];
            $hint = str_replace("<p>", "", $hint);
            $hint = str_replace("</p>", "<br />", $hint);
            $hint = str_replace(",", "&#44;", $hint);
            $source = $_POST['source'];
            $spj = $_POST['spj'];
            $spj = intval($spj);
            $title = ($title);
            // $description = RemoveXSS($description);
            if (!isset($problem_flag)) $input = RemoveXSS($input);
            $hint = RemoveXSS($hint);

            if (isset($problem_flag) && "1" == $problem_flag) {  // 结果填空
                $title = str_replace("（结果填空）", "", $title);
                $title .= "（结果填空）";
                $problem_answer = $_POST['problem_answer'];
                $output = $_POST['output'];
                $output = str_replace("<p>", "", $output);
                $output = str_replace("</p>", "<br />", $output);
                $output = str_replace(",", "&#44;", $output);

                $output = RemoveXSS($output);
                $sql = "UPDATE `problem` set `title`=?,`time_limit`=?,`memory_limit`=?,`description`=?,`output`=?,`hint`=?,`source`=?,`spj`=?,`in_date`=NOW() WHERE `problem_id`=?";
                @pdo_query($sql, $title, $time_limit, $memory_limit, $description, $output, $hint, $source, $spj, $id);
                $sql = "UPDATE `problem_fill` set `problem_answer`=? WHERE `problem_id`=?";
                @pdo_query($sql, $problem_answer, $id);
            }
            if (isset($problem_flag) && !strcmp("0", $problem_flag)) {  // 代码填空
                $title = str_replace("（代码填空）", "", $title);
                $title .= "（代码填空）";
                $problem_tempcode = $_POST['problem_tempcode'];
                $problem_tempcode = str_replace("<p>", "", $problem_tempcode);
                $problem_tempcode = str_replace("</p>", "<br />", $problem_tempcode);
                $problem_tempcode = str_replace(",", "&#44;", $problem_tempcode);
                $sample_input = $_POST['sample_input'];
                $sample_output = $_POST['sample_output'];
                //$test_input = $_POST['test_input'];
                //$test_output = $_POST['test_output'];
                $language = intval($_POST['language']);
                if ($language > count($language_name) || $language < 0) $language = 0;
                $language = strval($language);
                setcookie('lastlang', $language, time() + 360000);  // 设置cookie
                $tempsource = $_POST['tempsource'];

                $problem_tempcode = RemoveXSS($problem_tempcode);
                if ($language == 6) $tempsource = "# coding=utf-8\n" . $tempsource;
                $_fillProblem_ = md5("_fillProblem_");
                $salt = sha1(rand());
                $salt = substr($salt, 0, 4);
                $fillmd5 = base64_encode(sha1($_fillProblem_ . $salt, true) . $salt);
                $tempsource = str_replace("_fillProblem_", $fillmd5, $tempsource);
                if ((~$OJ_LANGMASK) & (1 << $language)) {
                    $sql = "UPDATE `problem` set `title`=?,`time_limit`=?,`memory_limit`=?,`description`=?,`sample_input`=?,`sample_output`=?,`hint`=?,`source`=?,`spj`=?,`in_date`=NOW() WHERE `problem_id`=?";
                    @pdo_query($sql, $title, $time_limit, $memory_limit, $description, $sample_input, $sample_output, $hint, $source, $spj, $id);
                    $sql = "UPDATE `problem_fill` set `problem_tempcode`=?,`language`=?,`problem_tempsource`=?,`fillmd5`=? WHERE `problem_id`=?";
                    @pdo_query($sql, $problem_tempcode, $language, $tempsource, $fillmd5, $id);
                    $basedir = $OJ_DATA . "/$id";
                    echo "Sample data file Updated!<br>";
                    if (strlen($sample_output) && !strlen($sample_input)) {
                        $fp = fopen($basedir . "/sample.in", "w");
                        fputs($fp, preg_replace("(\r\n)", "\n", "0"));
                        fclose($fp);
                    }
                    if (strlen($sample_input) && file_exists($basedir . "/sample.in")) {
                        $fp = fopen($basedir . "/sample.in", "w");
                        fputs($fp, preg_replace("(\r\n)", "\n", $sample_input));
                        fclose($fp);
                    }
                    if (strlen($sample_output) && file_exists($basedir . "/sample.out")) {
                        $fp = fopen($basedir . "/sample.out", "w");
                        fputs($fp, preg_replace("(\r\n)", "\n", $sample_output));
                        fclose($fp);
                    }
                    // if(strlen($test_output) && !strlen($test_input)) $test_input = "0";
                    // if(strlen($test_input)) mkdata($pid,"test.in", $test_input, $OJ_DATA);
                    // if(strlen($test_output)) mkdata($pid,"test.out", $test_output, $OJ_DATA);
                }
            }
            if (!isset($problem_flag)) {
                $output = $_POST['output'];
                $output = str_replace("<p>", "", $output);
                $output = str_replace("</p>", "<br />", $output);
                $output = str_replace(",", "&#44;", $output);
                $sample_input = $_POST['sample_input'];
                $sample_output = $_POST['sample_output'];

                $output = RemoveXSS($output);
                $basedir = $OJ_DATA . "/$id";
                echo "Sample data file Updated!<br>";
                if (strlen($sample_output) && !strlen($sample_input)) {
                    $fp = fopen($basedir . "/sample.in", "w");
                    fputs($fp, preg_replace("(\r\n)", "\n", "0"));
                    fclose($fp);
                }
                if (strlen($sample_input) && file_exists($basedir . "/sample.in")) {
                    $fp = fopen($basedir . "/sample.in", "w");
                    fputs($fp, preg_replace("(\r\n)", "\n", $sample_input));
                    fclose($fp);
                }
                if (strlen($sample_output) && file_exists($basedir . "/sample.out")) {
                    $fp = fopen($basedir . "/sample.out", "w");
                    fputs($fp, preg_replace("(\r\n)", "\n", $sample_output));
                    fclose($fp);
                }
                $sql = "UPDATE `problem` set `title`=?,`time_limit`=?,`memory_limit`=?,`description`=?,`input`=?,`output`=?,`sample_input`=?,`sample_output`=?,`hint`=?,`source`=?,`spj`=?,`in_date`=NOW() WHERE `problem_id`=?";
                @pdo_query($sql, $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj, $id);
            }
            echo "Edit OK!";
            echo "<a href='../problem.php?id=$id'>See The Problem!</a>";
        }
        ?>
    </div>
</body>
<script>
    function do_submit() {
        if (typeof(editor) != "undefined") {
            $("#hide_tempsource").val(editor.getValue());
        }
    }

    function switchLang(lang) {
        var langnames = new Array("c_cpp", "c_cpp", "pascal", "java", "ruby", "sh", "python", "php", "perl", "csharp", "objectivec", "vbscript", "scheme", "c_cpp", "c_cpp", "lua", "javascript", "golang");
        editor.getSession().setMode("../ace/mode/" + langnames[lang]);
    }

    function reloadtemplate(lang) {
        console.log("lang=" + lang);
        document.cookie = "lastlang=" + lang.value;
        // alert(document.cookie);
        // var url=window.location.href;
        // var i=url.indexOf("sid=");
        // if(i!=-1) url=url.substring(0,i-1);
        // if(confirm("<?php echo $MSG_LOAD_TEMPLATE_CONFIRM ?>")) document.location.href=url;
        switchLang(lang);
    }
</script>
<?php if ($OJ_ACE_EDITOR) { ?>
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
<?php } ?>

</html>