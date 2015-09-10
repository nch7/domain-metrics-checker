<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
	'driver' 	=> 'mysql',
	'host'		=> getenv('DB_HOST'),
	'username'	=> getenv('DB_USER'),
	'password'	=> getenv('DB_PASS'),
	'database'	=> getenv('DB_NAME'),
	'charset'	=> 'utf8',
	'collation'	=> 'utf8_unicode_ci',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

require __DIR__.'/helpers.php';
