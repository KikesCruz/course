<?php

use App\Authentication;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Authentication::init();

Authentication::setup();

Authentication::runAction();
