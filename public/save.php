<?php

include __DIR__.'/../start.php';

if(empty($_POST['domains'])) {
	die('Domains not entered!');
}

$domains = splitLines($_POST['domains']);

try {
	Domain::insertSkipDupes($domains);
	echo 'Imported!';
} catch (Exception $e) {
	die("<b>MySQL Error:</b> <i>{$e->getMessage()}</i>");
}
