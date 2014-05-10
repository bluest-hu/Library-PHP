<?php

include("config.php");
include("class/user.class.php");

// 开启session
session_start();

// 存储警告
$WARN_MESSAGE = array();
// 可否提交标志位
$can_submit = true;

// 防止恶意注册
if ($_GET) {
    if ($_GET['action'] != "register") {
        $can_submit = false;
    }
}

if ($_POST) {

    //存储POST信息
    $username           = MySQLDatabase::escape(trim($_POST["username"]));
    $password           = MySQLDatabase::escape(trim($_POST["password"]));
    $password_repeat    = MySQLDatabase::escape(trim($_POST['password_repeat']));
    $verifycode         = MySQLDatabase::escape(trim($_POST["verifycode"]));
    $level              = 1;

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
            VALUES ('$username', '$password', NOW(), '$unique_id', 1);";
 
        
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

// 判断已经登陆的跳转
if (isset($_SESSION['is_login'])) {
    if ($_SESSION['is_login']) {
        header("Location:user.php" ."?" . $_SESSION['username']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <link href="style/reset.css" rel="stylesheet" type="text/css" />
        <link href="style/main.css" rel="stylesheet" type="text/css" />
        <link href="style/style.css" rel="stylesheet" type="text/css" />
        <link href="style/register.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="main">
            <?php include('templ/nav.temp.php'); ?>
            <div class="content">
                <div class="box register">
                    <div class="box-header">
                        <h3 class="box-title">
                           &#xF171
                        </h3>
                    </div>
                    <div class="box-body clear">
                        <form action="<?php echo $_SERVER["PHP_SELF"] .'?action=register';?>" method="post" class="register-form">
                            <p>
                    	        <label for="userName">&#xF043</label>
                    	        <input type="text" id="userName" class="username" name="username" placeholder="用户名" required>
                            </p>

                            <p>
                    	        <label for="password">&#xF0C2</label>
                    	        <input type="password" id="password" class="password" name="password" placeholder="密码" required>
                            </p>
                            
                            <p>
                                <label for="passwordRepeat">&#xF0C2</label>
                                <input type="password" id="passwordRepeat" class="password-repeat" name="password_repeat" placeholder="重复密码" required>
                            </p>

                            <p class="clear">
                                <label for="verifyCode">&#xF0C0</label>
                                <input type="text" id="verifyCode" name="verifycode" class="verify-code" placeholder="验证码" required />   
                                <input type="image" id="verifyImg" src="verify.php" size="<?php echo strlen($_SESSION['verifycode']);?>" class="verify-img" >
                	        </p>

                            <p>
                                <input type="submit" class="btn submit" id="submit" >
                            </p>
                        </form>
                    </div>
                    <div class="box-footer">
                        <?php if (count($WARN_MESSAGE) > 0) { ?>
                        <div class="warning message">
                            <span class="close-btn">X</span>
                        <?php 
                            foreach ($WARN_MESSAGE as $value) {
                                echo "\t\t" . "<p>{$value}</P>\n"; 
                            }
                        ?>
                        </div> 
                        <?php } ?>
                    </div>
                </div>  
            </div>
            <?php include("templ/footer.temp.php");?>      
        </div>
    </body>
    <script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
    <script type="text/javascript">
        window.addEventListener("DOMContentLoaded", function () {

            var verifyImg = document.getElementById('verifyImg');
            var username = document.getElementById("userName");

            (function refreshVerifyImg() {
                var src = verifyImg.src;
                verifyImg.addEventListener("click", function (event) {
                    this.src = src + "?" + new Date().getTime() + Math.random();
                    event.preventDefault();
                }, false);
            })();
    
        }, false);


        function ajax(config, callBack) {
            var url,
                method;
                async;
            
            if (config) {
                url     = config.url;
                method  = config.method || "GET";
                async   = config.async || true; 
            }

            var xhr = null;

            if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                try {
                    xhr = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    xhr = null;
                }
            }


            if (xhr) {
                xhr.open(method, url + Math.random(), async);
            }

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) { 

                        var response;
                        var contentType = xhr.getResponseHeader("Content-Type");
                        
                        if (callBack) {
                            if (contentType === "application/json") {
                                
                                response = xhr.responseText;
                                
                                /**
                                 * JSON 解析兼容
                                 */
                                if (JSON && JSON.parse) {
                                    callBack.call(xhr, JSON.parse(response));
                                } else {
                                    callBack.call(xhr, eval("(" + response + ")"));
                                }

                            } else if (contentType == "text/xml" || contentType == "application/xml") {
                                
                                response = xhr.responseXML;
                                callBack.call(xhr, response);
                            } else {

                                response = xhr.responseText;
                                callBack.call(xhr, response);
                            }
                        }
                    }
                }
            }

            xhr.send(null);
        }

        function ParseJSON(content) {
            
            return (JSON || JSON.parse) ? JSON.parse(content) : eval("(" + content + ")");
            
        }

    </script>

</html>
