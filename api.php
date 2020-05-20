<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app_http.php';
require_once __DIR__ . '/config.php';
ini_set("precision", "-1");
ini_set("serialize_precision", "-1");

$app = new App("http://0.0.0.0:8888");
$app->count = 8;
$app->name = 'api';

$app->onWorkerStart = function ($worker) {
	require_once __DIR__ . '/auth.php';
	require_once __DIR__ . '/db.php';
	require_once __DIR__ . '/clickhouse.php';
	require_once __DIR__ . '/cache.php';
	require_once __DIR__ . '/kafka.php';
	require_once __DIR__ . '/config.php';
	ini_set("precision", "-1");
	ini_set("serialize_precision", "-1");

	echo "[" . date('Y-m-d H:i:s') . "] Worker start[" . $worker->id . "]\n";
};

///////////////////////////////// API接口定义开始

$app->get('/', function ($req) {
	return 'OK';
});

$app->post('/', function ($req) {
	return 'OK';
});

$app->get('/health/check', function ($req) {
	return 'OK';
});

$app->before('/api', function ($req) {
	return Auth::verify_api($req);
});

$app->get('/tables', function ($req) {
	if (CACHE::exists('tables')) {
		return json_decode(CACHE::get('tables'));
	} else {
		$tables = DB::query('show tables');
		CACHE::set('tables', json_encode($tables), 5);
		return json_decode(CACHE::get('tables'));
	}
});

///////////////////////////////// API接口定义结束


App::$logFile = './logs/workerman_api.log';
App::$stdoutFile = './logs/api.log';

App::runAll();