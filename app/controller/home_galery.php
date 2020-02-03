<?php
session_start();

try {
    $PDO = new PDO('mysql:host=172.23.0.1;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $REQUEST = $PDO->query('SELECT src FROM images ORDER BY date_created DESC');
    $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    $data = json_encode($data);
    $REQUEST->closeCursor();
    echo $data;
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
