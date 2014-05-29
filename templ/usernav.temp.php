<div class="left user-nav left-container">
	<div class="user-card">
		<img class="user-bg" src="<?php echo $_SESSION['user_bg']; ?>" alt="">
		<div class="user-info">
			<a class="avatar avatar-50 left user-avastar" href="<?php echo $BASE_URL . "/user.php?user=" . $_SESSION['username'] ?>">
				<img class="avatar avatar-50" src="<?php echo isset($_SESSION['avatar']) ?  $_SESSION['avatar'] : "image/default.png";  ?>">
			</a>
			<div class="text-info">
				<span class="username"><?php echo $_SESSION['username']; ?></span><span class="text-normal level">
				<?php
				switch($_SESSION['level']) {
					case 0:
						echo "Super";
						break;
					case 1:
						echo "普通用户";
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
		
		</div>
	</div>
	<nav class="navigation">
		<ul>
			<li>
				<a href="<?php echo $BASE_URL . "/user.php" ?>"><span class="icons">&#xF080</span>Profile</a>
			</li>
			<li>
				<a href="<?php echo $BASE_URL . "/admin/borrow_detail.php";?>"><span class="icons">&#xF133</span>借阅信息</a>
			</li>
<!-- 			<li>
				<a href=""><span class="icons">&#xF04D</span>设置</a>
			</li> -->
			<li>
				<a href="<?php echo $BASE_URL . "/admin/books.php" ?>">
					<span class="icons">&#xF0D2</span>
					图书
				</a>
				<ul>
					<li><a href="<?php echo $BASE_URL . "/admin/add_books.php" ?>">添加图书</a></li>
					<li><a href="<?php echo $BASE_URL . "/admin/category.php" ?>">目录管理</a></li>
					<li><a href="<?php echo $BASE_URL . "/admin/add_books.php" ?>">作者管理</a></li>
				</ul>
			</li>
			<li>
				<a href="<?php echo $BASE_URL . "/admin/user.php" ?>">
					<span class="icons">&#xF171</span>
					用户管理
				</a>
			</li>
			
		</ul>
	</nav>
</div>	