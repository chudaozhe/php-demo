<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Stomp\Client;
use Stomp\SimpleStomp;
use Stomp\Transport\Bytes;

// make a connection
$client = new Client('tcp://docker-rabbitmq:61613');
$client->setLogin('guest', 'guest');
$stomp = new SimpleStomp($client);

// send a message to the queue
$body = ['id'=>99, 'title'=>'order-99', 'status'=>0];
$bytesMessage = new Bytes(json_encode($body, JSON_UNESCAPED_UNICODE));
$stomp->send('order-99', $bytesMessage);
echo 'Sending message: ';
print_r(json_encode($body, JSON_UNESCAPED_UNICODE) . "\n");

//$stomp->subscribe('order-99', 'binary-sub-test', 'client-individual');
//$msg = $stomp->read();
//
//// extract
//if ($msg != null) {
//    echo 'Received message: ';
//    print_r($msg->body . "\n");
//    // mark the message as received in the queue
//    $stomp->ack($msg);
//} else {
//    echo "Failed to receive a message\n";
//}
//
//$stomp->unsubscribe('order-99', 'binary-sub-test');