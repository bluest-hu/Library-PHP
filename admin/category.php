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

$WARN_MESSAGE = array();

if ($_GET) {
	if ($_GET['action'] === "add_cate") {
		if ($_POST) {
			
			$cate_name = $_POST['cate_name'];
			$cate_desc = $_POST['cate_descrption'];


			if (Category::add_new($cate_name, $cate_desc,$WARN_MESSAGE )) {
				echo "sucsess";
			} else {
				print_r($WARN_MESSAGE);
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
</head>
<body>
	<div class="main">
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="right-container right">
				<h2 class="title"><span class="icons">&#xF0E3</span>分类管理</h2>
				<div class="catagory right-content clear">
					<div class="catagory-add left">
						<h3 class="title">
							添加分类
						</h3>
						<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?action=add_cate"; ?>" >
							<p>
								<label class="" for="">分类名：</label>
								<input class="category-name" type="text" id="categoryName" name="cate_name">
							</p>
							<p>
								<label class="" for="">分类描述：</label>
								<textarea name="cate_descrption"></textarea>
								<span class="description"></span>
							</p>
							<p>
								<input type="submit">
							</p>
						</form>
					</div>
					<div class="catagory-show right">
						<table border="1" style="border-collapse:collapse;">
							<caption>块级元素主要有：</caption>
							<thead>
								<tr>
									<th colspan="8">块级元素列表</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>address</td>
									<td>地址</td>
									<td>blockquote</td>
									<td>块引用</td>
									<td><del>center</del></td>
									<td>居中对齐块</td>
									<td>dir</td>
									<td>目录列表</td>

								</tr>
								<tr>
									<td>dl</td>
									<td>定义列表</td>
									<td>div</td>
									<td>块级元素</td>
									<td>filedset</td>
									<td>form控制组</td>
									<td>form</td>
									<td>交互表单</td>
								</tr>
								<tr>
									<td>h1</td>
									<td>大标题</td>
									<td>h2</td>
									<td>副标题</td>
									<td>h3</td>
									<td>3级标题</td>
									<td>h4</td>
									<td>4级标题</td>
								</tr>
								<tr>
									<td>h5</td>
									<td>5级标题</td>
									<td>h6</td>
									<td>6级标题</td>					
									<td>hr</td>
									<td>水平分割线</td>
									<td><del>menu</del></td>
									<td>菜单列表</td>
								</tr>
								<tr>
									<td>ol</td>
									<td>排序表单</td>
									<td> p</td>
									<td>段落</td>
									<td>table</td>
									<td>表格</td>
									<td>ul</td>
									<td>非排序列表</td>
								</tr>
							</tbody>
						</table>

					<?php
						// $cate_arr = Category::get_all();

						// foreach ($cate_arr as $key => $value) {
						// 	echo '<span class="iteams" data-id="'. $value["id"]. '">' . $value['name'] . '</span>';
						// }

					?>
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


