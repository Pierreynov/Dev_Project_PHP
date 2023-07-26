<?php 

include_once 'database.php';
$uuid = (isset($_COOKIE["creds"])) ? $_COOKIE["creds"] : -1;
$current_user = get_user_by_uuid($uuid);
if (!$current_user) {
    header("Location: /404");
    die();
}

$article_id = $_GET["id"];
supprimer_article_panier($current_user['user_id'],$article_id);
header("Location: /panier");
die();

?>