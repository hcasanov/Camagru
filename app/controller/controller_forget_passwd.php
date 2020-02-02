<?php
session_start();
require('../model/class_connexion.php');

if ($_POST['forget_passwd'] == "OK")
{
    if (empty($_POST['mail']) || $_POST['mail'] == "")
    {
        $error['empty_mail'] = "Veuillez rentrer une adresse mail.";
        $_SESSION['error'] = $error;
        header("Location: ../../index.php?view=forget_passwd");
    }
    else
    {
        $CHECK = new User_connexion();
        if ($CHECK->Check_mail_exist($_POST['mail']))
        {
            // mail();
            $_SESSION['success'] = 1;
            header("Location: ../../index.php?view=forget_passwd");
        }
        else
        {
            $error['mail_invalid'] = "L'adresse mail n'est pas valdie.";
            $_SESSION['error'] = $error;
            header("Location: ../../index.php?view=forget_passwd");
        }
    }
}