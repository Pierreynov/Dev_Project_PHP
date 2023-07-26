<?php
include_once 'database.php'; 
global $db;

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$password_confirm = $_POST["password-confirm"];


if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
    $error = "Il manque quelque chose là";
    include "./static/register.html";
    die();
}

if (get_user_by_username($username)) {
    $error = "Ce pseudo est déjà utilisé.";
    include "./static/register.html";
    die();
}

if (get_user_by_email($email)) {
    $error = "L'adresse email fournise est déjà utilisée.";
    include "./static/register.html";
    die();
}

if ($password != $password_confirm) {
    $error = "Erreur mot de passe";
    include "./static/register.html";
    die();
}


$password_hashed = password_hash($password, PASSWORD_BCRYPT);
new_register($username, $email, $password_hashed);

$uuid = uniqid();
set_user_uuid($uuid, $username);
setcookie("creds", $uuid, time() + (86400), "/");
header("Location: /home");
die();
?>