<?php 
session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");
include(dirname(__FILE__) . "../../class/book.class.php");


if ($_GET) {
	if($_GET['action'] == "book_del") {
		$del_book_id = (int)$_GET['book_id'];

		if (Book::del_by_id($del_book_id)) {
			header("location:" .  $BASE_URL . "/admin/books.php");
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
	.book-cover {
		width: 50px;
		margin: 10px 15px 10px 0px;
	}

	.book-list {
		padding: 30px;
	}

	table {
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


	.del-btn,
 	.update-btn {
		color: #E74C3C;
		border: 1px solid #E74C3C;
		border-radius: 3px;
		padding: 2px 6px;
		font-size: 13px;
	}

	.update-btn {
		margin-left: 5px; 
		color:#2980B9;
		border-color:#2980B9;
	}

	.update-btn:hover {
		color: #FFF;
		background-color:#2980B9;
	}

	.del-btn:hover {
		color: #FFF;
		background-color: #E74C3C;
	}
    </style>
</head>
<body>
	<div class="main">
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="right-container left">
				<h2 class="title">
					<span class="icons">&#xF0E3</span>
					书籍管理：
				</h2>
				<div class='book-list'>
					<h3 class="title">书籍列表：</h3>
					<table>
						<thead>
							<tr>
								<th>序号</th>
								<th>封面</th>
								<th>添加时间</th>
								<th>借阅信息</th>
								<th>操作</th>
							</tr>
						</thead>
						
						
<?php $book_all = Book::get_all();
$i = 0; 
foreach ($book_all as $key => $value) {
	$i++;
?>
<tr>
	<td><?php echo $i ;?></td>
	<td>
		<img class="book-cover left" src="<?php echo $BASE_URL ."/image/book_covers/" .$value['cover'];?>"/>
		<div class="left">
			<p>
				<b>书名：</b>
				《<?php echo $value['name'];?>》
			</p>
			<p >
				<b>出版社：</b>
				<?php echo is_null($value['publisher']) ? "未知" : $value['publisher'] ; ?>
			</p >
			<p>
				<b>作者：</b>
				<?php echo is_null($value['author']) ? "未知" : $value['author'] ; ?>
			</p >
			<p >
				<b>分类：</b>
				<?php
					$__cate_name;
					$cate_url = $BASE_URL . "/books.php?action=list_book&cate_id=". $value['cate']."&page=1";
					if ((int)$value['cate'] === 0) {
						$__cate_name = "未分类";
					}  else {
						$__cate_name =  Category::get_cate_name_by_id((int)$value['cate']) . "sdasd"; 
					}
				?>

				<a href="<?php echo $cate_url?>"><?php echo $__cate_name; ?></a>
			</p>
		</div>
	</td>
	<td><?php echo date("Y-m-d", strtotime($value['date'])); ?></td>
	
	<td>
		<p>
			<b>共有：</b><?php echo $value['sum']; ?>本
		</p>
		<p>
			<b>借出：</b><?php echo $value['borrow']; ?>本
		</p>
	</td>
	<td>
	<?php 
		$del_url = $_SERVER['PHP_SELF']. '?action=book_del&book_id=' . (int)$value['ID'];
		$update_url = $BASE_URL . '/admin/book_update.php?action=book_update&book_id=' . (int)$value['ID'];
	?>
		<a class="del-btn" href="<?php echo $del_url; ?>">删除</a>
		<a class="update-btn" href="<?php echo $update_url ?>">修改</a>
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


