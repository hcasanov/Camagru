<?php
session_start();

$var = '../../public/patch/' . $_POST['patch'];
$_SESSION['patch'] = $var;