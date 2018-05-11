<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;

$ws_worker = new Worker("websocket://0.0.0.0:2000");
$ws_worker->count = 4;
$ws_worker->name = 'ws';

$ws_worker->onMessage = function($connection, $data)
{
	$data = json_decode($data);
	var_dump($data->method);
	var_dump($data->params);
	$connection->send(json_encode($data));
};

// 运行worker
Worker::runAll();

// var ws = new WebSocket('ws://127.0.0.1:2000');
// ws.onopen = function(e) {
// 	console.log("[onopen]"+e);
// 	var data = {
// 		method: 'stats',
// 		params: {
// 			a: 111,
// 			b: 'abc'
// 		}
// 	}
// 	ws.send(JSON.stringify(data));
// }
// ws.onmessage = function(e) {
// 	console.log("[onmessage]"+e.data);
// }
// ws.onclose = function(e) {
// 	console.log("[onclose]"+e);
// }
// ws.onerror = function(e) {
// 	console.log("[onerror]"+e);
// }