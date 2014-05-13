<?php
session_start();

include(dirname(__FILE__) . "../config.php");
include(dirname(__FILE__) . "../function.php");
include(dirname(__FILE__) . "../class/mysql.class.php");
include(dirname(__FILE__) . "../class/category.class.php");
include(dirname(__FILE__) . "../class/user.class.php");


// 存储警告信息
$WARN_MESSAGE = array();
$CAN_LOGIN = TRUE;

// 存储每次的返回路径
if (isset($_SERVER['HTTP_REFERER'])) {
    // 如果非直接打开本页的话（$_SERVER['HTTP_REFERER'])的值为NULL）
    $referer_url = parse_url($_SERVER['HTTP_REFERER'])['path'];
} else {
    // 直接打开为NULL
    $referer_url = NULL;
}

// 在session中初始化默认值为空
if (!isset($_SESSION['referer_url'])) {
    $_SESSION['referer_url'] = NULL;
}

// 过滤出非本页的返回路径
if ($referer_url != $_SERVER['PHP_SELF'] && $referer_url != NULL) {
   $_SESSION['referer_url'] =  $referer_url;
} 

// 验证登陆
if ($_GET) {
    if ($_GET['action'] == "login") {
        if ($_POST) {
            // 存储POST 数据
            $username     = $_POST["username"];
            $password     = $_POST["password"];
            // 处理记住我选项
            $remberme   = isset($_POST["remberme"]) ? $_POST["remberme"] : false;
            
            // 验证用户名不为空
            if (empty($username)) {
                array_push($WARN_MESSAGE, '用户名不能为空！');
                $CAN_LOGIN = FALSE;
            }

            // 验证密码不为空
            if (empty($password)) {
                array_push($WARN_MESSAGE,'密码不能为空！');
                $CAN_LOGIN = FALSE;
            } 

            if ($CAN_LOGIN) {
                
                $username   = MySQLDatabase::escape($username);
                $password   = MySQLDatabase::escape($password);
                $remberme   = MySQLDatabase::escape($remberme);
                $password   = User::encry_password($username, $password);

                $login_query = "SELECT *
                            FROM users 
                            WHERE username = '$username' 
                            LIMIT 1";

                $login_sql = new MySQLDatabase($DATABASE_CONFIG);

                $login_result = $login_sql->query_db($login_query);

                if ($login_result) {
                    
                    if ($login_sql->num_rows() === 0) {
                        array_push($WARN_MESSAGE, '用户名不存在！');
                    }

                    while($row = $login_sql->fetch_array()) {
                        if ($password === $row['password']) {
                            
                            $_SESSION['username']   = htmlspecialchars_decode($username);
                            $_SESSION['is_login']   = TRUE;
                            $_SESSION['avatar']     = is_null($row['avatar']) ? $DEFAULT_USER_AVASTAR : $row['avatar'];
                            $_SESSION['user_bg']    = is_null($row['cover_bg']) ? $DEFAULT_USER_BACKGROUND_IMAGE : $row['cover_bg'];
                            $unique_id               = User::get_unique();
                            $_SESSION['level']      = (int)$row['level'];

                            setcookie('username', $username, time() + 60 * 60 * 24);
                            // 悬着记住密码的情况
                            if ($remberme === "on") {
                                // 处理自动登录
                                $update_uipque_id = "UPDATE users 
                                        SET unique_id = '$unique_id'
                                        WHERE username = '$username' 
                                        LIMIT 1";

                                $update_query = new MySQLDatabase($DATABASE_CONFIG);

                                $update_result = $update_query->query_db($update_uipque_id);

                                if($update_result) {
                                    if ($update_query->affected_rows() == 1 ) {
                                        $COOKIES_TIME  = time() + 60 * 60 * 24;
                                        //把密码加密后存储在Cookies中
                                        setcookie('username', $_SESSION['username'], $COOKIES_TIME);
                                        setcookie('unique_id', $unique_id, $COOKIES_TIME);
                                        setcookie('user_bg', $_SESSION['user_bg'], $COOKIES_TIME);
                                        setcookie('avatar', $_SESSION['avatar'], $COOKIES_TIME);
                                    }
                                }
                            }

                            // 跳转到转入页面
                            $location = is_null($_SESSION['referer_url']) ? "user.php?user=". $_SESSION['username'] : $_SESSION['referer_url'];
                            header("Location:" .$BASE_URL. $location); 
                            
                        } else {
                            array_push($WARN_MESSAGE, '密码或者用户名错误！');
                        }
                    }
                }
            }
        }
    }
}

// 处理已经登陆的情况
if (isset($_SESSION['is_login'])) {
    if ($_SESSION['is_login']) {
        header("Location:user.php?user={$_SESSION['username']}");
    }
}


