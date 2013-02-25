<?php

// USE

function hook_use($url, $data = array()){
	global $app, $config, $upload_path;

	$log = $app->getLog();

	$log->info($url. ' - Calling hook with request data: '. json_encode($data));
}