<?php
session_start();
require('../model/class_images.php');

if ($_SESSION['patch'] != NULL) {
    
    if (!file_exists('../../galery/' . $_SESSION['id'] . "_galery"))
        mkdir('../../galery/' . $_SESSION['id'] . '_galery');

    $token = openssl_random_pseudo_bytes(16);
    $token = bin2hex($token);

    $data = explode(',', $_POST['submit']);
    $data = $data[1];

    $data = str_replace(' ', '+', $data);
    $data = base64_decode($data);

    $im = imagecreatefromstring($data);
    $patch = imagecreatefromjpeg($_SESSION['patch']);

    imagecopymerge($im, $patch, 20, 20, 0, 0, imagesx($patch), imagesy($patch), 50);

    $token = openssl_random_pseudo_bytes(16);
    $token = bin2hex($token);

    $file = './galery/' . $_SESSION['id'] . '_galery/' . $token . '.jpeg';
    imagejpeg($im, $file); //Chemin vers la nouvelle img montée
    imagedestroy($im);

    $INSERT = new Image();
    $name = $token . 'jpeg';
    $INSERT->insert_img_db($file, $name);

    $_SESSION['token_last_img'] = $token;

    $_SESSION['patch'] = NULL;
} else {
    $error['patch'] = "Veuillez selectionner un patch à coller sur votre image";
    $_SESSION['error'] = $error;
}
