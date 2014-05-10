<?php 
	// 清除Session
	session_start();
	session_destroy();
	// 清除 Cookies
	require_once("class/user.class.php");
	USER::remove_all_cookies();
	
	// 跳转
	header("Location:login.php");

?>