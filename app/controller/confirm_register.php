<?php

try {
    $PDO = new PDO('mysql:host=172.22.0.1;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
$token = $_GET['register_token'];
$REQUEST = $PDO->query("SELECT register_token FROM users WHERE register_token = '$token'");
$data_db = $REQUEST->fetchAll(PDO::FETCH_ASSOC);


if (count($data_db)){
    $REQUEST = $PDO->query("UPDATE users SET account_confirm = 1 WHERE register_token = '$token'");
    header('Location:../../index.php?view=account_confirm');
}
else {
    $REQUEST->closeCursor();
    header('Location:../../index.php?view=account_confirm_fail');
}