<?php

ini_set('max_execution_time', 600); //10 mins

//composer autoload
require __DIR__ . '/vendor/autoload.php';

//load env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//init and run app
$wordpress_migrator = new WordpressMigrator();
$wordpress_migrator->run();