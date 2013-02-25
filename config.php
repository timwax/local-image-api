<?php

# Storage location

$upload_path = array();

$upload_path['default'] = 'images';
$upload_path['profile'] = 'profile/:profile_id/images';

# Libs

$config['slim'] = 'inc/Slim/Slim.php';

# Storage type

$config['order_by'] = 'date';
$config['random_filename'] = false;
$config['cache_dir'] = '.cache';
$config['image_quality'] = 85;

# Images sizes

$config['image_size'] = array();

$config['image_size']['thumb'] = array('width'=>'200', 'height'=>'200', 'aspectratio'=>true, 'resize' => 'crop', 'label'=>'Thumbnail 200 * 200');
$config['image_size']['banner_square_200'] = array('width'=>'200', 'height'=>'200', 'aspectratio'=>true, 'resize' => 'exact', 'label'=>'Banner 200 * 200');
$config['image_size']['medium'] = array('width'=>'640', 'height'=>'360', 'aspectratio'=>true, 'resize' => 'landscape', 'label'=>'Medium 640 * 360');
$config['image_size']['strip'] = array('width'=>'640', 'height'=>'100', 'aspectratio'=>true, 'resize' => 'landscape', 'label'=>'Banner 640 * 100');
$config['image_size']['full'] = array('width'=>'1024', 'height'=>'1024', 'aspectratio'=>true, 'resize' => 'landscape', 'label'=>'Full 1024 * 1024');