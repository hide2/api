<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;

$ws_worker = new Worker("websocket://0.0.0.0:2000");
$ws_worker->count = 4;
$ws_worker->name = 'ws';

$ws_worker->onMessage = function($connection, $data)
{
	var_dump($data);
    $connection->send('hello ' . $data);
};

// 运行worker
Worker::runAll();