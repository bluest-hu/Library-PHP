<?php 
class Book {

	/**
	 * [add_new description]
	 * @param [type] $bookname     [description]
	 * @param [type] $publisher    [description]
	 * @param [type] $author       [description]
	 * @param [type] $cover        [description]
	 * @param [type] $publish_date [description]
	 * @param [type] $sum_count    [description]
	 * @param [type] $category     [description]
	 * @param [type] $summary      [description]
	 * @param [type] $WARN_MESSAGE [description]
	 */
	public static function add_new(
		$bookname, 
		$publisher, 
		$author, 
		$cover, 
		$publish_date,
		$sum_count,
		$category,
		$summary,
		$WARN_MESSAGE
		) {

		global $DATABASE_CONFIG;


		$CAN_SUBMIT = TRUE;
		$CAN_UPLOAD = FALSE;


		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$bookname 		= MySQLDatabase::escape(trim($bookname));
		$publisher 		= MySQLDatabase::escape(trim($publisher));
		$author 		= MySQLDatabase::escape(trim($author));
		// 处理封面
		$cover 			= MySQLDatabase::escape(trim($cover));
		$publish_date 	= MySQLDatabase::escape(trim($publish_date));
		$sum_count 		= MySQLDatabase::escape(trim($sum_count));
		$category 		= MySQLDatabase::escape(trim($category));
		$summary 		= MySQLDatabase::escape(trim($summary));


		// 图书名不能为空
		if (empty($bookname)) {
			$CAN_SUBMIT = false;
			array_push($WARN_MESSAGE, "书名不能为空");
			return;
		} elseif (mb_strlen($bookname, 'utf-8') >= 500) {
			$CAN_SUBMIT = false;
			array_push($WARN_MESSAGE, "书名太长");
			return;
		}

		// 设置时区
		date_default_timezone_set("Asia/Shanghai");
		date_default_timezone_set('UTC');

		if (!empty($publish_date)) {
			// 尝试处理时间验证时间
			$publish_date  = strtotime($publish_date);
			if ($publish_date != FALSE) {
				$publish_date = date("Y-m-d H:i:s", $publish_date);
			} else {
				$publish_date = NULL;
			}
		} else {
			$publish_date = NULL;
		}

		// 插入 作者表 没有出现过的新作者才会
 		if (!empty($author)) {
			if (!Author::check_author_is_exit($author)) {
				if (!Author::create_author_by_name($author)) {
					array_push($WARN_MESSAGE, "处理作者出现错误");
				}
			}
		}

		// 处理 分类
		$category = (int) $category;
		// 处理 总数
		$sum_count = (int) $sum_count;

		//上传配置问价
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
			
				$filename = md5(User::get_unique()) . '.' . getExtenName($file['name']);
				$file_full_name = $FILE_UPLOAD_CONFIG['DIR'] . $filename;

				if ($CAN_UPLOAD) {
					// 上传成功
					if (move_uploaded_file($file['tmp_name'], $file_full_name)) {
						$cover = $filename;

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


		// 进入 傻逼拼接字符串模式 本来方式已经很傻逼无法忍受了
		// Dirty ！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！

		if ($CAN_SUBMIT) {
			$query = "INSERT INTO books (
				book_name, 
				publisher, 
				cover, 
				author, 
				publish_date,
				add_date,
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
					"NOW()," .
					"$sum_count," . // 书本总数
					"0 ," . // 已经借出
					"NULL," . // Tags
					"$category," . // 分类
					get_sql_null($summary) . ")";
		


			$result = $sql->query_db($query);	

			// echo $query;

			if ($result) {
				if ($sql->affected_rows() == 1) {
					return TRUE;
				} 
				return FALSE;
			}
			
			return FALSE;
		}
	}
	

	/**
	 * [get_book_list description]
	 * @param  [type] $cate_id  [description]
	 * @param  [type] $page     [description]
	 * @param  [type] $each_num [description]
	 * @return [type]           [description]
	 */
	public static function get_book_list($cate_id, $page, $each_num) {

		global $DATABASE_CONFIG;
		global $BASE_URL;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		// 总的条目
		$sum = Book::get_books_sum($cate_id);

		// 总的页数
		$sum_page = ceil($sum / $each_num);

		// 处理分页溢出
		if ($page < 1 || $page > $sum_page ) {
			$page = 1;
		}

		$start_index = ($page - 1) * $each_num;

		$offset = $each_num;

		/**
		 * 0 代表无限
		 */
		if ($cate_id < 0 ) {
			$cate_id = 0;
		}

		// 处理分类
		if (!category::cate_is_exit($cate_id)) {
			$cate_id = 0;
		}

		if ($cate_id === 0) {
			$query = "SELECT *
				FROM books 
				LIMIT $start_index , $offset";
		} else {
			$query = " SELECT *
				FROM books 
				WHERE  category = $cate_id
				LIMIT $start_index , $offset";
		}

		$result = $sql->query_db($query);

		// 检测是否有内容
		$HAS_CONTENT = TRUE;
		if ($result) {
?>


<div class="books-list right clear">
	<div class="top-nav">
		<h2 class="title">图书</h2>
<?php





?>

<?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == true) { 

$u_id  = $_SESSION['user_id'];
	?>
	
		<div class="user-borrow-info right">
			<p>等待同意：<b class="count" id="wait"><?php echo  count(Borrow::get_borrowed_info_user_id($u_id, false, false)); ?></b>本</p>
			<p>已经超期：<b class="count" id="outOfDate"><?php echo count(Borrow::get_extended_info($u_id, 60)); ?></b>本</p>
			<p>正在借阅：<b class="count" id="isBorrow"><?php echo count(Borrow::get_borrow_info($u_id)); ?></b>本</p>
			<p>曾经借阅：<b class="count" id=""><?php echo count(Borrow::get_completed_info($u_id)); ?></b>本</p>
		</div>
<?php } ?>
	</div>

	<ul class="books-list-container clear">
<?php 		$BOOK_LIST_COUNT = 0;
			while($row = $sql->fetch_array()) {
				$BOOK_LIST_COUNT++;

?>

		<li class="book">
			<img class="book-cover" src="<?php echo $BASE_URL .'/image/book_covers/'. $row['cover'];?>" />
			<a class="book-name" title="<?php echo $row['book_name'];?>" href="<?php echo  $BASE_URL .'/api/api.php?action=get_book_info_by_id&book_id=' . $row['ID'] ;?>" class="book-title">
				<?php echo  mb_substr($row['book_name'],0,10,"utf-8");?>
			</a>
		</li>


<?php				
			}
			// 如果木有输图书
			if ($BOOK_LIST_COUNT < 1) {
				$HAS_CONTENT = FALSE;
				echo "<p class='nothing'>Nothing Here</p></div>";
			}
?>
	</ul>
<?php 
		}else {
				echo "<p class='nothing'>Nothing Here</p></div>";
				$HAS_CONTENT = FALSE;
		}



		if ($HAS_CONTENT == TRUE) {
?>
	<nav class="book-list-nav">
		<ul>
			<li><a href="<?php echo $BASE_URL . "/books.php?action=list_book&cate_id=$cate_id&page=1"; ?>">首页</a></li>
			<li><span>共<?php echo $sum_page; ?>页</span></li>
<?php
			if ($page > 1) {
					$pre_page_index = $page - 1;
?>
<li>
	<a href="<?php echo $BASE_URL . "/books.php?action=list_book&cate_id=$cate_id&page=$pre_page_index"; ?>">上一页</a>
</li>
<?php					
				}
			// 输出分页
			for ($i = 1; $i <= $sum_page; $i++ ) {
				$page_url = $BASE_URL . "/books.php?action=list_book&cate_id=$cate_id&page=$i";

?>
<li class="<?php echo ($i == $page) ? "current" : "" ?>">
	<a class="page" href="<?php echo $page_url;?>"><?php echo $i; ?></a>
</li> 
<?php		
			}
			if ($page != $sum_page) {

				$next_page_index = $page + 1;
?>			
			<li>
				<a href="<?php echo $BASE_URL . "/books.php?action=list_book&cate_id=$cate_id&page=$next_page_index"; ?>">下一页</a>
			</li>
			<li>
				<a href="<?php echo $BASE_URL . "/books.php?action=list_book&cate_id=$cate_id&page=$sum_page"; ?>">最后一页</a>
			</li>
<?php 
			}
?>
		</ul>
	</nav>
</div> 
<?php
		}
	}

	public static function get_book_list_by_author($author_id, $page, $each_num) {

		global $DATABASE_CONFIG;
		global $BASE_URL;

		$author_name = Author::get_name_by_id($author_id);

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		// 总的条目
		$sum = Book::get_books_count_by_author($author_name);

		// 总的页数
		$sum_page = ceil($sum / $each_num);

		// 处理分页溢出
		if ($page < 1 || $page > $sum_page ) {
			$page = 1;
		}

		$start_index = ($page - 1) * $each_num;

		$offset = $each_num;

		/**
		 * 0 代表无限
		 */
		if ($author_id < 0 ) {
			$author_id = 0;
		}

		// 处理分类
		if (!Author::check_author_is_exit($author_name)) {
			$author_id = 0;
		}

		if ($author_id === 0) {
			$query = "SELECT *
				FROM books 
				LIMIT $start_index , $offset";
		} else {
			$query = "SELECT *
				FROM books 
				WHERE  author = '$author_name'
				LIMIT $start_index , $offset";
		}

		// echo $query;

		$result = $sql->query_db($query);

		// 检测是否有内容
		$HAS_CONTENT = TRUE;
		if ($result) {
?>


<div class="books-list right">
		<div class="top-nav">
		<h2 class="title">图书</h2>

		<?php




?>

<?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == true) {
		$u_id  = $_SESSION['user_id'];


 ?>
	
		<div class="user-borrow-info right">
			<p>等待同意：<b class="count" id="wait"><?php echo  count(Borrow::get_borrowed_info_user_id($u_id, false, false)); ?></b>本</p>
			<p>已经超期：<b class="count" id="outOfDate"><?php echo count(Borrow::get_extended_info($u_id, 60)); ?></b>本</p>
			<p>正在借阅：<b class="count" id="isBorrow"><?php echo count(Borrow::get_borrow_info($u_id)); ?></b>本</p>
			<p>曾经借阅：<b class="count" id=""><?php echo count(Borrow::get_completed_info($u_id)); ?></b>本</p>
		</div>
<?php } ?>
	</div>
	<ul class="books-list-container clear">
<?php 		$BOOK_LIST_COUNT = 0;
			while($row = $sql->fetch_array()) {
				$BOOK_LIST_COUNT++;
?>

		<li class="book">
			<img class="book-cover" src="<?php echo $BASE_URL .'/image/book_covers/'. $row['cover'];?>" />
			<a class="book-name" title="<?php echo $row['book_name'];?>" href="<?php echo  $BASE_URL .'/api/api.php?action=get_book_info_by_id&book_id=' . $row['ID'] ;?>" class="book-title">
				<?php echo  mb_substr($row['book_name'],0,10,"utf-8");?>
			</a>
		</li>
<?php				
			}
			// 如果木有输图书
			if ($BOOK_LIST_COUNT < 1) {
				$HAS_CONTENT = FALSE;
				echo "<p class='nothing'>Nothing Here</p></div>";
			}
?>
	</ul>
<?php 
		}else {
				echo "<p class='nothing'>Nothing Here</p></div>";
				$HAS_CONTENT = FALSE;
		}



		if ($HAS_CONTENT == TRUE) {
?>
	<nav class="book-list-nav">
		<ul>
			<li><a href="<?php echo $BASE_URL . "/books.php?action=list_book&author_id=$author_id&page=1"; ?>">首页</a></li>
			<li><span>共<?php echo $sum_page; ?>页</span></li>
<?php
			if ($page > 1) {
					$pre_page_index = $page - 1;
?>
<li>
	<a href="<?php echo $BASE_URL . "/books.php?action=list_book&author_id=$author_id&page=$pre_page_index"; ?>">上一页</a>
</li>
<?php					
				}
			// 输出分页
			for ($i = 1; $i <= $sum_page; $i++ ) {
				$page_url = $BASE_URL . "/books.php?action=list_book&author_id=$author_id&page=$i";

?>
<li class="<?php echo ($i == $page) ? "current" : "" ?>">
	<a class="page" href="<?php echo $page_url;?>"><?php echo $i; ?></a>
</li> 
<?php		
			}
			if ($page != $sum_page) {

				$next_page_index = $page + 1;
?>			
			<li>
				<a href="<?php echo $BASE_URL . "/books.php?action=list_book&author_id=$author_id&page=$next_page_index"; ?>">下一页</a>
			</li>
			<li>
				<a href="<?php echo $BASE_URL . "/books.php?action=list_book&author_id=$author_id&page=$sum_page"; ?>">最后一页</a>
			</li>
<?php 
			}
?>
		</ul>
	</nav>
</div> 
<?php
		}
	}

