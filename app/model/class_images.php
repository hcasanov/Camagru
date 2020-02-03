<?php
session_start();

class Image {

    private $PDO;

    public function __construct()
    {
        $this->data = $_POST;
        try {
            $PDO = new PDO('mysql:host=172.23.0.1;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $PDO->exec(file_get_contents('../../config/struct.sql'));//../../config/struct.sql
            $this->PDO = $PDO;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function insert_img_db($src, $name) {
        $INSERT = $this->PDO->prepare('INSERT INTO images(name, src, id_user, date_created) VALUES(:name, :src, :id, :date)');
        $INSERT->execute(array(
            'name' => $name,
            'src' => $src,
            'id' => $_SESSION['id'],
            'date' => date("Y-m-d H:i:s")
        ));
        $INSERT->closeCursor();
    }
    
    public function delete_img_db($src) {
        $img = $src;
        $sql = $this->PDO->query("SELECT id FROM images WHERE src = '$img'");
        $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
        $id = $sql[0]['id'];
        $sql = $this->PDO->query("DELETE FROM user_like WHERE id_img = '$id'");
        $sql = $this->PDO->query("DELETE FROM images WHERE src = '$img'");
        $sql = $this->PDO->query("DELETE FROM comment WHERE src_img = '$img'");
        $sql->closeCursor();
        unlink($img);
        return (1);
    }
}