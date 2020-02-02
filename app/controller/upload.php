<?php
session_start();
require('../model/class_images.php');

if ($_SESSION['patch'] != NULL)
{
    if (!file_exists('../../upload/' . $_SESSION['id'])) {
        $dossier = '../../upload/' . $_SESSION['id'];
        mkdir($dossier);
    }
    $dossier = '../../upload/' . $_SESSION['id'] . "/";
    $fichier = basename($_FILES['img_upload']['name']);
    $taille_maxi = 2000000;
    $taille = filesize($_FILES['img_upload']['tmp_name']);
    $extensions = array('.jpg', '.jpeg');
    $extension = strrchr($_FILES['img_upload']['name'], '.');
    //Début des vérifications de sécurité...
    if (!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
        $error['extension'] = 'Vous devez uploader un fichier de type jpg, jpeg.';
    }
    if ($taille > $taille_maxi) {
        $error['size'] = 'Le fichier est trop gros...';
    }
    if (!isset($error)) //S'il n'y a pas d'erreur, on upload
    {
        //  On formate le nom du fichier ici...
        $fichier = strtr(
            $fichier,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
        );
        $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
        if (move_uploaded_file($_FILES['img_upload']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        {
            header("Content-type:image/jpeg");
    
            $im = imagecreatefromjpeg($dossier . $fichier);
            $patch = imagecreatefromjpeg($_SESSION['patch']);
    
            imagecopymerge($im, $patch, 20, 20, 0, 0, imagesx($patch), imagesy($patch), 50);
            if (!file_exists('../../galery/' . $_SESSION['id'] . "_galery"))
                mkdir('../../galery/' . $_SESSION['id'] . '_galery');
    
            $token = openssl_random_pseudo_bytes(16);
            $token = bin2hex($token);
    
            $file = '../../galery/' . $_SESSION['id'] . '_galery/' . $token . '.jpeg';
            imagejpeg($im, $file);//Chemin vers la nouvelle img montée
            imagedestroy($img);

            $INSERT = new Image();
            $name = $token . 'jpeg';
            $INSERT->insert_img_db($file, $name);
            
            unlink($dossier . $fichier);
            $_SESSION['token_last_img'] = $token;
    
            $_SESSION['patch'] = NULL;
            header('Location: ../../index.php?view=profile');
    
        } else
        {
            $error = "Fail, téléchargement interrompu";
            $_SESSION['error'] = $error;
            header('Location: ../../index.php?view=profile');
        }
    } else {
        $_SESSION['error'] = $error;
        header('Location: ../../index.php?view=profile');
    }
}
else
{
    $error['patch'] = "Veuillez selectionner un patch à coller sur votre image";
    $_SESSION['error'] = $error;
    header('Location: ../../index.php?view=profile');
}
