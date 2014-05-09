<?php 
	// 清除Session
	session_start();
	session_destroy();
	// 清除 Cookies
	setcookie('username', $username, time() - 60 * 24);
	setcookie('uniqid', "", time() - 60 * 60 * 24);
    setcookie('user_bg', "", time() - 60 * 24);
    setcookie('avatar', "", time() - 60 * 24);
	
	// 跳转
	header("Location:login.php");

?>