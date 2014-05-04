<?php
session_start();
$text = $_SESSION["verifycode"] = random_code(5);

verify_code(array(
    "width"         => 170,
    "height"        => 40,
    "text"          => $text,
    "image_padding" => 10,
    "line_number"   => 6,
    "dot_number"    => 1000,
    "dot"           => "*"
));

/**
 * [verify_code description]
 * @param  array $config [description]
 * @return [type]         [description]
 */
function verify_code($config) {

    header ('Content-Type: image/png');

    // 图片句柄
    $image = imagecreatetruecolor($config['width'], $config['height']);

    $bg_color = imagecolorallocate($image, 250, 250, 250);
    $border_color = imagecolorallocate($image, 224, 224, 224);
    

    // 填充背景色
    imagefill($image, 0, 0, $bg_color);

    // 生成随机点
    $dot_text = $config['dot'];
    for ($i = 0; $i < 100; $i++) {
        // 随机点颜色
        $dot_x = mt_rand(0, $config['width']);
        $dot_y = mt_rand(0, $config['height']);
        $dot_color = random_color($image, 200, 250);
        imagestring($image, 1, $dot_x, $dot_y, $dot_text, $dot_color);
    }
    
    // 生成随机线
    if (isset($config['line_number'])) {
        for ($i = 0; $i < $config['line_number']; $i++) {
            $line_color = random_color($image, 200, 240);

            $line_x1 = mt_rand(0, (int)($config['width'] / 2));
            $line_y1 = mt_rand(0, (int)($config['height'] / 2));
            $line_x2 = mt_rand((int)($config['width'] / 2), $config['width']);
            $line_y2 = mt_rand(0, $config['height']);

            imageline($image, $line_x1, $line_y1, $line_x2, $line_y2, $line_color);
        }
    }

    // 填充文字    
    $text_len = strlen($config['text']);
    $image_padding = isset($config['image_padding']) ? $config['image_padding'] : 10;
    $each_width = (int)(($config['width'] - $image_padding) / $text_len);
    
    for ($i = 0; $i < $text_len; $i++) {

        $text_color = random_color($image, 0, 200);

        $text = $config["text"][$i];

        $text_x = mt_rand($each_width * $i + $image_padding / 2, $each_width * ($i + 1));
        
        $text_y = mt_rand(0, (int)($config['height'] / 2));

        $font = 5;

        imagestring($image, $font, $text_x, $text_y, $text, $text_color);
    }

     // 填充边框
    imagerectangle($image, 0, 0, $config['width'] - 1, $config['height'] - 1, $border_color); 

    // 生成PNG图片
    imagepng($image);
    // 销毁图片
    imagedestroy($image);
}

/**
 * 生成随机颜色
 * @param  [type] $image 图像资源句柄
 * @param  [type] $begin 可选
 * @param  [type] $end   可选
 * @return [type]        随即颜色
 */
function random_color($image, $begin, $end) {
    if (!isset($begin) || !isset($end) || $begin > 255 || $begin < 0 || $end > 255 || $end < 0 || $begin > $end) {
        return imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    } else {
        return imagecolorallocate($image, mt_rand($begin, $end), mt_rand($begin, $end), mt_rand($begin, $end));
    }       
}

/**
 * [random_code description]
 * @param  [type] $text_count [description]
 * @return [type]             [description]
 */
function random_code($text_count) {
    $result = NULL;
    for ($i = 0; $i < $text_count; $i++) {
        $result .= dechex(mt_rand(0, 15));
    }
    return strtoupper($result);
}
?>