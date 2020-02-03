<?php
session_start();

try {
    $id = $_SESSION['id'];
    $PDO = new PDO('mysql:host=172.23.0.1;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $REQUEST = $PDO->query("SELECT src FROM images WHERE id_user = '$id' ORDER BY date_created DESC");
    $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    foreach ($data as $key => $value) {
        foreach ($value as $key => $src) {
            $elem = explode('/', $src);
            $elem = array_reverse($elem);
            $elem = $elem[1] . '/' . $elem[0];
            array_push($list, $elem);
            $time = filemtime('../../galery/' . $_SESSION['id'] . '_galery/' . $elem[0]);
            array_push($list[$key], $time);
        }
    }
    $data = json_encode($data);
    $REQUEST->closeCursor();
    echo $data;
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
