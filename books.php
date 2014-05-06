<?php 
session_start();
header("Content-Type: text/html;charset=utf-8"); 
require_once("class/user.class.php");


$sql = new MySQLDatabase($DATABASE_CONFIG);

if ($_GET && $_GET['action'] == "add_books") {
	if ($_POST) {
		$bookname 		= $_POST['bookname'];
		$publisher 		= $_POST['publisher'];
		$cover 			= $_POST['cover'];
		$publish_date 	= $_POST['publishDate'];
		$sumCount 		= $_POST['sumCount'];


		$query = "INSERT INTO books 
			(book_name, publisher, cover, author, publish_date, sum_count, borrowed_count, tags, category, summary) 
			VALUES ('去你妈的乱码', '去你妈的乱码', NULL, NULL, '2014-05-14 00:00:00', '5', '0', NULL, NULL, NULL)";

		$result = $sql->query_db($query);	

		echo $query;
		if ($result) {
			if ($sql->affected_rows() == 1) {
				echo "sucess";
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
		margin:0 15px 15px 0;
		width: 150px;
		float: left;
		background-color: #FFF;
		box-shadow: 0 0 1px rgba(0, 0, 0, .1);
	}

	.books-list .book-cover {
		width: 150px;
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
					<input type="text" name="sumCount">
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
						$html .= $row['book_name'];
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
</html>


