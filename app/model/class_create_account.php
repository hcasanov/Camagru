<?php

class   Create_account
{

    private $data = [];
    private $PDO;

    public function __construct()
    {
        $this->data = $_POST;
        try {
            $PDO = new PDO('mysql:host=db;port=3308;dbname=camagru;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $PDO->exec(file_get_contents('../../config/struct.sql'));
            $this->PDO = $PDO;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function Check_account_data($data)
    {
        if (!array_key_exists('mail', $data) || $data['mail'] === "" || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
        {
            $error['mail'] = "Vous n'avez pas renseigné un email valide.";
        }
        if (!array_key_exists('name', $data) || $data['name'] === "")
        {
            $error['name'] = "Vous n'avez pas remplie votre nom.";
        }
        if (!array_key_exists('passwd', $data) || $data['passwd'] === "")
        {
            $error['passwd'] = "Vous n'avez pas remplie votre mot de passe.";
        }
        if (!array_key_exists('confirm_passwd', $data) || $data['confirm_passwd'] === "")
        {
            $error['confirm_passwd'] = "Vous n'avez pas confirmé votre mot de passe.";
        }
        else
        {
            if (strlen($data['passwd']) < 6)
            {
                $error['passwd_lenght'] = "Votre mot de passe doit contenir 6 caractères minimum.";
            }
            else if (preg_match('#^(?=.*[0-9])#', $data['passwd']) == 0)
            {
                $error['passwd_num'] = "Votre mot de passe doit contenir un chiffre minimum.";
            }
        }
        if ($data['passwd'] != $data['confirm_passwd'])
        {
            $error['passwd_diff'] = "Mot de passe erroné.";
        }
        if (Create_account::Check_email_exist())
        {
            $error['email_exist'] = "Cet email est déjà utilisé.";
        }
        if (Create_account::check_name_exist($data['name']))
        {
            $error['name_exist'] = "Ce nom est déjà utilisé !";
        }
        return ($error);
    }

    public function check_name_exist($name)
    {  
        $REQUEST = $this->PDO->query('SELECT name FROM users');
        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $tab)
        {
            if ($tab['name'] == $name)
                return true;
        }
        return false;
    }

    public function Check_email_exist()
    {
        $REQUEST = $this->PDO->query('SELECT mail FROM users');
        $data = $REQUEST->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $tab) {
            foreach ($tab as $mail) {
                if ($this->data['mail'] === $mail) {
                    return (true);
                }
            }
        }
        $REQUEST->closeCursor();
        return (false);
    }

    public function Add_new_user()
    {

        $REQUEST = $this->PDO->prepare('INSERT INTO users(mail, name, passwd, date_created, confirm_key, account_confirm) VALUES(:mail, :name, :passwd, :date, :key, :confirm)');
        $REQUEST->execute(array(
            'mail' => $this->data['mail'],
            'name' => $this->data['name'],
            'passwd' => hash('whirlpool', $this->data['passwd']),
            'date' => date("Y-m-d H:i:s"),
            'key' => Create_account::Key_gen(),
            'confirm' => 0
        ));
        $REQUEST->closeCursor();
    }

    public function Key_gen() 
    {
        $lenght_key = 12;
        $key = "";

        for($i = 1; $i < $lenght_key; $i++)//Generate confirm_key
        {
            $key .= mt_rand(0,9);
        }
        return ($key);
    }

    public function Mail_confirm()
    {
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);
        $mail = $this->data['mail'];
        $sql = $this->PDO->query("UPDATE users SET register_token = '$token' WHERE mail = '$mail'");
        $sql->closeCursor();
        $link_confirm = "http://localhost/app/controller/confirm_register.php?register_token=" . $token;
        $mail_template = file_get_contents('../../public/mail/confirme.html');

        mail($this->data['mail'], 'Confirmation inscription Camagru', $mail_template);
    }
}
