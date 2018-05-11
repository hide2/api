<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app_http.php';

$app = new App("http://0.0.0.0:2345");
$app->count = 4;
$app->name = 'http';

$app->post('/', function($req){
	return "66666";
});

$app->get('/api', function($req){
	$data = array('name'=>'dad');
	return $data;
});

// run all workers
App::runAll();