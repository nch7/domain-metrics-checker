<?php

require 'start.php';

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->dropIfExists('domains');

Capsule::schema()->create('domains', function ($table) {
	$table->increments('id');
	$table->string('name')->unique();
	$table->integer('tf')->nullable();
	$table->integer('cf')->nullable();
	$table->integer('rd')->nullable();
	$table->string('topical')->nullable();
	$table->enum('status',['active', 'busy', 'finished']);
	$table->timestamp('created_at');
	$table->timestamp('updated_at');
});