<?php

include_once 'database.php'; 
$name= $_POST['nom'];
$prix= $_POST['prix'];
$stock= $_POST['stock'];
$img= $_POST['img'];

create_article($name, $prix, $stock, $img);
header("Location: /admin");
die();
?>