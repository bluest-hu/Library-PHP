<?php 
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
				<li><a href=""><span class="icon">&#xF161</span>Index</a></li>
				<li><a href=""><span class="icon">&#xF0D1</span>Books</a></li>
				<li><a href=""><span class="icon">&#xF136</span>Author</a></li>
				<li><a href=""><span class="icon">&#xF045</span>Users</a></li>
				<li><a href="">test</a></li>
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
         if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == true) {
		?>
			<div class="user-area clear">
				<a class="avatar avatar-50 left" href="">
					<img class="avatar-50" src="<?php echo isset($_SESSION['avatar']) ?  $_SESSION['avatar'] : "image/default.png";  ?>">
				</a>
				<div class="user-title left clear">
					<span class="username text-large left"><?php echo $_SESSION['username']; ?></span>
					<!-- <br/> -->
					<span class="text-normal left">Admin</span>
				</div>
			</div>
			
		<?php } else { ?>
		<div class="user-area">
			<a class="login-btn ribbon" href="">Login</a>
		</diiv>
		<?php } ?>
		</div>




		
	</div>
</header>