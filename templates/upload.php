<!DOCTYPE html>
<html lang="en">
<head>
	<title>Image API</title>
	<link rel="stylesheet" href="./client/bootstrap/css/bootstrap.min.css" />
	<style type="text/css">

	</style>
</head>
<body>
	<div class="container">
		<?php include 'templates/fragments/header.php'; ?>
		
		<div class="row-fluid">
			<div class="span8 offset2">
				<div>
					<form method='post' enctype='multipart/form-data'>
						<input type='file' name='file' />
						<input type='submit' value='Upload' />
					</form>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="./client/jquery.js"></script>
	<script type="text/javascript" src="./client/bootstrap/js/bootstrap.min.js"></script>
</body>
</body>
</html>