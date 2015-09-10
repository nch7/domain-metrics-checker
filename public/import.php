<?php
	include __DIR__.'/../start.php';
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
			<h2>Use this form to import domains</h2>
			<form method="post" action="save.php">
				<div class='form-group'>
					<label for="domains">Domains list</label>
					<textarea id="domains" rows="20" name='domains' placeholder='Enter 1 domain per line' class='form-control'></textarea>
				</div>
				<div class='form-group'>
					<input type="submit" class='btn btn-lg btn-primary pull-right' value="Import">
				</div>
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>