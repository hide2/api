<?php
global $db;
$db = new Workerman\MySQL\Connection('127.0.0.1', '3306', 'user', 'password', 'database');

class DB {
	public static function get_tables() {
		global $db;
		return $db->query('show tables');
	}
}