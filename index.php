<?php 
session_start();

include(dirname(__FILE__) . "../config.php");
include(dirname(__FILE__) . "../function.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books_add.css" />
    <style type="text/css">
    .banner {
    	width: 100%;
    	overflow: hidden;
    }
	.banner .banner-iteams {
		width: 1366px;
		float: left;
	}
	.banner-container {
		position: relative;
		left: 0px;
	}

	.banner .banner-iteams .banner_bg {
		display: block;
		width: inherit;
		float: left;
	}
	.banner .banner-iteams .text-content {
		width: 1000px;
		margin: 0 auto;
		left: 0px;
		right: 0px;
		position: absolute;
	}
	.header {
		margin-bottom: 0px;
	}

	.search-area {
		border-top: 1px solid #333;
		position: relative;
		width: 100%;
		height: 400px;
		overflow: hidden;
	}

	.search-area .move-bg {
		position: absolute;
		top: 0px;
		left: 0px;
		right: 0px;
		bottom: 0px;
		margin: auto;
		width: 2000px;
		height: 1333px;
	}

	.search-area .search-from {
		width: 550px;
		height: 50px;
		position: absolute;
		top: 0px;
		left: 0px;
		right: 0px;
		bottom: 0px;
		margin: auto;
	}

	#search {
		border: 3px solid #666;
		width: 550px;
		height: 50px;
		border-radius: 80px;
		font: normal 25px/50px Arial;
		color: #eeaeae;
		text-indent: 1em;
	}
	#saerchSubmit {
		position: absolute;
		right: 5px;
		top: 2px;
		font-family: "Batch";
		width: 50px;
		height: 50px;
		line-height: 50px;
		font-size: 30px;
		background-color: transparent;
		color: #666;
	}
    </style>
</head>
<body>
	<div class="main">
		<?php include("templ/nav.temp.php"); ?>

		<div class="banner ">
			<ul class="banner-container clear">
				<li class="banner-iteams">
					<div class="text-content">
						<p>this is text</p>
					</div>
					<img  class="banner_bg" src="<?php echo $BASE_URL; ?>/image/banner/1.jpg" alt="">
				</li>
				<li class="banner-iteams">
					<img  class="banner_bg" src="<?php echo $BASE_URL; ?>/image/banner/2.jpg" alt="">
				</li>
				<li class="banner-iteams">
					<img  class="banner_bg" src="<?php echo $BASE_URL; ?>/image/banner/3.jpg" alt="">
				</li>
				<li class="banner-iteams">
					<img  class="banner_bg" src="<?php echo $BASE_URL; ?>/image/banner/4.jpg" alt="">
				</li>
				<li class="banner-iteams">
					<img  class="banner_bg" src="<?php echo $BASE_URL; ?>/image/banner/5.jpg" alt="">
				</li>
				<li class="banner-iteams">
					<img  class="banner_bg" src="<?php echo $BASE_URL; ?>/image/banner/6.jpg" alt="">
				</li>
			</ul>
		</div>

		<div class="search-area">
			<img src="<?php echo $BASE_URL; ?>/image/search_bg.jpg" class="move-bg" id="moveSearchBg">
			<form class="search-from" id="searchForm" method="POST" action="<?php echo $BASE_URL . '/search.php?action=search_all'; ?>">
				<input type="text" name="book_name" id="search" placeholder="Search" required/>
				<input type="submit" id="saerchSubmit" value="&#xF097" />	
			</form>
		</div>
		<?php include("templ/footer.temp.php");?>
	</div>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
	<script type="text/javascript">

	$(function () {
		var windowWidth  = null;
		var INDEX = 0;
		var $banner =  $(".banner");
		var $bannerContainer = $banner.find(".banner-container");
		$bannerIteams = $banner.find(".banner-iteams");

		initBannerWidth();

		function initBannerWidth() {
			windowWidth = $('body').width();
			$banner.css("width", windowWidth);
			$bannerIteams.css("width", windowWidth);
			$bannerContainer.css("width", windowWidth * $bannerIteams.length);
		}

		var timer = setInterval(function () {
			$bannerContainer.animate({"left": -windowWidth * INDEX});
			changeIndex(true);
		}, 1500)


		function changeIndex(direction) {
			INDEX += direction ? 1: -1;

			if (INDEX < 0) {

			} else if (INDEX >= $bannerIteams.length) {
				INDEX = 0;
			}
		}

		$(window).on("resize", function () {
			initBannerWidth();
		})
	});

	// move bg
	$(function () {
		var $moveBg = $("#moveSearchBg"),
			$container = $(".search-area");

		var MOVE_RADIUS_HOR = null;
		var MOVE_RADIUS_VIR = null;

		var toLeftDis = null;
		var toBottomDis = null;

		var MOVE_COUNT = 0;

		var time  = setInterval(function () {

			MOVE_COUNT++;

			if ( MOVE_COUNT === 1 ) {

				toLeftDis = MOVE_RADIUS_HOR / 2;
				toBottomDis = MOVE_RADIUS_VIR /2;

			} else if ( MOVE_COUNT === 2 ) {
				
				toLeftDis = 0;
				toBottomDis = 0;

			} else if ( MOVE_COUNT === 3 ) {

				toLeftDis = MOVE_RADIUS_HOR / 2;
				toBottomDis = MOVE_RADIUS_VIR / 2;
				
			} else if ( MOVE_COUNT === 4 ){

				toLeftDis = MOVE_RADIUS_HOR;
				toBottomDis = MOVE_RADIUS_VIR;
			
				MOVE_COUNT = 0;
			}
		
			$moveBg.animate({
				"left": toLeftDis,
				"top" : toBottomDis
			}, 10000, "linear");

			// cal move dis
		}, 300);

		
		(function init() {
			centerBg();
		})();

		function centerBg() {
			var containerWidth  = parseInt($container.css("width"));
			var containerHeight = parseInt($container.css("height"));

			var moveBgWidth = parseInt($moveBg.css("width"));
			var moveBgHeight = parseInt($moveBg.css("height"));

			MOVE_RADIUS_HOR  = (containerWidth - moveBgWidth) / 2;
			MOVE_RADIUS_VIR = (containerHeight - moveBgHeight) / 2;

			$moveBg.css({
				"left": MOVE_RADIUS_HOR,
				'top': MOVE_RADIUS_VIR
			});
		};

		$(window).on("resize", function () {
			centerBg();
		});

	});
	</script>
</body>
</html>