#任务队列
```text
//生产者，一个点代表任务执行时间为1秒
php new_task.php hello...
//3个消费者
php worker.php
php worker.php
php worker.php
...
```
###消息确认 
通过将第四个参数设置为 basic_consume 设置为 false（true 表示没有ack）来打开它们，并在完成任务后发送工人的适当确认。
```text
//work.php
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);
```
###消息持久性
为此，我们将第三个参数传递给 queue_declare 为 true
```text
$channel->queue_declare（'task_queue'，false，true，false，false）；
```
此标志设置为true需要同时应用于生产者和消费者代码。

###公平的分配(Fair dispatch
你可能已经注意到调度仍然没有完全按照我们想要的方式工作。例如，在两个工人的情况下，当所有奇怪的消息都很重，甚至消息也很轻时，一个工人会一直忙，另一个工人几乎不会做任何工作。嗯，RabbitMQ对此一无所知，仍然会均匀地发送消息。
```text
//worker.php
$channel->basic_qos(null, 1, null);
```
