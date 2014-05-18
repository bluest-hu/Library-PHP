<?php 
session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");

// 当前主页是否为自己的
$is_my_page = false;
$get_user_name = "";

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"]) && $_SESSION['level'] < 1) {
	header("Location:" . $BASE_URL . "login.php"); 
}

$CATE_ADD_WARN_MESSAGE = array();


$cation_url = "";

if ($_GET) {
	if ($_GET['action'] === "add_cate") {
		if ($_POST) {
			
			$cate_name = $_POST['cate_name'];
			$cate_desc = $_POST['cate_descrption'];

			echo $cate_desc;

			if (Category::add_new($cate_name, $cate_desc,$CATE_ADD_WARN_MESSAGE )) {
				header("location:" . $BASE_URL . "/admin/category.php");
				// echo "sucsess";
			} else {
				// print_r($CATE_ADD_WARN_MESSAGE);
			}
		}
	} else if ($_GET['action'] == 'cate_del') {
		$cate_id  = (int)$_GET['cate_id'];

		if ($cate_id != 0) {
			Category::delete_by_id($cate_id);
		}
	} else if ($_GET['action'] == 'cate_update') {
		$cate_name = $_POST['cate_name'];
		$cate_desc = $_POST['cate_descrption'];
		$cate_id  = (int)$_GET['cate_id'];

		if ($cate_id != 0) {
			$text = array();
			if (Category::update($cate_id,$cate_name, $cate_desc, $text)) {
				header("location:" . $BASE_URL . "/admin/category.php");
			}
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
		border-radius: 2px;
	}

	table td {
		font-size: 13px;
		color: #999;
	}

	.cate-list a {
		color: #3498DB;
	}

	thead tr th {
		color: #666;
		border-bottom: 1px solid #E0E0E0;
		font-size: 14px;
	}

	.cate-list .cate-index {

	}

	.cate-list .del-btn,
	.cate-list .update-btn {
		color: #E74C3C;
		border: 1px solid #E74C3C;
		border-radius: 3px;
		padding: 2px 6px;
		font-size: 13px;
	}

	.cate-list .update-btn {
		margin-left: 5px; 
		color:#2980B9;
		border-color:#2980B9;
	}

	.cate-list .update-btn:hover {
		color: #FFF;
		background-color:#2980B9;
	}

	.cate-list .del-btn:hover {
		color: #FFF;
		background-color: #E74C3C;
	}

	.add-cate-btn {
		border: 1px solid #FFF;
		color: #FFF;
		padding: 6px 12px;
		margin-left: 30px;
		border-radius: 3px;
		font-size: 12px;
		text-align: right;
 	}

 	.add-cate-btn .icons {
 		margin-right: 4px;
		font-size: 12px;
 	}

 	.add-cate-btn:hover {
 		background: #FFF;
 		color: #3498DB;
 		border-color: transparent;
 	}

	input[type="submit"] {
		background: #3498DB;
		color: #FFF;
		width: 282px;
		height: 40px;
		margin-left: 90px;
	}

	.cover {
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,.8);
		position: absolute;
		top: 0px;
		left: 0px;
		/*opacity: 0;*/
		display: none;
	}

	.category-action {
		margin: 100px auto;
		width: 400px;
		padding: 30px;
		background: #FFF;
		border: 1px solid #E0E0E0;
		border-radius: 4px;
		position: relative;
		display: none;
	}

	.close-btn {
		display: block;
		width: 25px;
		height: 25px;
		text-align: center;
		line-height: 25px;
		position: absolute;
		vertical-align: middle;
		top: 20px;
		right: 20px;
		font-size: 20px;
		color: #999;
		/*border-radius: 50%;*/
		/*background-color: #E74C3C;*/
	}

	.close-btn:hover {
		cursor: pointer;
		color: #666;
	}

	h3.title {
		line-height: 40px;
		border-bottom: 1px solid #E0E0E0;
	}
	.cate_list_action {
		min-width: 90px;
	}

	textarea {
		width: 275px; 	
	}

    </style>
</head>
<body>
	<div class="main">
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="right-container left">
				<div class="main-title">
					<span class="icons">&#xF0E3</span>分类管理
					<div style="float:right;margin-right:25px;">
						<a class="add-cate-btn" href="<?php echo $_SERVER['PHP_SELF']. '?action=add_cate'?>">
							<span class="icons">&#xF0E1</span>添加
						</a>
					</div>
					
				</div>

				<div class="catagory right-content clear">
					<div class="cate-list">
						<h3 class="title">分类列表</h3>
						<table>
							<thead class="cate-head">
								<tr>
									<th>序号：</th>
									<th>分类：</th>
									<th>描述：</th>
									<th>添加日期：</th>
									<th>操作：</th>
								</tr>
							</thead>
<?php
$cate_index = 0;
$cate_arr = Category::get_all();

foreach ($cate_arr as $key => $value) { 
	$time = strtotime($value['time']);
	$time = date("Y/m/d", $time);
	$cate_index++;
	$cate_url = $BASE_URL . "/books.php?action=list_book&cate_id=". $value['id']."&page=1";
	//截取字符串
	$cate_desc_output = empty($value['des']) ? "暂无描述" : $value['des'];

	if(strlen($cate_desc_output) > 15) {
		$cate_desc_output = mb_substr($cate_desc_output, 0, 15, 'utf-8') . "...";
	}
?>
<tr>
	<td class="cate-index"><?php echo $cate_index; ?></td>
	<td class="cate-name">
		<a href="<?php echo $cate_url ?>"><?php echo $value['name']; ?></a>
	</td>
	<td class="cate-desc"><?php echo $cate_desc_output ?></td>
	<td><?php echo $time ;?></td>
	<td class="cate_list_action">
		<a class="del-btn" href="<?php echo $_SERVER['PHP_SELF']. '?action=cate_del&cate_id=' . $value['id'];?>">删除</a> 
		<a class="update-btn" href="<?php echo $_SERVER['PHP_SELF']. '?action=cate_update&cate_id=' . $value['id'];?>">修改</a>
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
	</div>
	<?php include(dirname(__FILE__) . "../../templ/footer.temp.php");?>

	<div class="cover">

		<div class="catagory-add clear category-action">
			<h3 class="title">
				添加分类
			</h3>
			<span class="close-btn">x</span>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?action=add_cate"; ?>" >
				<p>
					<label class="" for="categoryName">分类名：</label>
					<input class="category-name" type="text" id="categoryName" name="cate_name">
				</p>
				<p>
					<label class="" for="cateDesc">分类描述：</label>
					<textarea name="cate_descrption" id="cateDesc"></textarea>
					<span class="description"></span>
				</p>
				<p>
					<input type="submit">
				</p>
			</form>
		</div>


		<div class="catagory-update clear category-action">
			<h3 class="title">
				更新分类
			</h3>
			<span class="close-btn">x</span>
			<form id="updateForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?action=cate_update"; ?>" >
				<p>
					<label class="" for="categoryNameUpdate">分类名：</label>
					<input class="category-name" type="text" id="categoryNameUpdate" name="cate_name" value="text">
					<input class="category-id"  type="hidden" name="cate_id">
				</p>
				</p>
				<p>
					<label class="" for="cateDescUpdate">分类描述：</label>
					<textarea name="cate_descrption" id="cateDescUpdate"></textarea>
					<span class="description"></span>
				</p>
				<p>
					<input type="submit">
				</p>
			</form>
		</div>
	</div>
</body>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script>
	<script type="text/javascript">

	$(function () {

		// init cover height
		$(".cover").css("height",$(document).height());

		$(".add-cate-btn").on("click", function(event) {
			$(".catagory-update").css({"display":"none"});
			$(".catagory-add").css({"display":"block"});
			$(".cover").fadeIn();

			event = event || window.event;

			event.preventDefault();
			return false;
		});



		$(".update-btn").on("click", function () {

			
			$(".catagory-update").css({"display":"block"});
			$(".catagory-add").css({"display":"none"});
			$(".cover").fadeIn();

			var parent = $(this).parent().parent();
			
			var summary = parent.find(".cate-desc").html();
			var name = parent.find(".cate-name a").html();
			$("#updateForm").attr("action",$(this).attr("href"));

			$("#categoryNameUpdate").get(0).value = name;
			$("#cateDescUpdate").val(summary);

			event = event || window.event;
			event.preventDefault();
			return false;
		});


		$(".close-btn").on("click", function () {
			$(".cover").fadeOut();
		});
	});


	</script> 
</html>


