<?php
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('PRC');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));

$config = \Kafka\ConsumerConfig::getInstance();
$config->setMetadataRefreshIntervalMs(10000);
$config->setMetadataBrokerList('127.0.0.1:9092');
$config->setBrokerVersion('1.1.0');
$config->setGroupId('test');
$config->setTopics(['test']);

$consumer = new \Kafka\Consumer();
$consumer->setLogger($logger);

$consumer->start(function($topic, $part, $message) {
	var_dump($message);
});