<?php

namespace demo\elasticsearch;

require_once __DIR__ . '/../../vendor/autoload.php';

//删除文档
class deleting {
    use init;

    public function delete()
    {
        $params = [
            'index' => 'my_index',
            'id'    => 'my_id'
        ];
        // Delete doc at /my_index/_doc_/my_id
        $response = $this->cache()->delete($params);
        var_dump($response);
    }
}

$obj=new deleting();
$obj->delete();
