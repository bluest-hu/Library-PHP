<?php 
session_start();
header("Content-Type: text/html;charset=utf-8"); 
require_once("class/user.class.php");

$WARN_MESSAGE = array();


$can_submit = TRUE;
$sql = new MySQLDatabase($DATABASE_CONFIG);

if ($_GET && $_GET['action'] == "add_books") {
	if ($_POST) {


		print_r($_POST);

		$bookname 		= MySQLDatabase::escape(trim($_POST['bookname']));
		$publisher 		= MySQLDatabase::escape(trim($_POST['publisher']));
		$cover 			= MySQLDatabase::escape(trim($_POST['cover']));
		$publish_date 	= MySQLDatabase::escape(trim($_POST['publishDate']));
		$sumCount 		= MySQLDatabase::escape(trim($_POST['sumCount']));

		if (empty($bookname)) {
			$can_submit = false;
			array_push($WARN_MESSAGE, "书名不能为空");
		}


		// 处理没有输入的情况
		$publisher = !empty($publisher) ? $publisher : "未知";
		// 
		print_r($publisher);

		if ($can_submit) {
			$query = "INSERT INTO books 
				(book_name, publisher, cover, author, publish_date, sum_count, borrowed_count, tags, category, summary) 
				VALUES ('测试', '测试', NULL, NULL, '2014-05-14 00:00:00', '5', '0', NULL, NULL, NULL)";

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
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL . "/simditor/styles"; ?>/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL . "/simditor/styles"; ?>/simditor.css" />
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content clear">

			<div class="books-add clear">
				<div class="add-form left card">
					<h2 class="title">添加图书</h2>
					<form action="<?php echo $_SERVER['PHP_SELF'] . "?action=add_books"; ?>" method="POST" >
						<p>
							<label for="">书名：</label>
							<input type="text" name="bookname">
						</p>

						<p>
							<label for="">出版社：</label>
							<input type="text" name="publisher">
						</p>

						<p>
							<label for="">作者：</label>
							<input type="text">
							<input type="hidden" name="author">
						</p>

						<p>
							<label for="">出版时间：</label>
							<input type="text" name="publishDate">
						</p>

						<div class="clear">
							<label class="left" for="">总数：</label>
							<div class="number-picker clear left">
								<span class="reduce-number-btn left">-</span>
								<input type="text" class="number-text left" value="0" name="sumCount">
								<span class="add-number-btn left" >+</span>
							</div>
							
						</div>
						<p>
							<label for="">封面：</label>
							<input type="file" name="cover" id="cover">
						</p>

						<div>
							<p class="clear">
								<label for="">简介：</label>
							</p>
							<textarea id="editor" placeholder="这里输入内容" autofocus></textarea>
						</div>

						<input type="submit">
					</form>
				</div>

				<div class="add-result right card">
					<h3>效果预览</h3>
					<div class="book-show">
						<img src="image/books.png" id="showCover" class="image-cover" alt="">
					</div>
				</div>
			</div>	
			
			<div class="books">
				<ul class="books-list clear">
				<?php 

				if (is_null($sql)) {
					$sql = new MySQLDatabase($DATABASE_CONFIG);
				}

				$query = "SELECT * FROM books";

				$result = $sql->query_db($query);	
				
				$html = "";
				
				if ($result) {
					while ($row = $sql->fetch_array()) {
						$html .= '<li class="book">';
						$html .= '<a href="">';
						$html .= '<img class="book-cover" src="image/books.png" />';
						$html .= '<span class="book-title">' . $row['book_name'] . "<span>";
						$html .= '</a>';
						$html .= '</li>';
					}
					echo $html;
				}
				?>
				</ul>
			</div> 
		</div>
	<?php include("templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
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


	window.addEventListener("load", function() {
		var cover = document.getElementById('cover');
		var showCover = document.getElementById('showCover');

		cover.onchange = function(event) {
			
			showCover.src = "file:///" + this.value.replace(/\\/g, "/");
		}
	},false);
</script>
</html>


