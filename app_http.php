<?php
use Workerman\Worker;
use Workerman\Protocols\Http;

class App extends Worker
{

	private $map_get = array();
	private $map_post = array();
	private $map_before = array();
	private $map_after = array();

	public function get($path, callable $callback){
		$this->map_get[$path] = $callback;
	}

	public function post($path, callable $callback){
		$this->map_post[$path] = $callback;
	}

	public function before($path, callable $callback){
		$this->map_before[$path] = $callback;
	}

	public function after($path, callable $callback){
		$this->map_after[$path] = $callback;
	}

	public function onClientMessage($connection, $data)
	{
		Http::header("Content-Type: application/json");

		// params
		$req = new stdClass();
		$req->method = $_SERVER['REQUEST_METHOD'];
		$req->uri = $_SERVER['REQUEST_URI'];
		$req->path = $req->uri;
		$pos = stripos($req->path,'?');
		if ($pos !== false) {
			$req->path = substr($req->path,0,$pos);
		}
		if ($req->method == 'GET') {
			$req->params = $_GET;
		} elseif ($req->method == 'POST') {
			$req->params = $_POST;
		}

		// before
		foreach ($this->map_before as $path => $cb) {
			$pos = stripos($req->path,$path);
			if ($pos !== false) {
				$data = call_user_func($cb, $req);
				if (!$data[0]) {
					$connection->send(json_encode($data[1]));
					return;
				}
			}
		}

		// callback
		if ($req->method == 'GET') {
			$cb = $this->map_get[$req->path];
			if ($cb) {
				$data = call_user_func($cb, $req);
				$connection->send(json_encode($data));
			} else {
				$connection->send('404');
			}
		} elseif ($req->method == 'POST') {
			$cb = $this->map_post[$req->path];
			if ($cb) {
				$data = call_user_func($cb, $req);
				$connection->send(json_encode($data));
			} else {
				$connection->send('404');
			}
		}

		// after
		foreach ($this->map_after as $path => $cb) {
			$pos = stripos($req->path,$path);
			if ($pos !== false) {
				$data = call_user_func($cb, $req);
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