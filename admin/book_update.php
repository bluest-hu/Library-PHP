<?php 
session_start();
header("Content-Type: text/html;charset=utf-8"); 

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/user.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");
include(dirname(__FILE__) . "../../class/book.class.php");
include(dirname(__FILE__) . "../../class/author.class.php");

// 存储警告信息
$WARN_MESSAGE = array();
$SUCESS_MESSAGE = array();

$CAN_SUBMIT = true;

if ($_GET) {

	$book_id = (int)$_GET['book_id'];
	$book = Book::get_book_info_by_id($book_id);

	if ($_GET['action'] == "book_update_submit") {


			// $bookname_old 		= $book['name'];
			// $publisher_old 		= $book['publisher'];
			// $author_old 		= $book['author'];
			// $cover_old 			= $book["cover"];
			// $publish_date_old 	= $book['date'];
			// $sum_old 			= $book['sum'];
			// $category_old 		= $book['cate_id'];
			// $summary_get 		= $book['summery'];


			$bookname_get 		= MySQLDatabase::escape(trim($_POST['bookname']));
			$publisher_get 		= MySQLDatabase::escape(trim($_POST['publisher']));
			$author_get 		= MySQLDatabase::escape(trim($_POST['author']));
			// 处理封面
			$publish_date_get 	= MySQLDatabase::escape(trim($_POST['publishDate']));
			$sum_count_get 		= MySQLDatabase::escape(trim($_POST['sumCount']));
			$category_get 		= MySQLDatabase::escape(trim($_POST['catagory']));
			$summary_get 		= MySQLDatabase::escape(trim($_POST['summery']));

			if (empty($bookname_get)) {
				$CAN_SUBMIT = false;
				array_push($WARN_MESSAGE, '用户名不能为空');
			}


	 		if (!empty($author_get)) {
				if (!Author::check_author_is_exit($author_get)) {
					if (!Author::create_author_by_name($author_get)) {
					}
				}
			}

			$FILE_UPLOAD_CONFIG = array(
				'MAX_FILE_SIZE' => 10000 * 1000,
				'TYPE' 			=> array(
					'image/jpeg',
					'image/png',
					'image/gif',
					'image/pjpeg'
					),
				'DIR' 			=> '../image/book_covers/'
				);

			if ($_FILES && $CAN_SUBMIT) {
				$file = $_FILES['cover'];

				if ($file['error'] == 0) {
					// FLAG 能否上传成功的标志位
					$CAN_UPLOAD = true;

					// 检查文件尺寸
					if ($file["size"] > $FILE_UPLOAD_CONFIG['MAX_FILE_SIZE']) {
						array_push($WARN_MESSAGE, '上传文件尺寸过大');
						$CAN_UPLOAD = false;
					}

					// 检查文件类型
					if (!in_array($file['type'], $FILE_UPLOAD_CONFIG['TYPE'])) {
						array_push($WARN_MESSAGE, '上传文件类型不符合');
						$CAN_UPLOAD = false;
					}
				
					$filename = md5("$bookname_get") . getExtenName($file['name']);
					$file_full_name = $FILE_UPLOAD_CONFIG['DIR'] . $filename;

					if ($CAN_UPLOAD) {

						// 上传成功
						if (move_uploaded_file($file['tmp_name'], $file_full_name)) {
							$cover = $filename;

							$query = "UPDATE books
								SET cover = '$cover'
								WHERE ID = $book_id
								LIMIT 1";
								MySQLDatabase::query($query);
						} 
					} else {
						array_push($WARN_MESSAGE, "文件上传失败");
					}
				} else {
					switch ($file['error']) {
						case UPLOAD_ERR_INI_SIZE :
							break;
						case UPLOAD_ERR_FORM_SIZE :
							break;
						case UPLOAD_ERR_PARTIAL :
							break;
						case UPLOAD_ERR_NO_FILE :
							break;
						case UPLOAD_ERR_NO_TMP_DIR :
							break;
						case UPLOAD_ERR_CANT_WRITE :	
							break;
						default:
							break;
					}
				}
			}

			$query = "UPDATE books
				SET book_name='$bookname_get', 
					publisher='$publisher_get', 
					author='$author_get', 
					publish_date='$publish_date_get',
					sum_count=$sum_count_get, 
					category='$category_get', 
					summary='$summary_get'
				WHERE ID = $book_id";

			$result = MySQLDatabase::query($query);


			if ($result) {
				header("location:" . $BASE_URL . "/admin/books.php");
			}			
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
					<h2 class="title">修改图书</h2>
					<div class="add-form right-content left">
						<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . "?action=book_update_submit&book_id=" . $book['ID']; ?>" method="POST" class="">
							<div class="clear">
								<div class="left">
									<h3 class="title">修改图书</h3>
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
		<input class="input-text" id="cateDropDownInput" type="text" placeholder="默认分类" readonly value="<?php echo $book['cate']; ?>">
		<span class="arrow-container"><span class="arrow">&#xF16B</span></span>
		<div class="options">
			<?php 

			$cate_arr = Category::get_all();

			foreach ($cate_arr as $key => $value) {
				echo '<span class="iteams" data-id="'. $value["id"]. '">' . $value['name'] . '</span>';
			}

			?>
		</div>
		<input class="hidden-input" type="hidden" name="catagory" value="<?php echo $book['cate_id']; ?>">
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
										<img src="<?php echo $book['cover'];?>" id="showCover" class="image-cover" alt="">
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

	$(function () {
		var $editor = $("#editor");
		if ($editor.attr("value") != "") {
			editor.setValue($editor.attr("value"));
		}
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


