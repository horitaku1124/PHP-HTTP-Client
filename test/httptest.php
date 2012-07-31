<?php
require_once "../src/HTTPClient.php";

$http = new HTTPClient();
$str = $http->head("http://github.com/");
print_r($str);
$str = $http->head("https://github.com/");
print_r($str);