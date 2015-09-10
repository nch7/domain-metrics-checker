<?php

include __DIR__.'/../start.php';

$domains = [];

if(!empty($_POST['domains'])) {
	$domains = array_merge($domains, splitLines($_FILES['domains']));
}

if(!empty($_FILES['domainsFile'])) {
	$domains = array_merge($domains, splitLines(file_get_contents($_FILES["domainsFile"]["tmp_name"])));
}

if(count($domains) == 0) {
	die('No domains entered!');
}

try {
	Domain::insertSkipDupes($domains);
	echo 'Imported!';
} catch (Exception $e) {
	die("<b>MySQL Error:</b> <i>{$e->getMessage()}</i>");
}
