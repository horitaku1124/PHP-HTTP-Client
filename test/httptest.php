<?php
require_once "../src/HTTPClient.php";

$http = new HTTPClient();
$str = $http->get("http://www.yahoo.co.jp/", "124.83.235.204");
print_r($str);