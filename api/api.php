<?php

header('Content-type: application/json');

session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/book.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");


if ($_GET) {
	if ($_GET['action'] == "get_book_info_by_id") {
		$id = (int)$_GET['book_id'];
		echo get_book_info_by_id($id);
	}
}

function get_book_info_by_id($id) {
	return json_encode(Book::get_book_info_by_id($id));
}
?>