##路由
可以选择性的接收一些东西(给指定用户发消息？)

//生产者
```text
php emit_log_direct.php error "Run. Run. Or it will explode."
```
//两个消费者
```text
php receive_logs_direct.php info warning error
php receive_logs_direct.php warning error > logs_from_rabbit.log
```


