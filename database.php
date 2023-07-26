<?php

    define('HOST', 'localhost');
    define('DB_NAME', 'sj macau');
    define('USER','root');
    define('PASS','');

    try{
        $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo $e;
    }

    function get_user_by_username(string $username) : ?array
    {
        global $db;
        $user = $db->prepare("SELECT * FROM user WHERE name =:username");
        $user->execute([
            'username' => $username
        ]);
        $result = $user->fetchAll();
        return ($result != null) ? $result[0] : null;
    }

    function get_user_by_email($email) : ?array
    {
        global $db;
        $user = $db->prepare("SELECT * FROM user WHERE email = :mail");
        $user->execute([
            'mail'=> $email
        ]);
        $result = $user->fetchAll();
        return ($result != null) ? $result[0] : null;
    }
    function new_register($username, $email, $password_hashed)
    {
        global $db;
        $query = $db->prepare("INSERT INTO User(name, password, email) VALUES(:username, :password, :email)");
        $query->execute([
            'username' => $username,
            'password' => $password_hashed,
            'email' => $email
        ]);
    }

    function check_credentials($username, $password) : bool
{
    global $db;
    $user=$db->prepare("SELECT password FROM user WHERE name = :username");
    $user->execute([
        'username' => $username,
    ]);
    $result = $user->fetchAll();
    if ($result == null) {
        return false;
    }
    if (password_verify($password, $result[0]["password"])) {
        return true;
    }
    return false;
}

function set_user_uuid(string $new_uuid, string $username)
{
    global $db;
    $query = $db->prepare("UPDATE user SET user_uuid = :new_uuid WHERE name = :username ");
    $query->execute([
        'new_uuid' => $new_uuid,
        'username' => $username
    ]);
}

function get_user_by_uuid(string $uuid) : ?array
{
    global $db;
    $user=$db->prepare("SELECT * FROM user WHERE user_uuid = :uuid");
    $user->execute([
        'uuid' => $uuid
    ]);
    $result = $user->fetchAll();
    return ($result != null) ? $result[0] : null;
}

function get_all_articles(){
    global $db;
    $user=$db->prepare("SELECT * FROM articles ORDER BY id");
    $user->execute([]);
    return $user->fetchAll();
}

function get_article_by_id($id){
    global $db;
    $user=$db->prepare("SELECT * FROM articles WHERE id = :id");
    $user->execute([
        'id' => $id,
    ]);
    return $user->fetchAll();
}

function get_all_articles_by_price(){
    global $db;
    $user=$db->prepare("SELECT * FROM articles ORDER BY price ");
    $user->execute([]);
    return $user->fetchAll();
}

function get_all_articles_by_price_dec(){
    global $db;
    $user=$db->prepare("SELECT * FROM articles ORDER BY price DESC");
    $user->execute([]);
    return $user->fetchAll();
}

function delete_article_by_id($id){
    global $db;
    $query = $db->prepare("DELETE FROM articles WHERE id = :id");
    $query->execute([
        'id' => $id,
    ]);
    delete_all_articles_in_cart($id);
}
    
function delete_all_articles_in_cart($article_id)
{
    global $db;
    $query = $db->prepare("DELETE FROM panier WHERE panier_article_id = :id");
    $query->execute([
        'id' => $article_id,
    ]);
}


function create_article($name, $price, $stock, $img){
    global $db;
    $query = $db->prepare("INSERT INTO articles(name,price, stock, img) VALUES (:nom, :prix, :stock, :img)");
    $query->execute([
        'nom' => $name,
        'prix' => $price,
        'stock' => $stock,
        'img' => $img,
    ]);
}

