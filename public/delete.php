<?php

include __DIR__.'/../start.php';

use Illuminate\Database\Capsule\Manager as DB;

DB::table('domains')->delete();

echo 'Deleted all!';

?>