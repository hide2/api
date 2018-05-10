<?php
namespace WebWorker;

use Workerman\Worker;

class App extends Worker
{

	private $map_get = array();
	private $map_post = array();

	public function get($path, callable $callback){
		$this->map_get[$path] = $callback;
	}

	public function post($path, callable $callback){
		$this->map_post[$path] = $callback;
	}

	public function onClientMessage($connection, $data)
	{
		$req = array();
		$req['method'] = $_SERVER['REQUEST_METHOD'];
		$req['uri'] = $_SERVER['REQUEST_URI'];
		$req['path'] = $req['uri'];
		$pos = stripos($req['path'],'?');
		if ($pos != false) {
			$req['path'] = substr($req['path'],0,$pos);
		}
		if ($req['method'] == 'GET') {
			$req['params'] = $_GET;
		} elseif ($req['method'] == 'POST') {
			$req['params'] = $_POST;
		}

		if ($req['method'] == 'GET') {
			$cb = $this->map_get[$req['path']];
			if ($cb) {
				$data = call_user_func($cb, $req);
				$connection->send($data);
			} else {
				$connection->send('404');
			}
		} elseif ($req['method'] == 'POST') {
			$cb = $this->map_post[$req['path']];
			if ($cb) {
				$data = call_user_func($cb, $req);
				$connection->send($data);
			} else {
				$connection->send('404');
			}
		}
	}

	public function run()
	{
		$this->reusePort = true;
		$this->onMessage = array($this, 'onClientMessage');
		parent::run();
	}
}

