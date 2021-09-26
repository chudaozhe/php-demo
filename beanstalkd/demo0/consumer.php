<?php
require './beanstalkd.php';

$pheanstalk = beanstalkd::factory()->handle();
$pheanstalk->watch('testtube');
while (true) {
    $job = $pheanstalk->reserve();

    //echo $job->getData().PHP_EOL;
    //处理任务
    exec('php result.php', $re, $status);
    $data=json_decode($re[0], true);
    if ($data['err']==0) {
        //删除任务
        $pheanstalk->delete($job);
    } else {
        $stats = $pheanstalk->statsJob($job);
        echo date("Y-m-d H:i:s").':'.json_encode($stats)."\n".PHP_EOL;
        if ($stats['releases'] >=0 && $stats['releases'] <=15) {
            //15次以下延时返回队列，通知频率为15s/15s/30s/3m/10m/20m/30m/30m/30m/60m/3h/3h/3h/6h/6h - 总计 24h4m
            $timer = [15,15,30,180,600,1200,1800,1800,1800,3600,10800,10800,10800,21600,21600];
            // $timer = array(2,3,3,4,5,5,5,6,6);
            $pheanstalk->release($job, 0, $timer[$stats['releases']]);

        }else{
            //错误次数过多时 bury
            $pheanstalk->bury($job);
        }

    }

}
// we want jobs from 'testtube' only.
//$pheanstalk->watch('testtube');

// this hangs until a Job is produced.
//$job = $pheanstalk->reserve();
//
//try {
//    $jobPayload = $job->getData();
//    // do work.
//
//    sleep(2);
//    // If it's going to take a long time, periodically
//    // tell beanstalk we're alive to stop it rescheduling the job.
//    $pheanstalk->touch($job);
//    sleep(2);
//
//    // eventually we're done, delete job.
//    $pheanstalk->delete($job);
//}
//catch(\Exception $e) {
//    // handle exception.
//    // and let some other worker retry.
//    $pheanstalk->release($job);
//}