<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Tian\Logger\Monitor;

Monitor::grep('C:/Users/what/Documents/logs', 'test', $argv[1]);