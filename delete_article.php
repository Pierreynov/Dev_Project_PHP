<?php

include_once 'database.php'; 



$id = $_GET["id"];
if (!$id) {
    include "./static/404.html";
    die();
}

delete_article_by_id($_GET["id"]);
header("Location: /admin");
die();
?>