<?php
	session_start();

    include("config.php");

    $WARN_MESSAGE = array();

    // 验证登陆
	if ($_GET) {
		$username = $_GET["username"];
    	$password = $_GET["password"];
	
		if (!empty($username) && !empty($password)) {
			
			$username = mysql_real_escape_string(htmlspecialchars($username));
			$password = mysql_real_escape_string(htmlspecialchars($password));

			$password = $password = sha1($username . $password);

			$query = "SELECT password, gravatar FROM user WHERE username = '$username' LIMIT 1";
            
            $result = $mysql->query_db($query);

            if ($result) {
            	while( $row = $mysql->fetch_array($result)) {
                    
            		if ($password == $row['password']) {
            			
                        $_SESSION['username'] = htmlspecialchars_decode($username);
                        $_SESSION['gravatar'] = $row['gravatar'];
                        setcookie('username', $username, time() + 60 * 60 * 24);
                        // 把密码加密后存储在Cookies中
            			header("Location:user.php?user=$username");
            		} else {
                        array_push($WARN_MESSAGE, '密码或者用户名错误！');
                    }
            	}
            }
		} else {
			if (empty($username)) {
            	array_push($WARN_MESSAGE, '用户名不能为空！');
        	} 
        	if (empty($password)) {
            	array_push($WARN_MESSAGE,'密码不能为空！');
        	} 
		}
	}

    // 处理已经登陆的情况
    if (isset($_SESSION['username'])) {
        header("Location:user.php?user={$_SESSION['username']}");
    }
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登陆</title>
    <link href="style/main.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
    </style>
</head>
<body>

    <div class="login box">
        <div class="login-title box-header">
            <h3>登陆</h3>
        </div>

        <div class="box-body">
            <form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="form">

                <p>
                    <img src="" alt="">
                </p>

                <p>
                    <label for="username">用户名：</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : '';?>" placeholder="用户名">
                </p>
               
                <p>
                    <label for="password">密码：</label> 
                    <input type="password" id="password" class="password" name="password">
                </p>

                <p>
                    <input type="submit" class="btn" value="submit">
                    <span class="clear"></span>
                </p>

            </form>
           
        </div>

        <div class="box-footer">
            <div class="warning message" style="display:<?php echo count($WARN_MESSAGE) > 0 ? 'block' : 'none'; ?>">
                <span class="close-btn">X</span>
                <?php
                foreach ($WARN_MESSAGE as $value) {
                    echo "\t\t" . "<p>{$value}</P>\n";
                }?>
            </div>
        </div>
    </div>
	
</body>



</html>