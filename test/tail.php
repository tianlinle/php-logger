<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Tian\Logger\Monitor;

Monitor::tail('C:/Users/what/Documents/logs', 'test');