<?php 

session_start();

include(dirname(__FILE__) . "../config.php");
include(dirname(__FILE__) . "../function.php");
include(dirname(__FILE__) . "../class/mysql.class.php");
include(dirname(__FILE__) . "../class/book.class.php");
include(dirname(__FILE__) . "../class/category.class.php");
include(dirname(__FILE__) . "../class/author.class.php");
include(dirname(__FILE__) . "../class/borrow_book.class.php");



$user_id = $_SESSION['user_id'];
$book_id = 0;

$CAN_SUBMIT = true;

$ERROR_MESSAGE = array();

if ($_GET) {
	if ($_GET['action'] == "borrow") {

		if (isset($_GET['book_id'])) {
			$book_id = (int)$_GET['book_id'];
		}

		// Borrow::add($book_id, $user_id,$ERROR_MESSAGE);
	}
}

?>
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
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>
		<div class="content" id="mianContent">
		</div>
	</div>
	<?php include("templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 

</html>