	/**
	 * [get_books_sum description]
	 * @param  [type] $cate_id [description]
	 * @return [type]          [description]
	 */
	public static function get_books_sum($cate_id) {

		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT COUNT(ID) AS sum
			FROM books";

		if ($cate_id > 0)  {
			$query .= " WHERE category = " . $cate_id; 
		}

		// echo $query;
		$result = $sql->query_db($query);

		if ($result) {
			while($row = $sql->fetch_array()) {
				return $row['sum'];
			}
			return FALSE;
		}
		return FALSE;
	}


	public static function get_books_count_by_author ($author) {
		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT COUNT(ID) AS sum
			FROM books";

		if ($author != "")  {
			$query .= " WHERE author = '$author'"; 
		}

		$result = $sql->query_db($query);

		if ($result) {
			while($row = $sql->fetch_array()) {
				return $row['sum'];
			}
			return FALSE;
		}
		return FALSE;
	}


	/**
	 * [get_book_info_by_id description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function get_book_info_by_id($id) {
		
		$arr = array();		
		global $DATABASE_CONFIG;
		global $BASE_URL;

		$sql = new MySQLDatabase($DATABASE_CONFIG);
		
		$id = (int) $id;

		if ($id <= 0) {
			return False;
		}

		$query = "SELECT *
			FROM books
			WHERE ID = $id
			LIMIT 1";

		$result = $sql->query_db($query);
			
		if ($result) {
			while($row = $sql->fetch_array()) {
				$temp_arr = array(
					'ID' 		=> $row['ID'],
					'name'		=> $row['book_name'],
					'publisher'	=> $row['publisher'],
					'cover'		=> is_null($row['cover']) ? $BASE_URL . "/image/books.png" : $BASE_URL . "/image/book_covers/" . $row['cover'],
					'author'	=> $row['author'],
					'date'		=> date("Y-m-d", strtotime($row['publish_date'])),
					'sum'		=> $row['sum_count'],
					'borrow'	=> $row['borrowed_count'],
					'cate'		=> Category::get_cate_name_by_id($row['category']),
					'tag'		=> $row['tags'],
					'summary' 	=> htmlspecialchars_decode($row['summary'])
					);

				return $temp_arr;				
			}
		}
	}


	/**
	 * [get_all description]
	 * @return [type] [description]
	 */
	public static function get_all() {


		global $DATABASE_CONFIG;
		global $BASE_URL;
		
		$sql = new MySQLDatabase($DATABASE_CONFIG);
		
		$query = "SELECT *
			FROM books
			ORDER BY publish_date DESC";

		$result = $sql->query_db($query);

		$res_arr = array();

		if ($result) {
			while($row = $sql->fetch_array()) {
				$temp_arr = array(
					'ID' 		=> $row['ID'],
					'name'		=> $row['book_name'],
					'publisher'	=> $row['publisher'],
					'cover'		=> is_null($row['cover']) ? $BASE_URL . "/image/books.png" : $BASE_URL . "/image/book_covers/" . $row['cover'],
					'author'	=> $row['author'],
					'date'		=> date("Y-m-d", strtotime($row['publish_date'])),
					'sum'		=> $row['sum_count'],
					'borrow'	=> $row['borrowed_count'],
					'cate'		=> Category::get_cate_name_by_id($row['category']),
					'tag'		=> $row['tags'],
					'summary' 	=> htmlspecialchars_decode($row['summary'])
					);

				array_push($res_arr, $temp_arr);
			}

			return $res_arr;
		}
		return False;
	}


