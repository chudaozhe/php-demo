##话题
可以选择性的接收一些东西(给指定用户发消息？)
更灵活，可以使用"#"，"*"
使用topic的routing_key是一个单词，或用点拼接的多个单词

//生产者
```text
php emit_log_topic.php "kern.critical" "A critical kernel error"
php emit_log_topic.php "aaa" "aaa...."
```
//两个消费者
```text
php receive_logs_topic.php "aaa"
php receive_logs_topic.php "#"
php receive_logs_topic.php "kern.*"
php receive_logs_topic.php "*.critical"
php receive_logs_topic.php "kern.*" "*.critical"
```


