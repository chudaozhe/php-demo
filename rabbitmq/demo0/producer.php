<?php
require './rabbitmq.php';
$amqp=rabbitmq::factory();
$connection=$amqp->connection();
$channel=$amqp->channel();
//交换机
//$channel->exchange_declare('order_exchange', 'direct', false, false, false);
$channel->queue_declare('order-99', false, true, false, false);
//$channel->queue_bind('order-99', 'order_exchange', 'order-99'); //将队列与某个交换机进行绑定，并使用路由关键字
$channel->basic_publish($amqp->message(json_encode(['id'=>99, 'title'=>'order-99', 'status'=>1], JSON_UNESCAPED_UNICODE)), '', 'order-99');

//for ($i=1; $i<100; $i++){
//    $channel->basic_publish($amqp->message(json_encode(['id'=>$i, 'title'=>'order-'.$i, 'status'=>1], JSON_UNESCAPED_UNICODE)), 'order_exchange', 'order');
//}

$channel->close();
$connection->close();
echo 'ok';