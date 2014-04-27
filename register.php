<?php

require_once("function.php");

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
    $username           = trim($_POST["username"]);
    $password           = $_POST["password"];
    $password_repeat    = trim($_POST['password_repeat']);
    $verifycode         = trim($_POST["verifycode"]);

    // 验证用户名
    if (filter_has_var(INPUT_POST, "username")) {
        
        $username = htmlspecialchars($username);

        if (!filter_var($_POST['username'], FILTER_VALIDATE_REGEXP, 
            array(
                "options" => array(
                    "regexp"=>"/^[0-9a-zA-Z]{2,20}$/"
                    )
                )
            )) {

            $can_submit = false;
            array_push($WARN_MESSAGE, "用户名必须为包含[A-Z][a-z][0-9]数字组合，长度为2-20位");
        }


        // 验证用户名 是否重复
        $query = "SELECT username 
                    FROM user 
                    WHERE username = '$username' 
                    LIMIT 1";

        $result = con(array(
            "server"    => "localhost",
            "user"      => "root",
            "password"  => "1010",
            "database"  => "musicbox",
            "query"     => $query
        ));

        if ($result) {

            $num = mysql_num_rows($result);
            
            if ( $num >= 1) {
                array_push($WARN_MESSAGE, '该用户名已经存在，请更换');
                $can_submit = false;
            }
            
            unset($result);
        } else {
            $can_submit = false;
            array_push($WARN_MESSAGE, '网站开小差了，请稍后再试');
        }

    } else {
        array_push($WARN_MESSAGE, '用户名不能为空');
        $can_submit = false;
    } 

    // 验证密码
    if (!empty($password)) {
        $password = htmlspecialchars($password);
    } else {
        array_push($WARN_MESSAGE, '密码不能为空');
        $can_submit = false;
    } 

    // 验证密码重复 是否一致
    if (!empty($password_repeat)) {
        $password_repeat = htmlspecialchars($password_repeat);

        if ($password_repeat != $password) {
            array_push($WARN_MESSAGE, '两次密码输入不一致！');
            $can_submit = false;
        } else {
            // 一致后加密
            $password = sha1($username.$password);
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
        echo array_push($WARN_MESSAGE, '验证码不能为空');
        $can_submit = false;
    }

    // 输入数据库
    if ($can_submit) {

        $u_id = sha1(uniqid(mt_rand(), true));

        $query = "INSERT INTO user (username, password, register_time, u_id) 
                    VALUES ('$username', '$password', NOW(), '$u_id');";

         echo $query;   
        
        $result = con(array(
            "server"    => "localhost",
            "user"      => "root",
            "password"  => "1010",
            "database"  => "musicbox",
            "query"     => $query
        ));

        if ($result) {
            setcookie("username", $username, time() + 24 * 60 * 60);
            
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
        <link rel="stylesheet" href="style/main.css">
        <style type="text/css">
        .box.register {
            width: 440px;
            margin: 20px auto;
        }
        .register .verify-code {
            width: 140px;
        }

        .register .verify-img {
            width: 150px;
            height: 32px;
            margin: -1px 0 0 4px ;
            vertical-align: smiddle;
            display: inline-block;
        }
        </style>
    </head>
    <body>
        
        <?php include('tmpl/nav.tmpl.php'); ?>

        <div class="box register">
            <div class="box-header">
                <h3 class="box-title">
                    注册
                </h3>
            </div>
            <div class="box-body">
                <form action="<?php echo $_SERVER["PHP_SELF"] .'?action=register';?>" method="post" class="register-form">
                    <p>
            	        <label for="userName">用户名：</label>
            	        <input type="text" id="userName" class="username" name="username" required>
                    </p>

                    <p>
            	        <label for="password">密码：</label>
            	        <input type="password" id="password" class="password" name="password" required>
                    </p>
                    
                    <p>
                        <label for="passwordRepeat">重复密码：</label>
                        <input type="password" id="passwordRepeat" class="password-repeat" name="password_repeat" required>
                    </p>
                    
                    <p>
                        <label for="verifyCode">验证码：</label>
                        <input type="text" id="verifyCode" name="verifycode" class="verify-code" required>   
                        <input type="image" id="verifyImg" src="verify.php" size="<?php echo strlen($_SESSION['verifycode']);?>" class="verify-img">
        	        </p>

                    <p>
                        <input type="submit" class="btn">
                        <span class="clear"></span>
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
        

    <script type="text/javascript">
        window.addEventListener("DOMContentLoaded", function () {

            var verifyImg = document.getElementById('verifyImg');
            var username = document.getElementById("userName");

            function refreshVerifyImg() {
                var src = verifyImg.src;
                verifyImg.addEventListener("click", function (event) {
                    this.src = src + "?" + new Date().getTime() + Math.random();
                    event.preventDefault();
                }, false);
            }

            checkRegisterForm();

            function checkRegisterForm() {
                username.addEventListener("focus", function () {
                    var value = this.value;


                }, false)

            }

            function closeWarningMessage() {
            }

            
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
    </body>
</html>
