<?php
require './kafka.php';

$consumer = (new kafka([
    'group.id'=>'test-group',
    'enable.auto.commit'=>'false',
    'auto.offset.reset'=>'earliest'
    ]))->consumer();

// 消费者订阅主题，数组形式
$consumer->subscribe(['test']);

while (true) {
    // 消费数据，阻塞120秒(120秒内有数据就消费，没有数据等待120秒进入下一轮循环)
    $message = $consumer->consume(120 * 1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            sleep(1);
            // 业务逻辑，
            var_dump($message);
            // 提交位移
            $consumer->commit($message);
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
    }
}
