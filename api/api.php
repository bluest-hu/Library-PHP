<?php

header('Content-type: application/json');

session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/book.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");
include(dirname(__FILE__) . "../../class/borrow_book.class.php");



if ($_GET) {
	if ($_GET['action'] == "get_book_info_by_id" && isset($_GET['book_id'])) {
		$id = (int)$_GET['book_id'];
		echo get_book_info_by_id($id);
	} else if ($_GET['action'] == "borrow_books" && isset($_GET['book_id'])) {
		$id = (int)$_GET['book_id'];
		echo borrow($id);
	}
}

function get_book_info_by_id($id) {
	return json_encode(Book::get_book_info_by_id($id));
}

function borrow($id) {
	$u_id = $_SESSION['user_id'];
	$can_return = true;
	$result = array(
		"error"=>0,
		"error_message" => array(),
		"result" => null
		);

	if (!$u_id) {
		$result['error'] = 1;
		array_push($result['error_message'], "请先登录");
		$can_return = false;
	} 

	if (!Book::get_book_info_by_id($id)) {
		$result['error'] = 1;
		array_push($result['error_message'], "该图书不存在");
		$can_return = false;
	}


	if ($can_return) {
		$waitForAgree = count(Borrow::get_borrowed_info_user_id($u_id, false, false));
		$extendedCount = count(Borrow::get_extended_info($u_id, 60)); 
		$borrowedCount = count(Borrow::get_borrow_info($u_id));


		if ($extendedCount > 0 ) {
			$result['error'] = 1;
			array_push($result['error_message'], "你已经欠费不能借阅");
			$can_return = false;
		}

		if (($waitForAgree + $borrowedCount) >= 10) {
			$result['error'] = 1;
			array_push($result['error_message'], "你的借阅额度已经用完");
			$can_return = false;
		}

		if ($can_return) {
			
			if (Borrow::add($id, $u_id, $result['error_message'])) {
				$result['result'] = "sucess";	
			}
			
		}
	}

	return json_encode($result);
}



?>