	public static function del_by_id($id) {
	
		$query = "DELETE FROM books
			WHERE ID = $id
			LIMIT 1";

		return MySQLDatabase::query($query);
	} 

	public static function update (
		$bookname, 
		$publisher, 
		$author, 
		$cover, 
		$publish_date,
		$sum_count,
		$category,
		$summary,
		$WARN_MESSAGE
		) {

		global $DATABASE_CONFIG;


		$CAN_SUBMIT = TRUE;
		$CAN_UPLOAD = FALSE;


		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$bookname 		= MySQLDatabase::escape(trim($bookname));
		$publisher 		= MySQLDatabase::escape(trim($publisher));
		$author 		= MySQLDatabase::escape(trim($author));
		// 处理封面
		$cover 			= MySQLDatabase::escape(trim($cover));
		$publish_date 	= MySQLDatabase::escape(trim($publish_date));
		$sum_count 		= MySQLDatabase::escape(trim($sum_count));
		$category 		= MySQLDatabase::escape(trim($category));
		$summary 		= MySQLDatabase::escape(trim($summary));


		// 图书名不能为空
		if (empty($bookname)) {
			$CAN_SUBMIT = false;
			array_push($WARN_MESSAGE, "书名不能为空");
			return;
		} elseif (mb_strlen($bookname, 'utf-8') >= 500) {
			$CAN_SUBMIT = false;
			array_push($WARN_MESSAGE, "书名太长");
			return;
		}

		// 设置时区
		date_default_timezone_set("Asia/Shanghai");
		date_default_timezone_set('UTC');

		if (!empty($publish_date)) {
			// 尝试处理时间验证时间
			$publish_date  = strtotime($publish_date);
			if ($publish_date != FALSE) {
				$publish_date = date("Y-m-d H:i:s", $publish_date);
			} else {
				$publish_date = NULL;
			}
		} else {
			$publish_date = NULL;
		}

		// 处理 分类
		$category = (int) $category;
		// 处理 总数
		$sum_count = (int) $sum_count;

		//上传配置问价
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
			
				$filename = md5(User::get_unique()) . '.' . getExtenName($file['name']);
				$file_full_name = $FILE_UPLOAD_CONFIG['DIR'] . $filename;

				if ($CAN_UPLOAD) {
					// 上传成功
					if (move_uploaded_file($file['tmp_name'], $file_full_name)) {
						$cover = $filename;

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


		// 进入 傻逼拼接字符串模式 本来方式已经很傻逼无法忍受了
		// Dirty ！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！

		if ($CAN_SUBMIT) {
			$query = "INSERT INTO books (
				book_name, 
				publisher, 
				cover, 
				author, 
				publish_date,
				add_date,
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
					"NOW()," .
					"$sum_count," . // 书本总数
					"0 ," . // 已经借出
					"NULL," . // Tags
					"$category," . // 分类
					get_sql_null($summary) . ")";
			
			$result = $sql->query_db($query);	


			if ($result) {
				if ($sql->affected_rows() == 1) {
					return TRUE;
				} 
				return FALSE;
			}
			
			return FALSE;
		}
	}

	public static function borrow($book_id, &$ERROR_MESSAGE) {
		global $DATABASE_CONFIG;
		
		$CAN_SUBMIT = TRUE;
		
		$book = Book::get_book_info_by_id($book_id);

		if (!$book) {
			array_push($ERROR_MESSAGE, "该书不存在！");
			$CAN_SUBMIT = FALSE;
		}

		// 检查 能否借阅
		if ($book['borrow'] >= $book['sum']) {
			array_push($ERROR_MESSAGE, "该书剩余借阅数已满！");
			$CAN_SUBMIT = FALSE;
		}

		if ($CAN_SUBMIT) {

			$borrow = $book['borrow'];
			
			$borrow++;

			$query = "UPDATE  books
				SET borrowed_count = $borrow
				WHERE ID = $book_id
				LIMIT 1";

			return MySQLDatabase::query($query);
		}
		return FALSE;
	}


	public static function return_book($book_id, &$ERROR_MESSAGE) {
		global $DATABASE_CONFIG;
		
		$CAN_SUBMIT = TRUE;
		

		$book = Book::get_book_info_by_id($book_id);

		if (!$book) {
			array_push($ERROR_MESSAGE, "该书不存在！");
			$CAN_SUBMIT = FALSE;
		}


		if ($CAN_SUBMIT) {

			$borrow = $book['borrow'];
			
			$borrow--;

			$query = "UPDATE  books
				SET borrowed_count = $borrow
				WHERE ID = $book_id
				LIMIT 1";

			return MySQLDatabase::query($query);
		}
		return FALSE;
	}


	public static function list_search_all(
		$bookname, 
		$author, 
		$category, 
		$publish_date_begain, 
		$publish_date_end) {

		global $DATABASE_CONFIG;
		global $BASE_URL;


		$sql = new MySQLDatabase($DATABASE_CONFIG);


		$query = "SELECT *  
			FROM books 
			 WHERE "; 

		if (!empty($bookname)) {
			$query .=" book_name LIKE '%" . $bookname. "%'";
		} 

		if (!empty($author)) {
			$query .=" AND author LIKE '%" . $author . "%'";
		} 

		if (!empty($category) || $category != 0) {
			$query .=" AND category = $category";
		} 

		if (!empty($publish_date_begain)) {
			$query .= " AND publish_date >= $publish_date_begain";
		}

		if (!empty($publish_date_end)) {
			$query .= " AND publish_date <= $publish_date_begain";
		}

		$result = $sql->query_db($query);

		// 检测是否有内容
		$HAS_CONTENT = TRUE;
		if ($result) {
?>


<div class="books-list right clear">
	<div class="top-nav">
		<h2 class="title">图书</h2>
<?php

?>

<?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == true) { 

$u_id  = $_SESSION['user_id'];
	?>
	
		<div class="user-borrow-info right">
			<p>等待同意：<b class="count" id="wait"><?php echo  count(Borrow::get_borrowed_info_user_id($u_id, false, false)); ?></b>本</p>
			<p>已经超期：<b class="count" id="outOfDate"><?php echo count(Borrow::get_extended_info($u_id, 60)); ?></b>本</p>
			<p>正在借阅：<b class="count" id="isBorrow"><?php echo count(Borrow::get_borrow_info($u_id)); ?></b>本</p>
			<p>曾经借阅：<b class="count" id=""><?php echo count(Borrow::get_completed_info($u_id)); ?></b>本</p>
		</div>
<?php } ?>
	</div>

	<ul class="books-list-container clear">
<?php 		$BOOK_LIST_COUNT = 0;
			while($row = $sql->fetch_array()) {
				$BOOK_LIST_COUNT++;

?>

		<li class="book">
			<img class="book-cover" src="<?php echo $BASE_URL .'/image/book_covers/'. $row['cover'];?>" />
			<a class="book-name" title="<?php echo $row['book_name'];?>" href="<?php echo  $BASE_URL .'/api/api.php?action=get_book_info_by_id&book_id=' . $row['ID'] ;?>" class="book-title">
				<?php echo  mb_substr($row['book_name'],0,10,"utf-8");?>
			</a>
		</li>


<?php				
			}
			// 如果木有输图书
			if ($BOOK_LIST_COUNT < 1) {
				$HAS_CONTENT = FALSE;
				echo "<p class='nothing'>Nothing Here</p></div>";
			}
?>
	</ul>
<?php 
		}else {
				echo "<p class='nothing'>Nothing Here</p></div>";
				$HAS_CONTENT = FALSE;
		}
?>

</div> 
<?php
	}


	public static function search_all($bookname, $author, $category, $publish_date_begain, $publish_date_end) {
		
		global $DATABASE_CONFIG;
		global $BASE_URL;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT *  
			FROM books 
			 WHERE ";


		if (!empty($bookname)) {
			$query .=" book_name LIKE '%" . $bookname. "%'";
		} 

		if (!empty($author)) {
			$query .=" AND author LIKE '%" . $author . "%'";
		} 

		if (!empty($category) || $category != 0) {
			$query .=" AND category = $category";
		} 

		if (!empty($publish_date_begain)) {
			$query .= " AND publish_date >= $publish_date_begain";
		}

		if (!empty($publish_date_end)) {
			$query .= " AND publish_date <= $publish_date_begain";
		}


		$res_arr = array();
		
		$result = $sql->query_db($query);

		if ($result) {
			while($row = $sql->fetch_array()) {
				$temp_arr = array(
					'ID' 		=> $row['ID'],
					'name'		=> $row['book_name'],
					'publisher'	=> $row['publisher'],
					'cover'		=> is_null($row['cover']) ? $BASE_URL . "/image/books.png" : $BASE_URL . "/image/book_covers/" . $row['cover'],
					'author'	=> $row['author'],
					'date'		=> date("Y-m-d", strtotime($row['publish_date'])),
					'sum'		=> $row['sum_count'],
					'borrow'	=> $row['borrowed_count'],
					'cate'		=> Category::get_cate_name_by_id($row['category']),
					'tag'		=> $row['tags'],
					'summary' 	=> htmlspecialchars_decode($row['summary'])
					);

				array_push($res_arr, $temp_arr);
			}

			return $res_arr;
		}
		return False;
	}
}
?>