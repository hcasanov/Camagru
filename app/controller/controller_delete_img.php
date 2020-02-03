<?php
session_start();
require('../model/class_images.php');
require('../model/class_connexion.php');

$connect = new User_connexion();
if ($connect->Permission_connect())
{
    $DELETE = new Image();
    $i =  $DELETE->delete_img_db($_POST['src']);
    if ($i)
        echo 1;
    else
        echo 0;
}
else
{
    header('../../index.php');
}