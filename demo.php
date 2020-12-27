<?php
require_once 'vendor/autoload.php';

$router = new if0xx\HuaweiHilinkApi\Router;
$router->setAddress('localhost');
$router->login('admin', 'admin');
$router->sendSms("89179014495", "message");




