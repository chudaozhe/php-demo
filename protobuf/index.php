<?php
require_once __DIR__ . '/../vendor/autoload.php';
include 'GPBMetadata/Person.php';
include 'People/Person.php';
include 'People/Person/address.php';

//序列化
$person = new People\Person();
$person->setName("张三");
$person->setAge("28");
$person->setSex(1);

$person_address = new People\Person\address();
$person_address->setCity('北京');
$person_address->setArea('朝阳');
$person_address->setCode(100000);
$person->setAddress($person_address);

$data = $person->serializeToString();
file_put_contents('data.bin', $data);

//反序列化
//$bindata = file_get_contents('./data.bin');
//$person = new People\Person();
//$person->mergeFromString($bindata);
//echo $person->serializeToJsonString();
//echo json_encode($person->getAddress()->getCity(), JSON_UNESCAPED_UNICODE);
