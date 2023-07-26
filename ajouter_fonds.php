<?php

include_once 'database.php';
$uuid = (isset($_COOKIE["creds"])) ? $_COOKIE["creds"] : -1;
$current_user = get_user_by_uuid($uuid);

$sold = intval($_GET["fonds"]);


ajouter_fonds($current_user['user_id'], $sold);
header("Location: /profil");
die();
?>