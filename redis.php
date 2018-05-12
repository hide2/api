<?php
global $redis;
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);


class MyRedis {
	public static function set_tables($tables) {
		global $redis;
		$redis->set('all_tables', json_encode($tables));
	}

	public static function get_tables() {
		global $redis;
		return json_decode($redis->get('all_tables'));
	}
}