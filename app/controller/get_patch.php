<?php
session_start();

$tab = glob('../../public/patch/*');
$final = [];
foreach($tab as $key => $value)
{
    $result = explode('/', $value);
    $result = array_reverse($result);
    array_push($final, $result[0]);
}
$final = json_encode($final);
header('Content-type: application/json');
echo ($final);