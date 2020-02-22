<?php

ini_set('max_execution_time', 600); //10 mins
// debug vars
// ini_set("xdebug.var_display_max_children", -1);
// ini_set("xdebug.var_display_max_data", -1);
// ini_set("xdebug.var_display_max_depth", -1);

//composer autoload
require __DIR__ . '/vendor/autoload.php';

//load env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//init and run app
$wordpress_migrator = new Migrator(getenv('IMPORTER'));
$wordpress_migrator->run();