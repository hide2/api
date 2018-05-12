<?php
// bin/zookeeper-server-start.sh config/zookeeper.properties &
// bin/kafka-server-start.sh config/server.properties &
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('PRC');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler('php://stdout'));

$config = \Kafka\ProducerConfig::getInstance();
$config->setMetadataRefreshIntervalMs(10000);
$config->setMetadataBrokerList('127.0.0.1:9092');
$config->setBrokerVersion('1.1.0');
$config->setRequiredAck(1);
$config->setIsAsyn(false);
$config->setProduceInterval(500);

$producer = new \Kafka\Producer();
$producer->setLogger($logger);

for($i = 0; $i < 100; $i++) {
    $producer->send([
        [
            'topic' => 'test',
            'value' => 'test....message'.$i,
            'key' => 'testkey',
        ],
    ]);
}