<?php 
session_start();
include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../class/category.class.php");

// 当前主页是否为自己的
$is_my_page = false;
$get_user_name = "";

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"])) {
	header("Location:" . $BASE_URL . "login.php"); 
}

$WARN_MESSAGE = array();

Category::add_new("fff", NULL, $WARN_MESSAGE);


print_r($WARN_MESSAGE);

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
	<style type="text/css">
	.content {
		background-color: #FFF;
		margin-bottom: 50px;
		border-radius: 4px;
	}
	.user-nav {
		border-radius: 4px;
		border-top-right-radius: 0px;
		border-bottom-right-radius: 0px;
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
		/*border-top-right-radius: 8px;*/
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
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
	

			<div class="catagory clear">
				<div class="catagory-add left">
					<form action="POST">
						<p>
							<label for="">分类名：</label>
							<input type="text">
							<span class="description">书籍的所属分类</span>
						</p>
						<p>
							<label for="">分类描述：</label>
							<textarea></textarea>
							<span class="description"></span>
						</p>
						<p>
							<input type="submit">
						</p>
					</form>
				</div>
				<div class="catagory-show right">
					<table>
						
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php include(dirname(__FILE__) . "../../templ/footer.temp.php");?>
</body>
</html>


