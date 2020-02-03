<?php
session_start();
require ('../model/class_change_parameter.php');

$NEW = new Change_param();
if ($_POST['data'] == 'no')
{
    $e = $NEW->mail_com(0);
    echo $e;
}
else if ($_POST['data'] == 1)
{
    $e = $NEW->mail_com(1);
    echo $e;
}
else
{
    if ($_POST['new_parameter'] == 'OK')
    {
        $error = $NEW->Check_data_send($_POST);
        if (!empty($error))
        {
            $_SESSION['error'] = $error;
            header('Location: ../../index.php?view=parameter');
        }
        else
        {
            $NEW->Insert_new_param();
            $_SESSION['success'] = 1;
            header('Location: ../../index.php?view=parameter');
        }
    }
    else
    {
        header('Location: ../../index.php?view=parameter');
    }
}