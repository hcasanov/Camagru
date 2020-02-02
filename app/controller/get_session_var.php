<?php
session_start();

try {
    $PDO = new PDO('mysql:host=db;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $PDO->exec(file_get_contents('../../config/struct.sql'));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
$id = $_SESSION['id'];
$data = $PDO->query("SELECT name, mail FROM users WHERE id = '$id'");
$data = $data->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['name'] = $data[0]['name'];
$_SESSION['mail'] = $data[0]['mail'];
echo json_encode($_SESSION);