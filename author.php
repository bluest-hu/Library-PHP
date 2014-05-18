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
<script type="text/javascript">

$(function () {
	updateNavHeight();
	
});


function updateNavHeight() {
	var mainHeight = $("#mianContent").height() - 2;
	var navHeight = $("#bookCateNav").height();
	// var height = mainHeight > navHeight ? mainHeight : navHeight;
	$("#bookCateNav").css("height", mainHeight);
}


function dealUnkonwText(text) {
	return (jQuery.trim(text) === "")  ? "未知" : text;
}


$(function() {


	// 插入的行数
	var $books = $(".books-list-container .book");
	var sumCount = $books.length;

	// 插入的第几行
	var appendRowIndex = 0;

	// 上一次插入位置缓存
	var lastappendBookIndex = null;
	// 每行的图书数目

	var EACH_COLUMN_COUNT = 6;
	//插入的书的索引
	var appendBookIndex = 0;

	// 缓存 上次点击的图书索引 
	var lastClickedBookIndex = null;

	// 详情窗口是否打开
	var isSlideDown = false;


	var __html = 	 
	'<li class="expland">' +
		'<span class="arrow"></span>' +
		'<span class="close-btn">X</span>' +
		'<div class="book-detail clear">' +
		'</div>' +
	'</li>';


	var $html = $(__html);
	var $arrow = $html.find(".arrow");
	var $closeBtn = $html.find(".close-btn");
	var $content = $html.find(".book-detail"); 

	$books.on("click", function () {
		var __this = $(this);

		$.ajax({
			url:$(this).find(".book-name").attr('href'),
			method:"GET",
			async:false,
			complete:function (data) {
				var json = eval("(" + data.responseText + ")");
				var __content = 
"<div class='column-one book-cover left'>"+
	"<img src='image/book_covers/"+json.cover+"'/>" +
"</div>"  +
"<div class='column-two left'>" +
	"<h4 class='title'>图书信息</h4>" +
	"<p>" + "<b>书名：</b>《" +json.name + "》</p>" +
	"<p>" + "<b>出版社：</b>" +  dealUnkonwText(json.publisher) + "</p>" +
	"<p>" +"<b>作者：</b>" + dealUnkonwText(json.author) + "</p>" +
	"<p>" +"<b>分类：</b>" + dealUnkonwText(json.cate) + "</p>" +
"</div>" +
"<div class='column-three left' style='clear:right'>" +
	"<h4 class='title'>图书简介</h4>" +
	"<div>" + dealUnkonwText(json.summary)  + "</div>"
"</div>";
				$content.html(__content);
				
			}
		});

		$html.css("display", "none");

		if (lastappendBookIndex != appendBookIndex) {
		} 

		// 计算要插入的一行最后一本图书的索引
		var bookIndex 	= __this.index();
		appendRowIndex 	= parseInt((bookIndex) / EACH_COLUMN_COUNT ) + 1;
		appendBookIndex = appendRowIndex * EACH_COLUMN_COUNT - 1;

		// 偏移量 简单粗暴 不计算了 
		// 我懒 我懒 我懒 还写爱出 shit 一样的代码
		// 看 它们长得一坨坨的~~
		var arrowToLeftDis = $(this).offset().left - 360;

		// 如果这一行是满的 那就插入最后一行
		// 不满的话那就最后一个咯，说明这一排是最后一排（这当然是句废话）
		if ($books.get(appendBookIndex)){
			$books.eq(appendBookIndex).after($html);
		} else {
			appendBookIndex = sumCount - 1;
			$books.eq(appendBookIndex).after($html);
		}
		
		// 如果点击的图书是同一行的，那么就不用slideDown特效了
		if (lastappendBookIndex != appendBookIndex) {
			$html.slideDown();
		} else {
			$html.css("display", "block");
		}

		$arrow.animate({"left": arrowToLeftDis});


		lastappendBookIndex = appendBookIndex;
		lastClickedBookIndex  = bookIndex;


		updateNavHeight();

	});


	$closeBtn.on("click", function () {
		$html.slideUp();
		lastappendBookIndex = null;
		updateNavHeight();
	});
});


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