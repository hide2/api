<?php

use Workerman\Worker;
use Workerman\Protocols\Http;

class RequestOjb
{
	public function get($key)
	{
		if (isset($this->params[$key])) {
			return $this->params[$key];
		}
	}
}

class App extends Worker
{
	private $map_get = array();
	private $map_post = array();
	private $map_html = array();
	private $map_before = array();
	private $map_after = array();

	public function get($path, callable $callback)
	{
		$this->map_get[$path] = $callback;
	}

	public function post($path, callable $callback)
	{
		$this->map_post[$path] = $callback;
	}

	public function html($path, callable $callback)
	{
		$this->map_html[$path] = $callback;
	}

	public function before($path, callable $callback)
	{
		$this->map_before[$path] = $callback;
	}

	public function after($path, callable $callback)
	{
		$this->map_after[$path] = $callback;
	}

	public function onClientMessage($connection, $data)
	{
		Http::header("Content-Type: application/json");

		$req = new RequestOjb();

		// ip
		$ip = $_SERVER['REMOTE_ADDR'];
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		$req->_from_ip = $ip;

		// refer
		$req->_refer = 'REF=';
		if (isset($_SERVER['HTTP_REFERER'])) {
			$req->_refer = $req->_refer . $_SERVER['HTTP_REFERER'];
		}

		// params
		$req->method = $_SERVER['REQUEST_METHOD'];
		$req->uri = $_SERVER['REQUEST_URI'];
		$req->path = $req->uri;
		$pos = stripos($req->path, '?');
		if ($pos !== false) {
			$req->path = substr($req->path, 0, $pos);
		}
		if ($req->method == 'GET') {
			$req->params = $_GET;
		} elseif ($req->method == 'POST') {
			$req->params = $_POST;
		} else {
			$req->params = [];
		}
		$ps = [];
		foreach ($req->params as $key => $value) {
			$ps[$key] = $value;
		}

		echo "[" . date('Y-m-d H:i:s') . "] " . $req->method . " " . $req->path . " " . json_encode($ps, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . " " . $req->_from_ip . " " . $req->_refer . "\n";

		// before
		foreach ($this->map_before as $path => $cb) {
			$pos = stripos($req->path, $path);
			if ($pos !== false) {
				$data = call_user_func($cb, $req);
				if (!$data[0]) {
					$connection->send(json_encode($data[1], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
					return;
				}
			}
		}

		// callback
		if ($req->method == 'GET') {
			if (isset($this->map_get[$req->path])) {
				$cb = $this->map_get[$req->path];
				$data = call_user_func($cb, $req);
				$connection->send(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			} elseif (isset($this->map_html[$req->path])) {
				$cb = $this->map_html[$req->path];
				$data = call_user_func($cb, $req);
				Http::header("Content-Type: text/html");
				$connection->send($data);
			} else {
				$connection->send(json_encode(['code' => 404, 'message' => 'Not Found']));
			}
		} elseif ($req->method == 'POST') {
			if (isset($this->map_post[$req->path])) {
				$cb = $this->map_post[$req->path];
				$data = call_user_func($cb, $req);
				$connection->send(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			} else {
				$connection->send(json_encode(['code' => 404, 'message' => 'Not Found']));
			}
		} else {
			$connection->send('ok');
		}

		// after
		foreach ($this->map_after as $path => $cb) {
			$pos = stripos($req->path, $path);
			if ($pos !== false) {
				$data = call_user_func($cb, $req);
			}
		}
	}

	public function run()
	{
		$this->reusePort = true;
		$this->onMessage = [$this, 'onClientMessage'];
		parent::run();
	}
}
