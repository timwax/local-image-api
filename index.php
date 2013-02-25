<?php

require 'config.php';

define ('ROOT', '/image/');
define ('FULL_ROOT', $_SERVER['DOCUMENT_ROOT']. '/image/');

include $config['slim'];

Slim\Slim::registerAutoloader();

$app = new Slim\Slim();

include 'inc/common.php';

$app->get('/', function(){
	$app = Slim\Slim::getInstance();

	if (isset($_GET['p'])){
		echo $_GET['p'];
	}else{
		$app->render('startpage.html');
	}
});

function upload(){
	global $app;

	$app->render('upload.php');

}

function upload_do(){
	global $config, $upload_path;
	// var_dump($GLOBALS);
	if (isset($_FILES['file'])){
		// Do up load

		$file = $_FILES['file'];

		$dest = $upload_path['default'];

		switch ($config['order_by']){
			case 'date':
				$dest .= '/' . date('Y/m/j');

				try{
					mkdir($dest, 0777, true);
				}catch(Exception $e){
					
				}
				
				$dest .= '/' .$file['name'];
			break;

			default:
				$dest .= '/' .$file['name'];
		}
		if (move_uploaded_file($file['tmp_name'], $dest)){

			$hooks = new DirectoryIterator('plugins/onupload/');

			foreach ($hooks as $key => $hook) {
				if ($hook->getExtension() == 'php'){
					include $hook->getPathname();

					// Call hook function

					$hook_name = pathinfo($hook->getFilename(), PATHINFO_FILENAME);

					$f = 'hook_'.$hook_name;

					$f($dest, $_REQUEST);
				}
			}
			echo 'Upload OK: '.$dest;
		}
			

	}else{
		echo 'Nothing in input';
	}
}

$app->get('/upload', 'upload');
$app->post('/upload', 'upload_do');

$app->get('/browse', function(){
	global $upload_path, $app;

	$files = new RecursiveDirectoryIterator($upload_path['default'], RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator = new RecursiveIteratorIterator($files, RecursiveIteratorIterator::LEAVES_ONLY);

	// foreach ($iterator as $key => $file) {
	// 	$path = $file->getPathname();
	// 	echo '<a href="./image?p='.$path.'&edit=true"><img src="/image/image?p='. $path .'&s=thumb" width="100" /></a><br />';
	// }
	$app->render('browse.php', array('images'=>$iterator));
});

$app->get('/image', function(){
	global $config, $upload_path, $app;

	if (isset($_GET['p'])){
		$path = $_GET['p'];

		if (isset($_GET['edit'])){
			$app->render('edit.php', array('image'=>$path, 'config'=>$config));
		}else{
			// Viewing image

			// Check if cache

			// else {{Auto resize}}

			$size = (isset($_GET['s'])) ? $_GET['s'] : 'thumb';

			$image = loadImage($path, $size);
			$app->redirect($image);
		}
		
	}else{
		$app->redirect('http://placeholt.it/200');
	}
});

$app->get('/api/config/sizes', function(){
	// Return image sizes as JSON
	global $app, $config;

	echo json_encode($config['image_size']);
});

$app->get('/api/imagesize/:imagesize', function($imagesize = 'original'){
	// Return image sizes as JSON
	global $app, $config;

	// Handle original

	if (isset($config['image_size'][$imagesize])){
		echo json_encode($config['image_size'][$imagesize]);
	}
	
});

$app->post('/action/crop', function(){
	global $app, $config, $upload_path;

	include_once 'inc/resize-class.php';

	$url = parse_url($_POST['src']);
	$src = urldecode($url['path']);
	$im = new resize($_SERVER['DOCUMENT_ROOT'] . $src);

	$dim = $im->getSize();

	$p = explode('&', $_POST['coords']);

	$params = array();

	foreach ($p as $key => $value) {
		$x = explode('=', $value);

		$params[$x[0]]  = $x[1];
	}

	if ($dim[0] == $params['scale_w']){
		$scale_x = 1;
	}else{
		$scale_x = $dim[0]/$params['scale_w'];
	} 	


	if ($dim[1] == $params['scale_h']){
		$scale_y = 1;
	}else{
		$scale_y = $dim[1]/$params['scale_h'];
	} 

	$im->crop_from($params, $scale_x, $scale_y);

	$im->resizeImage($config['image_size'][$_POST['imagesize']]['width'], $config['image_size'][$_POST['imagesize']]['width'], $config['image_size'][$_POST['imagesize']]['resize']);

	// save as cached image size
	
	$dir = pathinfo($_SERVER['DOCUMENT_ROOT'] . $src, PATHINFO_DIRNAME);
	$filename = pathinfo($_SERVER['DOCUMENT_ROOT'] . $src, PATHINFO_FILENAME);
	$extension = pathinfo($_SERVER['DOCUMENT_ROOT'] . $src, PATHINFO_EXTENSION);

	// Check if cache folder exists

	$cache_dir = $dir . '/' . $config['cache_dir'];

	if (!file_exists($dir . '/' . $config['cache_dir'])){
		// Create folder
		try{
			mkdir($dir . '/' . $config['cache_dir'], 0777);
		}catch(Exception $e){
			// Log error
			$app->getLog()->warn($e->getMessage());
		}
		
	}

	// Save image sizetype

	$im->saveImage($cache_dir.'/'.$filename . '_' . $_POST['imagesize'] . '.' . $extension, 85);

});
$app->run();