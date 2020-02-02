<?php
session_start();

require('../model/class_create_account.php');
$error = [];

if ($_POST['submit'] === "OK") {
    $NEW = new Create_account();
    $error = $NEW->Check_account_data($_POST);
    if (!empty($error)) {
        $_SESSION['error'] = $error;
        $_SESSION['input'] = $_POST;
        header('Location: ../../index.php?view=sign_up');
    } else {
        $_SESSION['success'] = '1';
        $NEW->Add_new_user();
        $NEW->Mail_confirm();
        header('Location: ../../index.php?view=sign_up');
    }
}
