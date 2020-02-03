<?php
session_start();
require('app/model/class_connexion.php');
$permission = new User_connexion();

if ($permission->Permission_connect()) {
    require('app/view/nav_bar_profile.php');
    if ($_GET['view'] == 'profile') {
        require('app/view/profile.php');
    }
    else if ($_GET['view'] == 'parameter') {
        require('app/view/parameter.php');
    } else {
        require('app/view/home.php');
    }
} else {
    require('app/view/nav_bar_home.php');
    if ($_GET['view'] == 'sign_up') {
        require('app/view/sign_up.php');
    } else if ($_GET['view'] == 'sign_in') {
        require('app/view/sign_in.php');
    } else if ($_GET['view'] == 'forget_passwd') {
        require('app/view/forget_passwd.php');
    }
    else if ($_GET['view'] == 'account_confirm')
    {
        require('app/view/account_confirm.php');
    }
    else if ($_GET['view'] == 'account_confirm_fail')
    {
        require('app/view/account_confirm_fail.php');
    }
    else {
        require('app/view/home.php');
    }
}