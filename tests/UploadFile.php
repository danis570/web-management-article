<?php

$img = 'default.pngg';

$validImgExtension = ['png', 'webp', 'jpg', 'jpeg'];

$imgExtension = explode('.', $img);
$imgExtension = strtolower(end($imgExtension));

if (!in_array($imgExtension, $validImgExtension)){
    echo 'Img format must png, webp, jpg, jpeg';
} else {
    echo 'success upload file';
}