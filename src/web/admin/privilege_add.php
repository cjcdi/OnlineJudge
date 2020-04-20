<?php require_once("admin-header.php");?>
<?php 
	if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
		echo "<a href='../loginpage.php'>Please Login First!</a>";
		exit(1);
	}
	if(isset($_POST['do'])){
		require_once("../include/check_post_key.php");
		$user_id=$_POST['user_id'];
		$rightstr =$_POST['rightstr'];
		if(isset($_POST['contest'])) $rightstr="c$rightstr";
		//if(isset($_POST['psv'])) $rightstr="s$rightstr";
		if(isset($_POST['problem'])) $rightstr="p$rightstr";
		if(isset($_POST['mcontest'])) $rightstr="m$rightstr";
		$sql="insert into `privilege` values(?,?,'N')";
		$rows=pdo_query($sql,$user_id,$rightstr);
		echo "$user_id $rightstr added! Please relogin.";
	}
?>
<div class="container">
	<form method=post>
		<?php require("../include/set_post_key.php");?>
			<b>Add privilege for User:</b>(为用户添加管理员权限，问题权限，竞赛权限)<br />
			User:<input type=text size=10 name="user_id"><br />
			Privilege:
			<select name="rightstr">
				<?php
					//$rightarray=array("administrator","problem_editor","source_browser","contest_creator","http_judge","password_setter","printer","balloon" );
					$rightarray=array("administrator","problem_editor","contest_creator");
					while(list($key, $val)=each($rightarray)) {
						if (isset($rightstr) && ($rightstr == $val)) {
							echo '<option value="'.$val.'" selected>'.$val.'</option>';
						} else {
							echo '<option value="'.$val.'">'.$val.'</option>';
					}
				}
				?>
			</select><br />
		<input type='hidden' name='do' value='do'>
		<input type=submit value='Add'>
		<?php //echo $MSG_HELP_ADD_PRIVILEGE; ?>
	</form>

	<form method=post>
		<b>Add contest for User:</b>（为用户增加某一竞赛的使用权）<br />
		User:<input type=text size=10 name="user_id"><br />
		Contest:<input type=text size=10 name="rightstr">1000 for Contest 1000<br />
		<input type='hidden' name='do' value='do'>
		<input type='hidden' name='contest' value='do'>
		<input type=submit value='Add'>
		<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
	</form>

	<!--form method=post>
		<b>Add Problem Solution View for User:</b>（为用户增加某一条解决答案的权限）<br />
		User:<input type=text size=10 name="user_id"><br />
		Problem:<input type=text size=10 name="rightstr">1000 for Problem 1000<br />
		<input type='hidden' name='do' value='do'>
		<input type='hidden' name='psv' value='do'>
		<input type=submit value='Add'>
		<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
	</form-->

	<form method=post>
		<b>Add Problem edit for User:</b>（为用户增加某一问题的编辑权限，前提该用户具有问题权限）<br />
		User:<input type=text size=10 name="user_id"><br />
		Problem:<input type=text size=10 name="rightstr">1000 for Problem 1000<br />
		<input type='hidden' name='do' value='do'>
		<input type='hidden' name='problem' value='do'>
		<input type=submit value='Add'>
		<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
	</form>

	<form method=post>
		<b>Add Contest edit for User:</b>（为用户增加某一竞赛的编辑权限，前提该用户具有竞赛权限）<br />
		User:<input type=text size=10 name="user_id"><br />
		Contest:<input type=text size=10 name="rightstr">1000 for Contest 1000<br />
		<input type='hidden' name='do' value='do'>
		<input type='hidden' name='mcontest' value='do'>
		<input type=submit value='Add'>
		<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
	</form>
</div>
