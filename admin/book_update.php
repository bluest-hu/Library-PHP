<?php 
session_start();
header("Content-Type: text/html;charset=utf-8"); 

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/user.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");
include(dirname(__FILE__) . "../../class/book.class.php");

// 存储警告信息
$WARN_MESSAGE = array();
$SUCESS_MESSAGE = array();



if ($_GET) {

	$book_id = (int)$_GET['book_id'];
	$book = Book::get_book_info_by_id($book_id);

	if ($_GET['action'] == "book_update_submit") {

			$bookname 		= $_POST['bookname'];
			$publisher 		= $_POST['publisher'];
			$author 		= $_POST['author'];
			// 处理封面
			$cover 			= isset($_POST['cover']) ? $_POST['cover'] : "";
			$publish_date 	= $_POST['publishDate'];
			$sum_count 		= $_POST['sumCount'];
			$category 		= $_POST['catagory'];
			$summary 		= $_POST['summery'];


			// 更新哈~~ 老子不验证 ID 了
			// 特么烦死了

			
	}
} else {
	// 木有GET请求就跳
	// 是非法请求 ，你妹的，简单粗暴，
	// 
	header("location:" . $BASE_URL . "/admin/books.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Books</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8">
   	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/datepicker/css/datepicker.css">
   	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/datepicker/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL . "/simditor/styles"; ?>/simditor.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL . "/simditor/styles"; ?>/font-awesome.css" />
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
			<div class="wrap">
				
				<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
				<div class="books-add right-container right clear">
					<h2 class="title">添加图书</h2>
					<div class="add-form right-content left">
						<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?action=book_update_submit&book_id=" . $book['ID']; ?>" method="POST" class="">
							<div class="clear">
								<div class="left">
									<h3 class="title">添加图书</h3>
<p>
	<label for="bookName">书名：</label>
	<input type="text" id="bookName" name="bookname" placeholder="书名" value="<?php echo $book['name'] ?>">
</p>

<p>
	<label for="publisher">出版社：</label>
	<input type="text" id="publisher" name="publisher" placeholder="出版社" value="<?php echo $book['publisher']?>">
</p>

<p>
	<label for="anthor">作者：</label>
	<input type="text" id="anthor" name="author"  placeholder="作者" value="<?php echo is_null($book['author'])? "" : $book['author'] ?>">
</p>

<p>
	<label for="publishDate">出版时间：</label>
	<input type="text" class="datepicker" data-date-format="yyyy-mm-dd" name="publishDate" id="publishDate"  placeholder="出版时间" value="<?php echo $book['date'] ?>">
</p>

<div class="clear">
	<label class="left" for="sumNumberInput">总数：</label>
	<div class="number-picker clear left">
		<span class="reduce-number-btn left">-</span>
		<input type="text" class="number-text left" id="sumNumberInput" value="<?php echo $book['sum']?>" name="sumCount">
		<span class="add-number-btn left" >+</span>
	</div>
</div>

<div class="clear">
	<label for="cateDropDownInput">图书分类：</label>
	<div class="drop-down-input catagory-input left">
		<input class="input-text" id="cateDropDownInput" type="text" placeholder="默认分类" readonly value="<?php echo Category::get_cate_name_by_id((int)$book['cate']); ?>">
		<span class="arrow-container"><span class="arrow">&#xF16B</span></span>
		<div class="options">
			<?php 

			$cate_arr = Category::get_all();

			foreach ($cate_arr as $key => $value) {
				echo '<span class="iteams" data-id="'. $value["id"]. '">' . $value['name'] . '</span>';
			}

			?>
		</div>
		<input class="hidden-input" type="hidden" name="catagory" value="<?php echo (int)$book['cate']; ?>">
	</div>
</div>

<div class="upload">
	<label for="upload">封面：</label>
	<div class="upload-cover">
		<input class="input-cover" type="text" name="" id="upload">
		<input class="btn-cover" type="button" value="上传">
	</div>
	<input class="upload-btn" type="file" name="cover" id="cover">
</div>

								</div>
								<div class="add-result right">
									<h3 class="title">效果预览</h3>
									<div class="book-show">
										<img src="<?php echo $BASE_URL . "/image/book_covers/" . $book['cover'];?>" id="showCover" class="image-cover" alt="">
									</div>
								</div>
							</div>
							
							<div>
								<p class="clear">
									<label for="editor">简介：</label>
								</p>
								<textarea id="editor" name="summery" placeholder="这里输入内容" autofocus value="<?php echo $book['summary'];?>">
								</textarea>
							</div>

							<input type="submit" class="submit" id="submit">
						</form>
					</div>
				</div>	
			</div>
		</div>
	<?php include(dirname(__FILE__) .  "../../templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL . "/simditor/scripts/js"; ?>/module.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL . "/simditor/scripts/js"; ?>/uploader.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL . "/simditor/scripts/js"; ?>/simditor.js"></script>
<script type="text/javascript">
	var editor = new Simditor({
	  	textarea: $('#editor'),
	  	toolbar: [
			'title',
			'bold',
			'italic',
			'underline',
			'strikethrough',
			'ol',
			'ul',
			'blockquote',
			'code',
			'table',
			'link',
			'image',
			'hr', 
			'indent', 
			'outdent'
		]
	});

	$(function() {
		$('.datepicker').datepicker();
	});

	window.addEventListener("load", function() {
		var cover = document.getElementById('cover');
		var showCover = document.getElementById('showCover');

		var input = document.getElementById('upload');

		cover.onchange = function () {
			input.value = this.value;
		}

	},false);


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


	$(function () {
		
	});


</script>
</html>


