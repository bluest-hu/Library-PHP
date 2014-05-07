<?php 
session_start();

// 当前主页是否为自己的
$is_my_page = false;
$get_user_name = "";

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"])) {
	header("Location:" . $BASE_URL . "login.php"); 
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
	<link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/main.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
	<style type="text/css">

	.user-nav {
		border-radius: 8px;
		width: 250px;
		background-color: #373942;
	}

	.user-nav .navigation {
		padding-bottom: 20px;
	}

	.user-nav .user-card {
		height: 100px;
		position: relative;
		overflow: hidden;
		border-top-left-radius: 8px;
		border-top-right-radius: 8px;
		padding-bottom: 20px; 
	}

	.user-avastar {

	}

	.user-card .user-bg {
		position: absolute;
		width: 254px;
		height: 104px;
		top: -2px;
		left: -2px;
		-webkit-filter: blur(2px);
		-moz-filter: blur(2px);
		-ms-filter: blur(2px);
		filter: blur(2px);
	}
	
	.user-card .user-info {
		position: absolute;
		padding: 20px;
	}

	.user-nav .navigation a {
		padding-left: 45px;
		display: block;
		color: #7b7d86;
		line-height: 40px;
		font-size: 16px;
		font-family: "Batch", "Microsoft Yahei";
	}

	.user-nav a:hover {
		background-color: #2f303a;
		color: #EAEAEA;
	}

	.user-nav a .icons {
		margin-right: 15px;
	}
	</style>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content clear">
			
			<div class="left user-nav">
				<div class="user-card">
					<img class="user-bg" src="<?php echo $_SESSION['user_bg']; ?>" alt="">
					<div class="user-info">
						<a class="avatar avatar-50 left user-avastar" href="<?php echo $BASE_URL . "/user.php?user=" . $_SESSION['username'] ?>">
							<img class="avatar avatar-50" src="<?php echo isset($_SESSION['avatar']) ?  $_SESSION['avatar'] : "image/default.png";  ?>">
						</a>

						<?php echo $_SESSION['username']; ?>
						<span class="text-normal level">
						<?php
						switch($_SESSION['level']) {
							case 0:
								echo "Super";
								break;
							case 1:
								echo "2B用户";
								break;
							case 2:
								echo "Admin";
								break;
							default:
								echo "";		
								break;
						}		
						?>
						</span>
					</div>
					
				</div>
				<nav class="navigation">
					<ul>
						<li>
							<a href=""><span class="icons">&#xF080</span>Profile</a>
						</li>
						<li>
							<a href=""><span class="icons">&#xF133</span>Message</a>
						</li>
						<li>
							<a href=""><span class="icons">&#xF04D</span>Settings</a>
						</li>
						<li>
							<a href=""><span class="icons">&#xF0D2</span>Books</a>
						</li>
						<li>
							<a href=""><span class="icons">&#xF171</span>Invate</a>
						</li>
						
					</ul>
				</nav>
			</div>

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


