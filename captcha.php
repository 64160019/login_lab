<?php
session_start();

function generate_captcha() {
    $captcha_length = 6;
    $captcha_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $captcha = '';
    for($i = 0; $i < $captcha_length; $i++) {
        $captcha .= $captcha_chars[rand(0, strlen($captcha_chars)- 1)];
    }
    return $captcha;
}

function render_captcha_image($captcha) {
     $image_width = 150;
     $image_height = 50;
     $image = imagecreate($image_width, $image_height);
     $background_color = imagecolorallocate($image, 255, 255, 255);
     $text_color = imagecolorallocate($image, 0, 0, 0);
     imagestring($image, 5, 30, 20, $captcha, $text_color);

     header('Content-type: image/png');
     imagepng($image);
     imagedestroy($image);
}

$captcha = generate_captcha();
$_SESSION['captcha'] = $captcha;
render_captcha_image($captcha);
?>