<?php 
session_start();

header("Content-Type: text/html;charset=utf-8"); 

include(dirname(__FILE__) . "../config.php");
include(dirname(__FILE__) . "../function.php");
include(dirname(__FILE__) . "../class/mysql.class.php");
include(dirname(__FILE__) . "../class/book.class.php");
include(dirname(__FILE__) . "../class/category.class.php");
include(dirname(__FILE__) . "../class/borrow_book.class.php");


$WARN_MESSAGE = array();

if ($_GET) {
	if ($_GET['action'] == "list_book") {
		$cate_id = (int) isset($_GET['cate_id']) ? $_GET['cate_id'] : 0;
		$page = (int)isset($_GET['page']) ? $_GET['page'] : 1; 
	} else {
		$cate_id = 0;
		$page = 1;
	}
} else {
	$cate_id = 0;
	$page = 1;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Books</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books_add.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books.css" />
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content" id="mianContent">
			<div class="books clear">
				<nav class="book-cate-nav left" id="bookCateNav">
					<h2 class="title"><span class="icons">&#xF0D3</span></h2>
					<ul>
						<li class="<?php echo ($cate_id == 0) ? 'current' : ' ' ;?>" data-id="0">
							<a href="<?php echo $BASE_URL . $_SERVER['PHP_SELF'] . '?action=list_book&cate_id=0&page=1';?>">
								<span class="icons">&#xF0AB</span>
								All
								<span class="count"><?php echo Book::get_books_sum(0);?></span>
							</a>
						</li>
<?php 

$cate_arr = Category::get_all();
foreach ($cate_arr as $key => $value) { 
	$url = $BASE_URL . $_SERVER['PHP_SELF'] . '?action=list_book&cate_id=' . $value['id'] . "&page=1";
	if ((int)$value['id'] == $cate_id) {
		$nav_curr = "current";
		$curr_cate_name = $value['name'];
	} else {
		$nav_curr = "";
	}
?>

						<li data-id="<?php echo $value['id'];?>" class="<?php echo $nav_curr;?>">
							<a href="<?php echo  $url ?>">
								<span class="icons">&#xF0AB</span>
								<?php echo $value['name'];?>
								<span class="count"><?php echo Book::get_books_sum($value['id']);?></span>
							</a>
						</li> 

<?php } ?>
					</ul>
				</nav>


<?php $curr_cate_name; ?>
				
<?php 

// 输出
Book::get_book_list($cate_id, $page, 18);
?>
				
		</div>
	</div>
	<?php include("templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/book_list.js"></script>
 
</html>