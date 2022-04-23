<?php
require_once __DIR__ . '/../../vendor/autoload.php';

spl_autoload_register('autoload1');
function autoload1($class){
    if(str_contains($class, '\\')){
        $file=__DIR__.'/'.implode('/', explode('\\', $class)).'.php';
        if(is_file($file)) require $file;
    }
}
//raw.bin 从 dy web直播间获取
$bindata = file_get_contents('./raw.bin');
$res = new ProcoApi\Result();
$res->mergeFromString($bindata);

foreach ($res->getMessages() as $message){
    switch ($message->getMethod()){
        case 'WebcastMemberMessage':
            $mmsg=new \ProcoApi\MemberMessage();
//            $mmsg->mergeFromString($message->getPayload());
//            var_dump($mmsg->getUser()->getNickname().': '.$mmsg->getAnchorDisplayText()->getDefaultPattern());
            break;
        case 'WebcastLikeMessage':
            $mmsg=new \ProcoApi\LikeMessage();
            break;
        case 'WebcastGiftMessage':
            $mmsg=new \ProcoApi\GiftMessage();
            break;
        case 'WebcastRoomUserSeqMessage':
            $mmsg=new \ProcoApi\RoomUserSeqMessage();
            break;
        case 'WebcastChatMessage':
            $mmsg=new \ProcoApi\ChatMessage();
            break;
    }

    $mmsg->mergeFromString($message->getPayload());
    echo $mmsg->serializeToJsonString().PHP_EOL;
}
//var_dump($res->getCursor());
