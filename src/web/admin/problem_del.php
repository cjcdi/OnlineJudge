<?php
    require_once("admin-header.php");
    ini_set("display_errors","On");
    require_once("../include/check_get_key.php");
    if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
            echo "<a href='../loginpage.php'>Please Login First!</a>";
            exit(1);
    }
?> 
<?php
    if($OJ_SAE||function_exists('system')){
        $id=intval($_GET['id']);
        $basedir = "$OJ_DATA/$id";
        if($OJ_SAE){
			;//need more code to delete files
    	}else{
    	    if(strlen($basedir)>16){
    			system("rm -rf $basedir");
    	    }
    	}
        $sql="DELETE FROM `problem` WHERE `problem_id`=?";
        pdo_query($sql,$id);
        $sql="DELETE FROM `problem_fill` WHERE `problem_id`=?";
        pdo_query($sql,$id);
        $sql="DELETE FROM `privilege` WHERE `rightstr`=?";
        pdo_query($sql,"p".$id);
        $sql="SELECT s.solution_id sid from (SELECT `solution_id` FROM `solution` where `problem_id`=?) s" ;
        $sql_ds="DELETE FROM `solution_fill` WHERE `solution_id` in (".$sql.")";
        pdo_query($sql_ds,$id);
        $sql_ds="DELETE FROM `source_code` WHERE `solution_id` in (".$sql.")";
        pdo_query($sql_ds,$id);
        $sql_ds="DELETE FROM `source_code_user` WHERE `solution_id` in (".$sql.")";
        pdo_query($sql_ds,$id);
        $sql_ds="DELETE FROM `solution` WHERE `solution_id` in (".$sql.")";
        pdo_query($sql_ds,$id);
        $sql="SELECT max(problem_id) FROM `problem`" ;
        $result=pdo_query($sql);
        $row=$result[0];
        $max_id=$row[0];
        $max_id++;
        if($max_id<1000) $max_id=1000;
        $sql="ALTER TABLE problem AUTO_INCREMENT = $max_id";
        pdo_query($sql);
        ?>
    <script language=javascript>
            history.go(-1);
    </script>
    <?php 
    }else{ ?>
        <script language=javascript>
            alert("Nees enable system() in php.ini");
            history.go(-1);
        </script>
<?php } ?>