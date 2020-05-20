<?php
require_once __DIR__ . '/config.php';

class Kafka
{
    private static $brokers = KAFKA_BROKERS;
    private static $_producer;
    private static $_consumer;

    public static function getProducer()
    {
        if (!isset(self::$_producer)) {
            $conf = new RdKafka\Conf();
            $conf->set('queue.buffering.max.kbytes', 2000000);
            $conf->set('queue.buffering.max.messages', 1000000);
            $rk = new RdKafka\Producer($conf);
            $rk->setLogLevel(LOG_DEBUG);
            $rk->addBrokers(Kafka::$brokers);
            self::$_producer = $rk;
        }
        return self::$_producer;
    }

    public static function getConsumer()
    {
        if (!isset(self::$_consumer)) {
            $rk = new RdKafka\Consumer();
            $rk->setLogLevel(LOG_DEBUG);
            $rk->addBrokers(Kafka::$brokers);
            self::$_consumer = $rk;
        }
        return self::$_consumer;
    }

    public static function produce($topic, $message)
    {
        if (defined('KAFKA_BROKERS')) {
            $rk = Kafka::getProducer();
            $topic = $rk->newTopic($topic);
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        }
    }

    public static function consume($topic, $offset = RD_KAFKA_OFFSET_END)
    {
        if (defined('KAFKA_BROKERS')) {
            $has_consumer = isset(self::$_consumer);
            $rk = Kafka::getConsumer();
            $topic = $rk->newTopic($topic);
            if (!$has_consumer) {
                $topic->consumeStart(0, $offset);
            }
            $msg = $topic->consume(0, 1000);
            if ($msg and $msg->payload) {
                return $msg->payload;
            }
        }
    }
}
