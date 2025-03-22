<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");


session_destroy();
header('location: ' . BASE_URL("/"));

?>