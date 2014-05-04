

<?php 
session_start();


if ($_GET) {
	// $username = $_GET["user"];
}

?>
<!DOCTYPE html>
<html>
<head>
	<link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/main.css" rel="stylesheet" type="text/css" />
    <link href="style/style.css" rel="stylesheet" type="text/css" />
	<title></title>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="content">
			
			<a href="logout.php">LOGOUT</a>
		</div>
	</div>
</body>
</html>


