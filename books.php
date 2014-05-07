<?php 
session_start();
header("Content-Type: text/html;charset=utf-8"); 
require_once("class/user.class.php");

$WARN_MESSAGE = array();


$can_submit = TRUE;
$sql = new MySQLDatabase($DATABASE_CONFIG);

if ($_GET && $_GET['action'] == "add_books") {
	if ($_POST) {
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
	<link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/main.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
	<style type="text/css">

	.books-list {

	}
	
	.books-list .book {
		margin:0 15px 20px 0;
		width: 100px;
		float: left;
		background-color: #FFF;
		box-shadow: 0 0 3px rgba(0, 0, 0, .1);
	}

	.books-list .book-cover {
		width: 100px;
	}

	.books-list .book-title {
		font-size: 12px;
	}

	</style>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content clear">

			<form action="<?php echo $_SERVER['PHP_SELF'] . "?action=add_books"; ?>" method="POST">
				<p>
					<label for="">Book Name</label>
					<input type="text" name="bookname">
				</p>

				<p>
					<label for="">Publisher</label>
					<input type="text" name="publisher">
				</p>

				<p>
					<label for="">Author</label>
					<input type="text">
					<input type="hidden" name="author">
				</p>

				<p>
					<label for="">出版时间</label>
					<input type="text" name="publishDate">
				</p>

				<p>
					<label for=""></label>
					<div class="number-picker clear">
						<span class="reduce-number-btn left">-</span>
						<input type="text" class="number-text left" value="0">
						<span class="add-number-btn left" >+</span>
						<input type="hideen" name="sumCount">
					</div>
					
				</p>

				<p>
					<div class="number-picker clear">
						<span class="reduce-number-btn left">-</span>
						<input type="text" class="number-text left" value="0">
						<span class="add-number-btn left" >+</span>
						<input type="hideen" name="sumCount">
					</div>
				</p>

				<p>
					<label for=""></label>
					<input type="text" name="cover">
				</p>
				<input type="submit">
			</form>
		


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
</html>


