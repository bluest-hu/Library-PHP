<?php

class User {
	// // 用户的ID
	// protected $ID;
	// protected $username = null;
	// protected $password = null;
	// protected $emaill = null;
	// // unique ID
	// protected $unique_id = null;
	// // 用户头像地址
	// protected $avastr = null;
	// // 注册时间 需要格式化为数据库标准时间
	// protected $register_time = null;
	// // 老家
	// protected $location = null;

	// /**
	//  * 用户的性别
	//  * 0 是基佬 
	//  * 1 是男
	//  * 2 是女
	//  * @var integer
	//  */
	// protected $sex = 3;
	// // 用户级别
	// // 0 是超级管理员
	// // 1 是普通用户
	// // 3 管理员
	// protected $level = 0;

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

	// /**
	//  * 实现重载的构造函数
	//  */
	// public function __construct() {
	// 	$method = "__construct" . func_num_args();
 //        $this->$method();
	// }

	// public function __construct10($ID, $username, $password, $emaill, $unique_id, $avastr, $register_time, $location, $sex, $level) {
	// 	$this->$ID 				= $ID;
	// 	$this->$username 		= $username;
	// 	$this->$password 		= $password;
	// 	$this->$emaill 			= $emaill;
	// 	$this->$unique_id 		= $uniqid;
	// 	$this->$avastr 			= $avastr;
	// 	$this->$register_time 	= $register_time;
	// 	$this->$location 		= $location;
	// 	$this->$sex 			= $sex;
	// 	$this->$level 			= $level;
	// }
 

	// public function add_new_user($user) {
 //        $str_1 = "(";
 //        $str_2 = "(";	

	// 	foreach ($user as $key => $value) {
	// 		$str_1 .= $key . ",";
	// 		$str_1 .= ("'" . $value . "'");
	// 	}

	//  	$str_1 .= ")";
 //        $str_2 .= ")";

	// 	$query = "INSERT INTO user";
	// }

	// public function check_password($pass_str) {
		
	// }

	// /**
	// * 只是用于获取用户信息
	// * 如果返回FALSE，则没有该用户
	// **/
	// public static function get_user_by_username($username) {

	// 	$username = MySQLDatabase::escape($username);

	// 	$query = "SELECT *
	// 		FROM user
	// 		WHERE username = '$username'
	// 		LIMIT 1";

	// 	$mysql = new MySQLDatabase($DATABASE_CONFIG);
		
	// 	$result = $mysql->query_db($query);

	// 	// 如果没有结果返回false，说明不存在该用户
	// 	if (!$result) {
	// 		return FALSE;
	// 	} 

	// 	while($row = $mysql->fetch_array()) {
	// 		$ID 			= $row["ID"];
	// 		$username 		= $row["username"];
	// 		$password 		= $row["password"];
	// 		$emaill 		= $row["email"];
	// 		$unique_id 		= $row["unique_id"];
	// 		$avastr 		= $row["avatar"];
	// 		$register_time 	= $row["register_time"];
	// 		$location 		= $row["location"];
	// 		$sex 			= $row["sex"];
	// 		$level 			= $row["level"];
	// 	}

	// 	return new User($ID, $username, $password, $emaill, $unique_id, $avastr, $register_time, $location, $sex, $level);

	// }


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


	public static function remove_all_cookies() {
		setcookie('username', $username, time() - 60 * 24);
		setcookie('unique_id', "", time() - 60 * 60 * 24);
	    setcookie('user_bg', "", time() - 60 * 24);
	    setcookie('avatar', "", time() - 60 * 24);
	}


	// public static function login($username, $password, $remberme, & $WARN_MESSAGE) {
	// 	global $DATABASE_CONFIG;

	// 	$CAN_LOGIN = TRUE

	//   // 验证用户名不为空
	//     if (empty($username)) {
	//         array_push($WARN_MESSAGE, '用户名不能为空！');
	//         $CAN_LOGIN = FALSE;
	//     }

	//     // 验证密码不为空
	//     if (empty($password)) {
	//         array_push($WARN_MESSAGE,'密码不能为空！');
	//         $CAN_LOGIN = FALSE;
	//     } 

	//     if ($CAN_LOGIN) {
	        
	//         $username   = MySQLDatabase::escape($username);
	//         $password   = MySQLDatabase::escape($password);
	//         $remberme   = MySQLDatabase::escape($remberme);
	//         $password   = User::encry_password($username, $password);

	//         $login_query = "SELECT *
 //                FROM users 
 //                WHERE username = '$username' 
 //                LIMIT 1";

	//         $login_sql = new MySQLDatabase($DATABASE_CONFIG);

	//         $login_result = $login_sql->query_db($login_query);

	//         if ($login_result) {
	            
	//             if ($login_sql->num_rows() === 0) {
	//                 array_push($WARN_MESSAGE, '用户名不存在！');
	//             }

	//             while($row = $login_sql->fetch_array()) {
	//                 if ($password === $row['password']) {
	                    
	//                     $_SESSION['username']   = htmlspecialchars_decode($username);
	//                     $_SESSION['is_login']   = TRUE;
	//                     $_SESSION['avatar']     = is_null($row['avatar']) ? $DEFAULT_USER_AVASTAR : $row['avatar'];
	//                     $_SESSION['user_bg']    = is_null($row['cover_bg']) ? $DEFAULT_USER_BACKGROUND_IMAGE : $row['cover_bg'];
	//                     $unique_id               = User::get_unique();
	//                     $_SESSION['level']      = (int)$row['level'];

