<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class rabbitmq {
    private $connection;
    private $channel;
    public $conf=[
        'host'=>'docker-rabbitmq',
        'port'=>5672,
        'user'=>'guest',
        'password'=>'guest'
    ];

    public static function factory(){
        return new self();
    }

    public function message($message, $properties=[]) {
        $properties= array_merge(['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT], $properties);
        return new AMQPMessage($message, $properties);
    }

    public function connection() {
        $this->connection = new AMQPStreamConnection($this->conf['host'], $this->conf['port'], $this->conf['user'], $this->conf['password']);
        return $this->connection;
    }
    /**
     * @param null $channel_id
     * @return AMQPChannel
     */
    public function channel($channel_id = null) {
        $this->channel=$this->connection->channel($channel_id);
        return $this->channel;
    }

//    public function __destruct(){
//        $this->channel->close();
//        $this->connection->close();
//    }

}
