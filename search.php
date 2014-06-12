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





?>
<!DOCTYPE html>
<html>
<head>
	<title>Books</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/datepicker/css/datepicker.css">
   	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/datepicker/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books_add.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books.css" />
    <style type="text/css">

    .book-cate-nav {
    	padding-bottom: 5px;
    }
	.book-cate-nav h3.title {
		line-height: 50px;
		height: 50px;
		background-color: #3498DB;
	}

	.book-cate-nav  label {
		display: block;
		margin-top: 5px;
		margin-left: 5px;
		line-height: 25px;
		color: #777;
	}
	.book-cate-nav input {
		width: 230px;
		height: 40px;
		border-left: none;
		border-right: none;
		border-radius: 0px;
		background-color: #EFEFEE;
	}
	.dropdown-menu {
		z-index: 9999999;
	}
	#cateDropDownInput {
		height: 30px;
	}
	
	#submit {
		display: inline-block;
		margin-top: 20px;
		background-color: #3498DB;
	}

    </style>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content" id="mianContent">
				<div class="books clear">
					<nav class="book-cate-nav left" id="bookCateNav">
						<h2 class="title"><span class="icons">&#xF094</span></h2>
						<h3 class="title">搜索</h3>
						<div>
							<form action="<?php echo $_SERVER['PHP_SELF'] ."?action=search_all" ?>" method="post">
								<p>
									<label for="">书名：</label>
									<input type="text" placeholder="书名" id="bookName" name="book_name" required>
								</p>

								<p>
									<label for="author">作者：</label>
									<input type="text" placeholder="作者" id="author" name="author">
								</p>
								<div style="margin-top:5px;" class="clear">
									<label for="">分类：</label>
									<div class="drop-down-input catagory-input left">
										<input class="input-text" id="cateDropDownInput" type="text" placeholder="默认分类" readonly>
										<span class="arrow-container"><span class="arrow">&#xF16B</span></span>
										<div class="options">
											<?php 

											$cate_arr = Category::get_all();

											foreach ($cate_arr as $key => $value) {
												echo '<span class="iteams" data-id="'. $value["id"]. '">' . $value['name'] . '</span>';
											}
											?>
										</div>
										<input class="hidden-input" type="hidden" name="catagory" value="0">
									</div>
								</div>
								<p>
									<label for="">日期开始：</label>
									<input type="text" class="datepicker" data-date-format="yyyy-mm-dd" name="publish_date_begain" id="publishDate"  placeholder="出版时间">
									
								</p>
								<p>
									<label for="">日期结束：</label>
									<input type="text" class="datepicker" data-date-format="yyyy-mm-dd" name="publish_date_end" id="publishDate"  placeholder="出版时间">
									
								</p>
								<input type="submit" class="submit" id="submit">
							</form>
						</div>
					</nav>			
<?php

if ($_GET) {


	if (isset($_GET['action']) && $_GET['action'] == "search_all") {

		  	$bookname   			= MySQLDatabase::escape(isset($_POST['book_name']) ? $_POST['book_name'] : null);
		  	
			$author   				= MySQLDatabase::escape(isset($_POST['author']) ? $_POST['author'] : null);
			$category   			= MySQLDatabase::escape(isset($_POST['catagory']) ? $_POST['catagory'] : null);
			$publish_date_begain   	= MySQLDatabase::escape(isset($_POST['publish_date_begain']) ? $_POST['publish_date_begain'] : null);
			$publish_date_end   	= MySQLDatabase::escape(isset($_POST['publish_date_end']) ? $_POST['publish_date_end'] : null);

			if (!empty($bookname)) {
				Book::list_search_all($bookname, $author, $category, $publish_date_begain, $publish_date_end);
			}
	}
}?>				
			<div>		
		</div>
	</div>
		</div>
	</div>
	<?php include("templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/book_list.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/datepicker/js/bootstrap-datepicker.js"></script>

<script type="text/javascript">
	
	$(function () {
		$(".drop-down-input").each(function (index,element){
			var $dropDownInput = $(element);
			var $input = $dropDownInput.find(".input-text");
			var $menuContainer = $dropDownInput.find(".options");
			var $arrow = $dropDownInput.find(".arrow");
			var $iteams = $menuContainer.find(".iteams");
			var $hidden = $dropDownInput.find(".hidden-input");

			$input.on("focus", function() {
				$menuContainer.slideDown();
				$arrow.addClass("target");

				$that = $(this);
				$iteams.on("click", function () {
					$that.val($(this).text());
					var value = parseInt($(this).data("id"));
					value = isNaN(value) ? 0 : value;
					$hidden.get(0).value = value;
				});
			}).on("blur", function () {
				$menuContainer.slideUp();
				$arrow.removeClass("target");
			}).on("keyDown", function(event){
				event = event || window.event;
				event.preventDefault();
				return false;
			});
		});

		// $input.css({'cursor':"pointer"});
	});	


	$(function() {
		$('.datepicker').datepicker();
	});

</script>
 
</html>