<?php

include __DIR__.'/../../start.php';

use Illuminate\Database\Capsule\Manager as DB;

$config = include(__DIR__.'/../../config/bots.php');

$bot = new Bot($config['majestic']['cookie']);

if(!isset($argv[1]) OR (!$domainList = json_decode($argv[1]))) {
	die('Invalid argument!'.PHP_EOL);
}

$domains = [];

foreach ($domainList as $key => $domain) {
	$domains[$domain->name] = $domain->id;
	$domainList[$key] = $domain->name;
}

$results = $bot->check($domainList);

foreach ($results as $name => $metrics) {
	if(isset($domains[$name]) && isset($metrics['tf']) && isset($metrics['cf']) && $metrics['tf']>=0 && $metrics['cf']>=0){
		if($metrics['niche']==''){
			$metrics['niche'] = 'N/A';
		}

		Domain::where('id', $domains[$name])->update([
			'tf' 		=> $metrics['tf'],
			'cf' 		=> $metrics['cf'],
			'rd' 		=> $metrics['rd'],
			'tf' 		=> $metrics['tf'],
			'topical'	=> $metrics['niche'],
			'status'	=> 'completed'	
		]);
	}
}

echo PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.'Finish'.PHP_EOL;