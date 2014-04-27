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
		}
	}

	/**
	 * [返回
	 * @return array [description]
	 */
	public function fetch_array() {
		if (!$this->result) {
			die("无法查询数据库" . mysql_error());
		} else {
			return mysql_fetch_array($this->result);
		}
	}

	/**
	 * [fetch_row description]
	 * @return [type] [description]
	 */
	public function fetch_row() {
		if (!$this->result) {	
			die("无法查询数据库" . mysql_error());
		} else {
			return mysql_fetch_row($this->result);
		}
	}

	/**
	 * Escape query string for mysql_query
	 * @param  	string $query SQL查询语句
	 * @return string        转以后的SQL查询语句
	 */
	public function escape($str) {
		return mysql_escape_string($str);
	}
}
?>