function article_present_panier(int $user_id, int $article_id){
    global $db;

    $query = $db->prepare("SELECT * FROM panier WHERE user_id = :user_id && panier_article_id = :article_id");
    $query->execute([
        'user_id' => $user_id,
        'article_id' => $article_id
    ]);
    if ($query->rowCount() > 0){
        return true;
    }
    return false;
}
function ajouter_quantite_article(int $user_id, int $article_id)
{
    global $db;

    $query = $db->prepare("UPDATE panier 
                            SET quantite = quantite +1 
                            WHERE user_id = :user_id 
                            AND panier_article_id = :article_id");

    $query->execute([
        'user_id' => $user_id,
        'article_id' => $article_id 
    ]);
}
function ajouter_article_panier(int $user_id, int $article_id, int $price)
{
    global $db;
    if (article_present_panier($user_id, $article_id)) {
        ajouter_quantite_article($user_id,$article_id);
    } else {
    $query = $db->prepare("INSERT INTO panier(user_id, panier_article_id, price) 
                            VALUES(:user_id, :article_id, :price)");
    $query->execute([
        'user_id' => $user_id,
        'article_id' => $article_id,
        'price' => $price
    ]);
    }
}

function supprimer_article_panier(int $user_id, int $article_id)
{
    global $db;
    $user=$db->prepare("SELECT quantite FROM panier WHERE (user_id, panier_article_id) = (:user_id, :article_id)");
    $user->execute([
        'user_id' => $user_id,
        'article_id' => $article_id
    ]);
    $result = $user->fetch();

    if (intval($result['quantite']) > 1 ){
        $query = $db->prepare("UPDATE panier 
                            SET quantite = quantite -1 
                            WHERE user_id = :user_id 
                            AND panier_article_id = :article_id");

    $query->execute([
        'user_id' => $user_id,
        'article_id' => $article_id 
    ]);
    }else {
    $query = $db->prepare("DELETE FROM panier WHERE (user_id, panier_article_id) = (:user_id, :article_id)");
    $query->execute([
        'user_id' => $user_id,
        'article_id' => $article_id
    ]);
    }
}

function get_panier_by_user_id(int $user_id): ?array
{ 
    global $db;
    $user=$db->prepare("SELECT * FROM panier WHERE user_id = :id ORDER BY panier_article_id");
    $user->execute([
        'id' => $user_id
    ]);
    $result = $user->fetchAll();
    return $result;
}

function get_article_price_by_id(int $id){
    global $db;
    $user=$db->prepare("SELECT price FROM articles WHERE id = :id");
    $user->execute([
        'id' => $id
    ]);
    $result = $user->fetch();
    return ($result != null) ? $result[0] : null;
}

function get_panier_price_by_id($user_id) {
    global $db;
    $user=$db->prepare("SELECT price, quantite FROM panier WHERE user_id = :id");
    $user->execute([
        'id' => $user_id
    ]);
    $result =$user->fetchAll();
    $price = 0;

    foreach ($result as $test){
        $price = $price + intval($test["price"])* intval($test["quantite"]);
    }

    return $price;
    
}
function nouveau_sold($id, $price){
    global $db;
    $user=$db->prepare("UPDATE user  SET sold = sold - $price WHERE user_id = :id");
    $user->execute([
        'id' => $id
    ]);
}

function supprimer_panier_par_user_id($user_id){
    global $db;
    $user=$db->prepare("DELETE  FROM panier WHERE user_id = :id");
    $user->execute([
        'id' => $user_id
    ]);
}

function ajouter_fonds($id, $sold){
    global $db;
    $user=$db->prepare("UPDATE user  SET sold = sold + $sold WHERE user_id = :id");
    $user->execute([
        'id' => $id
    ]);
}

function get_quantite_panier($user_id, $article_id){
    global $db;
    $user=$db->prepare("SELECT quantite FROM panier WHERE user_id = :id AND panier_article_id = :article_id");
    $user->execute([
        'id' => $user_id,
        'article_id' => $article_id
    ]);
    $result =$user->fetch();
    return $result;
}

function modif_stock($article_id, $quantite){
    global $db;
    $user=$db->prepare("UPDATE articles  SET stock = stock - $quantite WHERE id = :id");
    $user->execute([
        'id' => $article_id
    ]);
}
?>