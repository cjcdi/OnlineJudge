<?php
	require_once( "./include/db_info.inc.php" );
	require_once( './include/setlang.php' );
	//验证验证码
	$vcode = "";
	if ( isset( $_POST[ 'vcode' ] ) )$vcode = trim( $_POST[ 'vcode' ] ); //trim — 去除字符串首尾处的空白字符（或者其他字符）
	if ( $OJ_VCODE && ( $vcode != $_SESSION[ $OJ_NAME . '_' . "vcode" ] || $vcode == "" || $vcode == null ) ) {
		echo "<script language='javascript'>\n";
		echo "alert('Verify Code Wrong!');\n";
		echo "history.go(-1);\n";
		echo "</script>";
		exit( 0 );
	}
	//
	$view_errors = "";
	require_once( "./include/login-" . $OJ_LOGIN_MOD . ".php" );
	$user_id = $_POST[ 'user_id' ];
	$password = $_POST[ 'password' ];
	if ( get_magic_quotes_gpc() ) { //get_magic_quotes_gpc — Gets the current configuration setting of magic_quotes_gpc
		$user_id = stripslashes( $user_id ); //stripslashes — 反引用一个引用字符串，如果 magic_quotes_sybase 项开启，反斜线将被去除，但是两个反斜线将会被替换成一个。 
		$password = stripslashes( $password );
	}
	$sql = "SELECT `rightstr` FROM `privilege` WHERE `user_id`=?";
	$login = check_login( $user_id, $password ); //登陆验证成功返回用户id
	if ( $login ) {
		$_SESSION[ $OJ_NAME . '_' . 'user_id' ] = $login;
		$result = pdo_query( $sql, $login );

		foreach ( $result as $row ) $_SESSION[ $OJ_NAME . '_' . $row[ 'rightstr' ] ] = true; //给其权限设为真
		echo "<script language='javascript'>\n";
		if ( $OJ_NEED_LOGIN )
			echo "window.location.href='index.php';\n";
		else
			echo "history.go(-2);\n";
		echo "</script>";
	} else {
		if ( $view_errors ) {
			require( "template/" . $OJ_TEMPLATE . "/error.php" );
		} else {
			echo "<script language='javascript'>\n";
			echo "alert('UserName or Password Wrong!');\n";
			echo "history.go(-1);\n";
			echo "</script>";
		}
	}
?>