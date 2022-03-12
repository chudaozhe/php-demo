<?php
require './kafka.php';
$producer = (new kafka([
    'log_level'=>LOG_DEBUG,
    'debug'=>'all',
]))->producer();

// 创建主题实例
$topic = $producer->newTopic('test');
//生产主题数据，此时消息在缓冲区中，并没有真正被推送
//第一个参数是分区。RD_KAFKA_PARTITION_UA代表未赋值，让librdkafka去选择分区。
//第二个参数是信息标记，应该是0或者RD_KAFKA_MSG_F_BLOCK，代表在整个队列上阻塞生产。
for($i=0; $i<100000; $i++){
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode(['id'=>$i, 'test'.$i => 'data'.$i], JSON_UNESCAPED_UNICODE));
}
// 阻塞时间(毫秒)， 0为非阻塞
$producer->poll(0);

// 推送消息，如果不调用此函数，消息不会被发送且会丢失
$result = $producer->flush(5000);
if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
    echo 'Was unable to flush, messages might be lost!';
}
var_dump($result);


