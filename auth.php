<?php
class Auth {

	private static $secret = "1234567abcdefg";

	public static function verify_sign($params) {
		if (!$params['key'] || !$params['sign']) {
			return array(false, 'need key & sign');
		}
		$sign = hash('sha512', $params['key'].Auth::$secret);
		if ($sign !== $params['sign']) {
			return array(false, 'wrong sign');
		}
		return array(true);
	}
}






































