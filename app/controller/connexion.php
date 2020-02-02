<?php
session_start();
require ('../model/class_connexion.php');

if  ($_POST['connect'] === "OK")
{
    $CONNECT = new User_connexion();
    $error = $CONNECT->Check_connexion_data($_POST);
    if (($CONNECT->Try_to_connect($_POST['mail'], $_POST['passwd']) == 0) && empty($error))
    {
        $error['wrong_id'] = "Votre adresse mail ou votre mot de passe est incorrecte.";
    }
    if (!empty($error))
    {
        $_SESSION['error'] = $error;
        $_SESSION['input'] = $_POST;
        header('Location: ../../index.php?view=sign_in');
    }
    else
    {
        $_SESSION['token_connect'] = $CONNECT->Create_token_connect();
        $CONNECT->Insert_token_connect($_POST, $_SESSION['token_connect']);
        $data = $CONNECT->Get_user_id($_SESSION['token_connect']);
        $_SESSION['id'] = $data[0]['id'];
        header("Location: ../../index.php?view=profile");
    }
}