<?php 
session_start();
header("Content-Type: text/html;charset=utf-8"); 

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");

// 存储警告信息
$WARN_MESSAGE = array();

$can_submit = TRUE;

$sql = new MySQLDatabase($DATABASE_CONFIG);

if ($_GET && $_GET['action'] == "add_books") {
	if ($_POST) {
		// print_r($_POST);
		$bookname 		= MySQLDatabase::escape(trim($_POST['bookname']));
		$publisher 		= MySQLDatabase::escape(trim($_POST['publisher']));
		$author 		= NULL;
		$cover  		= isset($_POST['cover']) ? $_POST['cover'] : "";
		$cover 			= MySQLDatabase::escape(trim($cover));
		$publish_date 	= MySQLDatabase::escape(trim($_POST['publishDate']));
		$sum_count 		= MySQLDatabase::escape(trim($_POST['sumCount']));
		$category 		= MySQLDatabase::escape(trim($_POST['sumCount']));
		$summary 		= MySQLDatabase::escape(trim($_POST['sumCount']));

		// $bookname 		= NULL;
		// $publisher 		= NULL;
		// $author 		= NULL;
		// $cover  		= isset($_POST['cover']) ? $_POST['cover'] : "";
		// $cover 			= NULL;
		// $publish_date 	= NULL;
		// $sum_count 		= 10;
		// $category 		= null;
		// $summary 		= null;

		// CREATE TABLE books (
		//     ID INT NOT NULL AUTO_INCREMENT,
		//     book_name VARCHAR(255) NOT NULL,
		//     publisher VARCHAR(255) NULL,
		//     cover VARCHAR(255) NULL,
		//     author INT NULL,
		//     publish_date TIMESTAMP NULL,
		//     add_date TIMESTAMP NOT NULL,
		//     sum_count INT NOT NULL,
		//     borrowed_count INT DEFAULT 0,
		//     tags VARCHAR(255) NULL,
		//     category INT NULL,
		//     summary TEXT NULL,
		//     PRIMARY KEY (ID)
		// );
		// 图书名不能为空
		if (empty($bookname)) {
			$can_submit = false;
			array_push($WARN_MESSAGE, "书名不能为空");
		} elseif (mb_strlen($bookname, 'utf-8') >= 500) {
			$can_submit = false;
			array_push($WARN_MESSAGE, "书名太长");
		}

		if (!empty($publish_date)) {
			$publish_date .= " 00:00:00";
		} else {

		}

		// 进入 傻逼拼接字符串模式 本来方式已经很傻逼无法忍受了
		// Dirty ！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！

		if ($can_submit) {
			$query = "INSERT INTO books (
				book_name, 
				publisher, 
				cover, 
				author, 
				publish_date, 
				sum_count, 
				borrowed_count, 
				tags, 
				category, 
				summary
				) 
				VALUES (
					'$bookname', " .  // 图书名
					get_sql_null($publisher) . "," . //  出版社
					get_sql_null($cover) . "," . // 封面
					get_sql_null($author) . "," . // 作者
					get_sql_null($publish_date) . "," . // 出版日期
					"$sum_count," . // 书本总数
					"0 ," . // 已经借出
					"NULL," . // Tags
					get_sql_null($category) . "," . // 分类
					get_sql_null($summary) . ")";
			
			$result = $sql->query_db($query);	

			echo $query;

			if ($result) {
				if ($sql->affected_rows() == 1) {
					echo "sucess";
				}
			}
		}
	}
}



function get_sql_null ($iteam) {
	return is_null($iteam) ? "NULL" : "'". $iteam. "'";
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
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="books-add right-container right clear">
				<h2 class="title">添加图书</h2>


				<div class="add-form right-content left">
					<form action="<?php echo $_SERVER['PHP_SELF'] . "?action=add_books"; ?>" method="POST" class="">
						<div class="clear">
							<div class="left">
								<h3 class="title">添加图书</h3>
								<p>
									<label for="bookName">书名：</label>
									<input type="text" id="bookName" name="bookname" placeholder="书名">
								</p>

								<p>
									<label for="publisher">出版社：</label>
									<input type="text" id="publisher" name="publisher" placeholder="出版社">
								</p>

								<p>
									<label for="anthor">作者：</label>
									<input type="text" id="anthor" name="author"  placeholder="作者">
								</p>

								<p>
									<label for="publishDate">出版时间：</label>
									<input type="text" class="datepicker" data-date-format="yyyy-mm-dd" name="publishDate" id="publishDate"  placeholder="出版时间">
								</p>

								<div class="clear">
									<label class="left" for="sumNumberInput">总数：</label>
									<div class="number-picker clear left">
										<span class="reduce-number-btn left">-</span>
										<input type="text" class="number-text left" id="sumNumberInput" value="1" name="sumCount">
										<span class="add-number-btn left" >+</span>
									</div>
								</div>

								<div class="clear">
									<label for="cateDropDownInput">图书分类：</label>
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
									<img src="image/books.png" id="showCover" class="image-cover" alt="">
								</div>
							</div>
						</div>
						
						<div>
							<p class="clear">
								<label for="editor">简介：</label>
							</p>
							<textarea id="editor" name="summery" placeholder="这里输入内容" autofocus></textarea>
						</div>

						<input type="submit" class="submit" id="submit">
					</form>
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

		cover.onchange = function(event) {
			
			showCover.src = "file:///" + this.value.replace(/\\/g, "/");
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


</script>
</html>


