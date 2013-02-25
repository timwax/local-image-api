<?php

/**
 * Common function used by Image API
 */

if (!defined('ROOT')) die('Cant be executed directly');

include_once('inc/resize-class.php');

/**
 * Load image
 * 
 */

function loadImage($path, $size = 'full'){
	global $upload_path, $config, $app;

	$path = urldecode($path);

	$dir = pathinfo($path, PATHINFO_DIRNAME);
	$filename = pathinfo($path, PATHINFO_FILENAME);
	$extension = pathinfo($path, PATHINFO_EXTENSION);

	$cached = $dir . '/' . $config['cache_dir'] . '/' . $filename . '_' . $size . '.' . $extension;

	if (file_exists(FULL_ROOT . $cached)){
		$app->getLog()->info('Image from cache: '.$cached);
		return ROOT . $cached;
	}

	$app->getLog()->info('Load path: '. FULL_ROOT . $path);

	$image = new resize(FULL_ROOT . $path);

	$image->resizeImage($config['image_size'][$size]['width'], $config['image_size'][$size]['height'], $config['image_size'][$size]['resize']);

	if (!file_exists(FULL_ROOT . $dir . '/' . $config['cache_dir'])){
		mkdir(FULL_ROOT . $dir . '/' . $config['cache_dir'], 0777);
	}
	$image->saveImage(FULL_ROOT . $cached, $config['image_quality']);
	//$app->getLog()->info('Image from live: '.$cached);
	return ROOT . $cached;
}