<?php

try {
    $PDO = new PDO('mysql:host=db;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
$REQUEST = $PDO->query('SELECT register_token FROM users');
$data_db = $REQUEST->fetchAll(PDO::FETCH_ASSOC);

foreach($data_db as $key => $token)
{
    if ($token['register_token'] == $_GET['register_token'])
    {
        $token = $_GET['register_token'];
        $sql = $PDO->query("UPDATE users SET account_confirm = 1 WHERE register_token = '$token'");
        $sql->closeCursor();
        header('Location:../../index.php?view=account_confirm');
    }
}
$sql->closeCursor();
header('Location:../../index.php?view=account_confirm_fail');