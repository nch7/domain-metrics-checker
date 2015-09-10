<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config/database.php';
require __DIR__.'/helpers.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
	'driver' 	=> 'mysql',
	'host'		=> 'localhost',
	'username'	=> 'homestead',
	'password'	=> 'secret',
	'database'	=> 'DomainMetricsChecker',
	'charset'	=> 'utf8',
	'collation'	=> 'utf8_unicode_ci',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();