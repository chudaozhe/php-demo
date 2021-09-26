<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('docker-rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$i=1;
do{
	$data = "Hello World!";
	$msg = new AMQPMessage($data);
	$channel->basic_publish($msg, '', 'hello');
	// uses a 5 second timeout
	$channel->wait_for_pending_acks(5.000);
	$i++;
	echo $i.PHP_EOL;

	$channel->close();
	$connection->close();
}while($i<5);
