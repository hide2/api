<?php
class Auth
{

	public static function verify_api($req)
	{
		$uri = $req->uri;
		if ($req->_from_ip != '127.0.0.1') {
			// verify params
			if (!isset($req->params['app_id']) || !isset($_SERVER['HTTP_SIGN'])) {
				echo ("No app_id/sign\n");
				return [false, ['code' => 1001, 'message' => 'missing app_id, sign']];
			}
			$app_id = $req->params['app_id'];
			$sign = $_SERVER['HTTP_SIGN'];
			$secret = PROJECT_ID_SECRETE[$app_id];
			// verify sign
			$_sign = base64_encode(hash_hmac('sha256', urldecode($uri), $secret, true));
			if ($sign !== $_sign) {
				echo ("Wrong app_id: " . $app_id . ", sign: " . $sign . "\n");
				return [false, ['code' => 1002, 'message' => 'wrong sign']];
			}
		}
		return [true];
	}
}
