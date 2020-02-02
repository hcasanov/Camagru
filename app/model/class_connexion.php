<?php

class User_connexion{

    private $data = [];
    private $PDO;

    public function __construct()
    {
        $this->data = $_POST;
        try {
            $PDO = new PDO('mysql:host=db;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->PDO = $PDO;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function Check_connexion_data($data)
    {
        if (!array_key_exists('mail', $data) || $data['mail'] === "" || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
        {
            $error['mail'] = "Vous n'avez pas renseigné un email valide.";
        }
        if (!array_key_exists('passwd', $data) || $data['passwd'] === "")
        {
            $error['passwd'] = "Vous n'avez pas remplie votre mot de passe.";
        }
        return ($error);
    }

    public function Try_to_connect($mail, $passwd)
    {
        $passwd_hash = hash('whirlpool', $passwd);

        $REQUEST = $this->PDO->query("SELECT * FROM users WHERE mail = '$mail' AND passwd = '$passwd_hash'");
        if ($REQUEST->rowCount() == 1)
        {
            $REQUEST->closeCursor();
            $this->PDO->exec(file_get_contents('../../config/struct.sql'));
            return (1);
        }
        else
        {
            $REQUEST->closeCursor();
            return (0);
        }
    }

    public function Create_token_connect()
    {
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);
        return ($token);
    }

    public function Insert_token_connect($data, $token)
    {
        $mail = $data['mail'];
        $this->PDO->query("UPDATE users SET token_connect = '$token' WHERE mail = '$mail'");
    }

    public function Check_mail_exist($mail)
    {
        $REQUEST = $this->PDO->query('SELECT mail FROM users');
        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $tab) 
        {
            foreach ($tab as $mail_db) 
            {
                if ($mail === $mail_db) 
                {
                    $REQUEST->closeCursor();
                    return (true);
                }
            }
        }
        $REQUEST->closeCursor();
        return (false);
    }

    public function Get_user_id($token)
    {
        $REQUEST = $this->PDO->query("SELECT id FROM users WHERE token_connect = '$token'");
        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
        return ($data);
    }

    public function Get_user_token($id)
    {
        $REQUEST = $this->PDO->query("SELECT token_connect FROM users WHERE id = '$id'");
        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
        return ($data);
    }

    public function Permission_connect()
    {
        if ($_SESSION['token_connect'] && $_SESSION['id'])
        {
            $token = User_connexion::Get_user_token($_SESSION['id']);
            if ($token[0]['token_connect'] === $_SESSION['token_connect'])
            {
                return (1);
            }
            else
                return (0);
        }
    }
}