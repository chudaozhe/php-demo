<?php
require_once __DIR__ . '/../../vendor/autoload.php';


$collection = (new MongoDB\Client('mongodb://root:123456@docker-mongo/'))->images->shop1;

$document=[
    'content' => 'uuuu2',
    'uri'=>'ss.jpg',
    'create_time' => time(),
    'update_time' => 0,
];
$updateResult = $collection->updateOne(
    ['_id' => md5(11)],
    ['$set' => $document],
    ['upsert' => true]
);

printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
printf("Upserted %d document(s)\n", $updateResult->getUpsertedCount());

$upsertedDocument = $collection->findOne([
    '_id' => $updateResult->getUpsertedId(),
]);

var_dump($upsertedDocument);

//$document = $collection->findOne(['_id' => md5(11)]);
//
//var_dump($document->content);
//echo json_encode($document, JSON_UNESCAPED_UNICODE);