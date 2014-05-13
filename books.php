<?php 
session_start();

header("Content-Type: text/html;charset=utf-8"); 

include("class/user.class.php");
include("class/category.class.php");

$WARN_MESSAGE = array();

$can_submit = TRUE;
$sql = new MySQLDatabase($DATABASE_CONFIG);


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
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content clear">
			
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
						$html .= '<img class="book-cover" src="' . $BASE_URL ."/image/book_covers/". $row["cover"] . '"/>';
						$html .= '<span class="book-title">' . $row['book_name'] . "<span>"; 
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
<script type="text/javascript">

</script>
</html>


