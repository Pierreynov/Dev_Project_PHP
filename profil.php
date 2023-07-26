<?php
include_once 'database.php';
$uuid = (isset($_COOKIE["creds"])) ? $_COOKIE["creds"] : -1;
$current_user = get_user_by_uuid($uuid);
$method = $_SERVER["REQUEST_METHOD"];
// var_dump($current_user);
// die();
if (!$current_user) {
    header("Location: /login");
    die();
}else if ($method != "GET") {
    include "./static/404.html";
    die();
}
include "./static/profil.html";
die();
?>