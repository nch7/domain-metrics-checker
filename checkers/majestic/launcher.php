<?php

include __DIR__.'/../../start.php';

use Illuminate\Database\Capsule\Manager as DB;

$domains = Domain::whereRaw('`tf` is null AND `status` = "active"')->limit(200)->get(['id', 'name'])->toArray();

if(($domains) == 0) {
	die('No domains!');
}

$domainIds = array_pluck($domains, 'id');

$chunks = array_chunk($domains, 50);
$sleepingTime = 55 / count($chunks);
$chunksAmount = count($chunks);
$domainsAmount = count($domains);

echo PHP_EOL.PHP_EOL.PHP_EOL."Selected {$domainsAmount} domains".PHP_EOL;
echo "Splitted into {$chunksAmount} chunks".PHP_EOL;
echo "Threads will be launched with {$sleepingTime} second delay".PHP_EOL.PHP_EOL;


echo 'Updated selected domains statuses to "busy"'.PHP_EOL.PHP_EOL.PHP_EOL;
Domain::whereIn('id', $domainIds)->update(['status'=>'busy']);

$threadN = 0;

foreach ($chunks as $chunk) {
	$threadN++;
	echo json_encode($chunk).PHP_EOL;
	echo "Launching thread #{$threadN}".PHP_EOL;
	exec(sprintf("php ".__DIR__."/worker.php '%s' > /dev/null &", json_encode($chunk)));
	sleep($sleepingTime);
}

echo PHP_EOL.PHP_EOL.'Finish'.PHP_EOL;