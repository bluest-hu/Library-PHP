<?php 
session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/user.class.php");
include(dirname(__FILE__) . "../../class/borrow_book.class.php");
include(dirname(__FILE__) . "../../class/book.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");

// 当前主页是否为自己的
$is_my_page = false;
$get_user_name = "";

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"]) && $_SESSION['level'] = 0) {
	header("Location:" . $BASE_URL . "login.php"); 
}

if ($_GET) {
	if ($_GET['u_id']) {
		$user_id = $_GET['u_id'];
	} 
} else {
	$user_id = $_SESSION['user_id'];	
}


$user_info = User::get_info_by_id($user_id);
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

    .book-cover {
    	width: 60px;
    	display: block;
    	margin-right: 10px;
    }

    </style>
</head>
<body>
	<div class="main">
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="right-container right">
				<div class="main-title">
					<span class="icons">&#xF0E3</span>用户 <?php echo $user_info['name'];?> 的借阅管理
					<div style="float:right;margin-right:25px;">
					</div>
					
				</div>
<?php 

$wait_for_agree = Borrow::get_borrowed_info_user_id($user_id, false, false);
?>

				<div class="catagory right-content clear">
					<h3 class="title">等待管理员同意的书目：<?php echo count($wait_for_agree);?> 本</h3>
	<table>
		<thead>
			<tr>
				<th>序号</th>
				<th>书籍信息</th>
				<th>操作</th>
			</tr>
		</thead>			
<?php
$index = 0;
foreach ($wait_for_agree as $key => $value) {
$index++;
	
	$book_info = Book::get_book_info_by_id($value['book']);
?>
<tr>
	<td><?php echo $index; ?></td>
	<td>
		<img  class="book-cover left" src="<?php echo $book_info['cover'] ?>" alt="<?php echo $book_info['name'] ?>">
		<div class="borrow-detial">
			<p>
				<b>图书名：</b>
				<span class="title">《<?php echo $book_info['name'] ?>》</span>
			</p>
			<p>
				<b>申请时间：</b>
				<span class="date"><?php echo $value['borrow'] ?></span>
			</p>
			<p>
				<b>图书总数：</b>
				<span class="sum"><?php echo $book_info['sum']; ?></span>
			</p>
			<p>
				<b>剩余数目：</b>
				<span class="reduce"><?php echo $book_info['sum'] - $book_info['borrow'] ?></span>
			</p>
			
		</div>
	</td>
	<td>
	<?php 
		if ($user_info['level'] == 1 && $user_info['active'] == 1) {
	?>
		<a  class="btn agree" href="">同意借阅</a>	
	<?php } ?>
	</td>
</tr>


	
<?php
}

?>
	
					</table>
<?php 
$wait_for_agree = Borrow::get_extended_info($user_id, 60);
?>
					<h3 class="title">已经超期的书目：<?php echo count($wait_for_agree); ?> 本</h3>

	<table>
		<thead>
			<tr>
				<th>序号</th>
				<th>书籍信息</th>
				<th>欠款信息</th>
				<th>操作</th>
			</tr>
		</thead>	

<?php
$index = 0;
foreach ($wait_for_agree as $key => $value) {
	
	$book_info = Book::get_book_info_by_id($value['book']);
	$index++;
?>
<tr>
	<td>
		<?php echo $index; ?>
	</td>
	<td>
		<img  class="book-cover left" src="<?php echo $book_info['cover'] ?>" alt="<?php echo $book_info['name'] ?>">
		<div class="borrow-detial">
			<p>
				<b>图书名：</b>
				<span class="title">《<?php echo $book_info['name'] ?>》</span>
			</p>
			<p>
				<b>借阅时间：</b>
				<span class="date"><?php echo $value['accepte'] ?></span>
			</p>
			<p>
				<b>图书总数：</b>
				<span class="sum"><?php echo $book_info['sum']; ?></span>
			</p>
			<p>
				<b>剩余数目：</b>
				<span class="reduce"><?php echo $book_info['sum'] - $book_info['borrow'] ?></span>
			</p>
			
		</div>
	</td>
	<td>
		<p>
			<span>逾期 <?php echo $value['extended']; ?> 天</span>
		</p>
		<p>
			<span class="money">欠费 <?php echo $value['extended'] * 0.5; ?> 元</span>
		</p>
	</td>
	<td>
		<?php 
			if ($user_info['level'] == 1 && $user_info['active'] == 1) {
			?>
			<a  class="btn agree" href="">缴纳欠款</a>	
			<?php 
			} 
			?>
	</td>
</tr>
<?php
}

?>
</table>

					<h3 class="title">正在借阅的书目：</h3>



					<h3 class="title">已经归还的书目：</h3>
				</div>
			</div>
		</div>
	</div>
	<?php include(dirname(__FILE__) . "../../templ/footer.temp.php");?>
</body>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
</html>


