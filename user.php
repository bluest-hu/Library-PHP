<?php 
session_start();

// 当前主页是否为自己的
$is_my_page = false;
$get_user_name = "";

// 
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
	<link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/main.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
	<title>User</title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8">
	<style type="text/css">

	.user-nav {
		border-radius: 4px;
		width: 200px;
		background-color: #373942;

	}

	.user-nav .navigation {
	}


	.user-nav a {
		padding-left: 30px;
		display: block;
		color: #7b7d86;
		line-height: 40px;
		font-size: 16px;
		font-family: "Batch", "Microsoft Yahei";
	}

	.user-nav a:hover {
		background-color: #2f303a;
	}

	.user-nav a .icons {
		margin-right: 10px;
	}
	</style>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content clear">
			
			<div class="left user-nav">
				<div class="user-card">

					<?php echo $_SESSION['username']; ?>
				</div>
				<nav class="navigation">
					<ul>
						<li>
							<a href=""><span class="icons">&#xF133</span>dasd</a>
						</li>
						<li>
							<a href=""><span class="icons">&#xF133</span>消息</a>
						</li>
						<li><a href="">dasd</a></li>
						<li><a href="">sdas</a></li>
						<li><a href="">dsad</a></li>
						<li><a href="">dasd</a></li>
						<li><a href="">dsad</a></li>
						<li><a href="">ds</a></li>
						<li><a href="">dasds</a></li>
						<li><a href="">sadas</a></li>
					</ul>
				</nav>
			</div>

			<div class="user-content">
				
			</div>


			<a href="logout.php">LOGOUT</a>
		</div>
	</div>
</body>
</html>


