<?php
require './rabbitmq.php';

$rabbitmq=rabbitmq::factory();
$channel=$rabbitmq->channel();

$channel->queue_declare('order', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
	echo ' [x] Received ', $msg->body, "\n";
    //消息确认
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};
//告诉RabbitMQ不要一次给工人发一条以上的信息。或者，换句话说，在工人处理并确认前一条消息之前，不要向工人发送新消息
$channel->basic_qos(null, 1, null);
$channel->basic_consume('order', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
	$channel->wait();
}

