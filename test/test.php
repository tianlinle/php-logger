<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Tian\Logger\Logger;
error_reporting(E_ALL);
ini_set('display_errors', '1');
$logger = Logger::instance('C:/Users/what/Documents/logs', 'test');
$logger->info('hahaha');
$logger->error('this is an error');
$logger->importance('it is very importante');
$logger->writeAndClean();
echo 'done';