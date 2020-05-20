<?php

// redis
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_LOG', true);


// mysql - write
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_USER', 'test');
define('DB_PASS', 'test');
define('DB_SCHEMA', 'mysql');
// define('DB_LOG_SQL', true);

// mysql - read
define('DB_HOST_READ', '127.0.0.1');
define('DB_PORT_READ', 3306);
define('DB_USER_READ', 'test');
define('DB_PASS_READ', 'test');
define('DB_SCHEMA_READ', 'mysql');
define('DB_LOG_SQL', true);

// kafka
define('KAFKA_BROKERS', 'localhost');

// clickhouse
define('CLICKHOUSE_HOST', '127.0.0.1');
define('CLICKHOUSE_PORT', '8123');
define('CLICKHOUSE_USER', 'default');
define('CLICKHOUSE_PASS', '');
define('CLICKHOUSE_DB', 'test');

// Project ID/Secret
define('PROJECT_ID_SECRETE', [
    'test' => '2e3b42e53ba480b297cbc0b6f02c451f',
]);