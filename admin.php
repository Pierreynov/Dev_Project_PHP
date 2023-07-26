<?php

include_once 'database.php'; 
$articles = get_all_articles();
$uuid = (isset($_COOKIE["creds"])) ? $_COOKIE["creds"] : -1;
$current_user = get_user_by_uuid($uuid);
include "./static/admin.html";
die();
?>