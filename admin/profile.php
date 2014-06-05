<?php 
session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/user.class.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");
include(dirname(__FILE__) . "../../class/borrow_book.class.php");

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"]) && $_SESSION['level'] < 1) {
	header("Location:" . $BASE_URL . "/login.php"); 
}

$u_id = $_SESSION['user_id'];

$user = User::Get_info_by_id($u_id);

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
    <style type="text/css">
		.user-profile .user-info .avatar {
			width: 120px;
			height: 120px;
			display: block;
			margin-right: 20px;
			border-radius: 4px;
		}
		
		.edit {
			color: #3498DB;
			font: normal 12px/25px "Microsoft Yahei"; 
		}


	.user-info-more {
		color: #666;
		font-size: 14px;
		line-height: 24px;
	}

	.borrow-info {
		color: #666;
		line-height: 30px;
	}

	.borrow-info .count {
		border:  1px solid #E0E0E0;
		padding: 1px 4px;
		text-align: center;
		border-radius: 3px;
		margin: 2px;
		background-color: #EFEFEF;
	}
    </style>
</head>
<body>
	<div class="main">
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="right-container right">
				<h2 class="title"><span class="icons">&#xF0E3</span>用户信息</h2>
				<div class="catagory right-content clear">
					<div class="user-profile left">
						<h3 class="title">
							用户信息
							<a class="edit" href="<?php echo $BASE_URL . "/admin/profile_edit.php" ?>">编辑</a>
						</h3>

						<div>
							<div class="user-info clear">
								<img  class="avatar left" src="<?php echo $_SESSION['avatar'] ?>" alt="">
								<div class="left user-info-more">
									<span class="username">
										<span class="icons">&#xF170</span>
										<b>用户名：</b>
										<?php echo $user['name'] ?>
									</span>
									<br>
									<span>
										<span class="icons">&#xF0CD</span>
										<b>用户等级：</b>
										<?php
										switch($_SESSION['level']) {
											case 0:
												echo "普通用户";
												break;
											case 1:
												echo "管理员";
												break;
											default:
												echo "";		
												break;
										}		
										?>
									</span>
									<br>
									<span>
										<span class="icons">&#xF046</span>
										<b>性别：</b>
										<?php echo User::deal_with_sex($user['sex']); ?>
									</span>
									<br>
									<span>
										<span class="icons">&#xF07C</span>
										<b>地址：</b>
										<?php echo $user['location']; ?>
									</span>
								<!-- 	<br>
									<span>
										<span class="icons">&#xF046</span>
										<b>生日：</b>
										<?php echo $user['location']; ?>
									</span> -->
								</div>
							</div>
						</div>

						<h3 class="title" style="margin-top:20px;">
							借阅信息

							<?php if($_SESSION['level']  == 1) { ?>
								<a class="edit" href="<?php echo $BASE_URL . "/admin/borrow_detail.php" ?>">详细</a>
							<?php } else { ?>
								<a class="edit" href="<?php echo $BASE_URL . "/admin/borrow_detail_no_action.php" ?>">详细</a>
							<?php } ?>
						</h3>

						<div class="borrow-info">
							
							<p>
								等待同意：
								<b class="count"><?php echo  count(Borrow::get_borrowed_info_user_id($u_id, false, false)); ?></b>本
							</p>
							<p>已经超期：
								<b class="count"><?php echo count(Borrow::get_extended_info($u_id, 60)); ?></b>本
							</p>
							<p>正在借阅：
								<b class="count"><?php echo count(Borrow::get_borrow_info($u_id)); ?></b>本
							</p>
							<p>
								曾经借阅：
								<b class="count"><?php echo count(Borrow::get_completed_info($u_id)); ?></b>本
							</p>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include(dirname(__FILE__) . "../../templ/footer.temp.php");?>
</body>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
</html>


