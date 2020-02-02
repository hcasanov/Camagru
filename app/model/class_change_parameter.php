<?php

class Change_param
{
    private $PDO;
    private $data = [];

    public function __construct()
    {
        try {
            $PDO = new PDO('mysql:host=db;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->PDO = $PDO;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function Check_data_send($data_send)
    {
        $this->data = $data_send;
        if (!array_key_exists('mail', $this->data) || $this->data['mail'] === "" || !filter_var($this->data['mail'], FILTER_VALIDATE_EMAIL)) {
            $error['mail'] = "Vous n'avez pas renseigné un email valide.";
        }
        if (!array_key_exists('name', $this->data) || $this->data['name'] === "") {
            $error['name'] = "Vous n'avez pas remplie votre nouveau nom.";
        }
        if ($this->data['passwd'] != "" && $this->data['confirm_passwd'] != "")
        {
            if (!array_key_exists('passwd', $this->data) || $this->data['passwd'] === "") {
                $error['passwd'] = "Vous n'avez pas remplie votre nouveau mot de passe.";
            }
            if (!array_key_exists('confirm_passwd', $this->data) || $this->data['confirm_passwd'] === "") {
                $error['confirm_passwd'] = "Vous n'avez pas confirmé votre nouveau mot de passe.";
            } else {
                if (strlen($this->data['passwd']) < 6) {
                    $error['passwd_lenght'] = "Votre nouveau mot de passe doit contenir 6 caractères minimum.";
                } else if (preg_match('#^(?=.*[0-9])#', $this->data['passwd']) == 0) {
                    $error['passwd_num'] = "Votre nouveau mot de passe doit contenir un chiffre minimum.";
                }
            }
        }
        if (($this->data['passwd'] != $this->data['confirm_passwd']) && (array_key_exists('confirm_passwd', $this->data) && array_key_exists('passwd', $this->data) )) {
            $error['passwd_diff'] = "Nouveau mot de passe erroné.";
        }
        if (Change_param::Check_mail_exist()) //--------------------------
        {
            $error['email_exist'] = "Cet email est déjà utilisé.";
        }
        if (Change_param::Check_name_exist())
        {
            $error['name_exist'] = "Ce nom est déjà utilisé";
        }
        // $var = Change_param::Check_name_exist();
        return ($error);
        // return ($var);
    }

    public function Check_mail_exist()
    {
        $REQUEST = $this->PDO->query('SELECT mail FROM users');
        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $tab) {
            foreach ($tab as $mail) {
                if ($this->data['mail'] === $mail) {
                    $mail = $this->data['mail'];
                    $REQUEST = $this->PDO->query("SELECT token_connect FROM users WHERE mail = '$mail' ");
                    if ($REQUEST->rowCount() != 0)
                    {
                        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($data as $token) {
                            foreach ($token as $token_connect) {
                                if ($token_connect != $_SESSION['token_connect']) {
                                    $REQUEST->closeCursor();
                                    return (true);
                                }
                            }
                        }
                    }
                }
            }
        }
        $REQUEST->closeCursor();
        return (false);
    }

    public function Check_name_exist()
    {
        $REQUEST = $this->PDO->query('SELECT name FROM users');
        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $tab) {
            foreach ($tab as $name) {
                if ($this->data['name'] === $name) {
                    $name = $this->data['name'];
                    $REQUEST = $this->PDO->query("SELECT token_connect FROM users WHERE name = '$name' ");
                    if ($REQUEST->rowCount() != 0)
                    {
                        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
                        if ($data[0]['token_connect'] != $_SESSION['token_connect'])
                        {
                            $REQUEST->closeCursor();
                            return (true);
                        }
                    }
                }
            }
        }
        $REQUEST->closeCursor();
        return (false);
    }

    public function Insert_new_param()
    {
        $name = $this->data['name'];
        $mail  =$this->data['mail'];
        if (isset($this->data['passwd']) && $this->data['passwd'] != "")
        {
            $passwd = hash('whirlpool', $this->data['passwd']);
        }
        $token_connect = $_SESSION['token_connect'];

        if (isset($this->data['passwd']) && $this->data['passwd'] != "")
        {
            $e = $this->PDO->query("UPDATE users SET mail = '$mail', name = '$name', passwd = '$passwd' WHERE token_connect = '$token_connect'");
        }
        else
        {
            $e = $this->PDO->query("UPDATE users SET mail = '$mail', name = '$name' WHERE token_connect = '$token_connect'");
        }
        $e->closeCursor();
    }

    public function mail_com($data)
    {
        $token_connect = $_SESSION['token_connect'];
        if ($data == 0)
        {
            $sql = $this->PDO->query("UPDATE users SET mail_com = 0 WHERE token_connect = '$token_connect'");
        }
        else
        {
            $sql = $this->PDO->query("UPDATE users SET mail_com = 1 WHERE token_connect = '$token_connect'");
        }
        $sql->closeCursor();
    }
}
