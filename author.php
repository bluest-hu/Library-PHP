<?php 

session_start();

include(dirname(__FILE__) . "../config.php");
include(dirname(__FILE__) . "../function.php");
include(dirname(__FILE__) . "../class/mysql.class.php");
include(dirname(__FILE__) . "../class/book.class.php");
include(dirname(__FILE__) . "../class/category.class.php");
include(dirname(__FILE__) . "../class/author.class.php");

$author_all = Author::get_all();
$author_id = 0;
$page = 1;

if ($_GET) {
	if ($_GET['action'] = "list_book") {
		$author_id = (int)$_GET['author_id'];
		$page = (int)$_GET['page'];
	}
}


?>

<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
	<title>Authors</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books_add.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books.css" />
    <style type="text/css">
	.all {
		border-bottom: 1px solid #ddd;
	}
	.author-iteams {
		position: relative;
		display: block;
		width: 76px;
		height: 90px;
		float: left;
		border-right: 1px solid #ddd;
		border-bottom: 1px solid #ddd;
	}

	.author-iteams .avatar {
		width: 55px;
		position: absolute;
		left: 0px;
		right: 0px;
		top: 10px;
		margin: auto;
		-webkit-filter: grayscale(100%);
    	-moz-filter: grayscale(100%);
	    -ms-filter: grayscale(100%);
	    -o-filter: grayscale(100%);
	    
	    filter: grayscale(100%);
		
	    filter: gray;
	}

	.author-iteams .anthor-name {
		position: absolute;
		text-align: center;
		left: 0px;
		right: 0px;
		bottom: 4px;
		margin: auto;
		font-size: 12px;
		line-height: 15px;
		color: #666;
	}

	.book-cate-nav a .count {
		background-color: #3498DB; 
	}

	 .author-iteams .count {
		display: inline-block;
		position: absolute;
		margin: 0px;
		padding: 0px;
		right: -6px;
		top: -6px;
	} 

	 .author-iteams a {
		width: inherit;
		height: inherit;
		padding-left:0px;
	}

	.book-cate-nav li.current:after {
		display: none;
	}

	.book-cate-nav li:hover .anthor-name,
	.book-cate-nav li.current .anthor-name {
		color: #FFF;
	}
	
	.book-cate-nav li:hover .avatar,
	.book-cate-nav li.current .avatar {
		-webkit-filter: grayscale(0%);
    	-moz-filter: grayscale(0%);
	    -ms-filter: grayscale(0%);
	    -o-filter: grayscale(0%);
	    
	    filter: grayscale(0%);
		
	    filter: gray;
	}
    </style>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content" id="mianContent">
			<div class="books clear">
				<nav class="book-cate-nav left book-author-av" id="bookCateNav">
					<h2 class="title"><span class="icons">&#xF136</span></h2>
					<ul class="clear" >
						<li class="all <?php echo ($author_id == 0) ? "current" : "" ;?>" >
							<a href="<?php echo $BASE_URL;?>/author.php?action=list_book&author_id=0&page=1">
								<span class="count"><?php echo Book::get_books_count_by_author(0);?></span>
								All
							</a>
						</li>
<?php 
foreach ($author_all as $key => $value) { 
	$url = $BASE_URL . $_SERVER['PHP_SELF'] . '?action=list_book&author_id=' . $value['ID'] . "&page=1";
	if ((int)$value['ID'] == $author_id) {
		$nav_curr = "current";
		$curr_cate_name = $value['name'];
	} else {
		$nav_curr = "";
	}
?>

<li class="<?php echo $nav_curr;?> author-iteams">
	<a href="<?php echo  $url ?>">
		<img class="avatar" src="<?php echo $value['avatar'];?>" alt="">
		<span class="anthor-name"><?php echo $value['name'];?></span>
		<span class="count"><?php echo Book::get_books_count_by_author($value['name']);?></span>
	</a>
</li> 

<?php } ?>
					</ul>
				</nav>


<?php $curr_cate_name; ?>
				
<?php 

// 输出
Book::get_book_list_by_author($author_id, $page, 18);
?>
				
		</div>
	</div>
	<?php include("templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/book_list.js"></script> 
<script>

$(function () {
	$(".author-iteams").each(function(index,element) {

		if ((index + 1) % 3 === 0) {
			console.log(element)
			$(element).css("borderRight","none");
		}
	});
})
</script>
</html>