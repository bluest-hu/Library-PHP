<?php 

include(dirname(__FILE__) . "..\..\config.php");

// hightlight current page
function get_current($target_name) {
	if ($_SERVER['SCRIPT_NAME'] === "/" . $target_name) {
		echo "current";
	}
}


?>

<header class="top header">
	<div class="content">
<!-- 		<div class="logo title left">
			<a href="">
				<h1>Logo</h1>
			</a>
		</div> -->
		

		<nav class="navigation left">
			<ul>
				<li class="<?php get_current("index.php"); ?>">
					<a href="<?php echo $BASE_URL;?>"><span class="icon">&#xF161</span>Index</a>
				</li>
				<li class="<?php get_current("books.php"); ?>">
					<a href="<?php echo $BASE_URL. "/books.php";?>"><span class="icon">&#xF0D1</span>Books</a>
				</li>
				<li class="<?php get_current("bb.php"); ?>">
					<a href=""><span class="icon">&#xF136</span>Author</a>
				</li>
				<li class="<?php get_current("user.php"); ?>">
					<a href="<?php echo $BASE_URL . '/user.php'; ?>"><span class="icon">&#xF045</span>Users</a>
				</li>
				<li>
					<a href="">test</a>
				</li>
			</ul>
		</nav>

		<div class="search left">
			<form class="search-from" id="searchForm" action="">
				<input type="text" name="s" id="s" placeholder="Search"/>
				<input type="submit" id="headerSubmit" value="&#xF097" />	
			</form>
		</div>

		<div class="right">
		<?php 
		// 已经登陆
         if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == true) {
		?>
			<div class="user-area clear">
				<a class="avatar avatar-50 left" href="<?php echo $BASE_URL . "/user.php?user=" . $_SESSION['username'] ?>">
					<img class="avatar-50" src="<?php echo isset($_SESSION['avatar']) ?  $_SESSION['avatar'] : "image/default.png";  ?>">
				</a>
				<div class="user-title left clear">
					<span class="username text-large left"><?php echo $_SESSION['username']; ?></span>
					<!-- <br/> -->
					<span class="text-normal left level">
					<?php
					switch($_SESSION['level']) {
						case 0:
							echo "Super";
							break;
						case 1:
							echo "2B用户";
							break;
						case 2:
							echo "Admin";
							break;
						default:
							echo "";		
							break;
					}		
					?>
					</span>
				</div>
				<div class="user-action">
					<ul class="actions">
						<li><a href="<?php echo $BASE_URL . 'cc';?>"></a></li>
						<li><a href="<?php echo $BASE_URL . '/Logout.php'; ?>">Logout</a></li>
					</ul>
				</div>
			</div>
			
		<?php
		// 还没没有登陆 
		} else {
		 ?>
		<div class="user-area not-login">
			<a class="login-btn ribbon" href="<?php echo $BASE_URL . '/login.php' ?>">Login</a>
			<a class="register-btn" href="<?php echo $BASE_URL . '/register.php' ?>">Register</a>
		</diiv>
		<?php } ?>
		</div>
	</div>
</header>