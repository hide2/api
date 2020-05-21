<?php
use Workerman\Worker;

class WSApp extends Worker
{

	private $map_path = array();

	public function on($path, callable $callback){
		$this->map_path[$path] = $callback;
	}

	public function onWebSocketConnect($connection , $http_header)
    {
        // params
		$req = new stdClass();
		$req->method = $_SERVER['REQUEST_METHOD'];
		$req->uri = $_SERVER['REQUEST_URI'];
		$req->path = $req->uri;
		$pos = stripos($req->path,'?');
		if ($pos !== false) {
			$req->path = substr($req->path,0,$pos);
		}
		$connection->path = $req->path;
    }

	public function onClientMessage($connection, $data)
	{
		$cb = $this->map_path[$connection->path];
		if ($cb) {
			$data = json_decode($data);
			$data = call_user_func($cb, $data);
			$connection->send(json_encode($data));
		} else {
			$connection->send(json_encode(array('code' => '404')));
		}
	}

	public function run()
	{
		$this->reusePort = true;
		$this->onWebSocketConnect = array($this, 'onWebSocketConnect');
		$this->onMessage = array($this, 'onClientMessage');
		parent::run();
	}
}