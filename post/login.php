<?php 
include_once 'database.php'; 

$username = $_POST["identifiant"];
$password = $_POST["password"];


if (empty($username) ||  empty($password)) {
    $error = "Mets tout en fait";
    include "./static/login.html";
    die();
}


if (!check_credentials($username, $password))
{
    $error = "Pseudo ou Mot de passe erroné";
    include "./static/login.html";
    die();
}

$uuid = uniqid();
set_user_uuid($uuid, $username);
setcookie("creds", $uuid, time() + (86400), "/");
$success = "Success";
header("Location: /home");
die();
?>