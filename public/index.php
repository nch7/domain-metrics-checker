<?php
	include __DIR__.'/../start.php';

	use Illuminate\Database\Capsule\Manager as DB;

	$total 	= Domain::count();
	$page = isset($_GET['page']) == true ? $_GET['page'] : 1;
	$perPage = 100;
	$totalPages = floor($total / $perPage);

	$sortBy = isset($_GET['sortBy']) == true ? $_GET['sortBy'] : 'tf';
	$sortType = isset($_GET['sortType']) == true ? $_GET['sortType'] : 'desc';

	$domains = Domain::take($perPage)->skip($perPage * ($page - 1))->orderBy($sortBy, $sortType)->get();
	$newSortType = $sortType == 'desc' ? 'asc' : 'desc';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Domain Metrics Checker</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="assets/style.css">
	</head>
	<body>
		<?php include __DIR__.'/menu.php' ?>
		<div class='container'>
			<h2>Total <?=_e($total)?> domains found. Sorting by <?=_e($sortBy)?>, <?=_e($sortType)?> </h2>
			<table class='table table-stripped'>
				<thead>
					<th class='col-xs-5'><a href='<?="index.php?sortBy=name&sortType={$newSortType}"?>'>Domain</a></th>
					<th class='col-xs-1'><a href='<?="index.php?sortBy=tf&sortType={$newSortType}"?>'>TF</a></th>
					<th class='col-xs-1'><a href='<?="index.php?sortBy=cf&sortType={$newSortType}"?>'>CF</a></th>
					<th class='col-xs-1'><a href='<?="index.php?sortBy=rd&sortType={$newSortType}"?>'>RD</a></th>
					<th class='col-xs-3'><a href='<?="index.php?sortBy=topical&sortType={$newSortType}"?>'>Topical</a></th>
					<th class='col-xs-1'><a href='<?="index.php?sortBy=status&sortType={$newSortType}"?>'>Status</a></th>
				</thead>
				<tbody>	
					<?php foreach ($domains as $domain) { ?>
						<tr>
							<td><?=$domain->name?></td>
							<td><?=$domain->tf?></td>
							<td><?=$domain->cf?></td>
							<td><?=$domain->rd?></td>
							<td><?=$domain->topical?></td>
							<td><?=$domain->status?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class='text-center'>
				<?=paginate("index.php?sortBy={$sortBy}&sortType={$sortType}", $page, $totalPages)?>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>