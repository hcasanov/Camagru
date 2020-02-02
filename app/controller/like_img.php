<?php
session_start();

try {
    $PDO = new PDO('mysql:host=db;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $PDO->exec(file_get_contents('../../config/struct.sql'));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if ($_SESSION['token_connect']) {
    $src = $_POST['src_like'];


    $REQUEST = $PDO->query("SELECT id FROM images WHERE src = '$src'");
    $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    $data = $data[0]['id'];

    $user = $_SESSION['id'];
    $REQUEST = $PDO->query("SELECT id_img FROM user_like WHERE id_user = '$user'");
    $e = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    foreach ($e as $key => $tab) {
        foreach ($tab as $key => $id) {
            if ($id == $data) {
                $SQL = $PDO->query("DELETE FROM user_like WHERE id_user = '$user' AND id_img = '$data'");
                $REQUEST->closeCursor();
                $like = 1;
                echo 1;
            }
        }
    }
    if ($like == 0) {
        $SQL = $PDO->prepare('INSERT INTO user_like(id_img, id_user, date_created) VALUES(:id_img, :id_user, :date)');
        $SQL->execute(array(
            'id_img' => $data,
            'id_user' => $_SESSION['id'],
            'date' => date("Y-m-d H:i:s")
        ));
        $REQUEST->closeCursor();
        echo 0;
    }
} else {
    echo "No connect";
}
