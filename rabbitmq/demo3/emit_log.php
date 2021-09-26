<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('docker-rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

//交换机
$channel->exchange_declare('logs', 'fanout', false, false, false);

$data = implode(' ', array_slice($argv, 1));
if (empty($data)) {
	$data = "info: Hello World!";
}
$msg = new AMQPMessage($data);

//消息发布到交换机
$channel->basic_publish($msg, 'logs');

echo ' [x] Sent ', $data, "\n";

$channel->close();
$connection->close();