// 处理记住我的自动那个登陆
if (isset($_COOKIE['username']) && isset($_COOKIE['unique_id']) ) {

    $cookies_username   = $_COOKIE['username'];
    $cookies_unique_id  = $_COOKIE['unique_id'];
    // 过滤字符串
    $cookies_username   = MySQLDatabase::escape($cookies_username);
    $cookies_unique_id  = MySQLDatabase::escape($cookies_unique_id);

    $query = "SELECT *
                FROM users 
                WHERE username = '$cookies_username' 
                LIMIT 1";
                
    $mysql = new MySQLDatabase($DATABASE_CONFIG);
    $result = $mysql->query_db($query);

    if ($result) {
        while($row = $mysql->fetch_array()) {
            if ($cookies_unique_id === $row['unique_id']) {
                // 将重要信息写入 SESSION
                $_SESSION['username']   = htmlspecialchars_decode($cookies_username);
                $_SESSION['is_login']   = TRUE;
                $_SESSION['avatar']     = is_null($row['avatar']) ? $DEFAULT_USER_AVASTAR : $row['avatar'];
                $_SESSION['level']      = $row['level'];
                $_SESSION['user_bg']    = is_null($row['cover_bg']) ? $DEFAULT_USER_BACKGROUND_IMAGE : $row['cover_bg'];
                $cookies_unique_id      = User::get_unique(); 

                // 处理自动登录
                $update_query = "UPDATE users 
                            SET unique_id = '$cookies_unique_id'
                            WHERE username = '$cookies_username' 
                            LIMIT 1";

                $update_mysql = new MySQLDatabase($DATABASE_CONFIG);

                $update_result = $update_mysql->query_db($update_query);

                if ($update_result) {
                     if ($update_mysql->affected_rows() == 1 ) {
                        //把密码加密后存储在Cookies中 一天
                        $COOKIES_TIME  = time() + 60 * 60 * 24;
                        setcookie('username', $_SESSION['username'], $COOKIES_TIME);
                        setcookie('unique_id', $cookies_unique_id, $COOKIES_TIME);
                        setcookie('user_bg', $_SESSION['username'], $COOKIES_TIME);
                        setcookie('avatar', $_SESSION['avatar'], $COOKIES_TIME);

                        // 跳转到转入页面
                        $location = $_SESSION['referer_url'] == NULL ? "user.php?user=". $_SESSION['username'] : $_SESSION['referer_url'];
                        header("Location:" . $BASE_URL . $location); 
                    }
                }
            }
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登陆</title>
    <link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/main.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <link href="style/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="main">
    <?php include("templ/nav.temp.php"); ?>
        <div class="content">
            <div class="login box center">
                <div class="login-title box-header">
                    <img class="header-bg" src="<?php echo isset($_COOKIE['user_bg']) ? $_COOKIE['user_bg'] : $DEFAULT_USER_BACKGROUND_IMAGE; ?>" alt="">
                    <img  class="avatar-100 avatar" src="<?php echo isset($_COOKIE['avatar']) ? $_COOKIE['avatar'] : $DEFAULT_USER_AVASTAR ?>" alt="" >
                    <a class="register-btn avatar avatar-100" href="register.php">+</a>
                </div>

                <div class="box-body">
                    <form name="login" action="<?php echo $_SERVER['PHP_SELF'] . "?action=login"; ?>" method="POST" class="form">
                        <p class="clear">
                            <label for="username" class="username-label">&#xF170</label>
                            <input type="text" id="username" name="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : '';?>" placeholder="用户名" required>
                        </p>
                       
                        <p class="clear">
                            <label for="password" class="password-label">&#xF0C2</label>
                            <input type="password" id="password" class="password" name="password" placeholder="密码" required>
                        </p>

                        <div class="rember-me-container">
                            <label for="remeberme" class="remberme-label">记住我：</label>
                            <div class="switcher">
                                <div class="container">
                                    <span class="indicator clear">
                                        <span class="on status">ON</span>
                                        <span class="blank status"></span>
                                        <span class="off status">OFF</span>
                                    </span>
                                    <span class="left-area"></span>
                                    <span class="right-area"></span>
                                </div>
                            </div>
                            <input type="hidden" id="remberme" class="rember-me" value="on" name="remberme">
                        </div>
 
                        <div>
                            <input type="submit" class="btn" id="submit" value="Login">
                        </div>
                    </form>
                   
                </div>

                <?php if (count($WARN_MESSAGE) >= 1) { ?>
                <div class="box-footer">
                    <div class="warning message">
                        <span class="close-btn">X</span>
                         <?php
                            foreach ($WARN_MESSAGE as $value) {
                                echo "\t\t" . "<p>{$value}</P>\n";
                        }?> 
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>  
    </div>
	<?php include("templ/footer.temp.php");?>
</body>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
<script type="text/javascript"></script>

</html>