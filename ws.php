<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/app_ws.php';

$wsapp = new WSApp("websocket://0.0.0.0:2000/");
$wsapp->count = 4;
$wsapp->name = 'ws';

$wsapp->on('/', function($params) {
	return array('code' => '200');
});

$wsapp->on('/api', function($params) {
	if ($params->action == 'subscribe') {
		return $params;
	}
	return array('code' => '404');
});

WSApp::runAll();

// JavaScript Client
// var ws = new WebSocket('ws://127.0.0.1:2000/');
// ws.onopen = function(e) {
// 	console.log("[onopen]"+e);
// 	var data = {
// 		action: 'aaa',
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

// var ws = new WebSocket('ws://127.0.0.1:2000/api');
// ws.onopen = function(e) {
// 	console.log("[onopen]"+e);
// 	var data = {
// 		action: 'subscribe',
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