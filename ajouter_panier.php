<?php 

include_once 'database.php';
$uuid = (isset($_COOKIE["creds"])) ? $_COOKIE["creds"] : -1;
$current_user = get_user_by_uuid($uuid);
if (!$current_user) {
    header("Location: /login");
    die();
}

$article_id = $_GET["id"];
$art = get_article_by_id($article_id);
if (intval($art[0]['stock']) <= 0){
    header("Location: /boutique");
    die();
}
$price = get_article_price_by_id($article_id);
ajouter_article_panier($current_user['user_id'],$article_id, $price);
header("Location: /boutique");
die();

?>