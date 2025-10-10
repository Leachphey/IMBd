<?php
require "../app/core/init.php";

$url = $_GET['url'] ?? 'home';
$url = strtolower($url);
$url = explode('/', $url);

$page_name = !empty($url[0]) ? $url[0] : 'home';
$file_name = "../app/pages/" . $page_name . ".php";

$page = get_pagination_vars();

if (file_exists($file_name)) {
    require_once $file_name;
} else {
    require_once "../app/pages/404.php";
}
