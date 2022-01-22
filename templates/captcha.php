<?php
    session_start();

    // Create captcha image
    $digit1 = rand(1, 30);
    $digit2 = rand(1, 30);
    $_SESSION['randNumber'] = $digit1 + $digit2;

    $image = imagecreatetruecolor(200, 40);
    $textColor = imagecolorallocate($image, 255, 0, 0);
    $bgColor = imagecolorallocate($image, 0, 0, 0);
    
    imagettftext($image, 30, 0, 10, 35, $textColor, "./font.ttf", "$digit1 + $digit2");
    imagecolortransparent($image, $bgColor);
    imagepng($image);
