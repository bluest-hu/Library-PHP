<?php 
session_start();

include(dirname(__FILE__) . "/config.php");
include(dirname(__FILE__) . "/function.php");
include(dirname(__FILE__) . "/class/mysql.class.php");
include(dirname(__FILE__) . "/class/category.class.php");

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"]) && $_SESSION['level'] < 1) {
	header("Location:" . $BASE_URL . "login.php"); 
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
		<?php include(dirname(__FILE__) . "/templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "/templ/usernav.temp.php"); ?>
			<div class="right-container right">
				<h2 class="title"><span class="icons">&#xF0E3</span>用户信息</h2>
				<div class="catagory right-content clear">
					<div class="user-profile left">
						<h3 class="title">
							用户信息
						</h3>

						<div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include(dirname(__FILE__) . "/templ/footer.temp.php");?>
</body>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
</html>


