<?php

include("mysql.class.php");
include(dirname(__FILE__) . "..\..\config.php");

class User {
	// 用户的ID
	protected $ID;
	protected $username = null;
	protected $password = null;
	protected $emaill = null;
	// unique ID
	protected $unique_id = null;
	// 用户头像地址
	protected $avastr = null;
	// 注册时间 需要格式化为数据库标准时间
	protected $register_time = null;
	// 老家
	protected $location = null;

	/**
	 * 用户的性别
	 * 0 是基佬 
	 * 1 是男
	 * 2 是女
	 * @var integer
	 */
	protected $sex = 3;
	// 用户级别
	// 0 是超级管理员
	// 1 是普通用户
	// 3 管理员
	protected $level = 0;

	/**
	 * 加密用户密码
	 * 加密方式 用户名+密码 连接后sha1
	 * @param  string $username 用户名
	 * @param  string $password 密码
	 * @return string           加密后的密码
	 */
	public static function encry_password($username, $password) {
		return sha1($username . $password);
	}

	/**
	 * 生成用户的随机码 unique_id 每次重要操作都需要比对 unique_id
	 * 也用于处理自动登录
	 * @return string 随机不会重合的字符串
	 */
	public static function get_unique() {
		return sha1(uniqid(mt_rand(), true));
	}

	/**
	 * 实现重载的构造函数
	 */
	public function __construct() {
		$method = "__construct" . func_num_args();
        $this->$method();
	}

	public function __construct10($ID, $username, $password, $emaill, $unique_id, $avastr, $register_time, $location, $sex, $level) {
		$this->$ID 				= $ID;
		$this->$username 		= $username;
		$this->$password 		= $password;
		$this->$emaill 			= $emaill;
		$this->$unique_id 		= $uniqid;
		$this->$avastr 			= $avastr;
		$this->$register_time 	= $register_time;
		$this->$location 		= $location;
		$this->$sex 			= $sex;
		$this->$level 			= $level;
	}
 

	public function add_new_user($user) {
        $str_1 = "(";
        $str_2 = "(";	

		foreach ($user as $key => $value) {
			$str_1 .= $key . ",";
			$str_1 .= ("'" . $value . "'");
		}

	 	$str_1 .= ")";
        $str_2 .= ")";

		$query = "INSERT INTO user";
	}

	public function check_password($pass_str) {

	}

	/**
	* 只是用于获取用户信息
	* 如果返回FALSE，则没有该用户
	**/
	public static function get_user_by_username($username) {

		$username = MySQLDatabase::escape($username);

		$query = "SELECT *
			FROM user
			WHERE username = '$username'
			LIMIT 1";

		$mysql = new MySQLDatabase($DATABASE_CONFIG);
		
		$result = $mysql->query_db($query);

		// 如果没有结果返回false，说明不存在该用户
		if (!$result) {
			return FALSE;
		} 

		while($row = $mysql->fetch_array()) {
			$ID 			= $row["ID"];
			$username 		= $row["username"];
			$password 		= $row["password"];
			$emaill 		= $row["email"];
			$unique_id 		= $row["unique_id"];
			$avastr 		= $row["avatar"];
			$register_time 	= $row["register_time"];
			$location 		= $row["location"];
			$sex 			= $row["sex"];
			$level 			= $row["level"];
		}

		return new User($ID, $username, $password, $emaill, $unique_id, $avastr, $register_time, $location, $sex, $level);

	}


	/**
	 * check e-mil is illegal or not
	 * @param  string $email_str 传入字符串
	 * @return boolean 传入字符串是否是合法的e-mail
	 */
	public static function  check_email($email_str) {
		
		return filter_var($email_str, FILTER_VALIDATE_REGEXP, 
			array(
                "options" => array(
                	"regexp"=>"/^[0-9a-zA-Z]{2,20}$/"
                    )
                )
            );
	}


	public static function check_username($username) {
		return filter_var($username, FILTER_VALIDATE_REGEXP, 
			array(
                "options" => array(
                	"regexp"=>"/^[0-9a-zA-Z]{2,20}$/"
                    )
                )
            );
	}


	public static function get_avastar_from_cookies() {
		return isset($_COOKIE["avastar"]) ? isset($_COOKIE["avastar"]) : "image/default.png";
	}

}

?>