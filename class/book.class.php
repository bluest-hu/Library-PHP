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

	public static function get_book_list($cate_id, $page, $each_num) {
	}

	public static function get_book_nav() {

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
}






?>