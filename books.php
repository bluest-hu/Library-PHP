<?php 
session_start();

header("Content-Type: text/html;charset=utf-8"); 

include(dirname(__FILE__) . "../config.php");
include(dirname(__FILE__) . "../function.php");
include(dirname(__FILE__) . "../class/mysql.class.php");
include(dirname(__FILE__) . "../class/book.class.php");
include(dirname(__FILE__) . "../class/category.class.php");

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
    <style type="text/css">

	.main .content .books {
		/*background-color: #FFF;*/
		border: 1px solid #E0E0E0;
		border-radius: 4px;
		box-shadow: 0 1px 1px rgba(0,0,0,.1); 
		overflow: hidden;
		height: 100%;
		background-color: #EEE;
	}
	.book-cate-nav {
		width: 230px;
		height: inherit;
		background-color: #FFF;
		height: 100%;
		box-shadow: 1px 0px 4px rgba(0,0,0,.1); 
		position: relative;
		z-index: 99999;
	}

	.book-cate-nav .title {
		background-color: #2980B9;
		height: 80px;
		text-align: center;
		color: #FFF;
		position: relative;
	}

	.book-cate-nav .title .icons {
		text-align: center;
		position: absolute;
		display: block;
		top: 0px;
		right: 0px;
		bottom: 0px;
		left: 0px;
		margin: auto;
		width: 60px;
		height: 60px;
		font: normal 40px/60px "Batch","Microsoft YaHei" ;
		background-color: #FFF;
		border-radius: 50%;
		color: #2980B9;
	}

	.book-cate-nav a {
		display: block;
		font: lighter 14px/50px "Batch","Microsoft YaHei";
		padding-left:25px;
		color: #999;
		transition: all .5s ease;
	}
	.book-cate-nav a .icons {
		margin-right: 6px;
		font-size: 15px;
		transition: all .5s ease;
	}

	.book-cate-nav a .count {
		background-color: #999;
		color: #FFF;
		font-size: 10px;
		padding: 0px 7px;
		margin: 15px 15px 15px 0;
		border-radius: 15px;
		line-height: 20px;
		/*display: none;*/
		/*text-align: right;*/
		float: right;
		font-family: Arial;
		transition: all .5s ease;
	}


	.book-cate-nav a:hover{
		background-color: #777;
		color: #FFF;
	}
	 .book-cate-nav a:hover .count {
		background-color: #FFF;
		color: #999;
	}

	.book-cate-nav li.current a {
		background-color: #3498DB;
		color: #FFF;
	}

	.book-cate-nav li.current a .count {
		background-color: #FFF;
		color: #3498DB;
	}
	

	.book-cate-nav li.current {
		position: relative;
	}

	.book-cate-nav li.current:after {
		position: absolute;
		right: 0px;
		width: 0px;
		height: 0px;
		display: block;
		top: 10px;
		right: -5px;
		content: "";
		border: 15px solid transparent;
		border-right-color:#EEE; 
		z-index: 9;
	}

	.books-list {
		width: 760px;
		padding: 20px;
		padding-top: 0px;
		padding-right: 0px;
		padding-left: 8px;
		
	}

	.books-list .book {
		margin:15px 10px;
		width: 105px;
		float: left;
		background-color: #FFF;
		box-shadow: 0 1px 8px rgba(0, 0, 0, .3);
	}

	.books-list .book-cover {
		width: inherit;
		height: 160px;
		display: block;
	}

	.books-list .book-title {
		width: inherit;
		font: normal 14px/30px "Microsoft YaHei";
		display: block;
		text-align: center;
		color: #333;
	}

	.books-list .title {
		border-bottom: 1px solid #E0E0E0;
		text-indent: 30px;
		line-height: 80px;
		margin-bottom: 15px;
		background: #FFF;
		margin-left: -10px;
		color: #666;
		box-shadow: 0px 1px 4px rgba(0,0,0,.1);
	}

	.book-list-nav {
		margin: 30px 0 10px 0;
		text-align: center;
	}

	.book-list-nav ul {
		font-size: 0px;
		display: inline-block;
		background-color: #FFF;
		border-radius: 4px;
		overflow: hidden;
		box-shadow: 0 1px 1px rgba(0,0,0,.1);
	}

	.book-list-nav ul li {
		display: inline-block;
	}


	.book-list-nav ul li a,
	.book-list-nav ul li span {
		display: block;
		padding:  12px 8px;
		font-size: 14px;
		color: #7E7E7E;
	}

	.book-list-nav ul li a.page {
		padding:  12px 18px;
	}


	.book-list-nav ul li a:hover,
	.book-list-nav ul li span:hover {
		background-color: #FAFAFA;
		color: #666;
	}

	.book-list-nav ul li.current a {
		background-color: #3498DB;
		color: #FFF;
	}

	.book-list-nav ul li a:hover {
		background-color: 
	}

	.expland {
		width: 100%;
		height: 200px;
		margin-left: -8px; 
		padding-right: 10px;
		clear: both;
		background-color:#333; 
		display: none;
		position: relative;
		z-index: 99000;
	}
    </style>

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
<script type="text/javascript">

$(function () {

	var mainHeight = $("#mianContent").height()-2;
	var navHeight = $("#bookCateNav").height();
	var height = mainHeight > navHeight ? mainHeight : navHeight;
	$("#bookCateNav").css("height", height);
});



$(function() {


	var html = $('<li class="expland"></li>')
	// 插入的行数
	var $books = $(".books-list-container .book");
	var sumCount = $books.length;

	// 插入的第几行
	var appendRowIndex = 0;
	// 每行的图书数目
	var eachColumnCount = 6;
	//插入的书的索引
	var appendBookIndex = 0;

	var lastappendBookIndex = null;

	$books.on("click", function () {

		html.css("display", "none");

		if (lastappendBookIndex != appendBookIndex) {
			html.slideUp();
		} 

		var bookIndex = $(this).index();

		appendRowIndex = parseInt((bookIndex) /eachColumnCount ) + 1;

		appendBookIndex = appendRowIndex * eachColumnCount - 1;


		if ($books.get(appendBookIndex)){
			$books.eq(appendBookIndex).after(html);
		} else {
			$books.eq(sumCount - 1).after(html);
		}
		
		if (lastappendBookIndex != appendBookIndex) {
			html.slideDown();	
		} else {
			html.css("display", "block");
		}

		lastappendBookIndex = appendBookIndex;

	});
});
</script>
</html>