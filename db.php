<?php
require_once __DIR__ . '/config.php';

class DB
{
	private static  $_instance;
	private static  $_read_instance;

	// Write
	public static function getInstance()
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new Workerman\MySQL\Connection(DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_SCHEMA);
		}
		return self::$_instance;
	}

	// Read
	public static function getReadInstance()
	{
		if (!isset(self::$_read_instance)) {
			self::$_read_instance = new Workerman\MySQL\Connection(DB_HOST_READ, DB_PORT_READ, DB_USER_READ, DB_PASS_READ, DB_SCHEMA_READ);
		}
		return self::$_read_instance;
	}

	// Write
	public static function insert($args)
	{
		return DB::getInstance()->insert($args);
	}

	public static function update($args)
	{
		return DB::getInstance()->update($args);
	}

	public static function delete($args)
	{
		return DB::getInstance()->delete($args);
	}

	// Read
	public static function select($args)
	{
		return DB::getReadInstance()->select($args);
	}

	public static function query($args)
	{
		return DB::getReadInstance()->query($args);
	}
}
