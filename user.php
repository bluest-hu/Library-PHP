<?php 
session_start();

include(dirname(__FILE__) . "../config.php");

// 当前主页是否为自己的
$is_my_page = false;
$get_user_name = "";

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"])) {
	header("Location:" . $BASE_URL . "/login.php"); 
}

if ($_GET && isset($_GET["user"])) {
	$get_user_name = htmlspecialchars_decode($_GET["user"]);
} else {

}

if (isset($_SESSION["username"])) {
	if ($_SESSION["username"] == $get_user_name) {
		$is_my_page = TRUE;
	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>User</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8">
 	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books_add.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/user.css" />

	
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content clear">
			
			<?php include(dirname(__FILE__) . "../templ/usernav.temp.php"); ?>

			<div class="user-content">
				<ul>
					<li>
						
					</li>

					<li>
						
						<form action=""></form>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php include("templ/footer.temp.php");?>
</body>
</html>


