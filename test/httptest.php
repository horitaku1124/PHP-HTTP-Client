<?php
require_once "../src/HTTPClient.php";

$http = new HTTPClient();
$str = $http->head("http://windows.github.com/");
var_dump($str);