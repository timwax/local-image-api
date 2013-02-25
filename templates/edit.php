<!DOCTYPE html>
<html lang="en">
<head>
	<title>Image API</title>
	<link rel="stylesheet" href="./client/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="./client/jcrop/css/jquery.Jcrop.min.css" />
</head>
<body>
	<div class="container">
		<?php include 'templates/fragments/header.php'; ?>
		<div class="row-fluid">
			<div class="span3">
				<h3 style="margin-top:0; padding-top: 0">Editing image </h3>
			</div>
			<div class="span7">
				<form method="post" id="crop">
					<select id="image_size">
					<option value="original">Original</option>
					<?php foreach ($config['image_size'] as $key => $imagesize) { ?>
						<option value="<?php echo $key; ?>"><?php echo (isset($imagesize['label'])) ? $imagesize['label'] : $key; ?></option>
					<? } ?>
					
				</select>
				<input type="hidden" name="x" id="x" value="">
				<input type="hidden" name="y" id="y" value="">
				<input type="hidden" name="x2" id="x2" value="">
				<input type="hidden" name="y2" id="y2" value="">
				<input type="hidden" name="scale_w" id="scale_w" value="">
				<input type="hidden" name="scale_h" id="scale_h" value="">
				</form>
			</div>
			<div class="span2">
				<a class="btn btn-primary" href="#">Save</a>
			</div>
		</div>
		
		<div class="row-fluid">
			<div id="sidebar" class="span3">
				<ul class="nav nav-tabs nav-stacked">
					<li><a href="#crop">Crop</a></li>
<!-- 					<li><a href="#scale">Scale</a></li> -->
					<!-- <li><a href="#focus">Focus</a></li> -->
					<li><a class="btn btn-danger">Delete</a></li>
				</ul>
			</div>
			<div id="content" class="span9 last">
				<img src="<?php echo $image; ?>" id="cropbox">
			</div>
		</div>
	</div>
	<script type="text/javascript" src="./client/jquery.js"></script>
	<script type="text/javascript" src="./client/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="./client/jcrop/js/jquery.Jcrop.min.js"></script>
	<script type="text/javascript">
		var jcropapi;

		$(function(){
			function updateCoords(c){
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);

				$('#scale_w').val($('#cropbox').width());
				$('#scale_h').val($('#cropbox').height());
			}

			$('#cropbox').Jcrop({
				aspectRatio: 1,
				onSelect: updateCoords
			}, function(){ jcropapi = this });

			$('#image_size').on({
				'change': function(e){
					// UPDATE jcrop options

					var image_size = $(this).val();

					$.getJSON('./api/imagesize/'+image_size, function(response){
						//console.log(response);
						jcropapi.setOptions({
							aspectRatio: response.width / response.height
						});
					});

					//console.log($(this).val());
				}
			});

			$('.btn.btn-primary').on({
				click: function(e){
					e.preventDefault();

					var params = {};
					params.imagesize = $('#image_size').val();
					params.src = $('#cropbox').prop('src');
					params.coords = $('#crop').serialize();

					$.post('./action/crop', params, function(response){
						console.log(response);
					}, 'JSON');
					
				}
			})
			console.log('jcropapi', jcropapi);
		});
	</script>
</body>
</body>
</html>