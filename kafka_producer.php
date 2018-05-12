<?php
// bin/zookeeper-server-start.sh config/zookeeper.properties &
// bin/kafka-server-start.sh config/server.properties &
// bin/kafka-topics.sh --create --zookeeper localhost:2181 --replication-factor 1 --partitions 1 --topic test
// bin/kafka-topics.sh --list --zookeeper localhost:2181
// bin/kafka-console-producer.sh --broker-list localhost:9092 --topic test
// bin/kafka-console-consumer.sh --zookeeper localhost:2181 --topic test --from-beginning
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('PRC');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));

$config = \Kafka\ProducerConfig::getInstance();
$config->setMetadataRefreshIntervalMs(10000);
$config->setMetadataBrokerList('127.0.0.1:9092');
$config->setBrokerVersion('1.1.0');
$config->setRequiredAck(1);
$config->setIsAsyn(false);
$config->setProduceInterval(500);

$producer = new \Kafka\Producer();
$producer->setLogger($logger);

for($i = 0; $i < 10; $i++) {
    $producer->send([
        [
            'topic' => 'test',
            'value' => 'test....message'.$i,
            'key' => 'testkey',
        ],
    ]);
}