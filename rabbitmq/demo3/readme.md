##订阅/发布
所有消费者会接收到相同的信息

//停掉消费者，队列里的信息会消失
//生产者
```text
php emit_log.php
```
//两个消费者
```text
php receive_logs.php > logs_from_rabbit.log

php receive_logs.php
```

