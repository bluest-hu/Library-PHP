<?php 
session_start();

include(dirname(__FILE__) . "../../config.php");
include(dirname(__FILE__) . "../../function.php");
include(dirname(__FILE__) . "../../class/user.class.php");
include(dirname(__FILE__) . "../../class/mysql.class.php");
include(dirname(__FILE__) . "../../class/category.class.php");
include(dirname(__FILE__) . "../../class/borrow_book.class.php");

// 没有登陆切没有指定查看的用户 强制跳转到登陆
if (!isset($_SESSION['is_login']) && !isset($_GET["user"]) && $_SESSION['level'] < 1) {
	header("Location:" . $BASE_URL . "/login.php"); 
}

$WARN_MESSAGE = array();


$u_id = $_SESSION['user_id'];

$user = User::Get_info_by_id($u_id);

$sex_old 		= $user['sex'];
$location_old 	= $user['location'];
$avatar_old 	= $user['avatar'];
$bg_old 		= $user['bg'];

if ( $_GET ) {
	if (isset($_GET['action']) && $_GET['action'] === "update_profile") {
		if ($_POST) {
			$sex_get = trim(MySQLDatabase::escape($_POST['sex']));
			$location_get = trim(MySQLDatabase::escape($_POST['location']));

			if (empty($sex_get)) {
				$sex_get = (int)$sex_old;
			} else {
				$sex_get = (int)$sex_get;
			}

			if (empty($location_get) || $location_get == "未知") {
				$location_get = $location_get;
			}

			$query = "UPDATE users
				set sex = '$sex_get',
					location ='$location_get'
					WHERE ID = $u_id;
			";

			MySQLDatabase::query($query);


			$FILE_UPLOAD_CONFIG = array(
			'MAX_FILE_SIZE' => 10000 * 1000,
			'TYPE' 			=> array(
				'image/jpeg',
				'image/png',
				'image/gif',
				'image/pjpeg'
				),
			'DIR' 			=> '../image/avatar/'
			);

			if ($_FILES) {

				$file_cover = $_FILES['cover'];

				if ($file_cover['error'] == 0) {
					// FLAG 能否上传成功的标志位
					$CAN_UPLOAD = true;

					// 检查文件尺寸
					if ($file_cover["size"] > $FILE_UPLOAD_CONFIG['MAX_FILE_SIZE']) {
						array_push($WARN_MESSAGE, '上传文件尺寸过大');
						$CAN_UPLOAD = false;
					}

					// 检查文件类型
					if (!in_array($file_cover['type'], $FILE_UPLOAD_CONFIG['TYPE'])) {
						array_push($WARN_MESSAGE, '上传文件类型不符合');
						$CAN_UPLOAD = false;
					}
				
					$filename = md5($user['name']) . '_bg.' . getExtenName($file_cover['name']);
					$file_full_name = $FILE_UPLOAD_CONFIG['DIR'] . $filename;

					if ($CAN_UPLOAD) {
						// 上传成功
						if (move_uploaded_file($file_cover['tmp_name'], $file_full_name)) {
							$cover = $filename;
							$query = "UPDATE users
								set cover_bg = '$cover'
								WHERE ID = $u_id";

							MySQLDatabase::query($query);
							$_SESSION['user_bg'] = $BASE_URL . "/image/avatar/" . $cover;	
							setcookie('user_bg', $_SESSION['user_bg'], $COOKIES_TIME);

						} 
					} else {
						array_push($WARN_MESSAGE, "文件上传失败");
					}
				} else {
					switch ($file_cover['error']) {
						case UPLOAD_ERR_INI_SIZE :
							break;
						case UPLOAD_ERR_FORM_SIZE :
							break;
						case UPLOAD_ERR_PARTIAL :
							break;
						case UPLOAD_ERR_NO_FILE :
							break;
						case UPLOAD_ERR_NO_TMP_DIR :
							break;
						case UPLOAD_ERR_CANT_WRITE :	
							break;
						default:
							break;
					}
				}
			}




			if ($_FILES) {

				$file_avatar = $_FILES['avatar'];


				if ($file_avatar['error'] == 0) {
					// FLAG 能否上传成功的标志位
					$CAN_UPLOAD = true;

					// 检查文件尺寸
					if ($file_avatar["size"] > $FILE_UPLOAD_CONFIG['MAX_FILE_SIZE']) {
						array_push($WARN_MESSAGE, '上传文件尺寸过大');
						$CAN_UPLOAD = false;

					}

					// 检查文件类型
					if (!in_array($file_avatar['type'], $FILE_UPLOAD_CONFIG['TYPE'])) {
						array_push($WARN_MESSAGE, '上传文件类型不符合');
						$CAN_UPLOAD = false;
					}
				
					$filename = md5($user['name'])  . getExtenName($file_avatar['name']);
					$file_full_name = $FILE_UPLOAD_CONFIG['DIR'] . $filename;

					if ($CAN_UPLOAD) {
						// 上传成功
						if (move_uploaded_file($file_avatar['tmp_name'], $file_full_name)) {

							$avatar_upload = $filename;

							$query = "UPDATE users
								set avatar = '$avatar_upload'
								WHERE ID = $u_id";

							MySQLDatabase::query($query);
							$_SESSION['avatar'] = $BASE_URL . "/image/avatar/" . $avatar_upload;

                            setcookie('avatar', $_SESSION['avatar'], $COOKIES_TIME);	
						} 
					} else {
						array_push($WARN_MESSAGE, "文件上传失败");
					}
				} else {
					switch ($file_avatar['error']) {
						case UPLOAD_ERR_INI_SIZE :
							break;
						case UPLOAD_ERR_FORM_SIZE :
							break;
						case UPLOAD_ERR_PARTIAL :
							break;
						case UPLOAD_ERR_NO_FILE :
							break;
						case UPLOAD_ERR_NO_TMP_DIR :
							break;
						case UPLOAD_ERR_CANT_WRITE :	
							break;
						default:
							break;
					}
				}
			}
		}

		header("location:" . $BASE_URL . "/admin/profile.php");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>User</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/datepicker/css/datepicker.css">
   	<link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/datepicker/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/books_add.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $BASE_URL; ?>/style/user.css" />
    <style type="text/css">
	label {
		display: block;
		float: left;
	}
	
	#submit {
		display: inline-block;
		width: 400px;
		height: 40px;
		margin-top: 30px;
		background-color: #3498DB;
	}

    </style>
</head>
<body>
	<div class="main">
		<?php include(dirname(__FILE__) . "../../templ/nav.temp.php"); ?>

		<div class="content clear" id="mianContent">
			<?php include(dirname(__FILE__) . "../../templ/usernav.temp.php"); ?>
			<div class="right-container right">
				<h2 class="title"><span class="icons">&#xF0E3</span>用户信息</h2>
				<div class="catagory right-content clear">
					<div class="user-profile left">
						<h3 class="title">
							修改用户信息
						</h3>
						<div>
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=update_profile" method="POST" enctype="multipart/form-data">
								<div class="clear">
									<label for="cateDropDownInput left">性别：</label>
									<div class="drop-down-input catagory-input left">
										<input class="input-text" id="cateDropDownInput" type="text" placeholder="男" readonly>
										<span class="arrow-container"><span class="arrow">&#xF16B</span></span>
										<div class="options">
											<span class="iteams" data-id="1">男</span>
											<span class="iteams" data-id="2">女</span>
										</div>
										<input class="hidden-input" type="hidden" name="sex" value="<?php echo $user['sex']; ?>">
									</div>
								</div>
								<p>
									<label for="">地址：</label>
									<input type="text" name="location" value="<?php echo $user['location']; ?>">
								</p>
								
								<div class="upload">
									<label for="upload">头像：</label>
									<div class="upload-cover">
										<input class="input-cover" type="text" name="" id="uploadAvatar">
										<input class="btn-cover" type="button" value="上传">
									</div>
									<input class="upload-btn" type="file" name="avatar" id="avatar">
								</div>

								<div class="upload">
									<label for="upload">背景：</label>
									<div class="upload-cover">
										<input class="input-cover" type="text" name="" id="uploadBg">
										<input class="btn-cover" type="button" value="上传">
									</div>
									<input class="upload-btn" type="file" name="cover" id="cover">
								</div>

								<input type="submit" id="submit">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include(dirname(__FILE__) . "../../templ/footer.temp.php");?>
</body>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/jquery-2.1.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/script/common.js"></script> 
	<script type="text/javascript" src="<?php echo $BASE_URL; ?>/datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript">

	$(function () {
		$(".drop-down-input").each(function (index,element){
			var $dropDownInput = $(element);
			var $input = $dropDownInput.find(".input-text");
			var $menuContainer = $dropDownInput.find(".options");
			var $arrow = $dropDownInput.find(".arrow");
			var $iteams = $menuContainer.find(".iteams");
			var $hidden = $dropDownInput.find(".hidden-input");

			$input.on("focus", function() {
				$menuContainer.slideDown();
				$arrow.addClass("target");

				$that = $(this);
				$iteams.on("click", function () {
					$that.val($(this).text());
					var value = parseInt($(this).data("id"));
					value = isNaN(value) ? 0 : value;
					$hidden.get(0).value = value;
				});
			}).on("blur", function () {
				$menuContainer.slideUp();
				$arrow.removeClass("target");
			}).on("keyDown", function(event){
				event = event || window.event;
				event.preventDefault();
				return false;
			});
		});

		// $input.css({'cursor':"pointer"});
	});

	// $(function() {
	// 	$('.datepicker').datepicker();
	// });

	$(function () {
		$(".upload").each(function (index, element) {
			var $upload = $(element);
			var $input = $upload.find(".upload-btn");
			var $cover = $upload.find(".input-cover");

			$input.on("change", function() {
				$cover.val($(this).val());
			});
		});
	});

	</script>
</html>


