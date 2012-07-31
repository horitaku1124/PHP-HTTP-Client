<?php
require_once "../src/HTTPClient.php";

$http = new HTTPClient();
$str = $http->head("http://github.com/");
var_dump($str);
$str = $http->head("https://github.com/");
var_dump($str);