<?php 

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Domain extends Eloquent {

	protected $fillable = [
		'name',
		'tf',
		'cf',
		'rd',
		'topical',
		'status',
	];

	public $timestamps = true;

	static public function insertSkipDupes($domains) {
		$query = sprintf('INSERT INTO domains (name) VALUES ("%s") ON DUPLICATE KEY UPDATE name=name', implode('"),("', $domains));

		$status = DB::statement($query);

		Domain::where('created_at', '0000-00-00 00:00:00')->update(['created_at' => DB::raw('NOW()')]);
		Domain::where('created_at', '0000-00-00 00:00:00')->update(['updated_at' => DB::raw('NOW()')]);

		return $status;
	}
}