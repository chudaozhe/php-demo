<?php
require __DIR__ . '/../../vendor/autoload.php';

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;

$pheanstalk = Pheanstalk::create('docker-beanstalkd');

// Queue a Job
$pheanstalk
    ->useTube('testtube')
    ->put("job payload goes here\n");

$re=$pheanstalk
    ->useTube('testtube')
    ->put(
        json_encode(['test' => 'data'], JSON_UNESCAPED_UNICODE),  // encode data in payload
        PheanstalkInterface::DEFAULT_PRIORITY,     // default priority
        30, // delay by 30s
        60  // beanstalk will retry job after 60s
    );
echo json_encode(['id'=>$re->getId(), 'data'=>json_decode($re->getData(), true)], JSON_UNESCAPED_UNICODE);