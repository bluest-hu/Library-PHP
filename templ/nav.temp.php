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
					<a href="<?php echo $BASE_URL;?>">
						<span class="icon">&#xF161</span>Index</a>
				</li>
				<li class="<?php get_current("books.php"); ?>">
					<a href="<?php echo $BASE_URL. "/books.php";?>"><span class="icon">&#xF0D1</span>Books</a>
				</li>
				<li class="<?php get_current("author.php"); ?>">
					<a href="<?php echo $BASE_URL. "/author.php";?>"><span class="icon">&#xF136</span>Author</a>
				</li>
				<li class="<?php get_current("admin/profile.php"); ?>">
					<a href="<?php echo $BASE_URL . '/admin/profile.php'; ?>"><span class="icon">&#xF045</span>Users</a>
				</li>
				<li class="<?php get_current("search.php"); ?>">
					<a href="<?php echo $BASE_URL . '/search.php'; ?>"><span class="icon">&#xF0AD</span>Search</a>
				</li>
			</ul>
		</nav>

		<div class="search left">
			<form class="search-from" id="searchForm" method="POST" action="<?php echo $BASE_URL . '/search.php?action=search_all'; ?>">
				<input type="text" name="book_name" id="s" placeholder="Search" required/>
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

					if($_SESSION['level'] == 1) {
						echo "管理员";
					} else {
						echo "普通用户";
					}
					?>
					</span>
				</div>
				<div class="user-action">
					<ul class="actions">
						<li><a href="<?php echo $BASE_URL . "/admin/profile.php" ?>">用户信息</a></li>
						<li><a href="<?php echo $BASE_URL . '/Logout.php'; ?>">登出</a></li>
					</ul>
				</div>
			</div>
			
		<?php
		// 还没没有登陆 
		} else {
		 ?>
		<div class="user-area not-login">
			<a class="login-btn ribbon" href="<?php echo $BASE_URL . '/login.php' ?>"><span class="icons">&#xF150</span> Login</a>
			<a class="register-btn" href="<?php echo $BASE_URL . '/register.php' ?>"><span class="icons">&#xF171</span>Register</a>
		</diiv>
		<?php } ?>
		</div>
	</div>
</header>