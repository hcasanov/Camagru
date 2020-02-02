<?php
session_start();

try {
    $PDO = new PDO('mysql:host=db;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (isset($_SESSION['token_connect'])) {
    $src = $_POST['src_like'];
    $REQUEST = $PDO->query("SELECT id FROM images WHERE src = '$src'");
    $PDO->exec(file_get_contents('../../config/struct.sql'));
    $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    $data = $data[0]['id']; //id de l'image

    $user = $_SESSION['id'];
    $REQUEST = $PDO->query("SELECT id_img FROM user_like WHERE id_user = '$user'");
    $e = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    foreach ($e as $key => $tab) {
        foreach ($tab as $key => $id) {
            if ($id == $data) {
                echo true;
            } else
                echo false;
        }
    }
}
else
{
    echo "FAIL";
}
