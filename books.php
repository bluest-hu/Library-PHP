<?php 
session_start();

header("Content-Type: text/html;charset=utf-8"); 

include(dirname(__FILE__) . "../config.php");
include(dirname(__FILE__) . "../function.php");
include(dirname(__FILE__) . "../class/mysql.class.php");
include(dirname(__FILE__) . "../class/book.class.php");
include(dirname(__FILE__) . "../class/category.class.php");

$WARN_MESSAGE = array();

$can_submit = TRUE;

$sql = new MySQLDatabase($DATABASE_CONFIG);

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
    <style type="text/css">

	.main .content .books {
		background-color: #FFF;
		border: 1px solid #E0E0E0;
		border-radius: 4px;
		box-shadow: 0 1px 1px rgba(0,0,0,.1); 
		overflow: hidden;
	}
	.book-cate-nav {
		width: 230px;
		height: inherit;
		background-color: #3498DB;

	}
	.book-cate-nav a {
		display: block;
		font: normal 18px/50px "Microsoft YaHei";
		text-indent: 2em;
		color: #FFF;
	}

	.book-cate-nav a:hover {
		background-color: #2980B9;
	}

	.books-list {
		width: 760px;
		padding: 20px;
		padding-right: 0px;
		padding-left: 5px;
	}

	.books-list .book {
		margin:15px 10px;
		width: 105px;
		float: left;
		background-color: #FFF;
		box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
	}


	.books-list .book-cover {
		width: inherit;
		height: 160px;
		display: block;
	}

	.books-list .book-title {
		width: inherit;
		font-size: 12px;
		display: block;
		text-align: center;
	}

	.book-list-nav {
		margin: 10px 0;
	}

	.book-list-nav ul {
		text-align: center;
		font-size: 0px;
	}

	.book-list-nav ul li {
		display: inline-block;
	}

	.book-list-nav ul li a,
	.book-list-nav ul li span {
		display: block;
		padding:  4px 10px;
		background-color: #E0E0E0;
		font-size: 15px;
	}
    </style>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content ">

			<div class="books clear">
				<nav class="book-cate-nav left">
					<h2 class="titile"><span clss="icons"></span>图书分类</h2>
					<ul>
						<li><a href="<?php echo $BASE_URL . $_SERVER['PHP_SELF'] . '?action=list_book&cate_id=0&page=1';?>">All</a></li>
<?php 

$cate_arr = Category::get_all();
// print_r(Category::cate_is_exit(0));

foreach ($cate_arr as $key => $value) { 

	$url = $BASE_URL . $_SERVER['PHP_SELF'] . '?action=list_book&cate_id=' . $value['id'] . "&page=1";
?>
						<li data-id="<?php echo $value['id'];?>">
							<a href="<?php echo  $url ?>"> <?php echo $value['name'];?> </a>
						</li> 

<?php } ?>
					</ul>
				</nav>

				
<?php 

if (is_null($sql)) {
	$sql = new MySQLDatabase($DATABASE_CONFIG);
}
		

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


// 输出
Book::get_book_list($cate_id, $page, 18);
?>
				
		</div>
	</div>
	<?php include("templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
<script type="text/javascript">
</script>
</html>