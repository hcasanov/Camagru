<?php
session_start();

if ($_POST['control'] == "add_comment")
{
    try {
        $PDO = new PDO('mysql:host=172.23.0.1;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $PDO->exec(file_get_contents('../../config/struct.sql'));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    
    $img = $_POST['img'];
    $REQUEST = $PDO->query("SELECT content FROM comment WHERE src_img = '$img' ORDER BY date_created DESC");
    $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    $data = json_encode($data);
    echo $data;
}
if ($_POST['new_com'] == "true" && isset($_SESSION['token_connect']))
{
    try {
        $PDO = new PDO('mysql:host=172.23.0.1;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $PDO->exec(file_get_contents('../../config/struct.sql'));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    
    $img = $_POST['img'];
    $REQUEST = $PDO->query("SELECT id FROM images WHERE src = '$img'");
    $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
    $data_id = $data[0]['id'];
    $REQUEST = $PDO->prepare("INSERT INTO comment(src_img, id_user, content, date_created, id_img) VALUES(:img, :user, :content, :date, :id_img)");
    $REQUEST->execute(array(
        'img' => $_POST['img'],
        'user' => $_SESSION['id'],
        'content' => $_POST['com'],
        'date' => date("Y-m-d H:i:s"),
        'id_img' => $data_id
    ));
    $REQUEST->closeCursor();

    $token = $_SESSION['token_connect'];
    $SQL = $PDO->query("SELECT mail_com FROM users WHERE token_connect = '$token'");
    $data = $SQL->fetchAll(PDO::FETCH_ASSOC);
    $data = $data[0]['mail_com'];

    if ($data == 1)
    {
        $SQL = $PDO-> query("SELECT mail FROM users INNER JOIN images ON users.id = images.id_user WHERE images.id = '$data_id'");
        $data = $SQL->fetchAll(PDO::FETCH_ASSOC);
        $SQL->closeCursor();
        $mail = $data[0]['mail'];
        mail($mail, 'Nouveau commentaire', 'Hello, tu as un nouveau commentaire sur camagru !');
        echo 'success';
    }
}
else
{
    die();
}