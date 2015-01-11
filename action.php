<?php

session_start();
$file = file_get_contents('config.json');
$json = json_decode($file);
foreach ($_POST['variable'] as $variable => $value) {
  $json->$variable = $value;
}
file_put_contents("config.json", json_encode($json));
$_SESSION['message'] = 'Updated!';
header('Location: /');
