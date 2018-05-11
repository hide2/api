<?php
use Workerman\Worker;

class WSApp extends Worker
{

	private $map_rpc = array();

	public function on($path, callable $callback){
		$this->map_rpc[$path] = $callback;
	}

	public function onClientMessage($connection, $data)
	{
		$data = json_decode($data);
		$cb = $this->map_rpc[$data->method];
		if ($cb) {
			$data = call_user_func($cb, $data->params);
			$connection->send(json_encode($data));
		}
	}

	public function run()
	{
		$this->reusePort = true;
		$this->onMessage = array($this, 'onClientMessage');
		parent::run();
	}
}