	//                     setcookie('username', $username, time() + 60 * 60 * 24);
	//                     // 悬着记住密码的情况
	//                     if ($remberme === "on") {
	//                         // 处理自动登录
	//                         $update_uipque_id = "UPDATE users 
	//                                 SET unique_id = '$unique_id'
	//                                 WHERE username = '$username' 
	//                                 LIMIT 1";

	//                         $update_query = new MySQLDatabase($DATABASE_CONFIG);

	//                         $update_result = $update_query->query_db($update_uipque_id);

	//                         if($update_result) {
	//                             if ($update_query->affected_rows() == 1 ) {
	//                                 $COOKIES_TIME  = time() + 60 * 60 * 24;
	//                                 //把密码加密后存储在Cookies中
	//                                 setcookie('username', $_SESSION['username'], $COOKIES_TIME);
	//                                 setcookie('unique_id', $unique_id, $COOKIES_TIME);
	//                                 setcookie('user_bg', $_SESSION['user_bg'], $COOKIES_TIME);
	//                                 setcookie('avatar', $_SESSION['avatar'], $COOKIES_TIME);
	//                             }
	//                         }
	//                     }

	//                     // 跳转到转入页面
	//                     $location = is_null($_SESSION['referer_url']) ? "user.php?user=". $_SESSION['username'] : $_SESSION['referer_url'];
	//                     header("Location:" .$BASE_URL. $location); 
	                    
	//                 } else {
	//                     array_push($WARN_MESSAGE, '密码或者用户名错误！');
	//                 }
	//             }
	//         }
	//     }
	// }


	public static function reigster() {

		 // 验证用户名
	    if (filter_has_var(INPUT_POST, "username") && !empty($username)) {
	        
	        $username = htmlspecialchars($username);

	        if (!User::check_username($username)) {
	            $can_submit = false;
	            array_push($WARN_MESSAGE, "用户名必须为包含[A-Z][a-z][0-9]数字组合，长度为2-20位");
	        }

	        $mysql = new MySQLDatabase($DATABASE_CONFIG);

	        // 验证用户名 是否重复
	        $query = "SELECT COUNT(username) 
	            FROM users 
	            WHERE username = '$username' 
	            LIMIT 1";

	        // 如果出错返回 说明MySQL 挂了，网站出现问题
	        $result = $mysql->query_db($query);
	       
	        if ($result) {
	            // 该用户已经被占用
	            while ($row = $mysql->fetch_array()) {
	                if ($row[0] >= 1) {
	                    array_push($WARN_MESSAGE, '该用户名已经存在，请更换');
	                    $can_submit = false;
	                    // 消除资源
	                    unset($result);
	                }
	            }
	        } else {
	            $can_submit = false;
	            array_push($WARN_MESSAGE, '网站开小差了，请稍后再试');
	        }
	    } else {
	        array_push($WARN_MESSAGE, '用户名不能为空');
	        $can_submit = false;
	    } 

	    // 验证密码
	    if (empty($password)) {
	        array_push($WARN_MESSAGE, '密码不能为空');
	        $can_submit = false;
	    } 

	    // 验证密码重复 是否一致
	    if (!empty($password_repeat)) {

	        if ($password_repeat != $password) {
	            array_push($WARN_MESSAGE, '两次密码输入不一致！');
	            $can_submit = false;
	        } else {
	            // 一致后加密
	            $password = User::encry_password($username, $password);
	        }

	    } else {
	        array_push($WARN_MESSAGE, '请再次输入密码');
	        $can_submit = false;
	    }

	    // 验证码 验证
	    if (!empty($verifycode)) {

	        if (strtoupper($verifycode) != $_SESSION["verifycode"]) {

	            array_push($WARN_MESSAGE, '验证码输入不正确！');
	            $can_submit = false;    
	        }
	    } else {
	        array_push($WARN_MESSAGE, '验证码不能为空');
	        $can_submit = false;
	    }

	    // 输入数据库
	    if ($can_submit) {

	        $unique_id = User::get_unique();

	        $query = "INSERT INTO users 
	            (username, password, register_time, unique_id, level) 
	            VALUES ('$username', '$password', NOW(), '$unique_id', 0);";
	 
	        
	        $result = $mysql->query_db($query);

	        if ($result) {
	            
	            if ($mysql->affected_rows() == 1) {
	                $COOKIES_TIME  = time() + 60 * 60 * 24;
	                setcookie("username", $username, $COOKIES_TIME);
	                setcookie('unique_id', $unique_id, $COOKIES_TIME);
	                setcookie('user_bg', $DEFAULT_USER_BACKGROUND_IMAGE, $COOKIES_TIME);
	                setcookie('avatar', $DEFAULT_USER_AVASTAR, $COOKIES_TIME);
	            }
	            
	            header("Location:login.php");
	        } 
	    }
	}



	public static function del_user_by_username () {

	}

	public static function get_all () {

		$res_arr = array();

		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);
		
		$query = "SELECT *
			FROM users
			ORDER BY  DESC";

		$result = $sql->query_db($query);

		if ($result) {
			while($row = $sql->fetch_array()) {
				$temp_arr = array(
					'ID' 		=> $row['ID'],
					'name'		=> $row['book_name'],
					'publisher'	=> $row['publisher'],
					'cover'		=> $row['cover'],
					'author'	=> $row['author'],
					'date'		=> $row['publish_date'],
					'sum'		=> $row['sum_count'],
					'borrow'	=> $row['borrowed_count'],
					'cate'		=> $row['category'],
					'tag'		=> $row['tags'],
					'summary' 	=> $row['summary']
					);

				array_push($res_arr, $temp_arr);
			}

			return $res_arr;
		}
		return False;
	}
}

?>