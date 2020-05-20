API
========
High performance api service

单服开10+Worker，轻松支撑C100K请求

Windows下安装PHP7
========
```
下载最新PHP7.x http://windows.php.net/download/
解压到任意目录，比如F:\php
将PHP的安装路径F:\php加入PATH 环境变量
进入PHP安装目录（例如 F:\php）。找到php.ini-development文件并复制一份到当前目录，重命名为php.ini
用编辑器打开 php.ini 文件，修改以下配置：
去掉extension_dir = "ext"前面的分号（738 行左右）
去掉extension=php_curl.dll前面的分号（893 行左右）
去掉extension=php_mbstring.dll前面的分号（903 行左右）
去掉extension=php_openssl.dll前面的分号（907 行左右）
去掉extension=php_pdo_mysql.dll前面的分号（909 行左右）
```

Mac下安装PHP7
========
```
ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
brew update
brew untap homebrew/php
brew uninstall php
brew upgrade
brew clenaup
brew prune
brew install autoconf
brew install php
```

CentOS7下部署
========
```
sudo yum -y install epel-release
sudo rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
sudo yum -y install git
sudo yum install php71w php71w-devel php71w-cli php71w-common php71w-gd php71w-ldap php71w-mbstring php71w-mcrypt php71w-mysql php71w-pdo php71w-fpm php71w-pecl-redis -y

cd ~
sudo yum install gcc openssl openssl-devel libevent libevent-devel
sudo pecl install event-2.3.0
sudo vi /etc/php.d/sockets.ini
extension=sockets.so
extension=event.so

sudo yum install redis
sudo systemctl enable redis
sudo systemctl start redis
```

安装Workerman的pcntl和posix扩展、event或者libevent扩展：http://doc.workerman.net/315116

内核参数调优：http://doc.workerman.net/315302

示例代码
========
```php
/////////////////////////////////// http
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

/////////////////////////////////// websocket
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app_ws.php';

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
```

启动服务
========
```
mkdir logs
php api.php start
php ws.php start
```
压力测试
```
ab -n 1000000 -c 1000 -k http://localhost:8888/
```
