<?php
require __DIR__ . '/../../vendor/autoload.php';

use Pheanstalk\Pheanstalk;

class beanstalkd{
    public $conf=[
        'host'=>'docker-beanstalkd',
        'port'=>11300,
        'timeout'=>10,
    ];

    public static function factory(){
        return new self();
    }

    public function handle(): Pheanstalk {
        return Pheanstalk::create($this->conf['host'], $this->conf['port'], $this->conf['timeout']);
    }
}