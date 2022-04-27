<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

$server   = 'docker-rabbitmq';
$port     = 1883;
$clientId = 'client-php';
$username = 'guest';
$password = 'guest';
$clean_session = false;

$connectionSettings  = new ConnectionSettings();
$connectionSettings
    ->setUsername($username)
    ->setPassword($password)
    ->setKeepAliveInterval(60)
    // Last Will 遗嘱设置
    ->setLastWillTopic('test/last-will')
    ->setLastWillMessage('client disconnect')
    ->setLastWillQualityOfService(1);


$mqtt = new MqttClient($server, $port, $clientId);

$mqtt->connect($connectionSettings, $clean_session);
printf("client connected\n");

/*
//测试publish
for ($i = 0; $i< 10; $i++) {
    $payload = array(
        'protocol' => 'tcp',
        'date' => date('Y-m-d H:i:s'),
        'url' => 'https://github.com/emqx/MQTT-Client-Examples'
    );
    $mqtt->publish(
    // topic
        'testtopic/12',
        // payload
        json_encode($payload, JSON_UNESCAPED_UNICODE),
        // qos
        0,
        // retain
        true
    );
    printf("msg $i send\n");
    sleep(1);
}
*/

//测试subscribe
$mqtt->subscribe('test-topic', function ($topic, $message) {
    printf("Received message on topic [%s]: %s\n", $topic, $message);
}, 0);
$mqtt->loop(true);
