<!DOCTYPE html>
<html lang="en">
<head>
	<title>Image API</title>
	<link rel="stylesheet" href="./client/bootstrap/css/bootstrap.min.css" />
	<style type="text/css">
	.thumbnail{
		position: relative;
	}
	.thumbnail .action{
		display: absolute;
		bottom: 0;
		left: 0;
		background-color: #EEE;
	}

	.action ul{
		list-style-type: none;
		height: 24px;
	}

	.action ul li{
		float: left;
		margin: 0 5px;
	}
	</style>
</head>
<body>
	<div class="container">
		<?php include 'templates/fragments/header.php'; ?>
		<div class="row-fluid">
			<div class="span10">
				<h3>Browse Images</h3>
			</div>
			<div class="span2">
				<ul>
					<li><a href="./upload" class="btn btn-primary">upload</a></li>
				</ul>
			</div>
		</div>
		
		<div class="row-fluid">
			<ul class="thumbnails">
				<?php foreach ($images as $key => $image) { 
					if(strpos($image->getPathname(), '/.cache')){
						continue;
					}  ?>
				<li>
					<a href="/image/image?p=<?php echo $image->getPathname(); ?>&edit=1" class="thumbnail">
						<img src="/image/image?p=<?php echo $image->getPathname(); ?>&s=thumb">
					</a>
				</li>
				<?php }?>
			</ul>
		</div>
	</div>
	<script type="text/javascript" src="./client/jquery.js"></script>
	<script type="text/javascript" src="./client/bootstrap/js/bootstrap.min.js"></script>
</body>
</body>
</html>