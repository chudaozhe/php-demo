<?php
require __DIR__ . '/../../vendor/autoload.php';
use Pheanstalk\Pheanstalk;

$pheanstalk = Pheanstalk::create('docker-beanstalkd');

// we want jobs from 'testtube' only.
$pheanstalk->watch('testtube');

// this hangs until a Job is produced.
$job = $pheanstalk->reserve();

try {
    $jobPayload = $job->getData();
    // do work.

    sleep(2);
    // If it's going to take a long time, periodically
    // tell beanstalk we're alive to stop it rescheduling the job.
    $pheanstalk->touch($job);
    sleep(2);

    // eventually we're done, delete job.
    $pheanstalk->delete($job);
}
catch(\Exception $e) {
    // handle exception.
    // and let some other worker retry.
    $pheanstalk->release($job);
}