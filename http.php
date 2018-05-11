<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app_http.php';
use Workerman\Protocols\Http;

$app = new App("http://0.0.0.0:2345");
$app->count = 4;
$app->name = 'api';

$app->get('/', function($req){
	return "66666";
});

$app->post('/', function($req){
	return "66666";
});

$app->get('/api', function($req){
	Http::header("Content-Type: application/json");
	$data = array('name'=>'dad');
	return json_encode($data);

});

// run all workers
App::runAll();