<?php
session_start();
require('./app/model/class_connexion.php');

if (isset($_SESSION['token_connect']))
{
    echo $_SESSION['token_connect'];
    // $user = new User_connexion();
    // $token = $user->Get_user_token($_SESSION['id']);
    // echo $token[0]['token_connect'];
}
else
{
    header('Location: ./index.php');
}
?>