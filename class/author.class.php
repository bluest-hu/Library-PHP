<?php
class Author {
	/**
	 * [create_author_by_name description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public static function create_author_by_name($name) {
		
		global $DATABASE_CONFIG;
		
		$sql = new MySQLDatabase($DATABASE_CONFIG);

		// 这里就不检查 非重复了 不验证 降低耦合
		// 需要自己用 check_author_is_exit 检查
		$query = "INSERT INTO author
			(author_name, avatar)
			VALUES('$name', NULL)";
			echo $query;

		$result = $sql->query_db($query);

		
		if ($result) {
			if ($sql->affected_rows() === 1) {
				return true;
			}
		}
		return false;
	}


	/**
	 * [check_author_is_exit description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public static function check_author_is_exit($name) {

		global $DATABASE_CONFIG;
		
		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT *
			FROM author
			WHERE author_name = '$name'
			LIMIT 1";

		$result = $sql->query_db($query);
		
		if ($result) {
			if ($sql->affected_rows() === 1) {
				return true;
			}
		}
		return false;
	}


	/**
	 * [get_id_by_name description]
	 * @return [type] [description]
	 */
	public static function get_all() {
		$res_arr = array();

		global $DATABASE_CONFIG;
		global $BASE_URL;
		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT *
			FROM author";

		$result = $sql->query_db($query);

		if ($result) {
			while($row = $sql->fetch_array()) {
				$temp_arr = array(
					'ID'		=> $row['ID'],
					'name'		=> $row['author_name'],
					'avatar'	=> is_null($row['avatar']) ? $BASE_URL . "/image/" . "default.png" : $BASE_URL . "/image/" . $row['avatar'],
					'date'		=> $row['add_time']
				);

				array_push($res_arr, $temp_arr);
			}
			return $res_arr;
		}
		return False;
	}


	public static function get_id_by_name($name) {

		global $DATABASE_CONFIG;
		global $BASE_URL;
		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT ID
			FROM author
			WHERE author_name = '$name'";

		$result = $sql->query_db($query);

		if ($result) {
			while($row = $sql->fetch_array()) {
				return $row['ID'];
			}
		}

		return 0;
	}


	public static function get_name_by_id($id) {

		global $DATABASE_CONFIG;
		global $BASE_URL;
		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT author_name
			FROM author
			WHERE ID = $id";

		$result = $sql->query_db($query);

		if ($result) {
			while($row = $sql->fetch_array()) {
				return $row['author_name'];
			}
		}
		return 0;
	}
}

?>