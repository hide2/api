<?php
class CACHE {
	private static  $_instance;
	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new Redis();
			self::$_instance->connect(REDIS_HOST, REDIS_PORT);
		}
		return self::$_instance;
	}

	public static function exists($key) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][exists] ".$key."\n";
		}
		return CACHE::getInstance()->exists($key);
	}

	public static function get($key) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][get] ".$key."\n";
		}
		if (CACHE::getInstance()->exists($key)) {
			$r = CACHE::getInstance()->get($key);
			return $r;
		}
	}

	public static function set($key, $val, $exp=null) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][set] ".$key."\n";
		}
		CACHE::getInstance()->set($key, $val, $exp);
	}

	public static function setEx($key, $exp, $val) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][setEx] ".$key."\n";
		}
		CACHE::getInstance()->setEx($key, $exp, $val);
	}

	public static function del($key) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][del] ".$key."\n";
		}
		CACHE::getInstance()->del($key);
	}

	public static function mGet($keys) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][mGet] ".implode('_', $keys)."\n";
		}
		CACHE::getInstance()->mGet($keys);
	}

	public static function mSet($kvs) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][mSet] ".implode('_', array_keys($kvs))."\n";
		}
		CACHE::getInstance()->mSet($kvs);
	}

	public static function sAdd($key, $val) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][sAdd] ".$key."\n";
		}
		CACHE::getInstance()->sAdd($key, $val);
	}

	public static function sIsMember($key, $val) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][sIsMember] ".$key."\n";
		}
		CACHE::getInstance()->sIsMember($key, $val);
	}

	public static function sContains($key, $val) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][sContains] ".$key."\n";
		}
		CACHE::getInstance()->sContains($key, $val);
	}

	public static function sSize($key) {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][sSize] ".$key."\n";
		}
		CACHE::getInstance()->sSize($key);
	}

	public static function flushAll() {
		if (defined('REDIS_LOG') && REDIS_LOG) {
			echo "[".date('Y-m-d H:i:s')."][REDIS][flushAll] "."\n";
		}
		CACHE::getInstance()->flushAll();
	}
}