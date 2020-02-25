<?php

//composer autoload
require __DIR__ . '/vendor/autoload.php';

//load env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//update php execution time
ini_set('max_execution_time', getenv('PHP_SCRIPT_EXECUTION_TIME')); //10 mins

//init and run app
$wordpress_migrator = new Migrator(getenv('IMPORTER'));
$wordpress_migrator->run();