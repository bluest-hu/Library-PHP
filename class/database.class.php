<?php 

/**
 * Dabase 数据库基类
 */
class DataBase {


	// DB Conection Infomation
	protected $SERVER 		= null;
	protected $USERNAME 	= null;
	protected $PASSWORD 	= null;
	protected $DATABASE 	= null;

	// 查询字符串
	protected $query 		= null;

	// Resource 
	protected $link 		= null;
	protected $connection 	= null;
	protected $result 		= null;		

	/**
	 * 构造函数
	 * @param array $config 数据库连接信息
	 */
	public function __construct ($config) {
		
		$this->SERVER 	= $config['server'];
		$this->USERNAME = $config['username'];
		$this->PASSWORD = $config['password'];
		$this->DATABASE = $config['database'];

		$this->open_db();	
		$this->select_db();	
	}

	/**
	 * open mysql connection
	 * @return null 
	 */
	protected function open_db() {
	}

	/**
	 * [close_connection description]
	 * @return [type] [description]
	 */
	protected function close_db() {
		
	}

	/**
	 * select the databse
	 * @return null 无返回值
	 */
	public function select_db() {

	}

	/**
	 * 查询
	 * @return [type] [description]
	 */
	public function query_db($query) {
		
	}

	/**
	 * 
	 * @return array [description]
	 */
	public function fetch_array() {
		
	}

	/**
	 * 
	 * @return [type] [description]
	 */
	public function fetch_row() {
		
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