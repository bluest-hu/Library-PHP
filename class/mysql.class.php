<?php

include("database.class.php");

class MySQLDatabase extends DataBase{

	/**
	 * 构造函数
	 * @param array $config 数据库连接信息
	 */
	public function __construct($config) {
		parent::__construct($config);
	}

	/**
	 * open mysql connection
	 * @return null 
	 */
	public function open_db() {
		// SAVE CONNECTION LINK
		$this->link = mysql_connect($this->SERVER, $this->USERNAME, $this->PASSWORD);
		mysql_query("SET NAMES UTF8");
		if (!$this->link) {
			die("链接数据库失败" . mysql_error());
			$this->close_db();
		}
	}

	/**
	 * [close_connection description]
	 * @return [type] [description]
	 */
	public function close_db() {
		if (isset($this->link)) {

			if (!mysql_close($this->link)) {
				die("关闭数据库时错误" . mysql_error());
			} 
			unset($this->link);
		}
	}

	/**
	 * select the databse
	 * @return null 无返回值
	 */
	public function select_db() {
		$this->connection = mysql_select_db($this->DATABASE, $this->link);

		if (!$this->connection) {
			die("无法选择数据库" . mysql_error());
			// close connection
			$this->close_db();
			return FALSE;
		}
	}

	/**
	 * 查询
	 * @return [type] [description]
	 */
	public function query_db($query) {
		$this->query = $query;

		$this->result = mysql_query($this->query);

		if (!$this->result) {
			die("无法查询数据库" . mysql_error());
			// close connection
			$this->close_db();
			
			return FALSE;
		} else {
			return $this->result;
		}
	}

	/**
	 * [返回
	 * @return array [description]
	 */
	public function fetch_array() {
		if (!$this->result) {
			die("无法查询数据库" . mysql_error());
			return FALSE;
		} else {
			return mysql_fetch_array($this->result) ;
		}
	}


	public function fetch_assoc() {
		if (!$this->result) {
			die("无法查询数据库" . mysql_error());
			return FALSE;
		} else {
			return mysql_fetch_assoc($this->result);
		}
	} 

	/**
	 * [fetch_row description]
	 * @return [type] [description]
	 */
	public function fetch_row() {
		if (!$this->result) {	
			die("无法查询数据库" . mysql_error());
			return FALSE;
		} else {
			return mysql_fetch_row($this->result);
		}
	}

	/**
	 * Escape query string for mysql_query
	 * @param  	string $query SQL查询语句
	 * @return string        转以后的SQL查询语句
	 */
	public static function escape($str) {
		return mysql_real_escape_string(htmlspecialchars($str));
	}

	public function num_rows() {
		return mysql_num_rows($this->result);
	}

	public  function affected_rows() {
		return mysql_affected_rows ($this->link);
	}

	public static function query($query) {
		
		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);
		
		$result = $sql->query_db($query);

		if ($result) {
			if($sql->affected_rows() === 1) {
				return true;
			}
		}
		return false;
	}
}
?>
