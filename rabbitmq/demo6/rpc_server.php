<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('docker-rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('rpc_queue', false, false, false, false);

function fib($n){
	if ($n == 0) {
		return 0;
	}
	if ($n == 1) {
		return 1;
	}
	return fib($n-1) + fib($n-2);
}

echo " [x] Awaiting RPC requests\n";
$callback = function ($req) {
	$n = intval($req->body);
	echo ' [.] fib(', $n, ")\n";

	$msg = new AMQPMessage((string) fib($n), ['correlation_id' => $req->get('correlation_id')]);

	$req->delivery_info['channel']->basic_publish($msg, '', $req->get('reply_to'));
	//消息确认
	$req->delivery_info['channel']->basic_ack($req->delivery_info['delivery_tag']);
};

////告诉RabbitMQ不要一次给工人发一条以上的信息。或者，换句话说，在工人处理并确认前一条消息之前，不要向工人发送新消息
$channel->basic_qos(null, 1, null);
$channel->basic_consume('rpc_queue', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
	$channel->wait();
}

$channel->close();
$connection->close();