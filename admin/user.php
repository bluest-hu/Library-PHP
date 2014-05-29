<?php 
session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/user.class.php");
include(dirname(__FILE__) . "../../class/borrow_book.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");

// 当前主页是否为自己的
$is_my_page = false;
$get_user_name = "";

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"]) && $_SESSION['level'] < 1) {
	header("Location:" . $BASE_URL . "login.php"); 
}

$WARNING_MESSAGE = array();
$SECESS_MESSAGE = array();

if ($_GET) {

	if ($_GET['u_id']) {
		$user_id = $_GET['u_id'];

		$__user_info = User::get_info_by_id($user_id);

		if ($__user_info) {
			$__has_borrowed_count = count(Borrow::get_borrowed_info_user_id($user_id, true, false));
			$__has_extended_count = count(Borrow::get_extended_info($user_id, 60));

			if ($_GET['action'] == "deactive_user") {
				if ($__has_borrowed_count > 0 || $__has_extended_count > 0) {
					array_push($WARNING_MESSAGE, "无法禁用用户");
				} else {
					if(User::deactive_by_id($user_id)) {
						header("location:" . $BASE_URL . "/admin/user.php");
					}
				}
			} elseif ($_GET['action'] == "del_user") {
				if ($__has_borrowed_count > 0 || $__has_extended_count > 0) {
					array_push($WARNING_MESSAGE, "无法删除用户");
				} else {
					if(User::del_by_id($user_id)) {
						if(Borrow::del_by_user_id($user_id)) {
							header("location:" . $BASE_URL . "/admin/user.php");
						}
					}
				}
			} elseif ($_GET['action'] == "upgrade_user") {
				if(User::set_as_admin_by_id($user_id)) {
					header("location:" . $BASE_URL . "/admin/user.php");
				}

			}elseif ($_GET['action'] =='active_user') {
				if(User::active_by_id($user_id)) {
					header("location:" . $BASE_URL . "/admin/user.php");
				}
			}
		} else {
			// bande
			array_push($WARNING_MESSAGE, "用户不存在");
		}
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
    <style type="text/css">

	table {
		width: 100%;
		border-radius: 2px;
	}

	table td {
		font-size: 13px;
		color: #999;
	}

	table a {
		color: #3498DB;
	}
	
	table tr th {
		border-bottom: 1px solid #E0E0E0;
		color: #666;
	}

	table  tr th，
	thead tr td p {
		color: #666;
		border-bottom: 1px solid #E0E0E0;
		font-size: 14px;
	}

	.avatar {
		margin-right: 10px;
	}

	.action-area {
		width: 180px;
	}

	.action-area .action {
		color: #E74C3C;
		border: 1px solid #E74C3C;
		border-radius: 3px;
		padding: 2px 6px;
		font-size: 12px;
	}
	.action-area .action:hover {
		color: #FFF;
		background-color: #E74C3C;
	}

	.action-area span.action {
		color: #888;
		border-color: #888;
	}

	.action-area span.action:hover {
		background-color: #888;
		color: #FFF;
	}

	.action-area .active {
		color: #27AE60;
		border-color: #27AE60;
	}

	.action-area .active:hover {
		background-color: #27AE60;
	}

	.action-area .deactive {
		color: #8E44AD;
		border-color: #8E44AD;
	}

	.action-area .deactive:hover {
		background-color: #8E44AD;
	}

	.action-area .upgrade {
		color: #2980B9;
		border-color: #2980B9;
	}

	.action-area .upgrade:hover {
		background-color: #2980B9;
	}
	
	.borrow-info p {
		clear:both;
	}
	.borrow-info b {
		display: block;
		float: left;
		width: 80px;
	}

	.index {
		width: 50px;
	}

	.notice {
		padding:10px 20px;
		background-color:#E74C3C;
		color: #FFF;
		font-size: 12px;
		border: 1px solid #E0E0E0;
		border-bottom: none;
		border-top-left-radius: 4px;
		border-top-right-radius: 4px; 
	}

    </style>
</head>
<body>
	<div class="main">
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="right-container right">
				<h2 class="title"><span class="icons">&#xF0E3</span>用户管理</h2>
				<div class="catagory right-content clear">
					<h3 class="title">用户管理</h3>
					<div class="notice">
						<p>有书未归还或者超期的用户无法删除</p>
						<p>已经被禁用的用户无发设为管理员</p>
					</div>
<table>
<thead>
	<tr>
		<th>序号</th>
		<th>用户信息</th>
		<th>借阅详情</th>
		<th>用户操作</th>
	</tr>
</thead>
<?php 
$users = User::get_all();
$index = 0;

foreach ($users as $key => $value) {
	$index++;
	// print_r($value);
?>
	<tr>
		<td class="index">
			<?php echo $index; ?>
		</td>
		<td>
			<img class="avatar left" style="width:50px;" src="<?php echo $value['avatar'];?>" alt="">
			<div>
				<p>
					<b>用户名：</b>
					<a href="<?php echo $BASE_URL . "/admin/borrow_detail.php?u_id=". $value['ID'] ?>"><?php echo $value['name']; ?></a> 
				</p>
				<p>
					<b>等级：</b>
					<?php echo $value['level'] == 0 ? "普通用户" : "管理员"; ?>
				</p>
				<p>
					<b>状态：</b>
					<?php echo $value['active'] == 1 ? "正常" : "禁用" ?>
				</p>
			</div>
		</td>
		<td class="borrow-info">

			<p>
				<b>已经借阅数：</b>
				<?php echo $has_borrowed_count = count(Borrow::get_borrowed_info_user_id($value['ID'], true, false))?>
			</p>
			<p>
				<b>等待批准数：</b>
				<?php echo count(Borrow::get_borrowed_info_user_id($value['ID'], false, false))?>
			</p>
			<p>
				<b>超期数：</b>
				<?php echo $has_extended_count = count(Borrow::get_extended_info($value['ID'], 60)); ?>
			</p>
			<p>
				<b>剩余额度数：</b>
				<?php  echo 10 - $has_borrowed_count; ?>
			</p>
		</td>
		<td class="action-area">
			<?php 
			$del_user_url = $BASE_URL . "/admin/user.php?action=del_user&u_id=" . $value['ID'];
			$deactive_user_url = $BASE_URL . "/admin/user.php?action=deactive_user&u_id=" . $value['ID'];
			$acitve_user_url = $BASE_URL . "/admin/user.php?action=active_user&u_id=" . $value['ID'];
			$upgrade_user_url = $BASE_URL . "/admin/user.php?action=upgrade_user&u_id=" . $value['ID'];
			?>
			<?php if ($has_extended_count > 0 || $has_borrowed_count > 0 || $value['active'] == 0) {?>
				<span class="action ">禁用</span>
				<span class="action ">删除</span>
			<?php } else { ?>
				<a class="action deactive" href="<?php echo $deactive_user_url ?>">禁用</a>
				<a class="action del" href="<?php echo $del_user_url ?>">删除</a>
			<?php } ?>

			<?php if ($value['level'] != 1 ) { ?>
				<a class="action upgrade" href="<?php echo $upgrade_user_url ?>">管理员</a>
			<?php } else {?>
				<span class="action">管理员</span>
			<?php }?>
			
			<?php if ($value['active'] == 0 ) { ?>
				<a class="action active" href="<?php echo $acitve_user_url ?>">激活</a>
			<?php } else {?>
				<span class="action">激活</span>
			<?php }?>
			
		</td>
	</tr>

<?php
}

?>
</table>
				</div>
			</div>
		</div>
	</div>
	<?php include(dirname(__FILE__) . "../../templ/footer.temp.php");?>
</body>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
</html>


