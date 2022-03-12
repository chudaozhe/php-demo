<?php
class kafka{
    protected $conf;
    public function __construct($config=[])
    {
        $default_config=[
            'metadata.broker.list'=>'kafka:9092',
        ];
        $config=array_merge($default_config, $config);
        $this->conf = new \RdKafka\Conf();
        foreach($config as $key=>$value){
            $this->conf->set($key, $value);
        }
    }
    public function consumer():\RdKafka\KafkaConsumer{
        return new \RdKafka\KafkaConsumer($this->conf);
    }

    public function producer():\RdKafka\Producer{
        return new \RdKafka\Producer($this->conf);
    }
}