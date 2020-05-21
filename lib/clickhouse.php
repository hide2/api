<?php
class ClickHouse
{
    private static  $_instance;
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            $config = [
                'host' => CLICKHOUSE_HOST,
                'port' => CLICKHOUSE_PORT,
                'username' => CLICKHOUSE_USER,
                'password' => CLICKHOUSE_PASS
            ];
            $db = new ClickHouseDB\Client($config);
            $db->database(CLICKHOUSE_DB);
            $db->setTimeout(100);       // 100 seconds
            $db->setConnectTimeOut(10); // 10 seconds
            self::$_instance = $db;
        }
        return self::$_instance;
    }
}
