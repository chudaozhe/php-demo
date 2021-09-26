<?php
require './beanstalkd.php';

$pheanstalk = beanstalkd::factory()->handle();

$re=$pheanstalk
    ->useTube('testtube')
    ->put(
        json_encode(['test' => 'data'], JSON_UNESCAPED_UNICODE),
        1024,
        30,
        60
    );
echo json_encode(['id'=>$re->getId(), 'data'=>json_decode($re->getData(), true)], JSON_UNESCAPED_UNICODE);