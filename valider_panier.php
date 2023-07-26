<?php

include_once 'database.php';
$uuid = (isset($_COOKIE["creds"])) ? $_COOKIE["creds"] : -1;
$current_user = get_user_by_uuid($uuid);
if (!$current_user) {
    header("Location: /404");
    die();
}
$articles = get_panier_by_user_id($current_user["user_id"]);

$panier = get_panier_by_user_id($current_user["user_id"]);
if (!$panier) {
    header("Location: /panier");
    die();
}
$price = get_panier_price_by_id($current_user["user_id"]);

if ($price > $current_user['sold']){
    header("Location: /panier");
    die();
}

foreach($articles as $article){
    $quantite = get_quantite_panier($current_user['user_id'], $article['panier_article_id']);
    $art = get_article_by_id($article['panier_article_id']);    
    if (intval($art[0]['stock']) < intval($quantite['quantite'])){

        header("Location: /boutique");
        die();
    }
    modif_stock($article['panier_article_id'], intval($quantite['quantite']));
}
nouveau_sold($current_user['user_id'], $price);
supprimer_panier_par_user_id($current_user['user_id']);
header("Location: /panier");
die();

?>
