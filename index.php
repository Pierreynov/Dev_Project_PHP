<?php

include_once 'database.php';

$uuid = (isset($_COOKIE["creds"])) ? $_COOKIE["creds"] : -1;
$current_user = get_user_by_uuid($uuid);

$parameters = [];
parse_str($_SERVER["QUERY_STRING"], $parameters);

$method = $_SERVER["REQUEST_METHOD"];

switch (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) {
    case '/':
    case '/index':
    case '/home' :
        include "./static/home.html";
        break;
    case '/login':
        if ($current_user) {
            header("Location: /home");
            die();
        }
        if ($method == 'GET') {
            include "./static/login.html";
        } elseif ($method == 'POST') {
            include "./post/login.php";
        }
        break;
    case '/register':
        if ($method === 'GET') {
            include "./static/register.html";
            break;
        } elseif ($method === 'POST') {
            include "./post/register.php";
        }
        break;
    case '/logout':
        if ($current_user) {
            unset($_COOKIE["creds"]);  
            setcookie("creds", "", time()-1, "/"); 
        }
        supprimer_panier_par_user_id($current_user['user_id']);
        header("Location: /home");
        break;
    case '/profil':
        if (!$current_user) {
            header("Location: /login");
            die();
        }else if ($method != "GET") {
            include "./static/404.html";
            die();
        }
        header("Location: /home");
        break;
    case '/boutique' :
        
        if (!array_key_exists('filtre', $_POST)){
            $articles = get_all_articles();
            if (!$current_user){
                $cart = [];
            }else{
                $cart = get_panier_by_user_id($current_user["user_id"]);
            }
            
            include "./static/boutique.html";
            break;
        }
        if ($method == "POST" && $_POST["filtre"]=='croissant'){
            $cart = get_panier_by_user_id($current_user["user_id"]);
            $articles = get_all_articles_by_price();
            include "./static/boutique.html";
            break;
        }elseif ($method == "POST" && $_POST["filtre"]=='decroissant'){
            $cart = get_panier_by_user_id($current_user["user_id"]);
            $articles = get_all_articles_by_price_dec();
            include "./static/boutique.html";
            break;
        }
        $cart = get_panier_by_user_id($current_user["user_id"]);
        $articles = get_all_articles();
            include "./static/boutique.html";
            break;
        
    case '/admin' :
        if (!$current_user || $method != "GET" || $current_user["admin"] != "1") {
            include "./static/404.html";
            die();
        }
        include "./admin.php";
        break;
    case '/delete_article':
        if (!$current_user || $method != "GET" || $current_user["admin"] != "1") {
            include "./static/404.html";
            die();
        }
        
        include "./delete_article.php";
        die();
        break;
    case '/create_article' :
        if (!$current_user || $method != "POST" || $current_user["admin"] != "1") {
            include "./static/404.html";
            die();
        }
        include "create_article.php";
        die();
        break;
    case '/ajouter_panier' :
        if ($method != "POST") {
            include "./static/404.html";
            die();
        }

        if (!$_POST || !isset($_POST["id"])) {
            header("Location: /boutique");
            die();
        }

        include "./ajouter_panier.php";
        break;
    
    case '/panier':
        if(!$current_user){
            header("Location: /login");
            die();
        }
        $price = get_panier_price_by_id($current_user["user_id"]);
        $articles = get_panier_by_user_id($current_user["user_id"]);
        include "./static/panier.html";
        break;
    case '/supprimer_article_panier':
        if (!$current_user) {
            header("Location: /login");
            die();
        }

        if ($method != "POST") {
            include "./static/404.html";
            die();
        }

        if (!$_POST || !isset($_POST["id"])) {
            header("Location: /panier");
            die();
        }
        include "./supprimer_article_panier.php";
        break;

    case "/valider_panier":
        if (!$current_user) {
            header("Location: /login");
            die();
        }

        if ($method != 'POST') {
            include "./static/404.html";
            die();
        }
        include "valider_panier.php";
        break;
    case '/ajouter_fonds':
        if (!$current_user) {
            header("Location: /login");
            die();
        }

        if ($method != 'POST') {
            include "./static/404.html";
            die();
        }
        
        include "ajouter_fonds.php";
        break;
    default:
        include "./static/404.html";
        break;
}

?>