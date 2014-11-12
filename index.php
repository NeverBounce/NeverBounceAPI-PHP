<?php
require('vendor/autoload.php');
$secretKey = "JUyLm8pTNw0n35ZW6HxzDQruSjhKcekYC2o1bs4MVqOgPFAfd7tGvE9RiaXIlB";
$appID = "bP7BaRDaVme71ke1ADtz0hxw";

$app = new \NeverBounce\API\Single($secretKey, $appID);
$app->verify("support@neverbounce.com");
var_dump($app->response->result);